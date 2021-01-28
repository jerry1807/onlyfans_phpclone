<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 |-----------------------------------
 | Index
 |-----------------------------------
 */
Route::get('/', 'HomeController@index')->name('home');

Route::get('home', function() {
	return redirect('/');
});

// Authentication Routes.
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');

// Registration Routes.
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('signup', 'Auth\RegisterController@register');

// Password Reset Routes.
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Contact
Route::view('contact','index.contact');
Route::post('contact','HomeController@contactStore');

// Blog
Route::get('blog', 'BlogController@blog');
Route::get('blog/post/{id}/{slug?}', 'BlogController@post')->name('seo');

// Social Login
Route::group(['middleware' => 'guest'], function() {
	Route::get('oauth/{provider}', 'SocialAuthController@redirect')->where('provider', '(facebook|google|twitter)$');
	Route::get('oauth/{provider}/callback', 'SocialAuthController@callback')->where('provider', '(facebook|google|twitter)$');
});//<--- End Group guest

// Verify Account
Route::get('verify/account/{confirmation_code}', 'HomeController@getVerifyAccount')->where('confirmation_code','[A-Za-z0-9]+');

 // Pages Static Custom
 Route::get('p/{page}','PagesController@show')->where('page','[^/]*' )->name('seo');

 /*
  |-----------------------------------------------
  | Ajax Request
  |--------- -------------------------------------
  */
 Route::get('ajax/donations', 'DonationsController@donations');
 Route::get('ajax/updates', 'UpdatesController@ajaxUpdates');
 Route::get('ajax/user/updates', 'HomeController@ajaxUserUpdates');
 Route::get('loadmore/comments', 'CommentsController@loadmore');

 /*
  |-----------------------------------
  | Subscription
  |--------- -------------------------
  */

 // Paypal IPN
 Route::post('paypal/ipn','PayPalController@paypalIpn');

 Route::get('buy/subscription/success/{user}', function($user){
	 session()->put('subscription_success', trans('general.subscription_success'));
	 return redirect($user);
 	});

 Route::get('buy/subscription/cancel/{user}', function($user){
	 session()->put('subscription_cancel', trans('general.subscription_cancel'));
	 return redirect($user);
 	});

	// Stripe Webhook
	Route::post('stripe/webhook','StripeWebHookController@handleWebhook');

	// Paypal IPN (TIPS)
  Route::post('paypal/tip/ipn','TipController@paypalTipIpn');

  Route::get('paypal/tip/success/{user}', function($user){
 	 session()->put('subscription_success', trans('general.tip_sent_success'));
 	 return redirect($user);
  	});

  Route::get('paypal/tip/cancel/{user}', function($user){
 	 session()->put('subscription_cancel', trans('general.tip_sent_success'));
 	 return redirect($user);
  	});

	// Tip on Messages
   Route::get('paypal/msg/tip/redirect/{id}', function($id){
  	 return redirect('messages/'.$id);
   	});

		// Paypal IPN (Add Funds)
	  Route::post('paypal/add/funds/ipn','AddFundsController@paypalIpn');

 /*
  |-----------------------------------
  | User Views LOGGED
  |--------- -------------------------
  */
 Route::group(['middleware' => 'auth'], function() {

	 // Dashboard
	 Route::get('dashboard','UserController@dashboard');

	 // Buy Subscription
	 Route::post('buy/subscription','SubscriptionsController@buy');

	 // Free Subscription
	 Route::post('subscription/free','SubscriptionsController@subscriptionFree');

	 // Cancel Subscription
	 Route::post('subscription/free/cancel/{id}','SubscriptionsController@cancelFreeSubscription');

	 // Ajax Request
	 Route::post('ajax/like', 'UserController@like');
	 Route::get('ajax/notifications', 'UserController@ajaxNotifications');

	 // Comments
	 Route::post('ajax/delete-comment/{id}', 'CommentsController@destroy');
	 Route::post('comment/store', 'CommentsController@store');

	 // Settings Page
  	Route::get('settings/page','UserController@settingsPage');
  	Route::post('settings/page','UserController@updateSettingsPage');
		Route::post('delete/cover','UserController@deleteImageCover');

		// Subscription Page
   	Route::view('settings/subscription','users.subscription');
   	Route::post('settings/subscription','UserController@saveSubscription');

		// Verify Account
   	Route::get('settings/verify/account','UserController@verifyAccount');
   	Route::post('settings/verify/account','UserController@verifyAccountSend');

		// Delete Account
		Route::view('account/delete','users.delete_account');
   	Route::post('account/delete','UserController@deleteAccount');

	// Notifications
 	Route::get('notifications','UserController@notifications');
	Route::post('notifications/settings','UserController@settingsNotifications');
	Route::post('notifications/delete','UserController@deleteNotifications');

	// Messages
	Route::get('messages', 'MessagesController@inbox');
	// Message Chat
	Route::get('messages/{id}/{username?}', 'MessagesController@messages')->where(array('id' => '[0-9]+'));
	Route::get('loadmore/messages', 'MessagesController@loadmore');
	Route::post('message/send', 'MessagesController@send');
	Route::get('messages/search/creator', 'MessagesController@searchCreator');
	Route::post('message/delete', 'MessagesController@delete');
	Route::get('messages/ajax/chat', 'MessagesController@ajaxChat');
	Route::post('conversation/delete/{id}', 'MessagesController@deleteChat');

	// Upload Avatar
	Route::post('upload/avatar','UserController@uploadAvatar');

	// Upload Cover
	Route::post('upload/cover','UserController@uploadCover');

 	// Password
 	Route::get('settings/password','UserController@password');
 	Route::post('settings/password','UserController@updatePassword');

 	// My subscribers
 	Route::get('my/subscribers','UserController@mySubscribers');

	// My subscriptions
 	Route::get('my/subscriptions','UserController@mySubscriptions');
	Route::post('subscription/cancel/{id}','UserController@cancelSubscription');

	// My payments
	Route::get('my/payments','UserController@myPayments');
	Route::get('my/payments/received','UserController@myPayments');
	Route::get('my/payments/invoice/{id}','UserController@invoice');

	// Payout Method
 	Route::get('settings/payout/method','UserController@payoutMethod');
	Route::post('settings/payout/method/{type}','UserController@payoutMethodConfigure');

	// Withdrawals
 	Route::get('settings/withdrawals','UserController@withdrawals');
	Route::post('settings/withdrawals','UserController@makeWithdrawals');
	Route::post('delete/withdrawal/{id}','UserController@deleteWithdrawal');

 	// Upload Avatar
 	Route::post('upload/avatar','UserController@uploadAvatar');

	// Updates
	Route::post('update/create','UpdatesController@create');
	Route::get('update/edit/{id}','UpdatesController@edit');
	Route::post('update/edit','UpdatesController@postEdit');
	Route::post('update/delete/{id}','UpdatesController@delete');

	// Report Update
	Route::post('report/update/{id}','UpdatesController@report');

	// Report Creator
	Route::post('report/creator/{id}','UserController@reportCreator');

	//======================================= STRIPE ================================//
	Route::get("settings/payments/card", 'UserController@formAddUpdatePaymentCard');
	Route::post("settings/payments/card", 'UserController@addUpdatePaymentCard');

	// Pin Post
	Route::post('pin/post','UpdatesController@pinPost');

	// Dark Mode
	Route::get('mode/{mode}','HomeController@darkMode')->where('mode', '(dark|light)$');

	// Bookmarks
	Route::post('ajax/bookmark','HomeController@addBookmark');
	Route::get('my/bookmarks','UserController@myBookmarks');
	Route::get('ajax/user/bookmarks', 'UpdatesController@ajaxBookmarksUpdates');

	// Downloads Files
	Route::get('download/file/{id}','UserController@downloadFile');

	// Downloads Files
	Route::get('download/message/file/{id}','MessagesController@downloadFileZip');

	// My Wallet
 	Route::get('my/wallet', 'AddFundsController@wallet');

	// Add Funds
	Route::post('add/funds', 'AddFundsController@send');

	// Send Tips
	Route::post('send/tip', 'TipController@send');

 });//<------ End User Views LOGGED


 // Creators
	Route::get('creators/{new?}','HomeController@creators');

	// Category
 	Route::get('category/{slug}/{new?}','HomeController@category')->name('seo');

	// Profile User
 Route::get('{slug}', 'UserController@profile')->where('slug','[A-Za-z0-9\_-]+')->name('profile');
 Route::get('{slug}/{media}', 'UserController@profile')->where('media', '(photos|videos|audio|files)$')->name('profile');

 // Profile User
 Route::get('{slug}/post/{id}', 'UserController@postDetail')->where('slug','[A-Za-z0-9\_-]+')->name('profile');

 /*
  |-----------------------------------
  | Admin Panel
  |--------- -------------------------
  */
 Route::group(['middleware' => 'role'], function() {

     // Upgrades
 	Route::get('update/{version}','UpgradeController@update');

 	// Dashboard
 	Route::get('panel/admin','AdminController@admin');

 	// Settings
 	Route::get('panel/admin/settings','AdminController@settings');
 	Route::post('panel/admin/settings','AdminController@saveSettings');

	// BILLING
	Route::view('panel/admin/billing','admin.billing');
	Route::post('panel/admin/billing','AdminController@billingStore');

	// EMAIL SETTINGS
	Route::view('panel/admin/settings/email','admin.email-settings');
	Route::post('panel/admin/settings/email','AdminController@emailSettings');

	// STORAGE
	Route::view('panel/admin/storage','admin.storage');
	Route::post('panel/admin/storage','AdminController@storage');

	// THEME
	Route::get('panel/admin/theme','AdminController@theme');
	Route::post('panel/admin/theme','AdminController@themeStore');

 	// Limits
 	Route::get('panel/admin/settings/limits','AdminController@settingsLimits');
 	Route::post('panel/admin/settings/limits','AdminController@saveSettingsLimits');

 	//Withdrawals
 	Route::get('panel/admin/withdrawals','AdminController@withdrawals');
 	Route::get('panel/admin/withdrawal/{id}','AdminController@withdrawalsView');
 	Route::post('panel/admin/withdrawals/paid/{id}','AdminController@withdrawalsPaid');

 	// Subscriptions
 	Route::get('panel/admin/subscriptions','AdminController@subscriptions');

	// Transactions
	Route::get('panel/admin/transactions','AdminController@transactions');
	Route::post('panel/admin/transactions/cancel/{id}','AdminController@cancelTransaction');

 	// Members
 	Route::resource('panel/admin/members', 'AdminController',
 		['names' => [
 		    'edit'    => 'user.edit',
 		    'destroy' => 'user.destroy'
 		 ]]
 	);

 	// Pages
 	Route::resource('panel/admin/pages', 'PagesController',
 		['names' => [
 		    'edit'    => 'pages.edit',
 		    'destroy' => 'pages.destroy'
 		 ]]
 	);

	// Verification Requests
 	Route::get('panel/admin/verification/members','AdminController@memberVerification');
 	Route::post('panel/admin/verification/members/{action}/{id}/{user}','AdminController@memberVerificationSend');

 	// Payments Settings
 	Route::get('panel/admin/payments','AdminController@payments');
 	Route::post('panel/admin/payments','AdminController@savePayments');

	Route::get('panel/admin/payments/{id}','AdminController@paymentsGateways');
	Route::post('panel/admin/payments/{id}','AdminController@savePaymentsGateways');

 	// Profiles Social
 	Route::get('panel/admin/profiles-social','AdminController@profiles_social');
 	Route::post('panel/admin/profiles-social','AdminController@update_profiles_social');

 	// Categories
 	Route::get('panel/admin/categories','AdminController@categories');
 	Route::get('panel/admin/categories/add','AdminController@addCategories');
 	Route::post('panel/admin/categories/add','AdminController@storeCategories');
 	Route::get('panel/admin/categories/edit/{id}','AdminController@editCategories')->where(array( 'id' => '[0-9]+'));
 	Route::post('panel/admin/categories/update','AdminController@updateCategories');
 	Route::post('panel/admin/categories/delete/{id}','AdminController@deleteCategories')->where(array( 'id' => '[0-9]+'));

	// Updates
 	Route::get('panel/admin/posts','AdminController@posts');
	Route::post('panel/admin/posts/delete/{id}','AdminController@deletePost');

	// Reports
 	Route::get('panel/admin/reports','AdminController@reports');
	Route::post('panel/admin/reports/delete/{id}','AdminController@deleteReport');

	// Social Login
	Route::view('panel/admin/social-login','admin.social-login');
	Route::post('panel/admin/social-login','AdminController@updateSocialLogin');

	// Google
	Route::get('panel/admin/google','AdminController@google');
	Route::post('panel/admin/google','AdminController@update_google');

	//***** Languages
	Route::get('panel/admin/languages','LangController@index');

	// ADD NEW
	Route::get('panel/admin/languages/create','LangController@create');

	// ADD NEW POST
	Route::post('panel/admin/languages/create','LangController@store');

	// EDIT LANG
	Route::get('panel/admin/languages/edit/{id}','LangController@edit')->where( array( 'id' => '[0-9]+'));

	// EDIT LANG POST
	Route::post('panel/admin/languages/edit/{id}', 'LangController@update')->where(array( 'id' => '[0-9]+'));

	// DELETE LANG
	Route::resource('panel/admin/languages', 'LangController',
		['names' => [
				'destroy' => 'languages.destroy'
		 ]]
	);

	// Maintenance mode
	Route::view('panel/admin/maintenance/mode','admin.maintenance_mode');
	Route::post('panel/admin/maintenance/mode','AdminController@maintenanceMode');

	Route::post('panel/admin/mode', function() {
	    if (Auth::user()->id == 1) {
	        Artisan::call('down', [
			'--message' => trans('admin.msg_maintenance_mode'),
			'--allow' => request()->ip()
		]);
	}

	\Session::flash('success_message', trans('admin.maintenance_mode_on'));
	return redirect('panel/admin/mode');
	});

	Route::post('panel/admin/mode/on', function() {
	    if (Auth::user()->id == 1) {
				Artisan::call('up');
	  }

		\Session::flash('success_message', trans('admin.maintenance_mode_on'));
		return redirect('panel/admin/mode');
	});

	Route::post("ajax/upload/image", "AdminController@uploadImageEditor")->name("upload.image");

	// Blog
	Route::get('panel/admin/blog','AdminController@blog');
  Route::get('panel/admin/blog/delete/{id}','AdminController@deleteBlog');

  // Add Blog Post
  Route::view('panel/admin/blog/create','admin.create-blog');
	Route::post('panel/admin/blog/create','AdminController@createBlogStore');

  // Edit Blog Post
  Route::get('panel/admin/blog/{id}','AdminController@editBlog');
	Route::post('panel/admin/blog/update','AdminController@updateBlog');

	// Resend confirmation email
	Route::get('panel/admin/resend/email/{id}','AdminController@resendConfirmationEmail');

	// Deposits
	Route::get('panel/admin/deposits','AdminController@deposits');
	Route::get('panel/admin/deposits/{id}','AdminController@depositsView');
	Route::post('approve/deposits','AdminController@approveDeposits');
	Route::post('delete/deposits','AdminController@deleteDeposits');

 });
 //==== End Panel Admin

 // Installer Script
 Route::get('install/script','InstallScriptController@requirements');
 Route::get('install/script/database','InstallScriptController@database');
 Route::post('install/script/database','InstallScriptController@store');

// Install Controller (Add-on)
 Route::get('install/{addon}','InstallController@install');

 // Payments Gateways
 Route::get('payment/paypal', 'PayPalController@show')->name('paypal');

 Route::get('payment/stripe', 'StripeController@show')->name('stripe');
 Route::post('payment/stripe/charge', 'StripeController@charge');

// Files Images Post
Route::get('files/storage/{id}/{path}', 'UpdatesController@image')->where(['id' =>'[0-9]+', 'path' => '.*']);

// Files Images Messages
Route::get('files/messages/{id}/{path}', 'UpdatesController@messagesImage')->where(['id' =>'[0-9]+', 'path' => '.*']);

Route::get('lang/{id}', function($id) {

	$lang = App\Models\Languages::where('abbreviation', $id)->firstOrFail();

	Session::put('locale', $lang->abbreviation);

   return back();

})->where(array( 'id' => '[a-z]+'));

// Sitemaps
Route::get('sitemaps.xml', function() {
 return response()->view('index.sitemaps')->header('Content-Type', 'application/xml');
});

// Charts Admin
Route::get('public/admin/js/charts.js', function() {
 return response()->view('admin.charts')->header('Content-Type', 'application/javascript');
})->middleware('role');

// Search Creators
Route::get('search/creators', 'HomeController@searchCreator');

// Explore Creators refresh
Route::post('refresh/creators', 'HomeController@refreshCreators');
