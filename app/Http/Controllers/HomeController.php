<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Categories;
use App\Models\Updates;
use App\Models\Bookmarks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Mail;
use Image;
use App\Helper;
use Cache;

class HomeController extends Controller
{

  public function __construct(Request $request, AdminSettings $settings) {

    $this->request = $request;
    try {
      // Check Datebase access
      $this->settings = $settings::first();

    } catch (\Exception $e) {
      // Empty
    }
  }

    /**
     * Homepage Section.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // $currentDate = Carbon::now();
      // $agoDate = $currentDate->subDays($currentDate->dayOfWeek - 1)->subWeek();
      //Carbon::parse()->previousWeekendDay();

      try {
        // Check Datebase access
         $this->settings;
      } catch (\Exception $e) {
        // Redirect to Installer
        return redirect('install/script');
      }

        // Home Guest
        if (Auth::guest()) {
          $users = User::where('featured','yes')
            ->where('status','active')
              ->whereVerifiedId('yes')
              ->where('id', '<>', $this->settings->hide_admin_profile == 'on' ? 1 : 0)
              ->orderBy('featured_date','desc')
              ->paginate(6);

            $usersTotal = User::whereStatus('active')->whereVerifiedId('yes')->count();

            $home = $this->settings->home_style == 0 ? 'home' : 'home-login';

          return view('index.'.$home, [
              'users' => $users,
              'usersTotal' => $usersTotal
            ]);

        } else {

          $users = User::where('status','active')
            ->where('id', '<>', Auth::user()->id)
              ->whereVerifiedId('yes')
              ->where('id', '<>', $this->settings->hide_admin_profile == 'on' ? 1 : 0)
              ->inRandomOrder()
              ->paginate(3);

          $updates = Updates::leftjoin('users', 'updates.user_id', '=', 'users.id')
             ->leftjoin('subscriptions', 'subscriptions.stripe_plan', '=', 'users.plan')
    			    ->where('subscriptions.user_id', '=', Auth::user()->id)
              ->where('subscriptions.stripe_id', '=', '')
              ->whereDate('ends_at', '>=', Carbon::today())

              ->orWhere('subscriptions.stripe_id', '<>', '')
              ->where('subscriptions.user_id', '=', Auth::user()->id)
              ->where('stripe_status', 'active')

              ->orWhere('subscriptions.user_id', '=', Auth::user()->id)
              ->where('subscriptions.stripe_id', '=', '')
              ->whereFree('yes')

              ->orWhere('updates.user_id', Auth::user()->id)
        			->groupBy('updates.id')
        			->orderBy( 'updates.id', 'desc' )
        			->select('updates.*')
        			->paginate($this->settings->number_posts_show);

          return view('index.home-session', ['users' => $users, 'updates' => $updates]);

        }
    }

    public function ajaxUserUpdates()
    {
      $skip = $this->request->input('skip');
      $total = $this->request->input('total');

      $data = Updates::leftjoin('users', 'updates.user_id', '=', 'users.id')
         ->leftjoin('subscriptions', 'subscriptions.stripe_plan', '=', 'users.plan')
          ->where('subscriptions.user_id', '=', Auth::user()->id )
          ->where('subscriptions.stripe_id', '=', '')
          ->whereDate('ends_at', '>=', Carbon::today())

          ->orWhere('subscriptions.stripe_id', '<>', '')
          ->where('subscriptions.user_id', '=', Auth::user()->id )
          ->where('stripe_status', 'active')

          ->orWhere('subscriptions.user_id', '=', Auth::user()->id)
          ->where('subscriptions.stripe_id', '=', '')
          ->whereFree('yes')

          ->orWhere('updates.user_id', Auth::user()->id)
          ->skip($skip)
          ->take($this->settings->number_posts_show)
          ->groupBy('updates.id')
          ->orderBy( 'updates.id', 'desc' )
          ->select('updates.*')
          ->get();

      $counterPosts = ($total - $this->settings->number_posts_show - $skip);

      return view('includes.updates', ['updates' => $data, 'ajaxRequest' => true, 'counterPosts' => $counterPosts, 'total' => $total])->render();
    }//<--- End Method

    public function getVerifyAccount($confirmation_code) {


  		if (Auth::guest()
          || Auth::check()
          && Auth::user()->confirmation_code == $confirmation_code
          && Auth::user()->status == 'pending'
          ) {
  		$user = User::where( 'confirmation_code', $confirmation_code )->where('status','pending')->first();

  		if ($user) {

  			$update = User::where( 'confirmation_code', $confirmation_code )
  			->where('status','pending')
  			->update( array( 'status' => 'active', 'confirmation_code' => '' ) );

  			Auth::loginUsingId($user->id);

  			 return redirect('/')
  					->with([
  						'success_verify' => true,
  					]);
  			} else {
  			return redirect('/')
  					->with([
  						'error_verify' => true,
  					]);
  			}
  		}
      else {
  			 return redirect('/');
  		}
  	}// End Method

    public function creators($new = false)
    {
      $query = trim($this->request->input('q'));

      if ($new) {
        $orderBy = 'id';
        $title = trans('general.new_creators');
      } else {
        $orderBy = 'featured_date';
        $title = trans('general.explore_our_creators');
      }


      if ($query != '' && strlen($query) >= 3) {

        $title = trans('general.search').' "'.$query.'"';

        $users = User::where('status','active')
              ->where('username','LIKE', '%'.$query.'%')
                ->whereVerifiedId('yes')
                ->where('id', '<>', $this->settings->hide_admin_profile == 'on' ? 1 : 0)
                ->orWhere('status','active')
                ->whereVerifiedId('yes')
                ->where('id', '<>', $this->settings->hide_admin_profile == 'on' ? 1 : 0)
                ->where('name','LIKE', '%'.$query.'%')
                ->orderBy('featured_date','desc')
                ->paginate(12)->onEachSide(1);

      } else {
        $users = User::where('status','active')
          ->orderBy($orderBy,'desc')
            ->whereVerifiedId('yes')
            ->where('id', '<>', $this->settings->hide_admin_profile == 'on' ? 1 : 0)
            ->paginate(12)
            ->onEachSide(1);
      }

        if ($this->request->input('page') > $users->lastPage()) {
    			abort('404');
    		}
        return view('index.creators', ['users' => $users, 'title' => $title]);
    }

    public function category($slug, $new = false)
    {

      $category = Categories::where('slug', '=', $slug)->where('mode','on')->firstOrFail();
      $title    = \Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name;

      if ($new) {
        $orderBy = 'id';
        $title = $title.' - '.trans('general.new_creators');
      } else {
        $orderBy = 'featured_date';
        $title    = \Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name;
      }

      $users = User::where('status','active')
        ->where('categories_id', $category->id)
          ->whereVerifiedId('yes')
          ->orderBy($orderBy, 'desc')
          ->paginate(12)
          ->onEachSide(1);

        if ($this->request->input('page') > $users->lastPage()) {
    			abort('404');
    		}
        return view('index.categories', [
          'users' => $users,
            'title' => $title,
            'slug' => $slug,
            'image' => $category->image,
            'keywords' => $category->keywords,
            'description' => $category->description,
        ]);
    }

    public function contactStore(Request $request)
  	{
      $settings = AdminSettings::first();
      $input = $request->all();

      $errorMessages = [
        'g-recaptcha-response.required' => 'reCAPTCHA Error',
        'g-recaptcha-response.captcha' => 'reCAPTCHA Error',
      ];

        $validator = Validator::make($input, [
          'full_name' => 'min:3|max:25',
          'email'     => 'required|email',
          'subject'     => 'required',
          'message' => 'min:10|required',
          'g-recaptcha-response' => 'required|captcha',
          'agree_terms_privacy' => 'required'
       ], $errorMessages);

      if ($validator->fails()) {
        return redirect('contact')
        ->withInput()->withErrors($validator);
       }

       // SEND EMAIL TO SUPPORT
       $fullname    = $input['full_name'];
  	   $email_user  = $input['email'];
  		 $title_site  = $settings->title;
       $subject     = $input['subject'];
  		 $email_reply = $settings->email_admin;

       Mail::send('emails.contact-email', array(
         'full_name' => $input['full_name'],
         'email' => $input['email'],
         'subject' => $input['subject'],
         '_message' => $input['message']
       ),
  		 function($message) use (
  				 $fullname,
  				 $email_user,
  				 $title_site,
  				 $email_reply,
           $subject
  		 ) {
            $message->from($email_reply, $fullname);
            $message->subject(trans('general.message').' - '.$subject.' - '.$email_user);
            $message->to($email_reply,$title_site);
            $message->replyTo($email_user);
          });

      return redirect('contact')->with(['notification' => trans('general.send_contact_success')]);
  	}

    // Dark Mode
    public function darkMode($mode)
    {
      if ($mode == 'dark') {
        auth()->user()->dark_mode = 'on';
        auth()->user()->save();
      } else {
        auth()->user()->dark_mode = 'off';
        auth()->user()->save();
      }

      return redirect()->back();

    }

    // Add Bookmark
    public function addBookmark()
    {
      // Find post exists
      $post = Updates::findOrFail($this->request->id);

      $bookmark = Bookmarks::firstOrNew([
        'user_id' => Auth::user()->id,
        'updates_id' => $this->request->id
      ]);

      if ($bookmark->exists) {
        $bookmark->delete();

        return response()->json([
          'success' => true,
          'type' => 'deleted'
        ]);
      } else {
        $bookmark->save();

        return response()->json([
          'success' => true,
          'type' => 'added'
        ]);
      }
    } // End addBookmark

    public function searchCreator()
    {
      $query = $this->request->get('user');
      $data = "";

      if ($query != '' && strlen($query) >= 2) {
        $sql = User::where('status','active')
            ->where('username','LIKE', '%'.$query.'%')
             ->whereVerifiedId('yes')
             ->orWhere('status','active')
             ->where('name','LIKE', '%'.$query.'%')
             ->whereVerifiedId('yes')
             ->orderBy('id','desc')
             ->take(4)
             ->get();

          if ($sql) {
            foreach ($sql as $user) {

              if ($user->profession == '') {
                $profession = '@'.$user->username;
              } else {
                $profession = $user->profession;
              }

              $data .= '<div class="card border-0">
  							<div class="list-group list-group-sm list-group-flush">
                 <a href="'.url($user->username).'" class="list-group-item list-group-item-action text-decoration-none py-2 px-3 bg-autocomplete">
                   <div class="media">
                    <div class="media-left mr-3 position-relative">
                        <img class="media-object rounded-circle" src="'.Storage::url(config('path.avatar').$user->avatar).'" width="30" height="30">
                    </div>
                    <div class="media-body overflow-hidden">
                      <div class="d-flex justify-content-between align-items-center">
                       <h6 class="media-heading mb-0 text-truncate">
                            '.$user->name.'
                        </h6>
                      </div>
  										<span class="text-truncate m-0 w-100 text-left">'.$profession.'</span>
                    </div>
                </div>
                  </a>
               </div>
  					 </div>';
            }
            return $data;
           }
          }
        }// End Method

      public function refreshCreators()
      {
        $users = User::where('status','active')
          ->where('id', '<>', Auth::user()->id ?? 0)
            ->whereVerifiedId('yes')
            ->where('id', '<>', $this->settings->hide_admin_profile == 'on' ? 1 : 0)
            ->inRandomOrder()
            ->paginate(3);

            return view('includes.listing-explore-creators', ['users' => $users])->render();
      }
}
