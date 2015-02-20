<?php

require_once( "models/Response.php" );
require_once( "models/Authenticate.php" );
require_once( "actions/Action.php" );

class bootstrap {

	private $_action;
	private $_request;
	private $_controller;
	private $_view;
	private $_errors = false;
	private $_actionName = null;
	
	public function __construct($method, $request) {
	
		$request = explode('/', $request);
		
		// Determine action name (REQUEST_URI) (URL Scheme is [DOMAIN] / [ACTION] / [PARAM KEY] / [PARAM VALUE] / [PARAM KEY] / [PARAM VALUE] ... 
		$this->_actionName = array_shift($request);
		$action = ucfirst(strtolower($this->_actionName));
				
		// Format the rest of the request as KEY / VALUE pairs
		
		$requestTemp = array();
		$count = count($request);
		
		// This code requires you to have matching key/value pairs for each variable. If it's a boolean, for instance, key/true would set it true or key/false would set it false.
		for($i = 0; $i < $count; $i = $i + 2){
			
			if(isset($request[$i + 1]))
				$requestTemp[$request[$i]] = $request[$i + 1];
		
		}
		
		$this->_request = $request = $requestTemp;
		
		// Determine the controller based on the REQUEST_METHOD param
		switch(strtolower($method)){
		
			case "get":
			case "put":
			case "post":
			case "delete":
				$controller = ucfirst(strtolower($method));
				break;
			default:
				$controller = "Get";
				break;
		}
		
		$this->_controller = $controller;
		
		// Determine the view. If no view has been explicitly requested, default to Json.php
		
		if(!isset($request["view"])) 
			$request["view"] = "json";
		else
			$request["view"] = ucfirst(strtolower($request["view"]));
			
		if(!file_exists('views/'.$request["view"].'.php')){
		
			// Send an error response 500
			Response::getInstance()->sendResponseCode(500);
			return false;

		}else{
		
			// There exists a view for you!
			require_once('views/'.$request["view"].'.php'); // Filtering on this would be nice.
			$class = $request['view'];
			$this->_view = new $class();
			
			if(!($this->_view instanceof AbstractView)){
			
				// The view is not properly developed, and cannot be trusted. Kill it with fire.
				Response::getInstance()->sendResponseCode(406);
				require_once('views/Json.php');
				$this->_view = new Json();
				$this->_view->addRecord('view_error', "$class is not properly developed. Must extend AbstractView");
				$this->_errors = true;

			}
			
		}
		
		// Before we go into specific actions, we must do all the global actions.
		
		foreach(scandir(dirname(__FILE__).'/actions/Global') as $filename) {
		
			$path = dirname(__FILE__).'/actions/Global/'.$filename;
			if(is_file($path)){
				require_once($path);
				
				$class = str_replace('.php', null, $filename);

				$tempClass = new $class($this->_view, $this->_request, $this->_actionName, $this->_controller);
				
				if(!($tempClass instanceof Action)){
				
					if(!isset($globalErrors)) $globalErrors = array();
					// The action is not properly developed, and cannot be trusted. Kill it with fire.
					Response::getInstance()->sendResponseCode(406);
					$globalErrors[] = "$class is not properly developed. Does not extend Action.";
					
				}else{

					$method = strtolower($class).'Action';
					$tempClass->$method(); // Perform the action
				
				}
				
				if(isset($globalErrors) && count($globalErrors) > 0){
					$this->_view->addRecord('global_errors', $globalErrors);
					$this->_errors = true;
				}
				
			}
		
		}
		
		// By default this returns true
		if(!$this->_errors && Authenticate::getInstance()->isAuthRequired() && !Authenticate::getInstance()->isAuthed()){
		
			// You can't access this action because you're not authorized.
			Response::getInstance()->sendResponseCode(401);
			$this->_view->addRecord('auth_error', "You are not authorized");
		
		}else{
			
			if(!file_exists('actions/'.$controller.'/'.$action.'.php')){
				
				// Send an error response 404
				Response::getInstance()->sendResponseCode(404);
				$this->_view->addRecord('action_not_found', "/$controller/$action.php does not exist");
			
			}else{
				
				// There exists an action for you!
				require_once('actions/'.$controller.'/'.$action.'.php'); // Filtering on this would be nice.
							
				$this->_action = new $action($this->_view, $this->_request, $this->_actionName, $this->_controller);
				
				if(!($this->_action instanceof Action)){
				
					// The action is not properly developed, and cannot be trusted. Kill it with fire.
					Response::getInstance()->sendResponseCode(406);
					$this->_view->addRecord('action_error', "$action is not properly developed. Must extend class Action");
					
				}else{

					$method = strtolower($action).'Action';
					$this->_action->$method(); // Perform the action
				
				}
				
			}

		}
			
		$this->_view->View();
		
	}

}
