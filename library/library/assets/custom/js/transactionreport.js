jQuery(document).ready(function() {
    
    if(globalreportfor == 1 || globalreportfor == 2) {
        jQuery('#roleID').parent().parent().hide();
        jQuery('#memberID').parent().parent().hide();
    } else {
        jQuery('#roleID').parent().parent().show();
        jQuery('#memberID').parent().parent().show();
    } 

    jQuery('#reportfor').change(function() {
        var reportfor = jQuery(this).val();
        if(reportfor == 1 || reportfor == 2) {
            jQuery('#roleID').parent().parent().hide('slow');
            jQuery('#memberID').parent().parent().hide('slow');
        } else {
            jQuery('#roleID').parent().parent().show('slow');
            jQuery('#memberID').parent().parent().show('slow');
        }
    });

    jQuery('#roleID').on('change', function() {
        var roleID = jQuery('#roleID').val();
        jQuery.ajax({
            type: "POST",
            url: THEME_BASE_URL + 'transactionreport/get_member',
            data: {'roleID':roleID},
            dataType: 'html',
            success: function(data) {
                jQuery('#memberID').html(data);
            }
        });
    });

    jQuery('#bookcategoryID, #bookID, #roleID, #memberID, #status, #fromdate, #todate').on('change', function() {
        jQuery('.divhide').hide();
    });
});