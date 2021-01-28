@extends('layouts.app')

@section('title') {{trans('auth.sign_up')}} -@endsection

@section('content')
  <div class="jumbotron home m-0 bg-gradient">
    <div class="container pt-lg-md">
      <div class="row">
        <div class="col-lg-7">
          <img src="{{url('public/img', $settings->home_index)}}" class="img-center img-fluid d-lg-block d-none">
        </div>
        <div class="col-lg-5">
          <div class="card bg-white shadow border-0">

              <h4 class="text-center mb-0 font-weight-bold pt-4">
                {{trans('auth.sign_up')}}
              </h4>
              <small class="btn-block text-center mt-2">{{ trans('auth.signup_welcome') }}</small>

            <div class="card-body px-lg-5 py-lg-5">

              @if (session('status'))
                      <div class="alert alert-success">
                        {{ session('status') }}
                      </div>
                    @endif

              @include('errors.errors-forms')

              @if($settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
              <div class="mb-2 w-100">

                @if ($settings->facebook_login == 'on')
                  <a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn flex-grow mb-2 w-100">
                    <i class="fab fa-facebook mr-2"></i> {{ __('auth.sign_up_with') }} Facebook
                  </a>
                @endif

                @if ($settings->twitter_login == 'on')
                <a href="{{url('oauth/twitter')}}" class="btn btn-twitter auth-form-btn mb-2 w-100">
                  <i class="fab fa-twitter mr-2"></i> {{ __('auth.sign_up_with') }} Twitter
                </a>
              @endif

                  @if ($settings->google_login == 'on')
                  <a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn flex-grow w-100">
                    <img src="{{ url('public/img/google.svg') }}" class="mr-2" width="18" height="18"> {{ __('auth.sign_up_with') }} Google
                  </a>
                @endif
                </div>

                <small class="btn-block text-center my-3 text-uppercase or">{{__('general.or')}}</small>

              @endif

              <form method="POST" action="{{ route('register') }}" id="formLoginRegister">
                  @csrf

                  @if($settings->captcha == 'on')
                    @captcha
                  @endif

                  <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                      </div>
                      <input class="form-control" value="{{ old('name')}}" placeholder="{{trans('auth.full_name')}}" name="name" type="text" required>
                    </div>
                  </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    <input class="form-control" value="{{ old('email')}}" placeholder="{{trans('auth.email')}}" name="email" type="text" required>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group input-group-alternative" id="showHidePassword">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
                    </div>
                    <input name="password" type="password" class="form-control" placeholder="{{trans('auth.password')}}" required>
                    <div class="input-group-append">
                      <span class="input-group-text c-pointer"><i class="fa fa-eye-slash"></i></span>
                  </div>
                  </div>
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

                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="agree_gdpr" required>
                    <label class="custom-control-label" for="customCheckRegister">
                      <span>{{trans('admin.i_agree_gdpr')}}
                        <a href="{{$settings->link_privacy}}" target="_blank">{{trans('admin.privacy_policy')}}</a>
                      </span>
                    </label>
                </div>

                <div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
                    <ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
                  </div>

                <div class="alert alert-success mb-0 mt-3 display-none" id="checkAccount"></div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary mt-4 w-100" id="btnLoginRegister"><i></i> {{trans('auth.sign_up')}}</button>
                </div>
              </form>

              @if ($settings->captcha == 'on')
                <small class="btn-block text-center mt-3">{{trans('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{trans('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('general.terms')}}</a></small>
              @endif
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12 text-center">
              <a href="{{url('login')}}" class="text-light">
                <small>{{trans('auth.already_have_an_account')}}</small>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
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
</script>
@endsection
