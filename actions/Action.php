<?php

abstract class Action {

	protected $_view;
	protected $_request;
	protected $_actionName;
	protected $_controller;
	
	public function __construct(AbstractView &$view, &$request = array(), &$actionName = null, &$controller = null) {
	
		$this->_view = &$view;
		$this->_request = &$request;
		$this->_actionName = &$actionName;
		$this->_controller = &$controller;
		
	}

	protected function getRequest($key, $value = null) {
	
		return isset($this->_request[$key]) ? $this->_request[$key] : $value;
	
	}
	
	protected function getSortedRequest() {
	
		ksort($this->_request);
		
		return $this->_request;
	
	}
	
}