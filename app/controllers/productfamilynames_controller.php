<?php
/**
 * This is the master product family names class that will handle all the master pages for product family names insignt.
 * @author Mohit Khurana
 */
class ProductfamilynamesController extends AppController
{
	#Controller Class Name
	var $name = 'Productfamilynames';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form');
	
    /**
     * This functiion is to display product family names autocomplete.
     */
    function autoCompleteProductFamilyNames()
    {
			$this->set('productfamilynames', $this->Productfamilyname->find('all', array(
			'conditions' => array(
				'Productfamilyname.isactive' => 1,
				'Productfamilyname.family_name LIKE' => '%'.$this->data['Productfamilyname']['product_family_id'].'%'
				),
				'fields' => array('id','family_name')
			)));
			
			$this->layout = 'ajax';
    }
     
	/**
    * This functiion is to display Product Family Name autocomplete.
    */
    function autoComplete()
    {
    	$strProductFamilyNames = '';
    	$this->layout = 'ajax';
    	
			$productfamilynames = $this->Productfamilyname->find('all', array(
				'conditions' => array(
				'Productfamilyname.isactive' => 1,
				'Productfamilyname.family_name LIKE' => '%'.$_GET['query'].'%'
				),
				'fields' => array('id','family_name')
			));
			
			
			foreach($productfamilynames as $productfamilyname) {
				if(isset($strProductFamilyNames) && trim($strProductFamilyNames) != '') {
					$strProductFamilyNames .= ',' . '"' . $productfamilyname['Productfamilyname']['family_name'] . '"';
				} else {
					$strProductFamilyNames .= '"' . $productfamilyname['Productfamilyname']['family_name'] . '"';
				}
			}
			
			$this->set('strProductFamilyNames',$strProductFamilyNames);
		
    }

}
?>