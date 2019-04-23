<?php
	
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	$sessionMember = '';
	if(isset($_SESSION['Member']))
	{
		$sessionMember=$_SESSION['Member'];
	}

	include "admin/connect.php";
	//Routes 

/*
		* Directory of All Code 
*/
	$template 	 = 'includes/template/';
	$func     	 = 'includes/functions/';
	$css	  	 = 'layout/css/';
	$js 	  	 = 'layout/js/';
	$image 	   	 = 'images/';
	$logo 	  	 = 'login_image';
	$lan_english = 'includes/languages/english.php';
	$lan_arabic  = 'includes/languages/arabic.php';
	

   // include important files 
	include $lan_english;
	include $func. 'functions.php';
	include $template.'header.php';
	// include NavBar on all pages expect one with $noNarBar Variable 

		


	//include $lan_arabic;
?>