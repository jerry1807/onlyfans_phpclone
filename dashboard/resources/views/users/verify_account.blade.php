@extends('layouts.app')

@section('title') {{trans('general.verify_account')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="far fa-check-circle mr-2"></i> {{trans('general.verify_account')}}</h2>
          <p class="lead text-muted mt-0">{{Auth::user()->verified_id != 'yes' ? trans('general.verified_account_desc') : trans('general.verified_account')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>

                    {{ session('status') }}
                  </div>
                @endif

          @include('errors.errors-forms')

        @if(Auth::user()->verified_id != 'yes')
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <span class="alert-inner--text"><i class="fa fa-exclamation-triangle"></i> {{trans('general.warning_verification_info')}}</span>
        </div>

          <form method="POST" id="formVerify" action="{{ url('settings/verify/account') }}" accept-charset="UTF-8" enctype="multipart/form-data">

            @csrf

          <div class="form-group">
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
              </div>
              <input class="form-control" name="address" placeholder="{{trans('general.address')}}" value="{{old('address')}}" type="text">
            </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                  </div>
                  <input class="form-control" name="city" placeholder="{{trans('general.city')}}" value="{{old('city')}}" type="text">
                </div>
              </div>

              <div class="form-group">
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                    </div>
                    <input class="form-control" name="zip" placeholder="{{trans('general.zip')}}" value="{{old('zip')}}" type="text">
                  </div>
                </div>

                <div class="mb-3 text-center">
                  <span class="btn-block mb-2" id="previewImage"></span>

                    <input type="file" name="image" id="fileVerifiyAccount" accept="image/*" class="visibility-hidden">
                    <button class="btn btn-1 btn-block btn-outline-primary mb-2 border-dashed" type="button" id="btnFilePhoto">{{trans('general.upload_image')}} (JPG, PNG, GIF - {{trans('general.maximum')}}: {{Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}})</button>

                  <small class="text-muted btn-block">{{trans('general.info_verification_user')}}</small>
                </div>

                <button class="btn btn-1 btn-success btn-block" id="sendData" type="submit">{{trans('general.send_approval')}}</button>
          </form>
        @else
          <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
            <span class="alert-inner--icon mr-2"><i class="far fa-check-circle"></i></span>
          <span class="alert-inner--text">{{trans('general.verified_account_success')}}</span>
        </div>

        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
