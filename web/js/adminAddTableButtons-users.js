$(document).ready(function()
{
	//add remove button to the toolbar of table 1
	$(".removebutton1").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="removebutton1" class="btn btn-default" type="button" name="removeusers" title="Verwijderen"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button></div>');

	//script to get remove a user
	$("#removebutton1").click(function()
	{
		var cont = confirm("Deze actie is onomkeerbaar! Doorgaan?");

		if(cont == true)
		{
			//set projects array
			var users=[];

			$(this).parent().parent().next().find("table").find(".selected").each(function () {
				var user = {rnummer: $(this).find("td").eq(1).text()};

				//push project to array
				users.push(user);
			});

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

function changeUser(id)
{
	$("table").find("tr").each(function()
	{
		if($(this).find("td").eq(1).text() == id)
		{
			//save content of columns
			var email = $(this).find("td").eq(2).text();
			var naam = $(this).find("td").eq(3).text();
			var voornaam = $(this).find("td").eq(4).text();
			var niveau = $(this).find("td").eq(5).text();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();
			$(this).find("td").eq(5).empty();

			//replace button by accept or cancel change
			$(this).find("td").eq(7).empty();

			//insert input fields
			$(this).find("td").eq(2).append('<input type="text" value="'+ email +'"/>');
			$(this).find("td").eq(3).append('<input type="text" value="'+ naam +'"/>');
			$(this).find("td").eq(4).append('<input type="text" value="'+ voornaam +'"/>');
			$(this).find("td").eq(5).append('<input type="text" value="'+ niveau +'"/>');
			$(this).find("td").eq(7).append('<button class="btn btn-default" type="button" onclick="changeUserAccept(\''+ id +'\')"><i class="fa fa-check fa-lg"></i></button><button class="btn btn-default" type="button" onclick="changeUserDiscard(\''+ id +'\')"><i class="fa fa-remove fa-lg"></i></button>');
		}
	});
}

function changeUserAccept(id)
{
	$("table").find("tr").each(function(){
		if($(this).find("td").eq(1).text() == id)
		{
			//save content of columns
			var email = $(this).find("td").eq(2).find("input").val();
			var naam = $(this).find("td").eq(3).find("input").val();
			var voornaam = $(this).find("td").eq(4).find("input").val();
			var niveau = $(this).find("td").eq(5).find("input").val();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();
			$(this).find("td").eq(5).empty();

			//replace buttons
			$(this).find("td").eq(7).empty();

			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminModifyUserRequest.php?r=" + new Date().getTime(),
				data: {
					rnummer: id,
					email: email,
					naam: naam,
					voornaam: voornaam,
					niveau: niveau,
				}
			});

			//insert input fields
			$(this).find("td").eq(2).append(email);
			$(this).find("td").eq(3).append(naam);
			$(this).find("td").eq(4).append(voornaam);
			$(this).find("td").eq(5).append(niveau);
			$(this).find("td").eq(7).append('<button class="btn btn-default" type="button" name="wijzig" onclick="changeUser(\''+ id +'\')"><i class="fa fa-exchange fa-lg"></i></button>');
		}
	});
}

function changeUserDiscard(id)
{
	$("table").find("tr").each(function(){
		if($(this).find("td").eq(1).text() == id)
		{
			//save content of columns
			var email = $(this).find("td").eq(2).find("input").val();
			var naam = $(this).find("td").eq(3).find("input").val();
			var voornaam = $(this).find("td").eq(4).find("input").val();
			var niveau = $(this).find("td").eq(5).find("input").val();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();
			$(this).find("td").eq(5).empty();

			//replace button by accept or cancel change
			$(this).find("td").eq(7).empty();

			//insert input fields
			$(this).find("td").eq(2).append(email);
			$(this).find("td").eq(3).append(naam);
			$(this).find("td").eq(4).append(voornaam);
			$(this).find("td").eq(5).append(niveau);
			$(this).find("td").eq(7).append('<button class="btn btn-default" type="button" name="wijzig" onclick="changeUser(\''+ id +'\')"><i class="fa fa-exchange fa-lg"></i></button>');
		}
	});
}