@extends('layouts.app')

@section('title') {{trans('general.withdrawals')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="fa fa-university"></i> {{trans('general.withdrawals')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.history_withdrawals')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

        @if(Auth::user()->payment_gateway == '')
          <div class="alert alert-warning alert-dismissible" role="alert">
          <span class="alert-inner--text"><i class="far fa-credit-card mr-2"></i> {{trans('users.please_select_a')}}
            <a href="{{url('settings/payout/method')}}" class="text-white link-border">{{trans('users.payout_method')}}</a>
          </span>
        </div>
        @endif

            <div class="row">
              <div class="col-md-12">
                <h5>{{trans('general.balance')}}: {{Helper::amountFormatDecimal(Auth::user()->balance)}} <small>{{$settings->currency_code}}</small>

                  @if(Auth::user()->balance >= $settings->amount_min_withdrawal
                      && Auth::user()->payment_gateway != ''
                      && Auth::user()->withdrawals()
                      ->where('status','pending')
                      ->count() == 0)
                  {!! Form::open([
                   'method' => 'POST',
                   'url' => "settings/withdrawals",
                   'class' => 'd-inline'
                 ]) !!}

                  {!! Form::submit(trans('general.make_withdrawal'), ['class' => 'btn btn-1 btn-success mb-2 saveChanges']) !!}
                  {!! Form::close() !!}
                @else
                  <button class="btn btn-1 btn-success mb-2 disabled e-none">{{trans('general.make_withdrawal')}}</button>
                @endif

                </h5>

                @php
                  $datePaid = Withdrawals::select('date')
                      ->where('user_id', Auth::user()->id)
                      ->where('status','pending')
                      ->orderBy('id','desc')
                      ->first();
                @endphp

                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <i class="fa fa-info-circle mr-2"></i>

                  @if (! $datePaid)
                    <span>{{trans('general.amount_min_withdrawal')}} <strong>{{Helper::amountWithoutFormat($settings->amount_min_withdrawal)}} {{$settings->currency_code}}</strong>
                @endif

                @if ($datePaid)
                  {{trans('users.date_paid')}} <strong>{{Helper::formatDate(Carbon\Carbon::parse($datePaid->date)->addWeekdays($settings->days_process_withdrawals))}}</strong>
                @endif
                <small class="btn-block">* {{ trans('general.payment_process_days', ['days' => $settings->days_process_withdrawals]) }}</small>

                </span>
                </div>

              </div>
            </div>

          @if($withdrawals->count() != 0)
          <div class="card shadow-sm">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('admin.amount')}}</th>
                  <th scope="col">{{trans('admin.method')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.status')}}</th>
                  <th scope="col">{{trans('admin.actions')}}</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($withdrawals as $withdrawal)
                  <tr>
                    <td>{{Helper::amountFormatDecimal($withdrawal->amount)}}</td>
                    <td>{{$withdrawal->gateway == 'Bank' ? trans('general.bank') : $withdrawal->gateway}}</td>
                    <td>{{Helper::formatDate($withdrawal->date)}}</td>
                    <td>@if( $withdrawal->status == 'paid' )
                    <span class="badge badge-pill badge-success text-uppercase">{{trans('general.paid')}}</span>
                    @else
                    <span class="badge badge-pill badge-warning text-uppercase">{{trans('general.pending_to_pay')}}</span>
                    @endif
                  </td>
                    <td>

                      @if( $withdrawal->status != 'paid' )
                      {!! Form::open([
                        'method' => 'POST',
                        'url' => "delete/withdrawal/$withdrawal->id",
                        'class' => 'd-inline'
                      ]) !!}

                      {!! Form::button(trans('general.delete'), ['class' => 'btn btn-danger btn-sm deleteW p-1 px-2']) !!}
                      {!! Form::close() !!}

                  @else

                  {{trans('general.paid')}} - {{Helper::formatDate($withdrawal->date_paid)}}

                  @endif
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($withdrawals->hasPages())
            {{ $withdrawals->links() }}
          @endif

        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
