@extends('layouts.app')

@section('title') {{trans('general.wallet')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="fas fa-wallet mr-2"></i> {{trans('general.wallet')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.wallet_desc')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          <div class="alert alert-primary alert-dismissible fade show shadow" role="alert">
          <span>
            <h2><strong>{{Helper::amountFormatDecimal(Auth::user()->wallet)}}</strong> <small class="h5">{{$settings->currency_code}}</small></h2>
            {{trans('general.funds_available')}}
          </span>
          </div>

          <form method="POST" action="{{ url('add/funds') }}" id="formAddFunds">

            @csrf

            <div class="form-group mb-4">
              <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text">{{$settings->currency_symbol}}</span>
              </div>
                  <input class="form-control form-control-lg" id="onlyNumber" name="amount" min="{{ $settings->min_deposits_amount }}" max="{{ $settings->max_deposits_amount }}" autocomplete="off" placeholder="{{trans('admin.amount')}} ({{ __('general.min') }} {{ Helper::amountWithoutFormat($settings->min_deposits_amount) }})" type="number">
              </div>
              <p class="help-block margin-bottom-zero">
                + <strong>{{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="handlingFee">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</strong> {{ trans('general.transaction_fee') }}

                <span class="float-right">
                {{ trans('general.total') }}: <strong>{{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="total">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</strong>
                </span>
              </p>
            </div><!-- End form-group -->

            @foreach (PaymentGateways::where('enabled', '1')->get() as $payment)

              @php

              if ($payment->type == 'card' ) {
                $paymentName = '<i class="far fa-credit-card mr-1"></i> '. trans('general.debit_credit_card') .' ('.$payment->name.')';
              } elseif ($payment->type == 'bank') {
                $paymentName = '<i class="fa fa-university mr-1"></i> '.trans('general.bank_transfer');
              } else {
                $paymentName = '<img src="'.url('public/img/payments', $payment->logo).'" width="70"/>';
              }

              @endphp
              <div class="custom-control custom-radio mb-3">
                <input name="payment_gateway" value="{{$payment->id}}" id="tip_radio{{$payment->id}}" @if (PaymentGateways::where('enabled', '1')->count() == 1) checked @endif class="custom-control-input" type="radio">
                <label class="custom-control-label" for="tip_radio{{$payment->id}}">
                  <span><strong>{!!$paymentName!!}</strong></span>
                  <small class="w-100 d-block">* {{ trans('general.transaction_fee') }}: {{ $payment->fee }}% + {{ $payment->fee_cents }}</small>
                </label>
              </div>

              @if ($payment->id == 2)
              <div id="stripeContainer" class="@if (PaymentGateways::where('enabled', '1')->count() != 1) display-none @endif">
                <div id="card-element" class="margin-bottom-10">
                  <!-- A Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display form errors. -->
                <div id="card-errors" class="alert alert-danger display-none" role="alert"></div>
              </div>
              @endif

              @if ($payment->id == 3)

                <div class="btn-block @if (PaymentGateways::where('enabled', '1')->count() != 1) display-none @endif" id="bankTransferBox">
                  <div class="alert alert-default border">
                  <h5 class="font-weight-bold"><i class="fa fa-university mr-1"></i> {{trans('general.make_payment_bank')}}</h5>
                  <ul class="list-unstyled">
                      <li>
                        {!!nl2br($payment->bank_info)!!}

                        <hr />
                        <span class="d-block w-100 mt-2">
                        {{ trans('general.total') }}: <strong>{{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="total2">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</strong>
                        <span>

                      </li>
                  </ul>
                </div>

                <div class="mb-3 text-center">
                  <span class="btn-block mb-2" id="previewImage"></span>

                    <input type="file" name="image" id="fileBankTransfer" accept="image/*" class="visibility-hidden">
                    <button class="btn btn-1 btn-block btn-outline-primary mb-2 border-dashed" onclick="$('#fileBankTransfer').trigger('click');" type="button" id="btnFilePhoto">{{trans('general.upload_image')}} (JPG, PNG, GIF) {{trans('general.maximum')}}: {{Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}</button>

                  <small class="text-muted btn-block">{{trans('general.info_bank_transfer')}}</small>
                </div>
                </div><!-- Alert -->
              @endif

            @endforeach

            <div class="alert alert-danger display-none" id="errorAddFunds">
                <ul class="list-unstyled m-0" id="showErrorsFunds"></ul>
              </div>

            <button class="btn btn-1 btn-success btn-block mt-4" id="addFundsBtn" type="submit"><i></i> {{trans('general.add_funds')}}</button>
          </form>

          @if ($data->count() != 0)
          <h6 class="text-center mt-5 font-weight-light">{{ __('general.history_deposits') }}</h6>

          <div class="card shadow-sm">
            <div class="table-responsive">
              <table class="table table-striped m-0">
                <thead>
                  <th scope="col">ID</th>
                  <th scope="col">{{ trans('admin.amount') }}</th>
                  <th scope="col">{{ trans('general.payment_gateway') }}</th>
                  <th scope="col">{{ trans('admin.date') }}</th>
                  <th scope="col">{{ trans('admin.status') }}</th>
                </thead>

                <tbody>
                  @foreach ($data as $deposit)

                    <tr>
                      <td>{{ $deposit->id }}</td>
                      <td>{{ App\Helper::amountFormat($deposit->amount) }}</td>
                      <td>{{ $deposit->payment_gateway == 'Bank Transfer' ? __('general.bank_transfer') : $deposit->payment_gateway }}</td>
                      <td>{{ date('d M, Y', strtotime($deposit->date)) }}</td>

                      @php

                      if ($deposit->status == 'pending' ) {
                       			$mode    = 'warning';
             								$_status = trans('admin.pending');
                          } else {
                            $mode = 'success';
             								$_status = trans('general.success');
                          }

                       @endphp

                       <td><span class="badge badge-pill badge-{{$mode}} text-uppercase">{{ $_status }}</span></td>
                    </tr><!-- /.TR -->
                    @endforeach
                </tbody>
              </table>
            </div><!-- table-responsive -->
          </div><!-- card -->

          @if ($data->hasPages())
  			    	<div class="mt-3">
                {{ $data->links() }}
              </div>
  			    	@endif

        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection

@section('javascript')

<script type="text/javascript">
@if($settings->currency_code == 'JPY')
  $decimal = 0;
  @else
  $decimal = 2;
  @endif

  $('input[name=payment_gateway]').on('click', function() {

    var valueOriginal = $('#onlyNumber').val();
    var value = parseFloat($('#onlyNumber').val());
    var element = $(this).val();

    if(element != '' && value <= {{ $settings->max_deposits_amount }}) {
      // Fees
      switch(parseFloat(element)) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case {{$payment->id}}:
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var total = (value + amount);

      if( valueOriginal != '' || valueOriginal !=  0 ) {
        $('#handlingFee').html(amount.toFixed($decimal));
        $('#total, #total2').html(total.toFixed($decimal));
      }
    }

});

//<-------- * TRIM * ----------->

$('#onlyNumber').on('keyup', function() {

    var valueOriginal = $(this).val();
    var value = parseFloat($(this).val());
    var paymentGateway = $('input[name=payment_gateway]:checked').val();

    if (value > {{ $settings->max_deposits_amount }} || valueOriginal.length == 0) {
      $('#handlingFee').html('0');
      $('#total, #total2').html('0');
    }

    if (paymentGateway && value <= {{ $settings->max_deposits_amount }}) {

      switch(parseFloat(paymentGateway)) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case {{$payment->id}}:
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var total = (value + amount);

      if (valueOriginal != '' || valueOriginal !=  0) {
        $('#handlingFee').html(amount.toFixed($decimal));
        $('#total, #total2').html(total.toFixed($decimal));
      } else {
        $('#handlingFee, #total, #total2').html('0');
        }
    }

});

</script>
@endsection
