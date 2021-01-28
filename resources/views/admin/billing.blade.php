@extends('admin.layout')

@section('css')
<link href="{{ asset('public/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('general.billing_information') }}

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
                  <h3 class="box-title">{{ trans('general.billing_information') }}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/billing') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')

                 <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{trans('general.company')}}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->company }}" name="company" class="form-control" placeholder="{{trans('general.company')}}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{trans('general.select_your_country')}}</label>
                      <div class="col-sm-10">
                        <select name="country" class="form-control custom-select select2">
                          <option value="">{{trans('general.select_your_country')}}</option>
                              @foreach (Countries::orderBy('country_name')->get() as $country)
                                <option @if ($settings->country == $country->country_name) selected="selected" @endif value="{{$country->country_name}}">{{ $country->country_name }}</option>
                                @endforeach
                              </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">{{trans('general.address')}}</label>
                       <div class="col-sm-10">
                         <input type="text" value="{{ $settings->address }}" name="address" class="form-control" placeholder="{{trans('general.address')}}">
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                    <div class="box-body">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{trans('general.city')}}</label>
                        <div class="col-sm-10">
                          <input type="text" value="{{ $settings->city }}" name="city" class="form-control" placeholder="{{trans('general.city')}}">
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                    <!-- Start Box Body -->
                     <div class="box-body">
                       <div class="form-group">
                         <label class="col-sm-2 control-label">{{trans('general.zip')}}</label>
                         <div class="col-sm-10">
                           <input type="text" value="{{ $settings->zip }}" name="zip" class="form-control" placeholder="{{trans('general.zip')}}">
                         </div>
                       </div>
                     </div><!-- /.box-body -->

                     <!-- Start Box Body -->
                      <div class="box-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label">{{trans('general.vat')}}</label>
                          <div class="col-sm-10">
                            <input type="text" value="{{ $settings->vat }}" name="vat" class="form-control" placeholder="{{trans('general.vat')}}">
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

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
