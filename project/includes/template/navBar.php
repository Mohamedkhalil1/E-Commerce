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

				  <a class="navbar-brand" href="#"><div class="logo"><span>KH</span>ALIL	</div></a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="links_group">
				  <ul class="nav navbar-nav navbar-right">
					  <li><a href="dashboard.php"><?php echo lang("Home"); ?> </a></li>
					  <li><a href="category.php"><?php echo lang("Category");?></a></li>
					  <li><a href="items.php"><?php echo lang("Items");?></a></li>
					  <li><a href="members.php"><?php echo lang("Members");?></a></li>
					  <li><a href="comments.php"><?php echo lang("Comments");?></a></li>
					  <li><a href="#"><?php echo lang("Statistics");?></a></li>
					  <li><a href="#"><?php echo lang("Logs");?></a></li>

					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mohamed Khalil<span class="caret"></span></a>

					  <ul class="dropdown-menu">
						  
						<li><a href="members.php?do=Edit&UserID=<?php echo $_SESSION['UserID']; ?>"><?php echo lang("Edit Profile");?></a></li>
						  
						<li><a href="../index.php"><?php echo lang("Visit Shop");?></a></li>  
			
						<li><a href="#"><?php echo lang("Settings");?></a></li>
						  
						<li role="separator" class="divider"></li>
						  
						<li><a href="logout.php"><?php echo lang("Logout");?></a></li>
						  
					  </ul>

					</li>  
				  </ul>

				</div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
	 </nav>