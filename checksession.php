<?php

require_once '../User/user.php';

session_start();

if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
	$user_roles = $user->getRoles();
	$username = $user->username;
	
	$found=0;
	foreach ($user_roles as $urole){
		foreach ($page_roles as $prole){
			if($urole==$prole){
				$found=1;
			}
		}
	}
	
	if(!$found){
		header("Location: ../User/unauthorized.php");
		exit();
	}	

}else{
	header("Location: ../User/login.php");
}



?>
