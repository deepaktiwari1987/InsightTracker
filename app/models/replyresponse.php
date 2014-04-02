<?php

class Replyresponse extends AppModel
{
	var $name = 'Replyresponse';
	//var $useTable = false;
	 var $belongsTo = array(

		  'Pilotgroup' => array(
            'className'     => 'Pilotgroup',
	        'foreignKey'    => 'user_id',
			'fields' => 'Pilotgroup.id,Pilotgroup.name'
	        ),

	    );
	function getResponseReplies($insight_id='')
	{
		$res = $this->find('all',array(	'conditions' => array(
											'Replyresponse.insight_id' => $insight_id,
											'Replyresponse.isactive' => 1
											),
											'order' => 'Replyresponse.date_submitted desc'
											));
		return $res;
	}
	
	/**
	*	
	**/
	function getRecentResponseForInsight($insight_id='')
	{
		$res = $this->find('all',array(	'conditions' => array(
											'Replyresponse.insight_id' => $insight_id,
											'Replyresponse.isactive' => 1
											),
											'order' => 'Replyresponse.id desc',
											'limit' => 1
											));
		return $res;
	}
	
}    

?>