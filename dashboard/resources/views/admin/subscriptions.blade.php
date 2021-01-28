@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('admin.subscriptions') }} ({{$data->total()}})
          </h4>
        </section>

        <!-- Main content -->
        <section class="content">
        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  		{{ trans('admin.subscriptions') }}
                  	</h3>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if ($data->total() !=  0 && $data->count() != 0)
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('general.user') }}</th>
                      <th class="active">{{ trans('general.subscriber') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{trans('admin.status')}}</th>
                    </tr><!-- /.TR -->

                  @foreach ($data as $subscription)
                    <tr>
                      <td>{{ $subscription->id }}</td>
                      <td>
                        @if ( ! isset($subscription->subscribed()->username))
                          {{ trans('general.no_available') }}
                        @else
                        <a href="{{url($subscription->subscribed()->username)}}" target="_blank">
                          {{$subscription->subscribed()->name}} <i class="fa fa-external-link-square"></i>
                        </a>
                      @endif
                      </td>
                      <td>
                        @if ( ! isset($subscription->user()->username))
                          {{ trans('general.no_available') }}
                        @else
                        <a href="{{url($subscription->user()->username)}}" target="_blank">
                          {{$subscription->user()->name}}
                        </a>
                        @endif
                      </td>
                      <td>{{ Helper::formatDate($subscription->created_at) }}</td>
                      <td>
                        @if ($subscription->stripe_id == ''
                          && strtotime($subscription->ends_at) >= strtotime(Carbon\Carbon::today())
                            || $subscription->stripe_id != '' && $subscription->stripe_status == 'active'
                          )
                          <span class="label label-success">{{trans('general.active')}}</span>
                        @elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
                          <span class="label label-warning">{{trans('general.incomplete')}}</span>
                        @else
                          <span class="label label-danger">{{trans('general.cancelled')}}</span>
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
              @if ($data->lastPage() > 1)
             {{ $data->links() }}
             @endif
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
