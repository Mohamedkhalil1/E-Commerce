<?php
	
	session_start();
	
	session_unset(); // Reset Data Only 
	
	session_destroy(); // Destroy whole session there's no session anymore 

	header('location: index.php');

	exit();