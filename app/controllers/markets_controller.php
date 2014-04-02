<?php
/**
 * This is the master markets class that will handle all the master pages for markets insignt.
 * @author Mohit Khurana
 */
class MarketsController extends AppController
{
	# Controller Class Name
	var $name = 'Markets';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form', 'Custom');
	# Array of components used in this controller.
	var $components = array('utility', 'Session', 'Cookie');
	#Flag to check error messages.
	var $flagErrMsg = 0;
	#Flag to check error messages.
	var $flagSuccessMsg = 0;
	# Array for error messages.
	var $arrErrMsg = array();
	#Flag to check success messages.
	var $arrSucMsg = array();
	# Save data array
	var $arrMarketSaveData;

    /**
     * This functiion is to display markets insight page.
     * @todo Auto Search ID save issue to be resolved yet.
     */
    function index($flagSuccessMsg = 0)
    {
			# Include Layout
    	$this->layout='front';
			$this->Session->delete('conditionsArr');
			# Error message DIVs on view.		
			$this->setMessageDivDefaultStatus();
			# Set timestamp value
			$timeStamp = strtotime("now");
		    	
    	# Set How Come Array
    	# Import Content Type model
    	App::import('Model', 'Insightabout');
    	# Create Content Type model object
			$this->Insightabout = new Insightabout();
    	# Set Who Content Type Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
    	
    	# Set Who Market Array
    	$this->set('arrWhoMarket',$this->Market->getMarkets());
    	
    	# Import Productfamilyname model
    	App::import('Model', 'Productfamilyname');
    	# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();
    	# Set Who Product Family Names Array
    	$this->set('arrProductFamilyNames',$this->Productfamilyname->getProductFamilyNames());

    	# Import Productfamilyname model
    	App::import('Model', 'Practicearea');
    	# Create Productfamilyname model object
			$this->Practicearea = new Practicearea();
    	# Set Who Product Family Names Array
    	$this->set('arrPracticeArea',$this->Practicearea->getPracticeArea());
    	
    	if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');
			
    	# Check if the form is submitted.
    	if(isset($this->data) && !empty($this->data))
    	{	
    		$arrMarketSaveData = $this->data;

    		if($this->serverValidate($arrMarketSaveData))
				{					
					# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();
	
					# verify firm id and save id else save name.
					if(isset($arrMarketSaveData['Firm']['what_firm_name']) && trim($arrMarketSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrMarketSaveData['Firm']['what_firm_name']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrMarketSaveData['Market']['what_firm_name'] = $firmParentID;
						else
							$arrMarketSaveData['Market']['what_firm_name_text'] = $arrMarketSaveData['Firm']['what_firm_name'];
					}
				
				#Save User Id of the current user into the insights table.
				$arrMarketSaveData['Market']['user_id'] = $this->Session->read('current_user_id');

				# Check if attachment is there.
				if (isset($arrMarketSaveData['MarketAttachment']['attachment_name']['name']) && !empty($arrMarketSaveData['MarketAttachment']['attachment_name']['name']))
				{
					# Get attachment extension.
					$attachmentExtension =  pathinfo($arrMarketSaveData['MarketAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
					#Get new name for attachment to be saved into database.
					$attachmentNewName = str_replace(pathinfo($arrMarketSaveData['MarketAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrMarketSaveData['MarketAttachment']['attachment_name']['name']);
					if($this->serverValidateAttachment($attachmentExtension,$arrMarketSaveData['MarketAttachment']['attachment_name']['size']))
					{
						# Verify if attachment saved.
						if($this->utility->uploadAttachment($arrMarketSaveData['MarketAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,MARKET_ATTACHMENT_UPLOAD_PATH))
						{
							# Save Attachment New Name into database.
							$arrMarketSaveData['Market']['attachment_name'] = $attachmentNewName;
							# Save Attachment Original Name into database.
							$arrMarketSaveData['Market']['attachment_real_name'] = $arrMarketSaveData['MarketAttachment']['attachment_name']['name'];
						
						}
					}
				}

				// Code by Pragya Dave - fixed special characters insertion				
				$arrMarketSaveData['Market']['insight_summary'] = $this->utility->parseString($this->data['Market']['insight_summary']);
				$arrMarketSaveData['Market']['do_action'] = $this->utility->parseString($this->data['Market']['do_action']);

				# Verify if there is no error.
				if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
				{
					#Save Competitor Insight into database.
					$this->Insight->save($arrMarketSaveData['Market']);
					# Redirect if save is successful.
					$this->redirect(SITE_URL . '/markets/index/1');
				}
			}
		}		
    }

	/**
	 * This functiion is to display update markets insight records page.
	 */
    function records($id = '', $flagSuccessMsg = '')
    {
				$this->layout='front';
	
				# Check for existing insight for record.
				$this->checkInsightRedirect($id);
		
				# Call function to set default display value for error messages.
				$this->setMessageDivDefaultStatus();
		
				# Set timestamp value
				$timeStamp = strtotime("now");
	
	
				# Import Content Type model
				App::import('Model', 'Insightabout');
				# Create Content Type model object
				$this->Insightabout = new Insightabout();
				# Set Who Content Type Array
				$this->set('arrHowCome',$this->Insightabout->returnStaticData());
	
				# Set Who Market Array
				$this->set('arrWhoMarket',$this->Market->getMarkets());
	
				# Import Productfamilyname model
				App::import('Model', 'Productfamilyname');
				# Create Productfamilyname model object
				$this->Productfamilyname = new Productfamilyname();
				# Set Who Product Family Names Array
				$this->set('arrProductFamilyNames',$this->Productfamilyname->getProductFamilyNames());
			
				# Import Practicearea model
				App::import('Model', 'Practicearea');
				# Create Practicearea model object
				$this->Practicearea = new Practicearea();
				# Set Who Practicearea Array
				$this->set('arrPracticeArea',$this->Practicearea->getPracticeArea());
	
	
				# Import Insight model
				App::import('Model', 'Insight');
				# Create Insight model object
				$this->Insight = new Insight();
				
				# Import Statusinsight model
				App::import('Model', 'Statusinsight');
				# Create Statusinsight model object
				$this->Statusinsight = new Statusinsight();
				# Set Who Statusinsight Array
				$this->set('arrCurrentStatus',$this->Statusinsight->getStatusList(TRUE));
		
				# Import Pilotgroup model
				App::import('Model', 'Pilotgroup');
				# Create Statusinsight model object
				$this->Pilotgroup = new Pilotgroup();
				# Set Who Pilotgroup Array
				$this->set('arrDelegatedTo',$this->Pilotgroup->getPilotGroups(TRUE));
	
				if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
					$this->set('successDivSave','block');
	
				# Set id value for edit.
				$this->set('id',$id);
				$this->Insight->id = $id;
				$this->set('edit_flag', "");		
				# Set Customer Array
				if(isset($this->data) && !empty($this->data))
				{
					# Set form data values to user defined array.
					$arrMarketSaveData = $this->data;
				 
					if($this->serverValidate($arrMarketSaveData))
					{					
	
						# Set flag for edit mode.
					$this->set('edit_flag', 'edit');
					# verify firm id and save id else save name.
					if(isset($arrMarketSaveData['Firm']['what_firm_name']) && trim($arrMarketSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrMarketSaveData['Firm']['what_firm_name']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrMarketSaveData['Market']['what_firm_name'] = $firmParentID;
						else
							$arrMarketSaveData['Market']['what_firm_name_text'] = $arrMarketSaveData['Firm']['what_firm_name'];
					}
					
					#Save User Id of the current user into the insights table.
					$arrMarketSaveData['Market']['user_id'] = $this->Insight->getCreatedById($arrMarketSaveData['Market']['id']);
	
					# Check if attachment is there.
					if (isset($arrMarketSaveData['MarketAttachment']['attachment_name']['name']) && !empty($arrMarketSaveData['MarketAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrMarketSaveData['MarketAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrMarketSaveData['MarketAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrMarketSaveData['MarketAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrMarketSaveData['MarketAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
							if($this->utility->uploadAttachment($arrMarketSaveData['MarketAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,MARKET_ATTACHMENT_UPLOAD_PATH))
							{
	
								# Set old file to be removed.
								$file = new File(MARKET_ATTACHMENT_UPLOAD_PATH . '/' . $arrMarketSaveData['MarketAttachment']['old_attachment_name']);
								# Remove file.
								$file->delete();
	
								# Save Attachment New Name into database.
								$arrMarketSaveData['Market']['attachment_name'] = $attachmentNewName;
								# Save Attachment Original Name into database.
								$arrMarketSaveData['Market']['attachment_real_name'] = $arrMarketSaveData['MarketAttachment']['attachment_name']['name'];
							}
						}
					}
					else if(isset($arrMarketSaveData['MarketAttachment']['old_attachment_name']))
					{
							$arrMarketSaveData['Market']['attachment_name'] = $arrMarketSaveData['MarketAttachment']['old_attachment_name'];
							//$arrMarketSaveData['Market']['attachment_real_name'] = $arrMarketSaveData['MarketAttachment']['old_attachment_real_name'];					
					}
					else {
							$arrMarketSaveData['Market']['attachment_name'] = NULL;				
					}
	
				 if(isset($arrMarketSaveData['Market']['insight_status'])&&$arrMarketSaveData['Market']['insight_status']>0 && isset($arrMarketSaveData['Market']['insight_status_changed']))
					{
						 #updated date
						 $arrMarketSaveData['Market']['date_updated'] = date('Y-m-d H:i:s', time());
					}
					#data updated by
					$arrMarketSaveData['Market']['updated_by'] =$this->Session->read('current_user_id');
					//pr($arrMarketSaveData['Market']);die;
					
					// Code by Pragya Dave - fixed special characters edit (08/02/2011)
					$arrMarketSaveData['Market']['insight_summary'] = $this->utility->parseString($arrMarketSaveData['Market']['insight_summary']);
					$arrMarketSaveData['Market']['do_action'] = $this->utility->parseString($arrMarketSaveData['Market']['do_action']);

					# Verify if there is no error.
					if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
					{
						#Save Customer Insight into database.
						$this->Insight->save($arrMarketSaveData['Market']);
						# Redirect if save is successful.
						$this->redirect(SITE_URL . '/markets/records/'.$id.'/1');
					}
				}		
			}	
			//else 
			//	{
				# Reading insight record on the basis of $id.
						$this->data = $this->Insight->read();
				$this->data['Market']['what_how_come'] = $this->data['Insight']['what_how_come'];
				$this->data['Market']['what_source_name'] = $this->data['Insight']['what_source_name'];
				$this->data['Market']['market_id'] = $this->data['Insight']['market_id'];
				
				$this->data['Market']['insight_summary'] =  $this->utility->parseString($this->data['Insight']['insight_summary']);
				$this->data['Market']['relates_product_family_id'] = $this->data['Insight']['relates_product_family_id'];
				$this->data['Market']['practice_area_id'] = $this->data['Insight']['practice_area_id'];
				$this->data['Market']['do_action'] = $this->data['Insight']['do_action'];
				
				# Import Firm model
				if (isset($this->data['Insight']['what_firm_name']) && $this->data['Insight']['what_firm_name']>0) 
				{
					$this->data['Firm']['what_firm_name'] = $this->processFirmId($this->data['Insight']['what_firm_name']);
				}
				else
				{
					$this->data['Firm']['what_firm_name'] = $this->data['Insight']['what_firm_name_text'];
				}
				/*if(isset($this->data['Insight']['insight_status']) && $this->data['Insight']['insight_status']>0) {
						$this->data['Insight']['current_status_text'] = $this->Statusinsight->getStatusById($this->data['Insight']['current_status']); 
				}else{
					$this->data['Insight']['current_status_text'] = '';
				}*/
				# Set variable values for view.
				//pr($this->data);
				$this->set('what_how_come', $this->data['Insight']['what_how_come']); 
				$this->set('product_family_name_label', $this->data['Insight']['relates_product_family_id']);
				$this->set('practice_area_label', $this->data['Insight']['practice_area_id']);
				$this->set('do_action_dummy', $this->data['Insight']['do_action']);
				$this->set('attachment_real_name', $this->data['Insight']['attachment_real_name']);
				$this->set('attachment_name', $this->data['Insight']['attachment_name']);
				$this->set('current_status_label', $this->data['Insight']['insight_status']);
				$this->set('deligated_to_selected', $this->data['Insight']['deligated_to']);
				//if($this->data['Insight']['current_status_text'] == 'Delegated')	{
						//$this->set('deligated_to_selected', $this->data['Insight']['deligated_to']);
						/*$this->set('deligated_disable', FALSE);
				}else{
						$this->set('deligated_to_selected', 0);
						$this->set('deligated_disable', TRUE);
				}*/
    	//}
				# URL to go back to search result page.
				$this->set('backUrl', $this->Cookie->read('backUrl'));	    
    } //End of records action.
    
    /**
     * This functiion is to display markets insight search page.
     */
    function search($insightId = '', $found = '')
    {
				$this->layout='front';
				# Setting current url to redirect in case no result found.
				$this->Cookie->write('currentUrl', SITE_URL.'/markets/search');
				$this->Cookie->write('backUrl', SITE_URL.'/markets/search');
		
				# Removing conditions array from session if any.
				$this->Session->delete('conditionsArr');
	
				# Set How Come Array
				# Import Content Type model
				App::import('Model', 'Insightabout');
				# Create Content Type model object
				$this->Insightabout = new Insightabout();
				# Set Who Content Type Array
				$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
			
				# Set Who Market Array
				$this->set('arrWhoMarket',$this->Market->getMarkets());
	
				# Import Pilotgroup model
				App::import('Model', 'Pilotgroup');
				# Create Pilotgroup model object
				$this->Pilotgroup = new Pilotgroup();
				# Set Pilotgroup names array for search view.
				$arrCreatedBy = $this->Pilotgroup->getPilotGroups(TRUE); //True passed for protect pilot group key id for dropdown.
				$this->set('arrCreatedBy', $arrCreatedBy);
		
				# Import Productfamilyname model
				App::import('Model', 'Productfamilyname');
				# Create Productfamilyname model object
				$this->Productfamilyname = new Productfamilyname();
				# Set product family names array for search view.
				$arrFamilynames = $this->Productfamilyname->getProductFamilyNames(TRUE);
				$this->set('arrFamilyNames', $arrFamilynames);
		
				# Import Practicearea model
				App::import('Model', 'Practicearea');
				# Create Practicearea model object
				$this->Practicearea = new Practicearea();
				# Set practice area names array for search view.
				$arrPracticeArea = $this->Practicearea->getPracticeArea();
				$this->set('arrPracticeArea', $arrPracticeArea);		

				# Import Productfamilyname model
				App::import('Model', 'Statusinsight');
				# Create Productfamilyname model object
				$this->Statusinsight = new Statusinsight();
				# Set Who Product Family Names Array
				$this->set('arrStatusinsight', $this->Statusinsight->getStatusList());
				$this->set('arrDelegatedTo', $arrCreatedBy); // Delegated to users list.
	
				# To show error message not found in case result not found
				# by search by insight id.
				$this->set('found', $found);
				$this->set('insightId', $insightId);
		
    } //End of search action.

    /**
     * This functiion is to display markets insight search results page.
     */
    function results($insightId = '')
    {
			$this->layout='front';

			# Import Insight model
    	App::import('Model', 'Insight');
    	# Create Insight model object
			$this->Insight = new Insight();

			# Search by id.
			if(isset($this->data) && !empty($this->data) && isset($this->data['Insight']['id']))
			{
				if(!is_numeric($this->data['Insight']['id']) && $this->data['Insight']['id'] != "") {
					$this->redirect($this->Cookie->read('currentUrl') .'/'. $this->data['Insight']['id'] .'/notfound');
				}			
				$insightType = $this->Insight->field('what_insight_type', array('id' => $this->data['Insight']['id']));
				if(trim($insightType) != "") {
						$this->redirect(SITE_URL.'/'.strtolower($insightType).'s/records/'.$this->data['Insight']['id']);
				}elseif($this->data['Insight']['id']>0){
						$this->redirect($this->Cookie->read('currentUrl') .'/'. $this->data['Insight']['id'] .'/notfound');
				}
				/*if(trim($insightType) != "" && $insightType != 'MARKET') {
					$this->redirect(SITE_URL.'/'.strtolower($insightType).'s/records/'.$this->data['Insight']['id']);
				}elseif($this->data['Insight']['id']>0){
					$insightId = $this->data['Insight']['id'];
				}*/

			}
			
			# Import Pilotgroup model
    	App::import('Model', 'Pilotgroup');
    	# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();

			# Import Productfamilyname model
    	App::import('Model', 'Productfamilyname');
    	# Create Pilotgroup model object
			$this->Productfamilyname = new Productfamilyname();

			# Import Practicearea model
    	App::import('Model', 'Practicearea');
    	# Create Practicearea model object
			$this->Practicearea = new Practicearea();
  	
			$conditionsArr = array();

			# Import Market model
    	App::import('Model', 'Market');
    	# Create Insight model object
			$this->Market = new Market();
		
			$conditionsArr = array();		
		
			# Search process (conditions composing) start.
			if(isset($this->data) && !empty($this->data) || $insightId>0)
			{
				if($insightId<1) {	
				$conditionsArr = array('Insight.what_insight_type' => $this->data['Market']['what_insight_type']);
			
				if(isset($this->data['Market']['what_how_come']) && $this->data['Market']['what_how_come'] != "0" ) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.what_how_come' => $this->data['Market']['what_how_come']));
				}
				# Check for source name.
				if(isset($this->data['Market']['what_source_name']) && trim($this->data['Market']['what_source_name']) != "") {
					$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_source_name) LIKE' => '%' . strtolower($this->data['Market']['what_source_name']) . '%'));
				}
				# Check for firm name/ Condition for firm name.
				if(isset($this->data['Firm']['what_firm_name']) && trim($this->data['Firm']['what_firm_name']) != "") {
						$parentId = $this->processFirmExistance($this->data['Firm']['what_firm_name']);
					if($parentId>0) {
						$conditionsArr = array_merge($conditionsArr, array('Insight.what_firm_name' => $parentId));
					}
					else {
							$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_firm_name_text) LIKE' => '%' . strtolower($this->data['Firm']['what_firm_name']) . '%'));
					}
					
				}
				# Check / Conditon for market.
				if(isset($this->data['Market']['market_id']) && $this->data['Market']['market_id']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.market_id' => $this->data['Market']['market_id']));
				}
				
				# Check for created by.
				if(isset($this->data['Market']['user_id']) && trim($this->data['Market']['user_id'])>0) {
						$conditionsArr = array_merge($conditionsArr, array('Insight.user_id' => $this->data['Market']['user_id']));
				}
				# Check for created by.
				if(trim($this->data['Market']['created_from']) != "") {
						$startTimeArr = explode('-', $this->data['Market']['created_from']);
						$startTime = mktime(0,0,0,$startTimeArr[1],$startTimeArr[2],$startTimeArr[0]);
	
						if(trim($this->data['Market']['created_to']) == "" ) {
							$endTime = time();
						}else{
							$endTimeArr = explode('-', 	$this->data['Market']['created_to']);
							$endTime = mktime(23,59,59,$endTimeArr[1],$endTimeArr[2],$endTimeArr[0]);
						}
	
							$conditionsArr = array_merge($conditionsArr, array('UNIX_TIMESTAMP(Insight.date_submitted) BETWEEN ? AND ?' => array($startTime, $endTime)));
				}			
	
				# Check for relates product family name.
				if(isset($this->data['Market']['relates_product_family_id']) && $this->data['Market']['relates_product_family_id']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.relates_product_family_id' => $this->data['Market']['relates_product_family_id']));
				}

				# Search for current status value.
				if(isset($this->data['Market']['insight_status']) && $this->data['Market']['insight_status']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.insight_status' => $this->data['Market']['insight_status']));
				}

				# Search for delegated to value.
				if(isset($this->data['Market']['deligated_to']) && $this->data['Market']['deligated_to']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.deligated_to' => $this->data['Market']['deligated_to']));
				}
					
				# Check for relates_practice_area.
				if(isset($this->data['Market']['practice_area_id']) && $this->data['Market']['practice_area_id']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.practice_area_id' => $this->data['Market']['practice_area_id']));
				}
				}else{
					$conditionsArr = array('Insight.id' => $insightId);
				}
	
				# Setting condition to session.
				$this->Session->write('conditionsArr', serialize($conditionsArr));
				$this->Session->write('exportType', 'market');			
			}
	
			# In case searched other type from other tab.
			if($this->Session->read('exportType') != 'market')
				$this->redirect(SITE_URL.'/markets/search');
	
			# Looking for search condition.
			if($this->Session->read('conditionsArr') != "")
			{
				# Set pagination array and condition.
				$this->paginate = array(
						'conditions' => unserialize($this->Session->read('conditionsArr')),
						'order' => 'Insight.date_submitted DESC',
						'limit' => RECORD_PER_PAGE
				);
				# Retrieving results form paginator.
				$result = $this->paginate('Insight');
			}
			$i = 0;
			if(!empty($result))
			{
				foreach($result as $row)
				{
					$final_result[$i] = $row['Insight'];
					$final_result[$i]['userSubmittedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['user_id']);
					$final_result[$i]['userUpdatedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['updated_by']);
										
					if($final_result[$i]['what_how_come'] == "0")
						$final_result[$i]['what_how_come'] = "";
										
					# Composing firm name on basis of Id if any else direct name from db field.
					if(isset($row['Insight']['what_firm_name']) && trim($row['Insight']['what_firm_name']) != "") {
						$final_result[$i]['firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
					}
					else {
						$final_result[$i]['firmName'] = $final_result[$i]['what_firm_name_text'];
					}
					
					# Composing product family name.
					if(isset($row['Insight']['market_id']) && trim($row['Insight']['market_id']) != "") {
						$marketName = $this->Market->getMarketById($row['Insight']['market_id']);
						$final_result[$i]['whoMarket'] = $marketName;
					}else{
						$final_result[$i]['whoMarket'] = "";
					}
					
					# Composing product family name.
					if(isset($row['Insight']['relates_product_family_id']) && trim($row['Insight']['relates_product_family_id']) != "") {
						$prod_familiy_name_arr = array();
						$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['relates_product_family_id']);
						$final_result[$i]['relates_product_family_id'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
					}else{
						$final_result[$i]['relates_product_family_id'] = "";
					}
		
					# Retreiving practice area.
					if(isset($row['Insight']['practice_area_id']) && trim($row['Insight']['practice_area_id']) != "") {
						$final_result[$i]['practice_area_id'] = $this->Practicearea->getPracticeareaNameById($row['Insight']['practice_area_id']);
					}else{
						$final_result[$i]['practice_area_id'] = "";
					}
		
					$i++;
				}
			}
			# Setting current url to come from edit mode.
			$this->Cookie->write('backUrl', $this->here);
						
			if(!empty($final_result)) 
			{
				$this->set('final_result', $final_result);
			}
		
    } //End of results action.

    /**
     * This function validates the form on the server side in case there is no client side validation or any failure.
     * @return true/false
     * @author Mohit Khurana
     */
    function serverValidate($arrMarketSaveData)
    {
    	# Validate value of insight summary.
    	if(trim($arrMarketSaveData['Market']['insight_summary']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errDivMarketInsightSummary','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}
    	
    	# Return error flag.
    	return $this->utility->getErrorFlagReturnStatus($this->flagErrMsg);
    }

    /**
     * This validates and returns entered firm information.
     * @param String $firmParentID
     * @return Firm Parent ID
     * @author Mohit Khurana
     */
    function processFirmExistance($firmParentID='')
    {
    	# Import Firm model
    	App::import('Model', 'Firm');
    	# Create Firm model object
		$this->Firm = new Firm();
		
		# Get array for firm name id.
    	$arrFirmParentID = $this->Firm->getFirmParentIDData($firmParentID);
    	# Check if firm parent id exists.
    	if(isset($arrFirmParentID) && is_array($arrFirmParentID) && count($arrFirmParentID) > 0)
    		return $arrFirmParentID['Firm']['parent_id'];
    	else
    		return 0;
    }

    /**
     * This function sets the default display status for the error divs on the view page that will be 'none'.
     * @author Mohit Khurana
     */
    function setMessageDivDefaultStatus()
    {
    	# Set the default display status for the summary error div.
    	$this->set('errDivMarketInsightSummary','none');
    	# Set the default display status for the atatchment error div.
    	$this->set('errDivAttachment','none');
    	# Set the default display status for the atatchment size error div.
    	$this->set('errDivAttachmentSize','none');
    	# Set the default display status for the save success message div.
    	$this->set('successDivSave','none');

    }
    /**
     * This function validates attachment on server side.
     * @param String $attachmentExtension
     * @param Integer $attachmentSize
     * @return True/False Returns Error Flag
     * @author Mohit Khurana
     */
    function serverValidateAttachment($attachmentExtension = '',$attachmentSize = '')
    {
    	# Attachment default extensions.
    	$arrAllowedExtensions = $this->utility->attachmentAllowedExtensions();
    	
    	# Validate attachment extension.
    	if(isset($attachmentExtension) && !in_array(strtolower($attachmentExtension),$arrAllowedExtensions))
    	{
    		# Set error div to display mode.
    		$this->set('errDivAttachment','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}
    	
    	# Validate attachment size.
    	if(isset($attachmentSize) && $attachmentSize > MAX_ATTACHMENT_SIZE)
    	{
    		# Set error div to display mode.
    		$this->set('errDivAttachmentSize','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}

    	# Return error flag.
    	return $this->utility->getErrorFlagReturnStatus($this->flagErrMsg);
    	
    }

	/***
	* Compose firm name by name and parent id 
	*/
	function processFirmId($pid) 
	{
    	# Import Firm model	
		App::import('Model', 'Firm');
    	# Create Firm model object
		$this->Firm = new Firm();
		
		# Look in db for firm name by parent Id
		$result = $this->Firm->findByParentId($pid, array('firm_name'));
		
		return $result['Firm']['firm_name'] . "(" . $pid . ")";
	}
	/**
	* To check weather insight exists or not.
	*/
	function checkInsightRedirect($id='')
	{
		# Import Insight mode.
		App::import('Model', 'Insight');
		# Create  insight model object.
		$this->Insight = new Insight();
		
		$result = $this->Insight->findById($id);
		if(empty($result)) 
		{
			$this->redirect(SITE_URL."/");
		}
	}

	# To remove attachment
	function remove_attachment($id = NULL, $filename = NULL) {
		if($filename != "" || $id = "")
		{
			$file = base64_decode($filename);
			# Set Insight Controller.
			App::import('Model', 'Insight');
			# Create Insight model object.
			$this->Insight = new Insight();
			//$this->Insight->updateAll(array('Insight.attachment_name' => "''", 'Insight.attachment_real_name' => "''"),array('Insight.id'=>$id));
			$this->Insight->updateAll(array('Insight.attachment_name' => NULL),array('Insight.id'=>$id));
			$this->Insight->updateAll(array('Insight.attachment_real_name' => NULL),array('Insight.id'=>$id));				
			$msg = TRUE;
			if(file_exists(MARKET_ATTACHMENT_UPLOAD_PATH."/".$file)) {
				if(unlink(MARKET_ATTACHMENT_UPLOAD_PATH."/".$file))
				{
					$msg = TRUE;
				}
				else
				{
					$msg = "not removed";
				}
			}
		}
		print $msg;
		die;
	}
}
?>