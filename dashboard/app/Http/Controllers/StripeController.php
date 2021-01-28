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

    if ( ! $this->request->expectsJson()) {
        abort(404);
    }

    if ( ! auth()->user()->hasPaymentMethod()) {
      return response()->json([
        "success" => false,
        'errors' => ['error' => trans('general.please_add_payment_card')]
      ]);
    }

      try {

        // Find the user to subscribe
        $user = User::whereVerifiedId('yes')->whereId($this->request->id)->where('id', '<>', Auth::user()->id)->firstOrFail();

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
