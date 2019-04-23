<?php
	ob_start();
	$pageTitle ='Comments';
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
			/*Perpare Data into Query get all Users From DataBase expect Admin Previliege	*/
			 	$stmt = $con->prepare("Select comments.*,items.Name as Name, users.Username  as Member from comments inner join items ON comments.Item_ID = items.Item_ID 
				inner join users on comments.User_ID =users.UserID");
		
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
								<td>Comment</td>
								<td>Item_Name</td>
								<td>Member Name</td>
								<td>Comment_Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row)
								{
									echo '<tr>';
										echo '<td>'.$row['Comment_ID'].'</td>';	
										echo '<td>'.$row['Comment'].'</td>';
										echo '<td>'.$row['Name'].'</td>';	
										echo '<td>'.$row['Member'].'</td>';	
										echo '<td>'.$row['Comment_Date'].'</td>';	
										echo '<td>
											<a class="btn btn-success" href="comments.php?do=Edit&commentid='.$row['Comment_ID'].'"><i class="fa fa-edit"></i>Edit</a>
											
											<a class="btn btn-danger confirm" href="comments.php?do=Delete&commentid='.$row['Comment_ID'].'"><i class="fa fa-times"></i>Delete</a>' ;
											if($row['Status'] === '0')
											{
												echo '<a class="btn btn-info active" href="comments.php?do=Approve&commentid='.$row['Comment_ID'].'"><i class="fa fa-check"></i>Approve</a>';
											}
									echo '</td>';
									echo '</tr>';
								}	
							?>
						</table>
					</div>
					
				</div>	
				
				<?php
		}
		elseif($do == 'Edit')
		{ 
			  /*  Check UserID Is Correct (validation)  */
			$commetid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0 ; 
				
			  /*   Prepare Data from Database Base On User_ID  */
			$stmt = $con->prepare("select * from comments where Comment_ID = ? Limit 1");
			
			  /* Execute the Query */
			$stmt->execute(array($commetid));
			
			/* bring the data from Data Base */
			$row = $stmt->fetch();
			/* Check there's Data in Database or Not */
			$valid = $stmt->rowCount();
			/* IF there Data Then GO in Form */
			if($valid)
			{
				?>
				<h1 class="text-center">Edit Page </h1>
				<div class="container">
					<form class="form-horizontal" action ="?do=Update" method='POST'>
					
					<!-- USERID FIELD  -->
						<input type="hidden" name='commentid' value="<?php echo $commetid ?>">
						
					<!-- Comment FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Comment </label>
							<div class="col-sm-10 col-md-6">
								<textarea name='comment' class="form-control" required><?php echo $row['Comment']; ?> </textarea>
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
					$commentid 	= $_POST['commentid'];
					$comment	= $_POST['comment'];
				
	
					/* Before Sent Data to Database  
					   Make Sure about data Validation */

						$errorMessages = array();

					/* Start The Container Deviation */
						echo "<div class='container'>";

					/* Validate For Full Name  */	
						if(empty($comment))
						{
							$errorMessages[]='Comment Can\'t be<strong>Empty</strong>';
						}

					/* Display All Error Messages */	
						foreach($errorMessages as $error)
						{
							echo msgType($error,"danger");
						}
					/* End the Container Divation */	

						if(empty($errorMessages))
						{
						/*   Prepare Data from Database Base On User_ID  */
							$stmt = $con->prepare("Update comments SET Comment = ? where Comment_ID = ?");
							
						/* Execute the Query */
							$stmt->execute(array($comment,$commentid));
							
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
			else 
			{
				redirectHome(' you can\'t access Update pega directly' , null , 3 ,'danger');
			}
			
		}

		elseif($do == 'Delete')
		{
			
				  /*  Check UserID Is Correct (validation)  */
				$commentid= isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0 ; 

				  /*   Prepare Data from Database Base On User_ID  */
				$stmt = $con->prepare("select * from comments where Comment_ID = ? Limit 1");

				  /* Execute the Query */
				$stmt->execute(array($commentid));

				/* bring the data from Data Base */
				$row = $stmt->fetch();
				/* Check there's Data in Database or Not */
				$valid = $stmt->rowCount();
				/* IF there Data Then GO in Form */
				if($valid)
				{
					$stmt = $con->prepare("Delete  from comments where Comment_ID = :zcommetid");
					$stmt->bindParam("zcommetid" , $commentid);
					$stmt->execute();
					redirectHome("the Delete is <strong>Done on " . $valid ." row</strong>",'back', 3 , 'success');
				}
				else 
				{				
					redirectHome("this <strong>ID</strong> isn't exist ",'back', 3 , 'warning');
				}
		}
		elseif($do == 'Approve')
		{
			  /*  Check UserID Is Correct (validation)  */
				$commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0 ; 

				  /*   Prepare Data from Database Base On User_ID  */
				$stmt = $con->prepare("select * from comments where Comment_ID = ? Limit 1");

				  /* Execute the Query */
				$stmt->execute(array($commentid));

				/* bring the data from Data Base */
				$row = $stmt->fetch();
				/* Check there's Data in Database or Not */
				$valid = $stmt->rowCount();
				/* IF there Data Then GO in Form */
				if($valid)
				{
					$stmt = $con->prepare("Update comments SET Status = 1 where Comment_ID = :zcommentid");
					$stmt->bindParam("zcommentid" , $commentid);
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
