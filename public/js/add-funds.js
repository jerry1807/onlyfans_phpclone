//<--------- Start Payment -------//>
(function($) {
	"use strict";

  $('input[name=payment_gateway]').on('click', function() {

		if($(this).val() == '3') {
			$('#bankTransferBox').slideDown();
		} else {
				$('#bankTransferBox').slideUp();
		}

    if($(this).val() == 2) {
      $('#stripeContainer').slideDown();
    } else {
      $('#stripeContainer').slideUp();
    }
  });

	//======= FILE Bank Transfer
$("#fileBankTransfer").on('change', function() {

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
			 $('#fileBankTransfer').val('');
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
//======= END FILE Bank Transfer

 //<---------------- Send Tip ----------->>>>
 if (stripeKey != '') {

 // Create a Stripe client.
 var stripe = Stripe(stripeKey);

 // Create an instance of Elements.
 var elements = stripe.elements();

 // Custom styling can be passed to options when creating an Element.
 // (Note that this demo uses a wider set of styles than the guide below.)
 var style = {
	 base: {
		 color: colorStripe,
		 fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
		 fontSmoothing: 'antialiased',
		 fontSize: '16px',
		 '::placeholder': {
			 color: '#aab7c4'
		 }
	 },
	 invalid: {
		 color: '#fa755a',
		 iconColor: '#fa755a'
	 }
 };

 // Create an instance of the card Element.
 var cardElement = elements.create('card', {style: style, hidePostalCode: true});

 // Add an instance of the card Element into the `card-element` <div>.
 cardElement.mount('#card-element');

 // Handle real-time validation errors from the card Element.
 cardElement.addEventListener('change', function(event) {
	 var displayError = document.getElementById('card-errors');
	 var payment = $('input[name=payment_gateway]:checked').val();

	 if (payment == 2) {
		 if (event.error) {
			 displayError.classList.remove('display-none');
			 displayError.textContent = event.error.message;
			 $('#addFundsBtn').removeAttr('disabled');
			 $('#addFundsBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
		 } else {
			 displayError.classList.add('display-none');
			 displayError.textContent = '';
		 }
	 }

 });

 var cardholderName = document.getElementById('cardholder-name');
 var cardholderEmail = document.getElementById('cardholder-email');
 var cardButton = document.getElementById('addFundsBtn');

 cardButton.addEventListener('click', function(ev) {

	 var payment = $('input[name=payment_gateway]:checked').val();

	 if (payment == 2) {

	 stripe.createPaymentMethod('card', cardElement, {
		 billing_details: {name: cardholderName.value, email: cardholderEmail.value}
	 }).then(function(result) {
		 if (result.error) {

			 if (result.error.type == 'invalid_request_error') {

					 if(result.error.code == 'parameter_invalid_empty') {
						 $('.popout').addClass('popout-error').html(error).fadeIn('500').delay('8000').fadeOut('500');
					 } else {
						 $('.popout').addClass('popout-error').html(result.error.message).fadeIn('500').delay('8000').fadeOut('500');
					 }
			 }
			 $('#addFundsBtn').removeAttr('disabled');
			 $('#addFundsBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

		 } else {

			 $('#addFundsBtn').attr({'disabled' : 'true'});
			 $('#addFundsBtn').find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

			 // Otherwise send paymentMethod.id to your server
			 $('input[name=payment_method_id]').remove();

			 var $input = $('<input id=payment_method_id type=hidden name=payment_method_id />').val(result.paymentMethod.id);
			 $('#formAddFunds').append($input);

			 $.ajax({
			 headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				type: "POST",
				dataType: 'json',
				url: URL_BASE+"/add/funds",
				data: $('#formAddFunds').serialize(),
				success: function(result) {
						handleServerResponse(result);

						if(result.success == false) {
							$('#addFundsBtn').removeAttr('disabled');
							$('#addFundsBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						}
			}//<-- RESULT
			})

		 }//ELSE
	 });
 }//PAYMENT STRIPE
});

 function handleServerResponse(response) {
	 if (response.error) {
		 $('.popout').addClass('popout-error').html(response.error).fadeIn('500').delay('8000').fadeOut('500');
		 $('#addFundsBtn').removeAttr('disabled');
		 $('#addFundsBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

	 } else if (response.requires_action) {
		 // Use Stripe.js to handle required card action
		 stripe.handleCardAction(
			 response.payment_intent_client_secret
		 ).then(function(result) {
			 if (result.error) {
				 $('.popout').addClass('popout-error').html(error_payment_stripe_3d).fadeIn('500').delay('10000').fadeOut('500');
				 $('#addFundsBtn').removeAttr('disabled');
				 $('#addFundsBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

			 } else {
				 // The card action has been handled
				 // The PaymentIntent can be confirmed again on the server

				 var $input = $('<input type=hidden name=payment_intent_id />').val(result.paymentIntent.id);
				 $('#formAddFunds').append($input);

				 $('input[name=payment_method_id]').remove();

				 $.ajax({
				 headers: {
						 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					type: "POST",
					dataType: 'json',
					url: URL_BASE+"/add/funds",
					data: $('#formAddFunds').serialize(),
					success: function(result){

						if(result.success) {
							window.location.reload();
						} else {
							$('.popout').addClass('popout-error').html(result.error).fadeIn('500').delay('8000').fadeOut('500');
							$('#addFundsBtn').removeAttr('disabled');
							$('#addFundsBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						}
				}//<-- RESULT
				})
			 }// ELSE
		 });
	 } else {
		 // Show success message
		 if (response.success) {
				 window.location.reload();
		 }
	 }
 }
}
// Stripe Elements


//<---------------- Pay tip ----------->>>>
 $(document).on('click','#addFundsBtn',function(s) {

	 s.preventDefault();
	 var element = $(this);
	 var form = $(this).attr('data-form');
	 element.attr({'disabled' : 'true'});
	 var payment = $('input[name=payment_gateway]:checked').val();
	 element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

	 (function(){
			$('#formAddFunds').ajaxForm({
			dataType : 'json',
			success:  function(result) {

				// success
				if (result.success == true && result.insertBody) {

					$('#bodyContainer').html('');

				 $(result.insertBody).appendTo("#bodyContainer");

				 if (payment != 1 && payment != 2) {
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
				 }

					$('#errorAddFunds').hide();

				} else if(result.success == true && result.status == 'pending') {

					swal({
					 title: thanks,
					 text: result.status_info,
					 type: "success",
					 confirmButtonText: ok
					 });

					 $('#formAddFunds').trigger("reset");
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 $('#previewImage').html('');
					 $('#handlingFee, #total, #total2').html('0');
					 $('#bankTransferBox').hide();

				} else if(result.success == true && result.url) {
					window.location.href = result.url;
				} else {

					if (result.errors) {

						var error = '';
						var $key = '';

						for($key in result.errors) {
							error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						}

						$('#showErrorsFunds').html(error);
						$('#errorAddFunds').show();
						element.removeAttr('disabled');
						element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					}
				}

			 },
			 error: function(responseText, statusText, xhr, $form) {
					 // error
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 swal({
							 type: 'error',
							 title: error_oops,
							 text: error_occurred+' ('+xhr+')',
						 });
			 }
		 }).submit();
	 })(); //<--- FUNCTION %
 });//<<<-------- * END FUNCTION CLICK * ---->>>>
//============ End Payment =================//

})(jQuery);
