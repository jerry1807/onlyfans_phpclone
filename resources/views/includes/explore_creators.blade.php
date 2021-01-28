<div class="mb-2">
  <h5 class="text-center mb-3 text-muted">{{trans('general.explore_creators')}}</h5>

  <ul class="list-group">

      @foreach ($users as $user)
        <div class="card-cover" style="height: 65px; background: @if ($user->cover != '') url({{ Storage::url(config('path.cover').$user->cover) }})  @endif #505050 center center;"></div>

        <li class="list-group-item mb-2">
               <div class="media">
                <div class="media-left mr-3">
                    <img class="media-object rounded-circle avatar-user-home" src="{{Storage::url(config('path.avatar').$user->avatar)}}"  width="65" height="65">
                </div>
                <div class="media-body">
                  <h6 class="media-heading mb-0">
                    <a href="{{url($user->username)}}">
                      <strong>{{$user->name}}</strong>
                    </a>
                     <small class="text-muted">{{'@'.$user->username}}</small>

                     @if($user->verified_id == 'yes')
                       <small class="verified" title="{{trans('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
                         <i class="fas fa-check-circle"></i>
                       </small>
                     @endif
                  </h6>
                  <small class="btn-block text-muted font-weight-bold">

                    @if($user->profession != '')
                      {{$user->profession}}
                    @endif

                    @if(isset($user->country()->country_name) && $user->profession == '')
                      {{$user->country()->country_name}}
                    @endif

                  </small>
                  @if( $user->story != '' )
                  <p class="mb-0 lh-inherit text-muted">{{strip_tags(Str::limit($user->story, 50, '...'))}}</p>
                  @endif

                  <div class="mt-3">
                    <a href="{{url($user->username)}}" class="btn btn-sm btn-1 btn-outline-primary stretched-link e-none">{{trans('general.go_to_page')}}</a>
                  </div>
                </div>
            </div>
        </li>
        @endforeach
      </ul>
   </div><!-- d-lg-none -->
