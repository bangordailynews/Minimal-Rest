<?php

abstract class AbstractView {
	
	protected $_records = array();
	
	public function addRecord($key, $value){
	
		if($key == false || empty($key)){
			$this->_records[] = $value;
		}else{
			$this->_records[$key] = $value;
		}
	
	}
	
	public function addValue($value) {
	
		$this->addRecord(false, $value);
		
	}
	
	public function addMultipleRecords(array $records){
	
		foreach($records as $key => $value) {
		
			$this->addRecord($key, $value);
		
		}
	
	}
	
	public function changeRecord($key, $value) {
	
		$this->addRecord($key, $value); // For ease of use, we have two function names so if we're reading the code we won't have to wonder why we add record 5 twice.
	
	}
	
	public function removeRecord($key){
		
		unset($this->_records[$key]);
		
	}
	
	public function clearRecords() {
	
		// Completely remove all records
		$this->_records = array();
	
	}
	
	abstract protected function View(); // This should be what the views expand. It's where they compile $_records into whatever format they like, and display it.

}