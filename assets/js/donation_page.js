$(document).ready(function(){
	$("#ddcd").submit(function(event){
		event.preventDefault();
		form = $(this);
		form.trigger("loading");
		
		if(!form.validate({
			highlight:function(element,errorClass,validClass){
				input = $(element);
				row = input.parents(".formRow:first");
				input.addClass(errorClass).removeClass(validClass);
				row.addClass(errorClass).removeClass(validClass);
				$("label.error",row).show();
			},
			unhighlight:function(element,errorClass,validClass){
				input = $(element);
				row = input.parents(".formRow:first");
				input.removeClass(errorClass).addClass(validClass);
				row.removeClass(errorClass).addClass(validClass);
				$("label.error",row).hide();
			}
		}).form()){
			form.trigger("loaded");
			return false;
		}
		if(!form.hasClass("ajax")){
			form.unbind("submit").submit();
			return false;
		}
		$.ajax({
			url:form.attr('action'),
			data:form.serialize(),
			type:form.attr('method'),
			complete:function(XMLHttpRequest,textStatus){
				$("#ddcd").trigger("loaded");
			},
			success:function(data, textStatus, XMLHttpRequest){
				if(data['success']){
					if(data['page']){
						window.location = data['page'];
					}
				}else{
					if(data['error']){
						$("#DonationFormError").text(data['error']).show();	
					}
					for(var i in data){
						if(data[i]['error']){
							$(".error",$("#ddcd input[name="+i+"]").parent(".formRow")).text(data[i]['error']).show();
						}
					}
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert("error");
			}
		})		
	}).bind("loading",function(){
		$(this).addClass("loading");
	}).bind("loaded",function(){
		$(this).removeClass("loading");
	}).prepend('<input type="hidden" name="ajax" value="true" />');
	
	$("form.donation input.submit").before("<div class='loading'></div>");
	
	$("form.donation.mini").submit(function(event){
		if($("#ddcd").length>0){
			event.preventDefault();
			$.scrollTo("#ddcd",1000);
			$("#ddcd .giftstring input.radio").each(function(){
				radio = $(this);
				if(radio.val()==$("form.donation.mini .giftstring input.radio:checked").val()){
					radio.click();
				}
			});
			$("#ddcd .giftstring .otheramt input:not(.radio)").each(function(){
				other = $(this);
				matching = $("form.donation.mini .giftstring input[name='"+other.attr("name")+"']");
				if(matching.length>0){
					other.val($(matching[0]).val());
				}
			});
		}
		
	});
});