<?php
/**
 * This is the master competitor class that will handle all the master pages for competitor insignt.
 * @author Mohit Khurana
 */
class CompetitorsController extends AppController
{
	#Controller Class Name
	var $name = 'Competitors';
  # Array of helpers used in this controller.
	var $helpers = array('Javascript','Ajax','Form', 'Custom');
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
	var $arrCompetitorSaveData;
	# Save data array
	var $arrCompetitorEditData;
	
    /**
     * This functiion is to display competitor insight page.
     */
    function index($flagSuccessMsg = 0)
    {
			$this->layout='front';
			
			# Call function to set default display value for error messages.
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

    	if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');
			
    	# Check if the form is submitted.
    	if(isset($this->data) && !empty($this->data))
    	{	
    		$arrCompetitorSaveData = $this->data;

    		if($this->serverValidate($arrCompetitorSaveData))
			{					
				# Import Insight model
				App::import('Model', 'Insight');
				# Create Insight model object
				$this->Insight = new Insight();

				# verify firm id and save id else save name.
				if(isset($arrCompetitorSaveData['Firm']['what_firm_name']) && trim($arrCompetitorSaveData['Firm']['what_firm_name']) != '')
				{
					# Verify if a firm with this parent_id exists in the database.
					$firmParentID = $this->processFirmExistance($arrCompetitorSaveData['Firm']['what_firm_name']);
					
					# Set Firm name Field Text value from filled autosearch field.
					if(isset($firmParentID) && $firmParentID > 0)
						$arrCompetitorSaveData['Competitor']['what_firm_name'] = $firmParentID;
					else
						$arrCompetitorSaveData['Competitor']['what_firm_name_text'] = $arrCompetitorSaveData['Firm']['what_firm_name'];
				}

				# verify competitor id and save id else save name.
				if(isset($arrCompetitorSaveData['Competitorname']['who_competitor_name']) && trim($arrCompetitorSaveData['Competitorname']['who_competitor_name']) != '')
				{
					# Verify if a firm with this parent_id exists in the database.
					$competitorID = $this->processCompetitorExistance($arrCompetitorSaveData['Competitorname']['who_competitor_name']);
					
					# Set Firm name Field Text value from filled autosearch field.
					if(isset($competitorID) && $competitorID > 0)
						$arrCompetitorSaveData['Competitor']['competitor_id'] = $competitorID;
					else
						$arrCompetitorSaveData['Competitor']['who_competitor_name_text'] = $arrCompetitorSaveData['Competitorname']['who_competitor_name'];
				}

				#Save User Id of the current user into the insights table.
				$arrCompetitorSaveData['Competitor']['user_id'] = $this->Session->read('current_user_id');

				# Check if attachment is there.
				if (isset($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name']) && !empty($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name']))
				{
					# Get attachment extension.
					$attachmentExtension =  pathinfo($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
					#Get new name for attachment to be saved into database.
					$attachmentNewName = str_replace(pathinfo($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name']);
					if($this->serverValidateAttachment($attachmentExtension,$arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['size']))
					{
						# Verify if attachment saved.
						if($this->utility->uploadAttachment($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,COMPETITOR_ATTACHMENT_UPLOAD_PATH))
						{
							# Save Attachment New Name into database.
							$arrCompetitorSaveData['Competitor']['attachment_name'] = $attachmentNewName;
							# Save Attachment Original Name into database.
							$arrCompetitorSaveData['Competitor']['attachment_real_name'] = $arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name'];
						}
					}
				}
				// Code by Pragya Dave - fixed special characters insertion				
				$arrCompetitorSaveData['Competitor']['insight_summary'] = $this->utility->parseString($this->data['Competitor']['insight_summary']);
				$arrCompetitorSaveData['Competitor']['do_action'] = $this->utility->parseString($this->data['Competitor']['do_action']);

				# Verify if there is no error.
				if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
				{
					#Save Competitor Insight into database.
					$this->Insight->save($arrCompetitorSaveData['Competitor']);		
					# Redirect if save is successful.
					$this->redirect(SITE_URL . '/competitors/index/1');
					//$this->redirect(SITE_URL . '/competitors/records/'.$this->Insight->getlastinsertid());					
				}
			}
    	}
				
    }

	/**
	 * This functiion is to display update competitor insight records page.
	 */
    function records($id='', $flagSuccessMsg=0)
    {
				# Include Layout
				$this->layout='front';
		
				# Check for existing insight for record.
				$this->checkInsightRedirect($id);
		
				# Call function to set default display value for error messages.
				$this->setMessageDivDefaultStatus();
		
				# Set timestamp value
				$timeStamp = strtotime("now");
	
				# Set How Come Array
				# Import Content Type model
				App::import('Model', 'Insightabout');
				# Create Content Type model object
				$this->Insightabout = new Insightabout();
				# Set Who Content Type Array
				$this->set('arrHowCome',$this->Insightabout->returnStaticData());
	
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
	
				# Set Competitor Array
				if(isset($this->data) && !empty($this->data))
				{
					# Set form data values to user defined array.
					$arrCompetitorSaveData = $this->data;
						
					if($this->serverValidate($arrCompetitorSaveData))
					{					
					# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();
					# Set flag for edit mode.
					$this->set('edit_flag', 'edit');
					# verify firm id and save id else save name.
					if(isset($arrCompetitorSaveData['Firm']['what_firm_name']) && trim($arrCompetitorSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrCompetitorSaveData['Firm']['what_firm_name']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrCompetitorSaveData['Competitor']['what_firm_name'] = $firmParentID;
						else
							$arrCompetitorSaveData['Competitor']['what_firm_name_text'] = $arrCompetitorSaveData['Firm']['what_firm_name'];
					}
	
					# verify competitor id and save id else save name.
					if(isset($arrCompetitorSaveData['Competitorname']['competitor_id']) && trim($arrCompetitorSaveData['Competitorname']['competitor_id']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$competitorID = $this->processCompetitorExistance($arrCompetitorSaveData['Competitorname']['competitor_id']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($competitorID) && $competitorID > 0)
							$arrCompetitorSaveData['Competitor']['competitor_id'] = $competitorID;
						else
							$arrCompetitorSaveData['Competitor']['who_competitor_name_text'] = $arrCompetitorSaveData['Competitorname']['competitor_id'];
					}
	
					#Save User Id of the current user into the insights table.
					$arrCompetitorSaveData['Competitor']['user_id'] = $this->Insight->getCreatedById($arrCompetitorSaveData['Competitor']['id']);
	
	
					# Check if attachment is there.
					if (isset($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name']) && !empty($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
							if($this->utility->uploadAttachment($arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,COMPETITOR_ATTACHMENT_UPLOAD_PATH))
							{
								# Set old file to be removed.
								$file = new File(COMPETITOR_ATTACHMENT_UPLOAD_PATH . '/' . $arrCompetitorSaveData['Competitor']['old_attachment_name']);
								# Remove file.
								$file->delete();
	
								# Save Attachment New Name into database.
								$arrCompetitorSaveData['Competitor']['attachment_name'] = $attachmentNewName;
								# Save Attachment Original Name into database.
								$arrCompetitorSaveData['Competitor']['attachment_real_name'] = $arrCompetitorSaveData['CompetitorAttachment']['attachment_name']['name'];
							}
						}
					}
					else if(isset($arrCompetitorSaveData['CompetitorAttachment']['old_attachment_name']))
					{
							$arrCompetitorSaveData['Competitor']['attachment_name'] = $arrCompetitorSaveData['CompetitorAttachment']['old_attachment_name'];
					}
					else {
							$arrCompetitorSaveData['Competitor']['attachment_name'] = NULL;				
					}
					if(isset($arrCompetitorSaveData['Competitor']['insight_status'])&&$arrCompetitorSaveData['Competitor']['insight_status']>0 && isset($arrCompetitorSaveData['Competitor']['insight_status_changed']))
					{
						 #updated date
						 $arrCompetitorSaveData['Competitor']['date_updated'] = date('Y-m-d H:i:s', time());
					}
				 
					#data updated by
					$arrCompetitorSaveData['Competitor']['updated_by'] = $this->Session->read('current_user_id');
					
					// Code by Pragya Dave - fixed special characters edit (08/02/2011)
					$arrCompetitorSaveData['Competitor']['insight_summary'] = $this->utility->parseString($arrCompetitorSaveData['Competitor']['insight_summary']);
					$arrCompetitorSaveData['Competitor']['do_action'] = $this->utility->parseString($arrCompetitorSaveData['Competitor']['do_action']);

					# Verify if there is no error.
					if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
					{
						
						#Save Competitor Insight into database.
						$this->Insight->save($arrCompetitorSaveData['Competitor']);
						# Redirect if save is successful.
						//$this->redirect(SITE_URL . '/');
						$this->redirect(SITE_URL . '/competitors/records/'.$id.'/1');					
					}
				}		
			}	
			else 
				{
				# Reading insight record on the basis of $id.
						$this->data = $this->Insight->read();
	
				# Import Competitor model
				$this->data['Competitor']['what_how_come'] = $this->data['Insight']['what_how_come'];
				$this->data['Competitor']['what_source_name'] = $this->data['Insight']['what_source_name'];
				$this->data['Competitor']['insight_summary'] = $this->utility->parseString($this->data['Insight']['insight_summary']);
				$this->data['Competitor']['relates_product_family_id'] = $this->data['Insight']['relates_product_family_id'];
				$this->data['Competitor']['practice_area_id'] = $this->data['Insight']['practice_area_id'];
				$this->data['Competitor']['do_action'] = $this->data['Insight']['do_action'];
				 # Import Competitorname model
				if(isset($this->data['Insight']['competitor_id']) && is_numeric(intval($this->data['Insight']['competitor_id'])))
				{
					# Import Competitorname model
					App::import('Model', 'Competitorname');
					# Create Competitorname model object
					$this->Competitorname = new Competitorname();
					
					# Fetching competitor info.
						$competitorInfo = $this->Competitorname->getCompetitorName($this->data['Insight']['competitor_id']);
					$this->data['Competitorname']['competitor_id'] = $competitorInfo['Competitorname']['competitor_name'];
				}
				else
				{
						$this->data['Competitorname']['competitor_id'] = $this->data['Insight']['who_competitor_name_text'];
				}
				
				# Import Firm model
				if (isset($this->data['Insight']['what_firm_name']) && $this->data['Insight']['what_firm_name']>0) 
				{
					$this->data['Firm']['what_firm_name'] = $this->processFirmId($this->data['Insight']['what_firm_name']);
				}
				else
				{
					$this->data['Firm']['what_firm_name'] = $this->data['Insight']['what_firm_name_text'];
				}

			
				# Set variable values for view.
				$this->set('what_how_come', $this->data['Insight']['what_how_come']); 
				$this->set('product_family_name_label', $this->data['Insight']['relates_product_family_id']);
				$this->set('practice_area_label', $this->data['Insight']['practice_area_id']);
				$this->set('do_action_dummy', $this->data['Insight']['do_action']);
				$this->set('attachment_real_name', $this->data['Insight']['attachment_real_name']);
				$this->set('attachment_name', $this->data['Insight']['attachment_name']);
				$this->set('current_status_label', $this->data['Insight']['insight_status']);
				$this->set('deligated_to_selected', $this->data['Insight']['deligated_to']);
				# URL to go back to search result page.
				$this->set('backUrl', $this->Cookie->read('backUrl'));		   
    	}

    }
	    
    /**
     * This functiion is to display competitor insight search page.
     */
    function search($insightId = '', $found = '')
    {
			$this->layout='front';
			# Setting current url to redirect in case no result found.
			$this->Cookie->write('currentUrl', SITE_URL.'/competitors/search');
			$this->Cookie->write('backUrl', SITE_URL.'/competitors/search');
			# Removing conditions array from session if any.
			$this->Session->delete('conditionsArr');

    	# Set How Come Array
    	# Import Content Type model
    	App::import('Model', 'Insightabout');
    	# Create Content Type model object
			$this->Insightabout = new Insightabout();
    	# Set Who Content Type Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));

			# Import Competitorname model
			App::import('Model', 'Competitorname');
			# Create Competitorname model object
			$this->Competitorname = new Competitorname();
			# Set competitor names array for search view.
			$arrCompetitors = array('--Select--');
			$arrCompetitors = array_merge($arrCompetitors, $this->Competitorname->getCompetitors());
			$this->set('arrCompetitorNames', $arrCompetitors);
	
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
			//$arrFamilynames = array('--Select--');
			//$arrFamilynames = array_merge($arrFamilynames, $this->Productfamilyname->getProductFamilyNames());
			$arrFamilynames = $this->Productfamilyname->getProductFamilyNames(TRUE);
			$this->set('arrFamilyNames', $arrFamilynames);
	
			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();
			# Set practice area names array for search view.
			//$arrPracticeArea = array('--Select--');
			//$arrPracticeArea = array_merge($arrPracticeArea, $this->Practicearea->getPracticeArea());
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

    }

    /**
     * This functiion is to display competitor insight search results page.
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
				/*if(trim($insightType) != "" && $insightType != 'COMPETITOR') {
					$this->redirect(SITE_URL.'/'.strtolower($insightType).'s/results/'.$this->data['Insight']['id']);
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
  	

			# Import Competitorname model
			App::import('Model', 'Competitorname');
			# Create Competitorname model object
			$this->Competitorname = new Competitorname();

			$conditionsArr = array();
			
		
			# Search process (conditions composing) start.
    	if(isset($this->data) && !empty($this->data) || $insightId>0)
			{
				if($insightId<1) {				
					$conditionsArr = array('Insight.what_insight_type' => $this->data['Competitor']['what_insight_type']);
			
				if(isset($this->data['Competitor']['what_how_come']) && $this->data['Competitor']['what_how_come'] != "0" ) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.what_how_come' => $this->data['Competitor']['what_how_come']));
				}
				# Check for source name.
				if(isset($this->data['Competitor']['what_source_name']) && trim($this->data['Competitor']['what_source_name']) != "") {
					$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_source_name) LIKE' => '%' . strtolower($this->data['Competitor']['what_source_name']) . '%'));
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

				# Check for competitor name/ Condition for competitor name.
				if(isset($this->data['Competitorname']['who_competitor_name']) && trim($this->data['Competitorname']['who_competitor_name']) != "") {
					# Import Competitorname model
					App::import('Model', 'Competitorname');
					# Create Competitorname model object
					$this->Competitorname = new Competitorname();
	
					$competitorIdArr = $this->Competitorname->getCompetitorId($this->data['Competitorname']['who_competitor_name']);
					$competitorId = (!empty($competitorIdArr))?$competitorIdArr['Competitorname']['id']:0;
					if($competitorId>0) {
						$conditionsArr = array_merge($conditionsArr, array('Insight.competitor_id' => $competitorId));
					}
					else {
						$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_competitor_name_text) LIKE' => '%' . strtolower($this->data['Competitorname']['who_competitor_name']) . '%'));
					}
				}
		
				# Check for created by.
				if(isset($this->data['Competitor']['user_id']) && trim($this->data['Competitor']['user_id'])>0) {
						$conditionsArr = array_merge($conditionsArr, array('Insight.user_id' => $this->data['Competitor']['user_id']));
				}
	
				# Check for created by.
				if(trim($this->data['Competitor']['created_from']) != "") {
						$startTimeArr = explode('-', $this->data['Competitor']['created_from']);
						$startTime = mktime(0,0,0,$startTimeArr[1],$startTimeArr[2],$startTimeArr[0]);
	
						if(trim($this->data['Competitor']['created_to']) == "" ) {
							$endTime = time();
						}else{
							$endTimeArr = explode('-', 	$this->data['Competitor']['created_to']);
							$endTime = mktime(23,59,59,$endTimeArr[1],$endTimeArr[2],$endTimeArr[0]);
						}
	
							$conditionsArr = array_merge($conditionsArr, array('UNIX_TIMESTAMP(Insight.date_submitted) BETWEEN ? AND ?' => array($startTime, $endTime)));
				}
				# Check for relates product family name.
				if(isset($this->data['Competitor']['relates_product_family_id']) && $this->data['Competitor']['relates_product_family_id']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.relates_product_family_id' => $this->data['Competitor']['relates_product_family_id']));
				}

				# Search for current status value.
				if(isset($this->data['Competitor']['insight_status']) && $this->data['Competitor']['insight_status']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.insight_status' => $this->data['Competitor']['insight_status']));
				}

				# Search for delegated to value.
				if(isset($this->data['Competitor']['deligated_to']) && $this->data['Competitor']['deligated_to']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.deligated_to' => $this->data['Competitor']['deligated_to']));
				}
	
				# Check for relates_practice_area.
				if(isset($this->data['Competitor']['practice_area_id']) && $this->data['Competitor']['practice_area_id']>0) {
					$conditionsArr = array_merge($conditionsArr, array('Insight.practice_area_id' => $this->data['Competitor']['practice_area_id']));
				}
				}else{
					$conditionsArr = array('Insight.id' => $insightId);
				}
	
				$this->Session->write('conditionsArr', serialize($conditionsArr));
				$this->Session->write('exportType', 'competitor');			
				# Search in database.
				//$result = $this->Insight->find('all', array('conditions' => $conditionsArr));unserialize($this->Session->read('conditionsArr'))
			
			}

			# In case searched other type from other tab.
			if($this->Session->read('exportType') != 'competitor')
				$this->redirect(SITE_URL.'/competitors/search');

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
					if($final_result[$i]['what_how_come'] == "0")
						$final_result[$i]['what_how_come'] = "";
										
					# Composing firm name on basis of Id if any else direct name from db field.
					if(isset($row['Insight']['what_firm_name']) && trim($row['Insight']['what_firm_name']) != "") {
						$final_result[$i]['firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
					}
					else {
						$final_result[$i]['firmName'] = $final_result[$i]['what_firm_name_text'];
					}
	
					# Composing firm name on basis of Id if any else direct name from db field.
					if(isset($row['Insight']['competitor_id']) && trim($row['Insight']['competitor_id'])>0) {
						$comp_nameArr = $this->Competitorname->getCompetitorName($row['Insight']['competitor_id']);
						$final_result[$i]['who_competitorName'] = $comp_nameArr['Competitorname']['competitor_name'];
					}
					else {
						$final_result[$i]['who_competitorName'] = $final_result[$i]['who_competitor_name_text'];
					}
									
					# Composing product family name.
					if(isset($row['Insight']['relates_product_family_id']) && trim($row['Insight']['relates_product_family_id']) != "") {
						$prod_familiy_name_arr = array();
						$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['relates_product_family_id']);
						$final_result[$i]['relates_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
					}
					else {
						$final_result[$i]['relates_product_familyName'] = $row['Insight']['relates_product_family_name_text'];
					}
		
					# Retreiving practice area.
					if(isset($row['Insight']['practice_area_id']) && trim($row['Insight']['practice_area_id']) != "") {
						$final_result[$i]['practice_area_id'] = $this->Practicearea->getPracticeareaNameById($row['Insight']['practice_area_id']);
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

    }


    /**
     * This function sets the default display status for the error divs on the view page that will be 'none'.
     * @author Mohit Khurana
     */
    function setMessageDivDefaultStatus()
    {
    	# Set the default display status for the summary error div.
    	$this->set('errDivCompetitorInsightSummary','none');
    	# Set the default display status for the atatchment error div.
    	$this->set('errDivAttachment','none');
    	# Set the default display status for the atatchment size error div.
    	$this->set('errDivAttachmentSize','none');
    	# Set the default display status for the save success message div.
    	$this->set('successDivSave','none');

    }
    
    /**
     * This function validates the form on the server side in case there is no client side validation or any failure.
     * @return true/false
     * @author Mohit Khurana
     */
    function serverValidate($arrCompetitorSaveData)
    {
    	# Validate value of insight summary.
    	if(trim($arrCompetitorSaveData['Competitor']['insight_summary']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errDivCompetitorInsightSummary','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}
    	
    	# Return error flag.
    	return $this->utility->getErrorFlagReturnStatus($this->flagErrMsg);
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
     * This validates and returns entered competitor information.
     * @param String $competitorName
     * @return Competitor ID
     * @author Naresh Kr
     */
    function processCompetitorExistance($competitorName = '')
    {
    	# Import Firm model
    	App::import('Model', 'Competitorname');
    	# Create Firm model object
		$this->Competitorname = new Competitorname();
		
			# Get array for competitor id.
    	$arrCompetitorId = $this->Competitorname->getCompetitorId($competitorName);
    	# Check if firm parent id exists.
    	if(isset($arrCompetitorId) && is_array($arrCompetitorId) && count($arrCompetitorId) > 0)
    		return $arrCompetitorId['Competitorname']['id'];
    	else
    		return 0;
    }

    /**
     * This functiion is to display Competitors autocomplete.
     */
    function autoCompleteCompetitorNames()
    {
    	# Import Competitorname model
    	App::import('Model', 'Competitorname');
    	# Create Competitorname model object
			$this->Competitorname = new Competitorname();
    	# Set autocomplete array
			$this->set('Competitors', $this->Competitorname->find('all', array(
				'conditions' => array(
					'Competitorname.isactive' => 1,
					'Competitorname.competitor_name LIKE' => '%'.$this->data['Competitorname']['competitor_id'].'%'
				),
			'fields' => array('id','competitor_name')
			)));
		
			$this->layout = 'ajax';
    }
    
     /**
     * This functiion is to display Competitor name autocomplete.
     */
    function autoComplete()
    {
    	$strCompetitorNames = '';
    	$this->layout = 'ajax';
    	
    	# Import Competitorname model
    	App::import('Model', 'Competitorname');
    	# Create Competitorname model object
			$this->Competitorname = new Competitorname();
    	# Set autocomplete array
    	
			$Competitors = $this->Competitorname->find('all', array(
				'conditions' => array(
					'Competitorname.isactive' => 1,
					'Competitorname.competitor_name LIKE' => '%'.$_GET['query'].'%'
				),
			'fields' => array('id','competitor_name')
		));
		
			foreach($Competitors as $Competitor) {
				if(isset($strCompetitorNames) && trim($strCompetitorNames) != '') {
					$strCompetitorNames .= ',' . '"' . $Competitor['Competitorname']['competitor_name'] . '"';
				} else {
					$strCompetitorNames .= '"' . $Competitor['Competitorname']['competitor_name'] . '"';
				}
			}
		
			$this->set('strCompetitorNames',$strCompetitorNames);
    }
    

    /**
     * This functiion is to display pilotgroup as created by autocomplete.
     */
    function autoCompleteCreatedBy()
    {
    	# Import Competitorname model
    	App::import('Model', 'Pilotgroup');
    	# Create Competitorname model object
			$this->Pilotgroup = new Pilotgroup();
    	# Set autocomplete array
			$this->set('Createdby', $this->Pilotgroup->find('all', array(
				'conditions' => array(
					'Pilotgroup.isactive' => 1,
					'Pilotgroup.name LIKE' => '%'.$this->data['Competitorname']['user_id'].'%'
				),
			'fields' => array('id','name')
			)));
		
			$this->layout = 'ajax';
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

	# Export data to excel.
	function exportToExcel()
	{
		$this->layout="empty";
		# Set Insight Controller.
		App::import('Model', 'Insight');
		# Create Insight model object.
		$this->Insight = new Insight();

		# Set Pilotgroup Controller.
		App::import('Model', 'Pilotgroup');
		# Create Pilotgroup model object.
		$this->Pilotgroup = new Pilotgroup();

		# Set Productfamilyname Controller.
		App::import('Model', 'Productfamilyname');
		# Create Productfamilyname model object.
		$this->Productfamilyname = new Productfamilyname();

		# Set Practicearea Controller.
		App::import('Model', 'Practicearea');
		# Create Practicearea model object.
		$this->Practicearea = new Practicearea();
				
		$result = $this->Insight->find('all', array('conditions' => unserialize($this->Session->read('conditionsArr')), 'order' => 'Insight.date_submitted DESC'));
		//pr($data);die;
		$i = 0;
		if(!empty($result))
		{
			foreach($result as $row)
			{
				$final_result[$i] = $row['Insight'];
				$final_result[$i]['userSubmittedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['user_id']);
				# Composing firm name on basis of Id if any else direct name from db field.
				if(isset($row['Insight']['what_firm_name']) && trim($row['Insight']['what_firm_name']) != "") {
					$final_result[$i]['firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
				}
				else {
					$final_result[$i]['firmName'] = $final_result[$i]['what_firm_name_text'];
				}
				
				# Composing product family name.
				if(isset($row['Insight']['relates_product_family_id']) && trim($row['Insight']['relates_product_family_id']) != "") {
					$prod_familiy_name_arr = array();
					$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['relates_product_family_id']);
					$final_result[$i]['relates_product_family_id'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
				}
	
				# Retreiving practice area.
				if(isset($row['Insight']['practice_area_id']) && trim($row['Insight']['practice_area_id']) != "") {
					$final_result[$i]['practice_area_id'] = $this->Practicearea->getPracticeareaNameById($row['Insight']['practice_area_id']);
				}			

				$i++;
			}
		}
		
		
		$i=0;
		# Set header for excel file.
		$arrExcelInfo[0] = array('Id','Date','Created By','Source of Insight','Firm Name','LexisNexis Product Name','Practice Area','Summary');
		
		$arrExcelInfo[1] = array();
		
		App::import('Vendor', 'excelVendor', array('file' =>'excel'.DS.'class.export_excel.php'));
		
		$fileName = "export.xls";
		//create the instance of the exportexcel format
		$excel_obj = new ExportExcel("$fileName");
		
		foreach ($final_result as $keySet => $valueSet)
		{
			$arrExcelInfo[1][$i][0] = $valueSet['id'];
			$arrExcelInfo[1][$i][1] = date('dS M, Y', strtotime($valueSet['date_submitted']));
			$arrExcelInfo[1][$i][2] = $valueSet['userSubmittedName'];
			$arrExcelInfo[1][$i][3] = $valueSet['what_source_name'];
			$arrExcelInfo[1][$i][4] = $valueSet['firmName'];
			$arrExcelInfo[1][$i][5] = $valueSet['relates_product_family_id'];
			$arrExcelInfo[1][$i][6] = $valueSet['practice_area_id'];
			$arrExcelInfo[1][$i][7] = $valueSet['insight_summary'];
			$i++;
		}
		//setting the values of the headers and data of the excel file 
		//and these values comes from the other file which file shows the data
		$excel_obj->setHeadersAndValues($arrExcelInfo[0],$arrExcelInfo[1]); 
		
		//now generate the excel file with the data and headers set
		$excel_obj->GenerateExcelFile();
	}

	# To remove attachment
	function remove_attachment($id = NULL, $filename = NULL) {
		if($filename != "" || $id != "")
		{
			$file = base64_decode($filename);
			# Set Insight Controller.
			App::import('Model', 'Insight');
			# Create Insight model object.
			$this->Insight = new Insight();
			$this->Insight->updateAll(array('Insight.attachment_name' => NULL),array('Insight.id'=>$id));
			$this->Insight->updateAll(array('Insight.attachment_real_name' => NULL),array('Insight.id'=>$id));				
			$msg = TRUE;
			if(file_exists(COMPETITOR_ATTACHMENT_UPLOAD_PATH."/".$file)) {
				if(unlink(COMPETITOR_ATTACHMENT_UPLOAD_PATH."/".$file))
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