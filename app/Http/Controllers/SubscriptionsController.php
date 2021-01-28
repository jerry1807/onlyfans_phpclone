<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriptions;
use App\Models\AdminSettings;
use App\Models\Withdrawals;
use App\Models\Notifications;
use App\Models\Transactions;
use Fahim\PaypalIPN\PaypalIPNListener;
use App\Helper;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGateways;
use Image;


class SubscriptionsController extends Controller
{

  public function __construct(Request $request, AdminSettings $settings) {
    $this->request = $request;
    $this->settings = $settings::first();
  }

  /**
	 * Buy subscription
	 *
	 * @return Response
	 */
  public function buy()
  {
    // Check if subscription exists
    $checkSubscription = Auth::user()->mySubscriptions()->where('user_id', $this->request->id)->whereDate('ends_at', '>=', Carbon::today())->count();

    if ($checkSubscription != 0) {
      return response()->json([
          'success' => false,
          'errors' => ['error' => trans('general.subscription_exists')],
      ]);
    }

    // Find the User
    $user = User::whereVerifiedId('yes')->whereId($this->request->id)->where('id', '<>', Auth::user()->id)->firstOrFail();

  //<---- Validation
  $validator = Validator::make($this->request->all(), [
      'payment_gateway' => 'required',
      'agree_terms' => 'required',
      ]);

    if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

        // Wallet
        if ($this->request->payment_gateway == 'wallet') {
          return $this->sendWallet();
        }

        // Get name of Payment Gateway
        $payment = PaymentGateways::findOrFail($this->request->payment_gateway);

        // Send data to the payment processor
        return redirect()->route(str_slug($payment->name), $this->request->except(['_token']));

  }// End Method Send

  /**
	 * Free subscription
	 *
   */
  public function subscriptionFree()
  {
    // Find user
    $creator = User::whereId($this->request->id)
        ->whereFreeSubscription('yes')
        ->whereVerifiedId('yes')
          ->firstOrFail();

    // Verify subscription exists
    $subscription = Subscriptions::whereUserId(auth()->user()->id)
        ->whereStripePlan($creator->plan)
          ->whereFree('yes')
            ->first();

      if ($subscription) {
        return response()->json([
          'success' => false,
          'error' => trans('general.subscription_exists'),
        ]);
      }

    // Insert DB
    $sql          = new Subscriptions();
    $sql->user_id = auth()->user()->id;
    $sql->stripe_plan = $creator->plan;
    $sql->free = 'yes';
    $sql->save();

    // Send Notification to User --- destination, author, type, target
    Notifications::send($creator->id, auth()->user()->id, '1', $creator->id);

    return response()->json([
      'success' => true,
    ]);
  } // End Method SubscriptionFree

  public function cancelFreeSubscription($id)
  {
    $checkSubscription = auth()->user()->userSubscriptions()->whereId($id)->firstOrFail();
    $creator = User::wherePlan($checkSubscription->stripe_plan)->first();

    // Delete Subscription
    $checkSubscription->delete();

    session()->put('subscription_cancel', trans('general.subscription_cancel'));
    return redirect($creator->username);

  }// End Method cancelFreeSubscription

  /**
	 *  Send Tip Wallet
	 *
	 * @return Response
	 */
   protected function sendWallet()
   {
     // Find user
     $creator = User::whereId($this->request->id)
         ->whereVerifiedId('yes')
           ->firstOrFail();

     $amount = $creator->price;

     if (auth()->user()->wallet < $amount) {
       return response()->json([
         "success" => false,
         "errors" => ['error' => __('general.not_enough_funds')]
       ]);
     }

     $adminFee = $this->settings->fee_commission;

     // Earnings Net User
     $earningNetUser = $amount - ($amount * $adminFee / 100);

     // Earnings Net Admin
     $earningNetAdmin = ($amount - $earningNetUser);

     if ($this->settings->currency_code == 'JPY') {
       $userEarning = floor($earningNetUser);
       $adminEarning = floor($earningNetAdmin);
     } else {
       $userEarning = number_format($earningNetUser, 2);
       $adminEarning = number_format($earningNetAdmin, 2);
     }

     // Insert DB
     $subscription              = new Subscriptions;
     $subscription->user_id     = auth()->user()->id;
     $subscription->stripe_plan = $creator->plan;
     $subscription->ends_at     = now()->add(1, 'month');
     $subscription->save();

     // Insert Transaction
     $txn = new Transactions;
     $txn->txn_id  = 'subw_'.str_random(25);
     $txn->user_id = auth()->user()->id;
     $txn->subscriptions_id = $subscription->id;
     $txn->subscribed = $creator->id;
     $txn->amount   = $amount;
     $txn->earning_net_user  =  $userEarning;
     $txn->earning_net_admin = $adminEarning;
     $txn->payment_gateway = 'Wallet';
     $txn->save();

     // Subtract user funds
     auth()->user()->decrement('wallet', $amount);

     // Add Earnings to User
     $creator->increment('balance', $userEarning);

     // Send Email to User and Notification
     Subscriptions::sendEmailAndNotify(auth()->user()->name, $creator->id);

     return response()->json([
       'success' => true,
       'url' => url('buy/subscription/success', $creator->username)
     ]);

   } // End sendTipWallet

}
