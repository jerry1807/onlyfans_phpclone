@extends('layouts.app')

@section('content')
  <!-- SEE FULL IMAGE REAL PIXELS -->
  	<div class="wrap-full-image">
      <div class="container-image"></div>
  		<div class="btn-block details-full-image">
  		  <span class="icon-close" id="closeFull">×</span>
  		</div>
  		<div class="container-image-full"></div>
  	</div><!-- SEE FULL IMAGE REAL PIXELS -->

  <section class="section section-sm">
    <div class="container pt-5">
      <div class="row">
        <div class="col-md-8 second">

        @if (auth()->user()->verified_id == 'yes')
          @include('includes.form-post')
        @endif

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
            <i class="fa fa-photo-video ico-no-result"></i>
          </span>
        <h4 class="font-weight-light">{{trans('general.no_posts_posted')}}</h4>
        </div>

        @endif
        </div><!-- end col-md-12 -->

        <div class="col-md-4 mb-4 first">

          <button type="button" class="btn btn-primary btn-block mb-2 d-lg-none" type="button" data-toggle="collapse" data-target="#navbarUserHome" aria-controls="navbarCollapse" aria-expanded="false">
            <i class="fa fa-bars myicon-right"></i> {{trans('general.menu')}}
          </button>

          <div class="navbar-collapse collapse d-lg-block sticky-top" id="navbarUserHome">

            @if ($users->total() != 0)
                @include('includes.explore_creators')
            @endif

            <div class="d-lg-block d-none">
              @include('includes.footer-tiny')
            </div>

         </div><!-- navbarUserHome -->
        </div><!-- col-md -->

      </div>
    </div>
  </section>
@endsection

@section('javascript')

@if (session('noty_error'))
  <script type="text/javascript">
   swal({
     title: "{{ trans('general.error_oops') }}",
     text: "{{ trans('general.already_sent_report') }}",
     type: "error",
     confirmButtonText: "{{ trans('users.ok') }}"
     });
     </script>
@endif

@if (session('noty_success'))
<script type="text/javascript">
     swal({
       title: "{{ trans('general.thanks') }}",
       text: "{{ trans('general.reported_success') }}",
       type: "success",
       confirmButtonText: "{{ trans('users.ok') }}"
       });
       </script>
 @endif

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
