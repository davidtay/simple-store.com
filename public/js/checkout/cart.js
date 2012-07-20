/**
 * 
 */

function updateCart()
{
	var $form = $('form[name=cartForm]');
	var data = $form.serialize();
	$.post("/checkout/cart/update",data, function(res){
		if (parseInt(res)==1)
			window.location.href = "/checkout/cart";
			
	});
	return false;
}

function proceedToCheckout()
{
	window.location.href = "/checkout/onepage";
	return false;
}