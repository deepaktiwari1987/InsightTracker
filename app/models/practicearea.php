<?php

class Practicearea extends AppModel
{
	var $name = 'Practicearea';
   	
 	function getPracticeArea($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Practicearea.id','Practicearea.practice_area'), 'conditions' => array('Practicearea.isactive' => 1), 'order' => array('Practicearea.id')));
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

   	function getPracticeareaNameById($practiceAreaId)
	{
		$res = $this->find('first',array('fields'=>array("Practicearea.practice_area"),'conditions' => array('Practicearea.id' => $practiceAreaId)));
		return $res['Practicearea']['practice_area'];
		
	}   	
	function getPracticeareaIdByName($practiceAreaName)
	{
		$res = $this->find('first',array('fields'=>array('Practicearea.id'),'conditions' => array('Practicearea.practice_area' => $practiceAreaName),array('Practicearea.isactive' => 1)));
		return $res['Practicearea']['id'];
	}

	function getsearchPracticeareaData($practice_area)
	{	
			
			$res= $this->find('first',array('fields'=>array('Practicearea.id'),'conditions' =>array('Practicearea.practice_area LIKE ' => "%".$practice_area."%"),array('Practicearea.isactive' => 1)));
			return  $res['Practicearea']['id'] ;
		
	}

}    
?>