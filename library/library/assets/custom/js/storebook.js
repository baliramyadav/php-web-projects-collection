(function($) { 
	"use strict";

	function preview_image() 
	{
	 	var total_file=document.getElementById("images").files.length;
	 	for(var i=0;i<total_file;i++) {
	  		$('#image_preview').append("<img class='imgthumbnail' src='"+URL.createObjectURL(event.target.files[i])+"'>");
	 	}
	}
	
	$('#images').change(function() {
		preview_image();
    });
	
})(jQuery);