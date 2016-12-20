$(document).ready(function()
{
	//when create button is clicked
	$("#createnewuser").click(function()
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddNewUserRequest.php?r=" + new Date().getTime(),
			data: {rnummer: $("#rnummer").val(), voornaam: $("#voornaam").val(), achternaam: $("#achternaam").val(), wachtwoord: $("#wachtwoord").val(), wachtwoordconfirm: $("#wachtwoordconfirm").val(), email: $("#email").val(), machtigingsniveau: $("#machtigingsniveau").val()}
		});

		$request.done(function()
		{
			//display message when project is successfully added
			$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Succes</strong> De gebruiker is aangemaakt.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
			});
		});

		$request.fail(function()
		{
			//display message when project could not be added
			$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> De gebruiker kon niet worden aangemaakt. Probeer opnieuw.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
			});
		});
	});
});