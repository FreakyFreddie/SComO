$(document).ready(function()
{
	//when create button is clicked
	$("#createnewproject").click(function()
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddNewProjectRequest.php?r=" + new Date().getTime(),
			data: {projecttitle: $("#projecttitle").val(), projectfunds: $("#projectfunds").val(), projectaccount: $("#projectaccount").val(), projectstartdate: $("#projectstartdate").val(), projectenddate: $("#projectenddate").val()}
		});

		$request.done(function()
		{
			//display message when project is successfully added
			$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Succes</strong> Het project is aangemaakt</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
					//refresh all tables
					$("button[name='refresh']").trigger("click");
			});
		});

		$request.fail(function()
		{
			//display message when project could not be added
			$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> Het project kon niet worden aangemaakt. Probeer opnieuw.</div>').insertBefore($("footer")).fadeOut(2000, function()
			{
				$(this).remove();
			});
		});
	});
});