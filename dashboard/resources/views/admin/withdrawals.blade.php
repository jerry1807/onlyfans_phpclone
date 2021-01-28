@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('general.withdrawals') }} ({{$data->total()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	@if(Session::has('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		      <i class="fa fa-check margin-separator"></i>  {{ Session::get('success_message') }}
		    </div>
		@endif

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  		{{ trans('general.withdrawals') }}
                  	</h3>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $data->total() !=  0 && $data->count() != 0 )
                   <tr>
                      <th class="active">ID</th>
			   		  <th class="active">{{ trans('general.user') }}</th>
			          <th class="active">{{ trans('admin.amount') }}</th>
			          <th class="active">{{ trans('admin.method') }}</th>
			          <th class="active">{{ trans('admin.status') }}</th>
			          <th class="active">{{ trans('admin.date') }}</th>
			          <th class="active">{{ trans('admin.actions') }}</th>
                    </tr><!-- /.TR -->

@foreach( $data as $withdrawal )

                    <tr>
                      <td>{{ $withdrawal->id }}</td>
                      <td>
                        @if ( ! isset($withdrawal->user()->username))
                          {{ trans('general.no_available') }}
                        @else
                      	<a href="{{ url($withdrawal->user()->username) }}" target="_blank">{{$withdrawal->user()->name }}</a>
                      	</td>
                      @endif
                      <td>{{ Helper::amountFormatDecimal($withdrawal->amount) }}</td>
                      <td>{{$withdrawal->gateway == 'Bank' ? trans('general.bank') : $withdrawal->gateway}}</td>
                      <td>
                      	@if( $withdrawal->status == 'paid' )
                      	<span class="label label-success">{{trans('general.paid')}}</span>
                      	@else
                      	<span class="label label-warning">{{trans('general.pending_to_pay')}}</span>
                      	@endif
                      </td>
                      <td>{{ Helper::formatDate($withdrawal->date) }}</td>
                      <td>

                      	<a href="{{ url('panel/admin/withdrawal',$withdrawal->id) }}" class="btn btn-xs btn-success" title="{{trans('admin.view')}}">
                      		{{trans('admin.view')}}
                      	</a>

                      	</td>

                    </tr><!-- /.TR -->
                    @endforeach

                    @else
                    <hr />
                    	<h3 class="text-center no-found">{{ trans('general.no_results_found') }}</h3>

                    @endif
                  </tbody>
                  </table>

                </div><!-- /.box-body -->
              </div><!-- /.box -->
              @if( $data->lastPage() > 1 )
             {{ $data->links() }}
             @endif
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
