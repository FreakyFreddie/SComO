$(document).ready(function()
{
	//recreate table with new data
	$('#displayshoppingcart').bootstrapTable({
		onPageChange:function(){
			//when a product button is clicked, product id, supplier and amount are sent to the shopping cart
			$(".deleteproduct").click(function()
			{
				var division = $(this).parent().parent().parent();

				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/processDeleteFromCartRequest.php?r=" + new Date().getTime(),
					data: {productid: $(this).attr("productid"), supplier: $(this).attr("supplier")}
				});

				$request.done(function()
				{
					//remove from cart
					$(division).remove();

					//display message when product is successfully added
					$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Verwijderd</strong> Het product is succesvol uit uw winkelwagen verwijderd</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.fail(function()
				{
					//display message when product could not be added
					$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> Het product kon niet uit de winkelwagen worden verwijderd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.done(function()
				{
					//refresh all tables
					$("button[name='refresh']").trigger("click");
				});
			});

			//when a product button is clicked, product id, supplier and amount are sent to the shopping cart
			$(".amountproduct").change(function()
			{
				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/processUpdateProductAmountRequest.php?r=" + new Date().getTime(),
					data: {productid: $(this).data("productid"), supplier: $(this).data("supplier"), amount: $(this).val()}
				});

				$request.done(function()
				{
					//display message when product is successfully added
					$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Aangepast!</strong>De hoeveelheid van het product is succesvol gewijzigd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.fail(function()
				{
					//display message when product could not be added
					$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> De hoeveelheid van het product kon niet worden gewijzigd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});
			});
		},
		onLoadSuccess: function(){
			//when a product button is clicked, product id, supplier and amount are sent to the shopping cart
			$(".deleteproduct").click(function()
			{
				var division = $(this).parent().parent().parent();

				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/processDeleteFromCartRequest.php?r=" + new Date().getTime(),
					data: {productid: $(this).attr("productid"), supplier: $(this).attr("supplier")}
				});

				$request.done(function()
				{
					//remove from cart
					$(division).remove();

					//display message when product is successfully added
					$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Verwijderd</strong> Het product is succesvol uit uw winkelwagen verwijderd</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.fail(function()
				{
					//display message when product could not be added
					$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> Het product kon niet uit de winkelwagen worden verwijderd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.done(function()
				{
					//refresh all tables
					$("button[name='refresh']").trigger("click");
				});
			});

			//when a product button is clicked, product id, supplier and amount are sent to the shopping cart
			$(".amountproduct").change(function()
			{
				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/processUpdateProductAmountRequest.php?r=" + new Date().getTime(),
					data: {productid: $(this).data("productid"), supplier: $(this).data("supplier"), amount: $(this).val()}
				});

				$request.done(function()
				{
					//display message when product is successfully added
					$('<div class="navbar-fixed-bottom alert alert-success"> <strong>Aangepast!</strong>De hoeveelheid van het product is succesvol gewijzigd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});

				$request.fail(function()
				{
					//display message when product could not be added
					$('<div class="navbar-fixed-bottom alert alert-danger"> <strong>Fout!</strong> De hoeveelheid van het product kon niet worden gewijzigd.</div>').insertBefore($("footer")).fadeOut(2000, function()
					{
						$(this).remove();
					});
				});
			});
		},
		url: 'AJAX/processDisplayShoppingCartRequest.php?r=' + new Date().getTime()
	});
});