@extends('layouts.app')

@section('title') {{$title}} -@endsection

    @section('description_custom'){{$description ? $description : $settings->description}}@endsection
    @section('keywords_custom'){{$keywords ? $keywords.',' : null}}@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 font-montserrat">
            <img src="{{url('public/img-category', $image)}}" class="mr-2 rounded" width="30" /> {{$title}}</h2>
          <p class="lead text-muted mt-0">({{$users->total()}}) {{trans_choice('users.creators_in_this_category',$users->total() )}}</p>
        </div>
      </div>

      	<div class="btn-block mb-3 text-right">
      		<span>
      			{{trans('general.filter_by')}}

      			<select class="ml-2 custom-select w-auto" id="filter">
      					<option @if(request()->is('category', $slug)) selected @endif value="{{url('category', $slug)}}">{{trans('general.featured_creators')}}</option>
      					<option @if(request()->is('category/'.$slug.'/new')) selected @endif value="{{url('category/'.$slug.'','new')}}">{{trans('general.new_creators')}}</option>
      				</select>
      		</span>
      	</div>

      @include('includes.listing-categories')

      <div class="row">
        @if ($users->total() != 0)

          @foreach($users as $response)
          <div class="col-md-4 mb-4">
            @include('includes.listing-creators')
          </div><!-- end col-md-4 -->
          @endforeach

          @if($users->lastPage() > 1)
            <div class="w-100 d-block">
              {{ $users->links() }}
            </div>
          @endif

        @else
          <div class="col-md-12">
            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-user-slash ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{trans('general.not_found_creators_category')}}</h4>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection
