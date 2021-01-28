@extends('admin.layout')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('admin.general_settings') }}
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
                  <h3 class="box-title">{{ trans('admin.general_settings') }}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/settings') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')

                 <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.name_site') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->title }}" name="title" class="form-control" placeholder="{{ trans('admin.title') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.email_admin') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->email_admin }}" name="email_admin" class="form-control" placeholder="{{ trans('admin.email_admin') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.link_terms') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->link_terms }}" name="link_terms" class="form-control" placeholder="https://yousite.com/p/terms">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.link_privacy') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->link_privacy }}" name="link_privacy" class="form-control" placeholder="https://yousite.com/p/privacy">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.link_cookies') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->link_cookies }}" name="link_cookies" class="form-control" placeholder="https://yousite.com/p/cookies">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                 <div class="box-body">
                   <div class="form-group">
                     <label class="col-sm-2 control-label">{{ trans('admin.date_format') }}</label>
                     <div class="col-sm-10">
                       <select name="date_format" class="form-control">
                         <option @if( $settings->date_format == 'M d, Y' ) selected="selected" @endif value="M d, Y"><?php echo date('M d, Y'); ?></option>
                           <option @if( $settings->date_format == 'd M, Y' ) selected="selected" @endif value="d M, Y"><?php echo date('d M, Y'); ?></option>
                         <option @if( $settings->date_format == 'Y-m-d' ) selected="selected" @endif value="Y-m-d"><?php echo date('Y-m-d'); ?></option>
                           <option @if( $settings->date_format == 'm/d/Y' ) selected="selected" @endif  value="m/d/Y"><?php echo date('m/d/Y'); ?></option>
                             <option @if( $settings->date_format == 'd/m/Y' ) selected="selected" @endif  value="d/m/Y"><?php echo date('d/m/Y'); ?></option>
                         </select>
                     </div>
                   </div>
                 </div><!-- /.box-body -->

                 <!-- Start Box Body -->
                 <div class="box-body">
                   <div class="form-group">
                     <label class="col-sm-2 control-label">{{ trans('admin.account_verification') }}</label>
                     <div class="col-sm-10">

                       <div class="radio">
                       <label class="padding-zero">
                         <input type="radio" name="account_verification" @if( $settings->account_verification == '1' ) checked="checked" @endif value="1" checked>
                         {{ trans('general.yes') }}
                       </label>
                     </div>

                     <div class="radio">
                       <label class="padding-zero">
                         <input type="radio" name="account_verification" @if( $settings->account_verification == '0' ) checked="checked" @endif value="0">
                         {{ trans('general.no') }}
                       </label>
                     </div>
                     </div>
                   </div>
                 </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Captcha</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="captcha" @if( $settings->captcha == 'on' ) checked="checked" @endif value="on" checked>
                          {{ trans('general.yes') }}
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="captcha" @if( $settings->captcha == 'off' ) checked="checked" @endif value="off">
                          {{ trans('general.no') }}
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.new_registrations') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="registration_active" @if( $settings->registration_active == '1' ) checked="checked" @endif value="1" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="registration_active" @if( $settings->registration_active == '0' ) checked="checked" @endif value="0">
                          Off
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.email_verification') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="email_verification" @if( $settings->email_verification == '1' ) checked="checked" @endif value="1" checked>
                          {{ trans('general.yes') }}
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="email_verification" @if( $settings->email_verification == '0' ) checked="checked" @endif value="0">
                          {{ trans('general.no') }}
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.show_counter') }}</label>
                      <div class="col-sm-10">

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="show_counter" @if ($settings->show_counter == 'on') checked="checked" @endif value="on">
                            On
                          </label>
                        </div>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_counter" @if ($settings->show_counter == 'off') checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.show_widget_creators') }}</label>
                      <div class="col-sm-10">

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="widget_creators_featured" @if ($settings->widget_creators_featured == 'on') checked="checked" @endif value="on">
                            On
                          </label>
                        </div>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="widget_creators_featured" @if ($settings->widget_creators_featured == 'off') checked="checked" @endif value="off">
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
