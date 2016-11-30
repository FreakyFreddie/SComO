
<?php
	if (isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && $_SESSION["user"]->__get("permissionLevel")==2)
	{
		echo'<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
			<ul class="nav menu">';
				echo '<li ';

				if (strcmp($GLOBALS['adminpage'], "adminpanel") == 0)
				{
					echo 'class="active">';
				}
				else
				{
					echo '>';
				}

				echo '<a href="adminpanel.php">
						<svg class="glyph stroked dashboard-dial">
							<use xlink:href="#stroked-dashboard-dial"></use>
						</svg>
						 Dashboard
					</a>';
				echo '</li>';
				echo '<li ';

				if (strcmp($GLOBALS['adminpage'], "adminprojecten") == 0)
				{
					echo 'class="active">';
				}
				else
				{
					echo '>';
				}

				echo '<a href="adminprojecten.php">
						<svg class="glyph stroked calendar">
							<use xlink:href="#stroked-calendar"></use>
						</svg>
						 Projecten
					</a>';

				echo '</li>';
				echo '<li ';

				if (strcmp($GLOBALS['adminpage'], "adminbestellingen") == 0)
				{
					echo 'class="active">';
				}
				else
				{
					echo '>';
				}

				echo '<a href="adminbestellingen.php">
						<svg class="glyph stroked table">
							<use xlink:href="#stroked-table"></use>
						</svg>
						 Bestellingen
					</a>';

				echo '</li>';
				echo '<li ';

				if (strcmp($GLOBALS['adminpage'], "adminusers") == 0)
				{
					echo 'class="active">';
				}
				else
				{
					echo '>';
				}

				echo '<a href="adminusers.php">
						<svg class="glyph stroked male-user">
							<use xlink:href="#stroked-male-user"></use>
						</svg>
						 Gebruikers
					</a>';

				echo '</li>';
			echo'<li role="presentation" class="divider"></li>
				<li>
					<a href="http://medialoot.com/item/lumino-admin-bootstrap-template/">
						Lumino Dashboard
						<br />
						By MediaLoot
					</a>
				</li>
			</ul>
		</div>';
	}
?>
