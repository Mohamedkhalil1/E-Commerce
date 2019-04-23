<?php


		/*
			Catagories [ Manage | Edit | Update | Insert | Add | Delete] 
		*/


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
			echo 'WebPage iS Manage';
			echo '<a href="?do=Manage">Manage Cat</a>';
		}
		elseif($do == 'Edit')
		{
			echo 'WebPage iS Edit';
			echo '<a hr	ef="?do=Edit">Edit Cat</a>';
		}
		elseif($do == 'Update')
		{
			echo 'WebPage iS Update';
			echo '<a href="?do=Update">Update Cat</a>';
		}
		elseif($do == 'Insert')
		{
			echo 'WebPage iS Insert';
			echo '<a href="?do=Insert">Insert Cat</a>';
		}
		elseif($do == 'Add')
		{
			echo 'WebPage iS Add';
			echo '<a href="?do=Add">Add Cat</a>';
		}
		elseif($do == 'Delete')
		{
			echo 'WebPage iS Delete' ;
			echo '<a href="?do=Delete">Delete Cat</a>';
		}
		else 
		{
			echo 'WebPage iS Not Found';
		}


		