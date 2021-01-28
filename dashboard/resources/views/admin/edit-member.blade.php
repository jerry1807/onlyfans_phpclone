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
            		{{ trans('admin.edit') }}

            		<i class="fa fa-angle-right margin-separator"></i>
            		{{ $data->name }}
              </h4>
            </section>

        <!-- Main content -->
        <section class="content">

        	<div class="content">

       <div class="row">

       	<div class="col-md-9">

        	<div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">{{ trans('admin.edit') }}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/members/'.$data->id) }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                	<input type="hidden" name="_method" value="PUT">

					@include('errors.errors-forms')

                 <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.name') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $data->name }}" name="name" class="form-control" placeholder="{{ trans('admin.name') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('auth.email') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $data->email }}" name="email" class="form-control" placeholder="{{ trans('auth.email') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.role') }}</label>
                      <div class="col-sm-10">
                        <select name="role" class="form-control" >
                      		<option @if($data->role == 'normal') selected="selected" @endif value="normal">{{trans('admin.normal')}}</option>
                      		<option @if($data->role == 'admin') selected="selected" @endif value="admin">{{trans('admin.role_admin')}}</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('auth.password') }}</label>
                      <div class="col-sm-10">
                        <input type="password" value="" name="password" class="form-control" placeholder="{{ trans('admin.password_no_change') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('general.featured')  }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="featured" @if( $data->featured == 'yes' ) checked="checked" @endif value="yes">
                          {{ trans('general.yes')  }}
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="featured" @if( $data->featured == 'no' ) checked="checked" @endif value="no">
                         {{ trans('general.no')  }}
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                  	 <a href="{{ url('panel/admin/members') }}" class="btn btn-default">{{ trans('admin.cancel') }}</a>
                    <button type="submit" class="btn btn-success pull-right">{{ trans('admin.save') }}</button>
                  </div><!-- /.box-footer -->
                </form>
              </div>

        </div><!-- /. col-md-9 -->

        <div class="col-md-3">

        	<div class="block-block text-center">
        		<img src="{{Storage::url(config('path.avatar').$data->avatar)}}" class="thumbnail img-responsive">
        	</div>

        	<ol class="list-group">
			<li class="list-group-item"> {{trans('admin.registered')}} <span class="pull-right color-strong">{{ Helper::formatDate($data->date) }}</span></li>

			<li class="list-group-item"> {{trans('admin.status')}} <span class="pull-right color-strong">{{ ucfirst($data->status) }}</span></li>

			<li class="list-group-item"> {{trans('general.country')}} <span class="pull-right color-strong">@if( $data->countries_id != '' ) {{ $data->country()->country_name }} @else {{ trans('admin.not_established') }} @endif</span></li>

					</ol>

		<div class="block-block text-center">

      <a href="{{url($data->username)}}" target="_blank"class="btn btn-lg btn-success btn-block margin-bottom">
        {{trans('general.go_to_page')}}
      </a>

		{!! Form::open([
			            'method' => 'DELETE',
			            'route' => ['user.destroy', $data->id],
			            'class' => 'displayInline'
				        ]) !!}
	            	{!! Form::submit(trans('admin.delete'), ['data-url' => $data->id, 'class' => 'btn btn-lg btn-danger btn-block margin-bottom-10 actionDelete']) !!}
	        	{!! Form::close() !!}
	        </div>
        </div><!-- col-md-3 -->
  		</div><!-- /.row -->
  	</div><!-- /.content -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection
