@extends('layouts.app')

@section('content')
  <div class="jumbotron m-0 bg-gradient">
    <div class="container pt-lg-md">
      <div class="row">
        <div class="col-lg-7">
          <img src="{{url('public/img', $settings->home_index)}}" class="img-center img-fluid d-lg-block d-none">
        </div>
        <div class="col-lg-5">

          <div class="text-center d-block w-100">
            <img src="{{url('public/img', $settings->logo)}}" alt="{{$settings->title}}" width="50%" class="logo align-baseline mb-1" />
          </div>

          <div class="card bg-white shadow border-0">

            <div class="card-body px-lg-5 py-lg-5 pt-4">

              <small class="btn-block text-center pb-4 h6">{{ trans('general.title_home_login') }}</small>

        @if(session('login_required'))
    			<div class="alert alert-danger" id="dangerAlert">
                		<i class="fa fa-exclamation-triangle"></i> {{trans('auth.login_required')}}
                		</div>
                	@endif

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

                  <input type="hidden" name="return" value="{{ count($errors) > 0 ? old('return') : url()->previous() }}">

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

                <div class="form-group d-none">
                  <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-globe"></i></span>
                  </div>
                  <select name="countries_id" class="form-control custom-select">
                    <option value="">{{trans('general.select_your_country')}}</option>
                        @foreach(  Countries::orderBy('country_name')->get() as $country )
                          <option id="{{$country->country_code}}" value="{{$country->id}}">{{ $country->country_name }}</option>
                          @endforeach
                        </select>
                        </div>
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

                  <div class="alert alert-success display-none mb-0 mt-3" id="checkAccount"></div>

                <div class="text-center">
                  <button type="submit" id="btnLoginRegister" class="btn btn-primary mt-4 w-100"><i></i> {{trans('auth.login')}}</button>
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

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="text-center py-3">
    @include('includes.footer-tiny')
  </div>
@endsection

@section('javascript')
<script type="text/javascript">
    $.ajax({
		url: "https://geolocation-db.com/jsonp",
		jsonpCallback: "callback",
		dataType: "jsonp",
		success: function( location ) {
			$('#'+location.country_code).attr('selected', 'selected');
		}
	});

  @if (session('success_verify'))
  	swal({
  		title: "{{ trans('general.welcome') }}",
  		text: "{{ trans('users.account_validated') }}",
  		type: "success",
  		confirmButtonText: "{{ trans('users.ok') }}"
  		});
  	 @endif

  	 @if (session('error_verify'))
  	swal({
  		title: "{{ trans('general.error_oops') }}",
  		text: "{{ trans('users.code_not_valid') }}",
  		type: "error",
  		confirmButtonText: "{{ trans('users.ok') }}"
  		});
  	 @endif
</script>
@endsection
