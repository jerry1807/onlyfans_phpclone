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
            		{{ trans('admin.payment_settings') }}
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
                  <h3 class="box-title"><strong>{{ trans('admin.payment_settings') }}</strong></h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/payments') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')

                <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('admin.currency_code') }}</label>
                    <div class="col-sm-10">
                      <input type="text" value="{{ $settings->currency_code }}" name="currency_code" class="form-control" placeholder="{{ trans('admin.currency_code') }}">
                    </div>
                  </div>
                </div><!-- /.box-body -->

                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('admin.currency_symbol') }}</label>
                    <div class="col-sm-10">
                      <input type="text" value="{{ $settings->currency_symbol }}" name="currency_symbol" class="form-control" placeholder="{{ trans('admin.currency_symbol') }}">
                      <p class="help-block">{{ trans('admin.notice_currency') }}</p>
                    </div>
                  </div>
                </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.fee_commission') }}</label>
                      <div class="col-sm-10">
                      	<select name="fee_commission" class="form-control">
                          @for ($i=1; $i <= 50; ++$i)
                            <option @if( $settings->fee_commission == $i ) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                            @endfor
                            </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.min_subscription_amount') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->min_subscription_amount }}" name="min_subscription_amount" class="form-control onlyNumber" placeholder="{{ trans('admin.min_subscription_amount') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.max_subscription_amount') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->max_subscription_amount }}" name="max_subscription_amount" class="form-control onlyNumber" placeholder="{{ trans('admin.max_subscription_amount') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.amount_min_withdrawal') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->amount_min_withdrawal }}" name="amount_min_withdrawal" class="form-control onlyNumber" placeholder="{{ trans('general.amount_min_withdrawal') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                 <div class="box-body">
                   <div class="form-group">
                     <label class="col-sm-2 control-label">{{ trans('admin.currency_position') }}</label>
                     <div class="col-sm-10">
                       <select name="currency_position" class="form-control">
                         <option @if( $settings->currency_position == 'left' ) selected="selected" @endif value="left">{{$settings->currency_symbol}}99 - {{trans('admin.left')}}</option>
                         <option @if( $settings->currency_position == 'right' ) selected="selected" @endif value="right">99{{$settings->currency_symbol}} {{trans('admin.right')}}</option>
                         </select>
                     </div>
                   </div>
                 </div><!-- /.box-body -->

                 <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('general.decimal_format') }}</label>
                    <div class="col-sm-10">
                      <select name="decimal_format" class="form-control input-lg">
                        <option @if( $settings->decimal_format == 'dot' ) selected="selected" @endif value="dot">1,989.95</option>
                        <option @if( $settings->decimal_format == 'comma' ) selected="selected" @endif value="comma">1.989,95</option>
                        </select>
                    </div>
                  </div>
                </div><!-- /.box-body -->

                 <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('admin.days_process_withdrawals') }}</label>
                    <div class="col-sm-10">
                      <select name="days_process_withdrawals" class="form-control">
                        @for ($i=1; $i <= 30; ++$i)
                          <option @if( $settings->days_process_withdrawals == $i ) selected="selected" @endif value="{{$i}}">{{$i}} ({{trans_choice('general.days', $i)}})</option>
                          @endfor
                          </select>
                    </div>
                  </div>
                </div><!-- /.box-body -->

               <div class="box-footer">
                 <button type="submit" class="btn btn-success">{{ trans('admin.save') }}</button>
               </div><!-- /.box-footer -->
               </form>

              </div><!-- /.row -->
        	</div><!-- /.content -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
