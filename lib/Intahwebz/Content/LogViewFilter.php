<?php



class LogViewFilter {

	var		$startOffset = 0;
	var		$endOffset = FALSE;

//	var 	$date;
//	var		$time;
	var		$process;
	var		$level;
	var		$domain;
	var		$path;
	var		$message;

	var		$matchSession = TRUE;

	public function __construct(){
	}


	public function		setFilter($name, $value){
		$this->$name = $value;

		switch($name){
			case('process'):{
				//do some intval
				$this->process = $value;
				break;
			}
			case('level'):{
				//check is valid log level
				$this->level = $value;
				break;
			}
			case('domain'):{
				$this->domain = $value;
				break;
			}
			case('path'):{
				$this->path = $value;
				break;
			}
			case('message'):{
				$this->message = $value;
				break;
			}

			default:{
				throw new Exception("Unknown filter property.");
			}
		}
	}
}
