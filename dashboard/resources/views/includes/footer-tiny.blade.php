<div class="card">
  <div class="card-body">
    <small>&copy; {{date('Y')}} {{$settings->title}}</small>
    <ul class="list-inline mb-0">
      @foreach (Pages::all() as $page)
      <li class="list-inline-item"><a class="link-footer" href="{{ url('/p', $page->slug) }}">{{ $page->title }}</a></li>
      @endforeach
      <li class="list-inline-item"><a class="link-footer" href="{{ url('contact') }}">{{ trans('general.contact') }}</a></li>
      <li class="list-inline-item"><a class="link-footer" href="{{ url('blog') }}">{{ trans('general.blog') }}</a></li>

    <div class="btn-group dropup d-inline ">
      <li class="list-inline-item">
        <a class="link-footer dropdown-toggle text-decoration-none" href="javascript:;" data-toggle="dropdown">
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
    </ul>
  </div>
</div>
