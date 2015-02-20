<?php

class Authenticate {
	
	private $_authenticated = false;
	private $_authRequired = true;
	
	private static $_instance;

	protected function __construct() { /* Lock the class so it cannot be instantiated */ }
	
	public static function getInstance() {
	
		if(!(self::$_instance instanceof Authenticate)){
			self::$_instance = new Authenticate();
		}
	
		return self::$_instance;
	
	}
		
	public function setAuthed($authed = false) {

		$this->_authenticated = ($authed === true) ? true : false; // Anything but a true boolean will result in this return false
	
	}
	
	public function isAuthed() {
	
		return ($this->_authenticated === true) ? true : false; // Only ever want to return a boolean, so we verify it.
	
	}
	
	public function setAuthRequired($req = true) {
	
		$this->_authRequired = ($req === false) ? false : true; // The reason we do this is to make sure that only if you specifically pass a FALSE boolean is the api unlocked
	
	}
	
	public function isAuthRequired() {
	
		return ($this->_authRequired === false) ? false : true; // The reason we do this is to make sure that only if you specifically pass a FALSE boolean is the api unlocked
	
	}
	
	/* The clone and wakeup functions are shut off for the singleton pattern, so they can be ignored */
	private function __clone() {}
	private function __wakeup() {}
	
}