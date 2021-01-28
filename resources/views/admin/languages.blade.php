@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('admin.languages') }} ({{$data->count()}})
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
                <div class="box-header">
                  <h3 class="box-title"> {{ trans('admin.languages') }}</h3>
                  <div class="box-tools">
                    <a href="{{ url('panel/admin/languages/create') }}" class="btn btn-sm btn-success no-shadow pull-right">
	        		<i class="glyphicon glyphicon-plus myicon-right"></i> {{ trans('general.add_new') }}
	        		</a>
                  </div>
                </div><!-- /.box-header -->



                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $data->count() !=  0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('admin.name') }}</th>
                      <th class="active">{{ trans('admin.abbreviation') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                    </tr>

                  @foreach( $data as $lang )
                    <tr>
                      <td>{{ $lang->id }}</td>
                      <td>{{ $lang->name }}</td>
                      <td>{{ strtolower($lang->abbreviation) }}</td>
                      <td>
                      	<a href="{{ url('panel/admin/languages/edit', $lang->id) }}" class="btn btn-success btn-sm padding-btn">
                      		{{ trans('admin.edit') }}
                      	</a>

                     @if ($data->count() != 1)

                   {!! Form::open([
			            'method' => 'DELETE',
			            'route' => ['languages.destroy', $lang->id],
			            'id' => 'form'.$lang->id,
			            'class' => 'displayInline'
				        ]) !!}
	            	{!! Form::submit(trans('admin.delete'), ['data-url' => $lang->id, 'class' => 'btn btn-danger btn-sm padding-btn actionDelete']) !!}
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
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
