@foreach ($messages as $msg)

	@php

if ($msg->last()->from_user_id == Auth::user()->id && $msg->last()->to()->id != Auth::user()->id) {
		 $avatar   = $msg->last()->to()->avatar;
		 $name     = $msg->last()->to()->name;
		 $userID   = $msg->last()->to()->id;
		 $username = $msg->last()->to()->username;
		 $icon     = $msg->last()->status == 'readed' ? '<small><i class="fa fa-check-double mr-1 text-muted"></i></small>' : '<small><i class="fa fa-reply mr-1 text-muted"></i></small>';

	} else if($msg->last()->from_user_id == Auth::user()->id){
		 $avatar   = $msg->last()->to()->avatar;
		 $name     = $msg->last()->to()->name;
		 $userID   = $msg->last()->to()->id;
		 $username = $msg->last()->to()->username;
		 $icon = null;
	} else {
		 $avatar   = $msg->last()->from()->avatar;
		 $name     = $msg->last()->from()->name;
		 $userID   = $msg->last()->from()->id;
		 $username = $msg->last()->from()->username;
		 $icon = null;
	}

	switch ($msg->last()->format) {
		case 'image':
			$iconMedia = '<i class="feather icon-image"></i> '.trans('general.image');
			break;
		case 'video':
			$iconMedia = '<i class="feather icon-video"></i> '.trans('general.video');
			break;
		case 'music':
			$iconMedia = '<i class="feather icon-mic"></i> '.trans('general.audio');
			break;
		case 'zip':
			$iconMedia = '<i class="far fa-file-archive"></i> '.trans('general.file');
				break;
		default:
			$iconMedia = null;
	}

	if ($msg->last()->tip == 'yes') {
		$iconMedia = '<i class="fa fa-donate mr-1"></i>'.trans('general.tip');
	}

/* New - Readed */
	if ($msg->last()->status == 'new' && $msg->last()->from()->id != Auth::user()->id)  {
	 $styleStatus = ' active';
	} else {
		$styleStatus = null;
	}

	// Messages
	$messagesCount = Messages::where('from_user_id', $userID)->where('to_user_id', Auth::user()->id)->where('status','new')->count();

@endphp

<div class="card mb-2">
	<div class="list-group list-group-sm list-group-flush">

		<a href="{{url('messages/'.$userID, $username)}}" class="list-group-item list-group-item-action text-decoration-none p-4{{$styleStatus}}">
			<div class="media">
			 <div class="media-left mr-3 position-relative @if (Cache::has('is-online-' . $userID)) user-online @else user-offline @endif">
					 <img class="media-object rounded-circle" src="{{Storage::url(config('path.avatar').$avatar)}}"  width="50" height="50">
			 </div>

			 <div class="media-body overflow-hidden">
				 <div class="d-flex justify-content-between align-items-center">
					<h6 class="media-heading mb-2 text-truncate">
							 {{$name}}
					 </h6>
					 <small class="timeAgo text-truncate" data="{{ date('c',strtotime( $msg->last()->created_at ) ) }}"></small>
				 </div>

				 <p class="text-truncate m-0">
					 @if ($messagesCount != 0)
					 <span class="badge badge-light mr-1">{{ $messagesCount }}</span>
				 @endif
					 {!! $icon ?? $icon !!} {!! $iconMedia !!} {{ $msg->last()->message }}
				 </p>
			 </div><!-- media-body -->
	 </div><!-- media -->
		 </a>
	</div><!-- list-group -->
</div><!-- card -->
@endforeach

@if ($messages->hasMorePages())
  <div class="btn-block text-center mt-3">
    {{ $messages->appends(['q' => request('q')])->links('vendor.pagination.loadmore') }}
  </div>
  @endif
