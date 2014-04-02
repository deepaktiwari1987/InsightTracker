<?php
/**
 * This is the master Departmentnames class that will handle the master page for insignt.
 * @author Gaurav Saini
 */
class DepartmentnamesController extends AppController
{
	#Controller Class Name
	var $name = 'Departmentnames';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form');
	
    /**
     * This functiion is to display Departmentnames autocomplete.
     */
    function autoCompleteDepartmentnames()
    {
    	//pr($this->Departmentnames->find('all'));die;
    	//$this->set('Departmentnames', $this->Departmentnames->find('all'));
			$this->set('departmentnames', $this->Departmentnames->find('all', array(
			'conditions' => array(
			'Departmentnames.isactive' => 1,
			'Departmentnames.department_name LIKE' => '%'.$this->data['Departmentnames']['department_name'].'%'
			),
			'fields' => array('id','department_name')
			)));
			
			$this->layout = 'ajax';
    }

    
    function autoComplete()
    {
				$strFirmNames = '';
				$this->layout = 'ajax';
					
				$departmentnames = $this->Departmentnames->find('all', array(
					'conditions' => array(
					'Departmentnames.isactive' => 1,
					'Departmentnames.department_name LIKE' => '%'.$_GET['query'].'%'
				),
					'fields' => array('id','department_name')
				));
				
				foreach($departmentnames as $firm) {
					if(isset($strFirmNames) && trim($strFirmNames) != '') {
						$strFirmNames .= ',' . '"' . $firm['Departmentnames']['department_name'] . '"';
					} else {
						$strFirmNames .= '"' . $firm['Departmentnames']['department_name'] . '"';
					}
				}
				
				$this->set('strDepartmentnames',$strFirmNames);
    }
    

}
?>