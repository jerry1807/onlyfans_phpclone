<div class="card card-updates">
	<div class="card-cover" style="background: @if ($response->cover != '') url({{ Storage::url(config('path.cover').$response->cover) }})  @endif #505050 center center;"></div>
	<div class="card-avatar">
		<a href="{{url($response->username)}}">
		<img src="{{Storage::url(config('path.avatar').$response->avatar)}}" width="89" height="89" alt="{{$response->name}}" class="img-user-small">
		</a>
	</div>
	<div class="card-body text-center">
			<h6 class="card-title pt-3">
				{{$response->name}}

				@if ($response->featured == 'yes')
				<small class="text-featured mr-1" title="{{trans('users.creator_featured')}}" data-toggle="tooltip" data-placement="top">
					<i class="fas fa fa-award"></i>
				</small>
			@endif

				@if ($response->verified_id == 'yes')
					<small class="verified" title="{{trans('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
						<i class="fas fa-check-circle"></i>
					</small>
				@endif
			</h6>
			<small class="text-muted">
				@if ($response->profession != '')
				{{ $response->profession }}

				@elseif (isset($response->country()->country_name) && $response->profession == '')
						<i class="fa fa-map-marker-alt mr-1"></i>	{{ $response->country()->country_name }}

				@endif
			</small>
			<p class="m-0 py-3 text-muted">
				{{ Str::limit($response->story, 100, '...') }}
			</p>
			<a href="{{url($response->username)}}" class="btn btn-1 btn-sm btn-outline-primary">{{trans('general.go_to_page')}}</a>
	</div>
</div><!-- End Card -->
