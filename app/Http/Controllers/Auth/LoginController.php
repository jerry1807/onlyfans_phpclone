<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
      $this->auth = $auth;
      $this->middleware('guest')->except('logout');
    }

    public function login(Request $request) {

      $settings = AdminSettings::first();
      $request['_captcha'] = $settings->captcha;

      $messages = [
    'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
    'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
  ];

  	     // get our login input
      $login = $request->input('username_email');
      $urlReturn = $request->input('return');

      // check login field
      $login_type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

      // merge our login field into the request with either email or username as key
      $request->merge([$login_type => $login]);

      // let's validate and set our credentials
      if ($login_type == 'email') {

          $this->validate($request, [
              'username_email'    => 'required|email',
              'password' => 'required',
              'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
          ], $messages);

          $credentials = $request->only('email', 'password');

      } else {

          $this->validate($request, [
              'username_email' => 'required',
              'password' => 'required',
          ]);

          $credentials = $request->only('username', 'password');

      }

    if ($this->auth->attempt($credentials, $request->has('remember'))) {

  			if ($this->auth->User()->status == 'active') {

              if (isset($urlReturn) && url()->isValidUrl($urlReturn)) {
                  return redirect($urlReturn);
                } else {
                  return redirect()->intended('/');
                }

          } else if ($this->auth->User()->status == 'suspended') {

  			$this->auth->logout();

  			return redirect()->back()
  				->withErrors([
  					'status' => trans('validation.user_suspended'),
  				]);
          } else if ($this->auth->User()->status == 'pending') {

  			$this->auth->logout();

  			return redirect()->back()
  				->withErrors([
  					'status' => trans('validation.account_not_confirmed'),
  				]);
          }
        }

      return redirect()->back()
  				->withInput($request->only('username_email', 'remember', 'return'))
  				->withErrors([
  					'username_email' => trans('auth.failed'),
  				]);
  	}

}
