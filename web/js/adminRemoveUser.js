$(document).ready(function()
{
	//add add projects button to the toolbar
	$("#userlist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="removeusers" class="btn btn-default" type="button" name="removeusers"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button></div>');

	//script to get id & titel of projects
	$("#removeusers").click(function()
	{
		//set projects array
		var users=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function ()
		{
			var user = {userid: $(this).find("td").eq(1).text()};

			//push project to array
			users.push(user);
		});

		if(confirm("U staat op het punt gebruikers te verwijderen.\nDoorgaan?"))
		{
			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminRemoveUsersRequest.php?r=" + new Date().getTime(),
				data: {array: users}
			});

			$request.done(function()
			{
				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		}

	});
});