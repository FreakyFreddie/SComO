$(document).ready(function()
{
	//when button is clicked
	$("#adduserstoproject").click(function()
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddUsersToProjectRequest.php?r=" + new Date().getTime()
		});

		$request.done(function(msg)
		{
			//display message when users are successfully added
			$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Succes</strong> De projecten zijn toegewezen.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();

				//redirect
				window.location.replace("adminprojecten");
			});
		});

		$request.fail(function()
		{
			//display message when project could not be added
			$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> De projecten konden niet worden toegewezen</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
				$("button[name='refresh']").trigger("click");
			});
		});
	});
});