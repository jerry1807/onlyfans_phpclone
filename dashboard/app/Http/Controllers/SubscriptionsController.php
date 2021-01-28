<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriptions;
use App\Models\AdminSettings;
use App\Models\Withdrawals;
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

    // Validate Payment Gateway
    Validator::extend('check_payment_gateway', function($attribute, $value, $parameters) {
      return PaymentGateways::find($value);
    });

    $messages = array (
    'payment_gateway.check_payment_gateway' => trans('general.payments_error'),
  );

  //<---- Validation
  $validator = Validator::make($this->request->all(), [
      'payment_gateway' => 'required|check_payment_gateway',
      'agree_terms' => 'required',
      ], $messages);

    if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

        // Get name of Payment Gateway
        $payment = PaymentGateways::find($this->request->payment_gateway);

        // Send data to the payment processor
        return redirect()->route(str_slug($payment->name), $this->request->except(['_token']));

  }// End Method Send
}
