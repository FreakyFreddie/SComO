$(document).ready(function()
{
	$("#orderproducts").click(function()
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/processShowFinalCartRequest.php?r=" + new Date().getTime()
		});

		$request.done(function(msg)
		{
			$("#shoppingcart").find(".row").remove();
			$('#shoppingcart').html(msg);

			$("#placeorder").click(function()
			{
				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/processPlaceOrderRequest.php?r=" + new Date().getTime(),
					data: {orderpersonal: 0}
				});

				$request.done(function()
				{
					//display message when product is successfully added
					$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Aangepast!</strong> De hoeveelheid van het product is succesvol gewijzigd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.fail(function()
				{
					//display message when product could not be added
					$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> De hoeveelheid van het product kon niet worden gewijzigd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});
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