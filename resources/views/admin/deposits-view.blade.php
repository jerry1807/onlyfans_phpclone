@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
           <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('general.deposits') }} #{{$data->id}}
          </h4>
        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">

              	<div class="box-body">
              		<dl class="dl-horizontal">

					  <!-- start -->
					  <dt>ID</dt>
					  <dd>{{$data->id}}</dd>
					  <!-- ./end -->

            <!-- start -->
					  <dt>{{ trans('admin.transaction_id') }}</dt>
					  <dd>{{$data->txn_id != 'null' ? $data->txn_id : trans('general.not_available')}}</dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ trans('auth.full_name') }}</dt>
					  <dd>{{$data->user()->name}}</dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ __('general.image') }}</dt>
					  <dd>
              <a href="{{ Storage::url(config('path.admin').$data->screenshot_transfer) }}" target="_blank">
                {{ trans('admin.view') }} <i class="fa fa-external-link-square-alt"></i>
              </a>
            </dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ trans('auth.email') }}</dt>
					  <dd>{{$data->user()->email}}</dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ trans('admin.amount') }}</dt>
					  <dd><strong class="text-success">{{App\Helper::amountFormat($data->amount)}}</strong></dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ trans('general.payment_gateway') }}</dt>
					  <dd>{{ $data->payment_gateway == 'Bank Transfer' ? __('general.bank_transfer') : $data->payment_gateway}}</dd>
					  <!-- ./end -->
            
					  <!-- start -->
					  <dt>{{ trans('admin.date') }}</dt>
					  <dd>{{date($settings->date_format, strtotime($data->date))}}</dd>
					  <!-- ./end -->

					</dl>
              	</div><!-- box body -->

              	<div class="box-footer">
                  	 <a href="{{ url('panel/admin/deposits') }}" class="btn btn-default">{{ trans('auth.back') }}</a>

                     @if ($data->status == 'pending')

                       {{-- Delete Donation --}}
                       {!! Form::open([
                          'method' => 'POST',
                          'url' => 'delete/deposits',
                          'class' => 'displayInline',
                          'id' => 'formDeleteDeposits'
                        ]) !!}
                     {!! Form::hidden('id',$data->id ); !!}
                     {!! Form::submit(trans('general.delete'), ['class' => 'btn btn-danger pull-right margin-separator actionDelete']) !!}

                    {!! Form::close() !!}

                    {{-- Approve Donation --}}
                       {!! Form::open([
                          'method' => 'POST',
                          'url' => 'approve/deposits',
                          'class' => 'displayInline'
                        ]) !!}
                     {!! Form::hidden('id',$data->id ); !!}
                     {!! Form::submit(trans('general.approve'), ['class' => 'btn btn-success pull-right']) !!}

                    {!! Form::close() !!}

                  @endif

                  </div><!-- /.box-footer -->

              </div><!-- box -->
            </div><!-- col -->
         </div><!-- row -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')
  <script type="text/javascript">

$(".actionDelete").click(function(e) {
   	e.preventDefault();

   	var element = $(this);
    element.blur();

  swal(
		{   title: "{{trans('general.delete_confirm')}}",
		  type: "warning",
		  showLoaderOnConfirm: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		   confirmButtonText: "{{trans('general.yes_confirm')}}",
		   cancelButtonText: "{{trans('general.cancel_confirm')}}",
		    closeOnConfirm: false,
		    },
		    function(isConfirm){
		    	 if (isConfirm) {
		    	 	$('#formDeleteDeposits').submit();
		    	 	//$('#form' + id).submit();
		    	 	}
		    	 });

		 });
</script>


@endsection
