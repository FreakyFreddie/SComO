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
			$("#shoppingcart > .row").remove();
			$('#shoppingcart').html(msg);
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