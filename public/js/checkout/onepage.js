/**
 * 
 */

function saveOnePage(formName)
{
	var accordionTabs = $("#accordion h3");
	
	var $form = $('form[name='+formName+']');
	var data = $form.serialize();
	
	$.post('/checkout/onepage/save',data,function(res){
		
		var formParent = $form.parents('.form-parent');
		var $nextH3 = formParent.next();
		var index = $(accordionTabs).index($nextH3);
		
		if (res.success == 1)
		{
			$('#totals').load('/checkout/onepage/totals');	
			available_indexes.push(index); 
			$nextH3.click();
		}
		else if (res.success == 0)
		{
			available_indexes.splice(index,1); 
		}
		else if (res.complete == 1)
		{
			
		}
	});
	
	return false;
}


