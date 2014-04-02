<?php
/**
 * This is the master Sellingobstacles class that will handle the master page for insignt.
 * @author Pragya Dave
 */
class SellingobstaclesController extends AppController
{
	#Controller Class Name
	var $name = 'Sellingobstacles';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form');
	
    /**
     * This functiion is to display Sellingobstacles autocomplete.
     */
    function autoCompleteSellingobstacles()
    {
    	//pr($this->Sellingobstacles->find('all'));die;
    	//$this->set('sellingobstacles', $this->Sellingobstacles->find('all'));
			$this->set('sellingobstacles', $this->Sellingobstacle->find('all', array(
			'conditions' => array(
			'Sellingobstacle.isactive' => 1,
			'Sellingobstacle.selling_obstacles LIKE' => '%'.$this->data['Sellingobstacle']['selling_obstacles'].'%'
			),
			'fields' => array('id','selling_obstacles')
			)));
			
			$this->layout = 'ajax';
    }

    
    function autoComplete()
    {
				$strFirmNames = '';
				$this->layout = 'ajax';
					
				$sellingobstacles = $this->Sellingobstacle->find('all', array(
					'conditions' => array(
					'Sellingobstacle.isactive' => 1,
					'Sellingobstacle.selling_obstacles LIKE' => '%'.$_GET['query'].'%'
				),
					'fields' => array('id','selling_obstacles')
				));
				
				foreach($sellingobstacles as $firm) {
					if(isset($strFirmNames) && trim($strFirmNames) != '') {
						$strFirmNames .= ',' . '"' . $firm['Sellingobstacle']['selling_obstacles'] . '"';
					} else {
						$strFirmNames .= '"' . $firm['Sellingobstacle']['selling_obstacles'] . '"';
					}
				}
				
				$this->set('strSellingObstaclesNames',$strFirmNames);
    }
    

}
?>