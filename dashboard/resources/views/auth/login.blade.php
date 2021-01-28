@extends('layouts.app')

@section('title') {{trans('auth.login')}} -@endsection

@section('content')
  <div class="jumbotron home m-0 bg-gradient">
    <div class="container pt-lg-md">
      <div class="row">
        <div class="col-lg-7">
          <img src="{{url('public/img', $settings->home_index)}}" class="img-center img-fluid">
        </div>
        <div class="col-lg-5">
          <div class="card bg-light shadow border-0">

          <div class="card-header bg-white py-4">
            <h4 class="text-center mb-0 font-weight-bold">
              {{trans('auth.welcome_back')}}
            </h4>
            <small class="btn-block text-center mt-2">{{ trans('auth.login_welcome') }}</small>
          </div>

            <div class="card-body px-lg-5 py-lg-5">

              @if(session('login_required'))
    			<div class="alert alert-danger" id="dangerAlert">
                		<i class="fa fa-exclamation-triangle"></i> {{session('login_required')}}
                		</div>
                	@endif

              @include('errors.errors-forms')

              <form method="POST" action="{{ route('login') }}">
                  @csrf

                  <input type="hidden" name="return" value="{{ count($errors) > 0 ? old('return') : url()->previous() }}">

                  @if($settings->captcha == 'on')
                    @captcha
                  @endif

                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    <input class="form-control" required value="{{ old('username_email') }}" placeholder="{{ trans('auth.username_or_email') }}" name="username_email" type="text">

                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
                    </div>
                    <input name="password" required type="password" class="form-control" placeholder="{{ trans('auth.password') }}">
                  </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="custom-control-label" for=" customCheckLogin">
                    <span>{{trans('auth.remember_me')}}</span>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4 w-100">{{trans('auth.login')}}</button>
                </div>
              </form>

              @if($settings->facebook_login == 'on' || $settings->google_login == 'on')
              <div class="mb-2 w-100 d-flex">

                @if ($settings->facebook_login == 'on')
                  <a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn flex-grow mr-1 @if ($settings->google_login == 'on') w-50 @else w-100 @endif">
                    <i class="fab fa-facebook mr-2"></i>Facebook
                  </a>
                @endif

                  @if ($settings->google_login == 'on')
                  <a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn flex-grow ml-1 @if ($settings->facebook_login == 'on') w-50 @else w-100 @endif">
                    <img src="{{ url('public/img/google.svg') }}" width="18" height="18"> Google
                  </a>
                @endif
                </div>
              @endif

              @if ($settings->captcha == 'on')
                <small class="btn-block text-center">{{trans('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{trans('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('general.terms')}}</a></small>
              @endif

            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="{{url('password/reset')}}" class="text-light">
                <small>{{trans('auth.forgot_password')}}</small>
              </a>
            </div>
            @if($settings->registration_active == '1')
            <div class="col-6 text-right">
              <a href="{{url('signup')}}" class="text-light">
                <small>{{trans('auth.not_have_account')}}</small>
              </a>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascript')
<script type="text/javascript">
	@if(count($errors) > 0)
    	scrollElement('#dangerAlert');
    @endif
</script>
@endsection
