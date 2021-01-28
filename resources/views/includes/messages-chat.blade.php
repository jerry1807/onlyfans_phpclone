@if ($allMessages > 10 && $counter >= 1)
<div class="btn-block text-center wrap-container" data-total="{{ $allMessages }}" data-id="{{ $user->id }}">
  <a href="javascript:void(0)" class="loadMoreMessages d-none" id="paginatorChat">
    — {{ trans('general.load_messages') }}
    (<span class="counter">{{$counter}}</span>)
  </a>
</div>
@endif

@foreach ($messages as $msg)

  @php

  if ($msg->from_user_id  == Auth::user()->id) {
     $avatar   = $msg->to()->avatar;
     $name     = $msg->to()->name;
     $userID   = $msg->to()->id;
     $username = $msg->to()->username;

  } else if ($msg->to_user_id  == Auth::user()->id) {
     $avatar   = $msg->from()->avatar;
     $name     = $msg->from()->name;
     $userID   = $msg->from()->id;
     $username = $msg->from()->username;
  }

  if ( ! request()->ajax()) {
    $classInvisible = 'invisible';
  } else {
    $classInvisible = null;
  }

  $imageMsg = url('files/messages', $msg->id).'/'.$msg->file;

  if ($msg->file != '' && $msg->format == 'image') {
    $messageChat = '<a href="'.$imageMsg.'" data-group="gallery'.$msg->id.'" class="js-smartPhoto">
    <div class="container-media-img" style="background-image: url('.$imageMsg.')"></div>
    </a>';
  } elseif ($msg->file != '' && $msg->format == 'video') {
    $messageChat = '<div class="container-media-msg"><video class="js-player '.$classInvisible.'" controls>
      <source src="'.Storage::url(config('path.messages').$msg->file).'" type="video/mp4" />
    </video></div>
    ';
  } elseif ($msg->file != '' && $msg->format == 'music') {
    $messageChat = '<div class="container-media-music"><audio class="js-player '.$classInvisible.'" controls>
      <source src="'.Storage::url(config('path.messages').$msg->file).'" type="audio/mp3">
      Your browser does not support the audio tag.
    </audio></div>';
  } elseif ($msg->file != '' && $msg->format == 'zip') {
    $messageChat = '<a href="'.url('download/message/file', $msg->id).'" class="d-block text-decoration-none">
     <div class="card">
       <div class="row no-gutters">
         <div class="col-md-3 text-center bg-primary">
           <i class="far fa-file-archive m-2 text-white" style="font-size: 40px;"></i>
         </div>
         <div class="col-md-9">
           <div class="card-body py-2 px-4">
             <h6 class="card-title text-primary text-truncate mb-0">
               '.$msg->original_name.'.zip
             </h6>
             <p class="card-text">
               <small class="text-muted">'.$msg->size.'</small>
             </p>
           </div>
         </div>
       </div>
     </div>
     </a>';
  } elseif ($msg->tip == 'yes') {
    $messageChat = '<div class="card">
       <div class="row no-gutters">
         <div class="col-md-12">
           <div class="card-body py-2 px-4">
             <h6 class="card-title text-primary text-truncate mb-0">
               <i class="fa fa-donate mr-1"></i> '.__('general.tip'). ' -- ' .Helper::amountWithoutFormat($msg->tip_amount).'
             </h6>
           </div>
         </div>
       </div>
     </div>';
  } else {
    $messageChat = Helper::linkText(Helper::checkText($msg->message));
  }

@endphp

@if ($msg->from()->id == auth()->user()->id)
<div data="{{$msg->id}}" class="media py-2 chatlist">
<div class="media-body position-relative">
  @if ($msg->tip == 'no')
  <a href="javascript:void(0);" class="btn-removeMsg removeMsg" data="{{$msg->id}}" title="{{trans('general.delete')}}">
    <i class="fa fa-trash-alt"></i>
    </a>
  @endif

  <div class="position-relative text-word-break message @if ($msg->file == '' && $msg->tip == 'no') bg-primary @else media-container @endif text-white m-0 @if ($msg->format == 'zip') w-50 @else w-auto @endif float-right rounded-bottom-right-0">
    {!! $messageChat !!}
  </div>

    <small class="timeAgo w-100 d-block text-muted float-right text-right pr-1" data="{{ date('c', strtotime($msg->created_at)) }}"></small>
</div><!-- media-body -->

<a href="{{url($msg->from()->username)}}" class="align-self-end ml-3 d-none">
  <img src="{{Storage::url(config('path.avatar').$msg->from()->avatar)}}" class="rounded-circle" width="50" height="50">
</a>
</div><!-- media -->

@else
<div data="{{$msg->id}}" class="media py-2 chatlist">
<a href="{{url($msg->from()->username)}}" class="align-self-end mr-3">
  <img src="{{Storage::url(config('path.avatar').$msg->from()->avatar)}}" class="rounded-circle avatar-chat" width="50" height="50">
</a>
<div class="media-body position-relative">
  <div class="position-relative text-word-break message @if ($msg->file == '' && $msg->tip == 'no') bg-light @else media-container @endif m-0 @if ($msg->format == 'zip') w-50 @else w-auto @endif float-left rounded-bottom-left-0">
    {!! $messageChat !!}
  </div>
  <small class="timeAgo w-100 d-block text-muted float-left pl-1" data="{{ date('c', strtotime($msg->created_at)) }}"></small>
</div><!-- media-body -->
</div><!-- media -->

@endif

@endforeach
