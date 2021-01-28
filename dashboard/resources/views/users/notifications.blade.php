@extends('layouts.app')

@section('title'){{trans('general.notifications')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat">
            <i class="far fa-bell mr-2"></i> {{trans('general.notifications')}}

            <small class="font-tiny">
              <a href="javascript:;" data-toggle="modal" data-target="#notifications"><i class="fa fa-cog mr-2"></i></a>

          @if (count($notifications) != 0)
              {!! Form::open([
    						'method' => 'POST',
    						'url' => "notifications/delete",
    						'class' => 'd-inline'
    					]) !!}

    					{!! Form::button('<i class="fa fa-trash-alt"></i>', ['class' => 'btn btn-lg  align-baseline p-0 e-none btn-link actionDeleteNotify']) !!}
    					{!! Form::close() !!}
            @endif
            </small>
          </h2>
          <p class="lead text-muted mt-0">{{trans('general.notifications_subtitle')}}</p>
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

        <?php

        	foreach ($notifications as $key) {

        		switch ($key->type) {
        			case 1:
        				$action          = trans('users.has_subscribed');
        				$linkDestination = false;
        				break;
        			case 2:
        				$action          = trans('users.like_you');
        				$linkDestination = url($key->usernameAuthor, 'post').'/'.$key->id;
                $text_post       = Str::limit($key->description, 50, '...');
        				break;
        			case 3:
        				$action          = trans('users.comment_you');
        				$linkDestination = url($key->usernameAuthor, 'post').'/'.$key->id;
                $text_post       = Str::limit($key->description, 50, '...');
        				break;
        		}

        ?>

        <div class="card mb-3 card-updates">
        	<div class="card-body">
        	<div class="media">
        		<span class="rounded-circle mr-3">
        			<a href="{{url($key->username)}}">
        				<img src="{{Storage::url(config('path.avatar').$key->avatar)}}" class="rounded-circle" width="60" height="60">
        				</a>
        		</span>
        		<div class="media-body">
        				<h6 class="mb-0 font-montserrat">
        					<a href="{{url($key->username)}}">
        					{{$key->name}}
        				</a> {{$action}} @if( $linkDestination != false ) <a href="{{url($linkDestination)}}">{{$text_post}}</a> @endif
              </h6>
        				<small class="timeAgo text-muted" data="{{date('c', strtotime($key->created_at))}}"></small>
        		</div><!-- media body -->
        	</div><!-- media -->
        </div><!-- card body -->
        </div>

    <?php } //foreach ?>

    @if (count($notifications) == 0)

      <div class="my-5 text-center">
        <span class="btn-block mb-3">
          <i class="far fa-bell-slash ico-no-result"></i>
        </span>
      <h4 class="font-weight-light">{{trans('general.no_notifications')}}</h4>
      </div>
    @endif

@if($notifications->hasPages())
    {{ $notifications->links() }}
  @endif

    </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>

  <div class="modal fade" id="notifications" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="card bg-white shadow border-0">

            <div class="card-body px-lg-5 py-lg-5">

              <div class="mb-3">
                <h6 class="position-relative">{{trans('general.receive_notifications_when')}}
                  <small data-dismiss="modal" class="btn-cancel-msg"><i class="fa fa-times"></i></small>
                </h6>
              </div>

              <form method="POST" action="{{ url('notifications/settings') }}" id="form">

                @csrf

                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" name="notify_new_subscriber" value="yes" @if (auth()->user()->notify_new_subscriber == 'yes') checked @endif id="customSwitch1">
                  <label class="custom-control-label switch" for="customSwitch1">{{ trans('general.someone_subscribed_content') }}</label>
                </div>

                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" name="notify_liked_post" value="yes" @if (auth()->user()->notify_liked_post == 'yes') checked @endif id="customSwitch2">
                  <label class="custom-control-label switch" for="customSwitch2">{{ trans('general.someone_liked_post') }}</label>
                </div>

                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" name="notify_commented_post" value="yes" @if (auth()->user()->notify_commented_post == 'yes') checked @endif id="customSwitch3">
                  <label class="custom-control-label switch" for="customSwitch3">{{ trans('general.someone_commented_post') }}</label>
                </div>

                <div class="mt-3">
                  <h6 class="position-relative">{{trans('general.email_notification')}}
                  </h6>
                </div>

                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" name="email_new_subscriber" value="yes" @if (auth()->user()->email_new_subscriber == 'yes') checked @endif id="customSwitch4">
                  <label class="custom-control-label switch" for="customSwitch4">{{ trans('general.someone_subscribed_content') }}</label>
                </div>

                <button type="submit" id="save" class="btn btn-primary btn-sm mt-3 w-100" data-msg="{{trans('admin.save')}}">
                  {{trans('admin.save')}}
                </button>

            </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- End Modal new Message -->
@endsection
