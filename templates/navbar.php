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
				echo $_GLOBALS['settings']->Store['storeabbrev'];
			?>
			</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <?php
				//index is always there
				echo '<li ';
					if($_GLOBALS['page']=="index")
					{
						echo 'class="active">';
					}
					else
					{
						echo '>';
					}
					echo '<a href="./index.php">Shop</a>';
				echo '</li>';
				
				if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
				{
					//winkelmandje is only there when logged in
					echo '<li ';
						if($_GLOBALS['page']=="winkelmandje")
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
						if($_GLOBALS['page']=="bestellingen")
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
					echo '<li ';
						if($_GLOBALS['page']=="adminpanel")
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
			?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
			  <?php
					if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
					{
						//profiel is only there when logged in
						echo '<li ';
							if($_GLOBALS['page']=="profiel")
							{
								echo 'class="active">';
							}
							else
							{
								echo '>';
							}
							echo '<a href="./profiel.php">Profiel</a>';
						echo '</li>';
					}
					else
					{
						//registreren is only there when not logged in
					echo '<li ';
						if($_GLOBALS['page']=="registreren")
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
												<form method="post" action="login" id="login-nav">
													<div class="form-group">
														<label class="sr-only" for="rnr">r0123456</label>
														<input type="text" class="form-control" id="rnr" placeholder="r-nummer" required>
													</div>
													<div class="form-group">
														<label class="sr-only" for="pwd">Wachtwoord</label>
														<input type="password" class="form-control" id="pwd" placeholder="Wachtwoord" required>
													</div>
													<div class="form-group">
														<p>
															<button type="submit" class="btn btn-primary btn-block">Aanmelden</button>
														</p>
													</div>
													<div class="checkbox">
														<p class="text-center">
															<input type="checkbox"> aangemeld blijven
														</p>
													</div>
												</form>
											</div>
											<div class="bottom text-center">
												<p>
													<a href="">
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
							</li>
						';
					}
				?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>