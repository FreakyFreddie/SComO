$(document).ready(function()
{
	//when search button is clicked, process ajax request
	$("#searchproduct").click(function()
	{
		var searchterm = $("#searchterm").val();

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/processSearchProductRequest.php?r=" + new Date().getTime(),
			data: {searchterm: searchterm}
		});

		$request.done(function(msg)
		{
			var obj = JSON.parse(msg);

			$(".workspace").html('<noscript><div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.</div> </noscript>');

			for(var i = 0; i < obj.length; i++)
			{
				var name =obj[i].Name;
				var image =obj[i].Image;
				var supplier =obj[i].Supplier;
				var id =obj[i].Id;
				var vendor =obj[i].Vendor;
				var inventory =obj[i].Inventory;
				var datasheet =obj[i].DataSheet;
				var prices =obj[i].Prices;

				$(".workspace").append('<p>' + name + '</p>');
			}

			//when a product button is clicked, product id, supplier and amount are sent to the shopping cart
			$(".productbutton").click(function()
			{
				var DOMobj = $(this).parent().prev();

				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/processAddToCartRequest.php?r=" + new Date().getTime(),
					data: {productid: $(DOMobj).data("productid"), supplier: $(DOMobj).data("supplier"), amount: $(DOMobj).val()}
				});

				$request.done(function()
				{
					//display message when product is successfully added
					$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Toegevoegd!</strong> Het product is toegevoegd aan je winkelmandje.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.fail(function()
				{
					//display message when product could not be added
					$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> Het product kon niet worden toegevoegd aan je winkelmandje.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});
			});
		});
	});


});