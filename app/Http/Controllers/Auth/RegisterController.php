<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AdminSettings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

      $settings = AdminSettings::first();
      $data['_captcha'] = $settings->captcha;

		$messages = array (
			"letters"    => trans('validation.letters'),
      'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
      'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
        );

		 Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		// Validate if have one letter
	Validator::extend('letters', function($attribute, $value, $parameters){
    	return preg_match('/[a-zA-Z0-9]/', $value);
	});

        return Validator::make($data, [
            'name' => 'required|string|max:100',
            'username'  => 'required|min:3|max:15|ascii_only|alpha_dash|letters|unique:users|unique:reserved,name',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'agree_gdpr' => 'required',
            'countries_id' => 'required',
            'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
        ], $messages);
    }

    /**
     * Show registration form.
     */
    public function showRegistrationForm()
    {
      $settings = AdminSettings::first();

  		if($settings->registration_active == '1')	{
  			return view('auth.register');
  		} else {
  			return redirect('/');
  		}
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
    	$settings    = AdminSettings::first();

		// Verify Settings Admin
		if ($settings->email_verification == '1') {

			$confirmation_code = Str::random(100);
			$status = 'pending';

			//send verification mail to user
		 $_username      = $data['username'];
	   $_email_user    = $data['email'];
		 $_title_site    = $settings->title;
		 $_email_noreply = $settings->email_no_reply;

		 Mail::send('emails.verify', array('confirmation_code' => $confirmation_code),
		 function($message) use (
				 $_username,
				 $_email_user,
				 $_title_site,
				 $_email_noreply
		 ) {
                $message->from($_email_noreply, $_title_site);
                $message->subject(trans('users.title_email_verify'));
                $message->to($_email_user,$_username);
            });

		} else {
			$confirmation_code = '';
			$status            = 'active';
		}

    if($settings->account_verification == '1') {
      $verify = 'no';
    } else {
      $verify = 'yes';
    }

		$token = Str::random(75);

		return User::create([
			'username'          => $data['username'],
      'countries_id'      => $data['countries_id'],
			'name'              => $data['name'],
      'email'             => strtolower($data['email']),
			'password'          => bcrypt($data['password']),
			'avatar'            => $settings->avatar,
      'cover'             => '',
			'status'            => $status,
			'role'              => 'normal',
      'permission'        => 'none',
			'confirmation_code' => $confirmation_code,
      'oauth_uid'         => '',
      'oauth_provider'    => '',
			'token'             => $token,
      'story'             => trans('users.story_default'),
      'verified_id'       => $verify,
      'ip'                => request()->ip()
		]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $settings    = AdminSettings::first();
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // Verify Settings Admin
    		if ($settings->email_verification == '1') {

          return redirect('signup')
                ->with('status', trans('auth.check_account'));

        } else {
            $this->guard()->login($user);
            return $this->registered($request, $user)
                  ?: redirect('settings/page');
        }

    }
}
