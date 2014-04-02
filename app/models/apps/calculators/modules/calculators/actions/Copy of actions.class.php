<?php

/**
 * calculators actions.
 *
 * @package    LexisCalculate
 * @subpackage calculators
 * @author     Daniel Mullin daniel.mullin@lexisnexis.co.uk 
 * @author     Daniel Mullin email@danielmullin.com 
 * @version    0.1
 *
 */

class calculatorsActions extends sfActions
{
  
	/**
	 * method to return the sessions calculations as an array
	 * 
   * @package     LexisCalculate
   * @subpackage  calculators
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @params      int $calculatorSessionId
   * @returns     array calculations
   * 
   * @todo        improve model object utilisation
   * 							extrapalate caclulations
   * 							add xml file config for complex calculation defaults eg net income
	*/
	
	public $decimalPlaces = 2;
	
	protected function calculations($calculatorSessionId)
	{

		//echo 'function calculations(' . $calculatorSessionId . ')' . "\n\n"; // debug
 		
		// init the return array
		if(empty($calculatorSessionId))
		{
			$calculatorSessionId = $this->getRequest()->getCookie('symfony');
		}
		
		$return = array();

		// get the calculators order 
		
		
		$query = Doctrine_Query::create()
			->select('c.calculator_id, MAX(c.last_updated) latest')
            ->from('Calculation c')
            ->where('c.calculator_session_id = ?', $calculatorSessionId)
            ->andwhere('c.status_code = 700')
            ->groupBy('c.calculator_id')
            ->orderBy('latest desc');
              
		$calcs = $query->execute();
		
		// load the calculations

		$query = Doctrine_Query::create()
            ->from('Calculation cals')
            ->where('cals.calculator_session_id = ?', $calculatorSessionId)
            ->andwhere('cals.status_code = 700')
            ->orderBy('cals.calculator_id, cals.last_updated desc');
              
    	$calculations = $query->execute();
    
	    foreach ($calcs as $calcI => $calc)
	    {
	    	
		    foreach ($calculations as $i => $Calculation)
		    {
	
		    	if($calc->getCalculatorId() == $Calculation->getCalculatorId())
		    	{
	
			    	// add the calculation id to the return array
			    	
			    	$return[$i]['calculationId']   = (int) $Calculation->getCalculationId(); // calculationId
			    	
			    	// add the calculator id to the return array
			    	
			    	$return[$i]['calculatorId']   = (int) $Calculation->getCalculatorId(); // calculatorId
			    	
			    	// load the calculator 
			                     
					if(!$Calculator = Doctrine::getTable('Calculator')->find($Calculation->getCalculatorId()))
				    {
				      	// boom! the calculator no longer exists?
				       	$return[$i]['error']        = 1;
				      	$return[$i]['errorMessage'] = 'ERROR: Calculator no longer exists';
				                      
				    }
				    else
				    {
			
				        // add the calculator name to the return array
				      
				        $return[$i]['calculatorName']   = $Calculator->getName(); // Name
				      	
				        // init the calculator params array
				        
				      	$calculatorParams = array();
				
				      	// addd the calculators id to the params
				      	
				      	$calculatorParams['id']    = $return[$i]['calculatorId'];
				      	
				      	// load the calculation params
				      	
				        $query = Doctrine_Query::create()
				            ->from('CalculationParam capa')
				            ->where('capa.calculation_id = ?', $Calculation->getId())
				            ->orderBy('capa.param_id ASC');
				    
				       //echo $query->getSqlQuery();

				       $calculationParams = $query->execute();
				
				        foreach($calculationParams as $CalculationParam)
				        {
				        	
				        	// add each param / value
				        	
				        	$calculatorParams['value'][$CalculationParam->getParamId()] = $CalculationParam->getParamValue();
				        
				// print_r($calculatorParams['value']);
				
				        }
				        
				        // add the calculators response to the return array
				        
				      	$calculateFunctionName = "calculate" . str_replace(' ', '', str_replace('/', '', $Calculator->getName()));
				       
				        $return[$i]['calculation'] =  $this->$calculateFunctionName($calculatorParams);
			        
					}
		    		
		    	}
		    }
	    }
		
		return $return;
	}
	
  /**
   * method to wrap the load of the calculator model
   *
   * @package     LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @param       int   calculatorId  
   * @returns     array calculator
   * 
   * @todo        SELECT calc.id, calc.name, cavm.meta_name, cavm.meta_data FROM calculator_value_meta cavm JOIN calculator_value cava ON cava.id = cavm.calculator_value_id JOIN calculator calc ON calc.id = cava.calculator_id ORDER BY calc.id ASC, calculator_value_id ASC
   */
  
  protected function calculator($calculatorId)
  {
//echo 'function calculator(' . $calculatorId . ')' . "\n\n"; // debug
 // load the calculator
    $calculator['id'] = $calculatorId;
    
    if(!$Calculator = Doctrine::getTable('Calculator')->find($calculatorId))
    {
      
    	// calculator does not exist
    	
      $calculator['error']        = 1;
      $calculator['errorMessage'] = 'ERROR: Calculator does not exist';

      return $calculator;
      
    }
    elseif($Calculator->status_code != 700)
    {
    
    	// calculator is not active
    	
      $calculator['error']        = 1;
      $calculator['errorMessage'] = 'ERROR: Calculator is not active';
    
      return $calculator;
      
    }
    
    // calculator name
    
    $calculator['name'] = $Calculator->getName();
    
    $calculator['css'] = strtolower(str_replace(' ', '-', str_replace('/ ', '', $Calculator->getName())));
    
    // load the calculator values
    
    $query = Doctrine_Query::create()
            ->from('CalculatorValue cava')
            ->where('cava.calculator_id = ?', $calculatorId)
            ->orderBy('cava.display_order ASC');
             //->useResultCache(true); // use in production environment
    
    $calculatorValues = $query->execute();
         
    foreach ($calculatorValues as $i => $CalculatorsValue)
    {

//echo $CalculatorsValue->getId();
    	
    	// load the calculators meta data
    	
    	$query = Doctrine_Query::create()
            ->from('CalculatorValueMeta cavm')
            ->where('cavm.calculator_value_id = ?', $CalculatorsValue->getId())
            ->andwhere('cavm.status_code = 700');
    
      $calculatorValueMetas = $query->execute();

	  
      $iOption = 0;
      
      foreach ($calculatorValueMetas as $CalculatorsValueMeta)
      {
     
		if($CalculatorsValueMeta->getMetaName() == 'option')
      	{

      		// option as array
      		
			$calculator['values'][$i][$CalculatorsValueMeta->getMetaName()][++$iOption]['option'] = $CalculatorsValueMeta->getMetaData();
      		
      	}
      	elseif($CalculatorsValueMeta->getMetaName() == 'option-value')
      	{
      		
      		$calculator['values'][$i]['option'][$iOption]['value'] = $CalculatorsValueMeta->getMetaData();
      		
      	}
      	else
      	{
        
      		$calculator['values'][$i][$CalculatorsValueMeta->getMetaName()] = $CalculatorsValueMeta->getMetaData();
    
      	}

      }
    
    }
    
//print_r($calculator);
    
    return $calculator;
    
  }
  
  /**
   * method to return active calculators
   *
   * @package     LexisCalculate
   * @subpackage  calculators
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @returns     list of calculators as an array
   * 
   * @todo        
   */
  
  protected function calculators()
  {
  	//print_r($_COOKIE['caseInfo']);
//echo 'function calculators()' . "\n\n"; // debug
 
  	// load the calculators with an active status
  	 
    $query = Doctrine_Query::create()
            ->from('Calculator calc')
            ->where('calc.status_code = 700')
            ->orderBy('calc.display_order ASC')
            ;//->useResultCache(true);
    
    $calculators = $query->execute();
    
    $return = array();
      
    foreach ($calculators as $Calculator)
    {
      
    	// add the calculator to the return array
    	
      $return[$Calculator->getId()] = $Calculator->getName();         

    }
    
    return $return;
  
  }

  /**
   * method to add the calculation to the log table
   *
   * @package     LexisCalculate
   * @subpackage  calculators
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @params      int   $calculatorSessionId
   * @params      array $params
   * 
   * @todo        
   */
  
  protected function logCalculation($calculatorSessionId, $params)
  {

//echo 'logCalculation(' . $calculatorSessionId . ', ' . print_r($params) . ')' . "\n\n";

//  	if(!$Calculation = Doctrine::getTable('Calculation')->find($calculatorSessionId))
	//if(!$Calculation = Doctrine::getTable('Calculation')->findOneByCalculatorSessionId($calculatorSessionId))
    //{
          
      $Calculation = new Calculation();
      $Calculation->setStatusCode(700);
      $Calculation->setCalculationId($params['calculationId']);
      $Calculation->setCalculatorId($params['id']);
      $Calculation->setCalculatorSessionId('' . $calculatorSessionId . '');
      $Calculation->setLastUpdated(date('Y-m-d H:i:s'));
      $Calculation->save();
          
      foreach ($params['value'] AS $paramId => $paramValue)
      {
        
        $CalculationParam = new CalculationParam();
        $CalculationParam->setCalculationId($Calculation->getId());
        $CalculationParam->setParamId($paramId);
        $CalculationParam->setParamValue($paramValue);
        $CalculationParam->setLastUpdated(date('Y-m-d H:i:s'));
        $CalculationParam->save();

        unset($CalculationParam);
        
      }
        
   // }
  	
  }
  
  /**
   * EXECUTES
   */

  /**
   * calculation wrapper
   *
   * @package     LexisCalculate
   * @subpackage  calculators
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @params      object $request
   * 
   * @todo        fix base dir for fpdf class
   * 
   */
  
	public function executeCalculations(sfWebRequest $request)
	{
  		$httpHost = "http://" . $_SERVER['HTTP_HOST'];
  		
  		//print $httpHost;die;
	// echo executeCalculations()
  	
  	switch($this->getRequestParameter('format', 0))
  	{
		  	
			case 'doc':
  			
			//$output = "<html><body style='font-family:Verdana;font-size:11pt;'><h1><font style='font-size:24pt;'>Lexis</font><sup>&reg;</sup></font><font style='font-size:24pt;'>Calculate</font></h1>";
			
						
			$output = "<html>
						<body style='font-family:Verdana;font-size:11pt;'>
						<table cellspacing='0' cellpadding='0'>
							<tr style='v-align:bottom;'>
								<td><img src='" . $httpHost . "/images/logo.gif' height='50' width='202'></td>
							</tr>
						</table>";
			
			$output .= "<p><font style='font-size:13pt;font-family:Verdana;font-weight:bold;'>".$_COOKIE['caseInfo']."</font></p>";

			//print $output;die;
			
  			// now add each calculation block
        
        	$i = array();
       
        	foreach ($this->calculations($this->getRequest()->getCookie('csi')) as $calculation) 
        	{
        	
				/*echo "<pre>";
		   		print_r($calculation);
		  	 	echo "</pre>";
				*/
        		$iHead[$calculation['calculatorId']]++;
          
          	 	// add calculator title
          
				if($iHead[$calculation['calculatorId']] == 1)
	          	{
	          	
	          		if($calculation['calculatorId']==7)
					{
						$calculation['calculatorName']="Past ".strtolower($calculation['calculatorName']);
					}	
					$output .= '<h3><font style="font-size:15pt;font-family:Verdana;"><u>' . $calculation['calculatorName'] . '</u></font></h3>';
	
	          	}
	          
				// display the calculations
	          
				// switch in switch bad, if in switch is good
	          
				// VAT
	          
				
				if($calculation['calculatorId'] == 1)
	          	{
	          	
		          		
		    		$output.="	<table style='font-family:Verdana;font-size:11pt;width:640px;'>
		    						<tr>
		    							<td width='40%'><b>Item</b></td>
		    							<td width='2%'><b>:</b></td>
		    							<td width='58%' style='text-align:right;'><b>".$calculation['calculation']['params']['value'][0]."</b></td>
		    						</tr>
		    						<tr>
		    							<td>Invoice date</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>".$calculation['calculation']['params']['value'][3]."</font></td>
		    						</tr>
		    						<tr>
		    							<td>Net cost</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['net']."</font></td>
		    						</tr>
		    						<tr>
		    							<td>VAT (@".$calculation['calculation']['result']['vatRate']."%)</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['vat']."</font></td>
		    						</tr>
		    						<tr>
		    							<td><b>Total cost</b></td>
		    							<td><b>:</b></td>
		    							<td style='text-align:right;'><b><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['total']."</font></b></td>
		    						</tr>
		    					</table><br />" . "\n";
				}
	          
	          	// Adjust Percentage
	          
	        	if($calculation['calculatorId'] == 2)
	          	{
		    		$output.="	<table style='font-family:Verdana;font-size:11pt;width:640px;'>
		    						<tr>
		    							<td width='40%'><b><u>".$calculation['calculation']['params']['value'][0]."</u></b></td>
		    							<td width='2%'></td>
		    							<td width='58%' style='text-align:right;'></td>
		    						</tr>
		    						<tr>
		    							<td>Amount</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['start']."</font></td>
		    						</tr>
		    						<tr>
		    							<td><font style='font-family:Verdana;font-size:11pt;'>".($calculation['calculation']['params']['value'][2]?"Increase":"Decrease")." of ".$calculation['calculation']['params']['value'][3]."%</font></td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['change']."</font></td>
		    						</tr>
		    						<tr>
		    							<td><b>New amount</b></td>
		    							<td><b>:</b></td>
		    							<td style='text-align:right;'><b><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['final']."</font></b></td>
		    						</tr>
		    					</table><br />" . "\n";
	            
				}
          
	     	    // Interest on General Damages 
	          
	          if($calculation['calculatorId'] == 3)
	          {
	          	
		          		
		    		$output.="	<table style='font-family:Verdana;font-size:11pt;width:640px;'>
		    						<tr>
		    							<td width='40%'>Injury</td>
		    							<td width='2%'>:</td>
		    							<td  width='58%' style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>".$calculation['calculation']['params']['value'][0]."</font></td>
		    						</tr>
		    						<tr>
		    							<td>General damages</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['amount']."</font></td>
		    						</tr>
		    						<tr>
		    							<td>Interest rate</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>".$calculation['calculation']['params']['value'][2]."%</font></td>
		    						</tr>
		    						<tr>
		    							<td>Date of service</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>".$calculation['calculation']['params']['value'][3]."</font></td>
		    						</tr>
		    						<tr>
		    							<td>Calculate to</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>".$calculation['calculation']['params']['value'][4]."</font></td>
		    						</tr>
		    						<tr>
		    							<td>Period</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>".$calculation['calculation']['result']['daysInPeriod']." days</font></td>
		    						</tr>
		    						<tr>
		    							<td>Aggregate percentage rate</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>".$calculation['calculation']['result']['cumulativeInterest']."%</font></td>
		    						</tr>
		    						<tr>
		    							<td>Interest</td>
		    							<td>:</td>
		    							<td style='text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['totalInterest']."</font></td>
		    						</tr>
		    						<tr>
		    							<td style='font-weight:bold;'>General damages with interest</td>
		    							<td style='font-weight:bold;'>:</td>
		    							<td style='font-weight:bold;text-align:right;'><font style='font-family:Verdana;font-size:11pt;'>&pound;".$calculation['calculation']['result']['totalIncludingInterest']."</font></td>
		    						</tr>
		    					</table><br />" . "\n";
								
								
	          	}
          
				// Interst on Judgment Debt
          
        		if($calculation['calculatorId'] == 4)
          		{
          		
          			//print_r($calculation['calculation']);
          		
	          		//exit($calculation['calculation']['result']['totalJudgmentAndInterest']);
	          	      			          		
					$output .= '<table style="font-family:Verdana;font-size:11pt;width:640px;">';
					
					$output .= '<tr><td width="40%"><font style="font-family:Verdana;font-size:11pt;">Total judgment debt</td><td width="2%">:</td><td width="58%" align=right> &pound;' . $calculation['calculation']['result'][0]['amount'] . '</font></td></tr>' . "\n";
					
					$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Judgment date</td><td width="2%">:</td><td width="58%" align=right> ' . $calculation['calculation']['result'][0]['params']['value'][3] . '</font></td></tr>' . "\n";
					
					$output .= '<tr><td colspan=3><font style="font-family:Verdana;font-size:11pt;"><b>'.$calculation['calculation']['result'][0]['params']['value'][0].'</b></font></td></tr>' . "\n";
										
					$numCalculations = count($calculation['calculation']['result']);
					
					if($numCalculations > 1)
					{
						$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Payments</font></td><td></td><td></td></tr>' . "\n";
					}
				
					$i = 0;
					
					$output .= '<tr><td colspan=3><table align=right style="width:615px;">';
					
					foreach($calculation['calculation']['result'] as $row)
	          		{
	          			$i++;
	          			
	          			$output .= '<tr><td width="24%"><font style="font-family:Verdana;font-size:11pt;">Payment '.($numCalculations>1?$i:'date').'<br>(or proposed date)</font></td><td nowrap><font style="font-family:Verdana;font-size:11pt;">' . $row['params']['value'][4] . '</font>&nbsp;</td><td>&nbsp;<font style="font-family:Verdana;font-size:11pt;">Amount of payment</font></td><td nowrap align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $row['result']['payment'] . '</font></td></tr>' . "\n";
	            
	          			$output .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td><font style="font-family:Verdana;font-size:11pt;">Simple judgment interest @ 8% (' . $row['result']['daysInPeriod'] . ' days)</font></td><td nowrap align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $row['result']['totalInterest'] . '</font></td></tr>' . "\n";
	            
	          			$output .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td><font style="font-family:Verdana;font-size:11pt;">Judgment balance</font></td><td nowrap align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $row['result']['judgmentBalance'] . '</font></td></tr>' . "\n";
	            
	          		}
					$output .= '</table></td></tr>';
					
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;"><b>Total judgment interest</b></font></td><td>:</td><td style="text-align:right;"><font style="font-family:Verdana;font-size:11pt;"><b>&pound;' . $calculation['calculation']['totals']['judgmentInterest'] . '</b></font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;"><b>Total judgment outstanding</b></font></td><td>:</td><td style="text-align:right;"><font style="font-family:Verdana;font-size:11pt;"><b>&pound;' . $calculation['calculation']['totals']['judgmentOutstanding'] . '</b></font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;"><b>Total judgment interest outstanding</b></font></td><td>:</td><td style="text-align:right;"><font style="font-family:Verdana;font-size:11pt;"><b>&pound;' . $calculation['calculation']['totals']['judgmentInterestOutstanding'] . '</b></font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;"><b>Total judgment and interest</b></font></td><td>:</td><td style="text-align:right;"><font style="font-family:Verdana;font-size:11pt;"><b>&pound;' . $calculation['calculation']['totals']['totalJudgmentAndInterest'] . '</b></font></td></tr></table><br />' . "\n";
	          		
	          	}
          
	          	// Mileage
          
				if($calculation['calculatorId'] == 5)
          		{
          	
          			$output .= '<h4><font style="font-size:11pt;"><u>Travel (at ' . $calculation['calculation']['params']['value'][3] . ' pence per mile)</u></font></h4></p>' . "\n";
            
          			$output .= '<p>';
          		
					if(data.result.journeys == 1)
					{
				
						$output .= '1 trip ';
				
					}
					else
					{
					
						$output .= $calculation['calculation']['result']['journeys'] . ' trips ';
					
					}
				
					$output .= 'to ' . $calculation['calculation']['params']['value'][0] . ' (' . $calculation['calculation']['result']['journeyDistance'] . ' mile ';
				
					if($calculation['calculation']['params']['value'][2])
					{
					
						$output .= 'round trip)';
					
					}
					else
					{
					
						$output .= 'trip)';
					
					}
					
					if($calculation['calculation']['params']['value'][8] != '' && $calculation['calculation']['params']['value'][9] != '')
					{
					
						$output .= ' between ' . $calculation['calculation']['params']['value'][8] . ' and the ' . $calculation['calculation']['params']['value'][9];
					
					}
					
					if($calculation['calculation']['params']['value'][8] != '' && $calculation['calculation']['params']['value'][9] == '')
					{
						
						$output .=' on the ' . $calculation['calculation']['params']['value'][8];
					
					}
					
					$output .= ' : £' . $calculation['calculation']['result']['total'] . '</p>' . "\n";
	            
	          }
	          
	          	// Net Income
	          
				if($calculation['calculatorId'] == 6)
				{
	          	
	          		$output .= '<table style="font-family:Verdana;font-size:11pt;width:640px;"><tr><td><h4><font style="font-family:Verdana;font-size:11pt;"><u>' . $calculation['calculation']['params']['value'][0] . '</u></font></h4></td></tr></table>' . "\n";
	            
	          		//$output .= '<tr><td>Interest Rate [%]</td><td style="text-align: right;">£</td><td>' . $calculation['calculation']['params']['value'][2] . '</td></tr>' . "\n";
	            
	          		//$output .= '<tr><td>Days In Period[%]</td><td>&nbsp;</td><td style="text-align: right;">' . $calculation['calculation']['result']['daysInPeriod'] . '</td></tr>' . "\n";
	          		
	          		//$output .= '<tr><td>Total interest:</td><td>£</td><td style="border-bottom: 1px solid #666666; border-top: 1px solid #666666; text-align: right;">' . $calculation['calculation']['result']['totalInterest'] . '</td></tr></table>' . "\n";
	            
					$period = $calculation['calculation']['params']['value'][2];
					if($period=='13weeks')
					{
						$period = "Last 13 Weeks";
					}
	          		
	          		$output .= '
		          		<table style="font-family:Verdana;font-size:11pt;width:640px;">
		          			<tr>
		          				<td width="40%">Employee Name</td>
		          				<td width="2%">:</td>
		          				<td width="58%" style="text-align:right;">' . $calculation['calculation']['params']['value'][0] . '</td>
		          			</tr>
		          			<tr>
		          				<td>Gross earnings per '.$period.'</td>
		          				<td>:</td>
		          				<td style="text-align:right;">&pound;' . $calculation['calculation']['result']['gross'] . '</td>
		          			</tr>
		          			<tr>
		          				<td>Tax Year</td>
		          				<td>:</td>
		          				<td style="text-align:right;">' . $calculation['calculation']['result']['financialYear'] . '</td>
		          			</tr>
		          		</table>
	          		' . "\n";
          		
	          		$output .= '<table style="font-family:Verdana;font-size:11pt;width:640px;">';
	          		
	          		$output .= '<tr><th align=left><font style="font-family:Verdana;font-size:11pt;">Wage summary</font></th><th align="right"><font style="font-family:Verdana;font-size:11pt;">Yearly</font></th><th align="right"><font style="font-family:Verdana;font-size:11pt;">Monthly</font></th><th align="right"><font style="font-family:Verdana;font-size:11pt;">Weekly</font></th></tr>' . "\n";
	            
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Gross pay</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['gross']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['monthGross']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['weekGross']  . '</font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Tax free allowances</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['personalAllowance']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['monthPersonalAllowance']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['weekPersonalAllowance']  . '</font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Taxable earnings</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['taxableIncome']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['monthTaxableIncome']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['weekTaxableIncome']  . '</font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Starting rate tax @ ' . number_format($calculation['calculation']['result']['startingTaxRate'])  . '%</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['startingRateTax']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['monthStartingRateTax']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['weekStartingRateTax']  . '</font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Basic rate tax @ ' . number_format($calculation['calculation']['result']['basicTaxRate'])  . '% </font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['basicRateTax']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['monthBasicRateTax']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['weekBasicRateTax']  . '</font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Higher rate tax @ ' . number_format($calculation['calculation']['result']['higherTaxRate'])  . '%</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['higherRateTax']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['monthHigherRateTax']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['weekHigherRateTax']  . '</font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">National insurance</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['nationalInsurance']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['monthNationalInsurance']  . '</font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;">&pound;' . $calculation['calculation']['result']['weekNationalInsurance']  . '</font></td></tr>' . "\n";
	          		
	          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;"><b>Net earnings estimate</b></font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;"><b>&pound;' . $calculation['calculation']['result']['netPay']  . '</b></font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;"><b>&pound;' . $calculation['calculation']['result']['monthNetPay']  . '</b></font></td><td align="right"><font style="font-family:Verdana;font-size:11pt;"><b>&pound;' . $calculation['calculation']['result']['weekNetPay']  . '</b></font></td></tr></table><br />' . "\n";
	          		
	          }
          
          	// Periodic
          
			if($calculation['calculatorId'] == 7)
           	{
          	
          		
				$output .= "<table style='font-family:Verdana;font-size:11pt;width:640px;'>";
				
				$output .= '<tr><td width="40%"><b><u>' . $calculation['calculation']['params']['value'][0] . '</u></b></td><td width="2%"></td><td width="58%"></td>' . "\n";
          		
          		$output .= '<tr><td><font style="font-family:Verdana;font-size:11pt;">Cost</font></td><td> :</td><td style="text-align:right"> £' . $calculation['calculation']['result']['amount'] . '</td></tr>' . "\n";
            
          		$output .= '<tr><td>Frequency</td><td> :</td><td style="text-align:right">  ' . $calculation['calculation']['params']['value'][2] . ' per ' . $calculation['calculation']['params']['value'][3] . '</td></tr>' . "\n";
          		
          		$output .= '<tr><td>Period</td><td> :</td><td style="text-align:right"> ' . $calculation['calculation']['params']['value'][4] . ' to ' . $calculation['calculation']['params']['value'][5] . ' &nbsp;  ('.$calculation['calculation']['result']['frequencyCount'].' '.$calculation['calculation']['params']['value'][3].')</td></tr>' . "\n";
          		
          		$output .= '<tr><td>Total units of '.$calculation['calculation']['params']['value'][0].' </td><td>:</td><td style="text-align:right"> ' . $calculation['calculation']['result']['events'] . '</td></tr>' . "\n";
				
				$output .= '<tr><td><b>Total</b></td><td><b>:</b></td><td style="text-align:right"> <b>£' . $calculation['calculation']['result']['total'] . '</b></td></tr>' . "\n";
            	
				$output .= "</table><br />" . "\n";
          	}	
          	
			// special damages calculator 
        	if($calculation['calculatorId'] == 8)
          	{
          		
          		  		
          		//exit($calculation['calculation']['result']['totalJudgmentAndInterest']);
          	
      			
          		$totalDays = 0;
          		$output .= "<table style='font-family:Verdana;font-size:11pt;width:640px;'>";
				
				foreach($calculation['calculation']['result'] as $row)
          		{
          			$totalDays +=  $row['result']['daysInPeriod'];
          		}
	          		
				$output .= '<tr><td width="40%"><h4><u><font style="font-size:11pt;">' . $calculation['calculation']['params']['value'][0] . '</font></u></h4></td><td width="2%"></td><td width="58%"></td>' . "\n";
				
				$output .= '<tr><td>Amount</td><td>:</td><td style="text-align:right"> &pound;' .  $calculation['calculation']['amount'] . '</td><td></td></tr>' . "\n";
				$output .= '<tr><td>Date of loss</td><td>:</td><td style="text-align:right">' .  $calculation['calculation']['params']['value'][2] . '</td></tr>' . "\n";
				$output .= '<tr><td>Calculated to</td><td>:</td><td style="text-align:right">' .  $calculation['calculation']['params']['value'][3] . '</td></tr>' . "\n";
				$output .= '<tr><td>Total days</td><td>:</td><td style="text-align:right"> ' . $totalDays . ' days</td></tr>' . "\n";
				//add one more field for total days 
				
				$output .= '<tr><td>Full / Half special account rate</td><td>:</td><td style="text-align:right"> ' . ucfirst($calculation['calculation']['totals']['displayAccountRate']) . '</td></tr>' . "\n";
				
				$output .= '<tr><td>Aggregate percentage rate</td><td> :</td><td style="text-align:right"> ' . $calculation['calculation']['totals']['AggregatePercentageRate'] . '%</td></tr>' . "\n";
				
				$output .= '<tr><td><b>Total interest</b></td><td><b>:</b></td><td style="text-align:right"><b> &pound;' . $calculation['calculation']['totals']['interest'] . '</b></td></tr>' . "\n";
								
				$output .= '<tr><td><u>Breakdown</u></td><td></td><td></td></tr>' . "\n";
				
				$i = 0;
								
				foreach($calculation['calculation']['result'] as $row)
          		{
          			
          			$output .= '<tr style="font-size:9pt;"><td>Period ' . ++$i . '</td><td>:</td><td style="text-align:right"> ' . $row['params']['value'][3] . ' - ' . $row['params']['value'][4] . ' (' . $row['result']['daysInPeriod'] . ' days)</td></tr>' . "\n";
            
          			//$output .= '<tr style="font-size:9pt;"><td>Rate</td><td>:</td><td style="text-align:right"> ' . $row['params']['value'][2] . '% (' . $calculation['calculation']['totals']['AggregatePercentageRate'] . ' special account rate)</td></tr>' . "\n";
          			
          			$output .= '<tr style="font-size:9pt;"><td>Rate</td><td>:</td><td style="text-align:right"> ' . $row['params']['value'][2] . '% (Full special account rate)</td></tr>' . "\n";
                    			
          			$output .= '<tr style="font-size:9pt;"><td>Interest</td><td>:</td><td style="text-align:right">&pound;' . $row['result']['totalInterest'] . '</td></tr>' . "\n";
          
          		}
          	
          		$output .= "</table><br />" . "\n";
          }
        
		  if($calculation['calculatorId'] == 9)
	    	{	    		
				$output .="<table style='font-family:Verdana;font-size:11pt;width:640px;'>";
				if(!empty($calculation['calculation']['params']['value'][0]))
				{
					$output .= '<tr><td width="40%"><b>Description</b></td><td width="2%"><b>:</b></td><td width = "58%" style="text-align:right"><b>' . $calculation['calculation']['params']['value'][0] . '</b></td></tr>' . "\n";
				}
				
				$output .= '<tr><td>Value in ' . $calculation['calculation']['params']['value'][3] . '</td><td>:</td><td style="text-align:right">&pound;'.$calculation['calculation']['params']['value'][1].'</td></tr>' . "\n";
					
				$output .= '<tr><td>Value today (' . $calculation['calculation']['params']['value'][4] . ')</td><td>:</td><td style="text-align:right">&pound;'.$calculation['calculation']['params']['value'][6].'</td></tr>' . "\n";
					
				$output .= '<tr><td><b>Inflation rate</b></td><td>:</td><td style="text-align:right"><b> ' . $calculation['calculation']['params']['value'][7] .' ' . $calculation['calculation']['params']['value'][5] . '</b>%</td></tr>' . "\n";
				
				$output .= '</table><br />' . "\n";	
				
			}		

        }
	 
		$output .= '</body></html>';
		
		//echo $output;
		//die;
       		
		$cookie_name=$this->getRequest()->getCookie('csi');
			
		if(!empty($cookie_name))
		{
			$file_name=$this->getRequest()->getCookie('csi') . '.doc';
		}
		else
		{
			$file_name="Lexiscalculate.doc";
		}
		
		header('Content-Description: File Transfer');
    		
    	header('Content-Type: text/vnd.ms-word');
    
    	header('Content-Disposition: attachment; filename=' . $file_name);
    
    	header('Content-Transfer-Encoding: binary');
    
    	header('Expires: 0');
    
    	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    
    	header('Pragma: public');
    
    	header('Content-Length: ' . strlen($output));
  			
    	echo $output;
			    		
    	exit();
    		  	
  		break;

  		// json
  		
  		case 'json':
  			
  			// headers
    	
    		$this->getResponse()->setHttpHeader('Content-type', 'application/json');
  			
    		return $this->renderText(json_encode($this->calculations($this->getRequest()->getCookie('csi'))));

  		break;
			
		
  			
  		case 'pdf':
			  			
			// headers
  			
			$this->getResponse()->setHttpHeader('Content-type', 'application/pdf');
  			
			// fpdf
  			
			// fix this autoload shit this will mneed to take the base dire from the config
  			
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/../lib/3rdParty/fpdf/fpdf.php');
  			
			$this->pdf = new FPDF();

			$this->pdf->AddPage();

			// lexis nexis title block
        
			$this->pdf->SetFont('Arial','B',16);

			//$this->pdf->Cell(40,10,'Lexis Calculators', 0, 1);

			$this->pdf->SetTextColor(0,0,255);

			$this->pdf->SetFont('','U', 10);

			$this->pdf->Write(5,'http://calculators.lexisnexis.co.uk','http://calculators.lexisnexis.co.uk');
        
			$this->pdf->SetTextColor(0,0,0);
        
			// now add each calculation block
        
			$i = array();

			foreach ($this->calculations($this->getRequest()->getCookie('csi')) as $calculation) 
			{
	
        $i[$calculation['calculatorId']]++;
          
        // add calculator title
          
        if($i[$calculation['calculatorId']] == 1)
        {
          	
          $this->pdf->SetFont('Arial','U',10);
          $this->pdf->SetXY(10, $this->pdf->GetY() + 20);
          $this->pdf->Cell(0, 0,  $calculation['calculatorName'] . ' Calculations');
        }
          
        // switch in switch bad
          
        // VAT
        
        if($calculation['calculatorId'] == 1)
        {
          	
        	$this->pdf->SetFont('Arial','u',8);            
          	$this->pdf->SetXY(10, $this->pdf->GetY() + 10);
         	$this->pdf->Write(10, $calculation['calculation']['params']['value'][0], 0, 1, 'R');
           	$this->pdf->SetFont('Arial','',8);
            $this->pdf->Ln(5);
            $this->pdf->Write(10, 'Transaction Date : ' . $calculation['calculation']['params']['value'][3], 0, 1, 'R');
            $this->pdf->Ln(5);
            $this->pdf->Write(10, 'Total Cost : £' . $calculation['calculation']['result']['total'], 0, 1, 'R');
            $this->pdf->Ln(5);
            $this->pdf->Write(10, 'VAT : £' . $calculation['calculation']['result']['vat'] . ' (VAT Rate of ' . $calculation['calculation']['result']['vatRate'] . '%)', 0, 1, 'R');
            $this->pdf->Ln(5);
            $this->pdf->Write(10, 'Net Cost : £' . $calculation['calculation']['result']['net'], 0, 1, 'R');
            /*$this->pdf->Cell(20, 4, 'Net:', 0, 0);
            $this->pdf->Cell(5, 4, '£', 0, 0);
            $this->pdf->Cell(20, 4, $calculation['calculation']['result']['net'], 0, 1, 'R');
            $this->pdf->Cell(25, 4, 'VAT @ ' . $calculation['calculation']['params']['value'][3] . '% :', 'B', 0);
            $this->pdf->Cell(20, 4, $calculation['calculation']['result']['vat'], 'B', 1, 'R');
            $this->pdf->Cell(20, 5, 'Total:', 'B', 0);
         	$this->pdf->Cell(5, 5, '£', 'B', 0);
            $this->pdf->Cell(20, 5, $calculation['calculation']['result']['total'], 'B', 1, 'R');
          */  
         	$this->pdf->Ln(1);
            
        }

        // Adjust Percentage
        
        if($calculation['calculatorId'] == 2)
        {
          	
        	$this->pdf->SetFont('Arial','u',8);
            
          $this->pdf->SetXY(10, $this->pdf->GetY() + 10);

         	$this->pdf->Write(10, $calculation['calculation']['params']['value'][0], 0, 1, 'R');
          
         	$this->pdf->SetFont('Arial','',8);
         	 
          $this->pdf->Ln(5);
          
          //$this->pdf->Write(10, 'Cost of ' . "'" . $calculation['calculation']['params']['value'][0] . "'" . ' : £' . $calculation['calculation']['result']['start'], 0, 1, 'R');
          $this->pdf->Write(10, 'Amount : £' . $calculation['calculation']['result']['start'], 0, 1, 'R');
           
          $this->pdf->Ln(5);
          
          if($calculation['calculation']['params']['value'][2] == 'true')
          {
          			
						$this->pdf->Write(10, 'Increase of ' . $calculation['calculation']['params']['value'][3] . '% : £' . $calculation['calculation']['result']['change'], 0, 1, 'R');
          
		 }
         else
         {

						$this->pdf->Write(10, 'Decrease of ' . $calculation['calculation']['params']['value'][3] . '% : £' . $calculation['calculation']['result']['change'], 0, 1, 'R');
           	
         }
          
          $this->pdf->Ln(5);
          
          $this->pdf->Write(10, 'New Amount : £' . $calculation['calculation']['result']['final'], 0, 1, 'R');
          /*
          $this->pdf->SetFont('Arial','',8);
            
          $this->pdf->SetXY(10, $this->pdf->GetY() + 10);
          	
          $this->pdf->Cell(20, 4, 'Start Sum:', 0, 0);
            
          $this->pdf->Cell(5, 4, '£', 0, 0);
          
          $this->pdf->Cell(20, 4, $calculation['calculation']['result']['start'], 0, 1, 'R');
           
          if($calculation['calculation']['params']['value'][2] == 'true')
          {
          			
          	$this->pdf->Cell(25, 4, '+ ' . $calculation['calculation']['params']['value'][3] . '% :', 'B', 0);
          		
          }
          else
          {
          			
          	$this->pdf->Cell(25, 4, '- ' . $calculation['calculation']['params']['value'][3] . '% :', 'B', 0);
          			
          }	
             
          $this->pdf->Cell(20, 4, $calculation['calculation']['result']['change'], 'B', 1, 'R');
 
          $this->pdf->Cell(20, 5, 'Final Sum:', 'B', 0);

          $this->pdf->Cell(5, 5, '£', 'B', 0);
            
          $this->pdf->Cell(20, 5, $calculation['calculation']['result']['final'], 'B', 1, 'R');
            
          $this->pdf->Ln(1);
           */ 
        }

	    if($calculation['calculatorId'] == 3)
        {
          	
          $this->pdf->SetFont('Arial','',8);
          $this->pdf->SetXY(10, $this->pdf->GetY() + 10);
          $this->pdf->Cell(20, 4, 'Principal Sum:', 0, 0);
          $this->pdf->Cell(5, 4, '£', 0, 0);
          $this->pdf->Cell(20, 4, $calculation['calculation']['params']['value'][1], 0, 1, 'R');
          $this->pdf->Cell(25, 4, 'Interest Rate % :', 0, 0);
          $this->pdf->Cell(20, 4, $calculation['calculation']['params']['value'][2], 0, 1, 'R');
          $this->pdf->Cell(25, 4, 'Days In Period:', 'B', 0);
          $this->pdf->Cell(20, 4, $calculation['calculation']['result']['daysInPeriod'], 'B', 1, 'R');
          $this->pdf->Cell(20, 5, 'Total Interest:', 'B', 0);

          $this->pdf->Cell(5, 5, '£', 'B', 0);
            
          $this->pdf->Cell(20, 5, $calculation['calculation']['result']['totalInterest'], 'B', 1, 'R');
            
          $this->pdf->Ln(5);
            
          $this->pdf->Cell(25, 4, 'Start Date', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'End Date', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Days', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Rate [% p.a.]', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Rate [%]', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Interest', 'B', 1);
            
          //
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['params']['value'][3], 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['params']['value'][4], 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['result']['daysInPeriod'], 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['params']['value'][2], 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['result']['cumulativeInterest'], 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['result']['totalInterest'], 'B', 1);
            
          //
            
          $this->pdf->Cell(25, 4, 'Total', 'B', 0);
            
          $this->pdf->Cell(25, 4, '', 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['result']['daysInPeriod'], 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['params']['value'][2], 'B', 0);
            
          $this->pdf->Cell(25, 4, '', 'B', 0);
            
          $this->pdf->Cell(25, 4, $calculation['calculation']['result']['totalInterest'], 'B', 1);
            
          //
      
        }
        if($calculation['calculatorId'] == 4)
        {
          	
        	$this->pdf->SetFont('Arial','',8);
            
          $this->pdf->SetXY(10, $this->pdf->GetY() + 10);
            
         	$this->pdf->Cell(25, 4, 'Start Date', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'End Date', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Days', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Rate [%]', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Sum [£]', 'B', 0);
            
          $this->pdf->Cell(25, 4, 'Interest[£]', 'B', 1);
            
          //
            
          foreach($calculation['calculation']['result'] as $result)
          {
          			
	          $this->pdf->Cell(25, 4, $result['params']['value'][3], 'B', 0);
	            
	          $this->pdf->Cell(25, 4, $result['params']['value'][4], 'B', 0);
	           
	          $this->pdf->Cell(25, 4, $result['result']['daysInPeriod'], 'B', 0);
	            
	          $this->pdf->Cell(25, 4, $result['result']['cumulativeInterest'], 'B', 0);
	            
	          $this->pdf->Cell(25, 4, $result['params']['value'][1], 'B', 0);
	            
	          $this->pdf->Cell(25, 4, $result['result']['totalInterest'], 'B', 1);
	            
          }
          	
          //
            
          $this->pdf->Cell(25, 4, 'Total', 'B', 0);
          $this->pdf->Cell(25, 4, '', 'B', 0);
          $this->pdf->Cell(25, 4, '', 'B', 0);
          $this->pdf->Cell(25, 4, $calculation['calculation']['totals']['daysInPeriod'], 'B', 0);
          $this->pdf->Cell(25, 4, $calculation['calculation']['totals']['amount'], 'B', 0);
          $this->pdf->Cell(25, 4, $calculation['calculation']['totals']['totalInterest'], 'B', 1);
            
          //
      
				}
        	
		// Mileage
          
        if($calculation['calculatorId'] == 5)
        {
          	
         $this->pdf->SetFont('Arial','u',8);
         $this->pdf->SetXY(10, $this->pdf->GetY() + 10);
		 $this->pdf->Write(10, $calculation['calculation']['params']['value'][0], 0, 1, 'R');
         $this->pdf->SetFont('Arial','',8);
         $this->pdf->Ln(5);
         $this->pdf->Write(10, $calculation['calculation']['result']['journeys'] . ' trips to ' . $calculation['calculation']['params']['value'][0] . ' (' . $calculation['calculation']['result']['distance'] . ' mile ', 0, 1, 'R');
       	 if($calculation['calculation']['params']['value'][2]){
			$this->pdf->Write(10, 'round trip', 0, 1, 'R');
		  }
		  else{
			$this->pdf->Write(10, 'trip', 0, 1, 'R');
		   }
		 $this->pdf->Write(10, ') between ' . $calculation['calculation']['params']['value'][8] . ' and the ' . $calculation['calculation']['params']['value'][9] . ' : £' . $calculation['calculation']['result']['total'], 0, 1, 'R');
        }
          
        // Periodic
          
       	if($calculation['calculatorId'] == 7)
        {
          $this->pdf->SetFont('Arial','',8);
          $this->pdf->SetXY(10, $this->pdf->GetY() + 10);
          $this->pdf->Write(10, 'This item will be required ' . $calculation['calculation']['result']['events'] . ' times between ' . $calculation['calculation']['params']['value'][4] . ' and ' . $calculation['calculation']['params']['value'][5] . ', costing £' . $calculation['calculation']['result']['total'] . '', 0, 1, 'R');
            	 
        }
          
      }
        
      	$this->pdf->Output();
        
      	  	exit();
     		break;

				
  		case 'print':
  			
  			$i = array();
			//print_r($_COOKIE);
			//;
			$this->calculationsOutput.='<body onLoad="window.print();">';
			
			foreach ($this->calculations($this->getRequest()->getCookie('csi')) as $calculation) 
        	{
        	 
				//exit();
        		
				/*echo "<pre>";
	         	print_r($calculation);
				echo "<pre>";
				die;
				 */
				$iHead[$calculation['calculatorId']]++;
	          	/*
				echo "<pre>";
	         	print_r($iHead);
				echo "<pre>";
				die;
				*/
				 // add calculator title
	          
				
				if($iHead[$calculation['calculatorId']] == 1)
	          	{
	          	
					if($calculation['calculatorId']==7)
					{
						$calculation['calculatorName']="Past ".strtolower($calculation['calculatorName']);
					}	          	
					$this->calculationsOutput .= '<h3><u>' . $calculation['calculatorName'] . '</u></h3>' . "\n";
	
	          	}
				
				// render the calculations
				
        		// VAT
          
				if($calculation['calculatorId'] == 1)
          		{	
          	
					$this->calculationsOutput .= '<table width="600"><tr><td width="260" style="text-align:left"><b>Item</b></td><td>:</td><td style="text-align:right"><b>' . $calculation['calculation']['params']['value'][0] . '</b></td></tr>
					<tr><td style="text-align:left">Invoice date</td><td>:</td><td style="text-align:right">  ' . $calculation['calculation']['params']['value'][3] . '</td></tr>' . "\n";
            
          			$this->calculationsOutput .= '<tr><td style="text-align:left">Net cost</td><td>:</td><td style="text-align:right"> &pound;' . $calculation['calculation']['result']['net'] . '</td></tr>' . "\n";
            
          			$this->calculationsOutput .= '<tr><td style="text-align:left">VAT (@'.$calculation['calculation']['result']['vatRate'].'%)</td><td>:</td><td style="text-align:right">  &pound;' . $calculation['calculation']['result']['vat'] . ' <!--(VAT Rate of ' . $calculation['calculation']['result']['vatRate'] . '%)--></td></tr>' . "\n";
          		
          			$this->calculationsOutput .= '<tr><td style="text-align:left"><b>Total cost</b></td><td>:</td><td style="text-align:right"> <b> &pound;' . $calculation['calculation']['result']['total'] . '</b></td></tr></table>' . "\n";
            
				}
			
	        	// Percentage
	          
	        	if($calculation['calculatorId'] == 2)
	          	{
	          	
	          		$this->calculationsOutput .= '<table width="600"><tr><td width="260" style="text-align:left"><h4><u>' . $calculation['calculation']['params']['value'][0] . '<u></h4></td><td></td><td></td></tr><tr><td style="text-align:left">Amount</td><td>:</td><td style="text-align:right">&pound;' . $calculation['calculation']['result']['start'] . '</td></tr>' . "\n";
	            
	          		$this->calculationsOutput .= '<tr><td>';
	          		
	          		if($calculation['calculation']['params']['value'][2] == 'true')
	          		{
	          			
	          			$this->calculationsOutput .= 'Increase of ' . $calculation['calculation']['params']['value'][3] . '%</td>';
	          		
	          		}
	          		else
	          		{
	          			
	          			$this->calculationsOutput .= 'Decrease of ' . $calculation['calculation']['params']['value'][3] . '%';
	          			
	          		}
	          		
	          		$this->calculationsOutput .= '<td>:</td><td style="text-align:right">  &pound;' . $calculation['calculation']['result']['change'] . '</td></tr>' . "\n";
	            
	          		$this->calculationsOutput .= '<tr><td style="text-align:left"><b>New amount</b></td><td><b>:</b></td><td style="text-align:right"><b>&pound;' . $calculation['calculation']['result']['final'] . '</b></td></tr></table>' . "\n";
	            
				}
          
          // Interest on General Damages 
          
          if($calculation['calculatorId'] == 3)
          {
          		/*
				echo "<pre>";
	          	print_r($calculation);
				echo "<pre>";
				die;
				*/
				$this->calculationsOutput .= '<table width="600"><tr><td width="260" style="text-align:left">Injury</td><td>:</td><td style="text-align:right"> ' . $calculation['calculation']['params']['value'][0] . '</td></tr>';
				
				$this->calculationsOutput .= '<tr><td style="text-align:left">General Damages</td><td> :</td><td style="text-align:right"> &pound;' . $calculation['calculation']['amount'] . '</td></tr>' . "\n";
            
          		$this->calculationsOutput .= '<tr><td style="text-align:left">Interest Rate</td><td> : </td><td style="text-align:right">' . $calculation['calculation']['params']['value'][2] . '%</td></tr>' . "\n";
				
				$this->calculationsOutput .= '<tr><td style="text-align:left">Date of service</td><td> : </td><td style="text-align:right">' . $calculation['calculation']['params']['value'][3] . '</td></tr>' . "\n";
				
				$this->calculationsOutput .= '<tr><td style="text-align:left">Calculate to</td><td> : </td><td style="text-align:right">' . $calculation['calculation']['params']['value'][4] . '</td></tr>' . "\n";
            
          		$this->calculationsOutput .= '<tr><td style="text-align:left">Period</td><td> : </td><td style="text-align:right">' . $calculation['calculation']['result']['daysInPeriod'] . ' days</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td style="text-align:left">Aggregate percentage rate</td><td > : </td><td style="text-align:right">' . $calculation['calculation']['result']['cumulativeInterest'] . '  %</td></tr>' . "\n";
				
				$this->calculationsOutput .= '<tr><td style="text-align:left">Interest</td><td> : </td><td style="text-align:right">&pound;' . $calculation['calculation']['result']['totalInterest'] . '</td></tr>' . "\n";

          		$this->calculationsOutput .= '<tr><td style="text-align:left"><b>General damages with interest</b></td><td><b>:</b></td><td style="text-align:right"><b> &pound;' . $calculation['calculation']['result']['totalIncludingInterest'] . '</b></td></tr></table>' . "\n";

          }
          
          // Interst on Judgment Debt
          
        	if($calculation['calculatorId'] == 4)
          	{
          		
          		//print_r($calculation['calculation']);
          		
          		//exit($calculation['calculation']['result']['totalJudgmentAndInterest']);
          	
				$this->calculationsOutput .= '<table width="600">';
				
				$this->calculationsOutput .= '<tr><td width="260" >Total judgment debt</td><td>:</td><td style="text-align:right;">&pound;' . $calculation['calculation']['result'][0]['amount'] . '</td></tr>' . "\n";
				
				$this->calculationsOutput .= '<tr><td>Judgment date</td><td>:</td><td style="text-align:right;">' . $calculation['calculation']['result'][0]['params']['value'][3] . '</td></tr>' . "\n";
								
				$this->calculationsOutput .= '<tr><td><b>'.$calculation['calculation']['result'][0]['params']['value'][0].'</b></td></tr>' . "\n";
				
				$numCalculations = count($calculation['calculation']['result']);
				
				if($numCalculations > 1)
				{
					$this->calculationsOutput .= '<tr><td>Payments</td></tr>' . "\n";
				}
				
				$i = 0;
				$this->calculationsOutput .= '<tr><td colspan=3><table  align=right width=540 cellspacing=4>' . "\n";
				foreach($calculation['calculation']['result'] as $row)
          		{
          			$i++;
					
          			$this->calculationsOutput .= '<tr style="vertical-align:top"><td nowrap>&nbsp;</td nowrap><td>Payment '.($numCalculations>1?$i:'date').' <br/>(or proposed date)</td><td nowrap>' . $row['params']['value'][4] . '</td>&nbsp;&nbsp;&nbsp;<td>Amount of payment </td><td nowrap align="right">&pound;' . $row['result']['payment'] . '</td></tr>' . "\n";
            
          			$this->calculationsOutput .= '<tr><td nowrap>&nbsp;</td nowrap><td>&nbsp;</td><td nowrap>&nbsp;</td><td>Simple judgment interest <br>@ 8% (' . $row['result']['daysInPeriod'] . ' days)</td><td nowrap align="right"> &nbsp; &pound;' . $row['result']['totalInterest'] . '</td></tr>' . "\n";
            
          			$this->calculationsOutput .= '<tr><td nowrap>&nbsp;</td nowrap><td>&nbsp;</td><td>&nbsp;</td><td>Judgment balance</td><td nowrap align="right">&pound;' . $row['result']['judgmentBalance'] . '</td></tr>' . "\n";
            
          		}
				$this->calculationsOutput .= '</table></td></tr>' . "\n";

          		$this->calculationsOutput .= '<tr><td style="text-align:left;"><b>Total judgment interest</b></td><td><b>:</b></td><td style="text-align:right;"><b>&pound;' . $calculation['calculation']['totals']['judgmentInterest'] . '</b></td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td style="text-align:left;"><b>Total judgment outstanding</b></td><td><b>:</b></td><td style="text-align:right;"><b>&pound;' . $calculation['calculation']['totals']['judgmentOutstanding'] . '</b></td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td style="text-align:left;"><b>Total judgment interest outstanding</b></td><td><b>:</b></td><td style="text-align:right;"><b>&pound;' . $calculation['calculation']['totals']['judgmentInterestOutstanding'] . '</b></td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td style="text-align:left;"><b>Total judgment and interest</b></td><td><b>:</b></td><td style="text-align:right;"><b>&pound;' . $calculation['calculation']['totals']['totalJudgmentAndInterest'] . '</b></td></tr></table>' . "\n";
          		
          }
          
          // Mileage
          
		if($calculation['calculatorId'] == 5)
          {
          	
          		$this->calculationsOutput .= '<table width="600"><tr><td colspan="3"><h4><u>Travel (at ' . $calculation['calculation']['params']['value'][3] . ' pence per mile)</h4></td></tr>' . "\n";
            
          		$this->calculationsOutput .= '<tr><td>';
          		
          		
				if(data.result.journeys == 1)
				{
				
					$this->calculationsOutput .= '1 trip ';
				
				}
				else
				{
					
					$this->calculationsOutput .= $calculation['calculation']['result']['journeys'] . ' trips ';
					
				}
				
				$this->calculationsOutput .= 'to ' . $calculation['calculation']['params']['value'][0] . ' (' . $calculation['calculation']['result']['journeyDistance'] . ' mile ';
				
				if($calculation['calculation']['params']['value'][2])
				{
				
					$this->calculationsOutput .= 'round trip)';
					
				}
				else
				{
				
					$this->calculationsOutput .= 'trip)';
				
				}
				
				if($calculation['calculation']['params']['value'][8] != '' && $calculation['calculation']['params']['value'][9] != '')
				{
				
					$this->calculationsOutput .= ' between ' . $calculation['calculation']['params']['value'][8] . ' and the ' . $calculation['calculation']['params']['value'][9];
				
				}
				
				if($calculation['calculation']['params']['value'][8] != '' && $calculation['calculation']['params']['value'][9] == '')
				{
					
					$this->calculationsOutput .=' on the ' . $calculation['calculation']['params']['value'][8];
				
				}
				
				$this->calculationsOutput .= '</td><td nowrap="nowrap">: &pound;' . $calculation['calculation']['result']['total'] . '</td></tr></table>' . "\n";
           
          }
          
          // Net Income
          
			if($calculation['calculatorId'] == 6)
			{
          	
          		/*echo "<pre>";
				print_r($calculation);
				echo "</pre>";
				die;
				*/
				
				//print_r($totalYearEarning);
				//die;
				$this->calculationsOutput .= '<table width="600"><tr><td colspan="3"><h4><u>' . $calculation['calculation']['params']['value'][0] . '</u></h4></td></tr>' . "\n";
            
          		//$this->calculationsOutput .= '<tr><td>Interest Rate [%]</td><td style="text-align: right;">&pound;</td><td>' . $calculation['calculation']['params']['value'][2] . '</td></tr>' . "\n";
            
          		//$this->calculationsOutput .= '<tr><td>Days In Period[%]</td><td>&nbsp;</td><td style="text-align: right;">' . $calculation['calculation']['result']['daysInPeriod'] . '</td></tr>' . "\n";
          		
          		//$this->calculationsOutput .= '<tr><td>total Interest:</td><td>&pound;</td><td style="border-bottom: 1px solid #666666; border-top: 1px solid #666666; text-align: right;">' . $calculation['calculation']['result']['totalInterest'] . '</td></tr></table>' . "\n";
            
				$period = $calculation['calculation']['params']['value'][2];
				if($period=='13weeks')
				{
					$period = "Last 13 Weeks";
				}
          		
          		$this->calculationsOutput .= '<tr><td colspan=4>
	          		<table width="100%">
	          			<tr>
	          				<td width=260>Employee Name</td>
	          				<td>:</td>
	          				<td style="text-align:right;">' . $calculation['calculation']['params']['value'][0] . '</td>
	          			</tr>
	          			<tr>
	          				<td>Gross earnings per '.$period.'</td>
	          				<td>:</td>
	          				<td style="text-align:right;">&pound;' . $calculation['calculation']['result']['gross'] . '</td>
	          			</tr>
	          			<tr>
	          				<td>Tax Year</td>
	          				<td>:</td>
	          				<td style="text-align:right;">' . $calculation['calculation']['result']['financialYear'] . '</td>
	          			</tr>
	          		</table>
          		</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><th style="text-align:left;">Wage summary</th><th align="right">Yearly</th><th align="right">Monthly</th><th align="right">Weekly</th></tr>' . "\n";
            
          		$this->calculationsOutput .= '<tr><td>Gross pay</td><td align="right">&pound;' . $calculation['calculation']['result']['gross']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['monthGross']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['weekGross']  . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td>Tax free allowances</td><td align="right">&pound;' . $calculation['calculation']['result']['personalAllowance']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['monthPersonalAllowance']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['weekPersonalAllowance']  . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td>Taxable earnings</td><td align="right">&pound;' . $calculation['calculation']['result']['taxableIncome']  . '</td><td align="right"> &pound;' .$calculation['calculation']['result']['monthTaxableIncome'] . '</td><td align="right">&pound;' . $calculation['calculation']['result']['weekTaxableIncome']  . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td>Starting rate tax @ ' . number_format($calculation['calculation']['result']['startingTaxRate'])  . '%</td><td align="right">&pound;' . $calculation['calculation']['result']['startingRateTax']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['monthStartingRateTax']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['weekStartingRateTax']  . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td>Basic rate tax @ ' . number_format($calculation['calculation']['result']['basicTaxRate'])  . '%</td><td align="right">&pound;' . $calculation['calculation']['result']['basicRateTax']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['monthBasicRateTax']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['weekBasicRateTax']  . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td>Higher rate tax @ ' . number_format($calculation['calculation']['result']['higherTaxRate'])  . '%</td><td align="right">&pound;' . $calculation['calculation']['result']['higherRateTax']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['monthHigherRateTax']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['weekHigherRateTax']  . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td>National insurance</td><td align="right">&pound;' . $calculation['calculation']['result']['nationalInsurance']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['monthNationalInsurance']  . '</td><td align="right">&pound;' . $calculation['calculation']['result']['weekNationalInsurance']  . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td><b>Net earnings estimate<b></td><td align="right"><b>&pound;' . $calculation['calculation']['result']['netPay']  . '</b></td><td align="right"><b>&pound;' . $calculation['calculation']['result']['monthNetPay']  . '</b></td><td align="right"><b>&pound;' . $calculation['calculation']['result']['weekNetPay']  . '</b></td></tr></table>' . "\n";
          		
          }
          
          // Periodic
          
        	if($calculation['calculatorId'] == 7)
          	{
          		/*
          		echo "<pre>";
				print_r($calculation);
				echo "</pre>";
				die;
				*/
				$this->calculationsOutput .= '<table width="600"><tr><td width="260"><u><b>' . $calculation['calculation']['params']['value'][0] . '</b></u></td><td></td><td></td></tr><tr><td>Cost</td><td>:</td><td style="text-align:right;"> &pound;' . $calculation['calculation']['result']['amount'] . '</td></tr>' . "\n";
            
          		$this->calculationsOutput .= '<tr><td style="text-align:left;">Frequency</td><td>:</td><td style="text-align:right;">' . $calculation['calculation']['params']['value'][2] . ' per ' . $calculation['calculation']['params']['value'][3] . '</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td style="text-align:left;">Period</td><td>:</td><td style="text-align:right;">  ' . $calculation['calculation']['params']['value'][4] . ' to ' . $calculation['calculation']['params']['value'][5] . ' ('.$calculation['calculation']['result']['frequencyCount'].' '.$calculation['calculation']['params']['value'][3].')</td></tr>' . "\n";
          		
          		$this->calculationsOutput .= '<tr><td style="text-align:left;">Total units of '.$calculation['calculation']['params']['value'][0].'</td><td>:</td><td style="text-align:right;"> ' . $calculation['calculation']['result']['events'] . '</td></tr>' . "\n";
				
				$this->calculationsOutput .= '<tr><td style="text-align:left;"><b>Total</b></td><td><b>:</b></td><td style="text-align:right;"><b> &pound;' . $calculation['calculation']['result']['total'] . '</b></td></tr></table>' . "\n";
				
				//$this->calculationsOutput .= '<tr><td>Total</td></td> : &pound;' . $calculation['calculation']['result']['total'] . '</td></tr></table>' . "\n";
				
          	}
			
			
			
				// Interst on General Damages
			  
	        	if($calculation['calculatorId'] == 8)
	          	{
	          		
	          		//print_r($calculation['calculation']);
	          		
	          		/*echo "<pre>";
	          		print_r($calculation['calculation']['result'][7]['result']['daysInPeriod']);
					echo "<pre>";
					die;
					*/
	          		
	          		$totalDays = 0;
	          		
					foreach($calculation['calculation']['result'] as $row)
	          		{
	          			$totalDays +=  $row['result']['daysInPeriod'];
	          		}
	          		
					$this->calculationsOutput .= '<table width="600"><tr><td width="260"><b><u>' . $calculation['calculation']['params']['value'][0] . '</u></b></td><td></td><td></td></tr>' . "\n";
					
					$this->calculationsOutput .= '<tr><td style="text-align:left;">Amount</td><td>:</td><td style="text-align:right;"> &pound;' .  $calculation['calculation']['amount'] . '</td></tr>' . "\n";
					$this->calculationsOutput .= '<tr><td style="text-align:left;">Date of loss</td><td>:</td><td style="text-align:right;"> ' .  $calculation['calculation']['params']['value'][2] . '</td></tr>' . "\n";
					$this->calculationsOutput .= '<tr><td style="text-align:left;">Calculated to</td><td>:</td><td style="text-align:right;"> ' .  $calculation['calculation']['params']['value'][3] . '</td></tr>' . "\n";
					$this->calculationsOutput .= '<tr><td style="text-align:left;">Total days</td><td>:</td><td style="text-align:right;"> ' . $totalDays /*$calculation['calculation']['result'][7]['result']['daysInPeriod']*/ . ' days</td></tr>' . "\n";
					$this->calculationsOutput .= '<tr><td style="text-align:left;">Full / Half special account rate</td><td>:</td><td style="text-align:right;"> ' . ucfirst($calculation['calculation']['totals']['displayAccountRate']) . '</td></tr>' . "\n";
					$this->calculationsOutput .= '<tr><td>Aggregate percentage rate</td><td> :</td><td style="text-align:right"> ' . $calculation['calculation']['totals']['AggregatePercentageRate'] . '%</td></tr>' . "\n";
 				
					$this->calculationsOutput .= '<tr><td style="text-align:left;"><b>Total interest</b></td><td><b>:</b></td><td style="text-align:right;"><b> &pound;' . $calculation['calculation']['totals']['interest'] . '</b></td></tr>' . "\n";
									
					$this->calculationsOutput .= '<tr><td style="text-align:left;"><br /><u>Breakdown</u></td></tr>' . "\n";
					
					$i = 0;
	
					foreach($calculation['calculation']['result'] as $row)
	          		{
          			
	          			$this->calculationsOutput .= '<tr class="breakdown"><td style="text-align:left;">Period ' . ++$i . '</td><td>:</td><td style="text-align:right;"> ' . $row['params']['value'][3] . ' - ' . $row['params']['value'][4] . ' (' . $row['result']['daysInPeriod'] . ' days)</td></tr>' . "\n";
	            
	          			//$this->calculationsOutput .= '<tr class="breakdown"><td style="text-align:left;">Rate</td><td>:</td><td style="text-align:right;"> ' . $row['params']['value'][2] . '% (' . $calculation['calculation']['totals']['displayAccountRate'] . ' special account rate)</td></tr>' . "\n";
	          			
	          			$this->calculationsOutput .= '<tr class="breakdown"><td style="text-align:left;">Rate</td><td>:</td><td style="text-align:right;"> ' . $row['params']['value'][2] . '% (Full special account rate)</td></tr>' . "\n";
          			
	          			$this->calculationsOutput .= '<tr class="breakdown"><td style="text-align:left;">Interest</td><td>:</td><td style="text-align:right;">&pound;' . $row['result']['totalInterest'] . '</td></tr>' . "\n";
            
	          		}
					
	          		$this->calculationsOutput .= '</table>';
	          		
	          }
			  
				if($calculation['calculatorId'] == 9)
				{
					$this->calculationsOutput .= '<table width="600">';
					
					if(!empty($calculation['calculation']['params']['value'][0]))
					{
						$this->calculationsOutput .= '<tr><td width="260" style="text-align:left"><b>Description</b></td><td>:</td><td style="text-align:right;"><b>' . $calculation['calculation']['params']['value'][0] . '</b></td></tr>' . "\n";
					}
					
					$this->calculationsOutput .= '<tr><td style="text-align:left">Value in ' . $calculation['calculation']['params']['value'][3] . '</td><td>:</td><td style="text-align:right;">&pound;' . $calculation['calculation']['params']['value'][1] . '</td></tr>' . "\n";
					
					$this->calculationsOutput .= '<tr><td style="text-align:left">Value today (' . $calculation['calculation']['params']['value'][4] . ')</td><td>:</td><td style="text-align:right;">&pound;' . $calculation['calculation']['params']['value'][6] . '</td></tr>' . "\n";
					
					$this->calculationsOutput .= '<tr><td style="text-align:left"><b>Inflation rate</b></td><td >:</td><td style="text-align:right;"><b>   '.$calculation['calculation']['params']['value'][7].' ' . $calculation['calculation']['params']['value'][5].'</b>%</td></tr>' . "\n";
				
					$this->calculationsOutput .= '</table>';
				}
          
          		$this->calculationsOutput .= '<br />' . "\n";
        	}
			$this->calculationsOutput.='</body';
        	  	
          break;
		  
		  
          
  		}
  	
	}
  
  
  /** 
   * executeCalculate()
   *
   * calculation wrapper
   * 
   * @package     LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo       Load in the correct calculator to use the name to call the correct calculator,
   *             removes all this hard coded crap  
   *             fix the weird shit with render text
   *
   */
  
  public function executeCalculate(sfWebRequest $request)
  {   
        
// echo 'function executeCalculate(' . print_r(get_object_vars($request)) . ')' . "\n\n";

    // do we have a calculator session id csi
    
  	if(trim($this->getRequest()->getCookie('csi'))!='')
  	{
  		
  		$calculatorSessionId = $this->getRequest()->getCookie('csi');
  	
  	}
  	else
  	{
  		
  		$calculatorSessionId = $this->getRequest()->getCookie('symfony');//md5(rand() * time());
  		
  		setcookie('csi', $calculatorSessionId);
  		
  	}
  	
  	$calculatorParams = array();
  	
    $i = 0;
 
    $requestParams = $this->getRequestParameter('calculators');
    
    $calculatorParams[$i]['id'] = (int) $requestParams[id];
    
    $calculatorParams[$i]['calculationId'] = (int) $this->getRequestParameter('calculation_id', 0);
    
    $valuei = 0;
        
    while($this->getRequestParameter('value_' . $valuei, null) !== null)
    {

      $calculatorParams[$i]['value'][] = $this->getRequestParameter('value_' . $valuei);
          
      $valuei++;
          
    }
    
    // headers
        
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
             
    
    if(!$Calculator = Doctrine::getTable('Calculator')->find($calculatorParams[$i]['id']))
    {
    	  
    	 $result = array('error'        => 1,
                       'errorMessage' => 'The requested calculator does not exist',
                      );

       return $this->renderText(json_encode($result));
                      
    }
    else
    {
  	
    	// headers
    	
    	//$this->getResponse()->setHttpHeader('Content-type', 'application/json');
    	     
    	// return the calculation result as json
    	
    	$calculateFunctionName = "calculate" . str_replace(' ', '', str_replace('/', '', $Calculator->getName()));
		
	
	  	return $this->renderText(json_encode($this->$calculateFunctionName($calculatorParams[0], $calculatorSessionId)));
   
    }
   
  }
  
  /** 
   * executeCalculators()
   *
   * calculator wrapper
   * essentially returns the calculators page
   * including the index page if no or invalid id passed
   *  container for the form 
   * ahead of the executeCalculatorForm call 
   * 
   * @package     LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin email@danielmullin.com
   * @version    
   * @todo        ???
   */
 
  public function executeCalculators(sfWebRequest $request)
  {

//$platformConfig = parse_ini_file($_SERVER['PLATFORM_CONFIG_FILE'], true);
//print_r($platformConfig);
//exit();

// echo 'function executeCalculator(' . print_r(get_object_vars($request)) . ')' . "\n\n";

  	// move to calculators method()
  	
  	$this->calculator_menu_list = $this->calculators();
  	
  }
    
  /** 
     * executeCalculatorForm()
     *
     * calculator wrapper
     * essentially returns an empter container for the form 
     * ahead of the executeCalculatorForm call 
     * 
     * @package     LexisCalculate
     * @subpackage  calculator
     * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
     * @author      Daniel Mullin email@danielmullin.com 
     * @version     0.1
     * 
     * todo hande multiple labels
     * 
     *
     */
    
    public function executeCalculatorForm(sfWebRequest $request)
    {

// echo 'function executeCalculatorForm(' . print_r(get_object_vars($request)) . ')' . "\n\n";
    	
    	// set headers
    	
    	$this->getResponse()->setHttpHeader('Content-type', 'application/json');
  
    	// output json from $this->calculator()
    	
      return $this->renderText(json_encode($this->calculator($request->getParameter('id'))));

    }
	
  /** Amit validation check
   * executeCalculatorJQuery()
   * 
   * returns the Jquery block for validating and handling the form UI
   *
   * @package     LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo map directly to existing jquery.validate methods or new ones if non existent
   *
   */
    
  public function executeCalculatorJQuery(sfWebRequest $request)
  {

  	
  	 // load the calculator
    
    $calculator = $this->calculator($request->getParameter('id'));
    
//print_r($calculator);
 
  	$output = '';
    
  	// addition jquery on per calculator basis
  	
  	switch($request->getParameter('id'))
  	{  
  		
  		case 4:
            
  			$output = '
  			
  			// deactive form elements
            
            $("#value_4").attr("disabled", "disabled");
            
            $("#value_5").attr("disabled", "disabled");
            
            $("#value_6").attr("disabled", "disabled");
            
            function interestOnJudgmentDebtSinglePaymentOrMultiplePayments(click)
            {
            
                if($(click).val() == "true")
                {
                	
                    $("#value_4").removeAttr("disabled");
                    
                    $("#value_5").removeAttr("disabled");
                   
                    $("#value_6").attr("disabled", "disabled");
                    
                    validator.settings.rules = { value_0: {required: false}, 
                                                                             value_1: {required: true,min: 0.01}, 
                                                                             value_2: {required: false}, 
                                                                             value_3: {required: true}, 
                                                                             value_4: {required: true, enddate: true},
                                                                             value_5: { number: true, required: true },
                                                                             value_6: { number: true, required: false }                                                                           
                                                                            };
                    
                }
                else
                {
                    
                    $("#value_4").attr("disabled", "disabled");

                    $("#value_5").attr("disabled", "disabled");
                     
                    $("#value_6").removeAttr("disabled");
              
                    validator.settings.rules = { value_0: {required: false}, 
                                                                             value_1: {required: true,min: 0.01}, 
                                                                             value_2: {required: false}, 
                                                                             value_3: {required: false}, 
                                                                             value_4: {required: false},
                                                                             value_5: { number: true, required: false },
                                                                             value_5: { number: true, required: true }
                                                                            };
                }
                
            }
            
            $("#value_6").change(function( objEvent ){ 
  			
  				//alert(rows);
  				
  				var numberOfRows = $("#value_6").val();
  				
  				if (isNaN(numberOfRows)) 
  				{
  				
  					return false;
   				
  				}
  				else
  				{
  				
		 			v = 6;
  					
		 			for(j = 0; j < document.getElementById("hidNumPayments").value; j++)
		 			{
		 				$("#payment-" + j).remove();
		 			}
		 			
		 			//document.getElementById("hidNumPayments").focus();
		 			
  					for(i = 0; i < numberOfRows; i++)
  					{
  					
  						//alert($("#payment-" + i).length);
  						
  						if($("#payment-" + i).length == 0)
  						{
	  						// additional start date
	
	  						v++;
	  						
	  						document.getElementById("hidNumPayments").value = numberOfRows;
	  						
	  						$("<div>").attr("id", "payment-" + i).attr("style", "width: 358px;").appendTo("#calculator-container form #calculator-form-values");
	  						
	  						$("<hr />").attr("style", "width: 200px;").appendTo("#payment-" + i);
	
	  						$("<div>").attr("id", "calculator-form-value-payment-" + v).attr("style", "height: 26px; width: 358px;").appendTo("#payment-" + i);
	
	  						$("<p>").attr("style", "color: #3366CC; font-size:1em; font-weight: bold; line-height: 1.8em; text-indent: 10px;").html("Payment " + (i + 1)).appendTo("#calculator-form-value-payment-" + v);		
			
	  						//$("<div>").attr("id", "calculator-form-value-" + v).attr("style", "height: 26px; width: 358px;").appendTo("#payment-" + i);
			
								// add the label div
			
								//$("<div>").attr("id", "value-" + v + "-label").attr("style", "color: #999999; float: left; font-size: 1em; line-height: 1.8em; margin: 0px; min-width: 100px; text-align: right;").appendTo("#calculator-form-value-" + v);		
	
								// add the label
			
								//$("<p>").html("Start date").attr("class", "' . $calculator['css'] . '" + "-value-" + v + "-label-p").appendTo("#value-" + v + "-label");		
			
								// add the element div
			
								//$("<div>").attr("id", "value-" + v + "-element").attr("style", "float: left; margin-left: 10px; text-indent: 0px; width: 110px;").appendTo("#calculator-form-value-" + v);		
	
								//$("<input/>").attr("id", "value_" + v).attr("style", "color: #222222;  font-size: 1em;").attr("name", "value_" + v).attr("type", "text").attr("tabindex", v + 1).appendTo("#value-" + v + "-element");
				
								//$("#value_" + v).datepicker({ dateFormat: "dd-mm-yy" });
	
								// end date
	  						 
	  						//v++;
			 									
	  						$("<div>").attr("id", "calculator-form-value-" + v).attr("style", "margin:10px 0 0; min-height:30px; overflow:auto; width: 358px;").appendTo("#payment-" + i);
			
								// add the label div
			
								$("<div>").attr("id", "value-" + v + "-label").attr("style", "color: #999999; float: left; font-size: 1em; line-height: 18px; margin: 0px; width: 170px; text-align: right;").appendTo("#calculator-form-value-" + v);		
	
								// add the label
			
								$("<p>").html("Payment date / proposed payment date").attr("class", "' . $calculator['css'] . '" + "-value-" + v + "-label-p").appendTo("#value-" + v + "-label");		
			
								// add the element div
			
								$("<div>").attr("id", "value-" + v + "-element").attr("style", "float: left; margin-left: 10px; text-indent: 0px; width: 110px;").appendTo("#calculator-form-value-" + v);		
	
								$("<input />").attr("id", "value_" + v).attr("style", "color: #222222;  font-size: 1em;").attr("name", "value_" + v).attr("type", "text").attr("tabindex", v + 1).appendTo("#value-" + v + "-element");
				
								$("#value_" + v).datepicker({dateFormat: "dd-mm-yy", yearRange: "1990:2030", changeMonth: true, changeYear: true});
	
								valueName = "value_" + v;
	
								validator.settings.rules[valueName] = {required: true}; 
								validator.settings.messages[valueName] = "test";
								
								// amount
	  						 
	  						v++;
			 					
	  						$("<div>").attr("id", "calculator-form-value-" + v).attr("style", "margin:10px 0 0; min-height:30px; overflow:auto; width: 358px;").appendTo("#payment-" + i);
			
								// add the label div
			
								$("<div>").attr("id", "value-" + v + "-label").attr("style", "color: #999999; float: left; font-size: 1em; line-height: 18px; margin: 0px; width: 170px; text-align: right;").appendTo("#calculator-form-value-" + v);		
	
								// add the label
			
								$("<p>").html("Amount (&pound;)").attr("class", "' . $calculator['css'] . '" + "-value-" + v + "-label-p").appendTo("#value-" + v + "-label");		
			
								// add the element div
			
								$("<div>").attr("id", "value-" + v + "-element").attr("style", "float: left; margin-left: 10px; text-indent: 0px; width: 110px;").appendTo("#calculator-form-value-" + v);		
	  						
								$("<input />").attr("id", "value_" + v).attr("style", "color: #222222;  font-size: 1em;").attr("name", "value_" + v).attr("type", "text").attr("tabindex", v + 1).appendTo("#value-" + v + "-element");
				
								var valueName = "value_" + v; 
								
								validator.settings.rules[valueName] = {required: true, number : true}; 
								validator.settings.messages[valueName] = "test";
								
								// close
								
								$("<div>").attr("id", "calculator-form-value-payment-" + v + "-clear").attr("style", "height: 26px; width: 358px;").appendTo("#payment-" + i);
	
	  						$("<p>").attr("style", "color:#999999; font-size: 1em; line-height: 1.8em; text-indent: 10px;").html("[<a style=\'color:#CB0133; margin:0; font-size:12px; font-weight: normal; text-decoration:none;\'href=\'#\' onclick=\'clearJudgmentDebtRow(" + i + "); return false;\'>Clear this payment</a>]").appendTo("#calculator-form-value-payment-" + v + "-clear");		
						}
						else
						{
							v = v + 2;
							
						}
						
  					}
  					
  					// remove any excess payments
  					
  					elementsCount = ($("#calculator-form-values > div").size());
  					
  					for(i = 0; i < elementsCount; i++)
  					{

  						
  						if(i > (numberOfRows + 6))
  						{
  						
  							//$("#payment-2").remove();
  							
  						}
  						
					}
  					
  					// resize window
  					
  					
  				
  					resizeWindow();
  				
  					return
  					
  				}
  				
  			});
  			
  			function clearJudgmentDebtRow(row)
  			{
  			
  				$("#value_6").attr("value", ($("#value_6").val() - 1));
  				
  				$("#payment-" + row).remove();
  				
  			}
  			
';
  			break;
  			
  		case 5:
  			
  			$output .= '
  			
  			// deactive form elements
  			
  			$("#value_5").attr("disabled", "disabled");
  			
  			$("#value_6").attr("disabled", "disabled");
  			
  			$("#value_7").attr("disabled", "disabled");
  			
  			function mileageCalculatorJourneysOrTimePeriod(click)
  			{
  			
  				if($(click).val() == "true")
  				{
  				
  					$("#value_5").removeAttr("disabled");
  					
  					$("#value_6").attr("disabled", "disabled");
  			
  					$("#value_7").attr("disabled", "disabled");
  				
  					validator.settings.rules = { value_0: {alphanumeric: true}, 
  																			 value_1: {required: true,min: 0.01}, 
  																			 value_2: {required: false}, 
  																			 value_3: {required: true}, 
  																			 value_4: {required: true},
  																			 value_5: { number: true, required: true },
  																			 value_8: { required: false},
  																			 value_9: { required: false}																		 
  																			};
  					
  				}
  				else
  				{
  					
  					$("#value_5").attr("disabled", "disabled");
  					
  					$("#value_6").removeAttr("disabled");
  			
  					$("#value_7").removeAttr("disabled");
  				
  					validator.settings.rules = { value_0: {alphanumeric: true}, 
							 value_1: {required: true,min: 0.01}, 
							 value_2: {required: false}, 
							 value_3: {required: true}, 
							 value_4: {required: true}, 
							 value_5: {required: false}, 
							 value_6: {required: true,min: 1}, 
							 value_7: {required: true}, 
							 value_8: {required: true}, 
							 value_9: {required: true}
							};
  				}
  				
  			}';
  			
  			break;
  			
  	}
  	
    $output .= 'validator.settings.rules = {';
    	
    // map the validation rules for each value
    
    $i = 0;
    
	foreach ($calculator['values'] as $valueProperties)
    {
    	
		
		$output .= 'value_' . $i . ': {';
	   	foreach($valueProperties AS $key => $value)
    	{
			
      	switch($key)
        {
            
        	// additional alphanumeric 
        	
			case 'alphanumeric':
        	
        		$output .= 'alphanumeric: true,' . "";
        
        		break;
        		
          case 'decimal':
          case 'decimal-select':
                
             $output .= 'number: true,' . "";    
            
             break;
        
			case 'endDate':
        	
        		$output .= 'enddate: true,' . "";
        
        		break;
        		  
            case 'max':
            	
            	// max -> maximum jquery.validation max()
            	
            	$output .= 'max: ' . $value . ',' . "";
            
            	break;

					case 'min':
            	
            	// min -> minimum jquery.validation min()
            	
            	$output .= 'min: ' . $value . ',' . "";
            	
            	break;
            	
					case 'required':
            	
            	// required -> required jquery.validation required()
            			
        			if($value == 1)
        			{
        
            		$output .= 'required: true,' . "";
        
        			}
        			else
        			{
        
       			     $output .= 'required: false,' . "";
        
        			}
            	
						break;
        		
        	case 'ukTaxCode':
            	
            	// additional uk tax code
            	
            	$output .= 'ukTaxCode: true,' . "";
            	
            	break;
        	
            default:
            		
            	break;
            	
        	}
        
    	}
    	
			$output = substr($output, 0, (strlen($output) - 1)) . "}, ";
      
      $i++;
      
    }
    
    $output = substr($output, 0, -2);
    
    $output .= '};
validator.settings.messages = {';
    
    // map the validation error messages for each value

    $i = 0;
    
    foreach ($calculator['values'] as $value)
    {

//print_r($value);

      if($value['validationErrorMessage'])
      {

            $output .= 'value_' . $i . ': "' . $value['validationErrorMessage'] . '", ';
 
      }
			else
			{
				
						$output .= 'value_' . $i . ': "", ';
						
			} 
			   
			$i++;
        
    }

    $output = substr($output, 0, -2); 
    
    $output .= '};';
    
    /*$output .= '
           errorPlacement: function(error, element) {
                if ( element.is(":text") )
                    error.appendTo( element.parent().next() );
                else if ( element.is(":checkbox") )
                    error.appendTo ( element.next() );
                else
                    error.appendTo( element.parent().next() );
            },
            // specifying a submitHandler prevents the default submit, good for the demo
            submitHandler: calculate,
       });
';*/
         
    // output 
    
    return $this->renderText($output);
    
  }

  /** 
   * executeDeleteCalculation()
   * 
   * rpc style call to delete a calculation mirroring the ui
   *
   * @package     LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo        UAT
   *
   */
  
  public function executeDeleteCalculation(sfWebRequest $request)
  {
  	
//echo 'executeDeleteCalculation(' . print_r(get_object_vars($request)) . ')' . "\n\n";

    // load the calculation
	do{    
    $Calculation = Doctrine_Query::create()
            ->from('Calculation cals')
           	->where('cals.status_code = 700')
            ->andwhere('cals.calculator_session_id = ?', $this->getRequest()->getCookie('csi'));
            
    if($request->getParameter('calculation_id')!='')
    {
    	 $Calculation = $Calculation->andwhere('cals.calculation_id = ?', $request->getParameter('calculation_id'));
    }
    
    $Calculation = $Calculation->fetchOne();

	    $Calculation->setStatusCode(900);
	    $Calculation->save();
	} while ($Calculation);
    return $this->renderText('1');
    
  }
  
  /**
   * Executes index action forwarding to the calculator page
   *
   * @param sfRequest $request A request object
   * @todo routing.yml
   */
  
  public function executeIndex(sfWebRequest $request)
  {

    $this->forward('calculators', 'calculators');
      
  }
	
  /**
   * CALCULATORS
   * 
   * Calculator                       Status
   * 
   * helper methods
   * 
   * PHASE 1
   * 
   * calculateAdjustPercentage        uat
   * calculateInterestOnDamages       uat
   * calculateInterestOnJudgmentDebt  pending
   * calculateMileage                 pending
   * calculateNetIncome                 specification
   * calculatePeriodic                specification
   * calculateVat                     uat
   * 
   * calculateVatPlus				  development
   */

  /**
   * method to test if a give year is a leap year
   * 
   * @package     LexisCalculate
   * @subpackage  calculators
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @params      int     $year 
   * @returns     boolean 
   * 
   * @todo        improve model object utilisation
   */
  
  protected function isLeapYear($year) 
  {

    if(($year%4) != 0)
    {     
    	
    	return false;     
    
    }

    if(($year%100) == 0)
    {
     
    	if(($year%400) == 0)
      {     
      	
      	return true;     
      
      }
      else
      {     
      	
      	return false;    
      
      }

    }
    else
    {     
    	
    	return true;     
    
    }

  }
  
  /** 
   * calculatePercentage()
   *
   * The Percentage Calculator
   * 
   * @package    LexisCalculate
   * @subpackage calculator
   * @author     Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author     Daniel Mullin email@danielmullin.com 
   * @version    0.1
   * 
   * @todo    migrate validation to extended symfony validation
   *          extract calculation log to seperate function
   * 
   * @params  array   $params[id] // passthru
   *                  float   $params['value'][0] // amount
   *                  boolean $params['value'][1] // inclusive 
   *                  float   $params['value'][2] // percentage
   * @params  string  $calculatorSessionId // the application key for calculation logging
   */
  
  protected function calculatePercentageIncreaseDecrease($params, $calculatorSessionId = false)
  {
  	
// echo 'function calculateAdjustPercent(' . print_r($params), $calculatorSessionId = null) . ')' . "\n\n";

  /**
   * PRE FLIGHT 
   */
        
  $return['params']       = $params;
  $return['error']        = false;
  $return['errorMessage'] = '';
        
  // values
        
  if(!key_exists('value', $params))
  {

    $return['error']        = 1;
    $return['errorMessage'] = 'ERROR: Invalid call';
            
    return $return;
            
  }
        
  // amount
        
  if(!key_exists(1, $params['value']) 
    //||!is_int($params['value'][0]) 
    || $params['value'][1] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Error: Invalid amount';
            
      return $return;

    }

    // increase decrease
        
    if(!key_exists(2, $params['value']))
    {

      $return['error']        = 1;
      $return['errorMessage'] = 'ERROR: Increase or decrease is required';
            
      return $return;
            
    }
        
    // precentage
        
    if(!key_exists(3, $params['value'])
      || $params['value'][3] < 0.01)
    {
            
      $return['error']        = true;
      $return['errorMessage'] = 'ERROR: Invalid percentage';
            
      return $return;
      
    }

    // log
    
    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }
    
    /**
     * CALCULATION
     */
      
    if($params['value'][2] == 'true')
    {
            
      // increase
        
      $return['result']['start']  = number_format($params['value'][1], $this->decimalPlaces);
      $return['result']['change'] = number_format(($params['value'][1] * ($params['value'][3]) / 100), $this->decimalPlaces);
      $return['result']['final']  = number_format($params['value'][1] + ($params['value'][1] * ($params['value'][3]) / 100), $this->decimalPlaces);
            
    }
    else 
    {

      // decrease
            
      $return['result']['start']  = number_format($params['value'][1], $this->decimalPlaces);
      $return['result']['change'] = number_format($params['value'][1] * ($params['value'][3] / 100), $this->decimalPlaces);
      $return['result']['final']  = number_format($params['value'][1] - ($params['value'][1] * ($params['value'][3] / 100)), $this->decimalPlaces);
            
    }
    return $return;
  
  }
  
  /** 
   * calculateInterestOnGeneralDamages()
   *
   * The Interest On Damages Calculator
   * 
   * @package    LexisCalculate
   * @subpackage calculators
   * @author     Daniel Mullin email@danielmullin.com
   * @author     Daniel Mullin email@danielmullin.com
   * @version    0.1
   * 
   * @todo       UAT
   * 
   * @params  array   $params[id] // passthru
   *          string  $params['value'][0] // label
   *          float   $params['value'][1] // amount
   *          float   $params['value'][2] // interest rate 
   *          string  $params['value'][3] // start date
   *          string  $params['value'][4] // end date
   *          boolean $params['value'][5] // round cumulative interest
   * @params  string  $calculatorSessionId // the application key for calculation logging
   */
  
  protected function calculateInterestOnGeneralDamages($params, $calculatorSessionId = false)
  {
  	
// echo 'calculateInterestOnDamages(' . print_r($params) . ', ' . $calculatorSessionId . ')' . "\n\n";

	/**
   	 * PRE FLIGHT 
   	 */
        
	$return['params']       = $params;
    $return['error']        = false;
    $return['errorMessage'] = '';

    // values
        
    if(!key_exists('value', $params))
    {

    	$return['error']        = true;
    	$return['errorMessage'] = 'Invalid call!';
            
    	return $return;
            
    }

    // amount
        
    if(!key_exists(1, $params['value']) 
        //||!is_int($params['value'][0]) 
      || $params['value'][1] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid amount!';
            
      return $return;
        
    }
      
    // interest rate
   
    if(!key_exists(2, $params['value'])
        //||!is_int($params['value'][0]) 
        || $params['value'][2] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid rate';
            
      return $return;
            
    }

    // start date
   
    if(!key_exists(3,$params['value']))
        //||!is_int($params['value'][0]) 
        // $params['value'][2] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid start date';
            
      return $return;
            
    }
    

    // end date
   
    if(!key_exists(4,$params['value']))
        //||!is_int($params['value'][0]) 
        // $params['value'][2] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid end date';
            
      return $return;
            
    }
  
    // log
    
    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }
    
    /**
     * CALCULATION
     */
          
    // calculate number of days to apply daily interest
    
    $startDateExploded = explode('-', $params['value'][3]);
    
    $startDay = $startDateExploded[0];
    $startMonth = $startDateExploded[1];
    $startYear = $startDateExploded[2];
    
    $endDateExploded = explode('-', $params['value'][4]);
    
    $endDay = $endDateExploded[0];
    $endMonth = $endDateExploded[1];
    $endYear = $endDateExploded[2];
    
    $startTimestamp = gmmktime(0, 0, 0, $startMonth, $startDay, $startYear);

//echo $startTimestamp . "\n\n";

    $endTimestamp = gmmktime(0, 0, 0, $endMonth, $endDay, $endYear);

//echo $endTimestamp . "\n\n";

    // we need to slice the date ranges on years
    
    $dailyInterestRate = (($params['value'][2] / 100) / 365);
    
    $leapDailyInterestRate = (($params['value'][2] / 100) / 366);
    
    $principalSum = $params['value'][1];
    
    $totalInterest = 0;
    
    $return['result']['cumulativeInterest'] = '0.000';
    
    if($startYear == $endYear && !$this->isLeapYear($startYear))
    {
    	
    	// if the dates fall within the same year and that year is not a leap year
    	
    	// days in period
    	
    	$return['result']['daysInPeriod'] = (($endTimestamp - $startTimestamp) / 86400);
    	
    	// raw cumulative interest
    	
    	//if($params['value'][5])
    	//{
    		
    		//$rawCumulativeInterest = number_format(($return['result']['daysInPeriod'] / 365) * $params['value'][2], 2);
    		
    	//}
    	//else
    	//{
    	
    		$rawCumulativeInterest = ((($endTimestamp - $startTimestamp) / 86400) / 365) * $params['value'][2];
    	
    	//}
    	
    	// total interest
    	
    	//if($params['value'][5])
    	//{
    		
    		//$totalInterest = (($rawCumulativeInterest / 100) * $principalSum);
    		    		
    	//}
    	//else
    	//{
    	
    		$totalInterest = $principalSum * (((($endTimestamp - $startTimestamp) / 86400) / 365) * ($params['value'][2] / 100));
    	
    	//}
    	
    }
    elseif($startYear == $endYear && $this->isLeapYear($startYear))
    {
      
      // if the dates fall within the same year and that year is not a leap year
      
		// days in period
    	
    	$return['result']['daysInPeriod'] = (($endTimestamp - $startTimestamp) / 86400);
    	
    	// raw cumulative interest
    	
			//if($params['value'][5])
    	//{
    		
    		//$rawCumulativeInterest = number_format(($return['result']['daysInPeriod'] / 366) * $params['value'][2], 2);
    		
    	//}
    	//else
    	//{
    	
    		$rawCumulativeInterest = ((($endTimestamp - $startTimestamp) / 86400) / 366) * $params['value'][2];
    	
    	//}
    	
    	// total interest
    	
    	//if($params['value'][5])
    	//{
    		
    		//$totalInterest = (($rawCumulativeInterest / 100) * $principalSum);
    		    		
    	//}
    	//else
    	//{
    	
    		$totalInterest = $principalSum * (((($endTimestamp - $startTimestamp) / 86400) / 366) * ($params['value'][2] / 100));
    	
    	//}
      
    }
    else
    {
    	
    	// we need to iterate through each year testing if the i year is a leap and adding amoiunts as required

    	$return['result']['daysInPeriod'] = 0;
    	
    	$currentYear = $startYear;
    	
    	// start year

		$daysInStartYear = ((gmmktime(0, 0, 0, 13, 1, $startYear) - $startTimestamp) / 86400);

//echo $daysInStartYear  . "\n";

      	$return['result']['daysInPeriod'] = $return['result']['daysInPeriod'] + $daysInStartYear;
     
		if($this->isLeapYear($startYear))
      	{

	        // add interest / 366 * daysInstartYear		
	    		
	      	// raw cumulative interest
	      	
	      	//if($params['value'][5])
					//{
	    		
						//$rawCumulativeInterest = number_format(($daysInStartYear / 366) * $params['value'][2]);
	    		
					//}
					//else
					//{
	    		
			$rawCumulativeInterest = ($daysInStartYear / 366) * $params['value'][2];
					
//echo $rawCumulativeInterest . "\n\n";

      	  //$rawCumulativeInterest = ($daysInStartYear / 366) * $params['value'][2];
    	
				//}
    	
    		// total interest
    	
    		//if($params['value'][5])
	    	//{
	    		
	    		//$totalInterest = ($rawCumulativeInterest / 100) * $principalSum;
	      
	    	//}
	    	//else
	    	//{
	    		
			$totalInterest = $principalSum * (($daysInStartYear / 366) * ($params['value'][2] / 100));

//echo  $totalInterest . "\n\n";

      	  //$totalInterest = (($daysInStartYear / 366) * ($params['value'][2] / 100)) * $principalSum;
	     		
	    	//}
	    	
		}
		else 
		{
    		
			// add interest / 365 * daysInstartYear

			// raw cumulative interest
      	
    		//if($params['value'][5])
    		//{
    		
    			$rawCumulativeInterest = number_format(($daysInStartYear / 365) * $params['value'][2]);
    		
    		//}
    		//else
    		//{
    		
    			$rawCumulativeInterest = ($daysInStartYear / 365) * $params['value'][2];

//echo ($daysInStartYear / 365) . '*' . $params['value'][2] . "\n\n";
    		
    			//}
    	
    		// total interest
    	
    		//if($params['value'][5])
    		//{
    		
				$totalInterest = $principalSum * (($daysInStartYear / 365) * ($params['value'][2] / 100));

//echo $totalInterest . "\n\n";

    		//}
    		
    	}

    	// the years in between
    	
    	$currentYear++;
    	
    	while ($currentYear != $endYear) 
    	{
    		
			if($this->isLeapYear($startYear))
			{
	
		      	$return['result']['daysInPeriod'] = $return['result']['daysInPeriod'] + 366;
		        
		      	// raw cumulative interest
	      	
	      		$rawCumulativeInterest = $rawCumulativeInterest + $params['value'][2];
	    	
				// total interest
	    	
				$totalInterest = $totalInterest + ($principalSum * (($params['value'][2] / 100)));
	      
	      	}
	      	else 
	      	{
	        
	      		$return['result']['daysInPeriod'] = $return['result']['daysInPeriod'] + 365;
	      		
				$rawCumulativeInterest = $rawCumulativeInterest + $params['value'][2];
    	
    			// total interest
    	
    			$totalInterest = $totalInterest + ($principalSum * (($params['value'][2] / 100)));
	      
	    	}
        	
        $currentYear++;
    
      }

      // end year
      
      $daysInEndYear = (($endTimestamp - gmmktime(null, null, null, 1, 1, $endYear)) / 86400);
      
//echo ($endTimestamp - gmmktime(0, 0, 0, 1, 1, $endYear)) . "\n\n";

//echo $endTimestamp . ' - ' . gmmktime(23, 59, 59, 7, 31, 1999) .  ' - ' . gmmktime(0, 0, 0, 8, 1, 1999) . "\n\n";
//echo gmmktime(0, 0, 0, 1, 1, 1999) . ' - '  . gmmktime(0, 0, 0, 1, 1, $endYear) . "\n\n";


//echo $daysInEndYear . "\n\n";

      $return['result']['daysInPeriod'] = $return['result']['daysInPeriod'] + $daysInEndYear;
     
      if($this->isLeapYear($endYear))
      {

        // add interest / 366 * daysInstartYear   
        
        $rawCumulativeInterest = $rawCumulativeInterest + (($daysInEndYear / 366) * $params['value'][2]);
    	
    		// total interest
    	
    		$totalInterest = $totalInterest + ($principalSum * (($daysInEndYear / 366) * ($params['value'][2] / 100)));
    		
      }
      else 
      {
        
        // add interest / 365 * daysInstartYear

        $rawCumulativeInterest = $rawCumulativeInterest + (($daysInEndYear / 365) * $params['value'][2]);
    	
    		// total interest
    	
				$totalInterest = $totalInterest + ($principalSum * (($daysInEndYear / 365) * ($params['value'][2] / 100)));
      
      }
      
    }
 
    //if($params['value'][5])
    //{
    
    	//$return['result']['rawCumulativeInterest'] = 'Cumulative interest rate rounded to 2 decimal places';
    //}
    //else
    //{
    	
    	$return['result']['rawCumulativeInterest'] = $rawCumulativeInterest;    	
    
    //}
    
    $return['result']['cumulativeInterest'] = number_format($rawCumulativeInterest, $this->decimalPlaces+2);
    
    $return['result']['totalInterest'] = number_format($totalInterest, $this->decimalPlaces);
  
    $return['result']['totalIncludingInterest'] = number_format($totalInterest + $principalSum, $this->decimalPlaces);

//print_r($return);

	//exit();
	$return['amount'] = number_format($params['value'][1], $this->decimalPlaces);
	
    return $return;
    
  }
  
  /** 	
   * calculateInterestOnJudgmentDebt()
   *
   * The Interest On JudgMent Debt Calculator
   * hazaar essentially a wrapper of the Interest On Damages Calculator
   * can process x number of times
   * 
   * @package     LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo    migrate validation to extended symfony validation
   * 
   * @params  array   $params[id] // passthru
   *          float   $params['value'][ x ] // amount
   *          float   $params['value'][ x+1 ] // interest rate 
   *          string  $params['value'][ x+2 ] // start date
   *          string  $params['value'][ x+3 ] // end date
   * @params  string  $calculatorSessionId // the application key for calculation logging
   * 
   */
  
  protected function calculateInterestOnJudgmentDebt($params, $calculatorSessionId = null)
  {
  		
//

    // log
    
    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }
    
  	$lineItems = count($params);
  	
  	$ivalue = 0;
  	
  	$calculatorParams['value'] = array();
  	
  	$startDate = $params['value'][0];
  	
  	$totalsDaysInPeriod = 0;
  	
  	$judgmentInterest = 0;
  	
  	$amountPaid = 0;
  	
  	$totalsAmount = 0;
  	
  	if($params['value'][3] == 'true')
  	{
  		$calculatorParams['value'][] = $params['value'][2];
        $calculatorParams['value'][] = $params['value'][1] - $amountPaid;
        $calculatorParams['value'][] = 8;
        $calculatorParams['value'][] = $startDate;
        $calculatorParams['value'][] = $params['value'][4];
            
        $return['params']['id'] = 4;
                        
        $return['result'][0] = $this->calculateInterestOnGeneralDamages($calculatorParams);
            
        // totals
            
        $totalsDaysInPeriod = $totalsDaysInPeriod + $return['result'][0]['result']['daysInPeriod'];
            
        $amountPaid = $amountPaid + $params['value'][5];
            
        $return['result'][0]['result']['payment'] = number_format($params['value'][5], $this->decimalPlaces);
            
        $return['result'][0]['result']['judgmentBalance'] = number_format($params['value'][1] - $amountPaid, $this->decimalPlaces);
            
        $judgmentInterest = $judgmentInterest + str_replace( ',', '', $return['result'][0]['result']['totalInterest']);
            
        unset($calculatorParams['value']);
  		
  	}
  	else
  	{
  	
  	
	  	for($i = 0; $i < $params['value'][6]; $i++)
	  	{
	  			
	  		$iivalue = $ivalue * 2;
	  		
	  		$calculatorParams['value'][] = $params['value'][2];
	  		$calculatorParams['value'][] = $params['value'][1] - $amountPaid;
	  		$calculatorParams['value'][] = 8;
	  		
	  		//if($params['value'][$iivalue + 4] != '')
	  	//	{
	  		
	  		//		$calculatorParams['value'][] = $params['value'][$iivalue + 4];
	  		
	  		//}
	  		//else
	  		//{
	  		
	  			$calculatorParams['value'][] = ($iivalue<1)?$startDate:$params['value'][$iivalue  + 5];
	  		
	  		//}
	
	  		$calculatorParams['value'][] = $params['value'][$iivalue  + 7];
	  		
	//print_r($this->calculateInterestOnDamages($calculatorParams));
	  		
	  		$return['params']['id'] = 4;
	  		
	  		$return['result'][$i] = $this->calculateInterestOnGeneralDamages($calculatorParams);
	  		
	  		// totals
	  		
	  		$totalsDaysInPeriod = $totalsDaysInPeriod + $return['result'][$i]['result']['daysInPeriod'];
	  		
	  		$amountPaid = $amountPaid + $params['value'][$iivalue  + 8];
	  		
	  		$return['result'][$i]['result']['payment'] = number_format($params['value'][$iivalue  + 8], $this->decimalPlaces);
	  		
	  		$return['result'][$i]['result']['judgmentBalance'] = number_format($params['value'][1] - $amountPaid, $this->decimalPlaces);
	  		
	  		$judgmentInterest = $judgmentInterest + str_replace( ',', '', $return['result'][$i]['result']['totalInterest']);
	  		
	  		unset($calculatorParams['value']);
	  		
	  		$ivalue++;
	  		
	  		// set start date to date of last payment
	  		
	  		//$startDate = $params['value'][$iivalue  + 4];
	  		
	  	}
  	
  	}
  	
  	$return['totals']['daysInPeriod'] = $totalsDaysInPeriod;
  	
  	$return['totals']['judgmentInterest'] = number_format($judgmentInterest, $this->decimalPlaces);
  	
  	if($amountPaid > $params['value'][1])
  	{
  	
  		$return['totals']['judgmentOutstanding'] = number_format(0, $this->decimalPlaces);
  		
  	}
  	else
  	{
  		
  		$return['totals']['judgmentOutstanding'] = number_format($params['value'][1] - $amountPaid, $this->decimalPlaces);
  	
  	}
  	
  	if($amountPaid > $params['value'][1])
  	{
  	
  		$return['totals']['judgmentInterestOutstanding'] = number_format($judgmentInterest - ($amountPaid - $params['value'][1]), $this->decimalPlaces);
  		
  	}
  	else
  	{
  		
  		$return['totals']['judgmentInterestOutstanding'] = number_format($judgmentInterest, $this->decimalPlaces);
  	
  	}
  	
  	$return['totals']['totalJudgmentAndInterest'] = number_format($params['value'][1] + $judgmentInterest, $this->decimalPlaces);

    return $return;
  	
  }
  
  /** 
   * calculateMileage()
   *
   * The Interest On JudgMent Debt Calculator
   * 
   * @package    LexisCalculate
   * @subpackage calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   *
   * @todo    migrate validation to extended symfony validation
   * 					month   * 
   * @params  [id]	= 1 // ignore
   *          [0]  	= distance
   *          [1]  	= rate
   *          [2]  	= frequency
   *          [3]  	= period
   *          [4]	= start-date
   *          [5]	= end-date
   * 
   */
  
  protected function calculateMileage($params, $calculatorSessionId = null)
  {
 
// echo 'function calculateMileade(' . print_r($params) . ')' . "\n\n";

    /**
     * PRE FLIGHT 
     */
        
    $return['params']       = $params;
    $return['error']        = false;
    $return['errorMessage'] = '';

    // values
        
  	if(!key_exists('value', $params))
    {

    	$return['error']        = true;
    	$return['errorMessage'] = 'Invalid call!';
            
    	return $return;
            
    }

    // distance
        
    if(!key_exists(1, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][1] < 0.01)
      {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid distance!';
            
        return $return;
        
      }
  	
    // rate
    
   if(!key_exists(3, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][3] < 1)
      {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid rate!';
            
        return $return;
        
      }

   
     /*
   // frequency
   
   if(//!key_exists(4, $params['value']) 
      //||!is_int($params['value'][0]) ||
       $params['value'][5] < 0)
      {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid frequency!';
            
        return $return;
        
      }

   	// period
   	
  	if(//!key_exists(5, $params['value'])
      //||!is_int($params['value'][0]) ||
      (strtolower($params['value'][5]) != 'day' && strtolower($params['value'][5]) != 'week' && strtolower($params['value'][5]) != 'month'))
      {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid Period!';
            
        return $return;
        
      }
	
    // start date
   
    /*if(//!key_exists(6,$params['value']))
        //||!is_int($params['value'][0]) 
        // $params['value'][2] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid start date';
            
      return $return;
            
    }
  
    // end date
   
    if(!key_exists(7,$params['value']))
        //||!is_int($params['value'][0]) 
        // $params['value'][2] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid end date';
            
      return $return;
            
    }*/
  
    // log
    
    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }

    /**
     * CALCULATION
     */
        
    // return a result
 
 		// return journey?
 		   
    if($params['value'][2])
    {
    	
			$params['value'][1] = $params['value'][1] * 2;
    	
			
			
    }
    
    $return['result']['journeyDistance'] = $params['value'][1];
    
    // journeys calculation
    
    if($params['value'][4] == 'true' )
    {
    	
    	// journeys value has been passed through
    	
			$return['result']['journeys'] = $params['value'][5];
	  	$return['result']['distance'] = $return['result']['journeys'] * $params['value'][1];
	    $return['result']['total']	  = number_format($return['result']['journeys'] * $params['value'][1] * ($params['value'][3] / 100), $this->decimalPlaces);
	    		
    	
    }
    else
    {
    	
   		// date range
   		
    	$startDateExploded = explode('-', $params['value'][8]);
    
    			$startDay = $startDateExploded[0];
    $startMonth = $startDateExploded[1];
    $startYear = $startDateExploded[2];
    
    $endDateExploded = explode('-', $params['value'][9]);
    
    $endDay = $endDateExploded[0];
    $endMonth = $endDateExploded[1];
    $endYear = $endDateExploded[2];
    
    $startTimestamp = gmmktime(0, 0, 0, $startMonth, $startDay, $startYear);
    
    $endTimestamp = gmmktime(0, 0, 0, $endMonth, $endDay + 1, $endYear);
  	
    
	    switch(strtolower($params['value'][7]))
	    {
	    	
	    	 
    	// return journey, double distance
	    	case 'day':
	    				
	    		$return['result']['journeys'] = round(($endTimestamp - $startTimestamp) / 86400) * $params['value'][6];
	  			$return['result']['distance'] = $return['result']['journeys'] * $params['value'][1];
	    		$return['result']['total']	  = number_format($return['result']['journeys'] * $params['value'][1] * ($params['value'][3] / 100), $this->decimalPlaces);
	    		
	    		break;
	
	    	case 'week':
				    		
	    		$return['result']['journeys'] = round(($endTimestamp - $startTimestamp) / 604800) * $params['value'][6];
					$return['result']['distance'] = $return['result']['journeys'] * $params['value'][1];
	    		$return['result']['total']	  = number_format($return['result']['journeys'] * $params['value'][1] * ($params['value'][3] / 100), $this->decimalPlaces);
	    	  
	    		break;
	    		
	    	case 'month':
	    		
	   			$return['result']['journeys'] = round(($endTimestamp - $startTimestamp) / 2620800) * $params['value'][6];
					$return['result']['distance'] = $return['result']['journeys'] * $params['value'][1];
	    		$return['result']['total']	  = number_format($return['result']['journeys'] * $params['value'][1] * ($params['value'][3] / 100), $this->decimalPlaces);
	   
	    		break;
	    	
	    	default:
	    		
	    		$return['error']        = true;
					$return['errorMessage'] = 'Invalid period';
	    	
					break;
	    		
	    	}
      	
    }
    
    return($return);
      	
  }

  /** 
   * calculateGrossToNetEarnings()
   *
   * The Gross to net earnings calculator
   * 
   * @package			LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo    migrate validation to extended symfony validation
   * 		  move all HMRC values into db or xml file
   * 
   * @params  [id] = 1 // ignore
   * 
   */

	protected function calculateGrossToNetEarnings($params, $calculatorSessionId = null)
  	{
    
// echo 'calculateNetIncome(' . print_r($params) . ', ' . $calculatorSessionId . ')' . "\n\n";

    /**
     * PRE FLIGHT 
     */
      
    $return['params']       = $params;
    $return['error']        = false;
    $return['errorMessage'] = '';

    // values
        
  	if(!key_exists('value', $params))
    {

    	$return['error']        = true;
    	$return['errorMessage'] = 'Invalid call!';
            
    	return $return;
            
    }

    // gross
        
    if(!key_exists(1, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][1] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid gross!';
            
      return $return;
        
    }

    //period
    
  	if(!key_exists(2, $params['value']) 
      //||!is_int($params['value'][0]) 
      || ($params['value'][2] != 'Day' && $params['value'][2] != 'Week' && $params['value'][2] != 'Month' && $params['value'][2] != 'Year' && $params['value'][2] != '13weeks'))
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid Period!';
            
      return $return;
        
    }
   
  	// age
            
    if(!key_exists(3, $params['value']) 
      //||!is_int($params['value'][0]) 
      //|| $params['value'][5] < 1)
 		)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid Tax Year!';
            
      return $return;
        
    }
    
  	// age
            
    if(!key_exists(4, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][4] < 1)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid Age!';
            
      return $return;
        
    }
    
    // tax code
        
   /* if(!key_exists(3, $params['value']) 
      //||!is_int($params['value'][0]) )
      )
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid Tax Code!';
            
      return $return;
        
    }
    
	// allowance
        
    if(!key_exists(4, $params['value']) 
      //||!is_int($params['value'][0]) 
) // is a number
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid Allowance!';
            
      return $return;
        
    }
    
    

    // start date
   
    if(!key_exists(5,$params['value'])
        //||!is_int($params['value'][0]) 
        || $params['value'][5] == '')
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid start date';
            
      return $return;
            
    }
  
    // end date
   
    if(!key_exists(6,$params['value'])
        //||!is_int($params['value'][0]) 
        || $params['value'][6] == '')
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid end date';
            
      return $return;
            
    }
  */
    // log

    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }
    
    $currentYear = str_pad($params['value'][3], 4, 0, STR_PAD_LEFT);
    
    /**
     * CALCULATION
     */
  	      
      			
    $arrTaxRates = array(
    	'1011' => array(
    		'financialYear' 		=> '2010 / 2011',
    		'personalAllowance' 	=> array(
    			1 => 6475,
    			2 => 6475,
    			3 => 9490,
    			4 => 9640,
    		),
    		'startingRate' 			=> 0,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> '',
    		'basicRate' 			=> 20,
    		'basicRateLimit' 		=> 37400,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 5044 / 52,
    		'UEL' 					=> 43888 / 52,
    		'primaryThreshold' 		=> 5720 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0910' => array(
    		'financialYear' 		=> '2009 / 2010',
    		'personalAllowance' 	=> array(
    			1 => 6475,
    			2 => 6475,
    			3 => 9490,
    			4 => 9640,
    		),
    		'startingRate' 			=> 0,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> '',
    		'basicRate' 			=> 20,
    		'basicRateLimit' 		=> 37400,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4940 / 52,
    		'UEL' 					=> 43888 / 52,
    		'primaryThreshold' 		=> 5720 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0809' => array(
    		'financialYear' 		=> '2008 / 2009',
    		'personalAllowance' 	=> array(
    			1 => 6035,
    			2 => 6035,
    			3 => 9030,
    			4 => 9180,
    		),
    		'startingRate' 			=> 0,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> '',
    		'basicRate' 			=> 20,
    		'basicRateLimit' 		=> 34800,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4680 / 52,
    		'UEL' 					=> 40040 / 52,
    		'primaryThreshold' 		=> 5460 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0708' => array(
    		'financialYear' 		=> '2007 / 2008',
    		'personalAllowance' 	=> array(
    			1 => 5225,
    			2 => 5225,
    			3 => 7550,
    			4 => 7690,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 2230,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 34600,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4524 / 52,
    		'UEL' 					=> 34840 / 52,
    		'primaryThreshold' 		=> 5200 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0607' => array(
    		'financialYear' 		=> '2006 / 2007',
    		'personalAllowance' 	=> array(
    			1 => 5035,
    			2 => 5035,
    			3 => 7280,
    			4 => 7420,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 2150,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 33300,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4368 / 52,
    		'UEL' 					=> 33540 / 52,
    		'primaryThreshold' 		=> 5044 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0506' => array(
    		'financialYear' 		=> '2005 / 2006',
    		'personalAllowance' 	=> array(
    			1 => 4895,
    			2 => 4895,
    			3 => 7090,
    			4 => 7220,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 2090,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 32400,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4264 / 52,
    		'UEL' 					=> 32760 / 52,
    		'primaryThreshold' 		=> 4888 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0405' => array(
    		'financialYear' 		=> '2004 / 2005',
    		'personalAllowance' 	=> array(
    			1 => 4745,
    			2 => 4745,
    			3 => 6830,
    			4 => 6950,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 2020,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 31400,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4108 / 52,
    		'UEL' 					=> 31720 / 52,
    		'primaryThreshold' 		=> 4732 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0304' => array(
    		'financialYear' 		=> '2003 / 2004',
    		'personalAllowance' 	=> array(
    			1 => 4615,
    			2 => 4615,
    			3 => 6610,
    			4 => 6720,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 1960,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 30500,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4004 / 52,
    		'UEL' 					=> 30940 / 52,
    		'primaryThreshold' 		=> 4628 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.11,
    		'insuranceRate2' 		=> 0.01,
    	),
    	'0203' => array(
    		'financialYear' 		=> '2002 / 2003',
    		'personalAllowance' 	=> array(
    			1 => 4615,
    			2 => 4615,
    			3 => 6100,
    			4 => 6370,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 1920,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 29900,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 4628 / 52,
    		'UEL' 					=> 30420 / 52,
    		'primaryThreshold' 		=> 4628 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.1,
    		'insuranceRate2' 		=> '',
    	),
    	'0102' => array(
    		'financialYear' 		=> '2001 / 2002',
    		'personalAllowance' 	=> array(
    			1 => 4535,
    			2 => 4535,
    			3 => 5990,
    			4 => 6260,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 1880,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 29400,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 3744 / 52,
    		'UEL' 					=> 29900 / 52,
    		'primaryThreshold' 		=> 4524 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.1,
    		'insuranceRate2' 		=> '',
    	),
    	'0001' => array(
    		'financialYear' 		=> '2000 / 2001',
    		'personalAllowance' 	=> array(
    			1 => 4385,
    			2 => 4385,
    			3 => 5790,
    			4 => 6050,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 1520,
    		'basicRate' 			=> 22,
    		'basicRateLimit' 		=> 28400,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 3952 / 52,
    		'UEL' 					=> 27820 / 52,
    		'primaryThreshold' 		=> 3952 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.1,
    		'insuranceRate2' 		=> '',
    	),
    	'9900' => array(
    		'financialYear' 		=> '1999 / 2000',
    		'personalAllowance' 	=> array(
    			1 => 4335,
    			2 => 4335,
    			3 => 5720,
    			4 => 5980,
    		),
    		'startingRate' 			=> 10,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 1500,
    		'basicRate' 			=> 23,
    		'basicRateLimit' 		=> 28000,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 3452 / 52,
    		'UEL' 					=> 26000 / 52,
    		'primaryThreshold' 		=> 3452 / 52,//0 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.1,
    		'insuranceRate2' 		=> '',
    	),
    	'9899' => array(
    		'financialYear' 		=> '1998 / 1999',
    		'personalAllowance' 	=> array(
    			1 => 4195,
    			2 => 4195,
    			3 => 5990,
    			4 => 6260,
    		),
    		'startingRate' 			=> 20,
    		'savingsStartingRate' 	=> '',
    		'startingRateLimit' 	=> 4300,
    		'basicRate' 			=> 23,
    		'basicRateLimit' 		=> 27100,//22800,
    		'higherRate' 			=> 40,
    		'higherRateLimit' 		=> '',
    		'additionalRate' 		=> '',
    		'LEL' 					=> 3328 / 52,
    		'UEL' 					=> 25220 / 52,
    		'primaryThreshold' 		=> 3328 / 52,//0 / 52,
    		'secondaryThreshold' 	=> '',
    		'insuranceRate1' 		=> 0.1,
    		'insuranceRate2' 		=> '',
    	),
    );
    /*echo "<pre>";
       print_r($arrTaxRates[$currentYear]);
      
    echo "</pre>";*/
    extract($arrTaxRates[$currentYear]);
  		

	// gross wages
	
	switch($params['value'][2])
	{
	
		case 'Year':
			
			$annualGross = $params['value'][1];
	
			break;
			
		case 'Month':
			
			$annualGross = $params['value'][1] * 12;
			
			break;
		
		case 'Week':
			
			$annualGross = $params['value'][1] * 52;
			
			break;
			
		case 'Day':
			
			$annualGross = $params['value'][1] * 260;
			
			break;
			
		case 'Hour':
			
			$annualGross = $params['value'][1] * 9750;
			
			break;
			
		case '13weeks':
			
			//$annualGross = ($params['value'][1] * 52) / 13;
			$annualGross = ($params['value'][1] * 4);
			
			break;	
			
	}
	
	//$resultGross = ($annualGross / 365) * (ceil(($endTimestamp - $startTimestamp) / 86400));
	
	// year
	
	$return['result']['financialYear'] = $financialYear;
	
	$return['result']['gross'] = number_format($annualGross , $this->decimalPlaces);
	
	// month
	
	$return['result']['monthGross'] = number_format(($annualGross / 12), $this->decimalPlaces);
	
	// week
	
	$return['result']['weekGross'] = number_format(($annualGross / 52), $this->decimalPlaces);
	
	// $personalAllowance
	
	// tax code override
	
	//if($params['value'][3] != '')
	//{

	//	$personalAllowance[$params['value'][5]] = substr($params['value'][3], 0, -1) . '9';
	
	//}
	
	// add on ay additional allowances
	$personalAllowance = $personalAllowance[$params['value'][4]]; 
	
	$totalPersonalAllowance = $personalAllowance + $params['value'][7];
	
	// year
	
	$return['result']['personalAllowance'] = number_format($totalPersonalAllowance, $this->decimalPlaces);
	
	$taxableIncome = ($annualGross - $totalPersonalAllowance) > 0 ? $annualGross - $totalPersonalAllowance : 0;
	
	$return['result']['taxableIncome'] = number_format($taxableIncome, $this->decimalPlaces);
	
	// month
	
	$return['result']['monthPersonalAllowance'] = number_format(($totalPersonalAllowance / 12), $this->decimalPlaces);

	$return['result']['monthTaxableIncome'] = number_format($taxableIncome / 12, $this->decimalPlaces);
	
	// week
	
	$return['result']['weekPersonalAllowance'] = number_format(($totalPersonalAllowance / 52), $this->decimalPlaces);
	
	$return['result']['weekTaxableIncome'] = number_format($taxableIncome / 52, $this->decimalPlaces);
	
	// taxableIncome
	
	//$return['result']['taxableIncome'] = $annualGross - $personalAllowance;
	
	//  $return['result']['taxableIncome'] = number_format($annualGross- $return['result']['personalAllowance'], 2);
	
//echo $return['result']['taxableIncome'];
	

	// startingRateTax
	
	$return['result']['startingTaxRate'] = $startingRate;
	
	//($startingRate / 365) * (ceil(($endTimestamp - $startTimestamp) / 86400));
	
	if(($params['value'][1] - $totalPersonalAllowance) > $startingRateLimit)
	{

		// year
		
		$return['result']['startingRateTax'] = number_format(($startingRateLimit * ($startingRate / 100)), $this->decimalPlaces);
		
		//month

		$return['result']['monthStartingRateTax'] = number_format((($startingRateLimit * ($startingRate / 100)) / 12), $this->decimalPlaces);
		
		//day
		
		$return['result']['weekStartingRateTax'] = number_format((($startingRateLimit * ($startingRate / 100)) / 52), $this->decimalPlaces);
		
	}
	else
	{

		//$return['result']['startingRateTax'] = number_format((((($resultGross/ 365) * (ceil(($endTimestamp - $startTimestamp) / 86400)) - ($return['result']['personalAllowance'] / 365) * (ceil(($endTimestamp - $startTimestamp) / 86400))) * (ceil(($endTimestamp - $startTimestamp) / 86400))) * $startingRate / 100), 2);
	
		// year
		
		$return['result']['startingRateTax'] = number_format(($taxableIncome * ($startingRate / 100)), $this->decimalPlaces);
		
		// month
		
		$return['result']['monthStartingRateTax'] = number_format((($taxableIncome * ($startingRate / 100)) / 12), $this->decimalPlaces);
		
		// day
		
		$return['result']['weekStartingRateTax'] = number_format((($taxableIncome * ($startingRate / 100)) / 52), $this->decimalPlaces);
		
	}
			
	// basicRateTax
	
	$return['result']['basicTaxRate'] = $basicRate;
	
  	if($annualGross - $totalPersonalAllowance >= $basicRateLimit)
	{

//echo "= (($basicRateLimit - $startingRateLimit) * ($basicRate / 100))";
		
		// year
		
		$basicRateTax = ($basicRateLimit - $startingRateLimit) * ($basicRate / 100);
		
		if($basicRateTax < 0)
		{
			$basicRateTax = 0;
		}
		
		$return['result']['basicRateTax'] = number_format($basicRateTax, $this->decimalPlaces);
		
		// month

		$return['result']['monthBasicRateTax'] = number_format(($basicRateTax / 12), $this->decimalPlaces);
		
		// day
		
		$return['result']['weekBasicRateTax'] = number_format(($basicRateTax / 52), $this->decimalPlaces);
	
	}
	else
	{

		$basicRateTax = ($taxableIncome - $startingRateLimit) * ($basicRate / 100);
		
		if($basicRateTax < 0)
		{
			$basicRateTax = 0;
		}
		//echo "= (((" . $resultGross. " - $return['result']['personalAllowance']]) - $startingRateLimit) * ($basicRate / 100)))";
		$return['result']['basicRateTax'] = number_format($basicRateTax, $this->decimalPlaces);
		
		// month

		$return['result']['monthBasicRateTax'] = number_format(($basicRateTax / 12), $this->decimalPlaces);
		
		// day
		
		$return['result']['weekBasicRateTax'] = number_format(($basicRateTax / 52), $this->decimalPlaces);
					
	}
	
	// higherRateTax

	$return['result']['higherTaxRate'] = $higherRate;
		
  	if($taxableIncome > $basicRateLimit)
	{

		// year
		
		$higherRateTax = ($taxableIncome - $basicRateLimit) * ($higherRate / 100);
		
		if($higherRateTax < 0)
		{
			$higherRateTax = 0;
		}
		
		$return['result']['higherRateTax'] = number_format($higherRateTax, $this->decimalPlaces);

		// month
		
		//$return['result']['monthHigherRateTax'] = number_format(((($annualGross - $totalPersonalAllowance) - $basicRateLimit) * ($higherRate / 100) / 12), 2);
		$return['result']['monthHigherRateTax'] = number_format(($higherRateTax / 12), $this->decimalPlaces);
		
		// week
		
		//$return['result']['weekHigherRateTax'] = number_format(((($annualGross - $totalPersonalAllowance) - $basicRateLimit) * ($higherRate / 100) / 52), 2);
		$return['result']['weekHigherRateTax'] = number_format(($higherRateTax / 52), $this->decimalPlaces);
		
		}
	else
	{

		$higherRateTax = '0.00';
		
		$return['result']['higherRateTax'] = '0.00';
	
		$return['result']['monthHigherRateTax'] = '0.00';

		$return['result']['weekHigherRateTax'] = '0.00';
	
	}
	// ni

	// year
	
	if(($annualGross / 52) > $UEL)
	{
		// above the weekly upper limit

		$nationalInsurance = ((($UEL - $primaryThreshold) * 52) * $insuranceRate1) + ((($annualGross) - ($UEL * 52)) * $insuranceRate2);

	}
	elseif($annualGross / 52 > $LEL) 
	{
		
		$nationalInsurance = ($annualGross - ($primaryThreshold * 52)) * $insuranceRate1;
		
	}
	else 
	{
		$nationalInsurance = 0;
	}
	
	if($nationalInsurance < 0 ||  $params['value'][4] > 2 )
	{
		$nationalInsurance	= 0;
	}
	
	$return['result']['nationalInsurance'] = number_format($nationalInsurance, $this->decimalPlaces);
	
	// month
	
	$return['result']['monthNationalInsurance'] = number_format(($nationalInsurance / 12), $this->decimalPlaces);
	
	// week
	
	$return['result']['weekNationalInsurance'] = number_format(($nationalInsurance / 52), $this->decimalPlaces);
	
	// net pay
	 //change for nic rates for year
	
	// year
	
	$netPay = $annualGross - $startingTaxRate - $basicRateTax - $higherRateTax - $nationalInsurance;
	
	$return['result']['netPay'] = number_format($netPay, $this->decimalPlaces);

	// month 
	
	$return['result']['monthNetPay'] = number_format($netPay / 12, $this->decimalPlaces);
	
	// week
	
	$return['result']['weekNetPay'] = number_format($netPay / 52, $this->decimalPlaces);
//print_r($return);
//exit();
    return $return;
    
  } 
  
  
  /** 
   * calculatePeriodicLoss()
   *
   * The periodic loss calculator
   * 
   * @package     LexisCalculate
   * @subpackage  calculator
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo    migrate validation to extended symfony validation
   * 
   * @params  [id] = 1 // ignore
   * 
   */
  
  protected function calculatePeriodicLoss($params, $calculatorSessionId = null)
  {
    
// echo 'calculatePeriodic(' . print_r($params) . ', ' . $calculatorSessionId . ')' . "\n\n";
    
     /**
     * PRE FLIGHT 
     */
        
    $return['params']       = $params;
    $return['error']        = false;
    $return['errorMessage'] = '';

    // values
        
  	if(!key_exists('value', $params))
    {

    	$return['error']        = true;
    	$return['errorMessage'] = 'Invalid call!';
            
    	return $return;
            
    }

    // amount
        
    if(!key_exists(1, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][1] < 0.01)
      {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid amount!';
            
        return $return;
        
      }
  	
   // frequency
   
   if(!key_exists(2, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][2] < 0)
      {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid frequency!';
            
        return $return;
        
      }

   	// period
  	
      if(!key_exists(3, $params['value'])
      //||!is_int($params['value'][0]) 
      || ($params['value'][3] != 'Day' && $params['value'][3] != 'Week' && $params['value'][3] != 'Month' && $params['value'][3] != 'Year'))
      {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid Period!';
            
        return $return;
        
      }
	
    // start date
   
    if(!key_exists(4,$params['value']))
        //||!is_int($params['value'][0]) 
        // $params['value'][2] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid start date';
            
      return $return;
            
    }
  
    // end date
   
    if(!key_exists(5,$params['value']))
        //||!is_int($params['value'][0]) 
        // $params['value'][2] < 0.01)
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid end date';
            
      return $return;
            
    }
  
    // log
    
    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }
    
    /**
     * CALCULATION
     */
        
    // return a result
  
    $startDateExploded = explode('-', $params['value'][4]);
    
    $startDay = $startDateExploded[0];
    $startMonth = $startDateExploded[1];
    $startYear = $startDateExploded[2];
    
    $endDateExploded = explode('-', $params['value'][5]);
    
    $endDay = $endDateExploded[0];
    $endMonth = $endDateExploded[1];
    $endYear = $endDateExploded[2];
    
    $startTimestamp = gmmktime(0, 0, 0, $startMonth, $startDay, $startYear);
    
    $endTimestamp = gmmktime(0, 0, 0, $endMonth, $endDay + 1, $endYear);
  	
    $return['result']['amount'] =  number_format($params['value'][1], $this->decimalPlaces);
      	
    switch($params['value'][3])
    {
    	
    	case 'Day':
    	case 'day':
    	case 'DAY':
    		$return['result']['frequencyCount'] = floor(($endTimestamp - $startTimestamp) / 86400);
    		$return['result']['events'] = (floor(($endTimestamp - $startTimestamp) / 86400)) * $params['value'][2];
  			$return['result']['total'] = number_format($return['result']['events'] * $params['value'][1], $this->decimalPlaces);
    		
    		break;

			case 'Week':
   		case 'week':
			case 'WEEK':

			$return['result']['frequencyCount'] = floor(($endTimestamp - $startTimestamp) / 604800);
    		$return['result']['events'] = (floor(($endTimestamp - $startTimestamp) / 604800)) * $params['value'][2];
			$return['result']['total'] = number_format($return['result']['events'] * $params['value'][1], $this->decimalPlaces);
    		
    		break;
    		
    	case 'Month':
    	case 'month':
    	case 'MONTH':
    		
    		$return['result']['frequencyCount'] = floor(($endTimestamp - $startTimestamp) / 2620800);
   			$return['result']['events'] = (floor(($endTimestamp - $startTimestamp) / 2620800)) * $params['value'][2];
			$return['result']['total'] = number_format($return['result']['events'] * $params['value'][1], $this->decimalPlaces);
    		
    		break;
    	
    	case 'Year':
    	case 'year':
    	case 'YEAR':
    		
    		$return['result']['frequencyCount'] = floor(($endTimestamp - $startTimestamp) / 31556926);
   			$return['result']['events'] = round((($endTimestamp - $startTimestamp) / 31556926) * $params['value'][2]);
			$return['result']['total'] = number_format($return['result']['events'] * $params['value'][1], $this->decimalPlaces);
    		
    		break;
    		
    	default:
    		
    		$return['error']        = true;
      	$return['errorMessage'] = 'Invalid period';
    	
      		break;
    		
    	}
    return($return);
      	
  }
  
  /** 
   * calculateVat()
   *
   * The VAT Calculator
   * 
   * @package     LexisCalculate
   * @subpackage  calculators
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo    migrate validation to extended symfony validation
   * 
   * @params  array   $params[id] // passthru
   * 				  string  $params['label'][0] // label
   *                  float   $params['value'][1] // amount
   *                  boolean $params['value'][2] // inclusive 
   *                  float   $params['value'][3] // vat rate
   * @params  string  $calculatorSessionId // the application key for calculation logging
   */
  
  protected function calculateVat($params, $calculatorSessionId = false)
  {

// echo 'function calculateVat(' . print_r($params) . ', ' . $calculatorSessionId . ')' . "\n\n";

    /**
     * PRE FLIGHT 
     */
        
    $return['params']       = $params;
    $return['error']        = false;
    $return['errorMessage'] = '';

    // values
        
    if(!key_exists('value', $params))
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid call!';
            
      return $return;
            
    }

    // amount
        
    if(!key_exists(1, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][1] < 0.01)
    {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid amount!';
            
        return $return;
        
    }

    // inclusive / exclusive
        
    if(!key_exists(2, $params['value']))
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Inclusive or exclusive is required';
            
      return $return;
            
    }
        
    // rate
        
    if(!key_exists(3, $params['value'])
      //||!is_int($params['value'][0]) 
      //|| $params['value'][3] < 0.01)
  )
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid date';
            
      return $return;

    }
        
    // log
    
    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }
        
    /**
     * CALCULATION
     */
        
    // return a result

    // calculate rate based on date
    
    $dateExploded = explode('-', $params['value'][3]);
    
    $day = $dateExploded[0];
    $month = $dateExploded[1];
    $year = $dateExploded[2];
    
    $dateTimestamp = gmmktime(0, 0, 0, $month, $day, $year);
    
    if($dateTimestamp < gmmktime(0, 0, 0, 1, 1, 2010) && $dateTimestamp >= gmmktime(0, 0, 0, 1, 1, 2009)) {
    	$vatRate = 15;
    } elseif($dateTimestamp < gmmktime(0, 0, 0, 1, 4, 2011) && $dateTimestamp > gmmktime(0, 0, 0, 1, 1, 2010)) {
    	$vatRate = 17.5;
    } elseif($dateTimestamp >= gmmktime(0, 0, 0, 1, 4, 2011)) {
    	$vatRate = 20;
    } else {
    	$vatRate = 17.5;
    }
    
    if($params['value'][2] == 'true')
    {
            
      // inclusive
        
      $return['result']['net']     = number_format($params['value'][1] / (1 + ($vatRate / 100)), $this->decimalPlaces);
      $return['result']['vatRate'] = $vatRate;
      $return['result']['vat']     = number_format($params['value'][1] - ($params['value'][1] / (1 + ($vatRate / 100))), $this->decimalPlaces);
      $return['result']['total']   = number_format($params['value'][1], $this->decimalPlaces);
            
    }
    else 
    {

    	// exclusive
            
      $return['result']['net']   = number_format($params['value'][1], $this->decimalPlaces);
      $return['result']['vatRate'] = $vatRate;
      $return['result']['vat']   = number_format($params['value'][1] * ($vatRate / 100), $this->decimalPlaces);
      $return['result']['total'] = number_format($params['value'][1] + ($params['value'][1] * ($vatRate / 100)), $this->decimalPlaces);
            
    }
        
    return $return;
        
  }
  
/** 
   * calculateSpecialDamages()
   *
   * The SpecialDamages Calculator
   * 
   * @package     LexisCalculate
   * @subpackage  calculators
   * @author      Daniel Mullin daniel.mullin@lexisnexis.co.uk 
   * @author      Daniel Mullin email@danielmullin.com 
   * @version     0.1
   * 
   * @todo    migrate validation to extended symfony validation
   * 
   * @params  array   $params[id] // passthru
   * 				  string  $params['label'][0] // label
   *                  float   $params['value'][1] // amount
   *                  string  $params['value'][2] // date of loss 
   *                  string  $params['value'][3] // calculateTo
   *                  boolean $params['value'][4] // ongoingOrFixed
   *                  float  $params['value'][5] // rate
   * @params  string  $calculatorSessionId // the application key for calculation logging
   */
  
  protected function calculateInterestOnSpecialDamages($params, $calculatorSessionId = false)
  {

// echo 'function calculateVat(' . print_r($params) . ', ' . $calculatorSessionId . ')' . "\n\n";

    /**
     * PRE FLIGHT 
     */
        
    $return['params']       = $params;
    $return['error']        = false;
    $return['errorMessage'] = '';

    // values
        
    if(!key_exists('value', $params))
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid call!';
            
      return $return;
            
    }

    // amount
        
    if(!key_exists(1, $params['value']) 
      //||!is_int($params['value'][0]) 
      || $params['value'][1] < 0.01)
    {

        $return['error']        = true;
        $return['errorMessage'] = 'Invalid amount!';
            
        return $return;
        
    }
        
    // date of loss
        
    if(!key_exists(3, $params['value'])
      //||!is_int($params['value'][0]) 
      //|| $params['value'][3] < 0.01)
  )
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid date of loss';
            
      return $return;

    }
    
  // calculateTo
        
    if(!key_exists(3, $params['value'])
      //||!is_int($params['value'][0]) 
      //|| $params['value'][3] < 0.01)
  )
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Invalid calculate to date';
            
      return $return;

    }
    
    // ongoingOrFixed
    
      // inclusive / exclusive
        
    if(!key_exists(4, $params['value']))
    {

      $return['error']        = true;
      $return['errorMessage'] = 'Inclusive or exclusive is required';
            
      return $return;
            
    }
        
    // log
    
    if($calculatorSessionId)
    {

      // add to session calculations if session id supplied ie is a new calculation      
      
      $this->logCalculation($calculatorSessionId, $params);
      
    }
        
    /**
     * CALCULATION
     */

    $accountRates[0]['dateOfRateChange'] = '01-01-1970';
   	$accountRates[0]['timestamp'] = 0; 
    $accountRates[0]['rate'] = 10.25;
    
   	$accountRates[1]['dateOfRateChange'] = '01-02-1993';
   	$accountRates[1]['timestamp'] = 728524800; 
    $accountRates[1]['rate'] = 8;
    
    $accountRates[2]['dateOfRateChange'] = '01-08-1999'; 
    $accountRates[2]['timestamp'] = 933465600; 
    $accountRates[2]['rate'] = 7;
    
    $accountRates[3]['dateOfRateChange'] = '01-02-2002';
    $accountRates[3]['timestamp'] = 1012521600 ;
    $accountRates[3]['rate'] = 6;
    
    $accountRates[4]['dateOfRateChange'] = '01-02-2009';
    $accountRates[4]['timestamp'] = 1233446400;
    $accountRates[4]['rate'] = 3;
    
    $accountRates[5]['dateOfRateChange'] = '01-06-2009';
    $accountRates[5]['timestamp'] = 1243814400;
    $accountRates[5]['rate'] = 1.5;
    
    $accountRates[6]['dateOfRateChange'] = '01-07-2009';
    $accountRates[6]['timestamp'] = 1246406400;
    $accountRates[6]['rate'] = 0.5;
    
    $currentRate = 0.5;
    
    $return['totals']['displayAccountRate'] = 'Full';
    
    $return['totals']['cumulativeInterestRate'] = 0;
    
    // return a result
    
    if($params['value'][5] > 0)
    {

    	// single fixed rate so we can over ride the meatier calculation and return the values
    
    	if($params['value'][4] == 'false')
    	{

    		$accountRate = number_format(($params['value'][5] / 2), $this->decimalPlaces);
    		
    		 $return['totals']['displayAccountRate'] = 'Half';
 

    	}
    	else
    	{

    		$accountRate = $params['value'][5];
    		
    	}
    	
    	$calculatorParams['value'][] = $params['value'][0];
  		$calculatorParams['value'][] = $params['value'][1];
  		$calculatorParams['value'][] = $accountRate;
  		$calculatorParams['value'][] = $params['value'][2];
  		$calculatorParams['value'][] = $params['value'][3];
  		
  		$return['params']['id'] = 8;
  		
  		$return['result'][$i] = $this->calculateInterestOnGeneralDamages($calculatorParams);
  		
  		$return['totals']['displayRate'] = $accountRate;
  		 
  		// additional
  		
  		unset($calculatorParams['value']);
    	
    }
    else
    {

    	// sort dates
    	
    	$startDateExploded = explode('-', $params['value'][2]);
    
		$startDay = $startDateExploded[0];
   		$startMonth = $startDateExploded[1];
    	$startYear = $startDateExploded[2];
    
    	$endDateExploded = explode('-', $params['value'][3]);
    
    	$endDay = $endDateExploded[0];
    	$endMonth = $endDateExploded[1];
    	$endYear = $endDateExploded[2];
    
    	$startTimestamp = gmmktime(0, 0, 0, $startMonth, $startDay, $startYear);
    
    	$endTimestamp = gmmktime(0, 0, 0, $endMonth, $endDay + 1, $endYear);
    	
    	$i = 0;
    	
    	$max = count($accountRates) - 1;
    	
    	$cummulativeAmount = $params['value'][1];
    		
    	foreach ($accountRates as $key => $accountRate) 
    	{

//print_r($accountRate);
    		
   			if($key < $max)
    		{
    		
    			if($startTimestamp < $accountRates[($key + 1)]['timestamp'] && $endTimestamp < $accountRates[($key + 1)]['timestamp'])
	    		{
	
	    			// time frame is in this segment only
	    			
	    			// single fixed rate so we can over ride the meatier calculatoion and return the values
	    
			    	if($params['value'][4] == 'false')
			    	{
			
			    		$accountRatePercentage = number_format(($accountRate['rate'] / 2), $this->decimalPlaces);
			
			    		$return['totals']['displayAccountRate'] = 'Half';
			    		
			    	}
			    	else
			    	{
			
			    		$accountRatePercentage = $accountRate['rate'];
			    		
			    	}
	    	
			    	$calculatorParams['value'][] = $params['value'][0];
			  		$calculatorParams['value'][] = $cummulativeAmount;
			  		$calculatorParams['value'][] = $accountRatePercentage;
			  		$calculatorParams['value'][] = date('d-m-Y', $startTimestamp);
			  		$calculatorParams['value'][] = $accountRates[$key + 1]['dateOfRateChange'];
	  		
	  				$return['params']['id'] = 8;
	  		
	  				$return['result'][$i] = $this->calculateInterestOnGeneralDamages($calculatorParams);
	  				
	  				if($params['value'][6])
	  				{
	  					
	  					$cummulativeAmount = str_replace(',', '', $return['result'][$i]['result']['totalIncludingInterest']);
	  				
	  				}
	  				
	  				$return['totals']['interest'] = $return['totals']['interest'] + str_replace( ',', '', $return['result'][$i]['result']['totalInterest']);
				
	  				$return['totals']['cumulativeInterestRate'] = $return['totals']['cumulativeInterestRate'] + $return['result'][$i]['result']['rawCumulativeInterest'];
	  				
	  				// additional
	  		
	  				unset($calculatorParams['value']);
	
	    			
	    		}
	    		elseif($startTimestamp < $accountRates[$key + 1]['timestamp'] && $endTimestamp > $accountRates[$key + 1]['timestamp'])
	    		{
	
	    			// timeframe transcends this segment
	    			
	    			// time frame is in this segment only
	    			
	    			// single fixed rate so we can over ride the meatier calculatoion and return the values
	    
			    	if($params['value'][4] == 'false')
			    	{
			
			    		$accountRatePercentage = number_format(($accountRate['rate']/ 2), $this->decimalPlaces);
			
			    		$return['totals']['displayAccountRate'] = 'Half';
			    		
			    	}
			    	else
			    	{
			
			    		$accountRatePercentage = $accountRate['rate'];
			    		
			    	}
	    	
			    	$calculatorParams['value'][] = $params['value'][0];
			  		$calculatorParams['value'][] = $cummulativeAmount;
			  		$calculatorParams['value'][] = $accountRatePercentage;
			  		$calculatorParams['value'][] = date('d-m-Y', $startTimestamp);
			  		$calculatorParams['value'][] = $accountRates[$key + 1]['dateOfRateChange'];
	  		
	  				$return['params']['id'] = 8;
	  		
	  				$return['result'][$i] = $this->calculateInterestOnGeneralDamages($calculatorParams);
	  				
	  				// additional
	  		
	  				if($params['value'][6])
	  				{
	  					
	  					$cummulativeAmount = str_replace(',', '', $return['result'][$i]['result']['totalIncludingInterest']);
	  				
	  				}
	  				
	  				$return['totals']['interest'] = $return['totals']['interest'] + str_replace( ',', '', $return['result'][$i]['result']['totalInterest']);
  	  				
	  				$return['totals']['cumulativeInterestRate'] = $return['totals']['cumulativeInterestRate'] + $return['result'][$i]['result']['rawCumulativeInterest'];
	  				
	  				unset($calculatorParams['value']);
	  		
	    			$startTimestamp = $accountRates[$key + 1]['timestamp'];
	   
	    		}
				elseif($endTimestamp < $accountRates[$key + 1]['timestamp'])
				{
	
					// ends in this period
				
					if($params['value'][4] == 'false')
			    	{
			
			    		$accountRatePercentage = number_format(($accountRate['rate'] / 2), $this->decimalPlaces);
			
			    		$return['totals']['displayAccountRate'] = 'Half';
			    		
			    	}
			    	else
			    	{
			
			    		$accountRatePercentage = $accountRate['rate'];
			    		
			    	}
	    	
			    	$calculatorParams['value'][] = $params['value'][0];
			  		$calculatorParams['value'][] = $cummulativeAmount;
			  		$calculatorParams['value'][] = $accountRatePercentage;
			  		$calculatorParams['value'][] = date('d-m-Y', $startTimestamp);
			  		$calculatorParams['value'][] = date('d-m-Y', $endTimestamp);
	  		
	  				$return['params']['id'] = 8;
	  		
	  				$return['result'][$i] = $this->calculateInterestOnGeneralDamages($calculatorParams);
	  		
	  				// additional
	  		
	  				$cummulativeAmount = str_replace( ',', '', $return['result'][$i]['result']['totalIncludingInterest']);
	  				
	  				$return['totals']['interest'] = $return['totals']['interest'] + str_replace( ',', '', $return['result'][$i]['result']['totalInterest']);
		
	  				$return['totals']['cumulativeInterestRate'] = $return['totals']['cumulativeInterestRate'] + $return['result'][$i]['result']['rawCumulativeInterest'];
	  				
	  				unset($calculatorParams['value']);
	  		
	    			$startTimestamp = $accountRates[$key + 1]['timestamp'];
				
				}

				
				
	    	}
    	
	    	$i++;
	    	
    	}
    	
    	if($endTimestamp > $accountRates[$key]['timestamp'])
    	{

    		// ends in this period
				
			if($params['value'][4] == 'false')
	    	{
			
		   		$accountRatePercentage = number_format(($currentRate / 2), $this->decimalPlaces);
			
			  	$return['totals']['displayAccountRate'] = 'Half';
			    		
			}
			else
			{

				$accountRatePercentage = $currentRate;
			    		
			}
	    	
			$calculatorParams['value'][] = $params['value'][0];
			$calculatorParams['value'][] = $cummulativeAmount;
			$calculatorParams['value'][] = $accountRatePercentage;
			$calculatorParams['value'][] = date('d-m-Y', $startTimestamp);
			$calculatorParams['value'][] = date('d-m-Y', $endTimestamp);
	  		
	  		$return['params']['id'] = 8;
	  		
	  		$return['result'][$i] = $this->calculateInterestOnGeneralDamages($calculatorParams);
	  		
	  		// additional
	  		
	  		$cummulativeAmount = str_replace( ',', '', $return['result'][$i]['result']['totalIncludingInterest']);
	  				
	  		$return['totals']['interest'] = $return['totals']['interest'] + str_replace( ',', '', $return['result'][$i]['result']['totalInterest']);
		
	  		$return['totals']['cumulativeInterestRate'] = $return['totals']['cumulativeInterestRate'] + $return['result'][$i]['result']['rawCumulativeInterest'];
	  				
	  		unset($calculatorParams['value']);
	  		
	    	$startTimestamp = $accountRates[$key + 1]['timestamp'];
    		
    	}
    	    	
    }
    
    $return['amount'] = number_format($params['value'][1], $this->decimalPlaces);
    
    $return['totals']['interest'] = number_format($return['totals']['interest'], $this->decimalPlaces);
    
	$return['totals']['AggregatePercentageRate'] = number_format($return['totals']['cumulativeInterestRate'], $this->decimalPlaces+2);
	
    $return['totals']['cumulativeInterestRate'] = number_format($return['totals']['cumulativeInterestRate'], $this->decimalPlaces);
	

//print_r($return);

//exit();
    
    return $return;
        
  }
  
  protected function calculateInflation($params, $calculatorSessionId = false)
  	{
  		
		if(strstr($params['value']['2'],'minus'))
		{
			$value					=	explode("minus",trim($params['value']['2']));
			$paramsValue			=   explode("-",$value[1]);
			$type					=	"-";
		}
		else
		{
			$paramsValue			=	explode("-",$params['value']['2']);
			$type					=	"";
		}
		
		$params['value'][3]		=	$paramsValue[1];
		$params['value'][4]		=	date('Y');
		$params['value'][5]		=	$paramsValue[0];
		$params['value'][7]		=	$type;
		
	/*	if($type=='-')
		{
			$params['value'][6]		=	number_format(($params['value'][1]) - ($params['value'][1] * $params['value'][5] / 100),2);
		}
		else
		{
			$params['value'][6]		=	number_format($params['value'][1] + ($params['value'][1] * $params['value'][5] / 100),2);
		}*/
	
		$params['value'][6]		=	number_format($params['value'][1] * $params['value'][5],$this->decimalPlaces);
	
		
		$params['value'][5]		=	number_format(($paramsValue[0]-1) * 100,$this->decimalPlaces);
		
		//print_r($params);
	
    	if($calculatorSessionId)
    	{

      		// add to session calculations if session id supplied ie is a new calculation      
      		$this->logCalculation($calculatorSessionId, $params);
    	}
		
    	$params['value'][1]		=	number_format($params['value'][1],$this->decimalPlaces);
		
		$return['params']       = 	$params;
		$return['error']        = 	false;
		$return['errorMessage'] = '';
		return $return;
	}	

}

//done