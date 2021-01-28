//<--------- Start Payment -------//>
(function($) {
	"use strict";

  $('input[name=payment_gateway]').click(function() {
    if($(this).val() == 2) {
      $('#stripeContainer').slideDown();
    } else {
      $('#stripeContainer').slideUp();
    }
  });

  $(document).ready(function() {

    //<---------------- Buy Subscription ----------->>>>
			$(document).on('click','#subscriptionBtn',function(s) {

				s.preventDefault();
				var element = $(this);
				element.attr({'disabled' : 'true'});
        var payment = $('input[name=payment_gateway]:checked').val();
        element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

				(function(){
					 $("#formSubscription").ajaxForm({
					 dataType : 'json',
					 success:  function(result) {

             // success
             if(result.success == true && result.insertBody) {

               $('#bodyContainer').html('');

              $(result.insertBody).appendTo("#bodyContainer");

              if (payment != 1 && payment != 2) {
                element.removeAttr('disabled');
                element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
              }

               $('#error').fadeOut();

             } else if(result.success == true && result.url) {
               window.location.href = result.url;
             } else {

               var error = '';
               var $key = '';

               for($key in result.errors) {
                 error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
               }

               $('#showErrors').html(error);
               $('#error').fadeIn(500);
               element.removeAttr('disabled');
               element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

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
  });// document ready

})(jQuery);
