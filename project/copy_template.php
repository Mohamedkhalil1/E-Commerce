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
			echo 'Manage Pega';
		}
		
		elseif($do == 'Add')
		{
			
		}
		
		elseif($do == 'Insert')
		{
			
		}
		
		elseif($do == 'Edit')
		{
			
		}
		
		elseif($do == 'Update')
		{
			
		}
		
		elseif($do == 'Delete')
		{
			
		}
		
		elseif($do == 'Activate')
		{
			
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
	
	
	