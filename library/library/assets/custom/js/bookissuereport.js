(function($) { 
	"use strict";

    $(document).ready(function() {
        $('#bookcategoryID').on('change', function() {
            var bookcategoryID = $('#bookcategoryID').val();
            $.ajax({
                type: "POST",
                url: THEME_BASE_URL + 'bookissuereport/get_book',
                data: {'bookcategoryID':bookcategoryID},
                dataType: 'html',
                success: function(data) {
                    $('#bookID').html(data);
                }
            });
        });

        $('#roleID').on('change', function() {
            var roleID = $('#roleID').val();
            $.ajax({
                type: "POST",
                url: THEME_BASE_URL + 'bookissuereport/get_member',
                data: {'roleID':roleID},
                dataType: 'html',
                success: function(data) {
                    $('#memberID').html(data);
                }
            });
        });

        $('#bookcategoryID, #bookID, #roleID, #memberID, #status, #fromdate, #todate').on('change', function() {
            $('.divhide').hide();
        });
    });

})(jQuery);