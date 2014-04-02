<?php

class Productname extends AppModel
{
	var $name = 'Productname';
   	
   	
 	function getProductName($productName)
	{
		return $this->find('first',array('fields'=>array('Productname.id'),'conditions' => array('Productname.product_name' => $productName)));
		
	}
	
	function getProductNameCodeData($productNameCode)
	{
		return $this->find('first',array('fields'=>array('Productname.id'),'conditions' => array("CONCAT(Productname.product_name,'(',Productname.product_code,')')" => trim($productNameCode))));
		
	}

 	function getProductInfoByID($productId)
	{
		$res = $this->find('first',array('fields'=>array("Productname.product_name","Productname.product_code"),'conditions' => array('Productname.id' => $productId)));
		return $res['Productname']['product_name']."(".$res['Productname']['product_code'].")";
		
	}
		
	function getProductNameByID($familyName,$fields=array(),$conditions=array())
	{
		return $this->find('first',array('fields'=>$fields,'conditions' => $conditions));
		
	}

	function getProductNameByProdId($prodId)
	{
		$result = $this->find('first',array('conditions' => array('id' => $prodId)));
		return $result['Productname']['product_name'];
	}
   	
		function getsearchProductNameData($prodname)
		{			
			$res= $this->find('first',array('fields'=>array('Productname.id'),'conditions' =>array('Productname.product_name LIKE ' => "%".$prodname."%"),array('Productname.isactive' => 1)));
			return  $res['Productname']['id'] ;
			
		}
}    
?>