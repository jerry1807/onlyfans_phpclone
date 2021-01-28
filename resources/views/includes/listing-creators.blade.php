	<div class="card card-updates h-100">
	<div class="card-cover" style="background: @if ($response->cover != '') url({{ Storage::url(config('path.cover').$response->cover) }})  @endif #505050 center center;">
		@if ($response->free_subscription == 'yes')
		<span class="badge-free px-2 py-1 text-uppercase position-absolute rounded">{{ __('general.free') }}</span>
	@endif
	</div>
	<div class="card-avatar">
		<a href="{{url($response->username)}}">
		<img src="{{Storage::url(config('path.avatar').$response->avatar)}}" width="95" height="95" alt="{{$response->name}}" class="img-user-small">
		</a>
	</div>
	<div class="card-body text-center">
			<h6 class="card-title pt-4">
				{{$response->name}}

				@if ($response->verified_id == 'yes')
					<small class="verified mr-1" title="{{trans('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
						<i class="feather icon-check-circle"></i>
					</small>
				@endif

				@if ($response->featured == 'yes')
				<small class="text-featured" title="{{trans('users.creator_featured')}}" data-toggle="tooltip" data-placement="top">
					<i class="fas fa fa-award"></i>
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

			<ul class="list-inline m-0">
				<li class="list-inline-item small"><i class="feather icon-file-text"></i> {{ Helper::formatNumber($response->updates()->count()) }}</li>
				<li class="list-inline-item small"><i class="feather icon-image"></i> {{ Helper::formatNumber($response->updates()->where('image', '<>', '')->count()) }}</li>
				<li class="list-inline-item small"><i class="feather icon-video"></i> {{ Helper::formatNumber($response->updates()->where('video', '<>', '')->orWhere('video_embed', '<>', '')->whereUserId($response->id)->count()) }}</li>
				<li class="list-inline-item small"><i class="feather icon-mic"></i> {{ Helper::formatNumber($response->updates()->where('music', '<>', '')->count()) }}</li>
				<li class="list-inline-item small"><i class="far fa-file-archive"></i> {{ Helper::formatNumber($response->updates()->where('file', '<>', '')->count()) }}</li>
			</ul>

			<p class="m-0 py-3 text-muted card-text">
				{{ Str::limit($response->story, 100, '...') }}
			</p>
			<a href="{{url($response->username)}}" class="btn btn-1 btn-sm btn-outline-primary">{{trans('general.go_to_page')}}</a>
	</div>
</div><!-- End Card -->
