<!-- FOOTER -->
<div class="py-5 @if (Auth::check() && auth()->user()->dark_mode == 'off' || Auth::guest() ) footer_background_color footer_text_color @else bg-white @endif @if (Auth::check() && auth()->user()->dark_mode == 'off' && $settings->footer_background_color == '#ffffff' || Auth::guest() && $settings->footer_background_color == '#ffffff' ) border-top @endif">
<footer class="container">
  <div class="row">
    <div class="col-md-3">
      <a href="{{url('/')}}">
        @if (Auth::check() && auth()->user()->dark_mode == 'on' )
          <img src="{{url('public/img', $settings->logo)}}" alt="{{$settings->title}}" class="max-w-125">
        @else
          <img src="{{url('public/img', $settings->logo_2)}}" alt="{{$settings->title}}" class="max-w-125">
      @endif
      </a>
      @if($settings->twitter != ''
          || $settings->facebook != ''
          || $settings->instagram != ''
          || $settings->pinterest != ''
          || $settings->youtube != ''
          || $settings->github != ''
          )
      <div class="w-100">
        <span class="w-100">{{trans('general.keep_connect_with_us')}} {{trans('general.follow_us_social')}}</span>
        <ul class="list-inline list-social">
          @if ($settings->twitter != '')
          <li class="list-inline-item"><a href="{{$settings->twitter}}" target="_blank" class="ico-social"><i class="fab fa-twitter"></i></a></li>
        @endif

        @if ($settings->facebook != '')
          <li class="list-inline-item"><a href="{{$settings->facebook}}" target="_blank" class="ico-social"><i class="fab fa-facebook"></i></a></li>
          @endif

          @if ($settings->instagram != '')
          <li class="list-inline-item"><a href="{{$settings->instagram}}" target="_blank" class="ico-social"><i class="fab fa-instagram"></i></a></li>
        @endif

          @if ($settings->pinterest != '')
          <li class="list-inline-item"><a href="{{$settings->pinterest}}" target="_blank" class="ico-social"><i class="fab fa-pinterest"></i></a></li>
          @endif

          @if ($settings->youtube != '')
          <li class="list-inline-item"><a href="{{$settings->youtube}}" target="_blank" class="ico-social"><i class="fab fa-youtube"></i></a></li>
          @endif

          @if ($settings->github != '')
          <li class="list-inline-item"><a href="{{$settings->github}}" target="_blank" class="ico-social"><i class="fab fa-github"></i></a></li>
          @endif
        </ul>
      </div>
    @endif
    </div>
    <div class="col-md-3">
      <h5>@lang('general.about')</h5>
      <ul class="list-unstyled">
        @foreach (Pages::all() as $page)
        <li><a class="link-footer" href="{{ url('/p', $page->slug) }}">
          {{ Lang::has('pages.' . $page->slug) ? __('pages.' . $page->slug) : $page->title }}
        </a>
      </li>
        @endforeach
        <li><a class="link-footer" href="{{ url('contact') }}">{{ trans('general.contact') }}</a></li>
        <li><a class="link-footer" href="{{ url('blog') }}">{{ trans('general.blog') }}</a></li>
      </ul>
    </div>
    @if (Categories::count() != 0)
    <div class="col-md-3">
      <h5>@lang('general.categories')</h5>
      <ul class="list-unstyled">
        @foreach (Categories::where('mode','on')->orderBy('name')->take(6)->get() as $category)
        <li><a class="link-footer" href="{{ url('category', $category->slug) }}">{{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}</a></li>
        @endforeach

        @if (Categories::count() > 6)
          <li><a class="link-footer" href="{{ url('creators') }}">{{ trans('general.explore') }} <i class="fa fa-long-arrow-alt-right"></i></a></li>
          @endif
      </ul>
    </div>
  @endif
    <div class="col-md-3">
      <h5>@lang('general.links')</h5>
      <ul class="list-unstyled">
      @guest
        <li><a class="link-footer" href="{{$settings->home_style == 0 ? url('login') : url('/')}}">{{ trans('auth.login') }}</a></li><li>
          @if ($settings->registration_active == '1')
        <li><a class="link-footer" href="{{$settings->home_style == 0 ? url('signup') : url('/')}}">{{ trans('auth.sign_up') }}</a></li><li>
        @endif
        @else
          <li><a class="link-footer" href="{{ url(Auth::User()->username) }}">{{ trans('general.my_page') }}</a></li><li>
          <li><a class="link-footer" href="{{ url('settings/page') }}">{{ trans('general.edit_my_page') }}</a></li><li>
          <li><a class="link-footer" href="{{ url('my/subscriptions') }}">{{ trans('users.my_subscriptions') }}</a></li><li>
          <li><a class="link-footer" href="{{ url('logout') }}">{{ trans('users.logout') }}</a></li><li>
      @endguest

      @guest
      <div class="btn-group dropup d-inline ">
        <li>
          <a class="link-footer dropdown-toggle text-decoration-none" href="javascript:;" data-toggle="dropdown">
            <i class="fa fa-globe mr-1"></i>
            @foreach (Languages::orderBy('name')->get() as $languages)
              @if( $languages->abbreviation == config('app.locale') ) {{ $languages->name }}  @endif
            @endforeach
        </a>

        <div class="dropdown-menu">
          @foreach (Languages::orderBy('name')->get() as $languages)
            <a @if ($languages->abbreviation != config('app.locale')) href="{{ url('lang', $languages->abbreviation) }}" @endif class="dropdown-item dropdown-lang @if( $languages->abbreviation == config('app.locale') ) active text-white @endif">
            @if ($languages->abbreviation == config('app.locale')) <i class="fa fa-check mr-1"></i> @endif {{ $languages->name }}
            </a>
            @endforeach
        </div>
        </li>
      </div><!-- dropup -->
      @endguest

      </ul>
    </div>
  </div>
</footer>
</div>

<footer class="py-3 @if (Auth::check() && auth()->user()->dark_mode == 'off' || Auth::guest() ) footer_background_color @endif text-center">
  <div class="container">
    <div class="row">
      <div class="col-md-12 copyright">
        &copy; {{date('Y')}} {{$settings->title}}
      </div>
    </div>
  </div>
</footer>
