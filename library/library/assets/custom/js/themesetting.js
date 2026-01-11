(function($) { 
	"use strict";
	
	$(document).ready(function() {

		$('.single_theme').on('click', function() {
			$('.single_theme').removeClass('single_theme_active');
			$(this).addClass('single_theme_active');
			$('.theme_active').remove();
			$(this).append('<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>');
			
			var theme = $(this).data('theme');

			if(theme != '') {
				$.ajax({
	                type: "POST",
	                url: THEME_BASE_URL + 'themesetting/settheme',
	                data: {'theme':theme},
	                dataType: 'html',
	                success: function(data) {
	                    location.reload();
	                }
	            });
			}
		});
		
	});
        
})(jQuery);