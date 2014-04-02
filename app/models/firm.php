<?php

class Firm extends AppModel
{
	var $name = 'Firm';
	
	function getFirmData($conditionArray = array())
	{
		return $this->find('count',array('conditions' => $conditionArray));
		
	}
	
	function getFirmParentIDData($firmParentID=0,$noConcat=0)
	{
		if($noConcat == 0) {
			return $this->find('first',array('fields'=>array('Firm.parent_id'),'conditions' => array("CONCAT(Firm.firm_name,'(',Firm.parent_id,')')" => trim($firmParentID))));
		} else {
			
			return $this->find('all',array('fields'=>array('Firm.parent_id'),'conditions' => array("OR"=>array("CONCAT(Firm.firm_name,'(',Firm.parent_id,')')" => trim($firmParentID),"LOWER(Firm.firm_name) LIKE" => trim($firmParentID),"Firm.parent_id" => trim($firmParentID)))));
		}
		
	}
	
	function getFirmDataByParentID($firmParentID=0,$firmFields=array(),$firmConditions=array())
	{
		return $this->find('first',array('fields'=>$firmFields,'conditions' => $firmConditions));
		
	}
	 	function getFirmInfoByID($firmId)
	{

		$res = $this->find('first',array('fields'=>array("Firm.firm_name, Firm.parent_id"),'conditions' => array('Firm.parent_id' => $firmId)));
		//return $res['Firm']['firm_name'];
		return $res['Firm']['firm_name'] . '(' . $res['Firm']['parent_id'] . ')';
		
	}

		function getsearchFirmData($firm)
	{	
			
			$res= $this->find('first',array('fields'=>array('Firm.parent_id'),'conditions' =>array('Firm.firm_name LIKE ' => "%".$firm."%"),array('Firm.isactive' => 1)));
			return  $res['Firm']['parent_id'] ;
		
	}
}    

?>