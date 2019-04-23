<?php

	ob_start();
	$pageTitle ='';
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
			/*Perpare Data into Query get all Users From DataBase expect Admin Previliege	*/
			 	$stmt = $con->prepare("SELECT items.* , categories.Name as Cat_Name , users.Username  from items
				inner join categories on items.Cat_ID = categories.ID
				inner join users on users.UserID =items.Members_ID");
			/*Execute the Query  */
				$stmt->execute();
			/*Get All Rows In DataBase Except Admins */
				$rows=$stmt->fetchAll();
			?>
				<h1 class="text-center">Manage Page</h1>
				<div class="container">
					<div class="table-responsive ">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>Name</td>
								<td>Description</td>
								<td>Price</td>
								<td>Category</td>
								<td>Brand</td>
								<td>Add Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row)
								{
									echo '<tr>';
										echo '<td>'.$row['Item_ID'].'</td>';	
										echo '<td>'.$row['Name'].'</td>';	
										echo '<td>'.$row['Description'].'</td>';	
										echo '<td>'.$row['Price'].'</td>';
										echo '<td>'.$row['Cat_Name'].'</td>';
										echo '<td>'.$row['Username'].'</td>';
										echo '<td>'.$row['Add_Date'].'</td>';
										echo '<td>
											<a class="btn btn-success" href="items.php?do=Edit&items_id='.$row['Item_ID'].'"><i class="fa fa-edit"></i>Edit</a>
											
											<a class="btn btn-danger confirm" href="items.php?do=Delete&items_id='.$row['Item_ID'].'"><i class="fa fa-times"></i>Delete</a>' ;
									
											if($row['Approve'] === '0')
											{
												echo '<a class="btn btn-info active" href="items.php?do=Approve&itemid='.$row['Item_ID'].'"><i class="fa fa-check"></i>Approve</a>';
											}
									
									echo '</td>';
									echo '</tr>';
								}	
							?>
						</table>
						<a class="btn-additem btn btn-primary btn-lg" href="items.php?do=Add"> <i class="fas fa-cart-plus"></i> New Item</a>
					</div>
				</div>	
					
				<?php
		}
		
		elseif($do == 'Add')
		{
			?>
			<h1 class="text-center">ADD Items Pega </h1>
				<div class="container items">
					<form class="form-horizontal" action ="?do=Insert" method='POST'>
											
					<!-- NAME FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Name </label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="name" autocomplete="off" required ="required" placeholder = "Item Name"/>
							</div>
						</div>

						<!-- Description FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="description" placeholder ="Descrition of Item " required />
							</div>
						</div>

						<!-- Price FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="price" placeholder ="Price of Item " required />
							</div>
						</div>
						
						<!-- Country FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="country" placeholder ="Countery Made " required />
							</div>
						</div>
						
						<!-- Status FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="status">
									<option value='0' selected>....</option>
									<option value='1'>new</option>
									<option value='2'>old</option>
									<option value='3'>very old</option>
									<option value='4'>used</option>
								</select>
							</div>
						</div>
						
						<!-- Members FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Members</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="members">
									<option value='0' selected>....</option>
									<?php
			 							$stmt = $con->prepare('SELECT * from users where RegStatus = 1');
										$stmt->execute();
										$users = $stmt->fetchAll();
										foreach($users as $user)
										{
											echo '<option value="'.$user['UserID'].'">'.$user['Username'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						
						
						<!-- Categories FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="category">
									<option value='0' selected>....</option>
									<?php
			 							$stmt = $con->prepare('SELECT * from categories');
										$stmt->execute();
										$cats = $stmt->fetchAll();
										foreach($cats as $cat)
										{
											echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						
						
						<!-- SUBMIT FIELD  -->	
							<div class="col-sm-offset-2 col-sm-10 ">
								<input type="submit" class="add-item btn btn-info btn-lg" value="Add Item" />
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
			
					$name		 = $_POST['name'];
					$description = $_POST['description'];
					$price		 = $_POST['price'];
					$country 	 = $_POST['country'];
					$status 	 = $_POST['status'];
					$members 	 = $_POST['members'];
					$category 	 = $_POST['category'];
				
						  /*   Prepare Data from Database Base On User_ID  */
					/*$stmt = $con->prepare("Update users SET Username = ? , Email = ? , Fullname= ? , Password = ? where UserID = ?");*/

					/* Before Sent Data to Database  
					   Make Sure about data Validation */

						$errorMessages = array();

					/* Start The Container Deviation */
						echo "<div class='container'>";

					/* Validate For item-name */
						if(empty($name))
						{
							$errorMessages[]='Item Name Can\'t be <strong>Empty</strong>';
						}

					/* Validate For Email */	
						if(empty($description))
						{
							$errorMessages[]='Description of item Can\'t be <strong>Empty</strong>';
						}

					/* Validate For Full Name  */	
						if(empty($price))
						{
							$errorMessages[]='Price Can\'t be <strong>Empty</strong>';
						}
					/* Validate For Country-Made  */	
						if(empty($country))
						{
							$errorMessages[]='Country-Made Can\'t be <strong>Empty</strong>';
						}
					/* Validate For Status-Item  */	
						if($status === '0')
						{
							$errorMessages[]='you should choose <strong>Status</strong>';
						}
					
					/* Validate For Status-Item  */	
						if($category === '0')
						{
							$errorMessages[]='you should choose <strong>Category</strong>';
						}
					
					/* Validate For Status-Item  */	
						if($members === '0')
						{
							$errorMessages[]='you should choose <strong>Member</strong>';
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
									$stmt = $con->prepare("Insert INTO items (Name,Description,Price, Country_Made,Status,Add_Date,Cat_ID,Members_ID)
									VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember)");


								/* Execute the Query */
									$stmt->execute(array(
									'zname' 	 => $name,
									'zdesc' 	 =>	$description,
									'zprice'	 =>	$price,
									'zcountry'   =>	$country,
									'zstatus'	 => $status,
									'zcat'       => $category,
									'zmember'	 => $members
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
										redirectHome("the Update is <strong>Done on " . $valid ." row</strong>" , 'back' , 5 ,'info');
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
		
		elseif($do == 'Edit')
		{
			 /*  Check UserID Is Correct (validation)  */
			$item_id = isset($_GET['items_id']) && is_numeric($_GET['items_id']) ? intval($_GET['items_id']) : 0 ; 
				
			  /*   Prepare Data from Database Base On User_ID  */
			$stmt = $con->prepare("select * from items where Item_ID = ? Limit 1");
			
			  /* Execute the Query */
			$stmt->execute(array($item_id));
			
			/* bring the data from Data Base */
			$row = $stmt->fetch();
			/* Check there's Data in Database or Not */
			$valid = $stmt->rowCount();
			/* IF there Data Then GO in Form */
			if($valid)
			{
				?>
					<h1 class="text-center">Edit Items Page </h1>
					<div class="container">
					<form class="form-horizontal" action ="?do=Update" method='POST'>
					
					<!-- USERID FIELD  -->
						<input type="hidden" name='item_id' value="<?php echo $item_id ?>">
						
					<!-- NAME FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Name </label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="name" autocomplete="off" required ="required" placeholder = "Item Name"
									   value = '<?php echo $row['Name'] ?>'/>
							</div>
						</div>

						<!-- Description FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="description" placeholder ="Descrition of Item " required 
									    value = '<?php echo $row['Description'] ?>'/>
							</div>
						</div>

						<!-- Price FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="price" placeholder ="Price of Item " required 
									  value = '<?php echo $row['Price'] ?>'  />
							</div>
						</div>
						
						<!-- Country FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" class="form-control" name="country" placeholder ="Countery Made " required 
									   value ='<?php echo $row['Country_Made'] ?>'/>
							</div>
						</div>
						
						<!-- Status FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="status">
									<option <?php if($row['Status']==1) echo 'selected'; ?> value='1'>new</option>
									<option <?php if($row['Status']==2) echo 'selected'; ?> value='2'>old</option>
									<option <?php if($row['Status']==3) echo 'selected'; ?> value='3'>very old</option>
									<option <?php if($row['Status']==4) echo 'selected'; ?> value='4'>used</option>
								</select>
							</div>
						</div>
						
						<!-- Members/Brands FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Members</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="members">
									<?php
			 							$stmt = $con->prepare('SELECT * from users where RegStatus = 1');
										$stmt->execute();
										$users = $stmt->fetchAll();
										foreach($users as $user)
										{
											if($user['UserID'] == $row['Members_ID'])
											echo '<option selected value="'.$user['UserID'].'">'.$user['Username'].'</option>';
											else 
											{
												echo '<option value="'.$user['UserID'].'">'.$user['Username'].'</option>';
											}
										}
									?>
								</select>
							</div>
						</div>
						
						
						<!-- Categories FIELD  -->	
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="category">
									<?php
			 							$stmt = $con->prepare('SELECT * from categories');
										$stmt->execute();
										$cats = $stmt->fetchAll();
										foreach($cats as $cat)
										{
											if($cat['ID'] == $row['Cat_ID'])
											echo '<option selected value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
											else 
											{
												echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
											}
										}
									?>
								</select>
							</div>
						</div>
						
						
						<!-- SUBMIT FIELD  -->	
							<div class="col-sm-offset-2 col-sm-10 ">
								<input type="submit" class="btn btn-info btn-lg" value="Add Item" />
							</div>
						
						
					</form>
					<?php 
					/*Perpare Data into Query get all Users From DataBase expect Admin Previliege	*/
			 	$stmt = $con->prepare("Select comments.* ,users.Fullname  as Member from comments
										inner join users on comments.User_ID =users.UserID
										where comments.Item_ID = ?");
		
			/*Execute the Query  */
				$stmt->execute(array($row['Item_ID']));
			/*Get All Rows In DataBase Except Admins */
				$rows=$stmt->fetchAll();
			?>
				<br><br><br>		
				<h1 class="text-center">Comment [<?php echo $row['Name']?> ] </h1>
					<div class="table-responsive ">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>Member Name</td>
								<td>Comment</td>
								<td>Comment_Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row)
								{
									echo '<tr>';
										echo '<td>'.$row['Member'].'</td>';	
										echo '<td>'.$row['Comment'].'</td>';	
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
		}
		
		elseif($do == 'Update')
		{
			?>
			<h1 class="text-center">Update Pega </h1>
		<?php	
			
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
			
					$itemid 	= $_POST['item_id'];
					$name		= $_POST['name'];
					$desc 		= $_POST['description'];
					$price		= $_POST['price'];
					$country    = $_POST['country'];
					$status 	= $_POST['status'];
					$member 	= $_POST['members'];
					$category 	= $_POST['category'];
				

						$errorMessages = array();

					/* Start The Container Deviation */
						echo "<div class='container'>";

					/* Validate For item-name */
						if(empty($name))
						{
							$errorMessages[]='Item Name Can\'t be <strong>Empty</strong>';
						}

					/* Validate For Email */	
						if(empty($desc))
						{
							$errorMessages[]='Description of item Can\'t be <strong>Empty</strong>';
						}

					/* Validate For Full Name  */	
						if(empty($price))
						{
							$errorMessages[]='Price Can\'t be <strong>Empty</strong>';
						}
					/* Validate For Country-Made  */	
						if(empty($country))
						{
							$errorMessages[]='Country-Made Can\'t be <strong>Empty</strong>';
						}
					/* Validate For Status-Item  */	
						if($status === '0')
						{
							$errorMessages[]='you should choose <strong>Status</strong>';
						}
					
					/* Validate For Status-Item  */	
						if($category === '0')
						{
							$errorMessages[]='you should choose <strong>Category</strong>';
						}
					
					/* Validate For Status-Item  */	
						if($member === '0')
						{
							$errorMessages[]='you should choose <strong>Member</strong>';
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
							$stmt = $con->prepare("Update items SET Name = ? , Description = ? , Price= ? , Country_Made = ? , Status = ? , Cat_ID = ? , Members_ID = ?  where Item_ID = ?");
							
						/* Execute the Query */
							$stmt->execute(array($name,$desc,$price,$country,$status,$category,$member,$itemid));
							
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
				$itemid = isset($_GET['items_id']) && is_numeric($_GET['items_id']) ? intval($_GET['items_id']) : 0 ; 

				  /*   Prepare Data from Database Base On User_ID  */
				$stmt = $con->prepare("select * from items where Item_ID = ? Limit 1");

				  /* Execute the Query */
				$stmt->execute(array($itemid));

				/* bring the data from Data Base */
				$row = $stmt->fetch();
				/* Check there's Data in Database or Not */
				$valid = $stmt->rowCount();
				/* IF there Data Then GO in Form */
				if($valid)
				{
					$stmt = $con->prepare("Delete  from items where Item_ID = :zItem_ID");
					$stmt->bindParam("zItem_ID" , $itemid);
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
				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ; 

				  /*   Prepare Data from Database Base On User_ID  */
				$stmt = $con->prepare("select Name from items where Item_ID = ? Limit 1");

				  /* Execute the Query */
				$stmt->execute(array($itemid));

				/* bring the data from Data Base */
				$row = $stmt->fetch();
				/* Check there's Data in Database or Not */
				$valid = $stmt->rowCount();
				/* IF there Data Then GO in Form */
				if($valid)
				{
					$stmt = $con->prepare("Update items SET Approve = 1 where Item_ID = :zitemid");
					$stmt->bindParam("zitemid" , $itemid);
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
	
	