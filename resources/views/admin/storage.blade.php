@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('admin.storage') }}
          </h4>
        </section>

        <!-- Main content -->
        <section class="content">

        	 @if (session('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		       <i class="fa fa-check margin-separator"></i> {{ session('success_message') }}
		    </div>
		@endif

        	<div class="content">

        		<div class="row">

        	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">{{ trans('admin.storage') }}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/storage') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')

          <!-- Start Box Body -->
           <div class="box-body">
             <div class="form-group">
               <label class="col-sm-2 control-label">App URL</label>
               <div class="col-sm-10">
                 <input type="text" value="{{ env('APP_URL') }}" name="APP_URL" class="form-control" placeholder="App URL">
                 <p class="help-block margin-bottom-zero">{{trans('admin.notice_app_url')}} <strong>{{url('/')}}</strong></p>
               </div>
             </div>
           </div><!-- /.box-body -->

              <!-- Start Box Body -->
               <div class="box-body">
                 <div class="form-group">
                   <label class="col-sm-2 control-label">{{trans('admin.disk')}}</label>
                   <div class="col-sm-10">
                     <select name="FILESYSTEM_DRIVER" class="form-control custom-select">
                       <option @if (env('FILESYSTEM_DRIVER') == 'default') selected @endif value="default">{{trans('admin.disk_local')}}</option>
                       <option @if (env('FILESYSTEM_DRIVER') == 's3') selected @endif value="s3">Amazon S3</option>
                       <option @if (env('FILESYSTEM_DRIVER') == 'dospace') selected @endif value="dospace">DigitalOcean</option>
                       <option @if (env('FILESYSTEM_DRIVER') == 'wasabi') selected @endif value="wasabi">Wasabi</option>
                     </select>
                   </div>
                 </div>
               </div><!-- /.box-body -->

               <hr/>

                 <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Amazon Key</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ env('AWS_ACCESS_KEY_ID') }}" name="AWS_ACCESS_KEY_ID" class="form-control" placeholder="Amazon Key">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">Amazon Secret</label>
                       <div class="col-sm-10">
                         <input type="text" value="{{ env('AWS_SECRET_ACCESS_KEY') }}" name="AWS_SECRET_ACCESS_KEY" class="form-control" placeholder="Amazon Secret">
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                    <div class="box-body">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Amazon Region</label>
                        <div class="col-sm-10">
                          <input type="text" value="{{ env('AWS_DEFAULT_REGION') }}" name="AWS_DEFAULT_REGION" class="form-control" placeholder="Amazon Region">
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                    <!-- Start Box Body -->
                     <div class="box-body">
                       <div class="form-group">
                         <label class="col-sm-2 control-label">Amazon Bucket</label>
                         <div class="col-sm-10">
                           <input type="text" value="{{ env('AWS_BUCKET') }}" name="AWS_BUCKET" class="form-control" placeholder="Amazon Bucket">
                         </div>
                       </div>
                     </div><!-- /.box-body -->

                    <hr />

                    <!-- Start Box Body -->
                     <div class="box-body">
                       <div class="form-group">
                         <label class="col-sm-2 control-label">DigitalOcean Key</label>
                         <div class="col-sm-10">
                           <input type="text" value="{{ env('DOS_ACCESS_KEY_ID') }}" name="DOS_ACCESS_KEY_ID" class="form-control" placeholder="DigitalOcean Key">
                         </div>
                       </div>
                     </div><!-- /.box-body -->

                     <!-- Start Box Body -->
                      <div class="box-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label">DigitalOcean Secret</label>
                          <div class="col-sm-10">
                            <input type="text" value="{{ env('DOS_SECRET_ACCESS_KEY') }}" name="DOS_SECRET_ACCESS_KEY" class="form-control" placeholder="DigitalOcean Secret">
                          </div>
                        </div>
                      </div><!-- /.box-body -->

                      <!-- Start Box Body -->
                       <div class="box-body">
                         <div class="form-group">
                           <label class="col-sm-2 control-label">DigitalOcean Region</label>
                           <div class="col-sm-10">
                             <input type="text" value="{{ env('DOS_DEFAULT_REGION') }}" name="DOS_DEFAULT_REGION" class="form-control" placeholder="DigitalOcean Region">
                           </div>
                         </div>
                       </div><!-- /.box-body -->

                       <!-- Start Box Body -->
                        <div class="box-body">
                          <div class="form-group">
                            <label class="col-sm-2 control-label">DigitalOcean Bucket</label>
                            <div class="col-sm-10">
                              <input type="text" value="{{ env('DOS_BUCKET') }}" name="DOS_BUCKET" class="form-control" placeholder="DigitalOcean Bucket">
                            </div>
                          </div>
                        </div><!-- /.box-body -->

                        <hr />

                        <!-- Start Box Body -->
                         <div class="box-body">
                           <div class="form-group">
                             <label class="col-sm-2 control-label">Wasabi Key</label>
                             <div class="col-sm-10">
                               <input type="text" value="{{ env('WAS_ACCESS_KEY_ID') }}" name="WAS_ACCESS_KEY_ID" class="form-control" placeholder="Wasabi Key">
                               <p class="help-block margin-bottom-zero"><strong>Important:</strong> Wasabi in trial mode does not allow public files, you must send an email to <strong>support@wasabi.com</strong> to enable public files, or avoid trial mode.</p>
                             </div>
                           </div>
                         </div><!-- /.box-body -->

                         <!-- Start Box Body -->
                          <div class="box-body">
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Wasabi Secret</label>
                              <div class="col-sm-10">
                                <input type="text" value="{{ env('WAS_SECRET_ACCESS_KEY') }}" name="WAS_SECRET_ACCESS_KEY" class="form-control" placeholder="Wasabi Secret">
                              </div>
                            </div>
                          </div><!-- /.box-body -->

                          <!-- Start Box Body -->
                           <div class="box-body">
                             <div class="form-group">
                               <label class="col-sm-2 control-label">Wasabi Region</label>
                               <div class="col-sm-10">
                                 <input type="text" value="{{ env('WAS_DEFAULT_REGION') }}" name="WAS_DEFAULT_REGION" class="form-control" placeholder="Wasabi Region">
                               </div>
                             </div>
                           </div><!-- /.box-body -->

                           <!-- Start Box Body -->
                            <div class="box-body">
                              <div class="form-group">
                                <label class="col-sm-2 control-label">Wasabi Bucket</label>
                                <div class="col-sm-10">
                                  <input type="text" value="{{ env('WAS_BUCKET') }}" name="WAS_BUCKET" class="form-control" placeholder="Wasabi Bucket">
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
