$(document).ready(function()
{
	//buttons to add or remove
	$("#farnelllist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="downloadfarnellBOM" class="btn btn-default" type="button" name="downloadfarnellBOM"><span class="glyphicon glyphicon-download"></span> BOM Downloaden</button></div>');

	$("#mouserlist").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="downloadmouserBOM" class="btn btn-default" type="button" name="downloadmouserBOM"><span class="glyphicon glyphicon-download"></span> BOM Downloaden</button></div>');

	//script to extract data
	$("#downloadfarnellBOM").click(function()
	{
		//request BOM
		window.open('./AJAX/adminDownloadBOMRequest.php?supplier=Farnell');
	});

	//script to extract data
	$("#downloadmouserBOM").click(function()
	{
		//request BOM
		window.open('./AJAX/adminDownloadBOMRequest.php?supplier=Mouser');
	});

	//script send data
	$("#addfarnellordernumber").click(function()
	{
		//get order number
		var farnellordernumber = $("#definitiefbestelnummerfarnell").val();

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddFinalOrderNumberRequest.php?r=" + new Date().getTime(),
			data: {finalordernumber: farnellordernumber, supplier: "Farnell"}
		});

		$request.done(function()
		{
			//refresh all tables
			$("button[name='refresh']").trigger("click");
		});
	});

	//script send data
	$("#addmouserordernumber").click(function()
	{
		//get order number
		var mouserordernumber = $("#definitiefbestelnummermouser").val();

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/adminAddFinalOrderNumberRequest.php?r=" + new Date().getTime(),
			data: {finalordernumber: mouserordernumber, supplier: "Mouser"}
		});

		$request.done(function()
		{
			//refresh all tables
			$("button[name='refresh']").trigger("click");
		});
	});
});