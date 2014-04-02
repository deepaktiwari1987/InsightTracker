<?php

class Competitorname extends AppModel
{
	var $name = 'Competitorname';

  function getCompetitors()
	{
		$result = $this->find('list',array('fields'=>array('Competitorname.id','Competitorname.competitor_name'), 'conditions' => array('Competitorname.isactive' => 1), 'order' => 'Competitorname. competitor_name'));
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

	function getCompetitorId($competitorName)
	{
		return $this->find('first',array('fields'=>array('Competitorname.id'),'conditions' => array("Competitorname.competitor_name" => trim($competitorName))));
	}

	function getCompetitorName($competitorId)
	{
		return $this->find('first',array('fields'=>array('Competitorname.competitor_name'),'conditions' => array("Competitorname.id" => trim($competitorId))));
	}

	function getsearchCompetitorNameData($competitorId)
	{				
			$res= $this->find('first',array('fields'=>array('Competitorname.id'),'conditions' =>array('Competitorname.competitor_name LIKE ' => "%".$competitorId."%"),array('Competitorname.isactive' => 1)));
			return  $res['Competitorname']['id'] ;		
	}

}    
?>