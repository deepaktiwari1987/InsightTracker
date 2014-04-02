<?php

class Sellingobstacle extends AppModel
{
	var $name = 'Sellingobstacle';
   	
 	function getSellingObstacle($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Sellingobstacle.id','Sellingobstacle.selling_obstacles'), 'conditions' => array('Sellingobstacle.isactive' => 1)));
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

   	function getSellingobstacleNameById($SellingobstacleId)
	{
		$res = $this->find('first',array('fields'=>array("Sellingobstacle.selling_obstacles"),'conditions' => array('Sellingobstacle.id' => $SellingobstacleId)));
		return $res['Sellingobstacle']['selling_obstacles'];
		
	}   	
	function getSellingobstacleIdByName($SellingobstacleId)
	{
		$res = $this->find('first',array('fields'=>array('Sellingobstacle.id'),'conditions' => array('Sellingobstacle.selling_obstacles' => $SellingobstacleId),array('Sellingobstacle.isactive' => 1)));
		return $res['Sellingobstacle']['id'];
	}
	function getsearchSellingobstacleData($Sellingobstacle)
	{				
			$res= $this->find('first',array('fields'=>array('Sellingobstacle.id'),'conditions' =>array('Sellingobstacle.selling_obstacles LIKE ' => "%".$Sellingobstacle."%"),array('Sellingobstacle.isactive' => 1)));
			return  $res['Sellingobstacle']['id'] ;		
	}

}    
?>