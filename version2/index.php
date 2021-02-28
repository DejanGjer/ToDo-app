<?php
	session_start();

	require_once("classes/ToDo.php");
	require_once("db_utils.php");

	$db = new Database();

?>

<!DOCTYPE html>
<html>
<head>
	<title>TODO app</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/index.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/index.js"></script>
</head>
<body>
	<?php
		if(!isset($_SESSION["user_id"])){
			header("Location: /authentication/login.php");
		}
		else {

			//Dobijamo trenutno logovanog korisnika
			$user = $db->getUserById($_SESSION["user_id"]);
			echo "<nav class=\"sidebar\">
					<div class=\"profile_info\">
						<img class=\"profile_picture\" src=\"images/profile_pictures/".$user->getImage()."\">
						<h2 class=\"profile_greeting\">Hello ".$user->getUsername()." !!!</h2>
						<a class=\"profile_link\" href=\"/profile.php\">Profile</a>
						<a class=\"profile_signout\" href=\"/authentication/signout.php\">Sign out</a>
					</div>";

			//Proveravamo da li je neki todo dodat
			$todo = null;
			if(isset($_POST["add_todo"])){
				$text = htmlspecialchars($_POST["text"]);
				$description = (isset($_POST["description"])) ? htmlspecialchars($_POST["description"]) : "";
				$date = htmlspecialchars($_POST["date"]);
				$completed = (isset($_POST["completed"]) && $_POST["completed"]) ? 1 : 0;
				if(!$db->insertTask($text, $description, $date, $completed, $user->getId())){
					echo "Nije dodato u bazu";
				}
				//unset($_POST["add_todo"]);
			}

			?>
				<a href="/add_todo.php" class="sidebar_add_todo"><span>&#43;</span> Add todo</a>
				<div class="sidebar_options">
					<h2 class="options_header">Lists: </h2>
					<a class="options_link" href="?view=today">&raquo; Today's tasks</a>
					<a class="options_link" href="?view=week">&raquo; This week's tasks</a>
					<a class="options_link" href="?view=month">&raquo; This month's tasks</a>
					<a class="options_link" href="?view=all">&raquo; All tasks ever</a>
				</div>
			</nav>

			<div id="div_view">
				<div id="div_uncompleted">
					<?php
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
						$view = "today";
						if(isset($_GET["view"]))
							$view = $_GET["view"];

						if($view == "today"){
							echo "<h2 class=\"view_header\">Today's tasks </h2>";
							$todos = $db->getTasksForUser($user->getId(), date("Y-m-d"), date("Y-m-d"), 0);
						} else if($view == "week"){
							echo "<h2 class=\"view_header\">This week tasks </h2>";
							$todos = $db->getTasksForUser($user->getId(), date("Y-m-d", strtotime("+1 day")), date("Y-m-d", strtotime("+7 day")), 0);
						} else if($view == "month"){
							echo "<h2 class=\"view_header\">This month tasks </h2>";
							$todos = $db->getTasksForUser($user->getId(), date("Y-m-d", strtotime("+1 day")), date("Y-m-d", strtotime("+30 day")), 0);
						} else if($view == "all"){
							echo "<h2 class=\"view_header\">All tasks </h2>";
							$todos = $db->getTasksForUser($user->getId());
						} else {
							echo "<h2 class=\"view_header\">Today's tasks </h2>";
							$todos = $db->getTasksForUser($user->getId(), date("Y-m-d"), date("Y-m-d"));
						}
						printTodos($todos, $view, $user->getId(), ($view == "today"));

						/*if(isset($_GET["id"])){
							echo $db->getTaskById($_GET["id"])->getDetails($view);
						}*/
				?>
				</div>
				<div id="div_completed">
					<?php
						if($view != "all"){
							echo "<h2 class=\"view_header\">Completed today: </h2>";
							$todos_completed = $db->getTasksForUser($user->getId(), null, null, 1);
							printTodos($todos_completed, $view, $user->getId(), false);
						}
					?>
				</div>
			</div>
			<div class="div_right">
				<div class="quick_todo">
					<input id="quick_text" type="text" placeholder="Quick add..." maxlength="50">
					<button id="quick_submit" onClick="quick_add(<?php echo $user->getId() . ", '" . $view . "'"; ?>)">Add</button>
				</div>
				<div id="div_details"></div>
			</div>

	<?php
		}
	?>
	
	<script>
		if ( window.history.replaceState ) {
		  window.history.replaceState( null, null, window.location.href );
		}
	</script>
</body>
</html>