<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <title>{{ trans('admin.admin') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link href="{{ asset('public/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('public/css/fontawesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('public/admin/css/app.css')}}" rel="stylesheet" type="text/css" />
    <!-- IcoMoon CSS -->
    <link href="{{ asset('public/css/icomoon.css') }}" rel="stylesheet">
     <!-- Theme style -->
    <link href="{{ asset('public/admin/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link href="{{ asset('public/admin/css/skins/skin-black.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ url('public/img', $settings->favicon) }}" />

    <link href='//fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>

    <link href="{{ asset('public/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />

    @yield('css')

  <script type="text/javascript">
    var URL_BASE = "{{ url('/') }}";
    var url_file_upload = "{{route('upload.image', ['_token' => csrf_token()])}}";
    var delete_confirm = "{{trans('general.delete_confirm')}}";
    var yes_confirm = "{{trans('general.yes_confirm')}}";
    var cancel_confirm = "{{trans('general.cancel_confirm')}}";
    var timezone = "{{env('TIMEZONE')}}";
    var add_tag = "{{ trans("general.add_tag") }}";
    var choose_image = '{{trans('general.choose_image')}}';
    var formats_available = "{{ trans('general.formats_available') }}";
    var cancel_payment = "{!!trans('general.cancel_payment')!!}";
    var yes_cancel_payment = "{{trans('general.yes_cancel_payment')}}";
    var approve_confirm_verification = "{{trans('admin.approve_confirm_verification')}}";
    var yes_confirm_approve_verification = "{{trans('admin.yes_confirm_approve_verification')}}";
    var yes_confirm_verification = "{{trans('admin.yes_confirm_verification')}}";
    var delete_confirm_verification = "{{trans('admin.delete_confirm_verification')}}";
  </script>

  </head>
  <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |
  |               | sidebar-mini                            |
  |---------------------------------------------------------|
  -->
  <body class="skin-black sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="{{ url('panel/admin') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><i class="fas fa-bolt"></i></b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><i class="fas fa-bolt"></i> {{ trans('admin.admin') }}</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fa fa-bars"></i>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

            	<li>
            		<a href="{{ url('/') }}"><i class="glyphicon glyphicon-home myicon-right"></i> {{ trans('admin.view_site') }}</a>
            	</li>

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <img src="{{ Storage::url(config('path.avatar').auth()->user()->avatar) }}" class="user-image" alt="User Image" />
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <img src="{{ Storage::url(config('path.avatar').auth()->user()->avatar) }}" class="img-circle" alt="User Image" />
                    <p>
                      <small>{{ Auth::user()->name }}</small>
                    </p>
                  </li>

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="{{ url('settings/page') }}" class="btn btn-default btn-flat">{{ trans('general.edit_my_page') }}</a>
                    </div>
                    <div class="pull-right">
                      <a href="{{ url('logout') }}" class="btn btn-default btn-flat">{{ trans('users.logout') }}</a>
                    </div>
                  </li>
                </ul>
              </li>

            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="{{ Storage::url(config('path.avatar').auth()->user()->avatar) }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p class="text-overflow">{{ Auth::user()->name }}</p>
              <small class="btn-block text-overflow"><a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> {{ trans('general.online') }}</a></small>
            </div>
          </div>


          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">

            <li class="header">{{ trans('admin.main_menu') }}</li>

            <!-- Links -->
            <li @if(Request::is('panel/admin')) class="active" @endif>
            	<a href="{{ url('panel/admin') }}"><i class="iconmoon icon-Speedometter myicon-right"></i> <span>{{ trans('admin.dashboard') }}</span></a>
            </li><!-- ./Links -->

           <!-- Links -->
            <li class="treeview @if( Request::is('panel/admin/settings') || Request::is('panel/admin/settings/limits') ) active @endif">
            	<a href="{{ url('panel/admin/settings') }}"><i class="fa fa-cogs"></i> <span>{{ trans('admin.general_settings') }}</span> <i class="fa fa-angle-left pull-right"></i></a>

           		<ul class="treeview-menu">
                <li @if(Request::is('panel/admin/settings')) class="active" @endif><a href="{{ url('panel/admin/settings') }}"><i class="fas fa fa-angle-right"></i> {{ trans('admin.general') }}</a></li>
                <li @if(Request::is('panel/admin/settings/limits')) class="active" @endif><a href="{{ url('panel/admin/settings/limits') }}"><i class="fas fa fa-angle-right"></i> {{ trans('admin.limits') }}</a></li>
              </ul>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/maintenance/mode')) class="active" @endif>
            	<a href="{{ url('panel/admin/maintenance/mode') }}"><i class="fa fa-paint-roller"></i> <span>{{ trans('admin.maintenance_mode') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/billing')) class="active" @endif>
            	<a href="{{ url('panel/admin/billing') }}"><i class="fa fa-file-invoice"></i> <span>{{ trans('general.billing_information') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/settings/email')) class="active" @endif>
            	<a href="{{ url('panel/admin/settings/email') }}"><i class="fa fa-at"></i> <span>{{ trans('admin.email_settings') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/storage')) class="active" @endif>
            	<a href="{{ url('panel/admin/storage') }}"><i class="fa fa-database"></i> <span>{{ trans('admin.storage') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/theme')) class="active" @endif>
            	<a href="{{ url('panel/admin/theme') }}"><i class="fa fa-paint-brush"></i> <span>{{ trans('admin.theme') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/posts')) class="active" @endif>
            	<a href="{{ url('panel/admin/posts') }}"><i class="fa fa-user-edit"></i> <span>{{ trans('general.posts') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li class="@if( Request::is('panel/admin/subscriptions') ) active @endif">
            	<a href="{{ url('panel/admin/subscriptions') }}"><i class="fa fa-dollar-sign"></i> <span>{{ trans('admin.subscriptions') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li class="@if( Request::is('panel/admin/transactions') ) active @endif">
            	<a href="{{ url('panel/admin/transactions') }}"><i class="fa fa-file-invoice-dollar"></i> <span>{{ trans('admin.transactions') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
           <li @if (Request::is('panel/admin/deposits')) class="active" @endif>
             <a href="{{ url('panel/admin/deposits') }}"><i class="fa fa-money-bill-alt"></i> <span>{{ trans('general.deposits') }}</span></a>
           </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/members')) class="active" @endif>
            	<a href="{{ url('panel/admin/members') }}"><i class="glyphicon glyphicon-user"></i> <span>{{ trans('admin.members') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
           <li @if(Request::is('panel/admin/languages')) class="active" @endif>
             <a href="{{ url('panel/admin/languages') }}"><i class="fa fa-language"></i> <span>{{ trans('admin.languages') }}</span></a>
           </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/categories')) class="active" @endif>
            	<a href="{{ url('panel/admin/categories') }}"><i class="fa fa-list-ul"></i> <span>{{ trans('admin.categories') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/reports')) class="active" @endif>
            	<a href="{{ url('panel/admin/reports') }}"><i class="glyphicon glyphicon-ban-circle"></i> <span>
                {{ trans('admin.reports') }}
                @if( Reports::count() <> 0 ) <span class="label label-danger label-admin">{{Reports::count()}}</span> @endif
              </span>
            </a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/withdrawals')) class="active" @endif>
            	<a href="{{ url('panel/admin/withdrawals') }}"><i class="fa fa-university"></i> <span>
                {{ trans('general.withdrawals') }} @if(Withdrawals::where('status','pending')->count() != 0) <span class="label label-warning label-admin">{{Withdrawals::where('status','pending')->count()}}</span>  @endif
              </span>
            </a>
            </li><!-- ./Links -->



            <!-- Links -->
            <li @if(Request::is('panel/admin/verification/members')) class="active" @endif>
            	<a href="{{ url('panel/admin/verification/members') }}"><i class="far fa-check-circle myicon-right"></i>
                <span>{{ trans('admin.verification_requests') }} @if(VerificationRequests::where('status','pending')->count() != 0) <span class="label label-warning label-admin">{{VerificationRequests::where('status','pending')->count()}}</span>  @endif
                </span>
              </a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/pages')) class="active" @endif>
            	<a href="{{ url('panel/admin/pages') }}"><i class="glyphicon glyphicon-file"></i> <span>{{ trans('admin.pages') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/blog')) class="active" @endif>
            	<a href="{{ url('panel/admin/blog') }}"><i class="fa fa-pencil-alt"></i> <span>{{ trans('general.blog') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li class="treeview @if(Request::is('panel/admin/payments') || Request::is('panel/admin/payments/*')) active @endif">
            	<a href="{{ url('panel/admin/payments') }}"><i class="fa fa-credit-card"></i> <span>{{ trans('admin.payment_settings') }}</span> <i class="fa fa-angle-left pull-right"></i></a>

           		<ul class="treeview-menu">
                <li @if(Request::is('panel/admin/payments')) class="active" @endif><a href="{{ url('panel/admin/payments') }}"><i class="fas fa fa-angle-right"></i> {{ trans('admin.general') }}</a></li>

                  <?php
                  foreach (PaymentGateways::all() as $key) {
                    ?>
                    <li @if(Request::is('panel/admin/payments/'.$key->id)) class="active" @endif>
                      <a href="{{ url('panel/admin/payments/'.$key->id) }}"><i class="fas fa fa-angle-right"></i> {{ $key->type == 'bank' ? trans('general.bank_transfer') : $key->name }}</a>
                    </li>
                <?php
                  }
                ?>
              </ul>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/profiles-social')) class="active" @endif>
            	<a href="{{ url('panel/admin/profiles-social') }}"><i class="fa fa-share-alt"></i> <span>{{ trans('admin.profiles_social') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/social-login')) class="active" @endif>
            	<a href="{{ url('panel/admin/social-login') }}"><i class="fab fa-facebook myicon-right"></i> <span>{{ trans('admin.social_login') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/google')) class="active" @endif>
            	<a href="{{ url('panel/admin/google') }}"><i class="fab fa-google myicon-right"></i> <span>Google</span></a>
            </li><!-- ./Links -->

          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      @yield('content')

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- Default to the left -->
       &copy; <strong>{{ $settings->title }}</strong> - <?php echo date('Y'); ?>
      </footer>

    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <script src="{{ asset('public/plugins/jQuery/jQuery-2.1.4.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('public/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('public/plugins/fastclick/fastclick.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('public/admin/js/app.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('public/plugins/sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('public/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.js') }}" type="text/javascript"></script>
    <script src="{{{ asset('public/plugins/colorpicker/bootstrap-colorpicker.min.js') }}}" type="text/javascript"></script>
    <script src="{{ asset('public/admin/js/functions.js')}}" type="text/javascript"></script>

    @yield('javascript')

		 @if (session('info_message_demo'))
       <script type="text/javascript">
    		swal({
    			title: "{{ trans('general.error_oops') }}",
    			text: "Disabled on demo",
    			type: "info",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});
          </script>
   		 @endif
  </body>
</html>
