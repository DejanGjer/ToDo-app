<?php

session_start();
require_once("db_utils.php");

function changeImage($db, $user_id){
	if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK){
		$allowed = array("gif", "jpeg", "jpg", "png");
		$tmp = explode(".", $_FILES["photo"]["name"]);
		$extension = end($tmp);
		if(!in_array($extension, $allowed)){
			return array(false, "Unsupported format for image!");
		} else if(!move_uploaded_file($_FILES["photo"]["tmp_name"], "images/profile_pictures/" . $user_id . "." . $extension)){
			 return array(false, "Sorry, there was a problem uploading that photo! " . $_FILES["photo"]["error"]);
		} else {
			$image = $user_id . "." . $extension;
			if(!$db->updateUserImage($user_id, $image)){
				return array(false, "Error occured during saving image to database");
			} else {
				return array(true, "Image changed successfully");
			}
		}
	} else {
		 switch( $_FILES["photo"]["error"] ) {
	      case UPLOAD_ERR_INI_SIZE:
	        $message = "The photo is larger than the server allows.";
	        break;
	      case UPLOAD_ERR_FORM_SIZE:
	        $message = "The photo is larger than the script allows.";
	        break;
	      case UPLOAD_ERR_NO_FILE:
	        $message = "No file was uploaded. Make sure you choose a file to upload.";
	        break;
	      default:
	        return "Please contact your server administrator for help.";
	    }
	    return array(false, "Sorry, there was a problem uploading that photo. $message");
	}
}

function changePassword($db, $user_id, $old_password, $new_password, $new_password_repeat){
	if(empty($old_password) || empty($new_password) || empty($new_password_repeat)){
		return array(false, "All fields needs to be filled!");
	}

	if(strlen($new_password) < 5){
		return array(false, "New password is too short!");
	}

	if($new_password !== $new_password_repeat){
		return array(false, "New password doesn't match repeated!");
	}

	$user = $db->getUserById($user_id);
	if($user){
		if(password_verify($old_password, $user->getPassword())){
			$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
			if($db->updateUserPassword($user_id, $new_password_hash)){
				return array(true, "Password successfully changed!");
			} else {
				return array(false, "Database error!");
			}
		} else {
			return array(false, "Wrong current password. Try again!");
		}
	} else {
		return array(false, "Error occured!");
	}
}

if(!isset($_SESSION["user_id"])){
	header("Location: /authentication/login.php");
} else {
	$db = new Database();
	$message = "";
	$valid = false;
	$operation = false;

	if(isset($_POST["change_password"])){
		$old_password = ($_POST["old_password"]) ? htmlspecialchars($_POST["old_password"]) : "";
		$new_password = ($_POST["new_password"]) ? htmlspecialchars($_POST["new_password"]) : "";
		$new_password_repeat = ($_POST["new_password_repeat"]) ? htmlspecialchars($_POST["new_password_repeat"]) : "";
		list($valid, $message) = changePassword($db, $_SESSION["user_id"], $old_password, $new_password, $new_password_repeat);
		$operation = true;
	}

	if(isset($_POST["changeImage"])){
		list($valid, $message) = changeImage($db, $_SESSION["user_id"]);
		$operation = true;
	}
	$user = $db->getUserById($_SESSION["user_id"]);

?>

<!DOCTYPE html>
<html>
<head>
	<title>TODO app</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/profile.css">
</head>
<body>
	<?php
		if($operation){
			$class = ($valid) ? "success" : "error";
			echo "<div class=\"message {$class}\">
					<p>{$message}</p>
				</div>";
		}
	?>
	<div class="container">
		<div class="div_image">
			<?php
				echo "<img src=\"images/profile_pictures/".$user->getImage()."\">";
			?>

			<form method="POST" enctype="multipart/form-data">
				<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
				<!--<label for="photo">Upload Photo </label>-->
				<input type="file" name="photo" id="photo" value="" />
				<div style="clear: both;">
					<input class="button" type="submit" name="changeImage" value="Change profile picture" />
				</div>
			</form>
			<div class="down">
			<?php
				echo "<a href=\"/index.php\">Tasks</a>";
				echo "<a href=\"/authentication/signout.php\">Sign out</a>";
			?>
			</div>

		</div>

		<div class="div_info">
			<div class="subdiv">
				<h2 class="section_header">Profile Information</h2>
				<?php
					echo "<h2 class=\"text\">Username: ".$user->getUsername()."</h2>";
					echo "<h2 class=\"text\">Email: ".$user->getEmail()."</h2>";
				?>
			</div>
			<div class="subdiv">
				<h2 class="section_header">Change Password</h2>
				<form method="POST">
					<label for="old_password">Current password</label>
					<input type="password" name="old_password" required>
					<label for="new_password">New password</label>
					<input type="password" minlength="5" name="new_password" required>
					<label for="new_password_repeat">Repeat new password</label>
					<input type="password" minlength="5" name="new_password_repeat" required>
					<input type="submit" class="button" name="change_password" value="Change Password">
				</form>
			</div>
		</div>

	</div>

	<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>
	<?php
	}
	?>

</body>