<?php

namespace App\Http\Controllers;

use Mail;
use App\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Notifications;
use Fahim\PaypalIPN\PaypalIPNListener;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use Laravel\Cashier\Exceptions\IncompletePayment;


class TipController extends Controller
{

  public function __construct(Request $request, AdminSettings $settings) {
    $this->request = $request;
    $this->settings = $settings::first();
  }

  /**
	 *  Send Tip Request
	 *
	 * @return Response
	 */
  public function send() {

    // Find the User
    $user = User::whereVerifiedId('yes')->whereId($this->request->id)->where('id', '<>', Auth::user()->id)->firstOrFail();

    // Validate Payment Gateway
    Validator::extend('check_payment_gateway', function($attribute, $value, $parameters) {
      return PaymentGateways::find($value);
    });

    // Currency Position
    if ($this->settings->currency_position == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = array (
      'amount.min' => trans('general.amount_minimum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'amount.max' => trans('general.amount_maximum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'payment_gateway_tip.check_payment_gateway' => trans('general.payments_error'),
  );

  //<---- Validation
  $validator = Validator::make($this->request->all(), [
      'amount' => 'required|integer|min:'.$this->settings->min_tip_amount.'|max:'.$this->settings->max_tip_amount,
      'payment_gateway_tip' => 'required|check_payment_gateway',
      ], $messages);

    if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

    if ($this->request->payment_gateway_tip == 1) {
      return $this->sendTipPayPal($user);
    } elseif ($this->request->payment_gateway_tip == 2 && $this->request->payment_method_id) {
      return $this->sendTipStripe($user);
    }

    return response()->json([
      'success' => true,
      'insertBody' => '<i></i>'
    ]);

  } // End method Send

  /**
	 *  Send Tip PayPal
	 *
	 * @return Response
	 */
  protected function sendTipPayPal($user) {

    // Get Payment Gateway
    $payment = PaymentGateways::whereId(1)->whereName('PayPal')->firstOrFail();

    // Find user
    $user = User::find($this->request->id);

    // Verify environment Sandbox or Live
    if ($payment->sandbox == 'true') {
      $action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
      } else {
      $action = "https://www.paypal.com/cgi-bin/webscr";
      }

      $urlSuccess = url('paypal/tip/success', $user->username);
      $urlCancel   = url('paypal/tip/cancel', $user->username);
      $urlPaypalIPN = url('paypal/tip/ipn');

      return response()->json([
                  'success' => true,
                  'insertBody' => '<form id="form_pp" name="_xclick" action="'.$action.'" method="post"  style="display:none">
                  <input type="hidden" name="cmd" value="_xclick">
                  <input type="hidden" name="return" value="'.$urlSuccess.'">
                  <input type="hidden" name="cancel_return"   value="'.$urlCancel.'">
                  <input type="hidden" name="notify_url" value="'.$urlPaypalIPN.'">
                  <input type="hidden" name="currency_code" value="'.$this->settings->currency_code.'">
                  <input type="hidden" name="amount" id="amount" value="'.$this->request->amount.'">
                  <input type="hidden" name="custom" value="id='.$user->id.'&amount='.$this->request->amount.'&sender='.Auth::user()->id.'">
                  <input type="hidden" name="item_name" value="'.__('general.tip_for').' @'.$user->username.'">
                  <input type="hidden" name="business" value="'.$payment->email.'">
                  <input type="submit">
                  </form> <script type="text/javascript">document._xclick.submit();</script>',
              ]);
      } // sendTipPayPal

      /**
       * PayPal IPN
       *
       * @return void
       */
      public function paypalTipIpn() {

        $ipn = new PaypalIPNListener();

  			$ipn->use_curl = false;

        $payment = PaymentGateways::find(1);

  			if ($payment->sandbox == 'true') {
  				// SandBox
  				$ipn->use_sandbox = true;
  				} else {
  				// Real environment
  				$ipn->use_sandbox = false;
  				}

  	    $verified = $ipn->processIpn();

  			$custom  = $_POST['custom'];
  			parse_str($custom, $data);

  			$payment_status = $_POST['payment_status'];
  			$txn_id         = $_POST['txn_id'];

        //========== Processor Fees
        $processorFees = $data['amount'] - (  $data['amount'] * $payment->fee/100 ) - $payment->fee_cents;

        // Earnings Net User
        $earningNetUser = number_format($processorFees - (  $processorFees * $this->settings->fee_commission/100  ), 2);

        // Earnings Net Admin
        $earningNetAdmin = number_format($processorFees - $earningNetUser, 2);

  	    if ($verified) {
  				if ($payment_status == 'Completed') {

  	          // Check outh POST variable and insert in DB
  						$verifiedTxnId = Transactions::where('txn_id', $txn_id)->first();

  			if (! isset($verifiedTxnId)) {

          // Insert Transaction
          $txn = new Transactions;
          $txn->txn_id  = $txn_id;
          $txn->user_id = $data['sender'];
          $txn->subscriptions_id = 0;
          $txn->subscribed = $data['id'];
          $txn->amount   = $data['amount'];
          $txn->earning_net_user  =  $earningNetUser;
          $txn->earning_net_admin = $earningNetAdmin;
          $txn->payment_gateway = 'PayPal';
          $txn->type = 'tip';
          $txn->save();

          // Add Earnings to User
          User::find($data['id'])->increment('balance', $earningNetUser);

          // Send Notification to User --- destination, author, type, target
          Notifications::send($data['id'], $data['sender'], '5', $data['id']);

  			}// <--- Verified Txn ID

  	      } // <-- Payment status
  	    } else {
  	    	//Some thing went wrong in the payment !
  	    }

      }//<----- End Method paypalIpn()

  /**
	 *  Send Tip Stripe
	 *
	 * @return Response
	 */
  protected function sendTipStripe($user)
  {
        // Get Payment Gateway
        $payment = PaymentGateways::whereId(2)->whereName('Stripe')->firstOrFail();

      	$cents  = $this->settings->currency_code == 'JPY' ? $this->request->amount : ($this->request->amount*100);
      	$amount = (int)$cents;
      	$currency_code = $this->settings->currency_code;
      	$description = __('general.tip_for').' @'.$user->username;

        \Stripe\Stripe::setApiKey($payment->key_secret);

        $intent = null;
        try {
          if (isset($this->request->payment_method_id)) {
            # Create the PaymentIntent
            $intent = \Stripe\PaymentIntent::create([
              'payment_method' => $this->request->payment_method_id,
              'amount' => $amount,
              'currency' => $currency_code,
              "description" => $description,
              'confirmation_method' => 'manual',
              'confirm' => true
            ]);
          }
          if (isset($this->request->payment_intent_id)) {
            $intent = \Stripe\PaymentIntent::retrieve(
              $this->request->payment_intent_id
            );
            $intent->confirm();
          }
          return $this->generatePaymentResponse($intent);
        } catch (\Stripe\Exception\ApiErrorException $e) {
          # Display error on client
          return response()->json([
            'error' => $e->getMessage()
          ]);
        }
  } // End Method sendTipStripe

  protected function generatePaymentResponse($intent) {
    # Note that if your API version is before 2019-02-11, 'requires_action'
    # appears as 'requires_source_action'.
    if ($intent->status == 'requires_action' &&
        $intent->next_action->type == 'use_stripe_sdk') {
      # Tell the client to handle the action
      return response()->json([
        'requires_action' => true,
        'payment_intent_client_secret' => $intent->client_secret,
      ]);
    } else if ($intent->status == 'succeeded') {
      # The payment didn’t need any additional actions and completed!
      # Handle post-payment fulfillment

      $user = User::find($this->request->id);

      // Insert DB
      //========== Processor Fees
      $amount = $this->request->amount;
      $payment = PaymentGateways::whereId(2)->whereName('Stripe')->first();
      $processorFees = $amount - ($amount * $payment->fee/100) - $payment->fee_cents;

      // Earnings Net User
      $earningNetUser = $processorFees - ($processorFees * $this->settings->fee_commission/100);
      // Earnings Net Admin
      $earningNetAdmin = $processorFees - $earningNetUser;

      if ($this->settings->currency_code == 'JPY') {
        $userEarning = floor($earningNetUser);
        $adminEarning = floor($earningNetAdmin);
      } else {
        $userEarning = number_format($earningNetUser, 2);
        $adminEarning = number_format($earningNetAdmin, 2);
      }

      // Insert Transaction
      $txn = new Transactions;
      $txn->txn_id  = $intent->id;
      $txn->user_id = auth()->user()->id;
      $txn->subscriptions_id = 0;
      $txn->subscribed = $user->id;
      $txn->amount   = $amount;
      $txn->earning_net_user  =  $userEarning;
      $txn->earning_net_admin = $adminEarning;
      $txn->payment_gateway = 'Stripe';
      $txn->type = 'tip';
      $txn->save();

      // Add Earnings to User
      $user->increment('balance', $userEarning);

      // Send Notification
      Notifications::send($user->id, auth()->user()->id, '5', auth()->user()->id);

      return response()->json([
        "success" => true
      ]);
    } else {
      # Invalid status
      http_response_code(500);
      return response()->json(['error' => 'Invalid PaymentIntent status']);
    }
  }// End generatePaymentResponse
}
