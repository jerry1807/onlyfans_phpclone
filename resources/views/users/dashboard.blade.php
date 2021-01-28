@extends('layouts.app')

@section('title') {{trans('admin.dashboard')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="fa fa-tachometer-alt mr-2"></i> {{trans('admin.dashboard')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.dashboard_desc')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          <div class="content">
            <div class="row">
              <div class="col-lg-4 mb-2">
                <div class="card">
                  <div class="card-body">
                    <h4><i class="fas fa-hand-holding-usd mr-2 text-primary"></i> {{ Helper::amountFormatDecimal($earningNetUser) }}</h4>
                    <small>{{ trans('admin.earnings_net') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-4 mb-2">
                <div class="card">
                  <div class="card-body">
                    <h4><i class="fas fa-wallet mr-2 text-primary"></i> {{ Helper::amountFormatDecimal(Auth::user()->balance) }}</h4>
                    <small>{{ trans('general.balance') }}
                      @if (Auth::user()->balance >= $settings->amount_min_withdrawal)
                      <a href="{{ url('settings/withdrawals')}}" class="link-border"> {{ trans('general.make_withdrawal') }}</a>
                    @endif
                    </small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-4 mb-2">
                <div class="card">
                  <div class="card-body">
                    <h4><i class="fas fa-users mr-2 text-primary"></i> <span title="{{$subscriptionsActive}}">{{ Helper::formatNumber($subscriptionsActive) }}</span></h4>
                    <small>{{ trans('general.subscriptions_active') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-12 mt-3 py-4">
                <h4 class="mb-4">{{ trans('general.earnings_this_month') }} ({{ $month }})</h4>
                <canvas id="Chart"></canvas>
              </div>
            </div><!-- end row -->
          </div><!-- end content -->

        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection

@section('javascript')
  <script src="{{ asset('public/js/Chart.min.js') }}"></script>

  <script type="text/javascript">

function decimalFormat(nStr)
{
  @if ($settings->decimal_format == 'dot')
	 $decimalDot = '.';
	 $decimalComma = ',';
	 @else
	 $decimalDot = ',';
	 $decimalComma = '.';
	 @endif

   @if ($settings->currency_position == 'left')
   currency_symbol_left = '{{$settings->currency_symbol}}';
   currency_symbol_right = '';
   @else
   currency_symbol_right = '{{$settings->currency_symbol}}';
   currency_symbol_left = '';
   @endif

    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? $decimalDot + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + $decimalComma + '$2');
    }
    return currency_symbol_left + x1 + x2 + currency_symbol_right;
  }

  var init = document.getElementById("Chart").getContext('2d');
  var ChartArea = new Chart(init, {
      type: 'line',
      data: {
          labels: [{!!$label!!}],
          datasets: [{
              label: '{{trans('general.earnings')}} ',
              backgroundColor: '{{$settings->color_default}}',
              borderColor: '{{$settings->color_default}}',
              data: [{!!$data!!}],
              borderWidth: 2,
              fill: true,
              lineTension: 0,
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      min: 0, // it is for ignoring negative step.
                      beginAtZero: true,
                      callback: function(value, index, values) {
                          return '@if($settings->currency_position == 'left'){{ $settings->currency_symbol }}@endif' + value + '@if($settings->currency_position == 'right'){{ $settings->currency_symbol }}@endif';
                      }
                  }
              }]
          },
          tooltips: {
              callbacks: {
                  label: function(t, d) {
                      var xLabel = d.datasets[t.datasetIndex].label;
                      var yLabel = decimalFormat(t.yLabel);
                      return xLabel + ': ' + yLabel;
                  }
              }
          },
          legend: {
              display: false
          }
      }
  });
  </script>
  @endsection
