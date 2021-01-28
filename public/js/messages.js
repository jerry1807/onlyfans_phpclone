//<--------- Messages -------//>
(function($) {
	"use strict";

	//<-------- * TRIM Space * ----------->
	function trimSpace(string) {
		return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
	}

	// Autosize
	autosize(document.querySelector('textarea'));

	// Scroll to paginator chat
	var scr = $('#contentDIV')[0].scrollHeight;
	$('#contentDIV').animate({scrollTop: scr},100);

	$('#contentDIV').scroll(function() {
	    if($(this).scrollTop()==0){
	        $('#paginatorChat').trigger('click');
	    }
	});

	$(document).on('click','#button-reply-msg',function(s) {

	 s.preventDefault();

	 var element     = $(this);
	 var error       = false;
	 var param       = /^[0-9]+$/i;
	 var _lastId     = $('div.chatlist:last').attr('data');
	 var _message    = $('#message').val();
	 var file        = $('#file').val();
	 var zipFile     = $('#zipFile').val();
	 var dataWait    = '<i class="spinner-border spinner-border-sm"></i>';
	 var dataSent    = '<i class="far fa-paper-plane"></i>';

	 if (trimSpace(_message).length == 0 && file == '' && zipFile == '') {
		 var error = true;
		 return false;
	 }

	 if (error == false) {
		 $('#button-reply-msg').attr({'disabled' : 'true'}).html(dataWait);
	   $('.blocked').show();

	   if (file != '' || zipFile != '') {
	     $('.progress-upload-cover').show();
	   }

	 (function() {
	   var percent = $('.progress-upload-cover');
	   var percentVal = '0%';

			$("#formSendMsg").ajaxForm({
			dataType : 'json',
	    error: function(responseText, statusText, xhr, $form) {
	     element.removeAttr('disabled');
	     percent.width(percentVal);

	     $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred + ' ' + xhr).fadeIn('500').delay('5000').fadeOut('500');
	     $('#button-reply-msg').removeAttr('disabled').html(dataSent);
	     $('.blocked').hide();
	    },
	    beforeSend: function() {
	      if (file != '' || zipFile != '') {
	       percent.width(percentVal);
	     }
	   },
	   uploadProgress: function(event, position, total, percentComplete) {
	       var percentVal = percentComplete + '%';
	       if (file != '' || zipFile != '') {
	       percent.width(percentVal);
	     }
	   },
			success:  function(result) {

	      if (result.success == true && !param.test(_lastId)) {
	        Chat(result.last_id);
	        $('.progress-upload-cover').hide();
	      }

			//===== SUCCESS =====//
			if( result.success != false ) {
	      if (file == '') {
	        $('#message').val('');
	      }

	       $('#file').val('');
				 $('#zipFile').val('');
	       $('#removeFile').hide();
	       $('.previewFile').html('');
	       $('#previewFile').html('');
					$('#errorMsg').fadeOut();
	        $('#showErrorMsg').html('');
					$('#button-reply-msg').attr({'disabled' : 'true'}).html(dataSent).addClass('e-none');
	        $('.blocked').hide();
	        $('.progress-upload-cover').hide();
	        percent.width(percentVal);

			 }//<-- e
			 else if( result.error_custom ) {
				 $('#button-reply-msg').removeAttr('disabled').html(dataSent);
	       $('.blocked').hide();
	       $('#errorMsg').fadeIn();
				 $('#showErrorMsg').html(result.error_custom).fadeIn(500);
	       $('.progress-upload-cover').hide();
	       percent.width(percentVal);
				 $('#file').val('');
				 $('#zipFile').val('');
			 }
				 else {
			 var error = '';
			 var $key = '';

	  		 for($key in result.errors) {
	  			 error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
	  		 }

	        $('#errorMsg').fadeIn();
	    	 $('#showErrorMsg').html(error).fadeIn(500);
	       $('#button-reply-msg').removeAttr('disabled').html(dataSent);
	       $('.blocked').hide();
	       $('.progress-upload-cover').hide();
	       percent.width(percentVal);
				 $('#file').val('');
				 $('#zipFile').val('');
			 }

			 if (result.session_null) {
				 window.location.reload();
			 }
		 }//<----- SUCCESS
		 }).submit();
		 })(); //<--- FUNCTION %

	 }//<-- END ERROR == FALSE
	});//<<<-------- * END FUNCTION CLICK * ---->>>>

	//<----- Chat Live
	function Chat($idFirstMsg) {

		var param    = /^[0-9]+$/i;
		var _lastId  = $('div.chatlist:last').attr('data');
		var _userId  = $('.content').attr('data');
		var _list    = $('.content').html();
	  var _firstMsg = false;

		if (subscribed_active == false) {
			return false;
		}

		if (!param.test(_lastId) && !$idFirstMsg) {
			return false;
		}

	  if ($idFirstMsg) {
	    _lastId = $idFirstMsg;
	    var _firstMsg = true;
	  }

			//****** COUNT DATA
			$.get(URL_BASE+"/messages/ajax/chat", { last_id:_lastId, user_id: _userId, first_msg: _firstMsg }, function(res) {
			if (res) {

				$('.popout').slideUp('500');

	      if (res.userOnline == true) {
	        $('#lastSeen').hide();
	        $('.user-status').removeClass('user-offline').addClass('user-online');
	      } else {
	        $('.user-status').removeClass('user-online').addClass('user-offline');
	        $('#lastSeen').remove();
	        $('#timeAgo').html('<small class="timeAgo" id="lastSeen" data="'+res.last_seen+'" style="display: inline;"></small>')

	        jQuery(".timeAgo").timeago();
	      }

				if (res.total != 0) {

				var total_data = res.messages.length;

				for (var i = 0; i < total_data; ++i) {
					$( res.messages[i] ).hide().appendTo('.content').fadeIn(500);
					}

					jQuery(".timeAgo").timeago();

				   	var myDiv = $("#contentDIV").get(0);
				   	myDiv.scrollTop = myDiv.scrollHeight;

	          new SmartPhoto(".js-smartPhoto",{
							resizeStyle: 'fit',
							showAnimation: false,
							nav: false,
							useHistoryApi: false
						});

						const players = Plyr.setup('.js-player');

				    }
			   }//<-- DATA

			},'json').fail(function(jqXHR, ajaxOptions, thrownError)
			{
				$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500');
			});
	}//End Function TimeLine

	setInterval(Chat, 3000);

	//<---------- * Remove Message * ---------->
	 $(document).on('click','.removeMsg',function(){

	  var element   = $(this);
	  var data      = element.attr('data');
	  var deleteMsg = element.attr('data-delete');
	  var query     = 'message_id='+data;

	  swal(
	 	 {
	     title: delete_confirm,
	 		text: confirm_delete_message,
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

	  element.parents('div.chatlist').fadeTo( 200,0.00, function(){
	             element.parents('div.chatlist').slideUp( 200, function(){
	               element.parents('div.chatlist').remove();
	              });
	           });

	  $.ajax({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	      },
	    type : 'POST',
	    url  : URL_BASE+'/message/delete',
	    dataType: 'json',
	    data : query,

	  }).done(function(data) {

	    if (data.success != true) {
	      $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
	      return false;
	    }

	    if (data.session_null) {
	    window.location.reload();
	  }
	  });//<--- Done

	    }
	  });
	});//<---- * End Remove Message * ---->

	//<<==================== PAGINATOR Messages Chat
	$(document).on('click','.loadMoreMessages', function(e) {

	  e.preventDefault();

	var container = $(this).parents('.content');
	var allElements = $(container).find('div.chatlist').length;
	var firstMsg  = $('.chatlist:first');
	var curOffset = firstMsg.offset().top - $('#contentDIV').scrollTop();
	var user_id = $(this).parents('.wrap-container').attr('data-id');
	var wrapContainer = $(this).parents('.wrap-container');

	wrapContainer.html('<span class="spinner-border align-middle text-primary mb-2"></span>');

	$.ajax({
	  url: URL_BASE+'/loadmore/messages?id=' + user_id + '&skip=' + allElements
	}).done(function(data) {

	  if (data) {

	    wrapContainer.html('');

	    $(data).insertAfter(wrapContainer);

	    $('#contentDIV').scrollTop(firstMsg.offset().top-curOffset);

	    wrapContainer.remove();

	    jQuery(".timeAgo").timeago();

	    new SmartPhoto(".js-smartPhoto",{
	      resizeStyle: 'fit',
	      showAnimation: false,
	      nav: false,
	      useHistoryApi: false
	    });

	    const players = Plyr.setup('.js-player');

	  } else {
	    $('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
	  }
	  //<**** - Tooltip
	}).fail(function(jqXHR, ajaxOptions, thrownError)
	{
	  $('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
	});//<--- AJAX
	});

	// Remove file
	$('#removeFile').on('click', function() {
	    $('#file').val('');
	    $('.previewFile').html('');
	    $(this).hide();
	   });

		 // Upload FILE
		 $("#file").on('change', function() {

		   $('#zipFile').val('');

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

		         $('#file').val('');
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
		         $('#file').val('');
		         swal({
		           title: error_oops,
		           text: max_size_id,
		           type: "error",
		           confirmButtonText: ok
		           });
		         return false;
		       }
		       $('#button-reply-msg').removeAttr('disabled').trigger('click');
		     }
		   }
		 });
		 //============ END UPLOAD FILE

		 //======= Upload File
		 $("#zipFile").on('change', function() {

		 $('#file').val('');

		 var loaded = false;
		 if(window.File && window.FileReader && window.FileList && window.Blob) {
		 	 //check empty input filed
		  if($(this).val()) {
		 		var oFReader = new FileReader(), rFilter = /^(?:application\/x-zip-compressed)$/i;
		 	 if($(this)[0].files.length === 0){return}

		 	 var oFile = $(this)[0].files[0];
		 	 var fsize = $(this)[0].files[0].size; //get file size
		 	 var ftype = $(this)[0].files[0].type; // get file type

		 		if(!rFilter.test(oFile.type)) {
		 		 $('#zipFile').val('');
		 			swal({
		 			 title: error_oops,
		 			 text: formats_available_upload_file,
		 			 type: "error",
		 			 confirmButtonText: ok
		 			 });
		 		 return false;
		 	 }

		 	 var allowed_file_size = file_size_allowed;

		 	 if(fsize>allowed_file_size){
		 		 swal({
		 			title: error_oops,
		 			text: max_size_id,
		 			type: "error",
		 			confirmButtonText: ok
		 			});
		 		return false;
		 	 }
			 $('#button-reply-msg').removeAttr('disabled').trigger('click');

		  }
		 } else{
		  alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
		  return false;
		 }
		 });
		 //======= Upload File

		 // Delete Conversation
		 $(document).on('click','.actionDelete', function(e){

		 		 e.preventDefault();

		 		 var element = $(this);
		 		 var form    = $(element).parents('form');
		 		 element.blur();

		 	 swal(
		 		 {   title: delete_confirm,
		 			text: confirm_delete_conversation,
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
		 			});// Delete Conversation

})(jQuery);
