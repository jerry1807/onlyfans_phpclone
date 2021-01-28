@extends('layouts.app')

@section('title') {{ $response->title }} | {{ trans('general.blog') }} @endsection
  @section('description_custom'){{strip_tags($response->content)}}@endsection

  @section('css')
    <meta property="og:type" content="website" />
    <meta property="og:image:width" content="650"/>
    <meta property="og:image:height" content="430"/>

    <!-- Current locale and alternate locales -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:locale:alternate" content="es_ES" />

    <!-- Og Meta Tags -->
    <link rel="canonical" href="{{url()->current()}}"/>
    <meta property="og:site_name" content="{{ $response->title }}"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:image" content="{{Storage::url(config('path.admin').$response->image)}}"/>

    <meta property="og:title" content="{{ $response->title }}"/>
    <meta property="og:description" content="{{strip_tags($response->content)}}"/>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:image" content="{{Storage::url(config('path.admin').$response->image)}}" />
    <meta name="twitter:title" content="{{ $response->title  }}" />
    <meta name="twitter:description" content="{{strip_tags($response->content)}}"/>
    @endsection

@section('content')
  <section class="section section-sm">
    <div class="container">
      <div class="row">
            <div class="col-md-8 py-5">
              <div class="row no-gutters border rounded flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="card-cover w-100 img-post" style="background: @if ($response->image != '') url({{ Storage::url(config('path.admin').$response->image) }})  @endif #505050 no-repeat center center; background-size: cover; "></div>
                <div class="col p-4 d-flex flex-column position-static">
                  <small class="d-inline-block mb-2">{{ trans('general.by') }} {{ $response->user()->name }} </small>
                  <h3 class="mb-0">{{ $response->title }}</h3>
                  <div class="mb-3 text-muted">{{ Helper::formatDate($response->date) }}</div>
                  <div class="card-text mb-auto content-p">{!! $response->content !!}</div>

                  <div class="mt-4 justify-content-middle">
                    <hr>
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" class="mr-2">
                      <i class="fab fa-facebook mr-1"></i> {{trans('general.share')}}
                    </a>

                    <a target="_blank" href="https://twitter.com/intent/tweet?url={{url()->current()}}&text={{ $response->title }}">
                      <i class="fab fa-twitter mr-1"></i> Tweet
                    </a>
                  </div>

                </div>
              </div>

              @if ($blogs->count() != 0)
                <div class="row mt-5">

                  <h3 class="my-4 w-100 text-center">{{ trans('general.others_posts') }}</h3>

                @foreach ($blogs as $response)
                  <div class="col-md-6">
                    <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                      <div class="card-cover w-100" style="height:250px; background: @if ($response->image != '') url({{ Storage::url(config('path.admin').$response->image) }})  @endif #505050 center center;"></div>
                      <div class="col p-4 d-flex flex-column position-static">
                        <small class="d-inline-block mb-2">{{ trans('general.by') }} {{ $response->user()->name }} </small>
                        <h3 class="mb-0">{{ $response->title }}</h3>
                        <div class="mb-1 text-muted">{{ Helper::formatDate($response->date) }}</div>
                        <p class="card-text mb-auto">{{ Str::limit(strip_tags($response->content), 120, '...') }}</p>
                        <a href="{{ url('blog/post', $response->id).'/'.$response->slug }}" class="stretched-link">{{ trans('general.continue_reading') }}</a>
                      </div>
                    </div>
                  </div>
                @endforeach

                </div>
              @endif
            </div>

          <div class="col-md-4 mb-4 py-lg-5">
            @if ($users->total() != 0)
                @include('includes.explore_creators')
            @endif

              @include('includes.footer-tiny')
          </div><!-- end col-md-4 -->
      </div>
    </div>
  </section>
@endsection
