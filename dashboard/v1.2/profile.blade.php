@extends('layouts.app')

@section('title'){{ $user->verified_id == 'yes' ? $mediaTitle.trans('general.support_to').' '.$user->name : $user->name }} -@endsection
  @section('description_custom'){{$mediaTitle.$user->username}} - {{strip_tags($user->story)}}@endsection

  @section('css')

  <meta property="og:type" content="website" />
  <meta property="og:image:width" content="200"/>
  <meta property="og:image:height" content="200"/>

  <!-- Current locale and alternate locales -->
  <meta property="og:locale" content="en_US" />
  <meta property="og:locale:alternate" content="es_ES" />

  <!-- Og Meta Tags -->
  <link rel="canonical" href="{{url($user->username.$media)}}"/>
  <meta property="og:site_name" content="{{ trans('general.support_to').' '.$user->name }}"/>
  <meta property="og:url" content="{{url($user->username.$media)}}"/>
  <meta property="og:image" content="{{Storage::url(config('path.avatar').$user->avatar)}}"/>

  <meta property="og:title" content="{{ trans('general.support_to').' '.$user->name }}"/>
  <meta property="og:description" content="{{strip_tags($user->story)}}"/>
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:image" content="{{Storage::url(config('path.avatar').$user->avatar)}}" />
  <meta name="twitter:title" content="{{ $user->name }}" />
  <meta name="twitter:description" content="{{strip_tags($user->story)}}"/>

  <script type="text/javascript">
      var profile_id = {{$user->id}};
      var sort_post_by_type_media = "{!!$sortPostByTypeMedia!!}";
  </script>
  @endsection

@section('content')
<div class="jumbotron jumbotron-cover-user home m-0 position-relative" style="padding: @if ($user->cover != '') @if (request()->path() == $user->username) 250px @else 125px @endif @else 125px @endif 0; background: #505050 @if ($user->cover != '') url('{{Storage::url(config('path.cover').$user->cover)}}') no-repeat center center; background-size: cover; @endif">
  @if (auth()->check() && auth()->user()->status == 'active' && auth()->user()->id == $user->id)

    <div class="progress-upload-cover"></div>

    <form action="{{url('upload/cover')}}" method="POST" id="formCover" accept-charset="UTF-8" enctype="multipart/form-data">
      @csrf
    <input type="file" name="image" id="uploadCover" accept="image/*" class="visibility-hidden">
  </form>

  <button class="btn btn-cover-upload" id="coverFile" onclick="$('#uploadCover').trigger('click');">
    <i class="fa fa-camera mr-1"></i>  <span class="d-none d-lg-inline">{{trans('general.change_cover')}}</span>
  </button>
@endif
</div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="w-100 text-center py-4 img-profile-user">

          <div class="text-center position-relative avatar-wrap shadow @if (auth()->check() && auth()->user()->id != $user->id && Cache::has('is-online-' . $user->id) || auth()->guest() && Cache::has('is-online-' . $user->id)) user-online-profile overflow-visible @elseif (auth()->check() && auth()->user()->id != $user->id && !Cache::has('is-online-' . $user->id) || auth()->guest() && !Cache::has('is-online-' . $user->id)) user-offline-profile overflow-visible @endif">
            <div class="progress-upload">0%</div>

            @if (auth()->check() && auth()->user()->status == 'active' && auth()->user()->id == $user->id)

              <form action="{{url('upload/avatar')}}" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
              <input type="file" name="avatar" id="uploadAvatar" accept="image/*" class="visibility-hidden">
            </form>

            <a href="javascript:;" class="position-absolute button-avatar-upload" id="avatar_file">
              <i class="fa fa-camera"></i>
            </a>
          @endif

            <img src="{{Storage::url(config('path.avatar').$user->avatar)}}" width="150" height="150" alt="{{$user->name}}" class="rounded-circle img-user mb-2 avatarUser">
          </div><!-- avatar-wrap -->

          <div class="media-body">
            <h4 class="mt-1">
              {{$user->name}}

              @if ($user->featured == 'yes')
                <small class="text-featured" title="{{trans('users.creator_featured')}}"data-toggle="tooltip" data-placement="top">
                <i class="fas fa-award"></i>
              </small>
            @endif

              @if ($user->verified_id == 'yes')
              <small class="verified" title="{{trans('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
                <i class="fas fa-check-circle"></i>
              </small>
            @endif
          </h4>

            <p>
            <span>
              @if (!Cache::has('is-online-' . $user->id))
              <span class="w-100 d-block">
                <small>{{ trans('general.active') }}</small>
                <small class="timeAgo"data="{{ date('c', strtotime($user->last_seen ?? $user->date)) }}"></small>
               </span>
               @endif

              @if ($user->profession != '')
                {{$user->profession}}
              @endif
          </span>

            </p>

            <div class="d-flex-user justify-content-center mb-2">
            @if (Auth::check() && Auth::user()->id == $user->id)
              <a href="{{url('settings/page')}}" class="btn btn-primary btn-profile mr-1"><i class="fa fa-pencil-alt mr-2"></i> {{trans('general.edit_my_page')}}</a>
            @endif

              @if ($user->price != 0.00 && $user->verified_id == 'yes')

              @if (Auth::check() && Auth::user()->id != $user->id && $checkSubscription == 0 && ! $paymentIncomplete)
                <a href="javascript:void(0);" data-toggle="modal" data-target="#subscriptionForm" class="btn btn-primary btn-profile mr-1"><i class="fa fa-unlock-alt mr-1"></i> {{trans('general.get_access_month', ['price' => Helper::amountFormatDecimal($user->price)])}}</a>
              @elseif (Auth::check() && Auth::user()->id != $user->id && $checkSubscription == 0 && $paymentIncomplete)
                <a href="{{ route('cashier.payment', $paymentIncomplete->last_payment) }}" class="btn btn-warning btn-profile mr-1"><i class="fa fa-exclamation-triangle"></i> {{trans('general.confirm_payment')}}</a>
              @elseif (Auth::check() && Auth::user()->id != $user->id && $checkSubscription == 1)
                <a href="javascript:void(0);" class="btn btn-success btn-profile mr-1 disabled"><i class="fa fa-check"></i> {{trans('general.your_subscribed')}}</a>
              @elseif (Auth::guest())
                <a href="{{url('login')}}" class="btn btn-primary btn-profile mr-1"><i class="fa fa-unlock-alt mr-1"></i> {{trans('general.get_access_month', ['price' => Helper::amountFormatDecimal($user->price)])}}</a>
            @endif

            @endif

              @if (Auth::guest() && $user->verified_id == 'yes' || Auth::check() && Auth::user()->id != $user->id && $user->verified_id == 'yes')
              <button data-url="{{url('messages/'.$user->id, $user->username)}}" id="sendMessageUser" class="btn btn-google btn-profile mr-1">
                <i class="far fa-comment-dots mr-1"></i> {{trans('general.message')}}
              </button>
            @endif

            @if ($user->verified_id == 'yes')
            <div class="dropdown">
              <button class="btn btn-profile btn-google" id="dropdownUserShare" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h mr-1"></i> <span class="d-lg-none">{{trans('general.share')}}</span>
              </button>

              <div class="dropdown-menu d-menu dropdown-menu-right" aria-labelledby="dropdownUserShare">

                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" class="dropdown-item link-share">
                  <i class="fab fa-facebook mr-2"></i> {{trans('general.share')}}
                </a>

                <a target="_blank" href="https://twitter.com/intent/tweet?url={{url()->current()}}&text={{ e( $user->name ) }}" class="dropdown-item link-share">
                  <i class="fab fa-twitter mr-2"></i> Tweet
                </a>

                <a target="_blank" href="whatsapp://send?text={{url()->current()}}" data-action="share/whatsapp/share" title="WhatsApp" class="dropdown-item link-share">
                  <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                </a>

                <a class="dropdown-item link-share" href="javascript:void(0);" id="btn_copy_url"><i class="fas fa-link mr-2"></i> {{trans('general.copy_link')}}</a>
                <input type="hidden" readonly="readonly" id="copy_link" class="form-control" value="{{url()->current()}}">
              </div>
            </div>
          @endif

          </div><!-- d-flex-user -->

            @if (Auth::check() && Auth::user()->id != $user->id)
            <div class="text-center">
              <button type="button" class="btn e-none btn-link text-danger p-0" data-toggle="modal" data-target="#reportCreator">
                <small><i class="fas fa-flag"></i> {{trans('general.report_user')}}</small>
              </button>
            </div>
          @endif

          </div><!-- media-body -->
        </div><!-- media -->

        <ul class="nav nav-profile justify-content-center">
          <li class="nav-link @if (request()->path() == $user->username)active @endif">
            <small class="btn-block sm-btn-size">{{$user->updates()->count()}}</small>
              <a href="{{request()->path() == $user->username ? 'javascript:;' : url($user->username)}}">{{trans('general.posts')}}</a>
            </li>

            <li class="nav-link @if (request()->path() == $user->username.'/photos')active @endif">
              <small class="btn-block sm-btn-size">{{$user->updates()->where('image', '<>', '')->count()}}</small>
              <a href="{{request()->path() == $user->username.'/photos' ? 'javascript:;' : url($user->username, 'photos')}}">{{trans('general.photos')}}</a>
            </li>

            <li class="nav-link @if (request()->path() == $user->username.'/videos')active @endif">
              <small class="btn-block sm-btn-size">{{$user->updates()->where('video', '<>', '')->count()}}</small>
              <a href="{{request()->path() == $user->username.'/videos' ? 'javascript:;' : url($user->username, 'videos')}}">{{trans('general.videos')}}</a>
              </li>

            <li class="nav-link @if (request()->path() == $user->username.'/audio')active @endif">
              <small class="btn-block sm-btn-size">{{$user->updates()->where('music', '<>', '')->count()}}</small>
              <a href="{{request()->path() == $user->username.'/audio' ? 'javascript:;' : url($user->username, 'audio')}}">{{trans('general.audio')}}</a>
            </li>

        </ul>
      </div><!-- col-lg-12 -->
    </div><!-- row -->
  </div><!-- container -->

  <div class="container py-4 pb-5">
    <div class="row">
      <div class="col-lg-4 mb-3">

        <button type="button" class="btn btn-secondary btn-block mb-2 d-lg-none" type="button" data-toggle="collapse" data-target="#navbarUserHome" aria-controls="navbarCollapse" aria-expanded="false">
      		<i class="fa fa-bars myicon-right"></i> {{trans('general.about')}}
      	</button>

      <div class="sticky-top navbar-collapse collapse d-lg-block" id="navbarUserHome">
        <div class="card mb-3">
          <div class="card-body">
            <h6 class="card-title">{{ trans('general.about') }}</h6>
            <p class="card-text position-relative update-text">
              @if (isset($user->country()->country_name))
              <small class="btn-block">
                <i class="fa fa-map-marker-alt mr-1 text-muted"></i> {{$user->country()->country_name}}
              </small>
              @endif

              <small class="btn-block m-0 mb-1">
                <i class="fa fa-user-circle mr-1 text-muted"></i> {{ trans('general.member_since') }} {{ Helper::formatDate($user->date) }}
              </small>
              @if ($user->verified_id == 'yes')
              {{$user->story}}
            @endif
            </p>

              @if ($user->website != '')
                <a href="{{$user->website}}" title="{{$user->website}}" target="_blank" class="text-muted share-btn-user"><i class="fa fa-link mr-2"></i></a>
              @endif

              @if ($user->facebook != '')
                <a href="{{$user->facebook}}" title="{{$user->facebook}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-facebook-f mr-2"></i></a>
              @endif

              @if ($user->twitter != '')
                <a href="{{$user->twitter}}" title="{{$user->twitter}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-twitter mr-2"></i></a>
              @endif

              @if ($user->instagram != '')
                <a href="{{$user->instagram}}" title="{{$user->instagram}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-instagram mr-2"></i></a>
              @endif

              @if ($user->youtube != '')
                <a href="{{$user->youtube}}" title="{{$user->youtube}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-youtube mr-2"></i></a>
              @endif

              @if ($user->pinterest != '')
                <a href="{{$user->pinterest}}" title="{{$user->pinterest}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-pinterest-p mr-2"></i></a>
              @endif

              @if ($user->github != '')
                <a href="{{$user->github}}" title="{{$user->github}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-github mr-2"></i></a>
              @endif

              @if ($user->categories_id != 0 && $user->verified_id == 'yes')
              <div class="w-100">
                <a href="{{url('category', $user->category->slug)}}" class="badge badge-pill badge-secondary">
                  <i class="fa fa-tag mr-1"></i> {{ $user->category->name }}
                </a>
              </div>
            @endif
          </div><!-- card-body -->
        </div><!-- card -->

        @include('includes.footer-tiny')

        </div><!-- navbar-collapse -->
      </div><!-- col-lg-4 -->

      <div class="col-lg-8">

        @if (Auth::check() && Auth::user()->id == $user->id && request()->path() == $user->username)
          @include('includes.form-post')
        @endif

        @if ($updates->count() == 0)
            <div class="grid-updates"></div>

            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-photo-video ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{trans('general.no_posts_posted')}}</h4>
            </div>
          @else

            @php
              $counterPosts = ($updates->total() - $settings->number_posts_show);
            @endphp

            <div class="grid-updates position-relative" id="updatesPaginator">

              @if ($findPostPinned && ! request('media'))
                @include('includes.updates', ['updates' => $findPostPinned])
              @endif

              @include('includes.updates')
            </div>
          @endif
      </div>
      </div><!-- row -->
    </div><!-- container -->


    @if (Auth::check() && Auth::user()->id != $user->id)
    <div class="modal fade" id="reportCreator" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-danger modal-xs">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title font-weight-light" id="modal-title-default"><i class="fas fa-flag"></i> {{trans('general.report_user')}}</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
     <!-- form start -->
     <form method="POST" action="{{url('report/creator', $user->id)}}" enctype="multipart/form-data">
        <div class="modal-body">
          @csrf
          <!-- Start Form Group -->
          <div class="form-group">
            <label>{{trans('admin.please_reason')}}</label>
              <select name="reason" class="form-control custom-select">
               <option value="spoofing">{{trans('admin.spoofing')}}</option>
                  <option value="copyright">{{trans('admin.copyright')}}</option>
                  <option value="privacy_issue">{{trans('admin.privacy_issue')}}</option>
                  <option value="violent_sexual_content">{{trans('admin.violent_sexual_content')}}</option>
                </select>
                </div><!-- /.form-group-->
            </div><!-- Modal body -->

           <div class="modal-footer">
             <button type="submit" class="btn btn-xs btn-white">{{trans('general.report_user')}}</button>
             <button type="button" class="btn e-none text-white ml-auto" data-dismiss="modal">{{trans('admin.cancel')}}</button>
           </div>

           </form>
          </div><!-- Modal content -->
        </div><!-- Modal dialog -->
      </div><!-- Modal reportCreator -->
    @endif

    @if (Auth::check() && Auth::user()->id != $user->id && $checkSubscription == 0 && $user->verified_id == 'yes')
    <div class="modal fade" id="subscriptionForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white shadow border-0">
              <div class="card-header pb-2 border-0 position-relative" style="height: 100px; background: {{$settings->color_default}} @if ($user->cover != '')  url('{{Storage::url(config('path.cover').$user->cover)}}') no-repeat center center @endif; background-size: cover;">

              </div>
              <div class="card-body px-lg-5 py-lg-5 position-relative">

                <div class="text-muted text-center mb-3 position-relative modal-offset">
                  <img src="{{Storage::url(config('path.avatar').$user->avatar)}}" width="100" alt="{{$user->name}}" class="avatar-modal rounded-circle mb-1">
                  <h6 class="font-weight-light">
                    {{trans('general.get_access_month', ['price' => Helper::amountWithoutFormat($user->price)])}} {{trans('general.unlocked_content')}} {{$user->name}}
                  </h6>
                </div>

                @if ($updates->total() == 0)
                  <div class="alert alert-warning fade show small" role="alert">
                    <i class="fa fa-exclamation-triangle mr-1"></i> {{ $user->first_name }} {{ trans('general.not_posted_any_content') }}
                  </div>
                @endif

                <div class="text-center text-muted mb-2">
                  <h5>{{trans('general.what_will_you_get')}}:</h5>
                </div>

                <ul class="list-unstyled">
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{trans('general.access_one_month')}}</li>
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{trans('general.full_access_content')}}</li>
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{trans('general.direct_message_with_this_user')}}</li>
                </ul>

                <div class="text-center text-muted mb-2 @if ($allPayment->count() == 1) d-none @endif">
                  <small><i class="far fa-credit-card mr-1"></i> {{trans('general.choose_payment_gateway')}}</small>
                </div>

                <form method="post" action="{{url('buy/subscription')}}" id="formSubscription">

                  <input type="hidden" name="id" value="{{$user->id}}"  />

                  @csrf

                  @foreach ($allPayment as $payment)

                    @php

                    if ($payment->recurrent == 'no') {
                      $recurrent = '<br><small>'.trans('general.non_recurring').'</small>';
                    } else {
                      $recurrent = '<br><small>'.trans('general.automatically_renewed').'</small>';
                    }

                    if ($payment->type == 'card' ) {
                      $paymentName = trans('general.debit_credit_card') .' ('.$payment->name.')'.$recurrent;
                    } else {
                      $paymentName = '<img src="'.url('public/img/payments', $payment->logo).'" width="70"/>'.$recurrent;
                    }

                    @endphp
                    <div class="custom-control custom-radio mb-3">
                      <input name="payment_gateway" value="{{$payment->id}}" id="radio{{$payment->id}}" @if ($allPayment->count() == 1) checked @endif class="custom-control-input" type="radio">
                      <label class="custom-control-label" for="radio{{$payment->id}}">
                        <span><strong>{!!$paymentName!!}</strong></span>
                      </label>
                    </div>

                    @if ($payment->id == 2 && ! auth()->user()->stripe_id != '')
                      <div id="stripeContainer" class="@if ($allPayment->count() == 1 && $payment->id == 2)d-block @else display-none @endif">
                      <a href="{{ url('settings/payments/card') }}" class="btn btn-secondary btn-sm mb-3 w-100">
                        <i class="far fa-credit-card mr-2"></i>
                        {{ trans('general.add_payment_card') }}
                      </a>
                      </div>
                    @endif

                  @endforeach

                  <div class="alert alert-danger display-none" id="error">
                      <ul class="list-unstyled m-0" id="showErrors"></ul>
                    </div>

                  <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" id=" customCheckLogin" name="agree_terms" type="checkbox">
                    <label class="custom-control-label" for=" customCheckLogin">
                      <span>{{trans('general.i_agree_with')}} <a href="{{$settings->link_terms}}" target="_blank">{{trans('admin.terms_conditions')}}</a></span>
                    </label>
                  </div>
                  <div class="text-center">
                    <button type="submit" id="subscriptionBtn" class="btn btn-primary mt-4"><i></i> {{trans('general.pay')}} {{Helper::amountWithoutFormat($user->price)}} {{$settings->currency_code}}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Modal Subscription -->
  @endif
@endsection

@section('javascript')

@if (Auth::check() && Auth::user()->id == $user->id)
<script src="{{ asset('public/js/upload-avatar-cover.js') }}"></script>
@endif
@if (Auth::check())
<script src="{{ asset('public/js/payment.js') }}"></script>
@endif
<script type="text/javascript">

 @if (session('noty_error'))
   		swal({
   			title: "{{ trans('general.error_oops') }}",
   			text: "{{ trans('general.already_sent_report') }}",
   			type: "error",
   			confirmButtonText: "{{ trans('users.ok') }}"
   			});
  		 @endif

  @if (session('noty_success'))
   		swal({
   			title: "{{ trans('general.thanks') }}",
   			text: "{{ trans('general.reported_success') }}",
   			type: "success",
   			confirmButtonText: "{{ trans('users.ok') }}"
   			});
  @endif

  $('.dropdown-menu.d-menu').on({
      "click":function(e){
        e.stopPropagation();
      }
  });

  @if (session('subscription_success'))
     swal({
       title: "{{ trans('general.congratulations') }}",
       text: "{{ session('subscription_success') }}",
       type: "success",
       confirmButtonText: "{{ trans('users.ok') }}"
       });
    @endif

    @if (session('subscription_cancel'))
     swal({
       title: "{{ trans('general.error_oops') }}",
       text: "{{ session('subscription_cancel') }}",
       type: "error",
       confirmButtonText: "{{ trans('users.ok') }}"
       });
    @endif

</script>
@endsection
@php session()->forget('subscription_cancel') @endphp
@php session()->forget('subscription_success') @endphp
