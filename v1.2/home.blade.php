@extends('layouts.app')

@section('content')
  <!-- jumbotron -->
  <div class="jumbotron homepage m-0 bg-gradient">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 second">
          <h1 class="display-4 pt-5 mb-3 text-white">{{trans('general.welcome_title')}}</h1>
          <p class="text-white">{{trans('general.subtitle_welcome')}}</p>
          <p>
            <a href="{{url('creators')}}" class="btn btn-lg btn-primary btn-w-mb px-4 mr-2" role="button">{{trans('general.explore')}}</a>

            <a href="{{ $settings->registration_active == '1' ? url('signup') : url('login')}}" class="btn btn-lg btn-main btn-outline-light btn-w px-4">
              {{trans('general.getting_started')}} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
          </p>
        </div>
        <div class="col-lg-7 first">
          <img src="{{url('public/img', $settings->home_index)}}" class="img-center img-fluid">
        </div>
      </div>
    </div>
  </div>
  <!-- ./ jumbotron -->

  <div class="section py-5 py-large">
    <div class="btn-block text-center mb-5">
      <h1 class="font-weight-light">{{trans('general.header_box_2')}}</h1>
      <p>
        {{trans('general.desc_box_2')}}
      </p>
      </div>

      <div class="container">
          <div class="row">
            <div class="col-lg-4">
              <div class="text-center">
                <img src="{{url('public/img', $settings->img_1)}}" class="img-center img-fluid" width="120">
                <h5 class="mt-3">{{trans('general.card_1')}}</h5>
                <p class="text-muted mt-3">{{trans('general.desc_card_1')}}</p>
              </div>
          </div>

          <div class="col-lg-4">
            <div class="text-center">
              <img src="{{url('public/img', $settings->img_2)}}" class="img-center img-fluid" width="120">
              <h5 class="mt-3">{{trans('general.card_2')}}</h5>
              <p class="text-muted mt-3">{{trans('general.desc_card_2')}}</p>
            </div>
        </div>

        <div class="col-lg-4">
          <div class="text-center">
            <img src="{{url('public/img', $settings->img_3)}}" class="img-center img-fluid" width="120">
            <h5 class="mt-3">{{trans('general.card_3')}}</h5>
            <p class="text-muted mt-3">{{trans('general.desc_card_3')}}</p>
          </div>
      </div>

      </div>
    </div>
  </div>

  <!-- Create profile -->
  <div class="section py-5 py-large">
    <div class="container">
      <div class="row align-items-center">
      <div class="col-12 col-lg-7 text-center mb-3">
        <img src="{{url('public/img', $settings->img_4)}}" alt="User" class="img-fluid">
      </div>
      <div class="col-12 col-lg-5">
        <h1 class="m-0 w-75 font-weight-light">{{trans('general.header_box_3')}}</h1>
        <div class="col-lg-9 col-xl-8 p-0">
          <p class="py-4 m-0 text-muted">{{trans('general.desc_box_3')}}</p>
        </div>
        <a href="{{ $settings->registration_active == '1' ? url('signup') : url('login')}}" class="btn btn-lg btn-main btn-outline-primary btn-w px-4">
          {{trans('general.getting_started')}} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
      </div>
    </div>
    </div><!-- End Container -->
  </div><!-- End Section -->

@if ($settings->widget_creators_featured == 'on')

    @if ($users->count() != 0)
    <!-- Users -->
    <div class="section py-5 py-large">
      <div class="btn-block text-center mb-5">
        <h1 class="font-weight-light">{{trans('general.creators_featured')}}</h1>
        <p>
          {{trans('general.desc_creators_featured')}}
        </p>
      </div>

      <div class="container">
        <div class="row">

        @if ($usersTotal > $users->total())
          <div class="w-100 mb-3 text-center">
            <a href="{{url('creators')}}" class="float-right link-border">{{trans('general.view_all_creators')}} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
          </div>
        @endif

          <div class="owl-carousel owl-theme">
            @foreach ($users as $response)
              @include('includes.listing-creators')
          @endforeach
          </div>
        </div><!-- End Row -->
      </div><!-- End Container -->
    </div><!-- End Section -->
  @endif
@endif

  @if ($settings->show_counter == 'on')
  <!-- Counter -->
  <div class="section py-5 py-large">
    <div class="btn-block text-center">
      <h1 class="font-weight-light">{{trans('general.our_numbers')}}</h1>
      <p>
        {{trans('general.our_numbers_subtitle')}}
      </p>
    </div>
    <div class="container mb-4">
      <div class="row">
        <div class="col-md-4">
          <div class="d-flex py-3 my-3 my-lg-0 justify-content-center">
            <span class="mr-3 display-4"><i class="fa fa-users align-baseline text-primary"></i></span>
            <div>
              <h3 class="mb-0">{!! Helper::formatNumbersStats($usersTotal) !!}</h3>
              <h5 class="font-weight-light">{{trans('general.creators')}}</h5>
            </div>
          </div>

        </div>
        <div class="col-md-4">
          <div class="d-flex py-3 my-3 my-lg-0 justify-content-center">
            <span class="mr-3 display-4"><i class="fa fa-photo-video align-baseline text-warning"></i></span>
            <div>
              <h3 class="mb-0">{!! Helper::formatNumbersStats(Updates::count()) !!}</h3>
              <h5 class="font-weight-light">{{trans('general.content_created')}}</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="d-flex py-3 my-3 my-lg-0 justify-content-center">
            <span class="mr-3 display-4"><i class="fa fa-hand-holding-usd align-baseline text-success"></i></span>
            <div>
              <h3 class="mb-0">@if($settings->currency_position == 'left') {{ $settings->currency_symbol }}@endif{!! Helper::formatNumbersStats(Transactions::whereApproved('1')->sum('earning_net_user')) !!}@if($settings->currency_position == 'right'){{ $settings->currency_symbol }} @endif</h3>
              <h5 class="font-weight-light">{{trans('general.earnings_of_creators')}}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

    <div class="jumbotron m-0 text-white text-center bg-gradient">
      <div class="container position-relative">
        <h1>{{trans('general.head_title_bottom')}}</h1>
        <p>{{trans('general.head_title_bottom_desc')}}</p>
        <p><a class="btn btn-outline-light btn-main p-3 px-5 btn-lg" href="{{ $settings->registration_active == '1' ? url('signup') : url('login')}}" role="button">
          {{trans('general.getting_started')}} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small>
        </a>
        </p>
      </div>
    </div>

@endsection

@section('javascript')

@if (session('success_verify'))
  <script type="text/javascript">
	swal({
		title: "{{ trans('general.welcome') }}",
		text: "{{ trans('users.account_validated') }}",
		type: "success",
		confirmButtonText: "{{ trans('users.ok') }}"
		});
    </script>
	 @endif

	 @if (session('error_verify'))
   <script type="text/javascript">
	swal({
		title: "{{ trans('general.error_oops') }}",
		text: "{{ trans('users.code_not_valid') }}",
		type: "error",
		confirmButtonText: "{{ trans('users.ok') }}"
		});
    </script>
	 @endif

@endsection
