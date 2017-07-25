var mdjm_vars;
jQuery(document).ready(function ($) {

	/* = Datepicker
	====================================================================================== */
	var mdjm_datepicker = $( '.mdjm_datepicker' );
	if ( mdjm_datepicker.length > 0 ) {
		var dateFormat = mdjm_vars.date_format;
        var firstDay   = mdjm_vars.first_day;
        mdjm_datepicker.datepicker({
			dateFormat  : dateFormat,
            altfield    : '#_mdjm_event_date',
            altformat   : 'yy-mm-dd',
            firstday    : firstDay,
            changeyear  : true,
            changemonth : true
		});
	}

    /* = Timepicker
	====================================================================================== */
    var mdjm_timepicker = $('.mdjm_timepicker' );
    if ( mdjm_timepicker.length > 0 ) {
        $('input.mdjm_timepicker').timepicker({
            timeFormat: mdjm_vars.timepicker_format,
            interval: 15,
            minTime: '0',
            maxTime: '11:59pm',
            startTime: '19',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
    }

	/*=Payments Form
	---------------------------------------------------- */
	// Load the fields for the selected payment method
	$('select#mdjm-gateway, input.mdjm-gateway').change( function () {

		var payment_mode = $('#mdjm-gateway option:selected, input.mdjm-gateway:checked').val();

		if( payment_mode === '0' )	{
			return false;
		}

		mdjm_load_gateway( payment_mode );

		return false;
	});

	// Auto load first payment gateway
	if( mdjm_vars.is_payment === '1' && $('select#mdjm-gateway, input.mdjm-gateway').length ) {
		setTimeout( function() {
			mdjm_load_gateway( mdjm_vars.default_gateway );
		}, 200);
	}

	$( document.body ).on( 'click', '#mdjm-payment-part', function() {
		$('#mdjm-payment-custom').show('fast');
	});

	$( document.body ).on( 'click', '#mdjm-payment-deposit, #mdjm-payment-balance', function() {
		$('#mdjm-payment-custom').hide('fast');
	});

	$(document).on('click', '#mdjm_payment_form #mdjm_payment_submit input[type=submit]', function(e) {
		var mdjmPurchaseform = document.getElementById('mdjm_payment_form');

		if( typeof mdjmPurchaseform.checkValidity === 'function' && false === mdjmPurchaseform.checkValidity() ) {
			return;
		}

		e.preventDefault();

		$(this).val(mdjm_vars.payment_loading);
		$(this).prop('disabled', true);
		$(this).after('<span class="mdjm-payment-ajax"><i class="mdjm-icon-spinner mdjm-icon-spin"></i></span>');

		var valid = mdjm_validate_payment_form(mdjmPurchaseform);

		if ( valid.type === 'success' )	{
			$(mdjmPurchaseform).find('.mdjm-alert').hide('fast');
			$(mdjmPurchaseform).find('.error').removeClass('error');
			$(mdjmPurchaseform).submit();
		} else	{
			$(mdjmPurchaseform).find('.mdjm-alert').show('fast');
			$(mdjmPurchaseform).find('.mdjm-alert').text(valid.msg);

			if ( valid.field )	{
				$('#' + valid.field).addClass('error');
			}

			$(this).val(mdjm_vars.complete_payment);
			$(this).prop('disabled', false);
		}

	});

	/*=Availability Checker
	---------------------------------------------------- */
	if( mdjm_vars.availability_ajax )	{
		$('#mdjm-availability-check').submit(function(event)	{
			if( !$('#availability_check_date').val() )	{
				return false;
			}
			event.preventDefault ? event.preventDefault() : false;
			var date = $('#availability_check_date').val();
			var postURL = mdjm_vars.rest_url;
			postURL += 'availability/';
			postURL += '?date=' + date;
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url:  postURL,
				beforeSend: function()	{
					$('input[type="submit"]').hide();
					$('#pleasewait').show();
				},
				success: function(response)	{
					var availability = response.data.availability;
					if(availability.response === 'available') {
						if( mdjm_vars.available_redirect !== 'text' )	{
							window.location.href = mdjm_vars.available_redirect + 'mdjm_avail_date=' + date;
						} else	{
							$('#mdjm-availability-result').replaceWith('<div id="mdjm-availability-result">' + availability.message + '</div>');
							$('#mdjm-submit-availability').fadeTo('slow', 1);
							$('#pleasewait').hide();
						}
						$('input[type="submit"]').prop('disabled', false);
					} else	{
						if( mdjm_vars.unavailable_redirect !== 'text' )	{
							window.location.href = mdjm_vars.unavailable_redirect + 'mdjm_avail_date=' + date;
						} else	{
							$('#mdjm-availability-result').replaceWith('<div id="mdjm-availability-result">' + availability.message + '</div>');
							$('#mdjm-submit-availability').fadeTo('slow', 1);
							$('#pleasewait').hide();
						}
						
						$('input[type="submit"]').prop('disabled', false);
					}
				}
			});
		});
	}

	$('#mdjm-availability-check').validate({
		rules: {
			'mdjm-availability-datepicker' : {
				required: true,
			},
		},
		messages: {
			'mdjm-availability-datepicker': {
				required: mdjm_vars.required_date_message,
			},
		},
	
		errorClass: 'mdjm_form_error',
		validClass: 'mdjm_form_valid',
	});

    /*=Event Builder
	---------------------------------------------------- */
    // Step through
    $(document).on('click', '#mdjm-event-builder-buttons .next, #mdjm-event-builder-buttons .previous', function(e) {
        e.preventDefault();

        var $form     = $('#mdjm-event-builder-form');
		var eventData = $('#mdjm-event-builder-form').serialize();
        var next_step = $(this).data('step');

        $.ajax({
			type       : 'POST',
			dataType   : 'json',
			data       : eventData,
			url        : mdjm_vars.ajaxurl,
            beforeSend: function()	{
				$form.find('.mdjm-alert').hide('fast');
				$('.mdjm_loading_overlay').show();
                $('#mdjm-event-builder-buttons').hide();
            },
			success    : function (response) {
				if ( response.data && response.data.error )	{
					$form.find('.mdjm-alert').show('fast');
					$form.find('.mdjm-alert').html(response.data.error);
					$form.find('#' + response.data.field).addClass('error');
					$form.find('#' + response.data.field).focus();
					$('.mdjm_loading_overlay').hide();
					$('#mdjm-event-builder-buttons').show();
				} else	{
                    $form.attr('action', mdjm_vars.event_builder_page + 'mdjm_eb_step=' + next_step);
					$form.get(0).submit();
				}
			}
		}).fail(function (data) {
			if ( window.console && window.console.log ) {
				console.log( data );
			}
		});
    });
    
});

function mdjm_validate_payment_form() {

	var msg = false;

	// Make sure an amount is selected
	var payment = jQuery('input[type="radio"][name="mdjm_payment_amount"]:checked');

	if ( payment.length === 0 ) {
		return( {msg:mdjm_vars.no_payment_amount} );
	}

	// If part payment, make sure the value is greater than 0
	if ( 'part_payment' === payment.val() )	{
		var amount = jQuery('#part-payment').val();

		if ( ! jQuery.isNumeric(amount) )	{
			return( {type:'error', field:'part-payment', msg:mdjm_vars.no_payment_amount} );
		}
	} 

	return( {type:'success'} );

}

function mdjm_load_gateway( payment_mode ) {

	// Show the ajax loader
	jQuery('.mdjm-payment-ajax').show();
	jQuery('#mdjm_payment_form_wrap').html('<img src="' + mdjm_vars.ajax_loader + '"/>');

	var url = mdjm_vars.ajaxurl;

	if ( url.indexOf( '?' ) > 0 ) {
		url = url + '&';
	} else {
		url = url + '?';
	}

	url = url + 'payment-mode=' + payment_mode;

	jQuery.post(url, { action: 'mdjm_load_gateway', mdjm_payment_mode: payment_mode },
		function(response){
			jQuery('#mdjm_payment_form_wrap').html(response);
			jQuery('.mdjm-no-js').hide();
			jQuery('.mdjm-payment-ajax').hide();
		}
	);

}
