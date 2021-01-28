@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('admin.verification_requests') }} ({{$data->count()}})
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

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if ($data->count() !=  0)
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('admin.user') }}</th>
                      <th class="active">{{ trans('general.address') }}</th>
                      <th class="active">{{ trans('general.city') }}</th>
                      <th class="active">{{ trans('general.zip') }}</th>
                      <th class="active">{{ trans('general.image') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                    </tr>

                  @foreach ($data as $verify)
                    <tr>
                      <td>{{ $verify->id }}</td>
                      <td><a href="{{ url($verify->user()->username) }}" target="_blank">{{ $verify->user()->username }} <i class="fa fa-external-link-square-alt"></i></a></td>
                      <td>{{ $verify->address }}</td>
                      <td>{{ $verify->city }}</td>
                      <td>{{ $verify->zip }}</td>
                      <td><a href="{{ Storage::url(config('path.verification').$verify->image) }}" target="_blank">{{ trans('admin.see_document_id') }} <i class="fa fa-external-link-square-alt"></i></a></td>
                      <td>{{ Helper::formatDate($verify->created_at) }}</td>
                    <td>

                  @if ($verify->status == 'pending')
                      {!! Form::open([
                      'method' => 'POST',
                      'url' => url('panel/admin/verification/members/approve', $verify->id).'/'.$verify->user_id,
                      'class' => 'displayInline'
                    ]) !!}
                   {!! Form::submit(trans('admin.approve'), ['class' => 'btn btn-success btn-sm padding-btn actionApprove']) !!}

                     {!! Form::close() !!}

                        {!! Form::open([
      			            'method' => 'POST',
      			            'url' => url('panel/admin/verification/members/delete', $verify->id).'/'.$verify->user_id,
      			            'class' => 'displayInline'
				              ]) !!}
	                   {!! Form::submit(trans('admin.reject'), ['class' => 'btn btn-danger btn-sm padding-btn actionDeleteVerification']) !!}

	        	           {!! Form::close() !!}

                     @else
                       <span class="label label-success">{{trans('admin.approved')}}</span>
                     @endif
                     </td>

                    </tr><!-- /.TR -->
                    @endforeach

                    @else
                    	<h3 class="text-center no-found">{{ trans('general.no_results_found') }}</h3>
                    @endif

                  </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
