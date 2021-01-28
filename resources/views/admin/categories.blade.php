@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('general.categories') }}
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

		    @if (Session::has('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		        {{ Session::get('success_message') }}
		    </div>
		@endif

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"> {{ trans('general.categories') }}</h3>
                  <div class="box-tools">
                    <a href="{{ url('panel/admin/categories/add') }}" class="btn btn-sm btn-success no-shadow pull-right">
	        		<i class="glyphicon glyphicon-plus myicon-right"></i> {{ trans('general.add_new') }}
	        		</a>
                  </div>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $totalCategories !=  0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('admin.name') }}</th>
                      <th class="active">{{ trans('admin.slug') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                      <th class="active">{{ trans('admin.status') }}</th>
                    </tr>

                  @foreach( $categories as $category )
                    <tr>
                      <td>{{ $category->id }}</td>
                      <td>{{ $category->name }}</td>
                      <td>{{ $category->slug }}</td>
                      <td>
                      	<a href="{{ url('panel/admin/categories/edit/').'/'.$category->id }}" class="btn btn-success btn-sm padding-btn">
                      		{{ trans('admin.edit') }}
                      	</a>

                        {!! Form::open([
                          'method' => 'POST',
                          'url' => "panel/admin/categories/delete/$category->id",
                          'class' => 'displayInline'
                        ]) !!}

                        {!! Form::button(trans('admin.delete'), ['class' => 'btn btn-danger btn-sm padding-btn actionDelete']) !!}
                        {!! Form::close() !!}

                      		</td>
                      		<?php if( $category->mode == 'on' ) {
                      			$mode = 'success';
                      		} else {
                      			$mode = 'danger';
                      		} ?>
                      <td><span class="label label-{{$mode}}">{{ ucfirst($category->mode) }}</span></td>
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
