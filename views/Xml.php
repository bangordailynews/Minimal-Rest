<?php
require_once("AbstractView.php");

class Xml extends AbstractView {

	public function View() {
		// iterate through $this->_records

		Response::getInstance()->alterContentType('text/xml');
		Response::getInstance()->sendResponseCode(200);

		$xml = new SimpleXMLElement('<view/>');
		
		$this->_compileRecords($xml, $this->_records);
		
		print $xml->asXML();
		
	}
	
	private function _compileRecords(SimpleXMLElement &$xml, array $records) {
		
		foreach($records as $key => $value){
			
			if(is_numeric($key)) $key = 'key_'.$key;
			
			if(is_array($value)){
			
				$child = $xml->addChild($key);
				$this->_compileRecords($child, $value);
				
			}elseif(is_object($value)){
			
				$array = get_object_vars($value);
				$child = $xml->addChild($key);
				$this->_compileRecords($child, $array);
			
			}else{
			
				$xml->addChild($key, $value);
				
			}
			
		}
		
	}

}