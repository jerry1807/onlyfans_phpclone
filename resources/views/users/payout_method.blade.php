@extends('layouts.app')

@section('title') {{trans('users.payout_method')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="far fa-credit-card mr-2"></i> {{trans('users.payout_method')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.default_payout_method')}}:
            @if(Auth::user()->payment_gateway != '') <strong class="text-success">{{Auth::user()->payment_gateway == 'PayPal' ? 'PayPal' : trans('users.bank_transfer')}}</strong>
            @else <strong class="text-danger">{{trans('general.none')}}</strong> @endif
            </p>
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

      @if (auth()->user()->verified_id != 'yes')
      <div class="alert alert-danger mb-3">
               <ul class="list-unstyled m-0">
                 <li><i class="fa fa-exclamation-triangle"></i> {{trans('general.verified_account_info')}} <a href="{{url('settings/verify/account')}}" class="text-white link-border">{{trans('general.verify_account')}}</a></li>
               </ul>
             </div>
             @endif

      @if (auth()->user()->verified_id == 'yes')
          <div class="row justify-content-center">

            @php

            // PayPal
            $buttonPayPal = null;
            $formPayPal = null;

            // Bank
            $buttonBank = null;
            $formBank = null;

            if ($errors->has('bank_details')) {

                // Bank
                $buttonBank = ' active';
                $formBank = ' active show';

                // PayPal
                $buttonPayPal = null;
                $formPayPal = null;


            }

            if ($errors->has('email_paypal') || $errors->has('email_paypal_confirmation')) {

              // PayPal
              $buttonPayPal = ' active';
              $formPayPal = ' active show';

              // Bank
              $buttonBank = null;
              $formBank = null;
            }

            @endphp

            <div class="col-md-12">
              <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-md-row" role="tablist">
                  @if( $settings->payout_method_paypal == 'on' )
                  <li class="nav-item">
                    <a class="nav-link link-nav mb-sm-6 mb-md-0 mb-2 p-4{{$buttonPayPal}}" id="btnPayPal" data-toggle="tab" href="#formPayPal" role="tab" aria-controls="formPayPal" aria-selected="true">
                      <i class="fab fa-paypal mr-2"></i> PayPal
                      @if (auth()->user()->payment_gateway == 'PayPal') <span class="badge badge-pill badge-success">{{ __('general.default') }}</span> @endif
                    </a>
                  </li>
                @endif

                  @if( $settings->payout_method_bank == 'on' )
                  <li class="nav-item">
                    <a class="nav-link link-nav mb-sm-6 mb-md-0 p-4{{$buttonBank}}" id="btnBank" data-toggle="tab" href="#formBank" role="tab" aria-controls="formBank" aria-selected="false">
                      <i class="fa fa-university mr-2"></i> {{trans('users.bank_transfer')}}
                      @if (auth()->user()->payment_gateway == 'Bank') <span class="badge badge-pill badge-success">{{ __('general.default') }}</span> @endif
                    </a>
                  </li>
                @endif
                </ul>
              </div><!-- END COL-MD-12 -->
            </div><!-- ./ ROW -->
          </div><!-- ./ nav-wrapper -->

        <div class="tab-content">

          @if( $settings->payout_method_paypal == 'on' )
          <!-- FORM PAYPAL -->
          <div id="formPayPal" class="tab-pane fade{{$formPayPal}}" role="tabpanel">
          <form method="POST" action="{{ url('settings/payout/method/paypal') }}">
            @csrf

            <div class="form-group">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-paypal"></i></span>
                  </div>
                  <input class="form-control" name="email_paypal" value="{{Auth::user()->paypal_account == '' ? old('email_paypal') : Auth::user()->paypal_account}}" placeholder="{{trans('general.email_paypal')}}" required type="email">
                </div>
              </div>

              <div class="form-group">
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-envelope"></i></span>
                    </div>
                    <input class="form-control" name="email_paypal_confirmation" placeholder="{{trans('general.confirm_email_paypal')}}" required type="email">
                  </div>
                </div>
                <button class="btn btn-1 btn-success btn-block" type="submit">{{trans('general.save_payout_method')}}</button>
          </form>
        </div>
      @endif

      @if( $settings->payout_method_bank == 'on' )
          <!-- FORM BANK TRANSFER -->
          <div id="formBank" class="tab-pane fade{{$formBank}}" role="tabpanel">
          <form method="POST"  action="{{ url('settings/payout/method/bank') }}">

            @csrf
              <div class="form-group">
                <textarea name="bank_details" rows="5" cols="40" class="form-control" required placeholder="{{trans('users.bank_details')}}">{{Auth::user()->bank == '' ? old('bank_details') : Auth::user()->bank}}</textarea>
                </div>
                <button class="btn btn-1 btn-success btn-block" type="submit">{{trans('general.save_payout_method')}}</button>
          </form>
          </div>
        @endif


        </div><!-- ./ TAB-CONTENT -->
      @endif

        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
