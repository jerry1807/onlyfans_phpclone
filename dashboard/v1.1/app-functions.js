//<--------- waiting -------//>
(function($) {
	"use strict";
$.fn.waiting = function( p_delay ){
	var $_this = this.first();
	var _return = $.Deferred();
	var _handle = null;

	if ( $_this.data('waiting') != undefined ) {
		$_this.data('waiting').rejectWith( $_this );
		$_this.removeData('waiting');
	}
	$_this.data('waiting', _return);

	_handle = setTimeout(function(){
		_return.resolveWith( $_this );
	}, p_delay );

	_return.fail(function(){
		clearTimeout(_handle);
	});

	return _return.promise();
};
})(jQuery);


(function($) {
"use strict";

// Init autosize
autosize($('.textareaAutoSize'));
// Init Plyr
const players = Plyr.setup('.js-player');

// Owl Carousel
$('.owl-carousel').owlCarousel({
  margin:10,
  items : user_count_carousel,
  responsive: {
    0:{
          items:1
      },
      600:{
          items:2
      },
      1000:{
          items:3
      }
  }
});

// Check boxButton
$('.checkboxButton').on('click', function(){
  $amount = $(this).attr('data-amount');
  $('#onlyNumber').val($amount);
});

// Copy Link
$('#btn_copy_url').on('click', function(){
	copyToClipboard('#copy_link', this);
});

function copyToClipboard(element,btn) {
    var $temp = $('<input>');
    $("body").append($temp);
    $temp.val($(element).val()).select();
		$(element).select().focus();
    document.execCommand("copy");
		$(btn).html('<i class="fa fa-check"></i> '+copied);
    setTimeout(function(){$(btn).html('<i class="fas fa-link"></i> '+copy_link); }, 1000);
    $temp.remove();
    }
	// End Copy Link

// Button Delete Account
$('#buttonDeleteAccount').on('click', function() {
  $(this).attr({'disabled' : 'true'}).html(please_wait);
  $('#formSend').submit();
});

// Allowed only numbers
$("#onlyNumber").keydown(function (e) {
// Allow: backspace, delete, tab, escape, enter and .
if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
		 // Allow: Ctrl+A, Command+A
		(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
		 // Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40)) {
				 // let it happen, don't do anything
				 return;
}
// Ensure that it is a number and stop the keypress
if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
}
});// End Allowed only numbers

//<----- Notifications
function Notifications() {

	 console.time('cache');

	 $.get(URL_BASE+"/ajax/notifications", function(data) {
		if (data) {

		 if(data.message == 'Unauthenticated.') {
			 window.location.reload();
		 }

		 if(data.notifications == 0) {
				$('title').html(_title);
		 }

		 //* Messages */
		if(data.messages != 0 ) {
			var totalMsg = data.messages;
			$('#noti_msg').removeClass('d-none').html(data.messages).fadeIn();
		} else {
			$('#noti_msg').removeClass('d-block').addClass('d-none').html('');

			if(data.notifications == 0) {
				 $('title').html(_title);
			}
		}

			//* Notifications */
			if(data.notifications != 0) {
				var totalNoty = data.notifications;
				$('#noti_notifications').removeClass('d-none').html(data.notifications).fadeIn();
			} else {
				$('#noti_notifications').removeClass('d-block').addClass('d-none').html('');
			}

			//* Error */
			if(data.error == 1) {
				window.location.reload();
			}

			var totalGlobal = parseInt(totalMsg) + parseInt(totalNoty);

			if(data.notifications == 0 && data.messages == 0) {
				$('.notify').removeClass('d-block').addClass('d-none');
			}

		 if( data.notifications != 0 && data.messages != 0 ) {
				$('title').html( "("+ totalGlobal + ") " + _title );
			} else if( data.notifications != 0 && data.messages == 0 ) {
				$('title').html( "("+ data.notifications + ") " + _title );
			} else if( data.notifications == 0 && data.messages != 0 ) {
				$('title').html( "("+ data.messages + ") " + _title );
			}

		}//<-- DATA

		},'json');

		console.timeEnd('cache');
}

// Initiator notifications
if (session_status == 'on') {
	setInterval(Notifications, 10000);
}
//End Notifications

// Function read more text
function readMore() {
	$('.update-text').readmore({
		maxHeight: 190,
		moreLink: '<a href="#">'+ReadMore+'</a>',
		lessLink: false,
		sectionCSS: 'display: block; width: 100%;',
	});
}
readMore();

jQuery.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}
// Scroll element function
function scrollElement(element) {
	var offset = $(element).offset().top;
	$('html, body').animate({scrollTop:offset}, 500);
};

// Escape HTML
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

//<-------- * TRIM * ----------->
function trim(string) {
	return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

// Bootstrap Tooltip
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})
// Bootstrap Popover
$(function () {
  $('[data-toggle="popover"]').popover()
})

$(function () {
    $('a[href="#search"]').on('click', function(event) {
        event.preventDefault();
        $('#search').addClass('open');
        $('#search > form > input[type="text"]').focus();
        $('body').css({overflow:'hidden'})
    });

    $('#search, #search button.close').on('click keyup', function(event) {
        if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
            $(this).removeClass('open');
            $('body').css({overflow:'auto'});
            $('#search > form > input[type="search"]').blur();
        }
    });

});
// Function truncate text on paragraph
function textTruncate(element, text) {
var descHeight = $(element).outerHeight();

 if( descHeight > 500 ) {
 	$(element).addClass('truncate').append('<span class="btn-block text-center color-default font-default readmoreBtn"><strong>'+text+'</strong></span>');
 }

 $(document).on('click','.readmoreBtn', function(){
 	$(element).removeClass('truncate');
 	$(this).remove();
	});
}//<<--- End

$(".navbar-toggler").on('click', function() {
	$('.collapsing').toggleClass('show');
	$('body').addClass("sidebar-overlay overflow-hidden");
});

// Close menu mobile
$(".close-menu-mobile").on('click', function() {
$('body').removeClass("sidebar-overlay overflow-hidden");
});

// Bootstrap Toogle
function toggleNavbarMethod() {
     if ($(window).width() > 768) {
         $('.dropdown-Torner').on('mouseover', function(){
	        $(this).addClass('show');
					$(this).find('.dropdown-toggle').attr("aria-expanded","true");
	    }, function(){
	        $(this).removeClass('show');
					$(this).find('.dropdown-toggle').attr("aria-expanded","false");
	    });
     }
     else {
         $('.navbar .dropdown').off('mouseover').off('mouseout');
     }
 }
 toggleNavbarMethod();
 $(window).resize(toggleNavbarMethod);

	$('.counterStats').counterUp({
		delay: 10,
		time: 1000
	});

	var logo = URL_BASE +'/public/img/' + $(".logo").attr('data-logo');
	var logo2 = URL_BASE +'/public/img/' + $(".logo").attr('data-logo-2');

	if ($(document).scrollTop() > $(".scroll").height()) {
		$(".logo").attr('src', logo2);
		$('.navbar-toggler').removeClass('text-white');
		$('.input-search').removeClass('border-0');
		$(".scroll").addClass('shadow-custom bg-white p-nav-scroll link-scroll');
	}

/* Scroll Header */
$(function () {
  $(document).scroll(function () {
    var $nav = $(".scroll");
    $nav.toggleClass('shadow-custom bg-white p-nav-scroll link-scroll', $(this).scrollTop() > $nav.height());

		if ($(this).scrollTop() > $nav.height()) {
      $(".logo").attr('src',logo2);
			$('.navbar-toggler').removeClass('text-white');
			$('.input-search').removeClass('border-0');
  }
  if ($(this).scrollTop() < $nav.height()) {
      $(".logo").attr('src',logo);
			$('.navbar-toggler').addClass('text-white');
			$('.input-search').addClass('border-0');
  }
  });
});

$('#filter').on('change', function() {
	window.location.href = $(this).val();
});
jQuery(".timeAgo").timeago();

$(document).on('click','#avatar_file',function () {
		var _this = $(this);
	    $("#uploadAvatar").trigger('click');
	     _this.blur();
	});

	//<---------------- UPLOAD UPDATE/POST ----------->>>>
	$(document).on('click','#btnCreateUpdate',function(s) {

		s.preventDefault();
		var element = $(this);
		element.attr({'disabled' : 'true'});
		var $empty = element.attr('data-empty');
		var $error = element.attr('data-error');
		var $errorMsg = element.attr('data-msg-error');
		var description = $('#updateDescription').val();
		var postLength = $('#updateDescription').attr('data-post-length');
		element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

		if (trim(description).length  == 0) {
			$('#updateDescription').focus();
			element.removeAttr('disabled');

			$('#showErrorsUdpate').html('<li><i class="fa fa-times-circle"></i> ' + $empty + '</li>');
			$('#errorUdpate').fadeIn(500);
			return false;
		}

		$('#progress, .blocked').show();

		(function() {

			var bar = $('.progress-bar');
			var percent = $('.percent');
			var percentVal = '0%';

			 $("#formUpdateCreate").ajaxForm({
			 dataType : 'json',
			 error: function(responseText, statusText, xhr, $form) {
				element.removeAttr('disabled');

				if(!xhr) {
					xhr = '- ' + $errorMsg;
				} else {
					xhr = '- ' + xhr;
				}

				$('.popout').removeClass('popout-success').addClass('popout-error').html($error+' '+xhr+'').fadeIn('500').delay('5000').fadeOut('500');
					 $('#progress, .blocked').hide();
					 bar.width(percentVal).removeClass('bg-success').addClass('bg-primary');
					 percent.html(percentVal).removeClass('text-success');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 },
			 beforeSend: function() {
	        bar.width(percentVal);
	        percent.html(percentVal);
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal = percentComplete + '%';
	        bar.width(percentVal);
	        percent.html(percentVal);

					if(percentComplete == 100) {
						bar.removeClass('bg-primary').addClass('bg-success');
						percent.addClass('text-success');
					}
	    },
			 success:  function(result) {

			 //===== SUCCESS =====//
			 if( result.success != false ) {

				 $('#progress, .blocked').hide();
				 bar.width(percentVal).removeClass('bg-success').addClass('bg-primary');
				 percent.html(percentVal).removeClass('text-success');

				 $('#updateDescription').val('');
				  $('#filePhoto').val('');

					$(result.data).hide().prependTo('.grid-updates').fadeIn(500);

				 $(function () {
					 $('[data-toggle="tooltip"]').tooltip()
				 });

					jQuery(".timeAgo").timeago();
					$('.no-updates').remove();
					$('#previewImage, #removePhoto').hide();
					$('#maximum').html(postLength).css({color: '#666', fontWeight: 'normal'});

					$('#errorUdpate').fadeOut(500);
					$('#showErrorsUdpate').html('');
					element.addClass('e-none');
					element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

					readMore();

					new SmartPhoto(".js-smartPhoto",{
						resizeStyle: 'fit',
						showAnimation: false,
						nav: false,
						useHistoryApi: false
					});

					const players = Plyr.setup('.js-player');

				}//<-- e
			else {
				$('#progress, .blocked').hide();
				bar.width(percentVal).removeClass('bg-success').addClass('bg-primary');
				percent.html(percentVal).removeClass('text-success');

				var error = '';
				var $key = '';

				for( $key in result.errors ) {
					error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
				}

				$('#showErrorsUdpate').html(error);
				$('#errorUdpate').fadeIn(500);

				element.removeAttr('disabled');
				element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			}
		}//<----- SUCCESS
	}).submit();
	})(); //<--- FUNCTION %
	});//<<<-------- * END FUNCTION CLICK UPDATE * ---->>>>

	/*========= Like ==============*/
	$(document).on('click','.likeButton',function(e) {
		var element     = $(this);
		var id          = element.attr("data-id");
		var data        = 'id=' + id;

		e.preventDefault();

		element.blur();

			 $.ajax({
			 	headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    		},
			   type: "POST",
			   url: URL_BASE+"/ajax/like",
			   data: data,
			   success: function( result ){

			   	if( result != '') {

						element.find('.countLikes').html(result);

						if( element.hasClass('active') ) {
								element.removeClass('active');
								element.find('i').removeClass('fas fa-heart').addClass('far fa-heart');
								element.find('.countLikes').html(result);

							} else {
								element.addClass('active');
								element.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
							}

			   	} else {
						window.location.reload();
						element.removeClass('likeButton');
						element.removeClass('active');

			   	}
			 }//<-- RESULT
		   }).fail(function(jqXHR, ajaxOptions, thrownError)
			 {
				 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			 });//<--- AJAX

	});//<----- LIKE

	//============= Comments
	$(document).on('keypress','.comments',function(e) {

		if (e.which == 13) {

		var element = $(this);
		e.preventDefault();
		element.blur();

		element.parents('.card-footer').find('.blocked').show();

			 $.ajax({
			 	headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    		},
			   type: "POST",
			   url: URL_BASE+"/comment/store",
			   dataType: 'json',
			   data: element.parents('.card-footer').find(".comments-form").serialize(),
			   success: function(result){

			   	if (result.success == true) {

			   		element.parents('.card-footer').find('.comments').val('');
			   		element.parents('.card-footer').find('.dangerAlertComments').fadeOut(1);
						element.parents('.card-footer').find('.container-media').append(result.data);
			   		jQuery(".timeAgo").timeago();

						element.parents('.card-footer').find('.totalComments').html(result.total);
						element.parents('.card-footer').find('.blocked').hide();

			   	} else {

			   		var error = '';
						var $key = '';

		            for( $key in result.errors ) {
		            	error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
		            }

					element.parents('.card-footer').find('.showErrorsComments').html(error);
					element.parents('.card-footer').find('.dangerAlertComments').fadeIn(500);
					element.parents('.card-footer').find('.blocked').hide();
				}
			 }//<-- RESULT
		   }).fail(function(jqXHR, ajaxOptions, thrownError)
			 {
				 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
				 element.removeAttr('disabled');
			 });//<--- AJAX

		 }//e.which == 13
	});//<----- CLICK

	// Comment Btn Focus
	$(document).on('click','.comment-btn-focus',function(e){

		var element = $(this);

		$value = element.parents('.card-footer').find('.comments');
	      scrollElement($value);
				$value.focus();

	});//<----- CLICK

	// Maximum length Update
	$('#updateDescription').on('keyup', function() {

			var element = $(this).val();
			var maximum = $(this).attr('data-post-length');

			if (trim(element).length >= 1 && trim(element).length <= maximum) {
				$('#btnCreateUpdate, #btnEditUpdate').removeAttr('disabled').removeClass('e-none');
				return false;
			} else {
				$('#btnCreateUpdate, #btnEditUpdate').attr({'disabled' : 'true'}).addClass('e-none');
				return false;
			}
		});

		// Maximum length Post
		$('#updateDescription').on('keyup', function() {

	  var characterCount = $(this).val().length,
	      maximum = $('#maximum'),
	      theCount = $('#the-count'),
				postLength = $(this).attr('data-post-length');

	  maximum.text(postLength-characterCount);

	  if (characterCount >= postLength) {
	    maximum.css('color', '#ff0000');
	    maximum.css('font-weight','bold');
	  } else {
	    maximum.css('color', '#666');
	    maximum.css('font-weight','normal');
	  }
	});

	// Copy Link
	var clip = new ClipboardJS('.copy-url');

	clip.on("success", function() {
	  $('.popout').removeClass('popout-error').addClass('popout-success').html('<i class="fa fa-check mr-1"></i> '+copiedSuccess).slideDown('200').delay('3000').slideUp('50');
	});

	document.addEventListener('DOMContentLoaded',function(){
   new SmartPhoto(".js-smartPhoto",{
     resizeStyle: 'fit',
     showAnimation: false,
		 nav: false,
		 useHistoryApi: false
   });
 });

 $(window).on('scroll', function () {
 				if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {
 					 $('#paginator').trigger('click');
 				}
 		}).scroll();

	$(document).ready(function(){
	    $(".js-player").removeClass('invisible');
	});

$('#sendMessageUser').on('click', function() {
	window.location.href = $(this).attr('data-url');
});

// Cookies
 Cookies.set('cookiePolicy');
$(document).ready(function() {
	if (Cookies('cookiePolicy'));
	else {
		$('.showBanner').fadeIn();
			$("#close-banner").on('click', function() {
					$(".showBanner").slideUp(50);
					Cookies('cookiePolicy', true);
				});
			}
		});

		//<----- DELETE COMMENT
		$(document).on('click','.delete-comment', function(e) {

		 e.preventDefault();

		 var element     = $(this);
	 	 var id          = element.attr("data");
		 element.blur();

	   $.ajaxSetup({
	      headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	      }
	  });

	 swal(
		 {
	    title: delete_confirm,
			text: confirm_delete_comment,
			 type: "error",
			 showLoaderOnConfirm: true,
			 showCancelButton: true,
			 confirmButtonColor: "#DD6B55",
				confirmButtonText: yes_confirm,
				cancelButtonText: cancel_confirm,
				 closeOnConfirm: true,
				 },
				 function(isConfirm){
						if (isConfirm) {
						 $.post(URL_BASE+"/ajax/delete-comment/"+id, function(data) {
	             if(data.success) {
	               element.parents('.media').fadeOut( 400, function() {
	                 element.parents('.card-footer').find('.totalComments').html(data.total);
	                 element.parents('.media').remove();
	               });
	             } else {
	               $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
	             }
						  }).fail(function(jqXHR, ajaxOptions, thrownError)
	       		 {
	       			 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
	       		 });
						 }
						});
	    });//<----- End DELETE COMMENT

// Delete Update (Post)
$(document).on('click','.actionDelete', function(e){

   e.preventDefault();

   var element = $(this);
   var form    = $(element).parents('form');
   element.blur();

 swal(
   {   title: delete_confirm,
    text: confirm_delete_update,
     type: "error",
     showLoaderOnConfirm: true,
     showCancelButton: true,
     confirmButtonColor: "#DD6B55",
      confirmButtonText: yes_confirm,
      cancelButtonText: cancel_confirm,
       closeOnConfirm: false,
       },
       function(isConfirm){
          if (isConfirm) {
           form.submit();
           }
          });
    });// End Delete Update (Post)

		//<<==================== PAGINATOR COMMENTS
	  $(document).on('click','.loadMoreComments', function(e) {

	    e.preventDefault();

	  var container = $(this).parents('.container-media');
	  var allElements = $(container).find('div.comments').length;
	  var post = $(this).parents('.wrap-container').attr('data-id');
	  var wrapContainer = $(this).parents('.wrap-container');

	  wrapContainer.html('<a href="javascript:void(0)">— '+loading+'</a>');

	  $.ajax({
	    url: URL_BASE+'/loadmore/comments?post=' + post + '&skip=' + allElements
	  }).done(function(data) {

	    if(data) {

	      wrapContainer.html('');

	      $(data).hide().insertAfter(wrapContainer).fadeIn(500);
	      wrapContainer.remove();

	      jQuery(".timeAgo").timeago();
	      readMore();

	    } else {
	      $('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
	    }
	    //<**** - Tooltip
	  }).fail(function(jqXHR, ajaxOptions, thrownError)
	  {
	    $('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
	  });//<--- AJAX
	});
	//<<==================== END PAGINATOR COMMENTS

	//<<==================== PAGINATOR UPDATES
	$(document).on('click','#updatesPaginator .loadPaginator', function(e){

	    e.preventDefault();
	    $(this).remove();
	    $('<div class="card mb-3 pb-4 loadMoreSpin"> <div class="card-body"> <div class="media"> <span class="rounded-circle mr-3"> <span class="item-loading position-relative loading-avatar"></span> </span> <div class="media-body"> <h5 class="mb-0 item-loading position-relative loading-name"></h5> <small class="text-muted item-loading position-relative loading-time"></small> </div> </div> </div> <div class="card-body pt-0 pb-3"> <p class="mb-1 item-loading position-relative loading-text-1"></p> <p class="mb-1 item-loading position-relative loading-text-2"></p> <p class="mb-0 item-loading position-relative loading-text-3"></p> </div> </div>').appendTo( "#updatesPaginator" );

	    var allElements = $('div.card-updates').length;

			if (is_profile == true) {
				var url_pagination = URL_BASE+'/ajax/updates?id='+profile_id+sort_post_by_type_media+'&skip=' + allElements + '&total=' + totalPosts;
			} else if(is_bookmarks == true) {
				var url_pagination = URL_BASE+'/ajax/user/bookmarks?skip=' + allElements + '&total=' + totalPosts;
			} else {
				var url_pagination = URL_BASE+'/ajax/user/updates?skip=' + allElements + '&total=' + totalPosts;
			}

	    $.ajax({
	      url: url_pagination
	    }).done(function(data){
	      if(data) {
	        $('.loadMoreSpin').remove();

	        $(data).appendTo("#updatesPaginator");

	        $(function () {
	 				 $('[data-toggle="tooltip"]').tooltip()
	 			 });

	        jQuery(".timeAgo").timeago();
	        readMore();

	        new SmartPhoto(".js-smartPhoto",{
	          resizeStyle: 'fit',
	          showAnimation: false,
	          nav: false,
	          useHistoryApi: false
	        });

	        const players = Plyr.setup('.js-player');

	      } else {
	        $('.popout').html(error_occurred).slideDown('500').delay('2500').slideUp('500');
	      }
	      //<**** - Tooltip
	    }).fail(function(jqXHR, ajaxOptions, thrownError)
	    {
	      $('.popout').html(error_occurred).slideDown('500').delay('2500').slideUp('500');
	    });//<--- AJAX
	  });
	  //<<==================== END PAGINATOR UPDATES

		//============ Content Locked
		$('#contentLocked').on('click', function() {

		  if($(this).hasClass('unlock')) {
		     $('#customCheckLocked').trigger('click');
		     $(this).html('<i class="fas fa-lock" style="font-size:20px;"></i>').removeClass('unlock');
		  } else {
		     $('#customCheckLocked').trigger('click')
		     $(this).html('<i class="fas fa-lock-open" style="font-size:20px;"></i>').addClass('unlock');
		  }
		});

		//============ Remove photo on upload post
		$('#removePhoto').on('click', function(){
	  	 	$('#filePhoto').val('');
	  	 	$('#previewImage').html('');
	  	 	$(this).hide();
	  	 });

			 //============ Submit Form Edit Post
			 $('#btnEditUpdate').on('click', function() {
		   	 	$(this).attr({'disabled' : 'true'}).html(please_wait);
		       $('#btnUpload').attr({'disabled' : 'true'});
		       $('#formUpdateEdit').submit();
		   	 });

			 //============ Upload Preview Media
			 $("#filePhoto").on('change', function(){

		     $('#previewImage').fadeOut('');
		       $('#removePhoto').hide();

		   	var loaded = false;
		   	if(window.File && window.FileReader && window.FileList && window.Blob) {
		        //check empty input filed
		   		if($(this).val()) {
		   			var oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image|video\/mp4|video\/quicktime|audio\/mpeg)$/i;
		   			if($(this)[0].files.length === 0){return}

		   			var oFile = $(this)[0].files[0];
		   			var fsize = $(this)[0].files[0].size; //get file size
		   			var ftype = $(this)[0].files[0].type; // get file type

		   			if(!rFilter.test(oFile.type)) {
		   				$('#filePhoto').val('');
		           swal({
		       			title: error_oops,
		       			text: formats_available,
		       			type: "error",
		       			confirmButtonText: ok
		       			});
		   				return false;
		   			}

		   			var allowed_file_size = file_size_allowed;

		   			if(fsize>allowed_file_size) {
		   				$('#filePhoto').val('');
		           swal({
		       			title: error_oops,
		       			text: max_size_id,
		       			type: "error",
		       			confirmButtonText: ok
		       			});
		   				return false;
		   			}

		         if(ftype == 'video/mp4' || ftype == 'video/quicktime') {
		           // Extension
		           if(ftype == 'video/mp4') {
		             var $extension = '.mp4';
		           } else {
		             var $extension = '.mov';
		           }
		           if(oFile.name.length > 30) {
		             var $fileName = oFile.name.substring(0, 30) + "(...)" + $extension;
		           } else {
		             var $fileName = oFile.name;
		           }

		           $('#previewImage').html('<i class="fa fa-play-circle text-info mr-1"></i> '+$fileName).fadeIn();
		           $('#removePhoto').show();
		         }

		         if(ftype == 'audio/mpeg') {
		           if(oFile.name.length > 30) {
		             var $fileName = oFile.name.substring(0, 30) + "(...)" + ".mp3";
		           } else {
		             var $fileName = oFile.name;
		           }

		           $('#previewImage').html('<i class="fa fa-music text-info mr-1"></i> '+$fileName).fadeIn();
		           $('#removePhoto').show();
		         }

		   			oFReader.onload = function(e) {

		   				var image = new Image();
		   			    image.src = oFReader.result;

		   				image.onload = function() {

		   			    	if(image.width < 20) {
		   			    		$('#filePhoto').val('');
		                 swal({
		             			title: error_oops,
		             			text: error_width_min,
		             			type: "error",
		             			confirmButtonText: ok
		             			});
		   			    		return false;
		   			    	}

		               if(image.height > image.width) {
		                 var $imageWidth = 40;
		               } else {
		                 var $imageWidth = 65;
		               }

		   			    	$('#previewImage').html('<img src="'+e.target.result+'" class="rounded" width="'+$imageWidth+'" />').fadeIn();
		               $('#removePhoto').show();
		   			    	var _filname =  oFile.name;
		   					  var fileName = _filname.substr(0, _filname.lastIndexOf('.'));
		   			    };// <<--- image.onload
		           }
		           oFReader.readAsDataURL($(this)[0].files[0]);
		   		}
		   	}
		   });
		   //============ Upload Preview Media

			 // Story length
		   $('#story').on('keyup', function() {
		   var characterCount = $(this).val().length,
		       current = $('#current'),
		       maximum = $('#maximum'),
		       theCount = $('#the-count');

		   current.text(characterCount);

		   if (characterCount >= story_length) {
		     current.css('color', '#ff0000');
		     current.css('font-weight','bold');
		   } else {
		     current.css('color', '#666');
		     current.css('font-weight','normal');
		   }
		 });// End Story length

			 // Save changes form edit my page
		   $('#saveChanges').on('click', function(){
		     $(this).attr({'disabled' : 'true'}).html(please_wait);
		   $('#formEditPage').submit();
		  });

			// Maximum length Message
			$('#message').on('keyup', function() {

			    var element = $(this).val();

			    if (trim(element).length >= 1) {
			      $('#button-reply-msg').removeAttr('disabled').removeClass('e-none');
			      return false;
			    } else {
			      $('#button-reply-msg').attr({'disabled' : 'true'}).addClass('e-none');
			      return false;
			    }
			  });

		//==== Search Creator
		$('#searchCreator').on('keyup',function(e) {
		  e.preventDefault();
		    e.stopPropagation();

		  var $string = $(this).val();

		  if (trim($string).length < 2) {
		    return false;
		  } else if (e.which == 16
		    || e.which == 17
		    || e.which == 18
		    || e.which == 20
		    || e.which == 32
		    || e.which == 37
		    || e.which == 38
		    || e.which == 39
		    || e.which == 40
		    ) {
		    return false;
		  }

		  $('#spinner').show();
		  $('#containerUsers').html('');

		  $(this).waiting(500).done(function() {

		  $.ajax({
		    type : 'get',
		    url : URL_BASE+'/messages/search/creator',
		    data: {'user':trim($string)},
		    success:function(data) {
		      if (data) {
		        $('#spinner').hide();
		        $('#containerUsers').html(data);
		      } else {
		        $('#containerUsers').html('<small class="text-center">'+ no_results_found +'</small>');
		        $('#spinner').hide();
		      }
		    }
		  }).fail(function(jqXHR, ajaxOptions, thrownError)
		  {
		    $('#containerUsers').html('');
		    $('#containerUsers').html(error_occurred);
		    $('#spinner').hide();
		  });//<--- AJAX

		  });//<----- * WAITING * ---->
		});//==== End Search Creator

	// Cancel subscription
	$(".cancelBtn").on('click', function(e) {
     	e.preventDefault();

     	var element = $(this);
      element.blur();

  	swal(
  		{   title: delete_confirm,
  		 text: confirm_cancel_subscription,
  		 type: "error",
  		 showLoaderOnConfirm: true,
  		 showCancelButton: true,
  		 confirmButtonColor: "#DD6B55",
  		 confirmButtonText: yes_confirm_cancel,
  		 cancelButtonText: cancel_confirm,
  		 closeOnConfirm: false,
     },
     function(isConfirm){
  		    	 if (isConfirm) {
  		    	 	$('.formCancel').submit();
  		    	 	}
  		    	 });
  		 });// End Cancel subscription

			 // Save Notifications Settings
			 $('#save').on('click', function(e) {

		     e.preventDefault();
		     var $element = $(this);
		     var $msgDefault = $element.attr('data-msg');

		     $element.attr({'disabled' : 'true'}).html('<i class="spinner-border spinner-border-sm align-middle mr-1"></i>');

		     (function() {
		        $("#form").ajaxForm({
		        dataType : 'json',
		        success:  function(response) {
		          if (response.success) {
		            $element.html($msgDefault).removeAttr('disabled');
		            $('#notifications').modal('hide')
		          }
		        },
		        error: function(responseText, statusText, xhr, $form) {
		             // error
		             swal({
		                 type: 'error',
		                 title: 'Oops...',
		                 text: ''+error_occurred+' ('+xhr+')',
		               });
		               $element.html($msgDefault).removeAttr('disabled');
		         }
		       }).submit();
		     })(); //<--- FUNCTION %
		   });// End Save Notifications Settings

	 // Delete notifications
	 $(document).on('click','.actionDeleteNotify', function(e){

        e.preventDefault();

        var element = $(this);
        var form    = $(element).parents('form');
        element.blur();

      swal(
        {   title: delete_confirm,
         text: confirm_delete_notifications,
          type: "error",
          showLoaderOnConfirm: true,
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
           confirmButtonText: yes_confirm,
           cancelButtonText: cancel_confirm,
            closeOnConfirm: false,
            },
            function(isConfirm){
               if (isConfirm) {
                form.submit();
                }
               });
         });// End Delete notifications

				 // Send form Verification Account
				 $('#sendData').on('click', function(){
				 	$(this).attr({'disabled' : 'true'}).html(please_wait);
				 	$('#formVerify').submit();
				 });

				 // trigger click select photo verification account
				 $(document).on('click','#btnFilePhoto',function () {
				 	var _this = $(this);
				 		$("#fileVerifiyAccount").trigger('click');
				 		 _this.blur();
				 });

				 //======= FILE Verify Account
			 $("#fileVerifiyAccount").on('change', function() {

			 	$('#previewImage').html('');

			 	var loaded = false;
			 	if(window.File && window.FileReader && window.FileList && window.Blob) {
			      //check empty input filed
			 		if($(this).val()) {
			       var oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
			 			if($(this)[0].files.length === 0){return}

			 			var oFile = $(this)[0].files[0];
			 			var fsize = $(this)[0].files[0].size; //get file size
			 			var ftype = $(this)[0].files[0].type; // get file type

			       if(!rFilter.test(oFile.type)) {
			 				$('#fileVerifiyAccount').val('');
			         swal({
			     			title: error_oops,
			     			text: formats_available_images,
			     			type: "error",
			     			confirmButtonText: ok
			     			});
			 				return false;
			 			}

			 			var allowed_file_size = file_size_allowed_verify_account;

			 			if(fsize>allowed_file_size){
			 				$('.popout').addClass('popout-error').html(max_size_id_lang).fadeIn(500).delay(4000).fadeOut();
			         $(this).val('');
			 				return false;
			 			}

			 			$('#previewImage').html('<i class="fas fa-image text-info"></i> <strong>' + oFile.name + '</strong>');

			 		}
			 	} else{
			 		alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
			 		return false;
			 	}
			 });
			 //======= END FILE Verify Account

	// Delete Withdrawals
	$('.saveChanges').on('click', function(){
    $(this).attr({'disabled' : 'true'}).html(please_wait);
    $('form.d-inline').submit();
  });

  $(".deleteW").on('click', function(e) {
     	e.preventDefault();

     	var element = $(this);
      element.blur();

  	swal(
  		{   title: delete_confirm,
  		 text: confirm_delete_withdrawal,
  		 type: "warning",
  		 showLoaderOnConfirm: true,
  		 showCancelButton: true,
  		 confirmButtonColor: "#DD6B55",
  		 confirmButtonText: yes_confirm,
  		 cancelButtonText: cancel_confirm,
  		 closeOnConfirm: false,
     },
     function(isConfirm){
       if (isConfirm) {
         $('.d-inline').submit();
       }
     });
   });// End Delete Withdrawals

	 // Pin to your profile
 	$(document).on('click','.pin-post',function(e) {
 		var element     = $(this);
 		var id          = element.attr("data-id");
 		var data        = 'id=' + id;

 		e.preventDefault();

 		element.blur();

 			 $.ajax({
 			 	headers: {
 	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 	    		},
 			   type: "POST",
 			   url: URL_BASE+"/pin/post",
 			   data: data,
 			   success: function(response) {

 			   	if (response.success != false) {

						if (response.status == 'pin') {
							$('.pinned-current').parents('.card-updates').find('.pin-post').html(pin_to_your_profile);
							$('.pinned-current').hide();
							element.parents('.card-updates')
							element.html(unpin_from_profile);
							element.parents('.card-updates').find('.pinned_post').addClass('pinned-current');
							$('.popout').removeClass('popout-error').addClass('popout-success').html('<i class="fa fa-check mr-1"></i> '+post_pinned_success).slideDown('200').delay('3000').slideUp('50');

						} else {
							element.parents('.card-updates').find('.pinned_post').removeClass('pinned-current');
							element.html(pin_to_your_profile);
							$('.popout').removeClass('popout-error').addClass('popout-success').html('<i class="fa fa-check mr-1"></i> '+post_unpinned_success).slideDown('200').delay('3000').slideUp('50');

						}

						}//<-- response
				 }//<-- response
 		   }).fail(function(jqXHR, ajaxOptions, thrownError)
 			 {
 				 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
 			 });//<--- AJAX

 	});//<----- End Pin to your profile

	/*========= Like ==============*/
	$(document).on('click','.btnBookmark',function(e) {
		var element     = $(this);
		var id          = element.attr("data-id");
		var data        = 'id=' + id;

		e.preventDefault();

		element.blur();

			 $.ajax({
			 	headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    		},
			   type: "POST",
			   url: URL_BASE+"/ajax/bookmark",
			   data: data,
			   success: function(result){

			   	if (result.success && result.type == 'added') {

						element.find('i').removeClass('far fa-bookmark').addClass('fas fa-bookmark');
						element.removeClass('text-muted').addClass('text-primary');
					} else {

							if (is_bookmarks == true) {
								element.parents('.card-updates').slideUp(400, function() {
									element.parents('.card-updates').remove();
								});
							}
							element.find('i').removeClass('fas fa-bookmark').addClass('far fa-bookmark');
							element.addClass('text-muted').removeClass('text-primary');
						}
			 }//<-- RESULT
		   }).fail(function(jqXHR, ajaxOptions, thrownError)
			 {
				 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			 });//<--- AJAX

	});//<----- LIKE

})(jQuery);
