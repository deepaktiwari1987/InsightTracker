<?php

class Departmentname extends AppModel
{
	var $name = 'Departmentname';
 
  function getDepartmentNames($selectOption = FALSE)
	{
		$result = $this->find('list', array('fields'=>array('Departmentname.id','Departmentname.department_name'), 'conditions' => array('Departmentname.isactive' => 1), 'order' => array('Departmentname.department_name')));
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

}    
?>