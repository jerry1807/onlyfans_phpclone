@extends('layouts.app')

@section('title'){{ trans('general.support_to').' '.$user->name }} -@endsection
  @section('description_custom'){{$user->username}} - {{strip_tags($user->story)}}@endsection

  @section('css')

  <meta property="og:type" content="website" />
  <meta property="og:image:width" content="200"/>
  <meta property="og:image:height" content="200"/>

  <!-- Current locale and alternate locales -->
  <meta property="og:locale" content="en_US" />
  <meta property="og:locale:alternate" content="es_ES" />

  <!-- Og Meta Tags -->
  <link rel="canonical" href="{{url($user->username)}}"/>
  <meta property="og:site_name" content="{{ trans('general.support_to').' '.$user->name }}"/>
  <meta property="og:url" content="{{url($user->username)}}"/>
  <meta property="og:image" content="{{Storage::url(config('path.avatar').$user->avatar)}}"/>

  <meta property="og:title" content="{{ trans('general.support_to').' '.$user->name }}"/>
  <meta property="og:description" content="{{strip_tags($user->story)}}"/>
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:image" content="{{Storage::url(config('path.avatar').$user->avatar)}}" />
  <meta name="twitter:title" content="{{ $user->name }}" />
  <meta name="twitter:description" content="{{strip_tags($user->story)}}"/>
  @endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row">
        <div class="col-md-8 mb-lg-0 py-5">
          @foreach ($updates as $response)
            @include('includes.updates')
          @endforeach

          @if($user->updates()->count() == 0)
            {{trans('general.no_results_found')}}
          @endif
        </div><!-- end col-md-9 -->

        <div class="col-md-4 py-lg-5">

          @if ($users->total() != 0)
              @include('includes.explore_creators')
          @endif

            @include('includes.footer-tiny')

        </div>

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
  
@endsection
