@extends('admin.layout')

@section('css')
<link href="{{ asset('public/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            {{ trans('admin.dashboard') }} v{{$settings->version}}
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('panel/admin') }}"><i class="fa fa-dashboard"></i> {{ trans('admin.home') }}</a></li>
            <li class="active">{{ trans('admin.dashboard') }}</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">

        <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ $total_subscriptions }}</h3>
                  <p>{{ trans('admin.subscriptions') }}</p>
                </div>
                <div class="icon">
                  <i class="iconmoon icon-Dollar"></i>
                </div>
								<a href="{{url('panel/admin/subscriptions')}}" class="small-box-footer">{{trans('general.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

        <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ Helper::amountFormatDecimal($total_raised_funds) }}</h3>
                  <p>{{ trans('admin.earnings_net') }} ({{__('users.admin')}})</p>
                </div>
                <div class="icon">
                  <i class="iconmoon icon-Bag"></i>
                </div>
								<span class="small-box-footer">{{trans('admin.earnings_net')}}</span>
              </div>
            </div><!-- ./col -->

            <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{ Helper::formatNumber(User::count()) }}</h3>
                  <p>{{ trans('general.members') }}</p>
                </div>
                <div class="icon">
                  <i class="iconmoon icon-Users"></i>
                </div>
								<a href="{{url('panel/admin/members')}}" class="small-box-footer">{{trans('general.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

						<div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{ Helper::formatNumber($total_posts) }}</h3>
                  <p>{{ trans('general.posts') }}</p>
                </div>
                <div class="icon">
                  <i class="fa fa-user-edit"></i>
                </div>
								<a href="{{url('panel/admin/posts')}}" class="small-box-footer">{{trans('general.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

          </div>

    <div class="row">

      <section class="col-md-12">
        <div class="box">
          <div class="box-header with-border text-center">
          <h3 class="box-title"><i class="fa fa-bar-chart-o"></i> {{trans('general.statistics_of_the_month')}}</h3>
        </div>
        <div class="chart">
          <!-- Sales Chart Canvas -->
          <canvas id="salesChart" style="height: 280px;"></canvas>
        </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-sm-4 col-xs-12">
                <div class="description-block border-right">
                  <h2 class="{{$stat_revenue_today > 0 ? 'text-green' : 'text-red' }}">{{ Helper::amountFormatDecimal($stat_revenue_today) }}</h2>
                  <span class="description-text text-black">{{trans('general.revenue_today')}}</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4 col-xs-12">
                <div class="description-block border-right">
                  <h2 class="{{$stat_revenue_week > 0 ? 'text-green' : 'text-red' }}">{{ Helper::amountFormatDecimal($stat_revenue_week) }}</h2>
                  <span class="description-text text-black">{{trans('general.revenue_week')}}</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4 col-xs-12">
                <div class="description-block">
                  <h2 class="{{$stat_revenue_month > 0 ? 'text-green' : 'text-red' }}">{{ Helper::amountFormatDecimal($stat_revenue_month) }}</h2>
                  <span class="description-text text-black">{{trans('general.revenue_month')}}</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div><!-- /.box-footer -->
        </div><!-- /.box -->
      </section>

			<section class="col-md-7">
			  <div class="nav-tabs-custom">
			    <ul class="nav nav-tabs pull-right ui-sortable-handle">
			        <li class="pull-left header"><i class="ion ion-cash"></i> {{ trans('admin.subscriptions_last_30_days') }}</li>
			    </ul>
			    <div class="tab-content">
			        <div class="tab-pane active">
			          <div class="chart" id="chart1"></div>
			        </div>
			    </div>
			</div>
		  </section>

			<section class="col-md-5">
		  	<!-- Map box -->
              <div class="box box-solid bg-purple-gradient">
                <div class="box-header">

                  <i class="fa fa-map-marker-alt"></i>
                  <h3 class="box-title">
                    {{ trans('admin.user_countries') }}
                  </h3>
                </div>
                <div class="box-body">
                  <div id="world-map" class="world-map"></div>
                </div><!-- /.box-body-->
              </div>
              <!-- /.box -->
		  </section>

        </div><!-- ./row -->

        <div class="row">

					<div class="col-md-6">
						<div class="box">
							 <div class="box-header with-border">
								 <h3 class="box-title">{{ trans('admin.recent_subscriptions') }}</h3>
								 <div class="box-tools pull-right">
								 </div>
							 </div><!-- /.box-header -->

							 @if ($subscriptions->count() != 0)
							 <div class="box-body">

								 <ul class="products-list product-list-in-box">

								@foreach ($subscriptions as $subscription)

									 <li class="item">
										 <div class="product-img">
											 <img src="{{ Storage::url(config('path.avatar').$subscription->user()->avatar) }}" class="img-circle h-auto" onerror="onerror" />
										 </div>
										 <div class="product-info">
											 <span class="product-title">
                         <a href="{{url($subscription->user()->username)}}" target="_blank">{{$subscription->user()->name}}</a>
                          {{trans('general.subscribed_to')}} <a href="{{url($subscription->subscribed()->username)}}" target="_blank">{{$subscription->subscribed()->name}}</a>
												 </span>
											 <span class="product-description">
												 {{ Helper::formatDate($subscription->created_at) }}
											 </span>
										 </div>
									 </li><!-- /.item -->
									 @endforeach
								 </ul>
							 </div><!-- /.box-body -->

							 <div class="box-footer text-center">
								 <a href="{{ url('panel/admin/subscriptions') }}" class="uppercase">{{ trans('general.view_all') }}</a>
							 </div><!-- /.box-footer -->

							 @else
								<div class="box-body">
								 <h5>{{ trans('admin.no_result') }}</h5>
									</div><!-- /.box-body -->
							 @endif
						 </div>
					 </div>

              <div class="col-md-6">
                <!-- USERS LIST -->
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin.latest_members') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                  </div><!-- /.box-header -->

                  <div class="box-body">
                    <ul class="products-list product-list-in-box">
                      @foreach( $users as $user )
												@php
												switch (  $user->status ) {
												  case 'active':
													  $user_color_status = 'success';
													  $user_txt_status = trans('general.active');
													  break;

												case 'pending':
													  $user_color_status = 'info';
													  $user_txt_status = trans('general.pending');
													  break;

												case 'suspended':
													  $user_color_status = 'warning';
													  $user_txt_status = trans('admin.suspended');
													  break;

											  }
												 @endphp

											<li class="item">
	                      <div class="product-img">
	                        <img src="{{ Storage::url(config('path.avatar').$user->avatar) }}" class="img-circle h-auto" onerror="onerror" />
	                      </div>
	                      <div class="product-info">
	                        <a href="{{ url($user->username) }}" target="_blank" class="product-title">@if($user->name !='' ) {{ $user->name }} @else {{ $user->username }} @endif
	                        	<span class="label label-{{ $user_color_status }} pull-right">{{ $user_txt_status }}</span>
	                        	</a>
	                        <span class="product-description">
	                          {{ '@'.$user->username }} / {{ App\Helper::formatDate($user->date) }}
	                        </span>
	                      </div>
	                    </li><!-- /.item -->
                      @endforeach
                    </ul><!-- /.users-list -->
                  </div><!-- /.box-body -->

                  <div class="box-footer text-center">
                    <a href="{{ url('panel/admin/members') }}" class="uppercase">{{ trans('admin.view_all_members') }}</a>
                  </div><!-- /.box-footer -->
                </div><!--/.box -->
              </div>
              </div><!-- ./row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')
	<!-- Morris -->
	<script src="{{ asset('public/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/morris/morris.min.js')}}" type="text/javascript"></script>

	<!-- knob -->
	<script src="{{ asset('public/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/knob/jquery.knob.js')}}" type="text/javascript"></script>
  <script src="{{ asset('public/js/Chart.min.js') }}"></script>
  <script src="{{ asset('public/admin/js/charts.js')}}" type="text/javascript"></script>
@endsection
