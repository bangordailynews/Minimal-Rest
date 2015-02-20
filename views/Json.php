<?php
require_once("AbstractView.php");

class Json extends AbstractView {

	public function View() {
	
		Response::getInstance()->alterContentType('application/json');	
		Response::getInstance()->sendResponseCode(200);
		echo json_encode($this->_records);
	
	}

}