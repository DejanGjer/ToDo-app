<?php
	session_start();

	require_once("../db_utils.php");
	require_once("../classes/User.php");

	function validateAndSignIn($email, $username, $password, $password_check){
		if(empty($email) || empty($username) || empty($password) || empty($password_check)){
			return array(false, "All fields needs to be filled!");
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return array(false, "Email address is not valid!");
		}

		if(strlen($password) < 5){
			return array(false, "Password is too short!");
		}

		if($password !== $password_check){
			return array(false, "Passwords doesn't match!");
		}
		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$db = new Database();
		$users = $db->getAllUsers();
		foreach ($users as $user) {
			if(strcmp($user->getUsername(), $username) == 0){
				return array(false, "Username already exist!");
			}
			if(strcmp($user->getEmail(), $email) == 0){
				return array(false, "Account with this email already exist!");
			}
		}
		if($db->insertUser($username, $password_hash, $email)){
			$_SESSION["user_id"] = $db->getUserByUsername($username)->getId();
			return array(true, "Registration successful!");
		} else {
			return array(false, "Error occured while inserting in database! Please try again!");
		}
	}

	$msg = "";

	if(isset($_POST["sign_up"])){
		$username = (isset($_POST["username"])) ? htmlspecialchars(trim($_POST["username"])) : "";
		setcookie( "username_register", $username, time() + 60 * 60 * 24 * 30, "/", "", false, true);
		$_COOKIE["username_register"] = $username;
		$password = (isset($_POST["password"])) ? htmlspecialchars(trim($_POST["password"])) : "";
		$password_check = (isset($_POST["password_check"])) ? htmlspecialchars(trim($_POST["password_check"])) : "";
		$email = (isset($_POST["email"])) ? htmlspecialchars(trim($_POST["email"])) : "";
		setcookie( "email_register", $email, time() + 60 * 60 * 24 * 30, "/", "", false, true);
		$_COOKIE["email_register"] = $email;
		list($valid, $msg) = validateAndSignIn($email, $username, $password, $password_check);
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
		<h2 class="header">Register</h2>
		<form method="POST">
			<label>Email </label>
			<input type="email" name="email" maxlength="100" value="<?php echo (isset($_COOKIE["email_register"])) ? $_COOKIE["email_register"] : "" ?>" required>
			<label>Username </label>
			<input type="text" name="username" maxlength="50" value="<?php echo (isset($_COOKIE["username_register"])) ? $_COOKIE["username_register"] : "" ?>" required>
			<label>Password (5 characters minimum) </label>
			<input type="password" minlength="5" name="password" required>
			<label>Repeat password </label>
			<input type="password" name="password_check" required>
			<input type="submit" name="sign_up" value="Sign up">
		</form>
		<div>
			<p>Already have account?</p>
			<a href="/authentication/login.php">Login</a>
		</div>
		<h2><?php echo $msg;?></h2>
	</div>
</body>

