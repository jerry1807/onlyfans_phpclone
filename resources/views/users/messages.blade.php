@extends('layouts.app')

@section('title'){{trans('general.messages')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="feather icon-send mr-2"></i> {{trans('general.messages')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.messages_subtitle')}}</p>
          @if ($messages->count() != 0)
          <button class="btn btn-primary btn-sm w-small-100" data-toggle="modal" data-target="#newMessageForm">
            <i class="fa fa-plus"></i> {{trans('general.new_message')}}
          </button>
        @endif
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

      <div class="col-md-6 col-lg-9 mb-5 mb-lg-0" id="messagesContainer">

    @if ($messages->count() != 0)

      @include('includes.messages-inbox')

    @else

      <div class="my-5 text-center no-updates">
        <span class="btn-block mb-3">
          <i class="fa fa-comment-slash ico-no-result"></i>
        </span>
      <h4 class="font-weight-light">{{trans('general.no_messages')}}</h4>

      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newMessageForm">
        <i class="fa fa-plus"></i> {{trans('general.new_message')}}
      </button>

      </div>
    @endif
    </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>

  <div class="modal fade" id="newMessageForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="card bg-white shadow border-0">

            <div class="card-body px-lg-5 py-lg-5">

              <div class="mb-2">
                <h5 class="position-relative">{{trans('general.new_message')}}
                  <small data-dismiss="modal" class="btn-cancel-msg">{{ trans('admin.cancel') }}</small>
                </h5>

              </div>

              <div class="position-relative">
                <span class="my-sm-0 btn-new-msg">
                  <i class="fa fa-search"></i>
                </span>

                <input class="form-control input-new-msg rounded mb-2" id="searchCreator" type="text" name="q" autocomplete="off" placeholder="{{ trans('general.find_user') }}" aria-label="Search">
              </div>

              <div class="w-100 text-center mt-3 display-none" id="spinner">
                <span class="spinner-border align-middle text-primary"></span>
              </div>

              <div id="containerUsers" class="text-center"></div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- End Modal new Message -->
@endsection

@section('javascript')
<script src="{{ asset('public/js/paginator-messages.js') }}"></script>
@endsection
