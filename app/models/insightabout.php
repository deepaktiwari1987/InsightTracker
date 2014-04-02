<?php

class Insightabout extends AppModel
{
	var $name = 'Insightabout';
   	
   	function returnStaticData($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Insightabout.insight_type','Insightabout.insight_type'), 'conditions' => array('Insightabout.isactive' => 1),'order' => ('Insightabout.insight_type')));
		$count = count($result)+1;
		if($selectOption) {
			# Add blank option to dropdown.
			$result[0] = ' ';
		}
		# Set blank value at first.
		asort($result);
		# Get key for "other" in result array.
		$key = array_search('Other', $result);
		if($key == 'Other' ) {
			# Remove 'Other' from middle.
			unset($result[$key]);
			# Add element at last.
			$result[$key] = 'Other';
		}
		return $result;	
	}
   	
}    
?>