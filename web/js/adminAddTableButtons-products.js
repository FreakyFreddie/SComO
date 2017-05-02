//removing a product should not be possible since a person may have ordered it
function changeProduct(id, leverancier)
{
	$("table").find("tr").each(function()
	{
		if(($(this).find("td").eq(0).text() == id) && ($(this).find("td").eq(1).text() == leverancier))
		{
			//save content of columns
			var naam = $(this).find("td").eq(2).text();
			var verkoper = $(this).find("td").eq(3).text();
			var prijs = $(this).find("td").eq(4).text();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();

			//replace button by accept or cancel change
			$(this).find("td").eq(5).empty();

			//insert input fields
			$(this).find("td").eq(2).append('<input type="text" value="'+ naam +'"/>');
			$(this).find("td").eq(3).append('<input type="text" value="'+ verkoper +'"/>');
			$(this).find("td").eq(4).append('<input type="text" value="'+ prijs +'"/>');
			$(this).find("td").eq(5).append('<button class="btn btn-default" type="button" onclick="changeProductAccept(\''+ id +'\',\''+ leverancier +'\')"><i class="fa fa-check fa-lg"></i></button><button class="btn btn-default" type="button" onclick="changeProductDiscard(\''+ id +'\',\''+ leverancier +'\')"><i class="fa fa-remove fa-lg"></i></button>');
		}
	});
}

function changeProductAccept(id, leverancier)
{
	$("table").find("tr").each(function(){
		if(($(this).find("td").eq(0).text() == id) && ($(this).find("td").eq(1).text() == leverancier))
		{
			//save content of columns
			var naam = $(this).find("td").eq(2).find("input").val();
			var verkoper = $(this).find("td").eq(3).find("input").val();
			var prijs = $(this).find("td").eq(4).find("input").val();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();

			//replace buttons
			$(this).find("td").eq(5).empty();

			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminModifyProductRequest.php?r=" + new Date().getTime(),
				data: {
					id: id,
					leverancier: leverancier,
					naam: naam,
					verkoper: verkoper,
					prijs: prijs
				}
			});

			//insert input fields
			$(this).find("td").eq(2).append(naam);
			$(this).find("td").eq(3).append(verkoper);
			$(this).find("td").eq(4).append(prijs);
			$(this).find("td").eq(5).append('<button class="btn btn-default" type="button" name="wijzig" onclick="changeProduct(\''+ id +'\',\''+ leverancier +'\')"><i class="fa fa-exchange fa-lg"></i></button>');
		}
	});
}

function changeProductDiscard(id, leverancier)
{
	$("table").find("tr").each(function(){
		if(($(this).find("td").eq(0).text() == id) &&($(this).find("td").eq(1).text() == leverancier))
		{
			//save content of columns
			var naam = $(this).find("td").eq(2).find("input").val();
			var verkoper = $(this).find("td").eq(3).find("input").val();
			var prijs = $(this).find("td").eq(4).find("input").val();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();

			//replace button by accept or cancel change
			$(this).find("td").eq(5).empty();

			//insert input fields
			$(this).find("td").eq(2).append(naam);
			$(this).find("td").eq(3).append(verkoper);
			$(this).find("td").eq(4).append(prijs);
			$(this).find("td").eq(5).append('<button class="btn btn-default" type="button" name="wijzig" onclick="changeProduct(\''+ id +'\',\''+ leverancier +'\')"><i class="fa fa-exchange fa-lg"></i></button>');
		}
	});
}