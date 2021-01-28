<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\AdminSettings;
use App\Models\Subscriptions;
use App\Models\Categories;
use App\Models\Withdrawals;
use App\Models\Notifications;
use App\Models\PaymentGateways;
use App\Models\Comments;
use App\Models\Transactions;
use App\Models\Like;
use App\Models\Blogs;
use App\Models\Updates;
use App\Models\Reports;
use App\Models\VerificationRequests;
use App\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Image;
use Mail;


class AdminController extends Controller
{
	use Traits\UserDelete;

	public function __construct(AdminSettings $settings)
	{
		$this->settings = $settings::first();
	}

	/**
	 * Show Dashboard section
	 *
	 * @return Response
	 */
	public function admin()
	{
		$users               = User::orderBy('id','DESC')->take(4)->get();
		$total_raised_funds  = Transactions::whereApproved('1')->sum('earning_net_admin');
		$total_subscriptions = Subscriptions::count();
		$subscriptions       = Subscriptions::orderBy('id','desc')->take(4)->get();
		$total_posts         = Updates::count();

		// Statistics of the month

		// Today
		$stat_revenue_today = Transactions::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('today')))
		->whereApproved('1')
		 ->sum('earning_net_admin');

		 // Week
	 	$stat_revenue_week = Transactions::whereBetween('created_at', [
	        Carbon::parse()->startOfWeek(),
	        Carbon::parse()->endOfWeek(),
	    ])
			->whereApproved('1')
	 	 ->sum('earning_net_admin');

		 // Month
	 	$stat_revenue_month = Transactions::whereBetween('created_at', [
	        Carbon::parse()->startOfMonth(),
	        Carbon::parse()->endOfMonth(),
	    ])
			->whereApproved('1')
	 	 ->sum('earning_net_admin');

		return view('admin.dashboard', [
			'users' => $users,
			'total_raised_funds' => $total_raised_funds,
			'total_subscriptions' => $total_subscriptions,
			'subscriptions' => $subscriptions,
			'total_posts' => $total_posts,
			'stat_revenue_today' => $stat_revenue_today,
			'stat_revenue_week' => $stat_revenue_week,
			'stat_revenue_month' => $stat_revenue_month
		]);

	}//<--- END METHOD

	/**
	 * Show Members section
	 *
	 * @return Response
	 */
	 public function index(Request $request)
	 {
		 $query = $request->input('q');

		 if ($query != '' && strlen( $query ) > 2) {
			 $data = User::where('name', 'LIKE', '%'.$query.'%')->orderBy('id','desc')->paginate(20);
		 } else {
			 $data = User::orderBy('id','desc')->paginate(20);
		 }
		 return view('admin.members', ['data' => $data, 'query' => $query]);
	 }

	public function edit($id)
	{
		$data = User::findOrFail($id);

		if( $data->id == 1 || $data->id == Auth::user()->id ) {
			\Session::flash('info_message', trans('admin.user_no_edit'));
			return redirect('panel/admin/members');
		}
    	return view('admin.edit-member')->withData($data);

	}//<--- End Method

	public function update($id, Request $request)
	{
    $user = User::findOrFail($id);
		$input = $request->all();

	if(!empty($request->password)){
		$rules = array(
			'name' => 'required|min:3|max:25',
			'email'     => 'required|email|unique:users,email,'.$id,
			 'password' => 'min:6',
			);

			$password = \Hash::make($request->password);

	} else {
		$rules = array(
			'name' => 'required|min:3|max:25',
			'email'     => 'required|email|unique:users,email,'.$id,
			);

			$password = $user->password;
	}

	   $this->validate($request,$rules);

		 if($request->featured == 'yes' && $user->featured_date == '0000-00-00 00:00:00') {
			 $featured_date = Carbon::now();
		 } else {
			 $featured_date = $user->featured_date;
		 }

		 if($request->featured == 'no' && $user->featured_date != '0000-00-00 00:00:00') {
			 $featured_date = '0000-00-00 00:00:00';
		 }

	  $user->name = $request->name;
	  $user->email = $request->email;
	  $user->role = $request->role;
	  $user->password = $password;
		$user->featured = $request->featured;
		$user->featured_date = $featured_date;
      $user->save();

    \Session::flash('success_message', trans('admin.success_update'));

    return redirect('panel/admin/members');

	}//<--- End Method

	public function destroy($id)
	{
		// Find User
    $user = User::findOrFail($id);

  	if ($user->id == 1 || $user->id == Auth::user()->id) {
			return redirect('panel/admin/members');
			exit;
		}

		$this->deleteUser($id);

		return redirect('panel/admin/members');

    }//<--- End Method

	public function settings()
	{
		return view('admin.settings')->withSettings($this->settings);
	}//<--- END METHOD

	public function saveSettings(Request $request)
	{
		$request->validate([
			'title'            => 'required',
			'email_admin'      => 'required',
			'link_terms'       => 'required|url',
			'link_privacy'     => 'required|url',
			'link_cookies'     => 'required|url',
		]);

		$sql                      = AdminSettings::first();
		$sql->title               = $request->title;
		$sql->email_admin         = $request->email_admin;
		$sql->link_terms         = $request->link_terms;
		$sql->link_privacy         = $request->link_privacy;
		$sql->link_cookies         = $request->link_cookies;
		$sql->date_format         = $request->date_format;
		$sql->captcha                = $request->captcha;
		$sql->email_verification = $request->email_verification;
		$sql->registration_active = $request->registration_active;
		$sql->account_verification = $request->account_verification;
		$sql->show_counter = $request->show_counter;
		$sql->widget_creators_featured = $request->widget_creators_featured;
		$sql->save();

		// App Name
		Helper::envUpdate('APP_NAME', ' "'.$request->title.'" ', true);

		\Session::flash('success_message', trans('admin.success_update'));

    	return redirect('panel/admin/settings');

	}//<--- END METHOD

	public function settingsLimits()
	{
		return view('admin.limits')->withSettings($this->settings);
	}//<--- END METHOD

	public function saveSettingsLimits(Request $request)
	{

		$sql                     = AdminSettings::first();
		$sql->file_size_allowed  = $request->file_size_allowed;
		$sql->file_size_allowed_verify_account  = $request->file_size_allowed_verify_account;
		$sql->update_length      = $request->update_length;
		$sql->story_length      = $request->story_length;
		$sql->comment_length     = $request->comment_length;
		$sql->number_posts_show  = $request->number_posts_show;
		$sql->number_comments_show = $request->number_comments_show;
		$sql->save();

		\Session::flash('success_message', trans('admin.success_update'));

    	return redirect('panel/admin/settings/limits');

	}//<--- END METHOD

	public function maintenanceMode(Request $request)
	{

		if (Auth::user()->id == 1 && $request->maintenance_mode == 'on') {
			\Artisan::call('down', [
				'--message' => trans('admin.msg_maintenance_mode'),
				'--allow' => request()->ip()
			]);
		} elseif (Auth::user()->id == 1 && $request->maintenance_mode == 'off') {
			\Artisan::call('up');
		}

		$this->settings->maintenance_mode = $request->maintenance_mode;
		$this->settings->save();

		if ($request->maintenance_mode == 'on') {
			\Session::flash('success_message', trans('admin.maintenance_mode_on'));
		} else {
			\Session::flash('success_message', trans('admin.maintenance_mode_off'));
		}

    	return redirect('panel/admin/maintenance/mode');

	}//<--- END METHOD

	public function profiles_social()
	{
		return view('admin.profiles-social')->withSettings($this->settings);
	}//<--- End Method

	public function update_profiles_social(Request $request)
	{
		$sql = AdminSettings::find(1);

		$rules = array(
            'twitter'    => 'url',
            'facebook'   => 'url',
            'googleplus' => 'url',
            'youtube'   => 'url',
        );

		$this->validate($request, $rules);

	    $sql->twitter       = $request->twitter;
		$sql->facebook      = $request->facebook;
		$sql->pinterest    = $request->pinterest;
		$sql->instagram     = $request->instagram;
		$sql->youtube     = $request->youtube;
		$sql->github     = $request->github;

		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/profiles-social');
	}//<--- End Method

	public function subscriptions()
	{
		$data = Subscriptions::orderBy('id','DESC')->paginate(50);
		return view('admin.subscriptions', ['data' => $data]);
	}//<--- End Method

	public function transactions(Request $request)
	{
		$query = $request->input('q');

		if ($query != '' && strlen( $query ) > 2) {
			$data = Transactions::where('txn_id', 'LIKE', '%'.$query.'%')->orderBy('id','DESC')->paginate(50);
		} else {
			$data = Transactions::orderBy('id','DESC')->paginate(50);
		}

		return view('admin.transactions', ['data' => $data]);
	}//<--- End Method

	public function cancelTransaction($id)
	{
		$transaction = Transactions::whereId($id)->whereApproved('1')->firstOrFail();

		//Subtract user earnings
		User::wherePlan($transaction->subscription()->stripe_plan)->decrement('balance', $transaction->earning_net_user);

		// change status transaction to canceled
		$transaction->approved = '2';
		$transaction->earning_net_user = 0;
		$transaction->earning_net_admin = 0;
		$transaction->save();

		// Cancel subscription
		$subscription = $transaction->subscription();

		if ($subscription->stripe_id == '') {
			$subscription->update(['ends_at' => Carbon::yesterday()]);
		} else {
			$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
			$stripe->subscriptions->cancel($subscription->stripe_id, []);
		}

		\Session::flash('success_message', trans('admin.success_update'));

    return redirect('panel/admin/transactions');
	}


	public function payments()
	{
		return view('admin.payments-settings')->withSettings($this->settings);
	}//<--- End Method

	public function savePayments(Request $request)
	{
		$sql = AdminSettings::first();

		$rules = [
						'currency_code' => 'required|alpha',
						'currency_symbol' => 'required',
						'min_subscription_amount' => 'required|numeric|min:1',
						'max_subscription_amount' => 'required|numeric|min:1'
        ];

		$this->validate($request, $rules);

		$sql->currency_symbol  = $request->currency_symbol;
		$sql->currency_code    = strtoupper($request->currency_code);
		$sql->currency_position = $request->currency_position;
		$sql->min_subscription_amount   = $request->min_subscription_amount;
		$sql->max_subscription_amount   = $request->max_subscription_amount;
		$sql->fee_commission       = $request->fee_commission;
		$sql->amount_min_withdrawal    = $request->amount_min_withdrawal;
		$sql->days_process_withdrawals = $request->days_process_withdrawals;
		$sql->decimal_format           = $request->decimal_format;

		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/payments');
	}//<--- End Method

	public function withdrawals()
	{
		$data = Withdrawals::orderBy('id','DESC')->paginate(50);
		return view('admin.withdrawals', ['data' => $data]);
	}//<--- End Method

	public function withdrawalsView($id)
	{
		$data = Withdrawals::findOrFail($id);
		return view('admin.withdrawal-view', ['data' => $data]);
	}//<--- End Method

	public function withdrawalsPaid(Request $request)
	{
		$data = Withdrawals::findOrFail($request->id);

		$user = $data->user();

		$data->status    = 'paid';
		$data->date_paid = Carbon::now();
		$data->save();

		//<------ Send Email to User ---------->>>
		$amount       = Helper::amountWithoutFormat($data->amount).' '.$this->settings->currency_code;
		$sender       = $this->settings->email_no_reply;
	  $titleSite    = $this->settings->title;
		$fullNameUser = $user->name;
		$_emailUser   = $user->email;

		Mail::send('emails.withdrawal-processed', array(
					'amount'     => $amount,
					'title_site' => $titleSite,
					'fullname'   => $fullNameUser
		),
			function($message) use ($sender, $fullNameUser, $titleSite, $_emailUser)
				{
				    $message->from($sender, $titleSite)
									  ->to($_emailUser, $fullNameUser)
										->subject( trans('general.withdrawal_processed').' - '.$titleSite );
				});
			//<------ Send Email to User ---------->>>

		return redirect('panel/admin/withdrawals');

	}//<--- End Method


	// START
	public function categories()
	{
		$categories      = Categories::orderBy('name')->get();
		$totalCategories = count( $categories );

		return view('admin.categories', compact( 'categories', 'totalCategories' ));
	}//<--- END METHOD

	public function addCategories()
	{
		return view('admin.add-categories');
	}//<--- END METHOD

	public function storeCategories(Request $request) {

		$temp            = 'public/temp/'; // Temp
	  $path            = 'public/img-category/'; // Path General

		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$rules = array(
          'name'        => 'required',
	        'slug'        => 'required|ascii_only|unique:categories',
	        'thumbnail'   => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=30,min_height=30',
        );

		$this->validate($request, $rules);

		if( $request->hasFile('thumbnail') ) {

		$extension       = $request->file('thumbnail')->getClientOriginalExtension();
		$type_mime_image = $request->file('thumbnail')->getMimeType();
		$sizeFile        = $request->file('thumbnail')->getSize();
		$thumbnail       = $request->slug.'-'.Str::random(32).'.'.$extension;

		if( $request->file('thumbnail')->move($temp, $thumbnail) ) {

			$image = Image::make($temp.$thumbnail);

			\File::copy($temp.$thumbnail, $path.$thumbnail);
			\File::delete($temp.$thumbnail);



			}// End File
		} // HasFile

else {
	$thumbnail = '';
}

		$sql              = New Categories;
		$sql->name        = $request->name;
		$sql->slug        = $request->slug;
		$sql->keywords    = $request->keywords;
		$sql->description = $request->description;
		$sql->mode        = $request->mode;
		$sql->image       = $thumbnail;
		$sql->save();

		\Session::flash('success_message', trans('admin.success_add_category'));

    	return redirect('panel/admin/categories');

	}//<--- END METHOD

	public function editCategories($id) {

		$categories        = Categories::find( $id );

		return view('admin.edit-categories')->with('categories',$categories);

	}//<--- END METHOD

	public function updateCategories(Request $request)
	{
		$categories        = Categories::find($request->id);
		$temp            = 'public/temp/'; // Temp
	  $path            = 'public/img-category/'; // Path General

	  if(!isset($categories)) {
			return redirect('panel/admin/categories');
		}

		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$rules = array(
          'name'        => 'required',
	        'slug'        => 'required|ascii_only|unique:categories,slug,'.$request->id,
	        'thumbnail'   => 'mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=30,min_height=30',
	     );

		$this->validate($request, $rules);

		if($request->hasFile('thumbnail')) {

		$extension        = $request->file('thumbnail')->getClientOriginalExtension();
		$type_mime_image   = $request->file('thumbnail')->getMimeType();
		$sizeFile         = $request->file('thumbnail')->getSize();
		$thumbnail        = $request->slug.'-'.Str::random(32).'.'.$extension;

		if($request->file('thumbnail')->move($temp, $thumbnail)) {

			$image = Image::make($temp.$thumbnail);

			\File::copy($temp.$thumbnail, $path.$thumbnail);
			\File::delete($temp.$thumbnail);

			// Delete Old Image
			\File::delete($path.$categories->thumbnail);

			}// End File
		} // HasFile
		else {
			$thumbnail = $categories->image;
		}

		// UPDATE CATEGORY
		$categories->name   = $request->name;
		$categories->slug   = $request->slug;
		$categories->keywords    = $request->keywords;
		$categories->description = $request->description;
		$categories->mode   = $request->mode;
		$categories->image  = $thumbnail;
		$categories->save();

		\Session::flash('success_message', trans('general.success_update'));
		return redirect('panel/admin/categories');

	}//<--- END METHOD

	public function deleteCategories($id)
	{

			$categories   = Categories::findOrFail($id);
			$thumbnail    = 'public/img-category/'.$categories->image; // Path General

			$userCategory = User::where('categories_id', $id)->update(['categories_id' => 0]);

			// Delete Category
			$categories->delete();

			// Delete Thumbnail
			if ( \File::exists($thumbnail) ) {
				\File::delete($thumbnail);
			}//<--- IF FILE EXISTS

			return redirect('panel/admin/categories');
	}//<--- END METHOD

	public function posts()
	{
		$data = Updates::orderBy('id','desc')->paginate(20);
		return view('admin.posts')->withData($data);
	}

	public function deletePost(Request $request)
	{
	  $sql = Updates::findOrFail($request->id);
		$path   = config('path.images');
    $file   = $sql->image;
    $pathVideo   = config('path.videos');
    $fileVideo   = $sql->video;
    $pathMusic   = config('path.music');
    $fileMusic   = $sql->music;

		// Image
    Storage::delete($path.$file);
    // Video
    Storage::delete($pathVideo.$fileVideo);
    // Music
    Storage::delete($pathMusic.$fileMusic);

		// Delete Reports
		$reports = Reports::where('report_id', $request->id)->where('type','update')->get();

		if(isset($reports)){
			foreach($reports as $report){
				$report->delete();
			}
		}

		// Delete Notifications
		Notifications::where('target', $request->id)
			->where('type', '2')
			->orWhere('target', $request->id)
			->where('type', '3')
			->delete();

			// Delete Comments
			$sql->comments()->delete();

			// Delete likes
			Like::where('updates_id', $request->id)->delete();

    $sql->delete();

		return redirect('panel/admin/posts');

	}//<--- End Method

	public function reports()
	{
		$data = Reports::orderBy('id','desc')->get();
		return view('admin.reports')->withData($data);
	}

	public function deleteReport(Request $request) {

		$report = Reports::findOrFail($request->id);
		$report->delete();
		return redirect('panel/admin/reports');

	}//<--- END METHOD

	public function paymentsGateways($id)
	{
		$data = PaymentGateways::findOrFail($id);
		$name = ucfirst($data->name);

		return view('admin.'.str_slug($name).'-settings')->withData($data);
	}//<--- End Method

	public function savePaymentsGateways($id, Request $request)
	{
		$data  = PaymentGateways::findOrFail($id);
		$input = $_POST;

		$this->validate($request, [
            'email'    => 'email',
        ]);

		$data->fill($input)->save();

		// Set Keys on .env file
		if ($id == 2) {
			Helper::envUpdate('STRIPE_KEY', $input['key']);
			Helper::envUpdate('STRIPE_SECRET', $input['key_secret']);
			Helper::envUpdate('STRIPE_WEBHOOK_SECRET', $input['webhook_secret']);
		}

		\Session::flash('success_message', trans('admin.success_update'));

    return back();
	}//<--- End Method

	public function theme()
	{
		return view('admin.theme');

	}//<--- End method

	public function themeStore(Request $request) {

		$temp  = 'public/temp/'; // Temp
	  $path  = 'public/img/'; // Path
		$pathAvatar  = config('path.avatar'); // Path

		$rules = array(
          'logo'   => 'mimes:png',
					'logo_blue'   => 'mimes:png',
					'favicon'   => 'mimes:png',
        );

		$this->validate($request, $rules);

		set_time_limit(0);
		ini_set('memory_limit', '512M');

		//======= LOGO
		if( $request->hasFile('logo') )	{

		$extension = $request->file('logo')->getClientOriginalExtension();
		$file      = 'logo-'.time().'.'.$extension;

		if ($request->file('logo')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->logo);
			}// End File

			$this->settings->logo = $file;
			$this->settings->save();

		} // HasFile

		//======= LOGO BLUE
		if( $request->hasFile('logo_2') ) {

		$extension = $request->file('logo_2')->getClientOriginalExtension();
		$file      = 'logo_2-'.time().'.'.$extension;

		if ($request->file('logo_2')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->logo_2);
			}// End File

			$this->settings->logo_2 = $file;
			$this->settings->save();

		} // HasFile

		//======== FAVICON
		if($request->hasFile('favicon') )	{

		$extension  = $request->file('favicon')->getClientOriginalExtension();
		$file       = 'favicon-'.time().'.'.$extension;

		if ($request->file('favicon')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->favicon);
			}// End File

			$this->settings->favicon = $file;
			$this->settings->save();

		} // HasFile

		//======== Image Header
		if($request->hasFile('index_image_top') )	{

		$extension  = $request->file('index_image_top')->getClientOriginalExtension();
		$file       = 'home_index-'.time().'.'.$extension;

		if ($request->file('index_image_top')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->home_index);
			}// End File

			$this->settings->home_index = $file;
			$this->settings->save();

		} // HasFile

		//======== Background
		if($request->hasFile('background') )	{

		$extension  = $request->file('background')->getClientOriginalExtension();
		$file       = 'background-'.time().'.'.$extension;

		if ($request->file('background')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->background);
			}// End File

			$this->settings->bg_gradient = $file;
			$this->settings->save();

		} // HasFile

		//======== Image on index 1
		if($request->hasFile('image_index_1') )	{

		$extension  = $request->file('image_index_1')->getClientOriginalExtension();
		$file       = 'image_index_1-'.time().'.'.$extension;

		if ($request->file('image_index_1')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->img_1);
			}// End File

			$this->settings->img_1 = $file;
			$this->settings->save();

		} // HasFile

		//======== Image on index 2
		if($request->hasFile('image_index_2') )	{

		$extension  = $request->file('image_index_2')->getClientOriginalExtension();
		$file       = 'image_index_2-'.time().'.'.$extension;

		if ($request->file('image_index_2')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->img_2);
			}// End File

			$this->settings->img_2 = $file;
			$this->settings->save();

		} // HasFile

		//======== Image on index 3
		if($request->hasFile('image_index_3') )	{

		$extension  = $request->file('image_index_3')->getClientOriginalExtension();
		$file       = 'image_index_3-'.time().'.'.$extension;

		if ($request->file('image_index_3')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->img_3);
			}// End File

			$this->settings->img_3 = $file;
			$this->settings->save();

		} // HasFile

		//======== Image on index 4
		if($request->hasFile('image_index_4') )	{

		$extension  = $request->file('image_index_4')->getClientOriginalExtension();
		$file       = 'image_index_4-'.time().'.'.$extension;

		if ($request->file('image_index_4')->move($temp, $file)) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			// Delete old
			\File::delete($path.$this->settings->img_4);
			}// End File

			$this->settings->img_4 = $file;
			$this->settings->save();

		} // HasFile

		//======== Avatar
		if ($request->hasFile('avatar')) {

			$extension  = $request->file('avatar')->getClientOriginalExtension();
			$file       = 'default-'.time().'.'.$extension;

		$imgAvatar  = Image::make($request->file('avatar'))->fit(200, 200, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		})->encode($extension);

		// Copy folder
		Storage::put($pathAvatar.$file, $imgAvatar, 'public');

		// Update Avatar all users
		User::where('avatar', $this->settings->avatar)->update([
					'avatar' => $file
				]);

		// Delete old Avatar
		Storage::delete(config('path.avatar').$this->settings->avatar);

			$this->settings->avatar = $file;
			$this->settings->save();
		} // HasFile

		// Update Color Default
		$this->settings->whereId(1)->update(['color_default' => $request->get('color')]);


		\Artisan::call('cache:clear');
		\Artisan::call('view:clear');

		return redirect('panel/admin/theme')
			 ->with('success_message', trans('admin.success_update'));

	}//<--- End method

	// Google
	public function google()
	{
		return view('admin.google');
	}//<--- END METHOD

	public function update_google(Request $request)
	{
		$sql = $this->settings;
		$sql->google_analytics = $request->google_analytics;
		$sql->save();

		foreach ($request->except(['_token']) as $key => $value) {
			Helper::envUpdate($key, $value);
		}

		\Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/google');
	}//<--- End Method

	// Verification Requests
	public function memberVerification()
	{
		$data = VerificationRequests::orderBy('id','desc')->get();
		return view('admin.verification')->withData($data);
	}

	// Verification Requests Send
	public function memberVerificationSend($action, $id, $user)
	{
			$member = User::findOrFail($user);
			$pathImage = config('path.verification');

			// Data Email Send
			$sender       = $this->settings->email_no_reply;
		  $titleSite    = $this->settings->title;
			$fullNameUser = $member->name;
			$emailUser   = $member->email;

		if ($action == 'approve') {
			$sql = VerificationRequests::whereId($id)->whereUserId($user)->whereStatus('pending')->firstOrFail();
			$sql->status = 'approved';
			$sql->save();

			// Update status verify of user
			$member->verified_id = 'yes';
			$member->save();

			//<------ Send Email to User ---------->>>
			Mail::send('emails.account_verification', array(
				'body' => trans('general.body_account_verification_approved'),
				'title_site' => $titleSite,
				'fullname'   => $fullNameUser
			),
				function($message) use ($sender, $fullNameUser, $titleSite, $emailUser)
					{
					    $message->from($sender, $titleSite)
										  ->to($emailUser, $fullNameUser)
											->subject(trans('general.account_verification_approved').' - '.$titleSite);
					});
				//<------ End Send Email to User ---------->>>

				\Session::flash('success_message', trans('admin.success_update'));
			   return redirect('panel/admin/verification/members');

		} elseif ($action == 'delete') {
			$sql = VerificationRequests::findOrFail($id);

			// Delete Image
			Storage::delete($pathImage.$sql->image);

			$sql->delete();

			// Update status verify of user
			$member->verified_id = 'reject';
			$member->save();

			//<------ Send Email to User ---------->>>
			Mail::send('emails.account_verification', array(
				'body' => trans('general.body_account_verification_reject'),
				'title_site' => $titleSite,
				'fullname'   => $fullNameUser
			),
				function($message) use ($sender, $fullNameUser, $titleSite, $emailUser)
					{
					    $message->from($sender, $titleSite)
										  ->to($emailUser, $fullNameUser)
											->subject(trans('general.account_verification_not_approved').' - '.$titleSite);
					});
				//<------ End Send Email to User ---------->>>

			 \Session::flash('success_message', trans('admin.success_update'));
		   return redirect('panel/admin/verification/members');
		}
	}// End Method

	public function billingStore(Request $request)
	{
		$this->settings->company = $request->company;
		$this->settings->country = $request->country;
		$this->settings->address = $request->address;
		$this->settings->city = $request->city;
		$this->settings->zip = $request->zip;
		$this->settings->vat = $request->vat;
		$this->settings->save();

		\Session::flash('success_message', trans('admin.success_update'));
		return back();

	}

	public function emailSettings(Request $request)
	{
		$request->validate([
				'MAIL_FROM_ADDRESS' => 'required'
			]);

		$this->settings->email_no_reply = $request->MAIL_FROM_ADDRESS;
		$this->settings->save();

		foreach ($request->except(['_token']) as $key => $value) {
			Helper::envUpdate($key, $value);
		}

		\Session::flash('success_message', trans('admin.success_update'));
		return back();

	}

	public function updateSocialLogin(Request $request)
	{

		$this->settings->facebook_login = $request->facebook_login;
		$this->settings->google_login = $request->google_login;
		$this->settings->save();

		foreach ($request->except(['_token']) as $key => $value) {
			Helper::envUpdate($key, $value);
		}

		\Session::flash('success_message', trans('admin.success_update'));
		return back();

	}

	public function storage(Request $request)
	{
		$request->validate([
				'AWS_ACCESS_KEY_ID' => 'required_if:FILESYSTEM_DRIVER,==,s3',
				'AWS_SECRET_ACCESS_KEY' => 'required_if:FILESYSTEM_DRIVER,==,s3',
				'AWS_DEFAULT_REGION' => 'required_if:FILESYSTEM_DRIVER,==,s3',
				'AWS_BUCKET' => 'required_if:FILESYSTEM_DRIVER,==,s3',

				'DOS_ACCESS_KEY_ID' => 'required_if:FILESYSTEM_DRIVER,==,dospace',
				'DOS_SECRET_ACCESS_KEY' => 'required_if:FILESYSTEM_DRIVER,==,dospace',
				'DOS_DEFAULT_REGION' => 'required_if:FILESYSTEM_DRIVER,==,dospace',
				'DOS_BUCKET' => 'required_if:FILESYSTEM_DRIVER,==,dospace',

				'WAS_ACCESS_KEY_ID' => 'required_if:FILESYSTEM_DRIVER,==,wasabi',
				'WAS_SECRET_ACCESS_KEY' => 'required_if:FILESYSTEM_DRIVER,==,wasabi',
				'WAS_DEFAULT_REGION' => 'required_if:FILESYSTEM_DRIVER,==,wasabi',
				'WAS_BUCKET' => 'required_if:FILESYSTEM_DRIVER,==,wasabi',
			]);

		foreach ($request->except(['_token']) as $key => $value) {
			Helper::envUpdate($key, $value);
		}

		\Session::flash('success_message', trans('admin.success_update'));
		return back();

	} // End Method

	public function uploadImageEditor(Request $request)
	{
		if ($request->hasFile('upload')) {

			$path = config('path.admin');

			$validator = Validator::make($request->all(), [
				'upload' => 'required|mimes:jpg,gif,png,jpe,jpeg|max:'.$this->settings->file_size_allowed.'',
						]);

			if ($validator->fails()) {
 	        return response()->json([
 			        'uploaded' => 0,
							'error' => ['message' => trans('general.upload_image_error_editor').' '.Helper::formatBytes($this->settings->file_size_allowed * 1024)],
 			    ]);
 	    } //<-- Validator


        $originName = $request->file('upload')->getClientOriginalName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $request->file('upload')->getClientOriginalExtension();
        $fileName = str_random().'_'.time().'.'.$extension;

				$request->file('upload')->storePubliclyAs($path, $fileName);

        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $url = Storage::url($path.$fileName);
        $msg = 'Image uploaded successfully';
        $response = "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg');</script>";

				return response()->json([ 'fileName' => $fileName, 'uploaded' => true, 'url' => $url, ]);
    }
	}// End Method

	public function blog()
	{
		$data = Blogs::orderBy('id','desc')->paginate(50);
		return view('admin.blog', ['data' => $data]);
	}//<--- End Method

	public function createBlogStore(Request $request)
	{
		$path = config('path.admin');

		$rules = [
            'title'     => 'required',
						'thumbnail' => 'required|dimensions:min_width=650,min_height=430',
						'tags'      => 'required',
						'content'   => 'required',
	     ];

		$this->validate($request, $rules);

		// Image
		if( $request->hasFile('thumbnail') ) {

			$image     =  $request->file('thumbnail');
			$extension = $image->getClientOriginalExtension();
			$thumbnail = str_random(55).'.'.$extension;

		$imageResize  = Image::make($image)->resize(650, null, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		})->encode($extension);

		  Storage::put($path.$thumbnail, $imageResize, 'public');

		} // HasFile Image

		$data = New Blogs;
		$data->slug = str_slug($request->title);
		$data->title = $request->title;
		$data->image = $thumbnail;
		$data->tags = $request->tags;
		$data->content = $request->content;
		$data->user_id = Auth::user()->id;
		$data->save();

		\Session::flash('success_message',trans('admin.success_add'));
		return redirect('panel/admin/blog');

	}//<--- END METHOD

	public function editBlog($id)
	{
		$data = Blogs::findOrFail($id);

		return view('admin.edit-blog', ['data' => $data ]);

	}//<--- End Method

	public function updateBlog(Request $request)
	{
		$data = Blogs::findOrFail($request->id);

		$path = config('path.admin');

		$rules = [
            'title'   => 'required',
						'thumbnail' => 'dimensions:min_width=650,min_height=430',
						'tags'    => 'required',
						'content' => 'required',
	     ];

		$this->validate($request, $rules);

		$thumbnail = $data->image;

		// Image
		if( $request->hasFile('thumbnail') ) {

			$image     =  $request->file('thumbnail');
			$extension = $image->getClientOriginalExtension();
			$thumbnail = str_random(55).'.'.$extension;

		$imageResize  = Image::make($image)->resize(650, null, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		})->encode($extension);

			Storage::put($path.$thumbnail, $imageResize, 'public');

		// Delete Old Thumbnail
		Storage::delete($path.$data->image);

		} // HasFile Image

		$data->title = $request->title;
		$data->slug = str_slug($request->title);
		$data->image = $thumbnail;
		$data->tags = $request->tags;
		$data->content = $request->content;
		$data->save();

		return back()->withSuccessMessage(trans('admin.success_update'));

	}//<--- END METHOD

	public function deleteBlog($id)
	{
		$data = Blogs::findOrFail($id);

		$path = config('path.admin');

		// Delete Old Thumbnail
		Storage::delete($path.$data->image);

		$data->delete();

		return redirect('panel/admin/blog')->withSuccessMessage(trans('admin.blog_deleted'));

	}//<--- END METHOD

}// End Class
