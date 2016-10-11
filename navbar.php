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
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <?php
				echo "<li ";
					if($_GET['page']=="index")
					{
						echo "class=\"active\">";
						echo "<a href=\"#\">Home</a>";
					}
					else
					{
						echo ">";
						echo "<a href=\"index.php\">Home</a>";
					}
				echo "</li>";
				echo "<li ";
					if($_GET['page']=="about")
					{
						echo "class=\"active\">";
						echo "<a href=\"#\">about</a>";
					}
					else
					{
						echo ">";
						echo "<a href=\"index.php\">Home</a>";
					}
				echo "</li>";
				echo "<li ";
					if($_GET['page']=="winkelmandje")
					{
						echo "class=\"active\">";
						echo "<a href=\"#\">winkelmandje</a>";
					}
					else
					{
						echo ">";
						echo "<a href=\"index.php\">Home</a>";
					}
				echo "</li>";
			?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar-static-top/">Winkelmandje</a></li>
            <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
				<ul id="login-dp" class="dropdown-menu">
					<li>
						<div class="row">
							<div class="col-sm-12">
								<form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
									<div class="form-group">
										<label class="sr-only" for="exampleInputEmail2">r-nummer</label>
										<input type="email" class="form-control" id="exampleInputEmail2" placeholder="r-nummer" required>
									</div>
									<div class="form-group">
										<label class="sr-only" for="exampleInputPassword2">Wachtwoord</label>
										<input type="password" class="form-control" id="exampleInputPassword2" placeholder="Wachtwoord" required>
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
									<a href="#">
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
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>