$(document).ready(function()
{
	//when button is clicked
	$("#selectproject").change(function()
	{
		if($(this).val() != "Maak een keuze...")
		{
			var idtitle = $(this).val();

			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminGetProjectInfoToModifyRequest.php?r=" + new Date().getTime(),
				data: {idtitle: idtitle}
			});

			$request.done(function(msg)
			{
				//process the received data
				var obj = JSON.parse(msg);

				//set the values
				$("#projecttitle").val(obj.projectTitle);
				$("#projectfunds").val(obj.projectFunding);
				$("#projectaccount").val(obj.projectAccountNumber);
				$("#projectstartdate").val(obj.projectStartDate);
				$("#projectenddate").val(obj.projectEndDate);
			});

			$request.fail(function()
			{
				//display message when project could not be added
				$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> Het project kon niet worden opgehaald.</div>').insertBefore($("footer")).fadeOut(2000, function()
				{
					$(this).remove();
				});
			});
		}

	});

	$("#modifyproject").click(function()
	{
		if($("#selectproject").val() != "Maak een keuze...")
		{
			var idtitle = $("#selectproject").val();
			var title = $("#projecttitle").val();
			var funds = $("#projectfunds").val();
			var account = $("#projectaccount").val();
			var startdate = $("#projectstartdate").val();
			var enddate = $("#projectenddate").val();

			//prepare request
			$request = $.ajax({
				method: "POST",
				url: "AJAX/adminModifyProjectRequest.php?r=" + new Date().getTime(),
				data: {
					idtitle: idtitle,
					title: title,
					funds: funds,
					account: account,
					startdate: startdate,
					enddate: enddate
				}
			});

			$request.done(function()
			{
				//display message when project is successfully added
				$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Succes</strong> Het project is gewijzigd.</div>').insertBefore($("footer")).fadeOut(2000, function()
				{
					$(this).remove();
				});
			});
		}
	});
});