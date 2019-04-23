<?php

	ob_start();
	$pageTitle ='Category';
	session_start();
	if(isset($_SESSION['Username']))
	{
		include "init.php"; 
		
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;
		/*
			*  Split Page to Pieces  
			*  Chosen Page Will Work	  
		*/

		if($do == 'Manage')
		{
			// ordering my Categories
			$sort = 'ASC';
			$sort_array = array('ASC','DESC');
			if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array))
			{
				$sort = $_GET['sort'];
			}

			/*Perpare Data into Query get all Users From DataBase expect Admin Previliege	*/
			 	$stmt = $con->prepare("Select * from categories ORDER BY Ordering $sort");
			/*Execute the Query  */
				$stmt->execute();
			/*Get All Rows In DataBase Except Admins */
				$rows=$stmt->fetchAll();
			?>
				<h1 class="text-center">Manage Page </h1>
				<div class="container categories">
					<div class="panel panel-default">
						<div class="panel-heading"> <i class='fa fa-edit'></i>Manage Categories 
							<div class="ordering pull-right">
								Ordering : 
								<a class="<?php if($sort == 'ASC') {echo 'active';}?>" href="?sort=ASC">ASC | </a>
								<a class="<?php if($sort == 'DESC') {echo 'active';}?>" href="?sort=DESC">DESC</a>
							</div>
						</div>
						<div class="panel-body">
							<?php
								foreach($rows as $row)
								{
									
									echo '<div class="cat">';
									 echo '<div class="hidden-btn">';
									
										echo '<a href="?do=Edit&ID='.$row['ID'].'" class="btn btn-xs btn-primary"> <i class="fa fa-edit"></i>Edit</a>';
											
										echo '<a href="?do=Delete&ID='.$row['ID'].'" class=" confirm btn btn-xs btn-danger">
										<i class="fa fa-times"></i>Delete</a>';
									 echo '</div>';	
									/*  end hidden buttons (Edit / Delete) */
											echo '<h3>'.$row['Name'].'</h3>';
											echo '<div class="full-view">';
												if (empty($row['Description']))
													echo '<p>there\'s no Description for this category</p>';
												else 
													echo '<p>'.$row['Description'].'</p>';
												if($row['Visibility'] == 1)
													echo '<span class="global visibile">
													<i class="fa fa-eye-slash"></i>hidden</span>';
												if($row['Allow_Comment'] == 1)
													echo '<span class="global comment">
													<i class="fa fa-comment-slash"></i>Comment Disable</span>';
												if($row['Allow_Ads'] == 1)
													echo '<span class="global Adver">
													<i class="fa fa-times-circle"></i>Ads Disable</span>';
											echo'</div>';
									echo '</div>';
									echo '<hr>';
								}
							?>
						</div>
					</div>	
					<div class='add-category btn btn-primary'>
						<a href="?do=Add"><i class="i-category fa fa-plus-circle"></i>New Category</a>
					</div>
					
		<?php		
		}
		
		elseif($do == 'Add')
		{ ?>
			<h1 class="text-center">ADD Category Pega </h1>
				<div class="container">
					<form class="form-horizontal" action ="?do=Insert" method='POST'>
											
					<!-- NAME FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Name </label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="name" autocomplete="off" required ="required" placeholder = "Category Name"/>
							</div>
						</div>

						<!-- Description FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="description" placeholder ="Descrition of Categories " />
							</div>
						</div>

						<!-- Ordering FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="ordering" placeholder ="Add numer to order Categories " />
							</div>
						</div>

						<!-- Visibility FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visibile</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input type="radio" id ='vis-yes' name="visibility" 
										   value='0' checked />
									<label for="vis-yes">Yes</label>
								</div>	
								<div>
									<input type="radio" id ='vis-no' name="visibility" 
										   value='1'  />
									<label for="vis-no">No</label>
								</div>	
							</div>
						</div>
						
						<!-- Allow_Comment FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow_Comment</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input type="radio" id ='com-yes' name="allow_comment" 
										   value='0' checked />
									<label for="com-yes">Yes</label>
								</div>	
								<div>
									<input type="radio" id ='com-no' name="allow_comment" 
										   value='1'  />
									<label for="com-no">No</label>
								</div>	
							</div>
						</div>
						
						<!-- Allow_Ads FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow_Ads</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input type="radio" id ='ads-yes' name="allow_ads" 
										   value='0' checked />
									<label for="ads-yes">Yes</label>
								</div>	
								<div>
									<input type="radio" id ='ads-no' name="allow_ads" 
										   value='1'  />
									<label for="ads-no">No</label>
								</div>	
							</div>
						</div>
						
						
						<!-- SUBMIT FIELD  -->	
							<div class="col-sm-offset-2 col-sm-10 ">
								<input type="submit" class="btn btn-info btn-lg" value="Add Category" />
							</div>
						
						
					</form>
			</div>
			
			<?php
		}
		
		elseif($do == 'Insert')
		{
			?>
			<h1 class="text-center">Insert Pega </h1>
		<?php	
			
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
			
					$name		= $_POST['name'];
					$desc 		= $_POST['description'];
					$ordering	= $_POST['ordering'];
					$visibility = $_POST['visibility'];
					$comment 	= $_POST['allow_comment'];
					$ads  		= $_POST['allow_ads'];
				
					/* Start The Container Deviation */
						echo "<div class='container'>";
							
							/* Check if the User Name is exit in database or not */
							
							$valid = checkItem("Name","categories",$name);
							echo $valid;
							if(!($valid))
							{
								/*   Prepare Data from Database Base On User_ID  */
									$stmt = $con->prepare("Insert INTO categories (Name,Description,Ordering, Visibility,Allow_Comment,Allow_Ads)
									VALUES(:zname,:zdesc,:zordering,:zvisibility,:zcomment,:zads)");


								/* Execute the Query */
									$stmt->execute(array(
									'zname' 		=>  $name,
									'zdesc' 		=>	$desc,
									'zordering' 	=>	$ordering,
									'zvisibility' 	=>	$visibility,
									'zcomment' 		=>	$comment,
									'zads' 			=>	$ads,
									));

								/* Check there's Data in Database or Not */
									$valid = $stmt->rowCount();

								/* IF there Data Then GO in Form */
									if($valid)
									{
										redirectHome("the Update is <strong>Done on " . $valid ." row</strong>" , 'back' , 3 ,'success');
									}
									else
									{
										redirectHome("the Update is <strong>Done on " . $valid ." row</strong>" , 'back' , 3 ,'info');
									}
								}
							else 
							{
								redirectHome($name." Category is already exist in the Database", 'back' , 3 ,'info');
							}
						
						echo "</div>";	
				
			}
			else 
			{
				redirectHome(' you can\'t access Insert pega directly',null, 3 , 'danger');
			}
		}
		
		elseif($do == 'Edit')
		{
			 /*  Check CategoryID Is Correct (validation)  */

			$catID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0 ; 
				
			  /*   Prepare Data from Database Base On User_ID  */
			$stmt = $con->prepare("select * from categories where ID = ? Limit 1");
			
			  /* Execute the Query */
			$stmt->execute(array($catID));
			
			/* bring the data from Data Base */
			$row = $stmt->fetch();
			/* Check there's Data in Database or Not */
			$valid = $stmt->rowCount();
			/* IF there Data Then GO in Form */
			if($valid)
			{
				?>
					<h1 class="text-center">Edit Category Page </h1>
					<div class="container">
						<form class="form-horizontal" action ="?do=Update" method='POST'>
						<!-- category ID -->
							<input type="hidden" name='catid' value="<?php echo $catID ?>">
						<!-- NAME FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label"> UserName </label>
								<div class="col-sm-10 col-md-6">
									<input type="text" class="form-control" name="name" autocomplete="off" required ="required" placeholder = "Category Name" value ='<?php echo $row['Name']; ?>' />
								</div>
							</div>

							<!-- Description FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" class="form-control" name="description" placeholder ="Descrition of Categories " value ='<?php echo $row['Description']; ?>' />
								</div>
							</div>

							<!-- Ordering FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Ordering</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" class="form-control" name="ordering" placeholder ="Add numer to order Categories " value ='<?php echo $row['Ordering']; ?>' />
								</div>
							</div>

							<!-- Visibility FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Visibile</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input type="radio" id ='vis-yes' name="visibility" 
											   value='0' 
											   <?php if($row['Visibility'] == 0) echo 'checked'; ?> />
										<label for="vis-yes">Yes</label>
									</div>	
									<div>
										<input type="radio" id ='vis-no' name="visibility" 
											   value='1' <?php if($row['Visibility'] == 1) echo 'checked'; ?> />
										<label for="vis-no">No</label>
									</div>	
								</div>
							</div>

							<!-- Allow_Comment FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Allow_Comment</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input type="radio" id ='com-yes' name="allow_comment" 
											   value='0'  <?php if($row['Allow_Comment'] == 0) echo 'checked'; ?> />
										<label for="com-yes">Yes</label>
									</div>	
									<div>
										<input type="radio" id ='com-no' name="allow_comment" 
											   value='1' <?php if($row['Allow_Comment'] == 1) echo 'checked'; ?> />
										<label for="com-no">No</label>
									</div>	
								</div>
							</div>

							<!-- Allow_Ads FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Allow_Ads</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input type="radio" id ='ads-yes' name="allow_ads" 
											   value='0' <?php if($row['Allow_Ads'] == 0) echo 'checked'; ?> />
										<label for="ads-yes">Yes</label>
									</div>	
									<div>
										<input type="radio" id ='ads-no' name="allow_ads" 
											   value='1'  <?php if($row['Allow_Ads'] == 1) echo 'checked'; ?> />
										<label for="ads-no">No</label>
									</div>	
								</div>
							</div>


							<!-- SUBMIT FIELD  -->	
								<div class="col-sm-offset-2 col-sm-10 ">
									<input type="submit" class="btn btn-info btn-lg" value="Update Member" />
								</div>


						</form>
				</div>
				
				<?php 
			}
			
			/* if there's no ID then Show This Message */
			else 
			{
				echo msgType("There isn't ID Such like that",'danger');
			}
				
			
		}
		
		elseif($do == 'Update')
		{
			 ?>
			<h1 class="text-center">Update Pega </h1>
		<?php	
			
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
					
					$ID 			= $_POST['catid'];
					$name			= $_POST['name'];
					$description 	= $_POST['description'];
					$ordering		= $_POST['ordering'];
					$visibility 	= $_POST['visibility'];
					$allow_comment 	= $_POST['allow_comment'];
					$allow_ads 		= $_POST['allow_ads'];
						  /*   Prepare Data from Database Base On User_ID  
					$stmt = $con->prepare("Update users SET Username = ? , Email = ? , Fullname= ? , Password = ? where UserID = ?");*/

	
					/* Start The Container Deviation */
						echo "<div class='container'>";
						
						/*   Prepare Data from Database Base On User_ID  */
							$stmt = $con->prepare("Update categories SET
																		Name = ? ,
																		Description = ? ,
																		Ordering= ? , 
																		Visibility = ? ,
																		Allow_Comment = ? ,
																		Allow_Ads = ? 
																		where ID = ?");
							
						/* Execute the Query */
							$stmt->execute
								(array($name,$description,$ordering,$visibility,$allow_comment,$allow_ads,$ID));
							
						/* Check there's Data in Database or Not */
							$valid = $stmt->rowCount();
							
						/* IF there Data Then GO in Form */
							if($valid)
							{
								redirectHome("the Update is <strong>Done on " . $valid ." row</strong>" , 'back' , 3 ,'success');
							}
							else
							{
								redirectHome("the Update is <strong>Done on " . $valid ." row</strong>" , 'back' , 3 ,'info');
							}
							
						echo "</div>";	
			}
			else 
			{
				redirectHome(' you can\'t access Update pega directly' , null , 3 ,'danger');
			}
		}
		
		elseif($do == 'Delete')
		{
			  /*  Check UserID Is Correct (validation)  */
				$catid = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0 ; 

				  /*   Prepare Data from Database Base On User_ID  */
				$stmt = $con->prepare("select * from categories where ID = ? Limit 1");

				  /* Execute the Query */
				$stmt->execute(array($catid));

				/* bring the data from Data Base */
				$row = $stmt->fetch();
				/* Check there's Data in Database or Not */
				$valid = $stmt->rowCount();
				/* IF there Data Then GO in Form */
				if($valid)
				{
					$stmt = $con->prepare("Delete  from categories where ID = :zID");
					$stmt->bindParam(":zID" , $catid);
					$stmt->execute();
					redirectHome("the Delete is <strong>Done on " . $valid ." row</strong>",'back', 3 , 'success');
				}
				else 
				{				
					redirectHome("this <strong>ID</strong> isn't exist ",'back', 3 , 'warning');
				}
		}
		
		else 
		{
			msgType("there's no pega here" ,'danger');
		}
	}
	else 
	{
		header('location:index.php');
		exit();
	}

	ob_end_flush();

?>
	
	
	