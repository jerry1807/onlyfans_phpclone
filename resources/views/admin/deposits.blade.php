@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('general.deposits') }} ({{$data->total()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  		{{ trans('general.deposits') }}
                  	</h3>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
             <table class="table table-hover">
               <tbody>

               	@if( $data->total() !=  0 && $data->count() != 0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('admin.user') }}</th>
                      <th class="active">{{ trans('admin.transaction_id') }}</th>
                      <th class="active">{{ trans('admin.amount') }}</th>
                      <th class="active">{{ trans('general.payment_gateway') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{ trans('admin.status') }}</th>
                    </tr><!-- /.TR -->


                  @foreach( $data as $deposit )

                    <tr>
                      <td>{{ $deposit->id }}</td>
                      <td>
                        @if (isset($deposit->user()->username))
                        <a href="{{url($deposit->user()->name)}}" target="_blank">
                          {{$deposit->user()->username}} <i class="fa fa-external-link-square"></i>
                        </a>
                      @else
                        <em>{{ trans('general.no_available') }}</em>
                      @endif
                      </td>
                      <td>{{ $deposit->txn_id }}</td>
                      <td>{{ App\Helper::amountFormat($deposit->amount) }}</td>
                      <td>{{ $deposit->payment_gateway == 'Bank Transfer' ? __('general.bank_transfer') : $deposit->payment_gateway }}</td>
                      <td>{{ date('d M, Y', strtotime($deposit->date)) }}</td>

                      @php

                      if ($deposit->status == 'pending' ) {
                       			$mode    = 'warning';
             								$_status = trans('admin.pending');
                          } else {
                            $mode = 'success';
             								$_status = trans('general.success');
                          }

                       @endphp

                       <td>
                         <span class="label label-{{$mode}} text-uppercase">{{ $_status }}</span>
                         @if ($deposit->payment_gateway == 'Bank Transfer')
                           <a href="{{ url('panel/admin/deposits', $deposit->id) }}" class="btn btn-success btn-xs padding-btn">
                      		{{ __('admin.view') }}
                      	</a>
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
              @if( $data->lastPage() > 1 )
             {{ $data->links() }}
             @endif
            </div>
          </div>

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
