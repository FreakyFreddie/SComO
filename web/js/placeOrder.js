$(document).ready(function()
{
	$("#placeorder").click(function()
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/processPlaceOrderRequest.php?r=" + new Date().getTime(),
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