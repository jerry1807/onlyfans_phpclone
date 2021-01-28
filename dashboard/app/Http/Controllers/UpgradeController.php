<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Subscriptions;
use App\Models\Updates;
use App\Models\PaymentGateways;

class UpgradeController extends Controller {

	public function __construct(AdminSettings $settings, Updates $updates, User $user) {
		$this->settings  = $settings::first();
		$this->user      = $user::first();
		$this->updates   = $updates::first();
 }

 /**
	* Move a file
	*
	*/
 private static function moveFile($file, $newFile, $copy)
 {
	 if (File::exists($file) && $copy == false) {
		 	 File::delete($newFile);
			 File::move($file, $newFile);
	 } else if(File::exists($newFile) && isset($copy)) {
			 File::copy($newFile, $file);
	 }
 }

 /**
	* Copy a directory
	*
	*/
 private static function moveDirectory($directory, $destination, $copy)
 {
	 if (File::isDirectory($directory) && $copy == false) {
			 File::moveDirectory($directory, $destination);
	 } else if(File::isDirectory($destination) && isset($copy)) {
			 File::copyDirectory($destination, $directory);
	 }
 }

	public function update($version)
	{
		$DS = DIRECTORY_SEPARATOR;

		$APP = app_path().$DS;
		$MODELS = app_path('Models').$DS;
		$CONTROLLERS = app_path('Http'. $DS . 'Controllers').$DS;
		$CONTROLLERS_AUTH = app_path('Http'. $DS . 'Controllers'. $DS . 'Auth').$DS;
		$TRAITS = app_path('Http'. $DS . 'Controllers'. $DS . 'Traits').$DS;

		$CONFIG = config_path().$DS;
		$ROUTES = base_path('routes').$DS;

		$PUBLIC_JS = public_path('js').$DS;
		$PUBLIC_CSS = public_path('css').$DS;
		$PUBLIC_IMG = public_path('img').$DS;
		$PUBLIC_IMG_ICONS = public_path('img'.$DS.'icons').$DS;

		$VIEWS = resource_path('views').$DS;
		$VIEWS_ADMIN = resource_path('views'. $DS . 'admin').$DS;
		$VIEWS_AJAX = resource_path('views'. $DS . 'ajax').$DS;
		$VIEWS_AUTH = resource_path('views'. $DS . 'auth').$DS;
		$VIEWS_EMAILS = resource_path('views'. $DS . 'emails').$DS;
		$VIEWS_ERRORS = resource_path('views'. $DS . 'errors').$DS;
		$VIEWS_INCLUDES = resource_path('views'. $DS . 'includes').$DS;
		$VIEWS_INDEX = resource_path('views'. $DS . 'index').$DS;
		$VIEWS_LAYOUTS = resource_path('views'. $DS . 'layouts').$DS;
		$VIEWS_PAGES = resource_path('views'. $DS . 'pages').$DS;
		$VIEWS_USERS = resource_path('views'. $DS . 'users').$DS;

		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.trans('admin.upgrade_done').' <a style="text-decoration: none; color: #F50;" href="'.url('/').'">'.trans('error.go_home').'</a></h2>';

		if ($version == '1.1') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = true;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version $oldVersion</h2>";
			}

			//============== Files Affected ================//
			$file1 = 'Helper.php';
			$file2 = 'UserController.php';
			$file3 = 'StripeWebHookController.php';

			$file4 = 'Messages.php';
			$file5 = 'Comments.php';
			$file6 = 'Notifications.php';

			$file7 = 'edit_my_page.blade.php';
			$file8 = 'blog.blade.php';
			$file9 = 'posts.blade.php';
			$file10 = 'updates.blade.php';

			$file11 = 'app-functions.js';


			//============== Moving Files ================//
			$this->moveFile($path.$file1, $APP.$file1, $copy);
			$this->moveFile($path.$file2, $CONTROLLERS.$file2, $copy);
			$this->moveFile($path.$file3, $CONTROLLERS.$file3, $copy);

			$this->moveFile($path.$file4, $MODELS.$file4, $copy);
			$this->moveFile($path.$file5, $MODELS.$file5, $copy);
			$this->moveFile($path.$file6, $MODELS.$file6, $copy);

			$this->moveFile($path.$file7, $VIEWS_USERS.$file7, $copy);
			$this->moveFile($path.$file8, $VIEWS_INDEX.$file8, $copy);
			$this->moveFile($path.$file9, $VIEWS_ADMIN.$file9, $copy);
			$this->moveFile($path.$file10, $VIEWS_INCLUDES.$file10, $copy);

			$this->moveFile($path.$file11, $PUBLIC_JS.$file11, $copy);


			// Copy UpgradeController
			if ($copy == true) {
				$this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		 }

			// Delete folder
			if ($copy == false) {
			 File::deleteDirectory("v$version");
		 }

			// Update Version
		 $this->settings->whereId(1)->update([
					 'version' => $version
				 ]);

				 // Clear Cache, Config and Views
			\Artisan::call('cache:clear');
			\Artisan::call('config:clear');
			\Artisan::call('view:clear');

			return $upgradeDone;

		}
		//<<---- End Version 1.1 ----->>

		if ($version == '1.2') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = true;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version $oldVersion</h2>";
			}

			if (! Schema::hasColumn('admin_settings', 'widget_creators_featured', 'home_style')) {
						Schema::table('admin_settings', function($table) {
						 $table->enum('widget_creators_featured', ['on', 'off'])->default('on');
						 $table->unsignedInteger('home_style');
				});
			}

			if (! Schema::hasColumn('updates', 'fixed_post')) {
						Schema::table('updates', function($table) {
						 $table->enum('fixed_post', ['0', '1'])->default('0');
				});
			}

			if (! Schema::hasColumn('users', 'dark_mode')) {
						Schema::table('users', function($table) {
						 $table->enum('dark_mode', ['on', 'off'])->default('off');
				});
			}

			// Create Table Bookmarks
				if ( ! Schema::hasTable('bookmarks')) {
					Schema::create('bookmarks', function($table)
							 {
									 $table->increments('id');
									 $table->unsignedInteger('user_id')->index();
									 $table->unsignedInteger('updates_id')->index();
									 $table->timestamps();
							 });
			 }// <<--- End Create Table Bookmarks

			//============== Files Affected ================//
			$file1 = 'UpdatesController.php';
			$file2 = 'UserController.php';
			$file3 = 'AdminController.php';
			$file4 = 'HomeController.php';
			$file5 = 'MessagesController.php';
			$file6 = 'SocialAccountService.php';
			$file7 = 'PayPalController.php';

			$file8 = 'UserDelete.php'; // Traits
			$file9 = 'User.php';
			$file10 = 'Bookmarks.php';
			$file11 = 'Updates.php';

			$file12 = 'web.php';

			$file14 = 'bookmarks.blade.php';
			$file15 = 'home-session.blade.php';
			$file16 = 'css_general.blade.php';
			$file17 = 'javascript_general.blade.php';
			$file18 = 'limits.blade.php';
			$file19 = 'navbar.blade.php';
			$file20 = 'footer.blade.php';

			$file21 = 'settings.blade.php';
			$file22 = 'layout.blade.php';
			$file23 = 'updates.blade.php';

			$file24 = 'home.blade.php';
			$file25 = 'profile.blade.php';

			$file26 = 'withdrawals.blade.php';
			$file27 = 'withdrawals.blade.php';
			$file28 = 'social-login.blade.php';
			$file29 = 'app.blade.php';

			$file30 = 'app-functions.js';
			$file31 = 'bootstrap-dark.min.css';

			$file32 = 'bell-light.svg';
			$file33 = 'compass-light.svg';
			$file34 = 'home-light.svg';
			$file35 = 'paper-light.svg';


			//============== Moving Files ================//
			$this->moveFile($path.$file1, $CONTROLLERS.$file1, $copy);
			$this->moveFile($path.$file2, $CONTROLLERS.$file2, $copy);
			$this->moveFile($path.$file3, $CONTROLLERS.$file3, $copy);
			$this->moveFile($path.$file4, $CONTROLLERS.$file4, $copy);
			$this->moveFile($path.$file5, $CONTROLLERS.$file5, $copy);
			$this->moveFile($path.$file6, $APP.$file6, $copy);
			$this->moveFile($path.$file7, $CONTROLLERS.$file7, $copy);

			$this->moveFile($path.$file8, $TRAITS.$file8, $copy);
			$this->moveFile($path.$file9, $MODELS.$file9, $copy);
			$this->moveFile($path.$file10, $MODELS.$file10, $copy);
			$this->moveFile($path.$file11, $MODELS.$file11, $copy);

			$this->moveFile($path.$file12, $ROUTES.$file12, $copy);

			$this->moveFile($path.$file14, $VIEWS_USERS.$file14, $copy);
			$this->moveFile($path.$file15, $VIEWS_INDEX.$file15, $copy);
			$this->moveFile($path.$file16, $VIEWS_INCLUDES.$file16, $copy);
			$this->moveFile($path.$file17, $VIEWS_INCLUDES.$file17, $copy);
			$this->moveFile($path.$file18, $VIEWS_ADMIN.$file18, $copy);
			$this->moveFile($path.$file19, $VIEWS_INCLUDES.$file19, $copy);
			$this->moveFile($path.$file20, $VIEWS_INCLUDES.$file20, $copy);
			$this->moveFile($path.$file21, $VIEWS_ADMIN.$file21, $copy);
			$this->moveFile($path.$file22, $VIEWS_ADMIN.$file22, $copy);
			$this->moveFile($path.$file23, $VIEWS_INCLUDES.$file23, $copy);
			$this->moveFile($path.$file24, $VIEWS_INDEX.$file24, $copy);
			$this->moveFile($path.$file25, $VIEWS_USERS.$file25, $copy);
			$this->moveFile($path.$file26, $VIEWS_USERS.$file26, $copy);
			$this->moveFile($pathAdmin.$file27, $VIEWS_ADMIN.$file27, $copy);
			$this->moveFile($path.$file28, $VIEWS_ADMIN.$file28, $copy);
			$this->moveFile($path.$file29, $VIEWS_LAYOUTS.$file29, $copy);

			$this->moveFile($path.$file30, $PUBLIC_JS.$file30, $copy);
			$this->moveFile($path.$file31, $PUBLIC_CSS.$file31, $copy);

			$this->moveFile($path.$file32, $PUBLIC_IMG_ICONS.$file32, $copy);
			$this->moveFile($path.$file33, $PUBLIC_IMG_ICONS.$file33, $copy);
			$this->moveFile($path.$file34, $PUBLIC_IMG_ICONS.$file34, $copy);
			$this->moveFile($path.$file35, $PUBLIC_IMG_ICONS.$file35, $copy);


			// Copy UpgradeController
			if ($copy == true) {
				$this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		 }

			// Delete folder
			if ($copy == false) {
			 File::deleteDirectory("v$version");
		 }

			// Update Version
		 $this->settings->whereId(1)->update([
					 'version' => $version
				 ]);

				 // Clear Cache, Config and Views
			\Artisan::call('cache:clear');
			\Artisan::call('config:clear');
			\Artisan::call('view:clear');

			return $upgradeDone;

		}
		//<<---- End Version 1.2 ----->>

		if ($version == '1.3') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = true;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version $oldVersion</h2>";
			}

			if (! Schema::hasColumn('admin_settings', 'file_size_allowed_verify_account')) {
						Schema::table('admin_settings', function($table) {
						 $table->unsignedInteger('file_size_allowed_verify_account');
				});

				if (Schema::hasColumn('admin_settings', 'file_size_allowed_verify_account')) {
					AdminSettings::whereId(1)->update([
								'file_size_allowed_verify_account' => 1024
							]);
				}
			}

			//============== Files Affected ================//
			$file3 = 'AdminController.php';
			$file5 = 'MessagesController.php';

			$file8 = 'UserDelete.php'; // Traits

			$file14 = 'verify_account.blade.php';
			$file16 = 'css_general.blade.php';
			$file18 = 'limits.blade.php';

			$file22 = 'dashboard.blade.php';

			$file29 = 'app.blade.php';

			$file30 = 'app-functions.js';
			$file31 = 'messages.js';

			//============== Moving Files ================//
			$this->moveFile($path.$file3, $CONTROLLERS.$file3, $copy);
			$this->moveFile($path.$file5, $CONTROLLERS.$file5, $copy);

			$this->moveFile($path.$file8, $TRAITS.$file8, $copy);

			$this->moveFile($path.$file14, $VIEWS_USERS.$file14, $copy);
			$this->moveFile($path.$file16, $VIEWS_INCLUDES.$file16, $copy);
			$this->moveFile($path.$file18, $VIEWS_ADMIN.$file18, $copy);

			$this->moveFile($path.$file22, $VIEWS_ADMIN.$file22, $copy);

			$this->moveFile($path.$file29, $VIEWS_LAYOUTS.$file29, $copy);

			$this->moveFile($path.$file30, $PUBLIC_JS.$file30, $copy);
			$this->moveFile($path.$file31, $PUBLIC_JS.$file31, $copy);


			// Copy UpgradeController
			if ($copy == true) {
				$this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		 }

			// Delete folder
			if ($copy == false) {
			 File::deleteDirectory("v$version");
		 }

			// Update Version
		 $this->settings->whereId(1)->update([
					 'version' => $version
				 ]);

				 // Clear Cache, Config and Views
			\Artisan::call('cache:clear');
			\Artisan::call('config:clear');
			\Artisan::call('view:clear');

			return $upgradeDone;

		}
		//<<---- End Version 1.3 ----->>

		if ($version == '1.4') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = true;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version $oldVersion</h2>";
			}

			PaymentGateways::whereId(1)->update([
						'recurrent' => 'no',
						'logo' => 'paypal.png',
					]);

					PaymentGateways::whereId(2)->update([
								'logo' => 'stripe.png',
							]);

			//============== Files Affected ================//
			$file3 = 'AdminController.php';
			$file5 = 'UserController.php';
			$file18 = 'storage.blade.php';
			$file29 = 'app.blade.php';


			//============== Moving Files ================//
			$this->moveFile($path.$file3, $CONTROLLERS.$file3, $copy);
			$this->moveFile($path.$file5, $CONTROLLERS.$file5, $copy);
			$this->moveFile($path.$file18, $VIEWS_ADMIN.$file18, $copy);
			$this->moveFile($path.$file29, $VIEWS_LAYOUTS.$file29, $copy);

			// Copy UpgradeController
			if ($copy == true) {
				$this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		 }

			// Delete folder
			if ($copy == false) {
			 File::deleteDirectory("v$version");
		 }

			// Update Version
		 $this->settings->whereId(1)->update([
					 'version' => $version
				 ]);

				 // Clear Cache, Config and Views
			\Artisan::call('cache:clear');
			\Artisan::call('config:clear');
			\Artisan::call('view:clear');

			return $upgradeDone;

		}
		//<<---- End Version 1.4 ----->>

		if ($version == '1.5') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = false;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version $oldVersion</h2>";
			}

			//============== Files Affected ================//
			$file5 = 'UserController.php';
			$file6 = 'SocialAccountService.php';
			$file18 = 'updates.blade.php';
			$file29 = 'app.blade.php';
			$file30 = 'profile.blade.php';
			$file31 = 'edit_my_page.blade.php';


			//============== Moving Files ================//
			$this->moveFile($path.$file5, $CONTROLLERS.$file5, $copy);
			$this->moveFile($path.$file6, $APP.$file6, $copy);
			$this->moveFile($path.$file18, $VIEWS_INCLUDES.$file18, $copy);
			$this->moveFile($path.$file29, $VIEWS_LAYOUTS.$file29, $copy);
			$this->moveFile($path.$file30, $VIEWS_USERS.$file30, $copy);
			$this->moveFile($path.$file31, $VIEWS_USERS.$file31, $copy);

			// Copy UpgradeController
			if ($copy == true) {
				$this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		 }

			// Delete folder
			if ($copy == false) {
			 File::deleteDirectory("v$version");
		 }

			// Update Version
		 $this->settings->whereId(1)->update([
					 'version' => $version
				 ]);

				 // Clear Cache, Config and Views
			\Artisan::call('cache:clear');
			\Artisan::call('config:clear');
			\Artisan::call('view:clear');

			return $upgradeDone;

		}
		//<<---- End Version 1.5 ----->>

	}//<--- End Method

}
