@extends('layouts.app')

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 font-montserrat">{{trans('general.edit_post')}}</h2>
          <p class="lead text-muted mt-0"><a href="{{url()->previous()}}"><i class="fas fa-arrow-left"></i> {{trans('general.go_back')}}</a></p>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8 mb-5 mb-lg-0">

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

          <form method="POST" action="{{url('update/edit')}}" enctype="multipart/form-data" id="formUpdateEdit">
            @csrf
            <input type="hidden" name="id" value="{{request()->id}}" />
          <div class="card mb-4">
            <div class="blocked display-none"></div>
            <div class="card-body pb-0">

              <div class="media">
                <span class="rounded-circle mr-3">
            				<img src="{{ Storage::url(config('path.avatar').auth()->user()->avatar) }}" class="rounded-circle" width="60" height="60">
            		</span>

                <div class="media-body">
                <textarea name="description" id="updateDescription" data-post-length="{{$settings->update_length}}" rows="5" cols="40" placeholder="{{trans('general.write_something')}}" class="form-control textareaAutoSize  border-0">{{$data->description}}</textarea>
              </div>
            </div><!-- media -->

                <input class="custom-control-input d-none" id="customCheckLocked" type="checkbox" {{$data->locked == 'yes' ? 'checked' : ''}}  name="locked" value="yes">

                <!-- Alert -->
                <div class="alert alert-danger my-3 display-none" id="errorUdpate">
                 <ul class="list-unstyled m-0" id="showErrorsUdpate"></ul>
               </div><!-- Alert -->

               <div class="alert alert-success display-none" id="successUpdatePost">{{ trans('admin.success_update') }} <a href="{{ url($data->user()->username.'/post', $data->id) }}" class="link-border text-white">{{ trans('general.go_to_post') }}</a></div>

            </div><!-- card-body -->

            <div class="card-footer bg-white border-0 pt-0">
              <div class="justify-content-between align-items-center">
                <input type="file" name="photo" id="filePhoto" accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3" class="visibility-hidden">

                <button type="button" class="btn e-none btn-link text-primary rounded-pill btn-upload" data-toggle="tooltip" data-placement="top" title="{{$data->image == '' && $data->video == '' && $data->music == '' ? trans('general.upload_media') : trans('general.replace_media')}}" onclick="$('#filePhoto').trigger('click')">
                  <i class="far fa-image f-size-25"></i>
                </button>

                <input type="file" name="zip" id="fileZip" accept="application/x-zip-compressed" class="visibility-hidden">

                <button type="button" class="btn btn-upload e-none btn-link text-primary rounded-pill" data-toggle="tooltip" data-placement="top" title="{{trans('general.upload_file_zip')}}" onclick="$('#fileZip').trigger('click')">
                  <i class="far fa-file-archive f-size-25"></i>
                </button>

                <button type="button" id="contentLocked" class="btn e-none btn-link text-primary rounded-pill btn-upload {{$data->locked == 'yes' ? '' : 'unlock'}}" data-toggle="tooltip" data-placement="top" title="{{trans('users.locked_content')}}">
                  <i class="fas fa-lock{{$data->locked == 'yes' ? '' : '-open'}} f-size-20"></i>
                </button>

                <span id="previewImage"></span>
                <a id="removePhoto" class="btn e-none btn-danger text-white p-1 px-2 display-none" data-toggle="tooltip" data-placement="top" title="{{trans('general.delete')}}"><i class="fa fa-trash-alt"></i></a>

                <button type="submit" class="btn btn-sm btn-primary rounded-pill float-right" id="btnEditUpdate"><i></i> {{trans('users.save')}}</button>

                <div id="the-count" class="float-right my-2 mr-2">
                  <small id="maximum">{{$settings->update_length}}</small>
                </div>
              </div>
            </div><!-- card footer -->
          </div><!-- card -->
        </form>
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection

@section('javascript')
<script type="text/javascript">
$('#maximum').html({{$settings->update_length}}-$('#updateDescription').val().length);
</script>
@endsection
