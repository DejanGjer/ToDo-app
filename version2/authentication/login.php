<?php
	session_start();

	require_once("../db_utils.php");
	require_once("../classes/User.php");

	function validateAndLogin($username, $password){
		if(empty($username) || empty($password)){
			return array(false, "All fields needs to be filled!");
		}

		$db = new Database();
		$user = $db->getUserByUsername($username);
		if($user){
			if(password_verify($password, $user->getPassword())){
				$_SESSION["user_id"] = $user->getId();
				return array(true, "Login successful!");
			} else {
				return array(false, "Wrong password!");
			}
		} else {
			return array(false, "Username doesn't exist");
		}
	}

	$msg = "";

	if(isset($_POST["login"])){
		$username = (isset($_POST["username"])) ? htmlspecialchars(trim($_POST["username"])) : "";
		setcookie( "username", $username, time() + 60 * 60 * 24 * 30, "/", "", false, true);
		$_COOKIE["username"] = $username;
		$password = (isset($_POST["password"])) ? htmlspecialchars(trim($_POST["password"])) : "";
		list($valid, $msg) = validateAndLogin($username, $password);
		if($valid){
			header("Location: /index.php");
		} 
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TODO app</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/add_todo.css">
</head>
<body>
	<div class="container">
		<h2 class="header">Login</h2>
		<form method="POST">
			<label>Username </label>
			<input type="text" name="username" maxlength="50" value="<?php echo (isset($_COOKIE["username"])) ? $_COOKIE["username"] : "" ?>" required>
			<label>Password </label>
			<input type="password" name="password" required>
			<input type="submit" name="login" value="Sign In">
		</form>
		<div>
			<p>Don't have account?</p>
			<a href="/authentication/register.php">Register</a>
		</div>
		<h2><?php echo $msg;?></h2>
	</div>
</body>

