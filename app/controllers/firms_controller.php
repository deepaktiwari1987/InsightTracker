<?php
/**
 * This is the master firms class that will handle all the master pages for firms insignt.
 * @author Mohit Khurana
 */
class FirmsController extends AppController
{
	#Controller Class Name
	var $name = 'Firms';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form');
	
    /**
     * This functiion is to display firms autocomplete.
     */
    function autoCompleteFirms()
    {
    	//pr($this->Firm->find('all'));die;
    	//$this->set('firms', $this->Firm->find('all'));
			$this->set('firms', $this->Firm->find('all', array(
			'conditions' => array(
			'Firm.isactive' => 1,
			'Firm.firm_name LIKE' => '%'.$this->data['Firm']['what_firm_name'].'%'
			),
			'fields' => array('parent_id','id','firm_name'),
			'group' => array('parent_id')
			)));
			
			$this->layout = 'ajax';
    }

    /**
     * This functiion is to display firms autocomplete.
     */
    function autoCompleteOtherFirms()
    {
    	//pr($this->Firm->find('all'));die;
    	//$this->set('firms', $this->Firm->find('all'));
			$this->set('firms', $this->Firm->find('all', array(
			'conditions' => array(
			'Firm.isactive' => 1,
			'Firm.firm_name LIKE' => '%'.$this->data['Firm']['who_firm_name'].'%'
			),
			'fields' => array('parent_id','id','firm_name'),
			'group' => array('parent_id')
			)));
			
			$this->layout = 'ajax';
    }
    
    /**
     * This functiion is to display firms autocomplete firm account.
     */
    function autoCompleteFirmsAccountNo()
    {
    	//pr($this->Firm->find('all'));die;
    	//$this->set('firms', $this->Firm->find('all'));
			$this->set('accounts', $this->Firm->find('all', array(
			'conditions' => array(
			'Firm.isactive' => 1,		
			'Firm.account_number LIKE' => '%'.$this->data['Firm']['who_account_no'].'%'
			),
			'fields' => array('parent_id','id','account_number'),
			'group' => array('parent_id')
			)));
			
			$this->layout = 'ajax';
    }
    
    
    function autoComplete()
    {
				$strFirmNames = '';
				$this->layout = 'ajax';
					
				$firms = $this->Firm->find('all', array(
					'conditions' => array(
					'Firm.isactive' => 1,
					'Firm.firm_name LIKE' => '%'.$_GET['query'].'%'
				),
					'fields' => array('parent_id','id','firm_name'),
					'group' => array('parent_id')
				));
				
				foreach($firms as $firm) {
					if(isset($strFirmNames) && trim($strFirmNames) != '') {
						$strFirmNames .= ',' . '"' . $firm['Firm']['firm_name'] . '(' . $firm['Firm']['parent_id'] . ')' . '"';
					} else {
						$strFirmNames .= '"' . $firm['Firm']['firm_name'] . '(' . $firm['Firm']['parent_id'] . ')' . '"';
					}
				}
				
				$this->set('strFirmNames',$strFirmNames);
    }
    
    function autoCompleteAccountNo()
    {
			$strAccountNos = '';
			$this->layout = 'ajax';
				
			$accounts = $this->Firm->find('all', array(
				'conditions' => array(
				'Firm.isactive' => 1,			
				'Firm.account_number LIKE' => '%'.$_GET['query'].'%'
			),
				'fields' => array('parent_id','id','account_number'),
				'group' => array('parent_id')
			));
			
			foreach($accounts as $account) {
				if(isset($strAccountNos) && trim($strAccountNos) != '') {
					$strAccountNos .= ',' . '"' . $account['Firm']['account_number'] . '"';
				} else {
					$strAccountNos .= '"' . $account['Firm']['account_number'] . '"';
				}
			}
			
			$this->set('strAccountNos',$strAccountNos);
    }
    

}
?>