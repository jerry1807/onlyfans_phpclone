<?php

namespace App\Http\Controllers;

use Mail;
use App\Helper;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Subscriptions;
use App\Models\Notifications;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeController extends Controller
{
  public function __construct(AdminSettings $settings, Request $request) {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  /**
   * Show/Send data Stripe
   *
   * @return response
   */
  protected function show()
  {

    if (! $this->request->expectsJson()) {
        abort(404);
    }

    if (! auth()->user()->hasPaymentMethod()) {
      return response()->json([
        "success" => false,
        'errors' => ['error' => trans('general.please_add_payment_card')]
      ]);
    }

    // Find the user to subscribe
    $user = User::whereVerifiedId('yes')->whereId($this->request->id)->where('id', '<>', Auth::user()->id)->firstOrFail();
    $payment = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->first();
    $stripe = new \Stripe\StripeClient($payment->key_secret);

    // Verify Plan Exists
    try {
      $planCurrent = $stripe->plans->retrieve($user->plan, []);

    } catch (\Exception $exception) {

      // If it does not exist we create the plan
      $plan = $stripe->plans->create([
          'currency' => $this->settings->currency_code,
          'interval' => 'month',
          "product" => [
              "name" => trans('general.subscription_for').' @'.$user->username,
          ],
          'nickname' => $user->plan,
          'id' => $user->plan,
          'amount' => $this->settings->currency_code == 'JPY' ? $user->price : $user->price * 100,
      ]);
    }

      try {

        // Check Payment Incomplete
        if (Auth::user()
          ->userSubscriptions()
            ->where('stripe_plan', $user->plan)
            ->whereStripeStatus('incomplete')
            ->first()
          ) {
              return response()->json([
                "success" => false,
                'errors' => ['error' => trans('general.please_confirm_payment')]
              ]);
            }

        // Create New subscription
        auth()->user()->newSubscription('main', $user->plan)->create();

        // Send Email to User and Notification
        Subscriptions::sendEmailAndNotify(Auth::user()->name, $user->id);

        return response()->json([
          'success' => true,
          'url' => url('buy/subscription/success', $user->username)
        ]);

      } catch (IncompletePayment $exception) {
        return response()->json([
            'success' => true,
            'url' => url('stripe/payment', $exception->payment->id), // Redirect customer to page confirmation payment (SCA)
        ]);
      } catch (\Exception $exception) {

        return response()->json([
          'success' => false,
          'errors' => ['error' => $exception->getMessage()]
        ]);
    }
  }// End Method

}
