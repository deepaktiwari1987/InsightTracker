<?php
/**
 * This is the master issue class that will handle all the master pages for issue insignt.
 * @author Gaurav Saini
 */
class IssuesController extends AppController
{
	#Controller Class Name
	var $name = 'Issues';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form');
	 
	 /**
     * This functiion is to display Issues autocomplete.
     */
    function autoComplete()
    {
				$strIssueNames = '';
				$this->layout = 'ajax';
					
				$issues = $this->Issue->find('all', array(
					'conditions' => array(
					'Issue.isactive' => 1,
					'Issue.issue_title LIKE' => '%'.$_GET['query'].'%'
				),
					'fields' => array('id','issue_title')
				));
				
				foreach($issues as $issue) {
					if(isset($strIssueNames) && trim($strIssueNames) != '') {
						$strIssueNames .= ',' . '"' . $issue['Issue']['issue_title'] . '"';
					} else {
						$strIssueNames .= '"' . $issue['Issue']['issue_title']. '"';
					}
				}
				
				$this->set('strIssueNames',$strIssueNames);
    }

}
?>