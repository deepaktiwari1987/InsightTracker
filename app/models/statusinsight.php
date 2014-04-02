<?php

class Statusinsight extends AppModel
{
	var $name = 'Statusinsight';

	/**
	* Returns all status records.
	*/
  function getStatusList($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Statusinsight.id','Statusinsight.status'), 'conditions' => array('Statusinsight.isactive' => 1), 'order' => array('Statusinsight.status')));
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
	
	function getStatusById($status_id = '') {
		if($status_id) {
			$res = $this->findById($status_id);
			$result = $res['Statusinsight']['status'];
		}else{
			$result = '';
		}
		return $result;
	}

	function getStatusIdByName($status_id = '')
	{
		$res = $this->find('first',array('fields'=>array('Statusinsight.id'),'conditions' => array('Statusinsight.status' => $status_id),array('Statusinsight.isactive' => 1)));
		return $res['Statusinsight']['id'];
	}
	function getsearchStatusData($status)
	{				
			$res= $this->find('first',array('fields'=>array('Statusinsight.id'),'conditions' =>array('Statusinsight.status LIKE ' => "%".$status."%"),array('Statusinsight.isactive' => 1)));
			return  $res['Statusinsight']['id'] ;		
	}

}    

?>