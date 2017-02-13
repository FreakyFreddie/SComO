$(document).ready(function()
{
	//add add projects button to the toolbar
	$("#finalorderlist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="markordersarrived" class="btn btn-default" type="button" name="markordersarrived"><span class="glyphicon glyphicon-plus"></span> Aangekomen</button></div>');

	//script to get id & titel of projects
	$("#markordersarrived").click(function()
	{
		//set projects array
		var orders=[];

		$(this).parent().parent().next().find("table").find(".selected").each(function ()
		{
			var order = {id: $(this).find("td").eq(1).text()};

			//push project to array
			orders.push(order);
		});

		if(confirm("U staat op het punt orders te markeren als aangekomen. Dit is onomkeerbaar.\nDoorgaan?"))
		{
			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminMarkOrdersArrivedRequest.php?r=" + new Date().getTime(),
				data: {array: orders}
			});

			$request.done(function()
			{
				//refresh all tables
				$("button[name='refresh']").trigger("click");
			});
		}

	});
});