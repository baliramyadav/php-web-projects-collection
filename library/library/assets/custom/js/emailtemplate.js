(function($) { 
	"use strict";
	
    $(document).ready(function() {
        $('#template').summernote({
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
                url: THEME_BASE_URL + "emailtemplate/uploads",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status) {
                        $('#template').summernote('insertImage', response.photo, function ($image) {
                            $image.css('width', $image.width() / 3);
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

        $('.single_email_tag').click(function() {
            var emailtag = " "+$(this).data('emailtag');

            $('#template').summernote('editor.saveRange');
            // Editor loses selected range (e.g after blur)
            $('#template').summernote('editor.restoreRange');
            $('#template').summernote('editor.focus');
            $('#template').summernote('editor.insertText', emailtag);

        });
    });

})(jQuery);