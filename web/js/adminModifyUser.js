$(document).ready(function()
{
	//when button is clicked
	$("#selectuser").change(function()
	{
		if($(this).val() != "Maak een keuze...")
		{
			var rnummervoornaamachternaam = $(this).val();

			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminGetUserInfoToModifyRequest.php?r=" + new Date().getTime(),
				data: {rnummervoornaamachternaam: rnummervoornaamachternaam}
			});

			$request.done(function(msg)
			{
				//process the received data
				var obj = JSON.parse(msg);

				//set the values
				$("#voornaam").val(obj.voornaam);
				$("#email").val(obj.email);
				$("#achternaam").val(obj.achternaam);

				//change selected state to machtiginsniveau
				switch(obj.machtigingsniveau)
				{
					case "non-actief":
						$('#machtigingsniveau>option:eq(0)').prop('selected', true);
						break;

					case "user":
						$('#machtigingsniveau>option:eq(1)').prop('selected', true);
						break;

					case "admin":
						$('#machtigingsniveau>option:eq(2)').prop('selected', true);
						break;

					case "banned":
						$('#machtigingsniveau>option:eq(3)').prop('selected', true);
						break;
				}
			});

			$request.fail(function()
			{
				//display message when project could not be added
				$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> De gebruiker kon niet worden opgehaald.</div>').insertBefore($("footer")).fadeOut(2000, function()
				{
					$(this).remove();
				});
			});
		}

	});

	$("#modifyuser").click(function()
	{
		if($("#selectuser").val() != "Maak een keuze...")
		{
			var rnummervoornaamachternaam = $("#selectuser").val();
			var email = $("#email").val();
			var voornaam = $("#voornaam").val();
			var achternaam = $("#achternaam").val();
			var machtigingsniveau = $("#machtigingsniveau").val();

			//prepare request
			$request = $.ajax({
				method: "POST",
				url: "AJAX/adminModifyUserRequest.php?r=" + new Date().getTime(),
				data: {
					rnummervoornaamachternaam: rnummervoornaamachternaam,
					email: email,
					voornaam: voornaam,
					achternaam: achternaam,
					machtigingsniveau: machtigingsniveau
				}
			});
		}
	});
});