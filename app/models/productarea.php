<?php

class Productarea extends AppModel
{
	var $name = 'Productarea';
   	
 	function getProductArea($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Productarea.id','Productarea.product_area'), 'conditions' => array('Productarea.isactive' => 1)));
		$count = count($result)+1;
		# Add blank option to dropdown.
		$result[0] = ' ';
		# Set blank value at first.
		asort($result);
		# Get key for "other" in result array.
		$key = array_search('Other', $result);
		if($key>0) {
			# Remove 'Other' from middle.
			unset($result[$key]);
			# Add element at last.
			$result[$key] = 'Other';
		}
		return $result;	
	}

   	function getProductareaNameById($ProductareaId)
	{
		$res = $this->find('first',array('fields'=>array("Productarea.product_area"),'conditions' => array('Productarea.id' => $ProductareaId)));
		return $res['Productarea']['product_area'];
		
	}  
	function getProductareaIdByName($ProductareaId)
	{
		$res = $this->find('first',array('fields'=>array('Productarea.id'),'conditions' => array('Productarea.product_area' => $ProductareaId),array('Productarea.isactive' => 1)));
		return $res['Productarea']['id'];
	}
		function getsearchProductareaData($ProductareaId)
	{	
			
			$res= $this->find('first',array('fields'=>array('Productarea.id'),'conditions' =>array('Productarea.product_area LIKE ' => "%".$ProductareaId."%"),array('Productarea.isactive' => 1)));
			return  $res['Productarea']['id'] ;
		
	}

}    
?>