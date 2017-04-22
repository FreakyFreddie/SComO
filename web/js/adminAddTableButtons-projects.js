$(document).ready(function()
{
	//add remove button to the toolbar of table 1
	$(".removebutton1").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="removebutton1" class="btn btn-default" type="button" name="addprojects" title="Verwijderen"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button></div>');

	//script to get remove a project
	$("#removebutton1").click(function()
	{
		var cont = confirm("Deze actie is onomkeerbaar! Doorgaan?");

		if(cont == true)
		{
			//set projects array
			var projects=[];

			$(this).parent().parent().next().find("table").find(".selected").each(function () {
				var project = {id: $(this).find("td").eq(1).text()};

				//push project to array
				projects.push(project);
			});

			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminRemoveProjectsRequest.php?r=" + new Date().getTime(),
				data: {array: projects}
			});

			$request.done(function()
			{
				$("#projectlist").find(".selected").remove();
				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		}
	});

	$(".wijzig").click(function()
	{
		var cont = confirm("Deze actie is onomkeerbaar! Doorgaan?");

		if(cont == true)
		{
			//set projects array
			var projects=[];

			$(this).parent().parent().next().find("table").find(".selected").each(function () {
				var project = {id: $(this).find("td").eq(1).text()};

				//push project to array
				projects.push(project);
			});

			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminRemoveProjectsRequest.php?r=" + new Date().getTime(),
				data: {array: projects}
			});

			$request.done(function()
			{
				$("#projectlist").find(".selected").remove();
				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		}
	});
});

