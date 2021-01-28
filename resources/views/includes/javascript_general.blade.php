<script src="{{ asset('public/js/core.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/js/jqueryTimeago_'.Lang::locale().'.js') }}"></script>
<script src="{{ asset('public/js/lazysizes.min.js') }}" async=""></script>
<script src="{{ asset('public/js/plyr/plyr.min.js') }}"></script>
<script src="{{ asset('public/js/plyr/plyr.polyfilled.min.js') }}"></script>
<script src="{{ asset('public/js/app-functions.js') }}"></script>
<script src="{{ asset('public/js/smartphoto.min.js') }}"></script>

@auth
<script src="https://js.stripe.com/v3/"></script>
<script src='https://checkout.razorpay.com/v1/checkout.js'></script>
@if (request()->is('my/wallet'))
<script src="{{ asset('public/js/add-funds.js') }}"></script>
@else
<script src="{{ asset('public/js/payment.js') }}"></script>
@endif
@endauth
