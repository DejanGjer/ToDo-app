<?php
require_once("db_utils.php");
require_once("classes/ToDo.php");

if(isset($_POST["show_id"]) && isset($_POST["view"])){
	$id = $_POST["show_id"];
	$db = new Database();
	$todo = $db->getTaskById($id);
	if($todo){
		echo $todo->getDetails($_POST["view"]);
	}
}