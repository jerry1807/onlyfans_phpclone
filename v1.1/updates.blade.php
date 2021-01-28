@foreach ($updates as $response)
	<div class="card mb-3 card-updates" data="{{$response->id}}">
	<div class="card-body">
		<div class="pinned_post text-muted small w-100 mb-2 {{ $response->fixed_post == '1' && request()->path() == $response->user()->username ? 'pinned-current' : 'display-none' }}">
			<i class="fa fa-thumbtack mr-2"></i> {{ trans('general.pinned_post') }}
		</div>
	<div class="media">
		<span class="rounded-circle mr-3">
			<a href="{{url($response->user()->username)}}">
				<img src="{{ Storage::url(config('path.avatar').$response->user()->avatar) }}" alt="{{$response->user()->name}}" class="rounded-circle avatarUser" width="60" height="60">
				</a>
		</span>

		<div class="media-body">
				<h5 class="mb-0 font-montserrat">
					<a href="{{url($response->user()->username)}}">
					{{$response->user()->name}}
				</a> <small class="text-muted">{{'@'.$response->user()->username}}</small>

				@if($response->user()->verified_id == 'yes')
					<small class="verified" title="{{trans('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
						<i class="fas fa-check-circle"></i>
					</small>
				@endif

				@if(Auth::check() && Auth::user()->id == $response->user()->id)
				<a href="javascript:void(0);" class="text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-ellipsis-h"></i>
				</a>

				<!-- Target -->
				<button class="d-none copy-url" id="url{{$response->id}}" data-clipboard-text="{{url($response->user()->username.'/post', $response->id)}}">{{trans('general.copy_link')}}</button>

				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">
					@if (request()->path() != $response->user()->username.'/post/'.$response->id)
						<a class="dropdown-item" href="{{url($response->user()->username.'/post', $response->id)}}">{{trans('general.go_to_post')}}</a>
					@endif

						<a class="dropdown-item pin-post" href="javascript:void(0);" data-id="{{$response->id}}">
							{{$response->fixed_post == '0' ? trans('general.pin_to_your_profile') : trans('general.unpin_from_profile') }}
						</a>

					<button class="dropdown-item" onclick="$('#url{{$response->id}}').trigger('click')">{{trans('general.copy_link')}}</button>
	        <a class="dropdown-item" href="{{url('update/edit',$response->id)}}">{{trans('general.edit_post')}}</a>
					{!! Form::open([
						'method' => 'POST',
						'url' => "update/delete/$response->id",
						'class' => 'd-inline'
					]) !!}

					@if(isset($inPostDetail))
					{!! Form::hidden('inPostDetail', 'true') !!}
				@endif

					{!! Form::button(trans('general.delete_post'), ['class' => 'dropdown-item actionDelete']) !!}
					{!! Form::close() !!}
	      </div>
			@endif

				@if(Auth::check() && Auth::user()->id != $response->user()->id && $response->locked == 'yes'
					&& Auth::user()->userSubscriptions()
						->where('stripe_id', '=', '')
						->whereDate('ends_at', '>=', Carbon\Carbon::today())
						->orWhere('stripe_status', 'active')
						->where('stripe_plan', $response->user()->plan)
            ->where('stripe_id', '<>', '')
						->whereUserId(Auth::user()->id)
						->count() != 0
					|| Auth::check() && Auth::user()->id != $response->user()->id && Auth::user()->role == 'admin' && Auth::user()->permission == 'all'
					|| Auth::check() && Auth::user()->id != $response->user()->id && $response->locked == 'no'
					)
					<button type="button" class="btn btn-sm text-danger e-none float-right" data-toggle="modal" data-target="#reportUpdate{{$response->id}}">
						<small><i class="fas fa-flag"></i> {{trans('admin.report')}}</small>
					</button>

			<div class="modal fade" id="reportUpdate{{$response->id}}" tabindex="-1" role="dialog" aria-hidden="true">
     		<div class="modal-dialog modal-danger modal-xs">
     			<div class="modal-content">
						<div class="modal-header">
              <h6 class="modal-title font-weight-light" id="modal-title-default"><i class="fas fa-flag"></i> {{trans('admin.report_update')}}</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>

					<!-- form start -->
					<form method="POST" action="{{url('report/update', $response->id)}}" enctype="multipart/form-data">
				  <div class="modal-body">
						@csrf
				    <!-- Start Form Group -->
            <div class="form-group">
              <label>{{trans('admin.please_reason')}}</label>
              	<select name="reason" class="form-control custom-select">
                    <option value="copyright">{{trans('admin.copyright')}}</option>
                    <option value="privacy_issue">{{trans('admin.privacy_issue')}}</option>
                    <option value="violent_sexual_content">{{trans('admin.violent_sexual_content')}}</option>
                  </select>
                  </div><!-- /.form-group-->
				      </div><!-- Modal body -->

							<div class="modal-footer">
								<button type="submit" class="btn btn-xs btn-white">{{trans('admin.report_update')}}</button>
								<button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">{{trans('admin.cancel')}}</button>
							</div>
							</form>
     				</div><!-- Modal content -->
     			</div><!-- Modal dialog -->
     		</div><!-- Modal -->
				@endif
			</h5>

				<small class="timeAgo text-muted" data="{{date('c', strtotime($response->date))}}"></small>
			@if ($response->locked == 'yes')
				<small class="text-muted" title="{{trans('users.content_locked')}}"><i class="fa fa-lock"></i></small>
			@endif
		</div><!-- media body -->
	</div><!-- media -->
</div><!-- card body -->

@if(Auth::check() && Auth::user()->id == $response->user()->id
	|| $response->locked == 'yes' && $response->image != ''
	|| $response->locked == 'yes' && $response->video != ''
	|| $response->locked == 'yes' && $response->music != ''
	|| Auth::check() && $response->locked == 'yes'
	&& Auth::user()->userSubscriptions()
		->where('stripe_id', '=', '')
		->whereDate('ends_at', '>=', Carbon\Carbon::today())
			->orWhere('stripe_status', 'active')
				->where('stripe_plan', $response->user()->plan)
				->where('stripe_id', '<>', '')
					->whereUserId(Auth::user()->id)
						->count() != 0
	|| Auth::check() && Auth::user()->role == 'admin' && Auth::user()->permission == 'all'
	|| $response->locked == 'no')
	<div class="card-body pt-0 pb-3">
		<p class="mb-0 update-text position-relative text-word-break">
			{!! Helper::linkText(Helper::checkText($response->description)) !!}
		</p>
	</div>
@endif

		@if(Auth::check() && Auth::user()->id == $response->user()->id
		|| Auth::check() && $response->locked == 'yes'
		&& Auth::user()->userSubscriptions()
			->where('stripe_id', '=', '')
			->whereDate('ends_at', '>=', Carbon\Carbon::today())
			->orWhere('stripe_status', 'active')
			->where('stripe_plan', $response->user()->plan)
			->where('stripe_id', '<>', '')
			->whereUserId(Auth::user()->id)
			->count() != 0
		|| Auth::check() && Auth::user()->role == 'admin' && Auth::user()->permission == 'all'
		|| $response->locked == 'no'
		)

	<div class="btn-block">

		@if($response->image != '')

			@php
			if ($response->img_type == 'gif') {
				$urlImg =  Storage::url(config('path.images').$response->image);
			} else {
				$urlImg =  url("files/preview", $response->image);
			}
			@endphp
			<a href="{{ Storage::url(config('path.images').$response->image) }}" data-group="gallery{{$response->id}}" class="js-smartPhoto w-100">
				<img src="{{url('files/preview', $response->image)}}?w=100&h=100" data-src="{{$urlImg}}?w=650&h=650" class="img-fluid lazyload d-inline-block w-100" alt="{{ e($response->description) }}">
			</a>
			@endif

	@if($response->video != '')
		<video id="video-{{$response->id}}" class="js-player w-100 @if (!request()->ajax())invisible @endif" controls>
			<source src="{{ Storage::url(config('path.videos').$response->video) }}" type="video/mp4" />
		</video>
	@endif

	@if($response->music != '')
		<div class="mx-3 border rounded">
			<audio id="music-{{$response->id}}" class="js-player w-100 @if (!request()->ajax())invisible @endif" controls>
				<source src="{{ Storage::url(config('path.music').$response->music) }}" type="audio/mp3">
				Your browser does not support the audio tag.
			</audio>
		</div>
	@endif
	</div><!-- btn-block -->

@else

	<div class="btn-block p-sm text-center content-locked pt-lg pb-lg">
		<span class="btn-block text-center mb-3"><i class="fa fa-lock ico-no-result"></i></span>
	 {{Auth::guest() ? trans('general.content_locked') : trans('general.content_locked_user_logged')}}
	</div>
	@endif

<div class="card-footer bg-white border-top-0">
    <h4>
			@php
			$likeActive = Auth::check() && Auth::user()->likes()->where('updates_id', $response->id)->where('status','1')->first();
			$bookmarkActive = Auth::check() && Auth::user()->bookmarks()->where('updates_id', $response->id)->first();

			if(Auth::check() && Auth::user()->id == $response->user()->id
			|| Auth::check() && $response->locked == 'yes' && Auth::user()
				->userSubscriptions()
				->where('stripe_id', '=', '')
				->whereDate('ends_at', '>=', Carbon\Carbon::today())
				->orWhere('stripe_status', 'active')
				->where('stripe_plan', $response->user()->plan)
				->where('stripe_id', '<>', '')
				->whereUserId(Auth::user()->id)
				->count() != 0
			|| Auth::check() && Auth::user()->role == 'admin' && Auth::user()->permission == 'all'
			|| Auth::check() && $response->locked == 'no') {
				$buttonLike = 'likeButton';
				$buttonBookmark = 'btnBookmark';
			} else {
				$buttonLike = null;
				$buttonBookmark = null;
			}
			@endphp

			<a href="javascript:void(0);" class="btnLike @if($likeActive)active @endif {{$buttonLike}} text-muted mr-2" @auth data-id="{{$response->id}}" @endauth>
				<i class="@if($likeActive)fas @else far @endif fa-heart"></i> <small><strong class="countLikes">{{Helper::formatNumber($response->likes()->count())}}</strong></small>
			</a>

			<span class="text-muted mr-2">
				<i class="far fa-comment"></i> <small class="font-weight-bold totalComments">{{Helper::formatNumber($response->comments()->count())}}</small>
			</span>

			<a href="javascript:void(0);" class="@if($bookmarkActive) text-primary @else text-muted @endif float-right {{$buttonBookmark}}" @auth data-id="{{$response->id}}" @endauth>
				<i class="@if($bookmarkActive)fas @else far @endif fa-bookmark"></i>
			</a>
		</h4>

@auth

<div class="container-media">
@if($response->comments()->count() != 0)

	@php
	  $comments = $response->comments()->take($settings->number_comments_show)->orderBy('id', 'DESC')->get();
	  $data = [];

	  if ($comments->count()) {
	      $data['reverse'] = collect($comments->values())->reverse();
	  } else {
	      $data['reverse'] = $comments;
	  }

	  $dataComments = $data['reverse'];
		$counter = ($response->comments()->count() - $settings->number_comments_show);
	@endphp

	@include('includes.comments')

@endif
	</div><!-- container-media -->

	@if(Auth::user()->id == $response->user()->id
	|| $response->locked == 'yes'
	&& Auth::user()
		->userSubscriptions()
		->where('stripe_id', '=', '')
		->whereDate('ends_at', '>=', Carbon\Carbon::today())
		->orWhere('stripe_status', 'active')
		->where('stripe_plan', $response->user()->plan)
		->where('stripe_id', '<>', '')
		->whereUserId(Auth::user()->id)
		->count() != 0
	|| Auth::user()->role == 'admin'
	&& Auth::user()->permission == 'all'
	|| $response->locked == 'no')

		<hr />

		<div class="alert alert-danger alert-small dangerAlertComments display-none">
			<ul class="list-unstyled m-0 showErrorsComments"></ul>
		</div><!-- Alert -->

		<div class="media position-relative">
			<div class="blocked display-none"></div>
			<span href="#" class="float-left">
				<img src="{{ Storage::url(config('path.avatar').auth()->user()->avatar) }}" class="rounded-circle mr-1 avatarUser" width="40">
			</span>
			<div class="media-body">
				<form action="{{url('comment/store')}}" method="post" class="comments-form">
					@csrf
					<input type="hidden" name="update_id" value="{{$response->id}}" />
				<input type="text" name="comment" class="form-control comments border-0" autocomplete="off" placeholder="{{trans('general.write_comment')}}"></div>
				</form>
			</div>
			@endif

			@endauth
  </div><!-- card-footer -->

</div><!-- card -->
@endforeach

<div class="card mb-3 pb-4 loadMoreSpin d-none">
	<div class="card-body">
		<div class="media">
		<span class="rounded-circle mr-3">
			<span class="item-loading position-relative loading-avatar"></span>
		</span>
		<div class="media-body">
			<h5 class="mb-0 item-loading position-relative loading-name"></h5>
			<small class="text-muted item-loading position-relative loading-time"></small>
		</div>
	</div>
</div>
	<div class="card-body pt-0 pb-3">
		<p class="mb-1 item-loading position-relative loading-text-1"></p>
		<p class="mb-1 item-loading position-relative loading-text-2"></p>
		<p class="mb-0 item-loading position-relative loading-text-3"></p>
	</div>
</div>

@php
	if (isset($ajaxRequest)) {
		$totalPosts = $total;
	} else {
		$totalPosts = $updates->total();
	}
@endphp

@if ($totalPosts > $settings->number_posts_show && $counterPosts >= 1)
	<button rel="next" class="btn btn-primary w-100 text-center loadPaginator d-none" id="paginator">
		{{trans('general.loadmore')}}
	</button>
@endif
