<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description_custom')@if(!Request::route()->named('seo') && !Request::route()->named('profile')){{trans('seo.description')}}@endif">
  <meta name="keywords" content="@yield('keywords_custom'){{ trans('seo.keywords') }}" />
  <meta name="theme-color" content="{{ auth()->check() && auth()->user()->dark_mode == 'on' ? '#303030' : $settings->color_default }}">
  <title>{{ Auth::check() && User::notificationsCount() ? '('.User::notificationsCount().') ' : '' }}@section('title')@show @if( isset( $settings->title ) ){{$settings->title}}@endif</title>
  <!-- Favicon -->
  <link href="{{ url('public/img', $settings->favicon) }}" rel="icon" type="image/png">

  @include('includes.css_general')

  @yield('css')

 @if($settings->google_analytics != '')
  {!! $settings->google_analytics !!}
  @endif

  <style type="text/css">

  @if (Auth::check() && auth()->user()->dark_mode == 'on' )
    body { color: #FFF; }
    .dd-menu-user:before { color: #222222; }
    .dropdown-item.balance:hover {background: #222 !important;color: #ffffff;}
    .blocked {background-color: transparent;}
    .btn-google, .btn-google:hover, .btn-google:active, .btn-google:focus {
    background: transparent;
    border-color: #ccc;
    color: #fff;
  }
  .img-user,
  .avatar-modal,
  .img-user-small { border-color: #303030; }
  .actionDeleteNotify,
  .actionDeleteNotify:hover { color: #FFF; }

  .nav-profile a, .nav-profile li.active a:hover, .nav-profile li.active a:active, .nav-profile li.active a:focus,
  .sm-btn-size, .verified {
    color: #fff;
  }
  .text-featured {color: #fff !important;}
  .input-group-text {
    border-color: #303030;
    background-color: #303030;
  }
  .form-control:focus, .custom-select:focus {
    border-color: #222 !important;
  }
  .custom-select {
    background: #303030 url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23a5a5a5' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e")
    no-repeat right .75rem center/8px 10px;
    color: #fff;
  }
  .navbar-toggler,
  .sweet-alert h2,
  .sweet-alert p,
  .ico-no-result {
    color: #FFF;
  }
  .sweet-alert { background-color: #2f2f2f;}
  .content-locked {background: #444444;}

  @media (max-width: 991px) {
  .navbar .navbar-collapse {
    background: #222;
  }
  .navbar .navbar-collapse .navbar-nav .nav-item .nav-link:not(.btn) {
    color: #ffffff;
  }

  .navbar-collapse .navbar-toggler span {
    background: #fff;
  }
}
.link-scroll a.nav-link:not(.btn) {
    color: #969696;
}
.btn-upload:hover {
  background-color: #222222;
}
.modal-danger .modal-content {
  background-color: #303030;
}
h3, .h3 {font-size: 1.75rem;}
h2, .h2 {font-size: 2rem;}
h4, .h4 {font-size: 1.5rem;}
h5, .h5 {font-size: 1.25rem;}

@keyframes animate {
  from {transition:none;}
 to {background-color:#383838;transition: all 0.3s ease-out;}
}

.item-loading::before {
    background-color: #6b6b6b;
    content: ' ';
    display: block;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    animation-name: animate;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    background-image: none;
    border-radius: 0;
}
.loading-avatar::before {
  border-radius: 50%;
}
.loading-avatar {background-color: inherit;}
.plyr--audio .plyr__controls {background: #212121; color: #ffffff;}
.readmore-js-collapsed:after {background-image: linear-gradient(hsla(0,0%,100%,0),#303030 95%);}
.sweet-alert .sa-icon.sa-success .sa-fix {background-color: #2f2f2f;}
.sweet-alert .sa-icon.sa-success::after, .sweet-alert .sa-icon.sa-success::before {background: #2f2f2f;}
.page-item.disabled .page-link, .page-link {background-color: #222222;}
.nav-pills .nav-link {background-color: #303030; color: #ffffff;}
a.social-share i {color: #dedede!important;}

.StripeElement {background-color: #222222; border: 1px solid #222222;}
.StripeElement--focus {border-color: #525252;}
.bg-autocomplete {background-color: #222;}

@endif

  .bg-gradient {
    background: url('{{url('public/img', $settings->bg_gradient)}}');
    background-size: cover;
  }

  a.social-share i {color: #797979; font-size: 32px;}
  a:hover.social-share { text-decoration: none; }
  .btn-whatsapp {color: #50b154 !important;}
  .close-inherit {color: inherit !important;}
  .btn-twitter { background-color: #1da1f2;  color:#fff !important;}

  @media (max-width: 991px) {
    .navbar-user-mobile {
      font-size: 20px;
    }
  }

  .or {
  display:flex;
  justify-content:center;
  align-items: center;
  color:grey;
}

.or:after,
.or:before {
    content: "";
    display: block;
    background: #adb5bd;
    width: 50%;
    height:1px;
    margin: 0 10px;
}

  .icon-navbar { font-size: 23px; vertical-align: bottom; @if (auth()->check() && auth()->user()->dark_mode == 'on') color: #FFF !important; @endif }

  {{ $settings->button_style == 'rounded' ? '.btn {border-radius: 50rem!important;}' : null }}

  @if (auth()->check() && auth()->user()->dark_mode == 'off' || auth()->guest())
  .navbar_background_color { background-color: {{ $settings->navbar_background_color }} !important; }
  .link-scroll a.nav-link:not(.btn), .navbar-toggler:not(.text-white) { color: {{ $settings->navbar_text_color }} !important; }

  @media (max-width: 991px) {
    .navbar .navbar-collapse, .dd-menu-user, .dropdown-item.balance:hover { background-color: {{ $settings->navbar_background_color }} !important; }
    .dd-menu-user a, .dropdown-item:not(.dropdown-lang) { color: {{ $settings->navbar_text_color }} }
    .navbar-collapse .navbar-toggler span { background-color: {{ $settings->navbar_text_color }} !important; }
    .dropdown-divider { border-top-color: {{ $settings->navbar_background_color }} !important;}
    }

  .footer_background_color { background-color: {{ $settings->footer_background_color }} !important; }
  .footer_text_color, .link-footer:not(.footer-tiny) { color: {{ $settings->footer_text_color }}; }
  @endif

  @if ($settings->color_default <> '')

  :root {
    --plyr-color-main: {{$settings->color_default}};
  }

  ::selection{ background-color: {{$settings->color_default}}; color: white; }
  ::moz-selection{ background-color: {{$settings->color_default}}; color: white; }
  ::webkit-selection{ background-color: {{$settings->color_default}}; color: white; }

  body a,
  a:hover,
  a:focus,
  a.page-link,
  .btn-outline-primary {
      color: {{$settings->color_default}};
  }
  .text-primary {
      color: {{$settings->color_default}}!important;
  }

  a.text-primary.btnBookmark:hover, a.text-primary.btnBookmark:focus {
    color: {{$settings->color_default}}!important;
  }

  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary.dropdown-toggle,
  .btn-primary:hover,
  .btn-primary:focus,
  .btn-primary:active,
  .btn-primary,
  .btn-primary.disabled,
  .btn-primary:disabled,
  .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before,
  .page-item.active .page-link,
  .page-link:hover,
  .owl-theme .owl-dots .owl-dot span,
  .owl-theme .owl-dots .owl-dot.active span,
  .owl-theme .owl-dots .owl-dot:hover span
   {
      background-color: {{$settings->color_default}};
      border-color: {{$settings->color_default}};
  }
  .bg-primary,
  .dropdown-item:focus,
  .dropdown-item:hover,
  .dropdown-item.active,
  .dropdown-item:active,
  .tooltip-inner {
      background-color: {{$settings->color_default}}!important;
  }

  .custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before,
  .custom-control-input:focus:not(:checked) ~ .custom-control-label::before,
  .btn-outline-primary {
  	border-color: {{$settings->color_default}};
  }
  .custom-control-input:not(:disabled):active~.custom-control-label::before,
  .custom-control-input:checked~.custom-control-label::before,
  .btn-outline-primary:hover,
  .btn-outline-primary:focus,
  .btn-outline-primary:not(:disabled):not(.disabled):active,
  .list-group-item.active {
      color: #fff;
      background-color: {{$settings->color_default}};
      border-color: {{$settings->color_default}};
  }
  .popover .arrow::before { border-top-color: rgba(0,0,0,.35) !important; }
  .bs-tooltip-bottom .arrow::before {
    border-bottom-color: {{$settings->color_default}}!important;
  }
  .arrow::before {
    border-top-color: {{$settings->color_default}}!important;
  }
  .nav-profile li.active {
    border-bottom: 3px solid {{$settings->color_default}}!important;
  }
  .button-avatar-upload {left: 0;}
  input[type='file'] {overflow: hidden;}
  .badge-free { top: 10px; right: 10px; background: rgb(0 0 0 / 65%); color: #fff; font-size: 12px;}

  @endif
  </style>
</head>

<body>
  <div class="btn-block text-center showBanner padding-top-10 pb-3 display-none">
    <i class="fa fa-cookie-bite"></i> {{trans('general.cookies_text')}}
    @if($settings->link_cookies != '')
      <a href="{{$settings->link_cookies}}" class="mr-2 text-white link-border" target="_blank">{{ trans('general.cookies_policy') }}</a>
    @endif
    <button class="btn btn-sm btn-success" id="close-banner">{{trans('general.go_it')}}
    </button>
  </div>

  <div class="popout popout-error font-default"></div>

@if (Auth::guest() && request()->path() == '/' && $settings->home_style == 0
    || Auth::guest() && request()->path() != '/' && $settings->home_style == 0
    || Auth::guest() && request()->path() != '/' && $settings->home_style == 1
    || Auth::check()
    )
  @include('includes.navbar')
  @endif

  <main role="main">
    @yield('content')

    @if (Auth::guest() && ! request()->route()->named('profile')
          || Auth::check()
          && request()->path() != '/'
          && ! request()->is('my/bookmarks')
          && ! request()->route()->named('profile')
          )

          @if (Auth::guest() && request()->path() == '/' && $settings->home_style == 0
                || Auth::guest() && request()->path() != '/' && $settings->home_style == 0
                || Auth::guest() && request()->path() != '/' && $settings->home_style == 1
                || Auth::check()
                )
            @include('includes.footer')
          @endif

  @endif

  @guest

  @if (auth()->guest()
      && ! request()->is('/')
      && ! request()->is('login')
      && ! request()->is('signup')
      && ! request()->is('password/reset')
      && ! request()->is('password/reset/*')
      && ! request()->is('contact')
      )
    <div class="modal fade" id="loginFormModal" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
              <div class="card-body px-lg-5 py-lg-5 position-relative">

                <h6 class="modal-title text-center mb-3">{{ __('general.login_continue') }}</h6>

                @if ($settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
                <div class="mb-2 w-100">

                  @if ($settings->facebook_login == 'on')
                    <a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn flex-grow mb-2 w-100">
                      <i class="fab fa-facebook mr-2"></i> {{ __('auth.login_with') }} Facebook
                    </a>
                  @endif

                  @if ($settings->twitter_login == 'on')
                  <a href="{{url('oauth/twitter')}}" class="btn btn-twitter auth-form-btn mb-2 w-100">
                    <i class="fab fa-twitter mr-2"></i> {{ __('auth.login_with') }} Twitter
                  </a>
                @endif

                    @if ($settings->google_login == 'on')
                    <a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn flex-grow w-100">
                      <img src="{{ url('public/img/google.svg') }}" class="mr-2" width="18" height="18"> {{ __('auth.login_with') }} Google
                    </a>
                  @endif
                  </div>

                  <small class="btn-block text-center my-3 text-uppercase or">{{__('general.or')}}</small>

                @endif

                <form method="POST" action="{{ route('login') }}" data-url-login="{{ route('login') }}" data-url-register="{{ route('register') }}" id="formLoginRegister" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="isModal" id="isModal" value="true">

                    @if ($settings->captcha == 'on')
                      @captcha
                    @endif

                    <div class="form-group mb-3 display-none" id="full_name">
                      <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                        </div>
                        <input class="form-control"  value="{{ old('name')}}" placeholder="{{trans('auth.full_name')}}" name="name" type="text">
                      </div>
                    </div>

                  <div class="form-group mb-3 display-none" id="email">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                      </div>
                      <input class="form-control" value="{{ old('email')}}" placeholder="{{trans('auth.email')}}" name="email" type="text">
                    </div>
                  </div>

                  <div class="form-group mb-3" id="username_email">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                      </div>
                      <input class="form-control" value="{{ old('username_email') }}" placeholder="{{ trans('auth.username_or_email') }}" name="username_email" type="text">

                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group input-group-alternative" id="showHidePassword">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                      </div>
                      <input name="password" type="password" class="form-control" placeholder="{{ trans('auth.password') }}">
                      <div class="input-group-append">
                        <span class="input-group-text c-pointer"><i class="fa fa-eye-slash"></i></span>
                    </div>
                  </div>
                  <small class="form-text text-muted">
                    <a href="{{url('password/reset')}}" id="forgotPassword">
                      {{trans('auth.forgot_password')}}
                    </a>
                  </small>
                  </div>

                  <div class="custom-control custom-control-alternative custom-checkbox" id="remember">
                    <input class="custom-control-input" id=" customCheckLogin" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for=" customCheckLogin">
                      <span>{{trans('auth.remember_me')}}</span>
                    </label>
                  </div>

                  <div class="custom-control custom-control-alternative custom-checkbox display-none" id="agree_gdpr">
                    <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="agree_gdpr">
                      <label class="custom-control-label" for="customCheckRegister">
                        <span>{{trans('admin.i_agree_gdpr')}}
                          <a href="{{$settings->link_privacy}}" target="_blank">{{trans('admin.privacy_policy')}}</a>
                        </span>
                      </label>
                  </div>

                  <div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
                      <ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
                    </div>

                  <div class="text-center">
                    <button type="submit" id="btnLoginRegister" class="btn btn-primary mt-4 w-100"><i></i> {{trans('auth.login')}}</button>

                    <div class="w-100 mt-2">
                      <button type="button" class="btn e-none p-0" data-dismiss="modal">{{ __('admin.cancel') }}</button>
                    </div>
                  </div>
                </form>

                @if ($settings->captcha == 'on')
                  <small class="btn-block text-center mt-3">{{trans('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{trans('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('general.terms')}}</a></small>
                @endif

                @if ($settings->registration_active == '1')
                <div class="row mt-3">
                  <div class="col-12 text-center">
                    <a href="javascript:void(0);" id="toggleLogin" data-not-account="{{trans('auth.not_have_account')}}" data-already-account="{{trans('auth.already_have_an_account')}}" data-text-login="{{trans('auth.login')}}" data-text-register="{{trans('auth.sign_up')}}">
                      <strong>{{trans('auth.not_have_account')}}</strong>
                    </a>
                  </div>
                </div>
                @endif

              </div><!-- ./ card-body -->
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Modal -->
    @endif
  @endguest

  @auth
    <div class="modal fade" id="tipForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white shadow border-0">
              <div class="card-header pb-2 border-0 position-relative" style="height: 100px; background: {{$settings->color_default}} @if (auth()->user()->cover != '')  url('{{Storage::url(config('path.cover').auth()->user()->cover)}}') @endif no-repeat center center; background-size: cover;">

              </div>
              <div class="card-body px-lg-5 py-lg-5 position-relative">

                <div class="text-muted text-center mb-3 position-relative modal-offset">
                  <img src="{{Storage::url(config('path.avatar').auth()->user()->avatar)}}" width="100" class="avatar-modal rounded-circle mb-1">
                  <h6>
                    {{trans('general.send_tip')}} <span class="userNameTip"></span>
                  </h6>
                </div>

                <form method="post" action="{{url('send/tip')}}" id="formSendTip">

                  <input type="hidden" name="id" class="userIdInput" value="{{auth()->user()->id}}"  />

                  @if (request()->is('messages/*'))
                    <input type="hidden" name="isMessage" value="1"  />
                  @endif

                  <input type="hidden" id="cardholder-name" value="{{ auth()->user()->name }}"  />
                  <input type="hidden" id="cardholder-email" value="{{ auth()->user()->email }}"  />
                  <input type="number" min="{{$settings->min_donation_amount}}"  autocomplete="off" id="onlyNumber" class="form-control mb-3" name="amount" placeholder="{{trans('general.tip_amount')}}">

                  @csrf

                  @foreach (PaymentGateways::where('enabled', '1')->whereSubscription('yes')->get() as $payment)

                    @php

                    if ($payment->type == 'card' ) {
                      $paymentName = '<i class="far fa-credit-card mr-1"></i> '.trans('general.debit_credit_card') .' ('.$payment->name.')';
                    } else {
                      $paymentName = '<img src="'.url('public/img/payments', $payment->logo).'" width="70"/>';
                    }

                    @endphp
                    <div class="custom-control custom-radio mb-3">
                      <input name="payment_gateway_tip" value="{{$payment->id}}" id="tip_radio{{$payment->id}}" @if (PaymentGateways::where('enabled', '1')->count() == 1) checked @endif class="custom-control-input" type="radio">
                      <label class="custom-control-label" for="tip_radio{{$payment->id}}">
                        <span><strong>{!!$paymentName!!}</strong></span>
                      </label>
                    </div>

                    @if ($payment->id == 2)
                    <div id="stripeContainerTip" class="@if (PaymentGateways::where('enabled', '1')->count() != 1) display-none @endif">
                      <div id="card-element" class="margin-bottom-10">
                        <!-- A Stripe Element will be inserted here. -->
                      </div>
                      <!-- Used to display form errors. -->
                      <div id="card-errors" class="alert alert-danger display-none" role="alert"></div>
                    </div>
                    @endif

                  @endforeach

                  <div class="custom-control custom-radio mb-3">
                    <input name="payment_gateway_tip" @if (Auth::user()->wallet == 0) disabled @endif value="wallet" id="tip_radio0" class="custom-control-input" type="radio">
                    <label class="custom-control-label" for="tip_radio0">
                      <span>
                        <strong>
                        <i class="fas fa-wallet mr-1"></i> {{ __('general.wallet') }}
                        <span class="w-100 d-block font-weight-light">
                          {{ __('general.available_balance') }}: <span class="font-weight-bold mr-1 balanceWallet">{{Helper::amountFormatDecimal(Auth::user()->wallet)}}</span>

                          @if (Auth::user()->wallet == 0)
                          <a href="{{ url('my/wallet') }}" class="link-border">{{ __('general.recharge') }}</a>
                        @endif
                        </span>
                      </strong>
                      </span>
                    </label>
                  </div>

                  <div class="alert alert-danger display-none" id="errorTip">
                      <ul class="list-unstyled m-0" id="showErrorsTip"></ul>
                    </div>

                  <div class="text-center">
                    <button type="button" class="btn e-none mt-4" data-dismiss="modal">{{trans('admin.cancel')}}</button>
                    <button type="submit" id="tipBtn" class="btn btn-primary mt-4 tipBtn"><i></i> {{trans('general.pay')}}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Modal Tip -->
  @endauth
  </main>

  @include('includes.javascript_general')

  @yield('javascript')

@auth
  <div id="bodyContainer"></div>
@endauth

@if (auth()->guest()
    && ! request()->is('password/reset')
    && ! request()->is('password/reset/*')
    && ! request()->is('contact')
    )
<script type="text/javascript">

	//<---------------- Login Register ----------->>>>

	_submitEvent = function() {
		  sendFormLoginRegister();
		};

	if (captcha == false) {

	    $(document).on('click','#btnLoginRegister',function(s) {

 		 s.preventDefault();
		 sendFormLoginRegister();

 		 });//<<<-------- * END FUNCTION CLICK * ---->>>>
	}

	function sendFormLoginRegister()
	{
		var element = $(this);
		$('#btnLoginRegister').attr({'disabled' : 'true'});
		$('#btnLoginRegister').find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

		(function(){
			 $("#formLoginRegister").ajaxForm({
			 dataType : 'json',
			 success:  function(result) {

				 // success
				 if (result.success == true) {

           if (result.isModal && result.isLogin) {
             window.location.reload();
           }

					 if (result.url_return && ! result.isModal) {
					 	window.location.href = result.url_return;
					 }

					 if (result.check_account) {
					 	$('#checkAccount').html(result.check_account).fadeIn(500);

						$('#btnLoginRegister').removeAttr('disabled');
						$('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						$('#errorLogin').fadeOut(100);
						$("#formLoginRegister").reset();
					 }

				 }  else {

					 if (result.errors) {

						 var error = '';
						 var $key = '';

					for ($key in result.errors) {
							 error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						 }

						 $('#showErrorsLogin').html(error);
						 $('#errorLogin').fadeIn(500);
						 $('#btnLoginRegister').removeAttr('disabled');
						 $('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 }
				 }

				},
				error: function(responseText, statusText, xhr, $form) {
						// error
						$('#btnLoginRegister').removeAttr('disabled');
						$('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						swal({
								type: 'error',
								title: error_oops,
								text: error_occurred+' ('+xhr+')',
							});
				}
			}).submit();
		})(); //<--- FUNCTION %
	}// End function sendFormLoginRegister
</script>
@endif
</body>
</html>
