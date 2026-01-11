(function($) { 
	"use strict";
	
	$(document).ready(function() {
        $('#bookcategoryID').on('change', function() {
            var bookcategoryID = $('#bookcategoryID').val();
            $.ajax({
                type: "POST",
                url: THEME_BASE_URL + 'bookbarcode/get_book',
                data: {'bookcategoryID':bookcategoryID},
                dataType: 'html',
                success: function(data) {
                    $('#bookID').html(data);
                }
            });
        });

        $('#bookcategoryID, #bookID').on('change', function() {
            $('.divhide').hide();
        });
    });

})(jQuery);