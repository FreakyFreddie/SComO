$(document).ready(function()
{
	//add add projects button to the toolbar
	$("#getprojects").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="addprojects" class="btn btn-default" type="button" name="addprojects" title="Zet projecten klaar om toe te wijzen aan een groep gebruikers"><span class="glyphicon glyphicon-plus"></span> Toevoegen</button></div>');

	//add add users button to the toolber
	$("#getusers").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="addusers" class="btn btn-default" type="button" name="addusers" title="Zet projecten klaar om toe te wijzen aan een groep gebruikers"><span class="glyphicon glyphicon-plus"></span> Toevoegen</button></div>');

	//add add users button to the toolber
	$("#listprojects").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="deleteprojects" class="btn btn-default" type="button" name="deleteprojects" title="Zet projecten klaar om toe te wijzen aan een groep gebruikers"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button></div>');

	//add add users button to the toolber
	$("#listusers").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="deleteusers" class="btn btn-default" type="button" name="deleteusers" title="Zet projecten klaar om toe te wijzen aan een groep gebruikers"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button></div>');

	//script to get id & titel of projects
	$("#addprojects").click(function()
	{
		//set projects array
		var projects=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function ()
		{
			var project = {id: $(this).find("td").eq(1).text(), titel: $(this).find("td").eq(2).text()};

			//push project to array
			projects.push(project);
		});

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddProjectsToAssingRequest.php?r=" + new Date().getTime(),
			data: {array: projects}
		});

		$request.done(function()
		{
			//refresh all tables
			$("button[name='refresh']").trigger("click");
		});
	});

	//script to get rnummer, naam & voornaam of users
	$("#addusers").click(function()
	{
		//set users array
		var users=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function () {
			var user = {email: $(this).find("td").eq(1).text(), naam: $(this).find("td").eq(2).text(), voornaam: $(this).find("td").eq(3).text()};

			//push project to array
			users.push(user);
		});

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddUsersToAssignRequest.php?r=" + new Date().getTime(),
			data: {array: users}
		});

		$request.done(function()
		{
			//refresh all tables
			$("button[name='refresh']").trigger("click");
		});
	});

	//script to get rnummer, naam & voornaam of users
	$("#deleteprojects").click(function()
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
			url:"AJAX/adminRemoveProjectsFromAssignRequest.php?r=" + new Date().getTime(),
			data: {array: projects}
		});

		$request.done(function()
		{
			$("#projectlist").find(".selected").remove();
			//refresh all tables
			$("button[name='refresh']").trigger("click");
		});
	});

	//script to get rnummer, naam & voornaam of users
	$("#deleteusers").click(function()
	{
		//set users array
		var users=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function () {
			var user = {email: $(this).find("td").eq(1).text()};

			//push project to array
			users.push(user);
		});

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminRemoveUsersFromAssignRequest.php?r=" + new Date().getTime(),
			data: {array: users}
		});

		$request.done(function()
		{
			$("#userlist").find(".selected").remove();
			//refresh all tables
			$("button[name='refresh']").trigger("click");
		});
	});
});

