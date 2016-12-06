
<?php
	if (isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && $_SESSION["user"]->__get("permissionLevel")==2)
	{
		echo'<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
			<ul class="nav menu">';
				echo '<li class="';

				if (strcmp($GLOBALS['adminpage'], "adminpanel") == 0)
				{
					echo 'active">';
				}
				else
				{
					echo '">';
				}

				echo '<a href="adminpanel.php">
						<svg class="glyph stroked dashboard-dial">
							<use xlink:href="#stroked-dashboard-dial"></use>
						</svg> Dashboard
					</a>';

				echo '</li>';
				echo '<li class="parent';

				if (strcmp($GLOBALS['adminpage'], "adminprojecten") == 0)
				{
					echo ' active">';
				}
				else
				{
					echo '">';
				}

				echo '<a href="adminprojecten.php">
						<span data-toggle="collapse" href="#sub-item-1">
							<svg class="glyph stroked calendar">
								<use xlink:href="#stroked-calendar"></use>
							</svg>
						</span>
						 Projecten
					</a>
					<ul class="children collapse" id="sub-item-1">
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 1
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 2
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 3
							</a>
						</li>
					</ul>';

				echo '</li>';
				echo '<li class="parent';

				if (strcmp($GLOBALS['adminpage'], "adminbestellingen") == 0)
				{
					echo ' active">';
				}
				else
				{
					echo '">';
				}

				echo '<a href="adminbestellingen.php">
						<span data-toggle="collapse" href="#sub-item-2">
							<svg class="glyph stroked table">
								<use xlink:href="#stroked-table"></use>
							</svg>
						</span>
						 Bestellingen
					</a>
					<ul class="children collapse" id="sub-item-2">
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 1
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 2
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 3
							</a>
						</li>
					</ul>';

				echo '</li>';
				echo '<li class="parent';

				if (strcmp($GLOBALS['adminpage'], "adminusers") == 0)
				{
					echo ' active">';
				}
				else
				{
					echo '">';
				}

				echo '<a href="adminusers.php">
						<span data-toggle="collapse" href="#sub-item-3">
							<svg class="glyph stroked male-user">
								<use xlink:href="#stroked-male-user"></use>
							</svg>
						</span>
						 Gebruikers
					</a>
					<ul class="children collapse" id="sub-item-3">
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 1
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 2
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Sub Item 3
							</a>
						</li>
					</ul>';

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
