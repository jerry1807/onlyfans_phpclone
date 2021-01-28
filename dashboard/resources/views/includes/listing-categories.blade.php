<div class="nav-scroller py-1 mb-4">
	<nav class="nav d-flex justify-content-between">
		@foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
		<a class="text-muted btn btn-sm bg-white border mr-2 e-none btn-category @if(Request::path() == "category/$category->slug")active-category @endif" href="{{url('category', $category->slug)}}">
			<img src="{{url('public/img-category', $category->image)}}" class="mr-2 rounded" width="30" /> {{$category->name}}
		</a>
	@endforeach
	</nav>
</div>
