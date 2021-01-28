@extends('admin.layout')

@section('css')
<link href="{{{ asset('public/plugins/iCheck/all.css') }}}" rel="stylesheet" type="text/css" />
<link href="{{{ asset('public/plugins/colorpicker/bootstrap-colorpicker.min.css') }}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{{ trans('admin.admin') }}}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{{ trans('admin.theme') }}}
          </h4>
        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="content">

        		<div class="row">

        	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">{{{ trans('admin.theme') }}}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="post" action="{{{ url('panel/admin/theme') }}}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">

                  @if(session('success_message'))
                    <div class="box-body">
          		    <div class="alert alert-success">
          		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          								<span aria-hidden="true">×</span>
          								</button>
          		        <i class="fa fa-check margin-separator"></i> {{ session('success_message') }}
          		    </div>
                  </div>
          		@endif

              @if(session('error_max_upload_size'))
                <div class="alert alert-danger">
        		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        								<span aria-hidden="true">×</span>
        								</button>
        		      <i class="fa fa-warning margin-separator"></i>  {{trans('general.max_upload_files', ['post_size' => ini_get("post_max_size")."B"] )}}
        		    </div>
              @endif

					@include('errors.errors-forms')


                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.logo') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->logo)}}" class="logo-theme">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="logo" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.note_logo_white') }}</p>
                      <p class="help-block">{{ trans('general.recommended_size') }} 487x144 px (PNG)</p>

              <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.logo_blue') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->logo_2)}}" class="w-150">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="logo_2" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 487x144 px (PNG)</p>

              <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Favicon</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->favicon)}}">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="favicon" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 48x48 px (PNG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.index_image_top') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->home_index)}}" class="w-200">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="index_image_top" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 884x592 px</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.background') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->bg_gradient)}}" class="w-400">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="background" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 1441x480 px</p>

                        <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
          					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
          					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
          					    </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.image_index_1') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->img_1)}}" class="w-120">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="image_index_1" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 120x120 px</p>

                        <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
          					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
          					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
          					    </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.image_index_2') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->img_2)}}" class="w-120">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="image_index_2" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 120x120 px</p>

                        <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
          					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
          					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
          					    </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.image_index_3') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->img_3)}}" class="w-120">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="image_index_3" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 120x120 px</p>

                        <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
          					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
          					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
          					    </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.image_index_4') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->img_4)}}" class="w-120">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="image_index_4" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 362x433 px</p>

                        <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
          					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
          					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
          					    </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.avatar_default') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{ Storage::url(config('path.avatar').$settings->avatar) }}" class="w-200">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="avatar" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('general.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('general.recommended_size') }} 250x250 px</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('general.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <!-- Color Picker -->
                      <div class="form-group">
                        <label class="col-sm-2 control-label">@lang('admin.default_color'):</label>

                        <div class="col-sm-2">
                        <div class="input-group my-colorpicker2">
                          <div class="input-group-addon color-picker">
                            <i class="color-picker-tiny"></i>
                          </div>
                          <input type="text" readonly="readonly" name="color" value="{{$settings->color_default}}" class="form-control d-none">
                        </div>
                        <!-- /.input group -->
                      </div>
                      </div>
                      <!-- /.form group -->
                  </div><!-- /.box-body -->

                  <hr>
                  <div class="box-footer">
                    <a href="{{{ url('panel/admin/categories') }}}" class="btn btn-default">{{{ trans('admin.cancel') }}}</a>
                    <button type="submit" class="btn btn-success pull-right">{{{ trans('admin.save') }}}</button>
                  </div><!-- /.box-footer -->
                </form>
              </div>
        		</div><!-- /.row -->
        	</div><!-- /.content -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
