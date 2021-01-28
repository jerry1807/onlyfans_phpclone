@if ($paginator->hasMorePages())
<a href="javascript:void(0)" data-url="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-primary btn-sm text-center loadPaginator" id="paginator">
       	 {{trans('general.loadmore')}} <i class="far fa-arrow-alt-circle-down"></i>
       	 	</a>
       	 	@endif
