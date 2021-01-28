@extends('admin.layout')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('admin.social_login') }}

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
                  <h3 class="box-title">{{ trans('admin.social_login') }}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/social-login') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')

                 <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Facebook Client ID</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ env('FACEBOOK_CLIENT_ID') }}" name="FACEBOOK_CLIENT_ID" class="form-control" placeholder="">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                    <!-- Start Box Body -->
                     <div class="box-body">
                       <div class="form-group">
                         <label class="col-sm-2 control-label">Facebook Client Secret</label>
                         <div class="col-sm-10">
                           <input type="password" value="{{ env('FACEBOOK_CLIENT_SECRET') }}" name="FACEBOOK_CLIENT_SECRET" class="form-control" placeholder="">
                           <p class="help-block margin-bottom-zero">URL Callback: <strong>{{url('oauth/facebook/callback')}}</strong></p>
                         </div>
                       </div>
                     </div><!-- /.box-body -->

                     <!-- Start Box Body -->
                     <div class="box-body">
                       <div class="form-group">
                         <label class="col-sm-2 control-label">{{ trans('admin.facebook_login') }}</label>
                         <div class="col-sm-10">

                         	<div class="radio">
                           <label class="padding-zero">
                             <input type="radio" name="facebook_login" @if( $settings->facebook_login == 'on' ) checked="checked" @endif value="on" checked>
                             On
                           </label>
                         </div>

                         <div class="radio">
                           <label class="padding-zero">
                             <input type="radio" name="facebook_login" @if( $settings->facebook_login == 'off' ) checked="checked" @endif value="off">
                             Off
                           </label>
                         </div>

                         </div>
                       </div>
                     </div><!-- /.box-body -->

                     <hr>

                     <!-- Start Box Body -->
                      <div class="box-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Twitter Client ID</label>
                          <div class="col-sm-10">
                            <input type="text" value="{{ env('TWITTER_CLIENT_ID') }}" name="TWITTER_CLIENT_ID" class="form-control" placeholder="">
                          </div>
                        </div>
                      </div><!-- /.box-body -->

                        <!-- Start Box Body -->
                         <div class="box-body">
                           <div class="form-group">
                             <label class="col-sm-2 control-label">Twitter Client Secret</label>
                             <div class="col-sm-10">
                               <input type="password" value="{{ env('TWITTER_CLIENT_SECRET') }}" name="TWITTER_CLIENT_SECRET" class="form-control" placeholder="">
                               <p class="help-block margin-bottom-zero">URL Callback: <strong>{{url('oauth/twitter/callback')}}</strong></p>
                             </div>
                           </div>
                         </div><!-- /.box-body -->

                         <!-- Start Box Body -->
                         <div class="box-body">
                           <div class="form-group">
                             <label class="col-sm-2 control-label">{{ trans('admin.twitter_login') }}</label>
                             <div class="col-sm-10">

                              <div class="radio">
                               <label class="padding-zero">
                                 <input type="radio" name="twitter_login" @if( $settings->twitter_login == 'on' ) checked="checked" @endif value="on" checked>
                                 On
                               </label>
                             </div>

                             <div class="radio">
                               <label class="padding-zero">
                                 <input type="radio" name="twitter_login" @if( $settings->twitter_login == 'off' ) checked="checked" @endif value="off">
                                 Off
                               </label>
                             </div>

                             </div>
                           </div>
                         </div><!-- /.box-body -->

                         <hr>

                     <!-- Start Box Body -->
                      <div class="box-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Google Client ID</label>
                          <div class="col-sm-10">
                            <input type="text" value="{{ env('GOOGLE_CLIENT_ID') }}" name="GOOGLE_CLIENT_ID" class="form-control" placeholder="">
                          </div>
                        </div>
                      </div><!-- /.box-body -->

                        <!-- Start Box Body -->
                         <div class="box-body">
                           <div class="form-group">
                             <label class="col-sm-2 control-label">Google Client Secret</label>
                             <div class="col-sm-10">
                               <input type="password" value="{{ env('GOOGLE_CLIENT_SECRET') }}" name="GOOGLE_CLIENT_SECRET" class="form-control" placeholder="">
                               <p class="help-block margin-bottom-zero">URL Callback: <strong>{{url('oauth/google/callback')}}</strong></p>
                             </div>
                           </div>
                         </div><!-- /.box-body -->


                     <!-- Start Box Body -->
                     <div class="box-body">
                       <div class="form-group">
                         <label class="col-sm-2 control-label">{{ trans('admin.google_login') }}</label>
                         <div class="col-sm-10">

                         	<div class="radio">
                           <label class="padding-zero">
                             <input type="radio" name="google_login" @if( $settings->google_login == 'on' ) checked="checked" @endif value="on" checked>
                             On
                           </label>
                         </div>

                         <div class="radio">
                           <label class="padding-zero">
                             <input type="radio" name="google_login" @if( $settings->google_login == 'off' ) checked="checked" @endif value="off">
                             Off
                           </label>
                         </div>

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
