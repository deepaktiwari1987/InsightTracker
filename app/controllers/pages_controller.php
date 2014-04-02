<?php
class PagesController extends AppController
{
	var $name = 'Pages';
	var $useTable = false;
	var $uses = array();
	var $helpers = array('Javascript','Ajax','Form', 'Custom');

   function display()
	{
		# Detecting Mobile version & device.
		$MobileSpecificationArray	= explode("/", $_SERVER["HTTP_USER_AGENT"]);	
		$MobileDevice				= $MobileSpecificationArray[0];
		$this->Session->write('MobileDevice', $MobileDevice);
			
		if($MobileSpecificationArray[1] !=""){
			$MobileVersionArray			= explode(" ", $MobileSpecificationArray[1]);
			$MobileVersion				= $MobileVersionArray[0]; 
			$this->Session->write('MobileVersion', $MobileVersion);
		}
		# Stop caching for Browser
		header("Cache-Control: no-cache, must-revalidate");
		$this->layout='mobile';
   }
}
?>