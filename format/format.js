$(function(){

	$('.body form, .overlay form').submit(function(){
		var form_elements = $.trim($(this).serialize());
		var form_url = $.trim($(this).attr('action'));
		var form_method = $.trim($(this).attr('method'));
		var current_form = $.trim($(this).attr('id'));
		$('#' + current_form + " input").removeClass('form-error');
		$('#' + current_form + " textarea").removeClass('form-error');
		$('#' + current_form + " label.form-error").remove();
		$('#' + current_form + " input").removeClass('form-success');
		$('#' + current_form + " textarea").removeClass('form-success');
		$('#' + current_form + " label.form-success").remove();
		$.ajax({url:form_url, type:form_method, data:form_elements, dataType:'json', success:function(form_json_messages){
				/* If the success key has a value, the form was succesfully received by the backend php.
					if not, we will tell the users what mistakes they have done by highlighting appropriate
					text boxes and adding messages.
					
					If the 'redirect_url' JSON key is occupied, we are going to redirect the user to the
					intended url passed back by the PHP form.
				*/
				if(form_json_messages["success"]){
					$('#' + current_form).append("<label class='form-success'><h4>" + form_json_messages["success"] + "</h4></label>");
				}else{
					$('#' + current_form).append("<label class='form-error'></label>");
					$.each(form_json_messages, function(key, val){
						$('#' + current_form + " input[name='" + key + "']").addClass('form-error');
						$('#' + current_form + " textarea[name='" + key + "']").addClass('form-error');
						//Let's check if val is an array (returned JSON is multidimensional. If it is,
						//loop through the returned values for the array within array - multiple errors per box.
						if(val instanceof Array){
							$.each(val, function(keys,mval){
								$('#' + current_form + " label.form-error").append("<h4>" + mval + "</h4>");
							});
						}else{
							$('#' + current_form + " label.form-error").append("<h4>" + val + "</h4>");
						}
					});
				}
				if(form_json_messages["redirect_url"]){
					setTimeout("location.href='" + form_json_messages["redirect_url"] + "';", 1500);
				}
		}
		});
		return false;
	});
	
	$('.ajax-request').click(function(){
		var attached_info_list = $(this).attr('id').split(",");
		var attached_info_array = {};
		for(var i=0; i<attached_info_list.length; i++){
			var attached_info = attached_info_list[i].split(":");
			var attached_info_identifier = attached_info[0];
			var attached_info_value = attached_info[1];
			attached_info_array[attached_info_identifier] = attached_info_value;
		}
		var ajax_form = $(this).attr('class').split(" ");
		var ajax_form = ajax_form[1];
		$.post("http://myrighttoplay.com/MRTP_revised_2/ajax/" + ajax_form + ".php", attached_info_array, function(data){ alert(data); });
		return false;
	});
	
	//Fix IE7 and IE8 button clicking problems with <button> element inside a href anchor
	$(".ie7 a > button, .ie8 a > button").click(function(){ 
		location.href = $(this).closest("a").attr("href");
	});
	
	//Fix IE7, IE8 delay on overlay close. Just refresh the page
	$('.ie7 .close, .ie8 .close').click(function(){
		location.reload();
	});
	
	//Fix IE7 and IE8 placeholder text not showing.
	$('.ie7 input[placeholder], ie8 input[placeholder]').each(function(){  
    var input = $(this);        
    $(input).val(input.attr('placeholder'));
                
    $(input).focus(function(){
        if (input.val() == input.attr('placeholder')) {
           input.val('');
        }
    });
        
    $(input).blur(function(){
       if (input.val() == '' || input.val() == input.attr('placeholder')) {
           input.val(input.attr('placeholder'));
       }
    });
	});
});
