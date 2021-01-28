<link href="{{ asset('public/css/core.min.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/feather.css') }}" rel="stylesheet">
@if (Auth::check() && auth()->user()->dark_mode == 'on')
  <link href="{{ asset('public/css/bootstrap-dark.min.css') }}" rel="stylesheet">
@else
  <link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet">
@endif
<link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/smartphoto.min.css') }}" rel="stylesheet">
@if (Auth::check() && request()->path() == '/' || request()->route()->named('profile') || request()->is('messages/*') || request()->is('my/bookmarks'))
<link href="{{ asset('public/js/plyr/plyr.css')}}" rel="stylesheet" type="text/css" />
@endif
<script type="text/javascript">
// Global variables
  var URL_BASE = "{{ url('/') }}";
  var _title = '@section("title")@show {{e($settings->title)}}';
  var session_status = "{{ Auth::check() ? 'on' : 'off' }}";
  var totalPosts = @if(isset($updates)) {{ $updates->total() }}@else 0 @endif;
  var ReadMore = "{{trans('general.view_all')}}";
  var copiedSuccess = "{{trans('general.copied_success')}}";
  var copied = "{{trans('general.copied')}}";
  var copy_link = "{{trans('general.copy_link')}}";
  var loading = "{{trans('general.loading')}}";
  var please_wait = "{{trans('general.please_wait')}}";
  var error_occurred = "{{trans('general.error')}}";
  var error_oops = "{{ trans('general.error_oops') }}";
  var error_reload_page = "{{ trans('general.error_reload_page') }}";
  var ok = "{{trans('users.ok')}}";
  var user_count_carousel = @if (Auth::guest() && request()->path() == '/') {{$users->count()}}@else 0 @endif;
  var no_results_found = "{{trans('general.no_results_found')}}";
  var no_results = "{{trans('general.no_results')}}";
  var is_profile = {{ request()->route()->named('profile') ? 'true' : 'false' }};
  var error_scrollelement = false;
  var captcha = {{ $settings->captcha == 'on' ? 'true' : 'false' }};
@auth
  var is_bookmarks = {{ request()->is('my/bookmarks') ? 'true' : 'false' }};
  var delete_confirm = "{{trans('general.delete_confirm')}}";
  var confirm_delete_comment = "{{trans('general.confirm_delete_comment')}}";
  var confirm_delete_update = "{{trans('general.confirm_delete_update')}}";
  var yes_confirm = "{{trans('general.yes_confirm')}}";
  var cancel_confirm = "{{trans('general.cancel_confirm')}}";
  var formats_available = "{{trans('general.formats_available')}}";
  var formats_available_images = "{{trans('general.formats_available_images')}}";
  var formats_available_verification = "{{trans('general.formats_available_verification')}}";
  var file_size_allowed = {{$settings->file_size_allowed * 1024}};
  var max_size_id = "{{trans('general.max_size_id').' '.Helper::formatBytes($settings->file_size_allowed * 1024)}}";
  var max_size_id_lang = "{{trans('general.max_size_id').' '.Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}";
  var file_size_allowed_verify_account = {{$settings->file_size_allowed_verify_account * 1024}};
  var error_width_min = "{{trans('general.width_min',['data' => 20])}}";
  var story_length = {{$settings->story_length}};
  var payment_card_error = "{{ trans('general.payment_card_error') }}";
  var confirm_delete_message = "{{trans('general.confirm_delete_message')}}";
  var confirm_delete_conversation = "{{trans('general.confirm_delete_conversation')}}";
  var confirm_cancel_subscription = "{!!trans('general.confirm_cancel_subscription')!!}";
  var yes_confirm_cancel = "{{trans('general.yes_confirm_cancel')}}";
  var confirm_delete_notifications = "{{trans('general.confirm_delete_notifications')}}";
  var confirm_delete_withdrawal = "{{trans('general.confirm_delete_withdrawal')}}";
  var change_cover = "{{trans('general.change_cover')}}";
  var pin_to_your_profile = "{{trans('general.pin_to_your_profile')}}";
  var unpin_from_profile = "{{trans('general.unpin_from_profile')}}";
  var post_pinned_success = "{{trans('general.post_pinned_success')}}";
  var post_unpinned_success = "{{trans('general.post_unpinned_success')}}";
  var stripeKey = "{{ PaymentGateways::where('id', 2)->where('enabled', '1')->first() ? env('STRIPE_KEY') : false }}";
  var thanks = "{{ trans('general.thanks') }}";
  var tip_sent_success = "{{ trans('general.tip_sent_success') }}";
  var error_payment_stripe_3d = "{{ trans('general.error_payment_stripe_3d') }}";
  var colorStripe = {!! auth()->user()->dark_mode == 'on' ? "'#dcdcdc'" : "'#32325d'" !!};
  var full_name_user = '{{ auth()->user()->name }}';
  var color_default = '{{ $settings->color_default }}';
  var formats_available_upload_file = "{{trans('general.formats_available_upload_file')}}";
  var cancel_subscription = "{{trans('general.unsubscribe')}}";
  var your_subscribed = "{{trans('general.your_subscribed')}}";

@endauth
</script>
