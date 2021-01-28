<div class="progress-wrapper display-none mb-3" id="progress">
    <div class="progress-info">
      <div class="progress-percentage">
        <span class="percent">0%</span>
      </div>
    </div>
    <div class="progress progress-xs">
      <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>

  @if (Auth::user()->verified_id == 'no')
  <div class="alert alert-danger mb-3">
           <ul class="list-unstyled m-0">
             <li><i class="fa fa-exclamation-triangle"></i> {{trans('general.verified_account_info')}} <a href="{{url('settings/verify/account')}}" class="text-white link-border">{{trans('general.verify_account')}}</a></li>
           </ul>
         </div>
         @endif

      <form method="POST" action="{{url('update/create')}}" enctype="multipart/form-data" id="formUpdateCreate">
        @csrf
      <div class="card mb-4">
        <div class="blocked display-none"></div>
        <div class="card-body pb-0">

          <div class="media">
          <span class="rounded-circle mr-3">
      				<img src="{{ Storage::url(config('path.avatar').auth()->user()->avatar) }}" class="rounded-circle avatarUser" width="60" height="60">
      		</span>

          <div class="media-body">
            <textarea name="description" id="updateDescription" data-post-length="{{$settings->update_length}}" rows="4" cols="40" placeholder="{{trans('general.write_something')}}" class="form-control textareaAutoSize border-0"></textarea>
          </div>
        </div><!-- media -->

            <input class="custom-control-input d-none" id="customCheckLocked" type="checkbox" {{auth()->user()->post_locked == 'yes' ? 'checked' : ''}} name="locked" value="yes">

          <!-- Alert -->
          <div class="alert alert-danger my-3 display-none" id="errorUdpate">
           <ul class="list-unstyled m-0" id="showErrorsUdpate"></ul>
         </div><!-- Alert -->

        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <div class="justify-content-between align-items-center">
            <input type="file" name="photo" id="filePhoto" accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3" class="visibility-hidden">

            <button type="button" class="btn btn-upload e-none btn-link text-primary rounded-pill" title="{{trans('general.upload_media')}}" onclick="$('#filePhoto').trigger('click')">
              <i class="far fa-image f-size-25"></i>
            </button>

            <button type="button" id="contentLocked" class="btn btn-upload e-none btn-link text-primary rounded-pill {{auth()->user()->post_locked == 'yes' ? '' : 'unlock'}}" data-toggle="tooltip" data-placement="top" title="{{trans('users.locked_content')}}">
              <i class="fas fa-lock{{auth()->user()->post_locked == 'yes' ? '' : '-open'}} f-size-20"></i>
            </button>

            <span id="previewImage"></span>
            <a id="removePhoto" class="btn e-none btn-danger text-white p-1 px-2 display-none" data-toggle="tooltip" data-placement="top" title="{{trans('general.delete')}}"><i class="fa fa-trash-alt"></i></a>

            <button type="submit" disabled class="btn btn-sm btn-primary rounded-pill float-right e-none" data-empty="{{trans('general.empty_post')}}" data-error="{{trans('general.error')}}" data-msg-error="{{trans('general.error_internet_disconnected')}}" id="btnCreateUpdate">
              <i></i> {{trans('general.publish')}}
            </button>

            <div id="the-count" class="float-right my-2 mr-2">
              <small id="maximum">{{$settings->update_length}}</small>
            </div>
          </div>
        </div><!-- card footer -->
      </div><!-- card -->
    </form>
