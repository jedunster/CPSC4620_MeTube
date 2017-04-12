var title = false;
var file = false;
var keywords = true;
$(document).ready(function(){
	
	//disable submission until fields properly filled
	$('form').submit( function(){
		if(title){
			if(file){
				if(keywords){
					return true;
				}else{alert("Keywords should contain only a-z and be separated by spaces.");}
			}else{alert("Select a file of supported file type for upload.");}
		}else{alert("You must have a title.");}
		return false;
        });
	
	$('#fileInput').on('change', function(){
		if($(this).val() !== ""){
			var ext = $('#fileInput').val().split('.').pop().toLowerCase();
			if($.inArray(ext, 
			    ['ico','cur','wav','mp3','mp4','webm','ogg','gif','png','jpg','jpeg','svg','bmp']) == -1) {
				file=false;
			}else file=true;
		}else file = false;
	});

	$('#title').on('change', function(){
		if($(this).val() !== ""){
			title = true;
		}else title = false;
	});


	$('#keywords').on('change', function(){
		$('#keywords').val($('#keywords').val().toLowerCase());
		var list = $('#keywords').val().split(' ');
		var syntax=true;
		for(var i = 0; i < list.length; i++)
			if(!/^[a-z]*$/i.test(list[i])){
				syntax = false;
			}
		if(syntax === true) keywords = true; else keywords = false;
	});
	
});
