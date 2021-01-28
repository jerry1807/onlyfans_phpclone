<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description_custom')@if(!Request::route()->named('seo') && !Request::route()->named('profile')){{trans('seo.description')}}@endif">
  <meta name="keywords" content="@yield('keywords_custom'){{ trans('seo.keywords') }}" />
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
  .sm-btn-size {
    color: #fff;
  }
  .input-group-text {
    border-color: #303030;
    background-color: #303030;
  }
  .form-control:focus, .custom-select:focus {
    border-color: #303030 !important;
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

h3, .h3 {
    font-size: 1.75rem;
}
h2, .h2 {
  font-size: 2rem;
}

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

@endif

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

    @if (Auth::guest() && ! request()->route()->named('profile')
        || Auth::check()
          && request()->path() != '/'
          && ! request()->is('my/bookmarks')
          && ! request()->route()->named('profile'))

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
