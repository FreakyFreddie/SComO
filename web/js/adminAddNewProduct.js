$(document).ready(function()
{
	//when create button is clicked
	$("#createnewproduct").click(function()
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddNewProductRequest.php?r=" + new Date().getTime(),
			data: {id: $("#id").val(), leverancier: "EMSYS", naam: $("#naam").val(), prijs: $("#prijs").val()}
		});

		$request.done(function()
		{
			//display message when project is successfully added
			$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Succes</strong> Het product is aangemaakt.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();

				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		});

		$request.fail(function()
		{
			//display message when project could not be added
			$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> Het product kon niet worden aangemaakt. Probeer opnieuw.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
			});
		});
	});
});