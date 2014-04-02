<?php

class Market extends AppModel
{
	var $name = 'Market';
   	
  function getMarkets()
	{
		$result = $this->find('list',array('fields'=>array('Market.id','Market.market'), 'conditions' => array('Market.isactive' => 1), 'order' => array('Market.market')));
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

 	function getMarketById($marketId)
	{
		$res = $this->find('first',array('fields'=>array("Market.market"),'conditions' => array('Market.id' => $marketId)));
		return $res['Market']['market'];
	}     
	
	 	function getMarketDataByName($market)
	{
		$res = $this->find('first',array('fields'=>array("Market.market, Market.id"),'conditions' => array('Market.market LIKE ' => '%'. $market.'%'),array('Market.isactive' => 1)));
		return $res['Market']['id'];
	}     	
	function getsearchMarketData($market)
	{	
			
			$res= $this->find('first',array('fields'=>array('Market.id'),'conditions' =>array('Market.market LIKE ' => "%".$market."%"),array('Market.isactive' => 1)));
			return  $res['Market']['id'] ;
		
	}

}    
?>