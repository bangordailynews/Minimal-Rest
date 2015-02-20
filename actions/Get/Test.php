<?php 

class Test extends Action {

	public function testAction() {
	
		$this->_view->addValue('this is a value');
		$this->_view->addRecord('record1', 'record1 value');
		$this->_view->addMultipleRecords(array('record2' => 'record2 value', 'record3' => 'record3 value'));
		$this->_view->addRecord('record5', 'temporary content');
		$this->_view->changeRecord('record5', 'record5 value');
		$this->_view->addRecord('record100', 'temporary value');
		$this->_view->removeRecord('record100');
		// $this->_view->clearRecords() // This purges all records. Uncomment to test.
		
	}

}
