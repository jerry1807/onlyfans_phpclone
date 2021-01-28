@extends('layouts.app')

@section('title') {{trans('users.my_subscribers')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="fas fa-users mr-2"></i> {{trans('users.my_subscribers')}}</h2>
          <p class="lead text-muted mt-0">{{trans('users.my_subscribers_subtitle')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if ($subscriptions->count() != 0)
          <div class="card shadow-sm">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('general.subscriber')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{ trans('admin.ends_at') }}</th>
                  <th scope="col">{{trans('admin.status')}}</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($subscriptions as $subscription)
                  <tr>
                    <td>
                      <a href="{{url($subscription->user()->username)}}">
                        <img src="{{Storage::url(config('path.avatar').$subscription->user()->avatar)}}" width="40" height="40" class="rounded-circle mr-2">

                        {{$subscription->user()->name}}
                      </a>
                    </td>
                    <td>{{Helper::formatDate($subscription->created_at)}}</td>
                    <td>
                      @if ($subscription->ends_at)
                    {{Helper::formatDate($subscription->ends_at)}}
                  @else
                    {{Helper::formatDate($subscription->user()->subscription('main', $subscription->stripe_plan)->asStripeSubscription()->current_period_end, true)}}
                  @endif
                </td>
                    <td>
                      @if ($subscription->stripe_id == ''
                        && strtotime($subscription->ends_at) >= strtotime(Carbon\Carbon::today())
                          || $subscription->stripe_id != '' && $subscription->stripe_status == 'active'
                        )
                        <span class="badge badge-pill badge-success text-uppercase">{{trans('general.active')}}</span>
                      @elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
                        <span class="badge badge-pill badge-warning text-uppercase">{{trans('general.incomplete')}}</span>
                      @else
                        <span class="badge badge-pill badge-danger text-uppercase">{{trans('general.cancelled')}}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($subscriptions->hasPages())
  			    	{{ $subscriptions->links() }}
  			    	@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="fa fa-users ico-no-result"></i>
            </span>
            <h4 class="font-weight-light">{{trans('users.not_subscribers')}}</h4>
          </div>
        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
