@extends('layouts.app')

@section('title') {{trans('general.bookmarks')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="far fa-bookmark mr-2"></i> {{trans('general.bookmarks')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.desc_bookmarks')}}</p>
        </div>
      </div>
      <div class="row">

        <div class="col-md-4 mb-4 py-lg-2">

          <button type="button" class="btn btn-primary btn-block mb-2 d-lg-none" type="button" data-toggle="collapse" data-target="#navbarUserHome" aria-controls="navbarCollapse" aria-expanded="false">
            <i class="fa fa-bars myicon-right"></i> {{trans('general.menu')}}
          </button>

        <div class="navbar-collapse collapse d-lg-block" id="navbarUserHome">
          @if ($users->total() != 0)
              @include('includes.explore_creators')
          @endif

            @include('includes.footer-tiny')

            </div><!-- navbarUserHome -->

        </div>

        <div class="col-md-6 col-lg-8 mb-5 mb-lg-0">

          @if($updates->total() != 0)

            @php
              $counterPosts = ($updates->total() - $settings->number_posts_show);
            @endphp

          <div class="grid-updates position-relative" id="updatesPaginator">
              @include('includes.updates')
          </div>

        @else
          <div class="grid-updates position-relative" id="updatesPaginator"></div>

        <div class="my-5 text-center no-updates">
          <span class="btn-block mb-3">
            <i class="far fa-bookmark ico-no-result"></i>
          </span>
        <h4 class="font-weight-light">{{trans('general.no_bookmarks')}}</h4>
        </div>

        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
