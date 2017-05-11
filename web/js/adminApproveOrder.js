$(document).ready(function()
{
	//buttons to add or remove
	$("#orderprojectlist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button class="approveorders btn btn-default" type="button" name="approveorders"><span class="glyphicon glyphicon-plus"></span> Goedkeuren</button></div>');

	$("#orderprojectlist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button class="disapproveorders btn btn-default" type="button" name="dissaproveorders"><span class="glyphicon glyphicon-minus"></span> Afkeuren</button></div>');

	$("#orderpersonallist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button class="approveorders btn btn-default" type="button" name="approveorders"><span class="glyphicon glyphicon-plus"></span> Goedkeuren</button></div>');

	$("#orderpersonallist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button class="disapproveorders btn btn-default" type="button" name="dissaproveorders"><span class="glyphicon glyphicon-minus"></span> Afkeuren</button></div>');

	//script to extract data
	$(".approveorders").click(function()
	{
		//set projects array
		var orders=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function ()
		{
			var order = {id: $(this).find("td").eq(1).text()};

			//push project to array
			orders.push(order);
		});

		if(confirm("U staat op het punt orders goed te keuren.\nDoorgaan?"))
		{
			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminApproveOrdersRequest.php?r=" + new Date().getTime(),
				data: {array: orders, status: "approved"}
			});

			$request.done(function()
			{
				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		}

	});

	//script to extract data
	$(".disapproveorders").click(function()
	{
		//set projects array
		var orders=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function ()
		{
			var order = {id: $(this).find("td").eq(1).text()};

			//push project to array
			orders.push(order);
		});

		if(confirm("U staat op het punt orders af te keuren.\nDoorgaan?"))
		{
			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminApproveOrdersRequest.php?r=" + new Date().getTime(),
				data: {array: orders, status: "denied"}
			});

			$request.done(function()
			{
				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		}

	});
});