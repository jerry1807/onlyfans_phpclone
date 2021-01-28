<header>
	<nav class="navbar navbar-expand-lg navbar-inverse fixed-top p-nav @if(Auth::guest() && request()->path() == '/') scroll @else p-3 shadow-custom {{ auth()->check() && auth()->user()->dark_mode == 'on' ? 'bg-white' : 'navbar_background_color' }} link-scroll @endif">
		<div class="container-fluid d-flex">
			<a class="navbar-brand" href="{{url('/')}}">
				@if (Auth::check() && auth()->user()->dark_mode == 'on' )
					<img src="{{url('public/img', $settings->logo)}}" data-logo="{{$settings->logo}}" data-logo-2="{{$settings->logo_2}}" alt="{{$settings->title}}" class="logo align-baseline max-w-100" />
				@else
				<img src="{{url('public/img', Auth::guest() && request()->path() == '/' ? $settings->logo : $settings->logo_2)}}" data-logo="{{$settings->logo}}" data-logo-2="{{$settings->logo_2}}" alt="{{$settings->title}}" class="logo align-baseline max-w-100" />
			@endif
			</a>
			<button class="navbar-toggler @if(Auth::guest() && request()->path() == '/') text-white @endif" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<i class="fa fa-bars"></i>
			</button>

			<div class="collapse navbar-collapse" id="navbarCollapse">

			<div class="d-lg-none text-right pr-2 mb-2">
				<button type="button" class="navbar-toggler close-menu-mobile" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false">
					<span></span>
					<span></span>
				</button>
			</div>

				<ul class="navbar-nav mr-auto">
					<form class="form-inline my-lg-0 position-relative" method="get" action="{{url('creators')}}">
						<input id="searchCreatorNavbar" class="form-control input-search @if(Auth::guest() && request()->path() == '/') border-0 @endif" type="text" required name="q" autocomplete="off" minlength="3" placeholder="{{ trans('general.find_user') }}" aria-label="Search">
						<button class="btn btn-outline-success my-sm-0 button-search e-none" type="submit"><i class="fa fa-search"></i></button>

						<div class="dropdown-menu dd-menu-user position-absolute" style="width: 95%; top: 48px;" id="dropdownCreators">

							<button type="button" class="d-none" id="triggerBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

							<div class="w-100 text-center display-none py-2" id="spinnerSearch">
	                <span class="spinner-border spinner-border-sm align-middle text-primary"></span>
	              </div>

								<div id="containerCreators"></div>

								<div id="viewAll" class="display-none">
								    <a class="dropdown-item border-top py-2 text-center" href="#">{{ __('general.view_all') }}</a>
								</div>
					  </div><!-- dropdown-menu -->
					</form>

					@guest
						<li class="nav-item">
							<a class="nav-link" href="{{url('creators')}}">{{trans('general.explore')}}</a>
						</li>
					@endguest


					@if (Categories::count() != 0)
					<li class="nav-item dropdown d-none">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							{{trans('general.categories')}}
						</a>
						<div class="dropdown-menu dd-menu" aria-labelledby="dropdown01">
							@foreach(Categories::where('mode','on')->orderBy('name')->get() as $category)
							<a href="{{url('category',$category->slug)}}" class="dropdown-item @if(request()->path() == "category/$category->slug")active @endif">{{$category->name}}</a>
								@endforeach
						</div>
					</li>
				@endif
				</ul>

				<ul class="navbar-nav ml-auto">
					@guest
					<li class="nav-item mr-1">
						<a class="nav-link @if ($settings->registration_active == '0')  btn btn-main btn-primary pr-3 pl-3 @endif" href="{{$settings->home_style == 0 ? url('login') : url('/')}}">{{trans('auth.login')}}</a>
					</li>

					@if ($settings->registration_active == '1')
					<li class="nav-item">
						<a class="nav-link btn btn-main btn-primary pr-3 pl-3" href="{{$settings->home_style == 0 ? url('signup') : url('/')}}">{{trans('general.getting_started')}} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
					</li>
				@endif

			@else

					<li class="nav-item dropdown">
						<a class="nav-link px-2" href="{{url('/')}}" title="{{trans('admin.home')}}">
							<i class="feather icon-home icon-navbar"></i>
							<span class="d-lg-none align-middle ml-1">{{trans('admin.home')}}</span>
						</a>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link px-2" href="{{url('creators')}}" title="{{trans('general.explore')}}">
							<i class="far	fa-compass icon-navbar"></i>
							<span class="d-lg-none align-middle ml-1">{{trans('general.explore')}}</span>
						</a>
					</li>

				<li class="nav-item dropdown">
					<a href="{{url('messages')}}" class="nav-link px-2" title="{{ trans('general.messages') }}">

						<span class="notify @if (auth()->user()->messagesInbox() != 0) d-block @endif" id="noti_msg">
							{{ auth()->user()->messagesInbox() }}
							</span>

						<i class="feather icon-send icon-navbar"></i>
						<span class="d-lg-none align-middle ml-1">{{ trans('general.messages') }}</span>
					</a>
				</li>

				<li class="nav-item dropdown">
					<a href="{{url('notifications')}}" class="nav-link px-2" title="{{ trans('general.notifications') }}">

						<span class="notify @if (auth()->user()->notifications()->where('status', '0')->count()) d-block @endif" id="noti_notifications">
							{{ auth()->user()->notifications()->where('status', '0')->count() }}
							</span>

						<i class="far fa-bell icon-navbar"></i>
						<span class="d-lg-none align-middle ml-1">{{ trans('general.notifications') }}</span>
					</a>
				</li>

				<li class="nav-item dropdown">
					<a class="nav-link" href="#" id="nav-inner-success_dropdown_1" role="button" data-toggle="dropdown">
						<img src="{{Storage::url(config('path.avatar').auth()->user()->avatar)}}" alt="User" class="rounded-circle avatarUser mr-1" width="24" height="24">
						<span class="d-lg-none">{{Auth::user()->first_name}}</span>
						<i class="fas fa-angle-down m-0"></i>
					</a>
					<div class="dropdown-menu mb-1 dropdown-menu-right dd-menu-user" aria-labelledby="nav-inner-success_dropdown_1">
						@if(Auth::user()->role == 'admin')
								<a class="dropdown-item" href="{{url('panel/admin')}}">{{trans('admin.admin')}}</a>
								<div class="dropdown-divider"></div>
						@endif

						<span class="dropdown-item balance">
							{{trans('general.balance')}}: {{Helper::amountFormatDecimal(Auth::user()->balance)}}
						</span>
						<a class="dropdown-item" href="{{url('my/wallet')}}">{{trans('general.wallet')}}: <span class="balanceWallet">{{Helper::amountFormatDecimal(Auth::user()->wallet)}}</span></a>
							<div class="dropdown-divider"></div>

						<a class="dropdown-item" href="{{url(Auth::User()->username)}}">{{trans('general.my_page')}}</a>
						<a class="dropdown-item" href="{{url('dashboard')}}">{{trans('admin.dashboard')}}</a>
						<a class="dropdown-item" href="{{url('my/payments')}}">{{trans('general.payments')}}</a>
						<a class="dropdown-item" href="{{url('my/subscribers')}}">{{trans('users.my_subscribers')}}</a>
						<a class="dropdown-item" href="{{url('my/subscriptions')}}">{{trans('users.my_subscriptions')}}</a>
						<a class="dropdown-item" href="{{url('my/bookmarks')}}">{{trans('general.bookmarks')}}</a>

						<div class="dropdown-divider"></div>

						@if (auth()->user()->dark_mode == 'off')
							<a class="dropdown-item" href="{{url('mode/dark')}}">{{trans('general.dark_mode')}}</a>
						@else
							<a class="dropdown-item" href="{{url('mode/light')}}">{{trans('general.light_mode')}}</a>
						@endif

						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="{{url('logout')}}">{{trans('auth.logout')}}</a>
					</div>
				</li>

				<li class="nav-item">
					<a class="nav-link btn btn-main btn-primary pr-3 pl-3" href="{{url('settings/page')}}">
						{{trans('general.edit_my_page')}} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
				</li>

					@endguest

				</ul>
			</div>
		</div>
	</nav>
</header>
