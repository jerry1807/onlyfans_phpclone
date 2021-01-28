@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('admin.pages') }} ({{$data->count()}})
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
                  <h3 class="box-title"> {{ trans('admin.pages') }}</h3>
                  <div class="box-tools">
                    <a href="{{ url('panel/admin/pages/create') }}" class="btn btn-sm btn-success no-shadow pull-right">
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
                      <th class="active">{{ trans('admin.title') }}</th>
                      <th class="active">{{ trans('admin.slug') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                    </tr>

                  @foreach( $data as $page )
                    <tr>
                      <td>{{ $page->id }}</td>
                      <td>
                        <a href="{{ url('p', $page->slug) }}" target="_blank">
                          {{ $page->title }} <i class="fa fa-external-link-square-alt"></i>
                        </a>
                      </td>
                      <td>{{ strtolower($page->slug) }}</td>
                      <td>
                      	<a href="{{ route('pages.edit', $page->id) }}" class="btn btn-success btn-sm padding-btn">
                      		{{ trans('admin.edit') }}
                      	</a>

                     @if( $data->count() != 1 )

                {!! Form::open([
			            'method' => 'DELETE',
			            'route' => ['pages.destroy', $page->id],
			            'id' => 'form'.$page->id,
			            'class' => 'displayInline'
				        ]) !!}
	            	{!! Form::submit(trans('admin.delete'), ['data-url' => $page->id, 'class' => 'btn btn-danger btn-sm padding-btn actionDelete']) !!}
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
