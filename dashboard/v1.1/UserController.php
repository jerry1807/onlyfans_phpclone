<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriptions;
use App\Models\AdminSettings;
use App\Models\Withdrawals;
use App\Models\Updates;
use App\Models\Like;
use App\Models\Notifications;
use App\Models\Reports;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use App\Models\VerificationRequests;
use App\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Image;
use DB;

class UserController extends Controller
{
  use Traits\UserDelete;

  public function __construct(Request $request, AdminSettings $settings) {
    $this->request = $request;
    $this->settings = $settings::first();
  }

  /**
	 * Display dashboard user
	 *
	 * @return Response
	 */
  public function dashboard()
  {
    $earningNetUser = Auth::user()->myPaymentsReceived()->sum('earning_net_user');
    $subscriptionsActive = Auth::user()
      ->mySubscriptions()
        ->where('stripe_id', '=', '')
        ->whereDate('ends_at', '>=', Carbon::today())
        ->orWhere('stripe_status', 'active')
        ->where('stripe_id', '<>', '')
        ->whereStripePlan(Auth::user()->plan)
        ->count();

    $month = date('m');
    $year = date('Y');
    $daysMonth = cal_days_in_month(0, $month, $year);
    $dateFormat = "$year-$month-";

    $monthFormat  = trans("months.$month");
    $currencySymbol = $this->settings->currency_symbol;

    for ($i=1; $i <= $daysMonth; ++$i) {

      $date = date('Y-m-d', strtotime($dateFormat.$i));
      $_subscriptions = Auth::user()->myPaymentsReceived()->whereDate('created_at', '=', $date)->sum('earning_net_user');

      $monthsData[] =  "'$monthFormat $i'";


      $_earningNetUser = $_subscriptions;

      $earningNetUserSum[] = $_earningNetUser;

    }

    $label = implode(',', $monthsData);
    $data = implode(',', $earningNetUserSum);

    return view('users.dashboard', [
          'earningNetUser' => $earningNetUser,
          'subscriptionsActive' => $subscriptionsActive,
          'label' => $label,
          'data' => $data,
          'month' => $monthFormat
        ]);
  }

  public function profile($slug, $media = null)
  {
    $media = request('media');
    $mediaTitle = null;
    $sortPostByTypeMedia = null;

    if (isset($media)) {
      $mediaTitle = trans('general.'.$media.'').' - ';
      $sortPostByTypeMedia = '&media='.$media;
      $media = '/'.$media;
    }

    // All Payments
    $allPayment = PaymentGateways::where('enabled', '1')->get();

    // Stripe Key
      $_stripe = PaymentGateways::where('id', 2)->where('enabled', '1')->select('key')->first();

      $user    = User::where('username','=', $slug)->where('status','active')->firstOrFail();

      if (isset($media)) {
        $query = $user->updates();
      } else {
        $query = $user->updates()->whereFixedPost('0');
      }

      //=== Photos
  		$query->when(request('media') == 'photos', function($q) {
  			$q->where('image', '<>', '');
  		});

      //=== Videos
  		$query->when(request('media') == 'videos', function($q) {
  			$q->where('video', '<>', '');
  		});

      //=== Audio
  		$query->when(request('media') == 'audio', function($q) {
  			$q->where('music', '<>', '');
  		});

      $updates = $query->orderBy('id','desc')->paginate($this->settings->number_posts_show);

      // Check if subscription exists
      if (Auth::check()) {
        $checkSubscription = Auth::user()
          ->userSubscriptions()
            ->where('stripe_plan', $user->plan)
            ->where('stripe_id', '=', '')
            ->whereDate('ends_at', '>=', Carbon::today())
              ->orWhere('stripe_status', 'active')
              ->where('stripe_plan', $user->plan)
              ->where('stripe_id', '<>', '')
              ->whereUserId(Auth::user()->id)
              ->count();

              // Check Payment Incomplete
              $paymentIncomplete = Auth::user()
                ->userSubscriptions()
                  ->where('stripe_plan', $user->plan)
                  ->whereStripeStatus('incomplete')
                  ->first();

      } else {
        $checkSubscription = null;
        $paymentIncomplete = null;
      }

      if ($user->status == 'suspended') {
        abort(404);
      }

      //<<<-- * Redirect the user real name * -->>>
      $uri = request()->path();
      $uriCanonical = $user->username.$media;

      if ($uri != $uriCanonical) {
        return redirect($uriCanonical);
      }

      // Find post pinned
      $findPostPinned = $user->updates()->whereFixedPost('1')->paginate($this->settings->number_posts_show);

      return view('users.profile',[
          'user' => $user,
            'updates' => $updates,
            'findPostPinned' => $findPostPinned,
            '_stripe' => $_stripe,
            'checkSubscription' => $checkSubscription,
            'media' => $media,
            'mediaTitle' => $mediaTitle,
            'sortPostByTypeMedia' => $sortPostByTypeMedia,
            'allPayment' => $allPayment,
            'paymentIncomplete' => $paymentIncomplete
        ]);

  }//<--- End Method

  public function postDetail($slug, $id)
  {

      $user    = User::where( 'username','=', $slug )->where('status','active')->firstOrFail();
      $updates = $user->updates()->whereId($id)->orderBy('id','desc')->paginate(1);

      $users = User::where('status','active')
      ->where('id', '<>', $user->id)
      ->where('id', '<>', Auth::user()->id ?? 0)
      ->whereVerifiedId('yes')
      ->inRandomOrder()
      ->paginate(3);

      if ($user->status == 'suspended' || $updates->count() == 0) {
        abort(404);
      }

      //<<<-- * Redirect the user real name * -->>>
      $uri = request()->path();
      $uriCanonical = $user->username.'/post/'.$updates[0]->id;

      if( $uri != $uriCanonical ) {
        return redirect($uriCanonical);
      }

      return view('users.post-detail',
          ['user' => $user,
          'updates' => $updates,
          'inPostDetail' => true,
          'users' => $users
        ]);

  }//<--- End Method


    public function settings()
    {
        return view('users.settings');
    }

    public function updateSettings()
    {
      $input = $this->request->all();
      $id = Auth::user()->id;

     $validator = Validator::make($input, [
    'profession'  => 'required|min:6|max:100|string',
    'countries_id' => 'required',
    ]);

     if ($validator->fails()) {
         return redirect()->back()
                   ->withErrors($validator)
                   ->withInput();
     }

     $user               = User::find($id);
     $user->profession   = trim(strip_tags($input['profession']));
     $user->countries_id = trim($input['countries_id']);
     $user->email_new_subscriber = $input['email_new_subscriber'] ?? 'no';
     $user->save();

     \Session::flash('status',trans('auth.success_update'));

     return redirect('settings');
    }

    public function notifications()
    {
      // Notifications
      $notifications = DB::table('notifications')
         ->select(DB::raw('
        notifications.id id_noty,
        notifications.type,
        notifications.created_at,
        users.id userId,
        users.username,
        users.name,
        users.avatar,
        updates.id,
        updates.description,
        U2.username usernameAuthor
        '))
        ->leftjoin('users', 'users.id', '=', DB::raw('notifications.author'))
        ->leftjoin('updates', 'updates.id', '=', DB::raw('notifications.target '))
        ->leftjoin('users AS U2', 'U2.id', '=', DB::raw('updates.user_id'))
        ->leftjoin('comments', 'comments.updates_id', '=', DB::raw('notifications.target
        AND comments.user_id = users.id
        AND comments.updates_id = updates.id
        '))
        ->where('notifications.destination', '=',  Auth::user()->id )
        ->where('users.status', '=',  'active' )
        ->groupBy('notifications.id')
        ->orderBy('notifications.id', 'DESC')
        ->paginate(20);

      // Mark seen Notification
      $getNotifications = Notifications::where('destination', Auth::user()->id)->where('status', '0');
      $getNotifications->count() > 0 ? $getNotifications->update(['status' => '1']) : null;

      return view('users.notifications', ['notifications' => $notifications]);
    }

    public function settingsNotifications()
    {
      $notify_new_subscriber = $this->request->notify_new_subscriber ?? 'no';
      $notify_liked_post = $this->request->notify_liked_post ?? 'no';
      $notify_commented_post = $this->request->notify_commented_post ?? 'no';
      $email_new_subscriber = $this->request->email_new_subscriber ?? 'no';

      $user = User::find(Auth::user()->id);
      $user->notify_new_subscriber = $notify_new_subscriber;
      $user->notify_liked_post = $notify_liked_post;
      $user->notify_commented_post = $notify_commented_post;
      $user->email_new_subscriber = $email_new_subscriber;
      $user->save();

      return response()->json([
          'success' => true,
      ]);
    }

    public function deleteNotifications()
    {
      Auth::user()->notifications()->delete();
      return back();
    }

    public function password()
    {
      return view('users.password');
    }//<--- End Method

      public function updatePassword(Request $request)
      {

  	   $input = $request->all();
  	   $id = Auth::user()->id;

  		   $validator = Validator::make($input, [
  			'old_password' => 'required|min:6',
  	     'new_password' => 'required|min:6',
      	]);

  			if ($validator->fails()) {
           return redirect()->back()
  						 ->withErrors($validator)
  						 ->withInput();
  					 }

  	   if (!\Hash::check($input['old_password'], Auth::user()->password) ) {
  		    return redirect('settings/password')->with( array( 'incorrect_pass' => trans('general.password_incorrect') ) );
  		}

  	   $user = User::find($id);
  	   $user->password  = \Hash::make($input[ "new_password"] );
  	   $user->save();

  	   \Session::flash('status',trans('auth.success_update_password'));

  	   return redirect('settings/password');

  	}//<--- End Method

    public function mySubscribers()
    {
      $subscriptions = Auth::user()->mySubscriptions()->orderBy('id','desc')->paginate(20);


      return view('users.my_subscribers')->withSubscriptions($subscriptions);
    }

    public function mySubscriptions()
    {
      $subscriptions = Auth::user()->userSubscriptions()->orderBy('id','desc')->paginate(20);
      return view('users.my_subscriptions')->withSubscriptions($subscriptions);
    }

    public function myPayments()
    {
      if (request()->is('my/payments')) {
        $transactions = Auth::user()->myPayments()->orderBy('id','desc')->paginate(20);
      } elseif (request()->is('my/payments/received')) {
        $transactions = Auth::user()->myPaymentsReceived()->orderBy('id','desc')->paginate(20);
      } else {
        abort(404);
      }

      return view('users.my_payments')->withTransactions($transactions);
    }

    public function payoutMethod()
    {
      return view('users.payout_method');
    }

    public function payoutMethodConfigure()
    {

		if( $this->request->type != 'paypal' && $this->request->type != 'bank' ) {
			return redirect('settings/payout/method');
			exit;
		}

		// Validate Email Paypal
		if( $this->request->type == 'paypal') {
			$rules = array(
	        'email_paypal' => 'required|email|confirmed',
        );

		$this->validate($this->request, $rules);

		$user                  = User::find(Auth::user()->id);
		$user->paypal_account  = $this->request->email_paypal;
		$user->payment_gateway = 'PayPal';
		$user->save();

		\Session::flash('status', trans('admin.success_update'));
		return redirect('settings/payout/method')->withInput();

		}// Validate Email Paypal

		elseif ($this->request->type == 'bank') {

			$rules = array(
	        'bank_details'  => 'required|min:20',
       		 );

		  $this->validate($this->request, $rules);

		   $user                  = User::find(Auth::user()->id);
		   $user->bank            = strip_tags($this->request->bank_details);
		   $user->payment_gateway = 'Bank';
		   $user->save();

			\Session::flash('status', trans('admin.success_update'));
			return redirect('settings/payout/method');
		}

    }//<--- End Method

    public function uploadAvatar()
		{
      $validator = Validator::make($this->request->all(), [
        'avatar' => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=200,min_height=200|max:'.$this->settings->file_size_allowed.'',
      ]);

		   if ($validator->fails()) {
		        return response()->json([
				        'success' => false,
				        'errors' => $validator->getMessageBag()->toArray(),
				    ]);
		    }

		// PATHS
	  $path = config('path.avatar');

		 //<--- HASFILE PHOTO
	    if($this->request->hasFile('avatar'))	{

				$photo     = $this->request->file('avatar');
				$extension = $this->request->file('avatar')->getClientOriginalExtension();
				$avatar    = strtolower(Auth::user()->username.'-'.Auth::user()->id.time().str_random(10).'.'.$extension );

				set_time_limit(0);
				ini_set('memory_limit', '512M');

				$imgAvatar = Image::make($photo)->orientate()->fit(200, 200, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				})->encode($extension);

				// Copy folder
				Storage::put($path.$avatar, $imgAvatar, 'public');

				//<<<-- Delete old image -->>>/
				if (Auth::user()->avatar != $this->settings->avatar) {
					Storage::delete(config('path.avatar').Auth::user()->avatar);
				}

				// Update Database
				Auth::user()->update(['avatar' => $avatar]);

				return response()->json([
				        'success' => true,
				        'avatar' => Storage::url($path.$avatar),
				    ]);
	    }//<--- HASFILE PHOTO
    }//<--- End Method Avatar

    public function settingsPage()
    {
        return view('users.edit_my_page');
    }

    public function updateSettingsPage()
    {

      $input = $this->request->all();
      $id    = Auth::user()->id;

      if($this->settings->currency_position == 'right') {
				$currencyPosition =  2;
			} else {
				$currencyPosition =  null;
			}

      $messages = array (
			'price.min' => trans('users.price_minimum_subscription'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'price.max' => trans('users.price_maximum_subscription'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      "letters" => trans('validation.letters'),
		);

		 Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		// Validate if have one letter
	Validator::extend('letters', function($attribute, $value, $parameters){
    	return preg_match('/[a-zA-Z0-9]/', $value);
	});

  if (auth()->user()->verified_id == 'no') {
    $this->settings->min_subscription_amount = 0;
  } else {
    $this->settings->min_subscription_amount = $this->settings->min_subscription_amount;
  }

      $validator = Validator::make($input, [
        'full_name' => 'required|string|max:100',
        'username'  => 'required|min:3|max:15|ascii_only|alpha_dash|letters|unique:pages,slug|unique:reserved,name|unique:users,username,'.$id,
        'website' => 'url',
        'facebook' => 'url',
        'twitter' => 'url',
        'instagram' => 'url',
        'youtube' => 'url',
        'pinterest' => 'url',
        'github' => 'url',
        'story' => 'required|max:'.$this->settings->story_length.'',
        'price' => 'numeric|min:'.$this->settings->min_subscription_amount.'|max:'.$this->settings->max_subscription_amount.'',
        'countries_id' => 'required',
        'city' => 'max:100',
        'address' => 'max:100',
        'zip' => 'max:20',
     ], $messages);

     if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
          }

      if (auth()->user()->verified_id == 'yes') {
        $this->createPlanStripe();
      }

      $this->request->story = trim(Helper::spaces($this->request->story));

      $user                  = User::find($id);
      $user->name            = strip_tags($this->request->full_name);
      $user->username        = trim($this->request->username);
      $user->website         = trim($this->request->website);
      $user->categories_id   = $this->request->categories_id;
      $user->profession      = $this->request->profession;
      $user->countries_id    = $this->request->countries_id;
      $user->city            = $this->request->city;
      $user->address         = $this->request->address;
      $user->zip             = $this->request->zip;
      $user->company         = $this->request->company;
      $user->story           = Helper::removeBR($this->request->story);
      $user->price           = $this->request->price;
      $user->facebook         = trim($this->request->facebook);
      $user->twitter         = trim($this->request->twitter);
      $user->instagram         = trim($this->request->instagram);
      $user->youtube         = trim($this->request->youtube);
      $user->pinterest         = trim($this->request->pinterest);
      $user->github         = trim($this->request->github);
      $user->plan           = 'user_'.auth()->user()->id;
      $user->save();

      \Session::flash('status', trans('admin.success_update'));
			return redirect('settings/page');

    }//<--- End Method

  protected function createPlanStripe()
  {
    $payment = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->first();
    $plan = 'user_'.auth()->user()->id;

    if ($payment) {
      if ($this->request->price != auth()->user()->price) {
        $stripe = new \Stripe\StripeClient($payment->key_secret);

        try {
          $planCurrent = $stripe->plans->retrieve($plan, []);

          // Delete old plan
          $stripe->plans->delete($plan, []);

          // Delete Product
          $stripe->products->delete($planCurrent->product, []);
        } catch (\Exception $exception) {
          // not exists
        }

        // Create Plan
        $plan = $stripe->plans->create([
            'currency' => $this->settings->currency_code,
            'interval' => 'month',
            "product" => [
                "name" => trans('general.subscription_for').' @'.auth()->user()->username,
            ],
            'nickname' => $plan,
            'id' => $plan,
            'amount' => $this->settings->currency_code == 'JPY' ? $this->request->price : $this->request->price * 100,
        ]);
      }
    }
  }


   public function uploadCover(Request $request)
   {
     $settings  = AdminSettings::first();

     $validator = Validator::make($this->request->all(), [
       'image' => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=800,min_height=400|max:'.$settings->file_size_allowed.'',
     ]);

      if ($validator->fails()) {
           return response()->json([
               'success' => false,
               'errors' => $validator->getMessageBag()->toArray(),
           ]);
       }

   // PATHS
   $path = config('path.cover');

    //<--- HASFILE PHOTO
     if ($this->request->hasFile('image') )	{

       $photo       = $this->request->file('image');
       $widthHeight = getimagesize($photo);
       $extension   = $photo->getClientOriginalExtension();
       $cover       = strtolower(Auth::user()->username.'-'.Auth::user()->id.time().str_random(10).'.'.$extension );

       set_time_limit(0);
       ini_set('memory_limit', '512M');

       //=============== Image Large =================//
       $width     = $widthHeight[0];
       $height    = $widthHeight[1];
       $max_width = $width < $height ? 800 : 1500;

       if ($width > $max_width) {
         $coverScale = $max_width / $width;
       } else {
         $coverScale = 1;
       }

       $scale    = $coverScale;
       $widthCover = ceil($width * $scale);

       $imgCover = Image::make($photo)->orientate()->resize($widthCover, null, function ($constraint) {
         $constraint->aspectRatio();
         $constraint->upsize();
       })->encode($extension);

       // Copy folder
       Storage::put($path.$cover, $imgCover, 'public');

       //<<<-- Delete old image -->>>/
         Storage::delete(config('path.cover').Auth::user()->cover);

       // Update Database
       Auth::user()->update(['cover' => $cover]);

       return response()->json([
               'success' => true,
               'cover' => Storage::url($path.$cover),
           ]);

     }//<--- HASFILE PHOTO
   }//<--- End Method Cover

    public function withdrawals()
    {
      $withdrawals = Auth::user()->withdrawals()->orderBy('id','desc')->paginate(20);

      return view('users.withdrawals')->withWithdrawals($withdrawals);
    }

    public function makeWithdrawals()
    {
      if (Auth::user()->balance >= $this->settings->amount_min_withdrawal
          && Auth::user()->payment_gateway != ''
          && Withdrawals::where('user_id', Auth::user()->id
          )
          ->where('status','pending')
          ->count() == 0) {

        if (Auth::user()->payment_gateway == 'PayPal') {
   		   	$_account = Auth::user()->paypal_account;
   		   } else {
   		   	$_account = Auth::user()->bank;
   		   }

 			$sql           = new Withdrawals;
 			$sql->user_id  = Auth::user()->id;
 			$sql->amount   = Auth::user()->balance;
 			$sql->gateway  = Auth::user()->payment_gateway;
 			$sql->account  = $_account;
 			$sql->save();

      // Remove Balance the User
      $userBalance = User::find(Auth::user()->id);
      $userBalance->balance = 0;
      $userBalance->save();

      }

      return redirect('settings/withdrawals');
    } // End Method makeWithdrawals

    public function deleteWithdrawal()
    {
  		$withdrawal = Auth::user()->withdrawals()
      ->whereId($this->request->id)
      ->whereStatus('pending')
      ->firstOrFail();

      // Add Balance the User again
      User::find(Auth::user()->id)->increment('balance', $withdrawal->amount);

			$withdrawal->delete();

			return redirect('settings/withdrawals');

    }//<--- End Method

    public function deleteImageCover()
    {
      $path  = 'public/cover/';
      $id    = Auth::user()->id;

      // Image Cover
  		$image = $path.Auth::user()->cover;

      if (\File::exists($image)) {
        \File::delete($image);
      }

      $user = User::find($id);
      $user->cover = '';
      $user->save();

      return response()->json([
              'success' => true,
          ]);
    }// End Method

    public function reportCreator(Request $request)
    {
  		$data = Reports::firstOrNew(['user_id' => Auth::user()->id, 'report_id' => $request->id]);

  		if( $data->exists ) {
  			\Session::flash('noty_error','error');
  			return redirect()->back();
  		} else {

  			$data->type = 'user';
        $data->reason = $request->reason;
  			$data->save();
  		  \Session::flash('noty_success','success');
  			return redirect()->back();
  		}
  	}//<--- End Method

    public function like(Request $request){

  		$like = Like::firstOrNew(['user_id' => Auth::user()->id, 'updates_id' => $request->id]);

  		$user = Updates::find($request->id);

  		if ($like->exists) {

  			   $notifications = Notifications::where('destination', $user->user_id)
  			   ->where('author', Auth::user()->id)
  			   ->where('target', $request->id)
  			   ->where('type','2')
  			   ->first();

  				// IF ACTIVE DELETE FOLLOW
  				if ($like->status == '1') {
            $like->status = '0';
  					$like->update();

            	// DELETE NOTIFICATION
  				if (isset($notifications)) {
            $notifications->status = '1';
            $notifications->update();
          }

  				// ELSE ACTIVE AGAIN
  				} else {
  					$like->status = '1';
  					$like->update();

            // ACTIVE NOTIFICATION
  					if (isset($notifications)) {
              $notifications->status = '0';
              $notifications->update();
            }
  				}

  		} else {

  			// INSERT
  			$like->save();

  			// Send Notification //destination, author, type, target
  			if ($user->user_id != Auth::user()->id && $user->notify_liked_post == 'yes') {
  				Notifications::send($user->user_id, Auth::user()->id, '2', $request->id);
  			}
  		}

      $totalLike = Helper::formatNumber($user->likes()->count());

      return $totalLike;
  	}//<---- End Method

    public function ajaxNotifications()
    {
  		 if (request()->ajax()) {
  			// Notifications
  			$notifications_count = Auth::user()->notifications()->where('status', '0')->count();
        // Messages
  			$messages_count = Auth::user()->messagesInbox();

  			return response()->json([
          'messages' => $messages_count,
          'notifications' => $notifications_count
        ]);

  		   } else {
  				return response()->json(['error' => 1]);
  			}
     }//<---- * End Method

     public function verifyAccount()
     {
       return view('users.verify_account');
     }//<---- * End Method

     public function verifyAccountSend()
     {
       $checkRequest = VerificationRequests::whereUserId(Auth::user()->id)->whereStatus('pending')->first();

       if($checkRequest) {
         return redirect()->back()
     				->withErrors([
     					'errors' => trans('admin.pending_request_verify'),
     				]);
       } elseif (Auth::user()->verified_id == 'reject') {
         return redirect()->back()
     				->withErrors([
     					'errors' => trans('admin.rejected_request'),
     				]);
       }

       $input = $this->request->all();

      $validator = Validator::make($input, [
        'address'  => 'required',
        'city' => 'required',
        'zip' => 'required',
        'image' => 'required|mimes:jpg,gif,png,jpe,jpeg|max:'.$this->settings->file_size_allowed_verify_account.'',
     ]);

      if ($validator->fails()) {
          return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
      }

      // PATHS
  		$path = config('path.verification');

      if ($this->request->hasFile('image')) {

			$extension = $this->request->file('image')->getClientOriginalExtension();
			$fileImage = strtolower(Auth::user()->id.time().Str::random(40).'.'.$extension);

      $this->request->file('image')->storePubliclyAs($path, $fileImage);

	   }//<====== End HasFile

      $sql          = new VerificationRequests;
 			$sql->user_id = Auth::user()->id;
 			$sql->address = $input['address'];
 			$sql->city    = $input['city'];
      $sql->zip     = $input['zip'];
      $sql->image   = $fileImage;
 			$sql->save();

      \Session::flash('status', trans('general.send_success_verification'));

      return redirect('settings/verify/account');
     }

     public function invoice($id)
     {
       $data = Transactions::whereId($id)->where('user_id', Auth::user()->id)->whereApproved('1')->firstOrFail();

       if(Auth::user()->address == ''
         || Auth::user()->city == ''
         || Auth::user()->zip == ''
         || Auth::user()->name == ''
     ) {
       return back()->withErrorMessage('Error');
     }

   		return view('users.invoice')->withData($data);
     }

     public function formAddUpdatePaymentCard()
     {
       $payment = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->firstOrFail();
       \Stripe\Stripe::setApiKey($payment->key_secret);

       return view('users.add_payment_card', [
         'intent' => auth()->user()->createSetupIntent(),
         'key' => $payment->key
       ]);
     }// End Method

     public function addUpdatePaymentCard()
     {
       $payment = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->firstOrFail();
       \Stripe\Stripe::setApiKey($payment->key_secret);

       if (! $this->request->payment_method) {
         return response()->json([
           "success" => false
         ]);
       }

       if ( ! auth()->user()->hasPaymentMethod()) {
           auth()->user()->createAsStripeCustomer();
       }

       try {
         auth()->user()->deletePaymentMethods();
       } catch (\Exception $e) {
         // error
       }

       auth()->user()->updateDefaultPaymentMethod($this->request->payment_method);
       auth()->user()->save();

       return response()->json([
         "success" => true
       ]);
     }// End Method

     public function cancelSubscription($id)
     {
       $checkSubscription = auth()->user()->userSubscriptions()->whereStripeId($id)->firstOrFail();
       $payment = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->firstOrFail();

       $stripe = new \Stripe\StripeClient($payment->key_secret);

       try {
         $stripe->subscriptions->cancel($id, []);
       } catch (\Exception $e) {
         return back()->withErrorMessage($e->getMessage());
       }

       return back()->withMessage(trans('general.subscription_canceled'));

     }// End Method

     // Delete Account
     public function deleteAccount()
     {
       if (!\Hash::check($this->request->password, Auth::user()->password) ) {
  		    return back()->with(['incorrect_pass' => trans('general.password_incorrect')]);
  		}
       if (Auth::user()->id == 1) {
         return redirect('settings/page');
       }

       $this->deleteUser(Auth::user()->id);

       return redirect('/');
     }

     // My Bookmarks
     public function myBookmarks()
     {
       $bookmarks = auth()->user()->bookmarks()->orderBy('bookmarks.id','desc')->paginate($this->settings->number_posts_show);

       $users = User::where('status','active')
       ->where('id', '<>', Auth::id())
       ->where('id', '<>', Auth::user()->id ?? 0)
       ->whereVerifiedId('yes')
       ->inRandomOrder()
       ->paginate(3);

       return view('users.bookmarks', ['updates' => $bookmarks, 'users' => $users]);
     }
}
