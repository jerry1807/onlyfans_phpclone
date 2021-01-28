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
use App\Models\Conversations;
use App\Models\Messages;
use App\Models\Deposits;


class AddFundsController extends Controller
{

  public function __construct(Request $request, AdminSettings $settings) {
    $this->request = $request;
    $this->settings = $settings::first();
  }

  /**
	 *  Wallet View
	 *
	 * @return Response
	 */
  public function wallet()
  {
    $data = Deposits::whereUserId(auth()->user()->id)->orderBy('id', 'desc')->paginate(20);

    return view('users.wallet')->withData($data);
  }

  /**
	 *  Add Funds Request
	 *
	 * @return Response
	 */
  public function send() {

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
      'payment_gateway.check_payment_gateway' => trans('general.payments_error'),
      'image.required_if' => trans('general.please_select_image'),
  );

  //<---- Validation
  $validator = Validator::make($this->request->all(), [
      'amount' => 'required|integer|min:'.$this->settings->min_deposits_amount.'|max:'.$this->settings->max_deposits_amount,
      'payment_gateway' => 'required|check_payment_gateway',
      'image' => 'required_if:payment_gateway,==,3|mimes:jpg,gif,png,jpe,jpeg|max:'.$this->settings->file_size_allowed_verify_account.'',
      ], $messages);

    if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

    // PayPal
    if ($this->request->payment_gateway == 1) {
      return $this->sendPayPal();
    }

    // Stripe
    if ($this->request->payment_gateway == 2
        && $this->request->payment_method_id
        || $this->request->payment_gateway == 2
        && $this->request->payment_intent_id
      ) {
      return $this->sendStripe();
    }

    // Bank Transfer
    if ($this->request->payment_gateway == 3) {
      return $this->sendBankTransfer();
    }

    return response()->json([
      'success' => true,
      'insertBody' => '<i></i>'
    ]);

  } // End method Send

  /**
	 *  Add funds PayPal
	 *
	 * @return Response
	 */
  protected function sendPayPal() {

    // Get Payment Gateway
    $payment = PaymentGateways::whereId(1)->whereName('PayPal')->firstOrFail();

    // Verify environment Sandbox or Live
    if ($payment->sandbox == 'true') {
      $action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
      } else {
      $action = "https://www.paypal.com/cgi-bin/webscr";
      }

      $urlSuccess = url('my/wallet');
      $urlCancel   = url('my/wallet');

      $urlPaypalIPN = url('paypal/add/funds/ipn');

      $feePayPal   = $payment->fee;
      $centsPayPal =  $payment->fee_cents;

      $amountFixed = number_format($this->request->amount + ($this->request->amount * $feePayPal / 100) + $centsPayPal, 2, '.', ',');

      return response()->json([
                  'success' => true,
                  'insertBody' => '<form id="form_pp" name="_xclick" action="'.$action.'" method="post"  style="display:none">
                  <input type="hidden" name="cmd" value="_xclick">
                  <input type="hidden" name="return" value="'.$urlSuccess.'">
                  <input type="hidden" name="cancel_return"   value="'.$urlCancel.'">
                  <input type="hidden" name="notify_url" value="'.$urlPaypalIPN.'">
                  <input type="hidden" name="currency_code" value="'.$this->settings->currency_code.'">
                  <input type="hidden" name="amount" id="amount" value="'.$amountFixed.'">
                  <input type="hidden" name="custom" value="id='.Auth::user()->id.'&amount='.$this->request->amount.'">
                  <input type="hidden" name="item_name" value="'.__('general.add_funds').' @'.Auth::user()->username.'">
                  <input type="hidden" name="business" value="'.$payment->email.'">
                  <input type="submit">
                  </form> <script type="text/javascript">document._xclick.submit();</script>',
              ]);
      } // sendPayPal

      /**
       * PayPal IPN
       *
       * @return void
       */
      public function paypalIpn() {

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

  	    if ($verified) {
  				if ($payment_status == 'Completed') {

  	          // Check outh POST variable and insert in DB
  						$verifiedTxnId = Transactions::where('txn_id', $txn_id)->first();

  			if (! isset($verifiedTxnId)) {

          $sql = new Deposits;
  		   	$sql->user_id = $data['id'];
  			  $sql->txn_id = $txn_id;
  				$sql->amount = $data['amount'];
  				$sql->payment_gateway = 'PayPal';
  			  $sql->save();

  				//Add Funds to User
  				User::find($data['id'])->increment('wallet', $data['amount']);

  			}// <--- Verified Txn ID

  	      } // <-- Payment status
  	    } else {
  	    	//Some thing went wrong in the payment !
  	    }

      }//<----- End Method paypalIpn()

  /**
	 *  Add funds Stripe
	 *
	 * @return Response
	 */
  protected function sendStripe()
  {
        // Get Payment Gateway
        $payment = PaymentGateways::whereId(2)->whereName('Stripe')->firstOrFail();

        $feeStripe   = $payment->fee;
      	$centsStripe =  $payment->fee_cents;

        if ($this->settings->currency_code == 'JPY') {
          $amountFixed = round($this->request->amount + ($this->request->amount * $feeStripe / 100) + $centsStripe);
        } else {
          $amountFixed = number_format($this->request->amount + ($this->request->amount * $feeStripe / 100) + $centsStripe, 2, '.', ',');
        }

      	$amountGross = ($this->request->amount);
      	$amount   = $this->settings->currency_code == 'JPY' ? $amountFixed : ($amountFixed*100);

      	$currency_code = $this->settings->currency_code;
      	$description = __('general.add_funds').' @'.auth()->user()->username;

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
  } // End Method sendStripe

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

      // Insert DB
      $sql          = new Deposits;
      $sql->user_id = Auth::user()->id;
      $sql->txn_id  = $intent->id;
      $sql->amount  = $this->request->amount;
      $sql->payment_gateway = 'Stripe';
      $sql->save();

      //Add Funds to User
      User::find(Auth::user()->id)->increment('wallet', $this->request->amount);

      return response()->json([
        "success" => true
      ]);
    } else {
      # Invalid status
      http_response_code(500);
      return response()->json(['error' => 'Invalid PaymentIntent status']);
    }
  }// End generatePaymentResponse

  public function sendBankTransfer()
  {
    // PATHS
    $path = config('path.admin');

    if ($this->request->hasFile('image')) {

    $extension = $this->request->file('image')->getClientOriginalExtension();
    $fileImage = 'bt_'.strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);

    $this->request->file('image')->storePubliclyAs($path, $fileImage);

   }//<====== End HasFile

    $sql = new Deposits;
    $sql->user_id = auth()->user()->id;
    $sql->txn_id = 'bt_'.str_random(25);
    $sql->amount = $this->request->amount;
    $sql->payment_gateway = 'Bank Transfer';
    $sql->status = 'pending';
    $sql->screenshot_transfer = $fileImage;
    $sql->save();

    return response()->json([
      "success" => true,
      "status" => 'pending',
      'status_info' => __('general.pending_deposit')
    ]);

  } // End method sendBankTransfer


}
