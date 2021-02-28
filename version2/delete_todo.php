<?php
require_once("db_utils.php");

if(isset($_POST["id"])){
	$id = $_POST["id"];
	$db = new Database();
	if(!$db->deleteTask($id)){
		echo "Doslo je do greske pri brisanju u bazi!";
	}
}