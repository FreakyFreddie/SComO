$(document).ready(function()
{
	//when a product button is clicked, product id, supplier and amount are sent to the shopping cart
	$(".productbutton").click(function()
	{
		var DOMobj = $(this).parent().prev();

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/processAddToCartRequest.php?r=" + new Date().getTime(),
			data: {productid: $(DOMobj).data("productid"), supplier: $(DOMobj).data("supplier"), amount: $(DOMobj).val()}
		});

		$request.done(function()
		{
			//display message when product is successfully added
			$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Toegevoegd!</strong> Het product is toegevoegd aan je winkelmandje.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
			});
		});

		$request.fail(function()
		{
			//display message when product could not be added
			$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> Het product kon niet worden toegevoegd aan je winkelmandje.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
			});
		});
	});
});