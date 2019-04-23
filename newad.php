<?php	
	ob_start();
	$pageTitle ='New Add Item';
	session_start();
	include "init.php";
	if (isset($_SESSION['Member']))
	{
		$formerrors = array();
		if($_SERVER['REQUEST_METHOD'] =='POST')
		{
			$name    = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
			$disc    = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
			$price   = '$'.filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
			$country = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
			$status  = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
			$catid   = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
			
			if(empty($name))
			{
				echo 'Name is Empty';
			}
			if(strlen($name) < 4 ||  strlen($name) > 20)
			{
				$formerrors [] ='Item name should be 4-20 letters';
			}
			
			if(strlen($disc) < 5)
			{
				$formerrors [] ='Desc should be at least 5 letters';
			}
			
			if(strlen($price) < 2)
			{
				$formerrors [] ='Price can\'t be Empty';
			}
			
			if(strlen($country) < 1)
			{
				$formerrors [] = 'Country-Made should be at least 2 letters';
			}
			
			if(empty($errorMessages))
						{
		
								/*   Prepare Data from Database Base On User_ID  */
									$stmt = $con->prepare("Insert INTO items (Name,Description,Price, Country_Made,Status,Add_Date,Cat_ID,Members_ID)
									VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember)");


								/* Execute the Query */
									$stmt->execute(array(
									'zname' 	 => $name,
									'zdesc' 	 =>	$disc,
									'zprice'	 =>	$price,
									'zcountry'   =>	$country,
									'zstatus'	 => $status,
									'zcat'       => $catid,
									'zmember'	 => $_SESSION['User_ID']
									));

								/* Check there's Data in Database or Not */
									$valid = $stmt->rowCount();

								/* IF there Data Then GO in Form */
									if($valid)
									{
										echo msgType('Items is Added','success');
									}
									else
									{
										echo msgType('Items is not Added','danger');
									}
							}
			
		}
		$memberinfo = getMemberInfo($sessionMember);
	 ?>
	<!-- START DIVATION My Basic Information -->
	<div class='New-Ads block'>
		<h1 class='text-center'><?php echo $pageTitle ?> </h1>
		<div class = "container">
			<div class='item-panel panel panel-primary'>
				<div class='panel-heading'>
					<i class='fa fa-tag'></i>
					<?php echo $pageTitle ?>
				</div>
			<div class="panel-body">
				<div class='row'>
					<div class='col-md-8'>
						<form class="form-horizontal main-form" action =<?php echo $_SERVER['PHP_SELF'] ?> method='POST'>

						<!-- NAME FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label"> Name </label>
								<div class="col-sm-10 col-md-9">
									<input pattern= '.{4,20}' title='Item Name should be 4-20 letters' type="text" class="form-control live-name" name="name" autocomplete="off"  placeholder = "Item Name" required />
								</div>
							</div>

							<!-- Description FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-10 col-md-9">
									<input pattern= '.{5,}' title='Descripiton should be at least 5 letters' type="text" class="form-control live-disc" name="description" placeholder ="Descrition of Item " required />
								</div>
							</div>

							<!-- Price FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Price</label>
								<div class="col-sm-10 col-md-9">
									<input pattern= '.{1,}' title='Price should be at least 1 letters' type="text" class="form-control live-price" name="price" placeholder ="Price of Item "  
										   required />
								</div>
							</div>

							<!-- Country FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Country</label>
								<div class="col-sm-10 col-md-9">
									<input pattern= '.{2,}' title='Price should be at least 2 letters' type="text" class="form-control" name="country" placeholder ="Countery Made " 
										   required/>
								</div>
							</div>

							<!-- Status FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-10 col-md-9">
									<select class="form-control" name="status" required	>
										<option value='' selected>....</option>
										<option value='1'>new</option>
										<option value='2'>old</option>
										<option value='3'>very old</option>
										<option value='4'>used</option>
									</select>
								</div>
							</div>


							<!-- Categories FIELD  -->	
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Category</label>
								<div class="col-sm-10 col-md-9">
									<select class="form-control" name="category" required>
										<option value='' selected>....</option>
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
								<div class="col-sm-offset-3 col-sm-9">
									<input type="submit" class="add-item btn btn-info btn-lg" value="Add Item" />
								</div>
						</form>
					</div>

					<!-- IMAGE Filed -->
					<div class='col-md-4'>
								<div class="row">
									<div class="col-md-12">
									<div class="thumbnail item-box live-preview">
										<span class="item-price item-tag">Price</span>
											<img  class ="img-respnsive" src="layout\images\login_image.png"'.'alt ="Item's Image" />
										<div class="caption">
											<h3>Title</h3>
											<p>Description</p>
											<span>Date</span>
										</div>
									</div>
								</div>
							 </div>
							</div>
						</div>
				</div>
				
				
				
				<!-- Start Looping Error-->
				<?php
					foreach ($formerrors as $error)
					{
						echo msgType($error);
					}
				?>
				<!-- End Looping Error-->
		   </div>
		</div>
	</div>
	<!-- END DIVATION My Basic Information -->

<?php
	}
	else 
	{
		header('location: login.php');
		exit();
	}
	include $template.'footer.php'; 
	ob_end_flush();
?>
	