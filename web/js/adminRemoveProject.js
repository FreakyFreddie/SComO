$(document).ready(function()
{
	//add add projects button to the toolbar
	$("#projectlist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="removeprojects" class="btn btn-default" type="button" name="removeprojects"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button></div>');

	//script to get id & titel of projects
	$("#removeprojects").click(function()
	{
		//set projects array
		var projects=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function ()
		{
			var project = {id: $(this).find("td").eq(1).text(), titel: $(this).find("td").eq(2).text()};

			//push project to array
			projects.push(project);
		});

		if(confirm("U staat op het punt projecten te verwijderen.\nDoorgaan?"))
		{
			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminRemoveProjectsRequest.php?r=" + new Date().getTime(),
				data: {array: projects}
			});

			$request.done(function()
			{
				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		}

	});
});