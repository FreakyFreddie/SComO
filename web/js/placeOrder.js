$(document).ready(function()
{
	//display or hide the project list when a radio button is clicked
	$("#projectlist").hide();
	$(".orderpersoonlijk").change(function()
	{
		if($('.orderpersoonlijk:checked').val() == "persoonlijk")
		{
			$("#projectlist").hide();
		}
		if($('.orderpersoonlijk:checked').val() == "project")
		{
			$("#projectlist").show();
		}
	});

	$("#orderproducts").click(function()
	{
		var orderpersoonlijk = $('.orderpersoonlijk:checked').val();
		var project = $("#selectproject").val();

		var r = confirm("Ben je zeker dat je deze bestelling definitief wil plaatsen?");

		if (r == true)
		{
			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/processPlaceOrderRequest.php?r=" + new Date().getTime(),
				data: {orderpersonal: orderpersoonlijk, project: project}
			});

			$request.done(function()
			{
				//display message when product is successfully added
				$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Geslaagd!</strong> De bestelling is succesvol geplaatst.</div>').insertBefore($("footer")).fadeOut(2000, function()
				{
					$("#shoppingcart").find(".row").remove();
					$("#shoppingcart").html("Bestelling geplaatst.");
				});
			});

			$request.fail(function()
			{
				//display message when product could not be added
				$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> De bestelling kon niet worden geplaatst.</div>').insertBefore($("footer")).fadeOut(2000, function()
				{
					$(this).remove();
				});
			});
		}
	});
});