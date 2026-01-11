(function($) { 
	"use strict";
	
    $(document).ready(function() {

        $('.select2').select2();
        
        $('#sender_roleID').change(function() {
            var sender_roleID = $(this).val();
            if(parseInt(sender_roleID)) {
                $.ajax({
                    type: 'POST',
                    url: THEME_BASE_URL + "emailsend/get_member",
                    data: {'roleID':sender_roleID},
                    dataType: "html",
                    success: function(data) {
                        $('#sender_memberID').html(data);
                    }
                });
            } else {
                $(this).parent().addClass('has-error');
            }
        });

        if(parseInt(set_sender_roleID)) {
            $.ajax({
                type: 'POST',
                url: THEME_BASE_URL + "emailsend/get_member",
                data: {'roleID':set_sender_roleID},
                dataType: "html",
                success: function(data) {
                    $('#sender_memberID').html(data);
                }
            });
        };

        $('#emailtemplateID').on('change', function() {
            var emailtemplateID = $(this).val();
            if(parseInt(emailtemplateID)) {
                $.ajax({
                    type: 'POST',
                    url: THEME_BASE_URL + "emailsend/emailtemplate",
                    data: {'emailtemplateID':emailtemplateID},
                    dataType: "html",
                    success: function(data) {
                        $('#message').summernote('editor.saveRange');
                        // Editor loses selected range (e.g after blur)
                        $('#message').summernote('editor.restoreRange');
                        $('#message').summernote('editor.focus');
                        $('#message').summernote('editor.pasteHTML', data);
                    }
                });
            }
        });

        $('#message').summernote({
            height: 200,
            callbacks: {
                onImageUpload: function(photo) {
                    summernoteuploadImage(photo[0]);
                }
            }
        });

        function summernoteuploadImage(photo) {
            var data = new FormData();
            data.append("photo",photo);
            $.ajax ({
                data: data,
                type: "POST",
                url: THEME_BASE_URL + "emailsend/uploads",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status) {
                        $('#message').summernote('insertImage', response.photo, function ($image) {
                            $image.css('width', $image.width() / 2);
                            $image.css('display', 'block');
                        });
                    } else {
                        console.log('Error');
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        $('#othermessage').summernote({
            height: 200,
            callbacks: {
                onImageUpload: function(photo) {
                    summernoteuploadImage(photo[0]);
                }
            }
        });

    });

})(jQuery);