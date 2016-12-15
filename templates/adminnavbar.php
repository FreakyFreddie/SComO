
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
					<ul class="children collapse in" aria-expanded="true" id="sub-item-1">
						<li>
							<a class="" href="adminprojectennieuw.php">
								<svg class="glyph stroked plus sign">
									<use xlink:href="#stroked-plus-sign"/>
								</svg>
								 Nieuw project
							</a>
						</li>
						<li>
							<a class="" href="adminprojectentoewijzen.php">
								<svg class="glyph stroked clipboard with paper">
									<use xlink:href="#stroked-clipboard-with-paper"/>
								</svg>
								 Project toewijzen
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked pencil">
									<use xlink:href="#stroked-pencil"/>
								</svg>
								 Project wijzigen
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked trash">
									<use xlink:href="#stroked-trash"/>
								</svg>
								 Project verwijderen
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
					<ul class="children collapse in" aria-expanded="true" id="sub-item-2">
						<li>
							<a class="" href="#">
								<svg class="glyph stroked checkmark">
									<use xlink:href="#stroked-checkmark"/>
								</svg>
								 Bestellingen keuren
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked download">
									<use xlink:href="#stroked-download"/>
								</svg>
								 BOM downloaden
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked hourglass">
									<use xlink:href="#stroked-hourglass"/>
								</svg>
								 Verwachte leveringen
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
					<ul class="children collapse in" aria-expanded="true" id="sub-item-3">
						<li>
							<a class="" href="#">
								<svg class="glyph stroked plus sign">
									<use xlink:href="#stroked-plus-sign"/>
								</svg>
								 Gebruiker toevoegen
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked pencil">
									<use xlink:href="#stroked-pencil"/>
								</svg>
								 Gebruiker wijzigen
							</a>
						</li>
						<li>
							<a class="" href="#">
								<svg class="glyph stroked trash">
									<use xlink:href="#stroked-trash"/>
								</svg>
								 Gebruiker verwijderen
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
