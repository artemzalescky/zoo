<?php

//любой контролер будет наследоваться от базового класса
 
	class Lib_BaseController {
		private $member; 	// будет передаваться в представление

		function __set($name,$val) {
			$this->member[$name] = $val;
		}

		function __get($name) {
			return $this->member;	
		}        			 
	}
	
?>