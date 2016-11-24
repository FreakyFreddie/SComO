$(document).ready(function()
{
	//when a product button is clicked, product id, supplier and amount are sent to the shopping cart
	$(".productbutton").click(function()
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"processAddToCartRequest.php?r=" + new Date().getTime(),
			data: {productid: $(this).parent().prev("input").attr("productid"), supplier: $(this).parent().prev("input").attr("supplier"), amount: $(this).parent().prev("input").val()}
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