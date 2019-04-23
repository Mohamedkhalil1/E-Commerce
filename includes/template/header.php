<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php getTitle();?></title>
		<link rel="stylesheet" href='<?php echo $css ?>bootstrap.min.css'>
		<link rel="stylesheet" href='<?php echo $css ?>frontend.css'>
		<link rel="stylesheet" href='<?php echo $css ?>fontawesome.min.css'>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		
	</head>
	
	<body>
		
		
	
	<div class="upper-bar">
		<div class='container'>
			<?php 	
			if(isset($_SESSION['Member']))
				{?>
					<img class = 'profile-img img-thumbnail img-circle' src="layout\images\login_image.png"'.'alt ="profile Picture" />
					<div class='btn-group my-info'>
						
						<span class='btn btn-default dropdown-toggle' data-toggle="dropdown">
							<?php echo $_SESSION['Member'] ?>
							<span class='caret'></span>
						</span>
						<ul class='dropdown-menu'>
							<li><a href="profile.php"> My-Profile </a></li>
							<li><a href="newad.php"> new item </a></li>
							<li><a href="logout.php"> Logout </a></li>
						</ul>
					</div>
					
					
				<?php	
				  if (getUserStatus($_SESSION['Member']))
				  {
					  // user is not activate 
				  }
				} 
			else 
				{?>
				<a href='login.php'>
					<span class='pull-right'> LOGIN / SIGNUP </span>
				</a>
				<?php } ?>
		</div>
	</div>
	
	<nav class="navbar navbar-inverse">
			  <div class="container">

				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">

				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#links_group" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>

				  <a class="navbar-brand" href="homepage.php"><div class="logo"><span>KH </span>ALIL</div></a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="links_group">
				  <ul class="nav navbar-nav navbar-right">
					 
					  <?php $cats = getCategories(); 
					  		foreach($cats as $cat)
							{
								echo '<li><a href="categories.php?catid='.$cat['ID'].'">'. $cat['Name'] .'</a></li>';
							}
					  ?> 
				  </ul>

				</div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
	 </nav>