@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('admin.reports') }} ({{$data->count()}})
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

               	@if( $data->count() !=  0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('admin.report_by') }}</th>
                      <th class="active">{{ trans('admin.reported') }}</th>
                      <th class="active">{{ trans('admin.type_report') }}</th>
                      <th class="active">{{ trans('admin.reason') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                    </tr>

                  @foreach( $data as $report )
                    <tr>
                      <td>{{ $report->id }}</td>
                      <td><a href="{{ url($report->user()->username) }}" target="_blank">{{ $report->user()->username }} <i class="fa fa-external-link-square-alt"></i></a></td>

                      <td>
                      @if($report->type == 'update')
                        <a href="{{ url($report->updates()->user()->username.'/post', $report->updates()->id) }}" target="_blank">
                        {{ str_limit($report->updates()->description, 40, '...') }} <i class="fa fa-external-link-square-alt"></i>
                      </a>

                    @else

                    <a href="{{ url('panel/admin/members/'.$report->userReported()->id.'/edit') }}" target="_blank">
                      {{ str_limit($report->userReported()->name, 15, '...') }} <i class="fa fa-external-link-square-alt"></i>
                    </a>

                    @endif
                    </td>

                    <td>{{ $report->type == 'user' ? trans('admin.user') : trans('general.post') }}</td>

                      <?php if( $report->reason == 'copyright' ) {
								$reason = trans('admin.copyright');
                      		} elseif( $report->reason == 'privacy_issue' ) {
								$reason = trans('admin.privacy_issue');
                      		} else if( $report->reason == 'violent_sexual_content' ) {
								$reason = trans('admin.violent_sexual_content');
                      		}
                          else if( $report->reason == 'spoofing' ) {
								$reason = trans('admin.spoofing');
                      		}

                      	?>

                      <td>{{ $reason }}</td>

                      <td>{{ Helper::formatDate($report->created_at) }}</td>
                      <td>


                        {!! Form::open([
      			            'method' => 'POST',
      			            'url' => url('panel/admin/reports/delete',$report->id),
      			            'class' => 'displayInline'
				              ]) !!}
	                   {!! Form::submit(trans('admin.delete_report'), ['class' => 'btn btn-danger btn-xs padding-btn actionDelete']) !!}

	        	           {!! Form::close() !!}

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
