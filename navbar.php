<?php ?>
<nav class="navbar navbar-expand-lg bg-dark ">
	<div class="container-fluid">
		<a class="navbar-brand text-info" href="index.php">Les Flims de L'Ours</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link active text-info" aria-current="page" href="list_film.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active text-info" href="add_users_form.php">inscription</a>
				</li> 
				<?php if(!isset($_SESSION['login'])){ //mon session_start() se trouve dans le header.php "include" dans tous les fichiers ou c'est necessaire  ?>
					<li class="nav-item">
							<a class="nav-link active text-info" href="login.php">login</a>
						</li>
						<?php }		
				if(isset($_SESSION['login'])){
					if($_SESSION['login']){
					?>
					<li class="nav-item">
						<a class="nav-link active text-info" href="logout.php">logout</a>
					</li>

				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle text-info" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					Dropdown</a>
					<ul class="dropdown-menu text-info bg-dark" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item text-info" href="#">Action</a></li>
						<li><a class="dropdown-item text-info" href="#">Another action</a></li>
						<li><hr class="dropdown-divider text-info"></li>
						<li><a class="dropdown-item text-info" href="#">Something else here</a></li>
					</ul>
				</li>          
				<li class="nav-item ">
						<?php  
							echo "<p class='nav-link active text-info'>Bienvenu ".$_SESSION['fullname']."</p>";
						?>
				</li>
			</ul>
				<?php }
				} ?>
			<form class="d-flex" id="searchForm">
				<input class="form-control me-2" type="search" id="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-outline-success text-info" type="submit">Search</button>
			</form>
		</div>
	</div>
</nav>
