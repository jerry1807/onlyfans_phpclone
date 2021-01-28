//<--------- Add Payment Card -------//>
(function($) {
	"use strict";

	// Paginator Messages
 $(document).on('click','.loadPaginator', function(r) {
		 r.preventDefault();
		 $(this).addClass('disabled').html('<span class="spinner-border spinner-border-sm"></span>');

				 var page = $(this).attr('data-url').split('page=')[1];
				 $.ajax({
						 url: URL_BASE+'/messages?page=' + page,

				 }).done(function(data) {
					 if (data) {
						 $('.loadPaginator').remove();

						 $(data).appendTo( "#messagesContainer" );
						 jQuery(".timeAgo").timeago();

					 } else {
						 $('.popout').html(error_reload_page).slideDown('500').delay('2500').slideUp('500');
					 }
				 });
		 });// End Paginator Messages

})(jQuery);
