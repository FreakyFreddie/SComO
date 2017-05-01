//removing a product should not be possible since a person may have ordered it
function changeProject(id)
{
	$("table").find("tr").each(function(){
		if($(this).find("td").eq(1).text() == id)
		{
			//save content of columns
			var titel = $(this).find("td").eq(2).text();
			var budget = $(this).find("td").eq(3).text();
			var rekening = $(this).find("td").eq(4).text();
			var startdatum = $(this).find("td").eq(5).text();
			var einddatum = $(this).find("td").eq(6).text();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();
			$(this).find("td").eq(5).empty();
			$(this).find("td").eq(6).empty();

			//replace button by accept or cancel change
			$(this).find("td").eq(7).empty();

			//insert input fields
			$(this).find("td").eq(2).append('<input type="text" value="'+ titel +'"/>');
			$(this).find("td").eq(3).append('<input type="text" value="'+ budget +'"/>');
			$(this).find("td").eq(4).append('<input type="text" value="'+ rekening +'"/>');
			$(this).find("td").eq(5).append('<input type="text" value="'+ startdatum +'"/>');
			$(this).find("td").eq(6).append('<input type="text" value="'+ einddatum +'"/>');
			$(this).find("td").eq(7).append('<button class="btn btn-default" type="button" onclick="changeProjectAccept('+ id +')"><i class="fa fa-check fa-lg"></i></button><button class="btn btn-default" type="button" onclick="changeProjectDiscard('+ id +')"><i class="fa fa-remove fa-lg"></i></button>');
		}
	});
}

function changeProjectAccept(id)
{
	$("table").find("tr").each(function(){
		if($(this).find("td").eq(1).text() == id)
		{
			//save content of columns
			var titel = $(this).find("td").eq(2).find("input").val();
			var budget = $(this).find("td").eq(3).find("input").val();
			var rekening = $(this).find("td").eq(4).find("input").val();
			var startdatum = $(this).find("td").eq(5).find("input").val();
			var einddatum = $(this).find("td").eq(6).find("input").val();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();
			$(this).find("td").eq(5).empty();
			$(this).find("td").eq(6).empty();

			//replace buttons
			$(this).find("td").eq(7).empty();

			//prepare request
			$request = $.ajax({
				method:"POST",
				url:"AJAX/adminModifyProjectRequest.php?r=" + new Date().getTime(),
				data: {
					id: id,
					titel: titel,
					budget: budget,
					rekening: rekening,
					startdatum: startdatum,
					einddatum: einddatum
				}
			});

			//insert input fields
			$(this).find("td").eq(2).append(titel);
			$(this).find("td").eq(3).append(budget);
			$(this).find("td").eq(4).append(rekening);
			$(this).find("td").eq(5).append(startdatum);
			$(this).find("td").eq(6).append(einddatum);
			$(this).find("td").eq(7).append('<button class="btn btn-default" type="button" name="wijzig" onclick="changeProject('+ id +')"><i class="fa fa-exchange fa-lg"></i></button>');
		}
	});
}

function changeProjectDiscard(id)
{
	$("table").find("tr").each(function(){
		if($(this).find("td").eq(1).text() == id)
		{
			//save content of columns
			var titel = $(this).find("td").eq(2).find("input").val();
			var budget = $(this).find("td").eq(3).find("input").val();
			var rekening = $(this).find("td").eq(4).find("input").val();
			var startdatum = $(this).find("td").eq(5).find("input").val();
			var einddatum = $(this).find("td").eq(6).find("input").val();

			//empty columns
			$(this).find("td").eq(2).empty();
			$(this).find("td").eq(3).empty();
			$(this).find("td").eq(4).empty();
			$(this).find("td").eq(5).empty();
			$(this).find("td").eq(6).empty();

			//replace button by accept or cancel change
			$(this).find("td").eq(7).empty();

			//insert input fields
			$(this).find("td").eq(2).append(titel);
			$(this).find("td").eq(3).append(budget);
			$(this).find("td").eq(4).append(rekening);
			$(this).find("td").eq(5).append(startdatum);
			$(this).find("td").eq(6).append(einddatum);
			$(this).find("td").eq(7).append('<button class="btn btn-default" type="button" name="wijzig" onclick="changeProject('+ id +')"><i class="fa fa-exchange fa-lg"></i></button>');
		}
	});
}