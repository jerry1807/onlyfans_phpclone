<div class="mb-2">
  <h6 class="mb-3 text-muted font-weight-light">
    {{trans('general.explore_creators')}}

@auth
  @if ($users->total() > 3)
    <a href="javascript:void(0);" class="float-right refresh_creators">
      <i class="fa fa-sync mr-2"></i>
    </a>
    @endif

  @else
    <a href="{{url('creators')}}" class="float-right">{{ __('general.view_all') }} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
@endauth
  </h6>

  <ul class="list-group">

    <div id="containerRefreshCreators">
      @include('includes.listing-explore-creators')
    </div><!-- containerRefreshCreators -->
  </ul>
</div><!-- d-lg-none -->
