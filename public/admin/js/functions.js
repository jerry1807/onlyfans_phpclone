(function($) {
"use strict";

//Flat red color scheme for iCheck
$('input[type="radio"]').iCheck({
	radioClass: 'iradio_flat-red'
});

// Input checkbox iCheck
$('input[type="checkbox"]').iCheck({
	checkboxClass: 'icheckbox_square-red',
	radioClass: 'iradio_square-red',
	increaseArea: '20%'
});

//Colorpicker
$('.my-colorpicker2').colorpicker();

// Tags Input
$("#tagInput").tagsInput({
 'delimiter': [','],   // Or a string with a single delimiter. Ex: ';'
 'width':'auto',
 'height':'auto',
	 'removeWithBackspace' : true,
	 'minChars' : 2,
	 'maxChars' : 30,
	 'defaultText': add_tag
});

// Select2 Initiator
$('.select2 option[value="'+timezone+'"]').attr('selected', 'selected');
$('.select2').select2();

// Add subcategories
$("#addSub").on('click', function(e) {

	e.preventDefault();

	var saveHtml    = $('#addSub').html();
	$('#addSub').attr({'disabled' : 'true'}).html('<i class="fa fa-cog fa-spin fa-1x fa-fw fa-loader"></i>');

    $.ajax({
        url: URL_BASE + "/panel/admin/subcategories/add",
        type:"POST",
        data: $('#addSubForm').serialize(),
        success:function(data){
            window.location.reload();
        },error:function(data){
           var errors =data.responseJSON;

		     var errorsHtml = '';

		    $.each(errors['errors'], function(index, value) {
		        errorsHtml += '<li><i class="glyphicon glyphicon-remove myicon-right"></i> ' + value + '</li>';
		        });

					$('#errorsAlert').html(errorsHtml);
					$('#boxErrors').fadeIn();
					$('#addSub').removeAttr('disabled').html(saveHtml);
		        }
    }); //end of ajax
});// End Add subcategories

// Delete Post, Categories, Members, Languages, etc...
$(".actionDelete").on('click', function(e) {
   	e.preventDefault();

   	var element = $(this);
    var form    = $(element).parents('form');

	element.blur();

	swal(
		{   title: delete_confirm,
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
		    	 	form.submit();
		    	 	}
          });
		 });

		 // Delete Blog
		 $(".actionDeleteBlog").on('click', function(e) {
		    	e.preventDefault();

		    var element = $(this);
		 	  var url     = element.attr('data-url');

		 	element.blur();

		 	swal(
		 		{   title: delete_confirm,
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
		 		    	 	window.location.href = url;
		 		    	 	}
		 		    });
		 		 });

	 // Email Driver
	 $('#emailDriver').on('change', function() {

	     if($(this).val() == 'mailgun') {
	 			$('#mailgun').slideDown();
	 		} else {
	 				$('#mailgun').slideUp();
	 		}

	 		if($(this).val() == 'postmark') {
	 			$('#postmark').slideDown();
	 		} else {
	 			$('#postmark').slideUp();
	 		}

	     if($(this).val() == 'ses') {
	 			$('#ses').slideDown();
	 		} else {
	 			$('#ses').slideUp();
	 		}
	 });

	 // Accept Only Numbers
	 $(document).ready(function() {

	     $(".onlyNumber").on('keydown', function (e) {
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
	     });
	 });

	 // Delete Image in Theme section
	 $('.delete-image').on('click', function() {
         var element = $(this);

         element.parents('.box-body').find('.fileContainer').addClass('display-none');
         element.parents('.box-body').find('.file-name-file').html('');
         element.parents('.box-body').find('.filePhoto').val('');
   });

	 // Upload Image in Theme section
	 $(".filePhoto").on('change', function(){

     var element = $(this);

     element.parents('.box-file').find('.text-file').html(choose_image);

   	var loaded = false;
   	if(window.File && window.FileReader && window.FileList && window.Blob){
       // Check empty input filed
   		if($(this).val()) {

   			var oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
   			if($(this)[0].files.length === 0){return}

   			var oFile = $(this)[0].files[0];
         var fsize = $(this)[0].files[0].size; //get file size
   			var ftype = $(this)[0].files[0].type; // get file type

         // Validate formats
         if(!rFilter.test(oFile.type)) {
   				element.val('');
   				alert(formats_available);
   				return false;
   			}

         // Validate Size
         if(!rFilter.test(oFile.type)) {
   				element.val('');
   				alert(formats_available);
   				return false;
   			}

   			oFReader.onload = function (e) {

   				var image = new Image();
           image.src = oFReader.result;

   				image.onload = function() {

             element.parents('.box-body').find('.fileContainer').removeClass('display-none');
             element.parents('.box-body').find('.file-name-file').html(oFile.name);
           };// <<--- image.onload
         }
           oFReader.readAsDataURL($(this)[0].files[0]);
   		}// Check empty input filed
   	}// window File
   });
   // END Upload Image in Theme section

	 // Cancel Payment
	 $(".cancel_payment").on('click', function(e) {
	    	e.preventDefault();

	     var element = $(this);
	     var form    = $(element).parents('form');

	 	element.blur();

	 	swal(
	 		{   title: delete_confirm,
	 		  type: "warning",
	       text: cancel_payment,
	 		  showLoaderOnConfirm: true,
	 		  showCancelButton: true,
	 		  confirmButtonColor: "#DD6B55",
	 		   confirmButtonText: yes_cancel_payment,
	 		   cancelButtonText: cancel_confirm,
	 		    closeOnConfirm: false,
	 		    },
	 		    function(isConfirm){
	 		    	 if (isConfirm) {
	 		    	 	form.submit();
	 		    	 	}
	 		    	 });
	 		 });

			 // Approve verification request
			 $(".actionApprove").on('click', function(e) {
			    	e.preventDefault();

			   var element = $(this);
				 var url     = element.attr('href');
				 var form    = $(element).parents('form');

			 	element.blur();

			 	swal(
			 		{   title: delete_confirm,
			 		  type: "warning",
			       text: approve_confirm_verification,
			 		  showLoaderOnConfirm: true,
			 		  showCancelButton: true,
			 		  confirmButtonColor: "#52bb03",
			 		   confirmButtonText: yes_confirm_approve_verification,
			 		   cancelButtonText: cancel_confirm,
			 		    closeOnConfirm: false,
			 		    },
			 		    function(isConfirm){
			 		    	 if (isConfirm) {
			 		    	 	form.submit();
			 		    	 	}
			 		    	});
			 		 });

					 // Delete verification request
					 $(".actionDeleteVerification").on('click', function(e) {
					    	e.preventDefault();

					  var element = $(this);
					 	var url     = element.attr('href');
					 	var form    = $(element).parents('form');

					 	element.blur();

					 	swal(
					 		{   title: delete_confirm,
					 		  type: "warning",
					       text: delete_confirm_verification,
					 		  showLoaderOnConfirm: true,
					 		  showCancelButton: true,
					 		  confirmButtonColor: "#DD6B55",
					 		   confirmButtonText: yes_confirm_verification,
					 		   cancelButtonText: cancel_confirm,
					 		    closeOnConfirm: false,
					 		    },
					 		    function(isConfirm){
					 		    	 if (isConfirm) {
					 		    	 	form.submit();
					 		    	 	}
					 		    });
					 		});

})(jQuery);
