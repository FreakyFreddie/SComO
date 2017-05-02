
<?php
	if (isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && $_SESSION["user"]->__get("permissionLevel")==2)
	{
		echo'<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
			<ul class="nav menu">';
				echo '<li class="parent';

				if (strcmp($GLOBALS['adminpage'], "adminpanel") == 0)
				{
					echo ' active">';
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
					</a>';

				if (strcmp($GLOBALS['adminpage'], "adminprojecten") == 0)
				{
					echo '<ul class="children collapse in" aria-expanded="true" id="sub-item-1">';
				}
				else
				{
					echo '<ul class="children collapse" aria-expanded="false" id="sub-item-1">';
				}

				echo '<li>
							<a class="" href="adminprojecten.php#row1">
								<svg class="glyph stroked clipboard with paper">
									<use xlink:href="#stroked-clipboard-with-paper"/>
								</svg>
								 Overzicht
							</a>
						</li>
						<li>
							<a class="" href="adminprojecten.php#row2">
								<svg class="glyph stroked plus sign">
									<use xlink:href="#stroked-plus-sign"/>
								</svg>
								 Nieuw project
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
					</a>';

				if (strcmp($GLOBALS['adminpage'], "adminbestellingen") == 0)
				{
					echo '<ul class="children collapse in" aria-expanded="true" id="sub-item-2">';
				}
				else
				{
					echo '<ul class="children collapse" aria-expanded="false" id="sub-item-2">';
				}

				echo '<li>
							<a class="" href="adminbestellingenkeuren.php">
								<svg class="glyph stroked checkmark">
									<use xlink:href="#stroked-checkmark"/>
								</svg>
								 Bestellingen keuren
							</a>
						</li>
						<li>
							<a class="" href="adminbestellingenBOMdownloaden.php">
								<svg class="glyph stroked download">
									<use xlink:href="#stroked-download"/>
								</svg>
								 BOM downloaden
							</a>
						</li>
						<li>
							<a class="" href="adminbestellingenverwachteleveringen.php">
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
					</a>';

				if (strcmp($GLOBALS['adminpage'], "adminusers") == 0)
				{
					echo '<ul class="children collapse in" aria-expanded="true" id="sub-item-3">';
				}
				else
				{
					echo '<ul class="children collapse" aria-expanded="false" id="sub-item-3">';
				}

				echo '<li>
							<a class="" href="adminusers.php#row1">
								<svg class="glyph stroked clipboard with paper">
									<use xlink:href="#stroked-clipboard-with-paper"/>
								</svg>
								 Overzicht
							</a>
						</li>
						<li>
							<a class="" href="adminusers.php#row2">
								<svg class="glyph stroked plus sign">
									<use xlink:href="#stroked-plus-sign"/>
								</svg>
								 Gebruiker toevoegen
							</a>
						</li>
					</ul>';

				echo '</li>';
			echo '<li class="parent';

			if (strcmp($GLOBALS['adminpage'], "adminproducten") == 0)
			{
				echo ' active">';
			}
			else
			{
				echo '">';
			}

			echo '<a href="adminproducten.php">
							<span data-toggle="collapse" href="#sub-item-4">
								<svg class="glyph stroked bag">
									<use xlink:href="#stroked-bag"></use>
								</svg>
							</span>
							 Producten
						</a>';

			if (strcmp($GLOBALS['adminpage'], "adminproducten") == 0)
			{
				echo '<ul class="children collapse in" aria-expanded="true" id="sub-item-4">';
			}
			else
			{
				echo '<ul class="children collapse" aria-expanded="false" id="sub-item-4">';
			}

			echo '<li>
								<a class="" href="adminproducten.php#row1">
									<svg class="glyph stroked clipboard with paper">
										<use xlink:href="#stroked-clipboard-with-paper"/>
									</svg>
									 Overzicht
								</a>
							</li>
							<li>
								<a class="" href="adminproducten.php#row2">
									<svg class="glyph stroked plus sign">
										<use xlink:href="#stroked-plus-sign"/>
									</svg>
									 Product toevoegen
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
