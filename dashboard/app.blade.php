<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="fgjfjkfkjfkjfkfkfk">
  <meta name="keywords" content="fjfjfjfjfjfjfj" />
  <title>{{ Auth::check() && User::notificationsCount() ? '('.User::notificationsCount().') ' : '' }}@section('title')@show @if( isset( $settings->title ) ){{$settings->title}}@endif</title>
  <!-- Favicon -->
  <link href="{{asset('public/img/favicon.png')}}" rel="icon" type="image/png">

  @include('includes.css_general')

  @yield('css')

 @if($settings->google_analytics != '')
  {!! $settings->google_analytics !!}
  @endif

  <style type="text/css">
  .bg-gradient {
    background: url('{{url('public/img', $settings->bg_gradient)}}');
    background-size: cover;
  }
  @if($settings->color_default <> '')
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
  .bs-tooltip-bottom .arrow::before {
    border-bottom-color: {{$settings->color_default}}!important;
  }
  .arrow::before {
    border-top-color: {{$settings->color_default}}!important;
  }
  .nav-profile li.active {
    border-bottom: 3px solid {{$settings->color_default}}!important;
  }
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
  @include('includes.navbar')

  <main role="main">
    @yield('content')

    @if (Auth::guest() && !request()->route()->named('profile')
        || Auth::check() && request()->path() != '/' && !request()->route()->named('profile'))
    @include('includes.footer')
  @endif
  </main>

  @include('includes.javascript_general')

  @yield('javascript')

@auth
  <div id="bodyContainer"></div>
@endauth
</body>
</html>
