@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('admin.general_settings') }}

            		<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('admin.limits') }}
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	 @if(Session::has('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		        <i class="fa fa-check margin-separator"></i> {{ Session::get('success_message') }}
		    </div>
		@endif

        	<div class="content">

        		<div class="row">

        	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">{{ trans('admin.limits') }}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/settings/limits') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                  @include('errors.errors-forms')

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group margin-zero">
                      <label class="col-sm-2 control-label">{{ trans('admin.file_size_allowed') }}</label>
                      <div class="col-sm-10">
                      	<select name="file_size_allowed" class="form-control">
                            <option @if( $settings->file_size_allowed == 1024 ) selected="selected" @endif value="1024">1 MB</option>
            						  	<option @if( $settings->file_size_allowed == 2048 ) selected="selected" @endif value="2048">2 MB</option>
            						  	<option @if( $settings->file_size_allowed == 3072 ) selected="selected" @endif value="3072">3 MB</option>
            						  	<option @if( $settings->file_size_allowed == 4096 ) selected="selected" @endif value="4096">4 MB</option>
            						  	<option @if( $settings->file_size_allowed == 5120 ) selected="selected" @endif value="5120">5 MB</option>
            						  	<option @if( $settings->file_size_allowed == 10240 ) selected="selected" @endif value="10240">10 MB</option>
                            <option @if( $settings->file_size_allowed == 15360 ) selected="selected" @endif value="15360">15 MB</option>
                            <option @if( $settings->file_size_allowed == 20480 ) selected="selected" @endif value="20480">20 MB</option>
                            <option @if( $settings->file_size_allowed == 25600 ) selected="selected" @endif value="25600">25 MB</option>
                            <option @if( $settings->file_size_allowed == 30720 ) selected="selected" @endif value="30720">30 MB</option>
                            <option @if( $settings->file_size_allowed == 40960 ) selected="selected" @endif value="40960">40 MB</option>
                            <option @if( $settings->file_size_allowed == 51200 ) selected="selected" @endif value="51200">50 MB</option>
                            <option @if( $settings->file_size_allowed == 102400 ) selected="selected" @endif value="102400">100 MB</option>
                            <option @if( $settings->file_size_allowed == 153600 ) selected="selected" @endif value="153600">150 MB</option>
                            <option @if( $settings->file_size_allowed == 256000 ) selected="selected" @endif value="256000">250 MB</option>
                            <option @if( $settings->file_size_allowed == 307200 ) selected="selected" @endif value="307200">300 MB</option>
                            <option @if( $settings->file_size_allowed == 512000 ) selected="selected" @endif value="512000">500 MB</option>
                            <option @if( $settings->file_size_allowed == 716800 ) selected="selected" @endif value="716800">700 MB</option>
                            <option @if( $settings->file_size_allowed == 819200 ) selected="selected" @endif value="819200">800 MB</option>
                            <option @if( $settings->file_size_allowed == 1024000 ) selected="selected" @endif value="1024000">1 GB</option>
                          </select>
                          <span class="help-block ">{{ trans('admin.upload_max_filesize_info') }} <strong><?php echo str_replace('M', 'MB', ini_get('upload_max_filesize')) ?></strong></span>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group margin-zero">
                      <label class="col-sm-2 control-label">{{ trans('admin.file_size_allowed') }} ({{ trans('general.verify_account') }})</label>
                      <div class="col-sm-10">
                      	<select name="file_size_allowed_verify_account" class="form-control">
                            <option @if( $settings->file_size_allowed_verify_account == 1024 ) selected="selected" @endif value="1024">1 MB</option>
            						  	<option @if( $settings->file_size_allowed_verify_account == 2048 ) selected="selected" @endif value="2048">2 MB</option>
            						  	<option @if( $settings->file_size_allowed_verify_account == 3072 ) selected="selected" @endif value="3072">3 MB</option>
            						  	<option @if( $settings->file_size_allowed_verify_account == 4096 ) selected="selected" @endif value="4096">4 MB</option>
            						  	<option @if( $settings->file_size_allowed_verify_account == 5120 ) selected="selected" @endif value="5120">5 MB</option>
            						  	<option @if( $settings->file_size_allowed_verify_account == 10240 ) selected="selected" @endif value="10240">10 MB</option>
                            <option @if( $settings->file_size_allowed_verify_account == 15360 ) selected="selected" @endif value="15360">15 MB</option>
                          </select>
                          <span class="help-block ">{{ trans('admin.upload_max_filesize_info') }} <strong><?php echo str_replace('M', 'MB', ini_get('upload_max_filesize')) ?></strong></span>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group margin-zero">
                      <label class="col-sm-2 control-label">{{ trans('admin.post_length') }}</label>
                      <div class="col-sm-10">
                      	<select name="update_length" class="form-control">
                            <option @if( $settings->update_length == 100 ) selected="selected" @endif value="100">100 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->update_length == 150 ) selected="selected" @endif value="150">150 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->update_length == 200 ) selected="selected" @endif value="200">200 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->update_length == 250 ) selected="selected" @endif value="250">250 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->update_length == 300 ) selected="selected" @endif value="300">300 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->update_length == 400 ) selected="selected" @endif value="400">400 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->update_length == 500 ) selected="selected" @endif value="500">500 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->update_length == 700 ) selected="selected" @endif value="700">700 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->update_length == 1000 ) selected="selected" @endif value="1000">1000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->update_length == 2000 ) selected="selected" @endif value="2000">2000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->update_length == 3000 ) selected="selected" @endif value="3000">3000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->update_length == 4000 ) selected="selected" @endif value="4000">4000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->update_length == 5000 ) selected="selected" @endif value="5000">5000 {{ trans('admin.characters') }}</option>
                          </select>
                          <span class="help-block ">{{ trans('admin.post_length_info') }}</span>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group margin-zero">
                      <label class="col-sm-2 control-label">{{ trans('admin.story_length') }}</label>
                      <div class="col-sm-10">
                      	<select name="story_length" class="form-control">
                            <option @if( $settings->story_length == 100 ) selected="selected" @endif value="100">100 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->story_length == 150 ) selected="selected" @endif value="150">150 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->story_length == 200 ) selected="selected" @endif value="200">200 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->story_length == 250 ) selected="selected" @endif value="250">250 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->story_length == 300 ) selected="selected" @endif value="300">300 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->story_length == 400 ) selected="selected" @endif value="400">400 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->story_length == 500 ) selected="selected" @endif value="500">500 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->story_length == 700 ) selected="selected" @endif value="700">700 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->story_length == 1000 ) selected="selected" @endif value="1000">1000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->story_length == 2000 ) selected="selected" @endif value="2000">2000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->story_length == 3000 ) selected="selected" @endif value="3000">3000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->story_length == 4000 ) selected="selected" @endif value="4000">4000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->story_length == 5000 ) selected="selected" @endif value="5000">5000 {{ trans('admin.characters') }}</option>
                          </select>
                          <span class="help-block ">{{ trans('admin.story_length_info') }}</span>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group margin-zero">
                      <label class="col-sm-2 control-label">{{ trans('admin.comment_length') }}</label>
                      <div class="col-sm-10">
                      	<select name="comment_length" class="form-control">
                            <option @if( $settings->comment_length == 100 ) selected="selected" @endif value="100">100 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->comment_length == 150 ) selected="selected" @endif value="150">150 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->comment_length == 200 ) selected="selected" @endif value="200">200 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->comment_length == 250 ) selected="selected" @endif value="250">250 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->comment_length == 300 ) selected="selected" @endif value="300">300 {{ trans('admin.characters') }}</option>
            						  	<option @if( $settings->comment_length == 400 ) selected="selected" @endif value="400">400 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->comment_length == 500 ) selected="selected" @endif value="500">500 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->comment_length == 700 ) selected="selected" @endif value="700">700 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->comment_length == 1000 ) selected="selected" @endif value="1000">1000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->comment_length == 2000 ) selected="selected" @endif value="2000">2000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->comment_length == 3000 ) selected="selected" @endif value="3000">3000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->comment_length == 4000 ) selected="selected" @endif value="4000">4000 {{ trans('admin.characters') }}</option>
                            <option @if( $settings->comment_length == 5000 ) selected="selected" @endif value="5000">5000 {{ trans('admin.characters') }}</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                 <div class="box-body">
                   <div class="form-group margin-zero">
                     <label class="col-sm-2 control-label">{{ trans('admin.number_posts_show') }}</label>
                     <div class="col-sm-10">
                       <select name="number_posts_show" class="form-control">
                           <option @if( $settings->number_posts_show == 5 ) selected="selected" @endif value="5">5</option>
                           <option @if( $settings->number_posts_show == 10 ) selected="selected" @endif value="10">10</option>
                           <option @if( $settings->number_posts_show == 15 ) selected="selected" @endif value="15">15</option>
                           <option @if( $settings->number_posts_show == 20 ) selected="selected" @endif value="20">20</option>
                           <option @if( $settings->number_posts_show == 30 ) selected="selected" @endif value="30">30</option>
                         </select>
                     </div>
                   </div>
                 </div><!-- /.box-body -->

                 <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group margin-zero">
                    <label class="col-sm-2 control-label">{{ trans('admin.number_comments_show') }}</label>
                    <div class="col-sm-10">
                      <select name="number_comments_show" class="form-control">
                        <option @if( $settings->number_comments_show == 1 ) selected="selected" @endif value="1">1</option>
                          <option @if( $settings->number_comments_show == 2 ) selected="selected" @endif value="2">2</option>
                            <option @if( $settings->number_comments_show == 3 ) selected="selected" @endif value="3">3</option>
                              <option @if( $settings->number_comments_show == 4 ) selected="selected" @endif value="4">4</option>
                              <option @if( $settings->number_comments_show == 5 ) selected="selected" @endif value="5">5</option>
                              <option @if( $settings->number_comments_show == 10 ) selected="selected" @endif value="10">10</option>
                              <option @if( $settings->number_comments_show == 15 ) selected="selected" @endif value="15">15</option>
                              <option @if( $settings->number_comments_show == 20 ) selected="selected" @endif value="20">20</option>
                              <option @if( $settings->number_comments_show == 30 ) selected="selected" @endif value="30">30</option>
                        </select>
                    </div>
                  </div>
                </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">{{ trans('admin.save') }}</button>
                  </div><!-- /.box-footer -->
                </form>
              </div>
        		</div><!-- /.row -->
        	</div><!-- /.content -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
