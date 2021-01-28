@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('admin.transactions') }} ({{$data->total()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">
          @if (session('success_message'))
      		    <div class="alert alert-success">
      		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      								<span aria-hidden="true">×</span>
      								</button>
      		       <i class="fa fa-check margin-separator"></i>  {{ session('success_message') }}
      		    </div>
      		@endif

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  		{{ trans('admin.transactions') }}
                  	</h3>

                  <div class="box-tools">
                   @if( $data->total() !=  0 )
                      <!-- form -->
                      <form role="search" autocomplete="off" action="{{ url('panel/admin/transactions') }}" method="get">
  	                 <div class="input-group input-group-sm w-150">
  	                  <input type="text" name="q" class="form-control pull-right" placeholder="{{ trans('admin.transaction_id') }}">

  	                  <div class="input-group-btn">
  	                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
  	                  </div>
  	                </div>
                  </form><!-- form -->
                  @endif
                </div>

                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if ($data->total() !=  0 && $data->count() != 0)
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('admin.transaction_id') }}</th>
                      <th class="active">{{ trans('general.user') }}</th>
                      <th class="active">{{ trans('admin.type') }}</th>
                      <th class="active">{{ trans('admin.amount') }}</th>
                      <th class="active">{{ trans('admin.earnings_admin') }}</th>
                      <th class="active">{{ trans('general.payment_gateway') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{ trans('admin.status') }}</th>
                    </tr><!-- /.TR -->

                  @foreach ($data as $transaction)
                    <tr>
                      <td>{{ str_pad($transaction->id, 4, "0", STR_PAD_LEFT) }}</td>
                      <td>{{ $transaction->txn_id }}</td>
                      <td>
                        @if ( ! isset($transaction->user()->username))
                          {{ trans('general.no_available') }}
                        @else
                          <a href="{{url($transaction->user()->username)}}" target="_blank">
                          {{$transaction->user()->name}} <i class="fa fa-external-link-square"></i>
                        </a>
                        @endif
                    </td>
                      <td>{{ __('general.'.$transaction->type) }}
                      </td>
                      <td>{{ Helper::amountFormatDecimal($transaction->amount) }}</td>
                      <td>{{ Helper::amountFormatDecimal($transaction->earning_net_admin) }}</td>
                      <td>{{ $transaction->payment_gateway }}</td>
                      <td>{{ Helper::formatDate($transaction->created_at) }}</td>
                      <td>
                        @if ($transaction->approved == '0')
                        <span class="label label-warning">{{trans('admin.pending')}}</span>
                      @elseif ($transaction->approved == '1')
                        <span class="label label-success">{{trans('admin.approved')}}</span>
                      @else
                        <span class="label label-danger">{{trans('general.canceled')}}</span>
                      @endif

                    @if ($transaction->approved == '1')
                          {!! Form::open([
        			            'method' => 'POST',
        			            'url' => url('panel/admin/transactions/cancel', $transaction->id),
        			            'class' => 'displayInline'
  				              ]) !!}
  	                   {!! Form::submit(trans('admin.cancel'), ['class' => 'btn btn-danger btn-xs padding-btn cancel_payment']) !!}

  	        	           {!! Form::close() !!}
                       @endif
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
              @if ($data->hasPages())
             {{ $data->links() }}
             @endif
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
