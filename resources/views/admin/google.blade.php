@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		Google
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
                  <h3 class="box-title">Google</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/google') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">reCAPTCHA Key</label>
                       <div class="col-sm-10">
                         <input type="text" value="{{ env('INVISIBLE_RECAPTCHA_SITEKEY') }}" name="INVISIBLE_RECAPTCHA_SITEKEY" class="form-control" placeholder="*************">
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                    <div class="box-body">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">reCAPTCHA Secret Key</label>
                        <div class="col-sm-10">
                          <input type="password" value="{{ env('INVISIBLE_RECAPTCHA_SECRETKEY') }}" name="INVISIBLE_RECAPTCHA_SECRETKEY" class="form-control" placeholder="*************">
                          <p class="help-block margin-bottom-zero"><a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a></p>
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Google Analytics</label>
                      <div class="col-sm-10">

                      	<textarea name="google_analytics" rows="8" id="google_analytics" class="form-control" placeholder="Google Analytics">{{ $settings->google_analytics }}</textarea>
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
