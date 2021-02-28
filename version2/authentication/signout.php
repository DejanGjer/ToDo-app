<?php
	session_start();

	if(isset($_SESSION["user_id"])){
		setcookie("PHPSESSID","",time()-1000,"/");
		session_destroy();
	} else {
		echo "You are already signed out!";
	}
	header("Location: /index.php");
?>