<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		  <a class="navbar-brand" href="./index.php">
			<?php
				echo $GLOBALS['settings']->Store['storeabbrev'];
			?>
			</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
			<?php
				//index is always there
				echo '<li ';
					if(strcmp($GLOBALS['page'], "index")==0)
					{
						echo 'class="active">';
					}
					else
					{
						echo '>';
					}
					echo '<a href="./index.php">Shop</a>';
				echo '</li>';
				
				if (isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn"))
				{
					//emsys is only there when logged in
					echo '<li ';
					if(strcmp($GLOBALS['page'], "emsys")==0)
					{
						echo 'class="active">';
					}
					else
					{
						echo '>';
					}
					echo '<a href="./emsys.php">EmSys</a>';
					echo '</li>';

					//winkelmandje is only there when logged in
					echo '<li ';
						if(strcmp($GLOBALS['page'], "winkelmandje")==0)
						{
							echo 'class="active">';
						}
						else
						{
							echo '>';
						}
						echo '<a href="./winkelmandje.php">Winkelmandje</a>';
					echo '</li>';
					
					//bestellingen is only there when logged in
					echo '<li ';
						if(strcmp($GLOBALS['page'], "bestellingen")==0)
						{
							echo 'class="active">';
						}
						else
						{
							echo '>';
						}
						echo '<a href="./bestellingen.php">Bestellingen</a>';
					echo '</li>';
					
					//winkelmandje is only there when logged in as admin (docent)
					if($_SESSION["user"]->__get("permissionLevel") == 2)
					{
						echo '<li ';
							if(strcmp($GLOBALS['page'], "adminpanel")==0)
							{
								echo 'class="active">';
							}
							else
							{
								echo '>';
							}
							echo '<a href="./adminpanel.php">Adminpanel</a>';
						echo '</li>';
					}
				}
			?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					//different links based on user level
					if (isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn"))
					{
						//profiel is only there when logged in
						echo '<li ';
							if(strcmp($GLOBALS['page'], "profiel")==0)
							{
								echo 'class="active">';
							}
							else
							{
								echo '>';
							}
							echo '<a href="./profiel.php"><span class="glyphicon glyphicon-user"></span> '.$_SESSION["user"]->__get("firstName").'</a>';
						echo '</li>';
						
						//logout is only there when logged in
						echo '<li ';
							if(strcmp($GLOBALS['page'], "logout")==0)
							{
								echo 'class="active">';
							}
							else
							{
								echo '>';
							}
							echo '<a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a>';
						echo '</li>';
					}
					else
					{
						//registreren is only there when not logged in
						echo '<li ';
						if(strcmp($GLOBALS['page'], "registreren")==0)
						{
							echo 'class="active">';
						}
						else
						{
							echo '>';
						}
						echo '<a href="./registreren.php">Registreren</a>';
						
						//login is only there when not logged in
						echo '
							<li class="dropdown">
							  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
								<ul id="login-dp" class="dropdown-menu">
									<li>
										<div class="row">
											<div class="col-sm-12">
												<form method="post" action="';
												echo htmlspecialchars($_SERVER['PHP_SELF']);
												echo '" id="login-nav">
													<div class="form-group">
														<label class="sr-only" for="rnr">r-nummer</label>
														<input type="text" class="form-control" id="rnr" name="rnr" placeholder="r0123456" required>
													</div>
													<div class="form-group">
														<label class="sr-only" for="pwd">Wachtwoord</label>
														<input type="password" class="form-control" id="pwd" name="pwd" placeholder="Wachtwoord" required>
													</div>
													<div class="form-group">
														<p>
															<button type="submit" class="btn btn-primary btn-block">Aanmelden</button>
														</p>
													</div>
													<!--<div class="checkbox">
														<p class="text-center">
															<input type="checkbox"> aangemeld blijven</input>
														</p>
													</div>-->
												</form>
											</div>
											<div class="bottom text-center">
												<p>
													<a href="./wachtwoordvergeten.php">
														<b>
															Wachtwoord vergeten?
														</b>
													</a>
												</p>
												<p>
													<a href="./registreren.php">
														<b>
															Registreren
														</b>
													</a>
												</p>
											</div>
										</div>
									</li>
								</ul>
							</li>';
					}
				?>
			</ul>
		</div><!--/.nav-collapse -->
      </div>
    </nav>