<?php

class Auth extends Action {

	private $_key = 'testkey';

	public function authAction() {
	
		Authenticate::getInstance()->setAuthRequired(true);
		Authenticate::getInstance()->setAuthed($this->_verifySignature());

	}

	private function _isAuthed() {

		return $this->getRequest('key') === $this->_key;

	}
	
}
