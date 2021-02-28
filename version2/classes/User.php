<?php

class User{

	private $id;
	private $username;
	private $password;
	private $email;
	private $image;

	public function __construct($id, $username, $password, $email, $image="default.png"){
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->image = $image;
	}

	public function getId(){
		return $this->id;
	}

	public function getUsername(){
		return $this->username;
	}

	public function getPassword(){
		return $this->password;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getHtml(){
		return "<p>$this->id, $this->username, $this->email</p>";
	}

	public function getImage(){
		return $this->image;
	}

}