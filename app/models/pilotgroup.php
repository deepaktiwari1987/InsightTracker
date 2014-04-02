<?php

class Pilotgroup extends AppModel
{
	var $name = 'Pilotgroup';
	var $hasMany = array(
        'Insight' => array(
		'foreignKey'    => 'user_id',
        'dependent'=> true,
		));

	/**
	* Returns all pilot group records.
	*/
	function getPilotGroups($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Pilotgroup.id','Pilotgroup.name'), 'conditions' => array('Pilotgroup.isactive' => 1), 'order' => array('Pilotgroup.name')));
		if($selectOption) {
			$arrReturn[0] = ' ';
			foreach($result as $key => $val) {
				$arrReturn[$key] = $val;
			}
			return $arrReturn;
		}
		else {
			return $result;
		}
	}
	
	/**
	* Returns all SME records.
	*/
	function getPilotGroupsSME($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Pilotgroup.id','Pilotgroup.name'), 'conditions' => array(
																						//'Pilotgroup.role' => 'S',
																						'Pilotgroup.isactive' => 1
																						), 
																							'order' => array('Pilotgroup.name')));
		if($selectOption) {
			$arrReturn[0] = ' ';
			foreach($result as $key => $val) {
				$arrReturn[$key] = $val;
			}
			return $arrReturn;
		}
		else {
			return $result;
		}
	}
	
	/**
	* Returns pilot group name on the basis of Id.
	*/   	
  function getPilotgroupNameByID($pilotgroupId)
	{
		$res = $this->find('first',array('fields'=>array("Pilotgroup.name"),'conditions' => array('Pilotgroup.id' => $pilotgroupId)));
		return $res['Pilotgroup']['name'];
	}

	/**
	* Returns pilot group id on the basis of name.
	*/   	
  function getPilotgroupIdByName($pilotgroupName)
	{
		$res = $this->find('first',array('fields'=>array("Pilotgroup.name"),'conditions' => array('Pilotgroup.id' => $pilotgroupId)));
		return $res['Pilotgroup']['name'];
	}
 
   	function getsearchPilotgroupUserIData($name)
	{				
		$res= $this->find('first',array('fields'=>array('Pilotgroup.id'),'conditions' =>array('Pilotgroup.name LIKE ' => "%".$name."%"),array('Pilotgroup.isactive' => 1)));
		return  $res['Pilotgroup']['id'] ;		
	}
	
	/**
	*	Fetch Email Address of Moderator.
	**/	
	function getModeratorEmailAddress()
	{	
		$res = $this->find('first', array('fields'=>array('Pilotgroup.id, Pilotgroup.name, Pilotgroup.emailaddress'),
				'conditions' => array('Pilotgroup.role' => 'A',
										'Pilotgroup.isactive' => 1)));
		
		return $res['Pilotgroup']['emailaddress'];
	} 	
	
}    
?>