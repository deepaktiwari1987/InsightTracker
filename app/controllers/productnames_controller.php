<?php
/**
 * This is the master Productnames class that will handle all the master pages for Productnames insignt.
 * @author Mohit Khurana
 */
class ProductnamesController extends AppController
{
	#Controller Class Name
	var $name = 'Productnames';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form');
	
    /**
     * This functiion is to display Productnames autocomplete.
     */
    function autoCompleteProductNames()
    {
    	//pr($this->Firm->find('all'));die;
    	//$this->set('firms', $this->Firm->find('all'));
			$this->set('productnames', $this->Productname->find('all', array(
			'conditions' => array(
			'Productname.isactive' => 1,
			'Productname.product_name LIKE' => '%'.$this->data['Productname']['product_id'].'%'
			),
			'fields' => array('product_code','id','product_name')
			)));
			
			$this->layout = 'ajax';
    }
    
    
    function autoComplete()
    {
				$strProductNames = '';
				$this->layout = 'ajax';
				$prodfamilyid = 0;
				if($_GET['prodfamilyname'] > 0) {
				# Import Product Family Name model
					/*App::import('Model', 'Productfamilyname');
					# Create Product Family Name model object
					$this->Productfamilyname = new Productfamilyname();
					# Get array for product family name id.
					$arrProductFamilyNameID = $this->Productfamilyname->getProductFamilyName($_GET['prodfamilyname']);
					$prodfamilyid = $arrProductFamilyNameID['Productfamilyname']['id'];*/
					$prodfamilyid = $_GET['prodfamilyname'];
				
				$productNames = $this->Productname->find('all', array(
					'conditions' => array(
					'Productname.isactive' => 1,
					'Productname.product_name LIKE' => '%'.$_GET['query'].'%',
					'Productname.product_family_id' => $prodfamilyid
					),
					'fields' => array('product_code','id','product_name','')
				));
				
			
				foreach($productNames as $productName) {
					if(isset($strProductNames) && trim($strProductNames) != '') {
						$strProductNames .= ',' . '"' . $productName['Productname']['product_name'] . '(' . $productName['Productname']['product_code'] . ')' . '"';
					} else {
						$strProductNames .= '"' . $productName['Productname']['product_name'] . '(' . $productName['Productname']['product_code'] . ')' . '"';
					}
				}
			}	
				$this->set('strProductNames',$strProductNames);
		
    }
    

}
?>