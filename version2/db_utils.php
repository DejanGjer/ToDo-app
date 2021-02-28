<?php
require_once("constants.php");
require_once("classes/ToDo.php");
require_once("classes/User.php");

class Database{
	private $conn;

	public function __construct($configFile = "config.ini") {
		if($config = parse_ini_file($configFile)) {
			$host = $config["host"];
			$database = $config["database"];
			$user = $config["user"];
			$password = $config["password"];
			$this->conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
		}
		else
			exit("Missing configuration file.");
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if($this->conn){
			//echo "Povezani smo sa bazom";
		}
	}

	public function __destruct(){
		$this->conn = null;	
	}

	public function getAllTasks() {
		$result = array();
		$sql = "SELECT * FROM ".TBL_TASKS;
		try {
			$query_result = $this->conn->query($sql);
			foreach ($query_result as $q) {
				$result[] = new ToDo($q[COL_TASKS_ID], $q[COL_TASKS_TEXT], $q[COL_TASKS_DESCRIPTION], $q[COL_TASKS_DATE], $q[COL_TASKS_COMPLETED], $q[COL_TASKS_USERID]);
			}
		} catch (PDOException $e) {
				echo $e->getMessage();
		}
		return $result;
	}

	public function getTaskById($id){
		$result = null;
		$sql = "SELECT * FROM ".TBL_TASKS." WHERE ".COL_TASKS_ID." = :id";
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":id", $id);
			$st->execute();
			$match = $st->fetch(PDO::FETCH_ASSOC);
			if(!empty($match)){
				$result = new ToDo($match[COL_TASKS_ID], $match[COL_TASKS_TEXT], $match[COL_TASKS_DESCRIPTION], $match[COL_TASKS_DATE], $match[COL_TASKS_COMPLETED], $match[COL_TASKS_USERID]);
				
			}
		} catch (PDOException $e) {
				echo $e->getMessage();
		}
		return $result;
	}

	public function getTasksForUser($id, $start_date = null, $end_date = null, $completed = null){
		$result = array();
		$sql = "SELECT * FROM ".TBL_TASKS." WHERE ".COL_TASKS_USERID." = :userid";
		if($start_date){
			$sql .= " AND ".COL_TASKS_DATE." >= :start_date";
		}
		if($end_date){
			$sql .= " AND ".COL_TASKS_DATE." <= :end_date";
		}
		if($completed !== null){
			$sql .= " AND ".COL_TASKS_COMPLETED." = :completed";
			if($completed == 1){
				$sql .= " AND ".COL_TASKS_DATE_COMPLETED." = :date_completed";
			}
		}
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":userid", $id);
			if($start_date)
				$st->bindValue(":start_date", $start_date);
			if($end_date)
				$st->bindValue(":end_date", $end_date);
			if($completed !== null){
				$st->bindValue(":completed", $completed);
				if($completed == 1)
					$st->bindValue(":date_completed", date("Y-m-d"));
			}
			$st->execute();
			$match = $st->fetchAll();
			if(!empty($match)){
				foreach ($match as $q) {
					$result[] = new ToDo($q[COL_TASKS_ID], $q[COL_TASKS_TEXT], $q[COL_TASKS_DESCRIPTION], $q[COL_TASKS_DATE], $q[COL_TASKS_COMPLETED], $q[COL_TASKS_USERID]);
				}
			}
		} catch (PDOException $e) {
				echo $e->getMessage();
		}
		return $result;
	}



	public function getAllUsers() {
		$result = array();
		$sql = "SELECT * FROM ".TBL_USERS;
		try {
			$query_result = $this->conn->query($sql);
			foreach ($query_result as $q) {
				$result[] = new User($q[COL_USERS_ID], $q[COL_USERS_USERNAME], $q[COL_USERS_PASSWORD], $q[COL_USERS_EMAIL], $q[COL_USERS_IMAGE]);
			}
		} catch (PDOException $e) {
				echo $e->getMessage();
		}
		return $result;
	}

	public function insertTask($text, $description, $date, $completed, $userid) {
		$sql = "";
		if($completed == 1){
			$completed_date = date("Y-m-d");
			$sql = "INSERT INTO ".TBL_TASKS." (".COL_TASKS_TEXT.", ".COL_TASKS_DESCRIPTION.", ".COL_TASKS_DATE.", ".COL_TASKS_COMPLETED.", ".COL_TASKS_DATE_COMPLETED.", ".COL_TASKS_USERID.") VALUES (:text, :description, :date, :completed, :completed_date, :userid);";
		} else {
			$sql = "INSERT INTO ".TBL_TASKS." (".COL_TASKS_TEXT.", ".COL_TASKS_DESCRIPTION.", ".COL_TASKS_DATE.", ".COL_TASKS_COMPLETED.", ".COL_TASKS_USERID.") VALUES (:text, :description, :date, :completed, :userid);";
		}

		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":text", $text);
			$st->bindValue(":description", $description);
			$st->bindValue(":date", $date);
			$st->bindValue(":completed", $completed);
			if($completed == 1){
				$st->bindValue(":completed_date", $completed_date);
			}
			$st->bindValue(":userid", $userid);
			$st->execute();
		} catch (PDOException $e) {
				echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function insertQuickTask($userid, $text){
		$description = "";
		$date = date("Y-m-d");
		$completed = 0;
		$sql = "INSERT INTO ".TBL_TASKS." (".COL_TASKS_TEXT.", ".COL_TASKS_DESCRIPTION.", ".COL_TASKS_DATE.", ".COL_TASKS_COMPLETED.", ".COL_TASKS_USERID.") VALUES (:text, :description, :date, :completed, :userid);";
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":text", $text);
			$st->bindValue(":description", $description);
			$st->bindValue(":date", $date);
			$st->bindValue(":completed", $completed);
			$st->bindValue(":userid", $userid);
			$st->execute();
		} catch (PDOException $e) {
				echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function insertUser($username, $password, $email) {
		$sql = "INSERT INTO ".TBL_USERS." (".COL_USERS_USERNAME.", ".COL_USERS_PASSWORD.", ".COL_USERS_EMAIL.") VALUES (:username, :password, :email);";
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":username", $username);
			$st->bindValue(":password", $password);
			$st->bindValue(":email", $email);
			$st->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function getUserByUsername($username){
		$sql = "SELECT * FROM ".TBL_USERS." WHERE ".COL_USERS_USERNAME." = :username";
		$result = null;
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":username", $username);
			$st->execute();
			$match = $st->fetch(PDO::FETCH_ASSOC);
			if(!empty($match)){
				$result = new User($match[COL_USERS_ID], $match[COL_USERS_USERNAME], $match[COL_USERS_PASSWORD], $match[COL_USERS_EMAIL], $match[COL_USERS_IMAGE]);
			}
			
		} catch (PDOException $e) {
				echo $e->getMessage();
		}
		return $result;
	}

	public function getUserById($id){
		$sql = "SELECT * FROM ".TBL_USERS." WHERE ".COL_USERS_ID." = :id";
		$result = null;
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":id", $id);
			$st->execute();
			$match = $st->fetch(PDO::FETCH_ASSOC);
			if(!empty($match)){
				$result = new User($match[COL_USERS_ID], $match[COL_USERS_USERNAME], $match[COL_USERS_PASSWORD], $match[COL_USERS_EMAIL], $match[COL_USERS_IMAGE]);
			}
			
		} catch (PDOException $e) {
				echo $e->getMessage();
		}
		return $result;
	}

	public function updateTaskState($id, $new_completed){
		$date_completed = ($new_completed == 1) ? date("Y-m-d") : null;
		$sql = "UPDATE " . TBL_TASKS . " SET " . COL_TASKS_COMPLETED . " = :completed, " . COL_TASKS_DATE_COMPLETED . " = :date_completed WHERE " . COL_TASKS_ID . " = :id";
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":completed", $new_completed);
			$st->bindValue(":date_completed", $date_completed);
			$st->bindValue(":id", $id);
			$st->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function updateUserImage($id, $image){
		$sql = "UPDATE " . TBL_USERS . " SET " . COL_USERS_IMAGE." = :image WHERE " . COL_USERS_ID . " = :id";
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":image", $image);
			$st->bindValue(":id", $id);
			$st->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function updateUserPassword($id, $password){
		$sql = "UPDATE " . TBL_USERS . " SET " . COL_USERS_PASSWORD." = :password WHERE " . COL_USERS_ID . " = :id";
		try {
			$st = $this->conn->prepare($sql);
			$st->bindValue(":password", $password);
			$st->bindValue(":id", $id);
			$st->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function deleteTask($id){
		$sql = "DELETE FROM " . TBL_TASKS . " WHERE " . COL_TASKS_ID . " = :id";
		try{
			$st = $this->conn->prepare($sql);
			$st->bindValue(":id", $id);
			$st->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
		return true;
	}

}