		<!-- Text to be displayed in footer -->
		<footer class="navbar-fixed-bottom">
			<div class="container">
				<div id="mobile-dissapear" class="footer-block text-left">
					<p>
						<?php
							echo $GLOBALS["settings"]->Store["storeabbrev"]." - ".$GLOBALS["settings"]->Store["storename"];
						?>
					</p>
				</div>
				<div class="footer-block text-right">
					<p>
						&copy; 2016 - Dennis Van Elst
					</p>
				</div>
			</div>
		</footer>
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>
