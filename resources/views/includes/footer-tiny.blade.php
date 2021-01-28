<div class="card border-0 bg-transparent">
  <div class="card-body p-0">
    <small class="text-muted">&copy; {{date('Y')}} {{$settings->title}}</small>
    <ul class="list-inline mb-0 small">
      @foreach (Pages::all() as $page)
      <li class="list-inline-item"><a class="link-footer footer-tiny" href="{{ url('/p', $page->slug) }}">
        {{ Lang::has('pages.' . $page->slug) ? __('pages.' . $page->slug) : $page->title }}
      </a>
      </li>
      @endforeach
      <li class="list-inline-item"><a class="link-footer footer-tiny" href="{{ url('contact') }}">{{ trans('general.contact') }}</a></li>
      <li class="list-inline-item"><a class="link-footer footer-tiny" href="{{ url('blog') }}">{{ trans('general.blog') }}</a></li>

    @guest
    <div class="btn-group dropup d-inline">
      <li class="list-inline-item">
        <a class="link-footer dropdown-toggle text-decoration-none footer-tiny" href="javascript:;" data-toggle="dropdown">
          <i class="fa fa-globe mr-1"></i>
          @foreach (Languages::orderBy('name')->get() as $languages)
            @if( $languages->abbreviation == config('app.locale') ) {{ $languages->name }}  @endif
          @endforeach
      </a>

      <div class="dropdown-menu">
        @foreach (Languages::orderBy('name')->get() as $languages)
          <a @if ($languages->abbreviation != config('app.locale')) href="{{ url('lang', $languages->abbreviation) }}" @endif class="dropdown-item @if( $languages->abbreviation == config('app.locale') ) active text-white @endif">
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
