<?php

class ToDo{

	private $id;
	private $text;
	private $description;
	private $date;
	private $completed;
	private $userid;

	public function __construct($id, $text, $description, $date, $completed, $userid){
		$this->id = $id;
		$this->text = $text;
		$this->description = $description;
		$this->date = $date;
		$this->completed = $completed;
		$this->userid = $userid;
	}

	public function getId(){
		return $this->id;
	}

	public function getText(){
		return $this->text;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getDate(){
		return $this->date;
	}

	public function getCompleted(){
		return $this->completed;
	}

	public function getUserId(){
		return $this->userid;
	}

	public function getHtml($view_mode, $user_id, $reorder){
		$done = ($this->completed) ? "checked" : "";
		$buttons = ($reorder) ? "<button class=\"up\" onClick=\"up(this)\">&uarr;</button>
					<button class=\"down\" onClick=\"down(this)\">&darr;</button>" : "";
		$html = "<div class=\"todo {$done}\" id=\"todo{$this->id}\">
					<span onClick=\"show_details({$this->id}, '{$view_mode}')\"><h3>$this->text</h3></span>
					{$buttons}
					<input type=\"checkbox\" id=\"check{$this->getId()}\" onClick=\"toggle(this, {$this->id}, '{$view_mode}', $user_id)\" $done>
				</div>";
	
		return $html;
	}

	public function getDetails($view_mode){
		$done = ($this->completed) ? "Completed" : "Not Completed";
		$change_state_text = ($this->completed) ? "Uncheck" : "Check";
		$html = "<div>
					<h3 class=\"details_header\">$this->text</h3>
					<p class=\"details_check\">$done</p>
					<p class=\"details_date\">Date: ".date("M d, Y.", strtotime($this->date)) ."</p>
					<p class=\"details_label\">Description: </p>
					<div class=\"details_box\">
						<p class=\"details_description\">$this->description</p>
					</div>
					<div class=\"details_buttons\">
						<button class=\"details_button\" onClick=\"checkButton(this, {$this->id}, '{$view_mode}', {$this->getUserId()})\">{$change_state_text}</button>
						<button class=\"details_button\" onClick=\"deleteButton({$this->id})\">Delete</button>
					</div>
				</div>";
	
		return $html;
	}
}