<?php

class Contenttype extends AppModel
{
	var $name = 'Contenttype';
	
	
	function getContentTypes($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Contenttype.id', 'Contenttype.content_type'), 'conditions' => array('Contenttype.isactive' => 1), 'order' => array('Contenttype.content_type')));

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

	function getContentTypeById($contentId)
	{
		return $this->find('first',array('fields'=>array('Contenttype.id', 'Contenttype.content_type'),'conditions' => array('Contenttype.id' => $contentId)));
	}	
	function getContentTypeIdByName($contentId)
	{
		$res = $this->find('first',array('fields'=>array('Contenttype.id'),'conditions' => array('Contenttype.content_type' => $contentId),array('Contenttype.isactive' => 1)));
		return $res['Contenttype']['id'];
	}
	function getsearchContentTypeIData($content)
	{				
			$res= $this->find('first',array('fields'=>array('Contenttype.id'),'conditions' =>array('Contenttype.content_type LIKE ' => "%".$content."%"),array('Contenttype.isactive' => 1)));
			return  $res['Contenttype']['id'] ;		
	}

}    
?>