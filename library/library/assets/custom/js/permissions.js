(function($) { 
	"use strict";
	
	$('.mainmodule').each(function() {
        var mainid  = $(this).attr('id');
        var idsplit = mainid.split('_');

        var mainmodule = idsplit[0];
        var roleID     = idsplit[1];

        var mainidadd    = mainmodule+"_add_"+roleID;
        var mainidedit   = mainmodule+"_edit_"+roleID;
        var mainiddelete = mainmodule+"_delete_"+roleID;
        var mainidview   = mainmodule+"_view_"+roleID;

        if (!$('#'+mainid).is(':checked')) {
            $('#'+mainidadd).prop('disabled', true);
            $('#'+mainidadd).prop('checked', false);
        
            $('#'+mainidedit).prop('disabled', true);
            $('#'+mainidedit).prop('checked', false);
        
            $('#'+mainiddelete).prop('disabled', true);
            $('#'+mainiddelete).prop('checked', false);
        
            $('#'+mainidview).prop('disabled', true);
            $('#'+mainidview).prop('checked', false);
        }
    });

    $(document).ready(function() {
        $('.mainmodule').on('click', function() {

            var mainid  = $(this).attr('id');
            var idsplit = mainid.split('_');

            var mainmodule = idsplit[0];
            var roleID     = idsplit[1];

            var mainidadd    = mainmodule+"_add_"+roleID;
            var mainidedit   = mainmodule+"_edit_"+roleID;
            var mainiddelete = mainmodule+"_delete_"+roleID;
            var mainidview   = mainmodule+"_view_"+roleID;

            if($('#'+mainid).is(':checked')) {
                $('#'+mainidadd).prop('disabled', false);
                $('#'+mainidadd).prop('checked', true);

                $('#'+mainidedit).prop('disabled', false);
                $('#'+mainidedit).prop('checked', true);

                $('#'+mainiddelete).prop('disabled', false);
                $('#'+mainiddelete).prop('checked', true);

                $('#'+mainidview).prop('disabled', false);
                $('#'+mainidview).prop('checked', true);
              } else {
                $('#'+mainidadd).prop('disabled', true);
                $('#'+mainidadd).prop('checked', false);
            
                $('#'+mainidedit).prop('disabled', true);
                $('#'+mainidedit).prop('checked', false);
            
                $('#'+mainiddelete).prop('disabled', true);
                $('#'+mainiddelete).prop('checked', false);
            
                $('#'+mainidview).prop('disabled', true);
                $('#'+mainidview).prop('checked', false);
            }

        });
    });

})(jQuery);