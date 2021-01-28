@extends('layouts.app')

@section('title') {{$title}} -@endsection

@section('content')
  <section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 text-break">{{$title}}</h2>
          <p class="lead text-muted mt-0">{{trans('users.the_best_creators_is_here')}}
            @guest
              @if ($settings->registration_active == '1')
                <a href="{{url('signup')}}" class="link-border">{{ trans('general.join_now') }}</a>
              @endif
          @endguest</p>
        </div>
      </div>

      @if (! request()->get('q'))
      	<div class="btn-block mb-3 text-right">
      		<span>
      			{{trans('general.filter_by')}}

      			<select class="ml-2 custom-select w-auto" id="filter">
      					<option @if(request()->is('creators')) selected @endif value="{{url('creators')}}">{{trans('general.featured_creators')}}</option>
      					<option @if(request()->is('creators/new')) selected @endif value="{{url('creators/new')}}">{{trans('general.new_creators')}}</option>
      				</select>
      		</span>
      	</div>
      @endif

      @include('includes.listing-categories')

      <div class="row">
        @if( $users->total() != 0 )

          @foreach($users as $response)
          <div class="col-md-4 mb-4">
            @include('includes.listing-creators')
          </div><!-- end col-md-4 -->
          @endforeach

          @if($users->hasPages())
            <div class="w-100 d-block">
              {{ $users->appends(['q' => request('q')])->links() }}
            </div>
          @endif

        @else
          <div class="col-md-12">
            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-user-slash ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{trans('general.no_results_found')}}</h4>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection
