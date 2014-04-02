<?php

class Productfamilyname extends AppModel
{
	var $name = 'Productfamilyname';
   	
  function getProductFamilyNames($selectOption = FALSE)
	{
		$result = $this->find('list', array('fields'=>array('Productfamilyname.id','Productfamilyname.family_name'), 'conditions' => array('Productfamilyname.isactive' => 1), 'order' => array('Productfamilyname.family_name')));
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
	
   	function getProductFamilyName($familyName)
	{
		return $this->find('first', array('fields'=>array('Productfamilyname.id'),'conditions' => array('Productfamilyname.family_name' => $familyName)));
		
	}

   	function getProductFamilyInfoById($familyNameId)
	{
		return $this->find('first', array('fields'=>array('Productfamilyname.family_name'),'conditions' => array('Productfamilyname.id' => $familyNameId)));
		
	}
	
  	function getProductFamilyNameByID($familyName,$fields=array(),$conditions=array())
	{
		return $this->find('first', array('fields'=>$fields,'conditions' => $conditions));
		
	}

	function getsearchProductfamilynameData($prodfname)
	{			
		$res= $this->find('first',array('fields'=>array('Productfamilyname.id'),'conditions' =>array('Productfamilyname.family_name LIKE ' => "%".$prodfname."%"),array('Productfamilyname.isactive' => 1)));
		return  $res['Productfamilyname']['id'] ;
		
	}
}    
?>