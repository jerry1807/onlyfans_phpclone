@foreach ($users as $user)

<a href="{{url($user->username)}}">
<div class="w-100 h-100 d-block" style="background: @if ($user->cover != '') url({{ Storage::url(config('path.cover').$user->cover) }})  @endif #505050 center center; border-radius: 6px;">

  <div class="card-cover position-relative" style="height: 50px">
    @if ($user->free_subscription == 'yes')
    <span class="badge-free px-2 py-1 text-uppercase position-absolute rounded">{{ __('general.free') }}</span>
  @endif
  </div>

  <li class="list-group-item mb-2" style="background: rgba(0,0,0,.30);">
         <div class="media">
          <div class="media-left mr-3">
              <img class="media-object rounded-circle avatar-user-home" src="{{Storage::url(config('path.avatar').$user->avatar)}}"  width="95" height="95">
          </div>
          <div class="media-body">
            <h6 class="media-heading mb-1">
              <a href="{{url($user->username)}}" class="stretched-link text-white">
                <strong>{{$user->name}}</strong>
              </a>
               @if($user->verified_id == 'yes')
                 <small class="verified mr-1 text-white" title="{{trans('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
                   <i class="feather icon-check-circle"></i>
                 </small>
               @endif

               @if ($user->featured == 'yes')
              <small class="text-featured" title="{{trans('users.creator_featured')}}" data-toggle="tooltip" data-placement="top">
                <i class="fas fa fa-award"></i>
              </small>
              @endif

               <small class=" text-white w-100 d-block">{{'@'.$user->username}}</small>
            </h6>

            <ul class="list-inline text-white">
              <li class="list-inline-item small"><i class="feather icon-file-text"></i> {{ Helper::formatNumber($user->updates()->count()) }}</li>
              <li class="list-inline-item small"><i class="feather icon-image"></i> {{ Helper::formatNumber($user->updates()->where('image', '<>', '')->count()) }}</li>
              <li class="list-inline-item small"><i class="feather icon-video"></i> {{ Helper::formatNumber($user->updates()->where('video', '<>', '')->orWhere('video_embed', '<>', '')->whereUserId($user->id)->count()) }}</li>
              <li class="list-inline-item small"><i class="feather icon-mic"></i> {{ Helper::formatNumber($user->updates()->where('music', '<>', '')->count()) }}</li>
              <li class="list-inline-item small"><i class="far fa-file-archive"></i> {{ Helper::formatNumber($user->updates()->where('file', '<>', '')->count()) }}</li>
            </ul>
          </div>
      </div>
  </li>
	</div><!-- cover -->
  </a>
  @endforeach
