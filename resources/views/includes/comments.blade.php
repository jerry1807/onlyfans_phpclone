@if ($response->comments()->count() > $settings->number_comments_show && $counter >= 1)
<div class="btn-block mb-4 text-center wrap-container" data-total="{{ $response->comments()->count() }}" data-id="{{ $response->id }}">
  <a href="javascript:void(0)" class="loadMoreComments">
    — {{ trans('general.load_comments') }}
    (<span class="counter">{{$counter}}</span>)
  </a>
</div>
@endif

@foreach ($dataComments as $comment)
<div class="comments media li-group pt-3 pb-3" data="{{$comment->id}}">
  <a class="float-left" href="{{url($comment->user()->username)}}">
    <img class="rounded-circle mr-3 avatarUser" src="{{Storage::url(config('path.avatar').$comment->user()->avatar)}}" width="40"></a>
    <div class="media-body">
      <h6 class="media-heading mb-0">
      <a href="{{url($comment->user()->username)}}">
        {{$comment->user()->name}}</a></h6>
        <p class="list-grid-block p-text mb-0 text-word-break">{!! Helper::linkText(Helper::checkText($comment->reply)) !!}</p>
        <span class="small sm-font sm-date text-muted timeAgo" data="{{date('c', strtotime($comment->date))}}"></span>
        @if($comment->user_id == Auth::user()->id)
        <span class="c-pointer small sm-font delete-comment font-weight-bold" data="{{$comment->id}}">- {{trans('general.delete')}}</span>
      @endif
      </div><!-- media-body -->
    </div>
  @endforeach
