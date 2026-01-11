(function($) { 
	"use strict";
	
	$(document).ready(function() {
        $('#roleID').on('change', function() {
            var roleID    = $(this).val();
            if(parseInt(roleID)) {
                $.ajax({
                    type: 'POST',
                    url: THEME_BASE_URL + "bookissue/get_member",
                    data: {'roleID': roleID},
                    dataType: "html",
                    success: function(data) {
                        $('#memberID').html(data);
                    }
                });
            }
        });

        $('#bookcategoryID').on('change', function() {
            var bookcategoryID    = $(this).val();
            $.ajax({
                type: 'POST',
                url: THEME_BASE_URL + "bookissue/get_book",
                data: {'bookcategoryID': bookcategoryID},
                dataType: "html",
                success: function(data) {
                    $('#bookID').html(data);
                }
            });
        });

        $('#bookID').on('change', function() {
            var bookID    = $(this).val();
            if(parseInt(bookID)) {
                $.ajax({
                    type: 'POST',
                    url: THEME_BASE_URL + "bookissue/get_book_item",
                    data: {'bookID': bookID},
                    dataType: "html",
                    success: function(data) {
                        $('#bookno').html(data);
                    }
                });
            }
        });

        var globalbookissueID = 0;
	    $('.paymentamount').on('click', function() {
	        globalbookissueID = $(this).data('bookissueid');
	        var error    = 0;

	        if(parseInt(globalbookissueID) && (globalbookissueID > 0)) {
	            $.ajax({
	                type: 'POST',
	                url: THEME_BASE_URL + "bookissue/get_paymentamount",
	                data: {'bookissueID': globalbookissueID},
	                dataType: "html",
	                success: function(data) {
	                    var response = JSON.parse(data);
	                    if(response.status) {
	                        $('.totalfineamount').text("Your total assign due amount is "+response.paymentamount+".");
	                        $('#paymentamount').attr('data-paymentamount', response.paymentamount);
	                        $('#discountamount').val(response.discountamount);
	                    } else {
	                        toastr.error(response.message);
	                    }
	                }
	            });
	        }
	    });

	    $('#paymentamount').on('keyup', function() {
	        var originalamount = convertNumber($('#paymentamount').data('paymentamount'));
	        var paymentamount  = convertNumber($('#paymentamount').val());
	        var discountamount = convertNumber($('#discountamount').val());
	        
	        var paymentdiscountamount = paymentamount+discountamount;
	        if(paymentdiscountamount > originalamount) {
	            paymentamount  = originalamount - discountamount;
	        }
	        $('#paymentamount').val(paymentamount);
	    });

	    $('#discountamount').on('keyup', function() {
	        var originalamount = convertNumber($('#paymentamount').data('paymentamount'));
	        var paymentamount  = convertNumber($('#paymentamount').val());
	        var discountamount = convertNumber($('#discountamount').val());

	        var paymentdiscountamount = paymentamount+discountamount;
	        if(paymentdiscountamount > originalamount) {
	            discountamount = originalamount - paymentamount;
	        } 
	        $('#discountamount').val(discountamount);
	    });

	    $('.submitpaymentamount').click(function(event) {
	        event.preventDefault();

	        var formdata = new FormData($('#paymentform')[0]);
	        formdata.append('bookissueID', globalbookissueID);

	        $.ajax({
	            type: 'POST',
	            url: THEME_BASE_URL + "bookissue/set_paymentamount",
	            data: formdata,
	            processData: false,
	            contentType: false,
	            success: function(data) {
	                var response = JSON.parse(data);
	                if(response.status) {
	                    location.reload();
	                } else {
	                    $.each(response, function( index, value ) {
	                        if(index != 'status') {
	                            toastr.error(value);
	                        }
	                    });
	                }
	            }
	        });
	    });

	    $('#fineamount').on('keyup', function() {
	        var fineamount = convertNumber($('#fineamount').val());
	        $('#fineamount').val(fineamount);
	    });
	   
	    function convertNumber(data) {
	        if(parseInt(data)) {
	            return parseInt(data);
	        }
	        return 0;
	    }

        $('#bookstatusID').change(function() {
	        var bookstatusID = $(this).val();
            $('.lostbookerror').text('');
            if(bookstatusID == 3) {
            	$('.lostbookerror').text('This fine amount added book price and also added fine amount.');
            }
            $.ajax({
                type: 'POST',
                url: THEME_BASE_URL + "bookissue/get_fineamount",
                data: {'bookissueID':bookissueID, 'bookstatusID': bookstatusID},
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status) {
                    	$('#fineamount').val(response.amount);
                    } else {
                    	toastr.error(response.message);
                    }
                }
            });
        });
    });

})(jQuery);