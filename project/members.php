<?php
	ob_start();
	$pageTitle ='Members';
	session_start();
	if(isset($_SESSION['Username']))
	{
		include "init.php"; 
	}
	else 
	{
		header('location:index.php');
		exit();
	}


	/*
		* get What Page is chosen By User through Request ! 
	*/

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;
		/*
			*  Split Page to Pieces  
			*  Chosen Page Will Work	  
		*/


		if($do == 'Manage')
		{
			
			$pending_query = '';
			if(isset($_GET['page']) && $_GET['page'] == 'pending')
			{
				$pending_query = 'AND RegStatus = 0';
			}
			/*Perpare Data into Query get all Users From DataBase expect Admin Previliege	*/
			 	$stmt = $con->prepare("Select * from users Where GroupID!=1 $pending_query");
			/*Execute the Query  */
				$stmt->execute();
			/*Get All Rows In DataBase Except Admins */
				$rows=$stmt->fetchAll();
			?>
				<h1 class="text-center">Manage Pega </h1>
				<div class="container">
					<div class="table-responsive ">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>Username</td>
								<td>Email</td>
								<td>Full Name</td>
								<td>Resisted Data</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row)
								{
									echo '<tr>';
										echo '<td>'.$row['UserID'].'</td>';	
										echo '<td>'.$row['Username'].'</td>';	
										echo '<td>'.$row['Email'].'</td>';	
										echo '<td>'.$row['Fullname'].'</td>';
										echo '<td>'.$row['RegDate'].'</td>';
										echo '<td>
											<a class="btn btn-success" href="members.php?do=Edit&UserID='.$row['UserID'].'"><i class="fa fa-edit"></i>Edit</a>
											
											<a class="btn btn-danger confirm" href="members.php?do=Delete&UserID='.$row['UserID'].'"><i class="fa fa-times"></i>Delete</a>' ;
											if($row['RegStatus'] === '0')
											{
												echo '<a class="btn btn-info active" href="members.php?do=Activate&UserID='.$row['UserID'].'"><i class="fa fa-bell-slash"></i>Activate</a>';
											}
									echo '</td>';
									echo '</tr>';
								}	
							?>
						</table>
						<a class="btn btn-primary" href="members.php?do=Add"> <i class="fas fa-user-plus"></i> New Member</a>
					</div>
					
				</div>	
				
				<?php
		}
		elseif($do == 'Edit')
		{ 
			  /*  Check UserID Is Correct (validation)  */
			$userID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0 ; 
				
			  /*   Prepare Data from Database Base On User_ID  */
			$stmt = $con->prepare("select * from users where UserID = ? Limit 1");
			
			  /* Execute the Query */
			$stmt->execute(array($userID));
			
			/* bring the data from Data Base */
			$row = $stmt->fetch();
			/* Check there's Data in Database or Not */
			$valid = $stmt->rowCount();
			/* IF there Data Then GO in Form */
			if($valid)
			{
				?>
				<h1 class="text-center">Edit Pega </h1>
				<div class="container">
					<form class="form-horizontal" action ="?do=Update" method='POST'>
					
					<!-- USERID FIELD  -->
						<input type="hidden" name='userid' value="<?php echo $userID ?>">
						
					<!-- USERNAME FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="username"> UserName </label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" value="<?php echo $row['Username']?>"
									   name="username" autocomplete="off" required ="required"/>
							</div>
						</div>

						<!-- PASSWORD FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="newpassword">Password</label>
							<input type="hidden" name='oldpassword' value="<?php echo $row['Password'] ?>">
							<div class="col-sm-10 col-md-6">
								<input type="password" class="form-control" name="newpassword" autocomplete="new-password" placeholder="let it empty if you don't want to change password" />
							</div>
						</div>

						<!-- EMAIL FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="username">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" class="form-control" value="<?php echo $row['Email']?>"
									   name="email" required ="required" />
							</div>
						</div>

						<!-- FULL NAME FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="fullname">Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" value="<?php echo $row['Fullname']?>"
									   name="fullname" required ="required" />
							</div>
						</div>
						
						<!-- SUBMIT FIELD  -->	
							<div class="col-sm-offset-2 col-sm-10 ">
								<input type="submit" class="btn btn-primary btn-lg" value="Save Changes" />
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
		{ ?>
			<h1 class="text-center">Update Pega </h1>
		<?php	
			
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
			
					$userid 	= $_POST['userid'];
					$username	= $_POST['username'];
					$email 		= $_POST['email'];
					$fullname	= $_POST['fullname'];
					$password 	= $_POST['newpassword'];
				
						  /*   Prepare Data from Database Base On User_ID  
					$stmt = $con->prepare("Update users SET Username = ? , Email = ? , Fullname= ? , Password = ? where UserID = ?");*/

					/* Check if user Change Password to new Password or Stay the Same if it's empty */ 
					$password  = empty($password) ? $_POST['oldpassword'] : sha1($password);


					/* Before Sent Data to Database  
					   Make Sure about data Validation */

						$errorMessages = array();

					/* Start The Container Deviation */
						echo "<div class='container'>";
						
						if(empty($username))
						{
							$errorMessages[]='<UserName Can\'t be <strong>Empty</strong>';
						}
					/* Validate For UserName */
						elseif(strlen($username) < 4 || strlen($username) > 15)
						{
							$errorMessages[]='UserName should be <strong>4-10 letters</strong>';
						}

					/* Validate For Email */	
						if(empty($email))
						{
							$errorMessages[]='<Email Can\'t be <strong>Empty</strong>';
						}

					/* Validate For Full Name  */	
						if(empty($fullname))
						{
							$errorMessages[]='Full Name Can\'t be<strong>Empty</strong>';
						}

					/* Display All Error Messages */	
						foreach($errorMessages as $error)
						{
							echo msgType($error,"danger");
						}
					/* End the Container Divation */	
										
					

						if(empty($errorMessages))
						{
							
							
							$statement = $con->prepare("Select Username from users Where
							Username = ? and UserID != ? ");
							$statement->execute(array($username,$userid));
							$valid = $statement->rowCount();
							if($valid)
							{
								redirectHome("Sorry the User is already Exist" , 'back' , 3 ,'danger');
							}
							else 
							{
								/*   Prepare Data from Database Base On User_ID  */
									$stmt = $con->prepare("Update users SET Username = ? , Email = ? , Fullname= ? , Password = ? where UserID = ?");

								/* Execute the Query */
									$stmt->execute(array($username,$email,$fullname,$password,$userid));

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
								echo "</div>";
							  }
			}
			else 
			{
				redirectHome(' you can\'t access Update pega directly' , null , 3 ,'danger');
			}
			
		}

		elseif($do == 'Add')
		{
			?>
				<h1 class="text-center">ADD User Pega </h1>
				<div class="container">
					<form class="form-horizontal" action ="?do=Insert" method='POST'>
											
					<!-- USERNAME FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="username"> UserName </label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="username" autocomplete="off" required ="required" placeholder = "Username & ID to Enter"/>
							</div>
						</div>

						<!-- PASSWORD FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="password">Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="password" class="password form-control" name="password" autocomplete="new-password" placeholder="make password complex" required ="required" />
							</div>
							<i class="show-pass far fa-eye"></i>
						</div>

						<!-- EMAIL FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="email">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" class="form-control" name="email" required ="required" 
									   placeholder ="enter Valid Email " />
							</div>
						</div>

						<!-- FULL NAME FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label" for="fullname">Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control"  name="fullname" required ="required"
									   placeholder = "enter your full name to appear in your profile" />
							</div>
						</div>
						
						<!-- SUBMIT FIELD  -->	
							<div class="col-sm-offset-2 col-sm-10 ">
								<input type="submit" class="btn btn-success btn-lg" value="Add Member" />
							</div>
						
						
					</form>
			</div>	
				
				<?php 
		}

		elseif($do == 'Insert')
		{ ?>
			<h1 class="text-center">Insert Pega </h1>
		<?php	
			
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
			
					$username	= $_POST['username'];
					$email 		= $_POST['email'];
					$fullname	= $_POST['fullname'];
					$password 	= $_POST['password'];
					$hashpass 	= sha1($password);
				
						  /*   Prepare Data from Database Base On User_ID  */
					/*$stmt = $con->prepare("Update users SET Username = ? , Email = ? , Fullname= ? , Password = ? where UserID = ?");*/

					/* Before Sent Data to Database  
					   Make Sure about data Validation */

						$errorMessages = array();

					/* Start The Container Deviation */
						echo "<div class='container'>";

						/* Validate For UserName */
						if(empty($username))
						{
							$errorMessages[]='UserName Can\'t be <strong>Empty</strong>';
						}
						elseif(strlen($username) < 4 || strlen($username) > 15)
						{
							$errorMessages[]='UserName should be <strong>4-10 letters</strong>';
						}


					/* Validate For Email */	
						if(empty($email))
						{
							$errorMessages[]='<Email Can\'t be <strong>Empty</strong>';
						}

					/* Validate For Full Name  */	
						if(empty($fullname))
						{
							$errorMessages[]='Full Name Can\'t be <strong>Empty</strong>';
						}

					/* Display All Error Messages */	
						foreach($errorMessages as $error)
						{
							echo msgType($error,"danger");
						}
					/* End the Container Divation */	
										
					

						if(empty($errorMessages))
						{
							
							/* Check if the User Name is exit in database or not */
							
							$valid = checkItem("Username","users",$username);
							if(!($valid))
							{
								/*   Prepare Data from Database Base On User_ID  */
									$stmt = $con->prepare("Insert INTO users (Username, Email,Fullname, Password,RegDate,RegStatus)
									VALUES(:zuser,:zemail,:zfullname,:zpassword,now(),1)");


								/* Execute the Query */
									$stmt->execute(array(
									'zuser' 	=>  $username,
									'zemail' 	=>	$email,
									'zfullname' =>	$fullname,
									'zpassword' =>	$hashpass
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
								redirectHome($username." is already exist" . $valid ." row</strong>" , 'back' , 3 ,'info');
							}
						}
						else 
						{
							redirectHome('Error in Inserted Information', 'back' , 3 ,'danger');
						}
						echo "</div>";	
				
			}
			else 
			{
				redirectHome(' you can\'t access Insert pega directly',null, 3 , 'danger');
			}
		}
		
		elseif($do == 'Delete')
		{
			
				  /*  Check UserID Is Correct (validation)  */
				$userID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0 ; 

				  /*   Prepare Data from Database Base On User_ID  */
				$stmt = $con->prepare("select * from users where UserID = ? Limit 1");

				  /* Execute the Query */
				$stmt->execute(array($userID));

				/* bring the data from Data Base */
				$row = $stmt->fetch();
				/* Check there's Data in Database or Not */
				$valid = $stmt->rowCount();
				/* IF there Data Then GO in Form */
				if($valid)
				{
					$stmt = $con->prepare("Delete  from users where UserID = :zuserID");
					$stmt->bindParam("zuserID" , $userID);
					$stmt->execute();
					redirectHome("the Delete is <strong>Done on " . $valid ." row</strong>",'back', 3 , 'success');
				}
				else 
				{				
					redirectHome("this <strong>ID</strong> isn't exist ",'back', 3 , 'warning');
				}
		}
		elseif($do == 'Activate')
		{
			  /*  Check UserID Is Correct (validation)  */
				$userID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0 ; 

				  /*   Prepare Data from Database Base On User_ID  */
				$stmt = $con->prepare("select Username from users where UserID = ? Limit 1");

				  /* Execute the Query */
				$stmt->execute(array($userID));

				/* bring the data from Data Base */
				$row = $stmt->fetch();
				/* Check there's Data in Database or Not */
				$valid = $stmt->rowCount();
				/* IF there Data Then GO in Form */
				if($valid)
				{
					$stmt = $con->prepare("Update users SET RegStatus = 1 where UserID = :zuserID");
					$stmt->bindParam("zuserID" , $userID);
					$stmt->execute();
					redirectHome("the Update is <strong>Done on " . $valid ." row</strong>",'back', 3 , 'success');
				}
				else 
				{				
					redirectHome("this <strong>ID</strong> isn't exist ",'back', 3 , 'warning');
				}
		}

		else 
		{
			redirectHome('Web Page isn\'t Found',null,6,'info');
		}

	ob_end_flush();
