function addmore(th, val,i) {
	//th.innerHTML="  Remove";
	//jQuery(th).removeAttr('onclick','addmore');
	//jQuery(th).attr('id','deleteme1');
	//jQuery('.copy').clone(true).removeClass('copy').appendTo('.check').show();
    jQuery('.'+i+'copy').clone().removeClass(i+'copy').addClass('noclass').appendTo("#"+i+"classformtable").show();
	//jQuery('.stime').attr('id','clockpick');
 }


jQuery(function(){ 
	
	jQuery('a#deleteme1').live('click',function(){
		jQuery(this).attr('id','deleteme');
	});

	jQuery('a#deleteme').live('click',function(){
		jQuery(this).parent().parent().parent().parent().parent().parent().remove();
	});
});		

function removeimage(th)
{
	jQuery(th).parent().parent().parent().parent().parent().parent().remove();
}

function addvals(th)
{
	 var multipleValues = jQuery(th).val() || [];
	 multipleValues.join(", ");
	 jQuery(th).parent().parent().children(':last-child').attr("value", multipleValues);

}

function checkForm()
{
	var class_val = jQuery('#class_code').val();
	var inst_val = jQuery('#inst_name').val();
	var start_time_val = jQuery('#start_time').val();
}
