<?php

class Issue extends AppModel
{
	var $name = 'Issue';
	//var $useTable = false;
	
	/**
	* Returns all Issue records.
	*/
  function getIssues($selectOption = FALSE)
	{
		$result = $this->find('list',array('fields'=>array('Issue.id','Issue.issue_title'), 'conditions' => array('Issue.isactive' => 1), 'order' => array('Issue.issue_title')));
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
	*	This method will fetch issues on the basis of combination.
	**/	
	function getIssuesForCombination($product_family_id='', $practice_area_id='',  $selling_obstacle_id='')
	{
		$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
																'Issue.product_family_id' => $product_family_id,
																'Issue.practice_area_id' => $practice_area_id,
																'Issue.selling_obstacle_id' => $selling_obstacle_id,
																'Issue.isactive' => 1
																),
														'order' => 'Issue.date_submitted desc'
														));		
		return $res;
	} 
	
	
	/**
	*	This method will fetch issues on the basis of Product familiy id and exclude the records which has been displayed earlier.
	**/
	function getIssuesForProductFamily($product_family_id='', $IssuesToExclude = '')
	{
		if($IssuesToExclude!=''){
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
																'Issue.product_family_id' => $product_family_id,
																'Issue.isactive' => 1,
																'Issue.id NOT IN ('.$IssuesToExclude.')'
																),
														'order' => 'Issue.date_submitted desc'
																));	
		}
		else
		{
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
																'Issue.product_family_id' => $product_family_id,
																'Issue.isactive' => 1
																),
														'order' => 'Issue.date_submitted desc'
																));	
		}
		
		return $res;
	}
	
	/**
	*	This method will fetch issues on the basis of PracticeArea id and exclude the records which has been displayed earlier.
	**/
	function getIssuesForPracticeArea($practice_area_id='', $IssuesToExclude = '')
	{
		if($IssuesToExclude!=''){
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
																'Issue.practice_area_id' => $practice_area_id,
																'Issue.isactive' => 1,
																'Issue.id NOT IN ('.$IssuesToExclude.')'
																),
														'order' => 'Issue.date_submitted desc'
																));
		}
		else
		{
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
																'Issue.practice_area_id' => $practice_area_id,
																'Issue.isactive' => 1
																),
														'order' => 'Issue.date_submitted desc'
																));
		}
		return $res;
	}
	
	/**
	*	This method will fetch issues on the basis of Selling Obstacle id and exclude the records which has been displayed earlier.
	**/
	function getIssuesForSellingObstacle($selling_obstacle_id='', $IssuesToExclude = '')
	{
		if($IssuesToExclude!=''){
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
																'Issue.selling_obstacle_id' => $selling_obstacle_id,
																'Issue.isactive' => 1,
																'Issue.id NOT IN ('.$IssuesToExclude.')'
																),
														'order' => 'Issue.date_submitted desc'
																));
		}
		else
		{
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
																'Issue.selling_obstacle_id' => $selling_obstacle_id,
																'Issue.isactive' => 1																
																),
														'order' => 'Issue.date_submitted desc'
																));
		}
		return $res;
	}
	
	/**
	*	This method will fetch rest of the issues and exclude the records which has been displayed earlier.
	**/
	function getRestIssues($IssuesToExclude = '')
	{
		if($IssuesToExclude!=''){
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
															'Issue.isactive' => 1,
																'Issue.id NOT IN ('.$IssuesToExclude.')'
																),
														'order' => 'Issue.date_submitted desc'
														));
		}
		else
		{
			$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
															'Issue.isactive' => 1
																),
														'order' => 'Issue.date_submitted desc'
																));
		}
		return $res;
	}
	
	/**
	*	This method will fetch the Issue based on Issue title.
	**/
	function getIssuesByTitle($IssueTitle = '')
	{
		
		$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
															'Issue.issue_title' => $IssueTitle,
															'Issue.isactive' => 1
												)));
		return $res;
	}
	
	/**
	*	This method will fetch the Issue based on Issue title and Issue Id.
	**/
	function getIssuesByTitle_Id($IssueTitle = '', $IssueId = '')
	{
		
		$res = $this->find('all',array('fields'=>array("Issue.id, Issue.issue_title, Issue.issue_description"),
														'conditions' => array(
															'Issue.issue_title' => $IssueTitle,
															'Issue.id NOT IN ('.$IssueId.')',	
															'Issue.isactive' => 1
												)));
		return $res;
	}
	/**
	*	This method will fetch the Issue id based on Issue title.
	**/
	function getIssueId($IssueTitle = '')
	{
		return $this->find('first',array('fields'=>array('Issue.id'),'conditions' => array("Issue.issue_title" => trim($IssueTitle))));
	}
}    

?>