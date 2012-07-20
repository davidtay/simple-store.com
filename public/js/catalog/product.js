/**
 * 
 */

function addToCart(qty, id)
{
	//console.log(qty);
	qty = parseInt(qty);
	id = parseInt(id);
	
	if (id>0)
	{
		if (!qty || isNaN(qty) || qty <= 0 )
			qty = 1;
		
		var data = {
			'qty':qty,
			'id':id
		};
		$.post("/checkout/cart/add",data,function(res){
			console.log(res);
			if (parseInt(res) == id)
				window.location.href = "/checkout/cart";
			else
				alert("An error occurred");
		});		
	}
	else
		return false;
}