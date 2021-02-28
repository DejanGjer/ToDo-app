<?php
require_once("db_utils.php");
require_once("classes/ToDo.php");

function cmp($a, $b){
	if($a->getDate() == $b->getDate()){
		return 0;
	}
	return $a->getDate() > $b->getDate();
}

function printTodos($todos, $view, $user_id, $reorder){
	if($todos && count($todos) > 0){
		if($reorder && isset($_COOKIE["order"])){
			$order = json_decode($_COOKIE["order"]);
			foreach ($order as $id) {
				foreach ($todos as $todo) {
					if($todo->getId() == $id){
						echo $todo->getHtml($view, $user_id, $reorder);
						break;
					}
				}
			}
			foreach ($todos as $todo) {
				if(!in_array($todo->getId(), $order)){
					echo $todo->getHtml($view, $user_id, $reorder);
				}
			}
		} else {
			uasort($todos, "cmp");
			//uasort($todos, create_function('$a, $b', 'return $a->getDate() > $b->getDate();'));
			foreach ($todos as $todo) {
				echo $todo->getHtml($view, $user_id, $reorder);
			}
		}
	}
}

$check = (isset($_POST["id"]) && isset($_POST["checked"]));
$quick_add = (isset($_POST["user_id"]) && isset($_POST["quick_text"]));

if($check || $quick_add){
	$db = new Database();
	if($check){
		$id = $_POST["id"];
		$new_completed = $_POST["checked"];
		if(!$db->updateTaskState($id, $new_completed)){
			echo "Doslo je do greske pri menjanju u bazi!";
		} 
	}
	if($quick_add){
		$user_id = $_POST["user_id"];
		$text = $_POST["quick_text"];
		if(!$db->insertQuickTask($user_id, $text)){
			echo "Doslo je do greske pri menjanju u bazi!";
		}
	}
	?>

	<div id="div_uncompleted">
		<?php
		$view = "today";
		if(isset($_POST["view"]) && isset($_POST["user_id"])){
			$view = $_POST["view"];
			if($view == "today"){
				echo "<h2 class=\"view_header\">Today's tasks: </h2>";
				$todos = $db->getTasksForUser($_POST["user_id"], date("Y-m-d"), date("Y-m-d"), 0);
			} else if($view == "week"){
				echo "<h2 class=\"view_header\">This week tasks: </h2>";
				$todos = $db->getTasksForUser($_POST["user_id"], date("Y-m-d", strtotime("+1 day")), date("Y-m-d", strtotime("+7 day")), 0);
			} else if($view == "month"){
				echo "<h2 class=\"view_header\">This month tasks: </h2>";
				$todos = $db->getTasksForUser($_POST["user_id"], date("Y-m-d", strtotime("+1 day")), date("Y-m-d", strtotime("+30 day")), 0); 
			} else if($view == "all"){
				echo "<h2 class=\"view_header\">All tasks: </h2>";
				$todos = $db->getTasksForUser($_POST["user_id"]);
			} else {
				echo "<h2 class=\"view_header\">Today's tasks: </h2>";
				$todos = $db->getTasksForUser($_POST["user_id"], date("Y-m-d"), date("Y-m-d"));
			}
			printTodos($todos, $view, $_POST["user_id"], ($view == "today"));
			?>
		</div>
		<div id="div_completed">
			<?php
				if($view != "all"){
					echo "<h2 class=\"view_header\">Completed today: </h2>";
					$todos_completed = $db->getTasksForUser($_POST["user_id"], null, null, 1);
					printTodos($todos_completed, $view, $_POST["user_id"], false);
				}
			?>
		</div>
		<?php	
	} else {
		echo "Nedostaju informacije za prikaz!";
	}
}
?>