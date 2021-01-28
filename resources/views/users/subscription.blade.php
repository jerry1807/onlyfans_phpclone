@extends('layouts.app')

@section('title') {{trans('general.subscription')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="fa fa-sync mr-2"></i> {{trans('general.subscription')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.info_subscription')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>

                    {{ session('status') }}
                  </div>
                @endif

          @include('errors.errors-forms')

    @if (auth()->user()->verified_id == 'no' && $settings->requests_verify_account == 'on')
    <div class="alert alert-danger mb-3">
             <ul class="list-unstyled m-0">
               <li><i class="fa fa-exclamation-triangle"></i> {{trans('general.verified_account_info')}} <a href="{{url('settings/verify/account')}}" class="text-white link-border">{{trans('general.verify_account')}}</a></li>
             </ul>
           </div>
           @endif

          <form method="POST" action="{{ url('settings/subscription') }}">

            @csrf

            <div class="form-group">
              <label>{{trans('users.subscription_price')}} @if (auth()->user()->free_subscription == 'no' && Auth::user()->verified_id == 'yes') <a href="javascript:void(0)" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" class="link-border" data-content='{{ trans('general.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}'>{{ __('general.how_much_earn') }}</a> @endif</label>
              <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text">{{$settings->currency_symbol}}</span>
              </div>
                  <input class="form-control form-control-lg" id="subscriptionPrice" @if (Auth::user()->verified_id == 'no' || Auth::user()->verified_id == 'reject' || auth()->user()->free_subscription == 'yes') disabled @endif name="price" placeholder="{{trans('users.subscription_price')}}" value="{{$settings->currency_code == 'JPY' ? round(Auth::user()->price) : Auth::user()->price}}"  type="text">
              </div>
              <div class="text-muted btn-block mb-4">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" @if (Auth::user()->verified_id == 'no' || Auth::user()->verified_id == 'reject') disabled @endif name="free_subscription" value="yes" @if (auth()->user()->free_subscription == 'yes') checked @endif id="customSwitch1">
                  <label class="custom-control-label switch" for="customSwitch1">{{ trans('general.free_subscription') }}</label>
                </div>
              </div>
            </div><!-- End form-group -->

            <button class="btn btn-1 btn-success btn-block" @if (Auth::user()->verified_id == 'no' || Auth::user()->verified_id == 'reject') disabled @endif onClick="this.form.submit(); this.disabled=true; this.innerText='{{trans('general.please_wait')}}';" type="submit">{{trans('general.save_changes')}}</button>

          </form>
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
