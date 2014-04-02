<?php
/**
	* Filename : product_controller.php
	* Developer :  Mohit Khurana
	* Creation Date : 01/09/2010
	* Author LexisNexis Development Team
	* Cake Version : 1.3.4 
	* @copyright Copyright (c) 2010, LexisNexis
	* Functionality / Description : This is the master class that will handle all the master pages for the insight.
	* Lexis Insight Tracker application is implementing new enhanced functionality for Adding, Editing and Search Insights. 
	* Add Insight form - stores various details of an insight including - How did this insight come about?, Organisation , Contact Name / Role, Product Family Name, Product Name, Content Type, Practice Area, Competitor, Selling Obstacles, Attachment, Description. Some of the fields are autocomplete which allows the user to enter text and the values from the database is autofilled in the textbox.
	* Search Insight form - User can search records on various search criteria or Insight Id and can also perform search as any Free text search. The Search result page can be refined with further search criterias. Those fields searched from basic search form gets disabled and refine search can be done on remaining criterias.
	* Edit Insight form - User can edit any insight and can update more fields. Moderator has the role to modify all the records of an insight while the generic user can modify restricted fields.
	* Modified By: Pragya Dave
	* Modified On: 10/02/2011
	* Modified Description : The code is modified to implement a single insight form for all insight types. Add/Edit/Search and result functionality are managed using this class file.
	* Modified By: Gaurav Saini
	* Modified On: 06/02/2011
	* Modified Description : The code is modified to implement below mentioned functionalities.
	*						Issue functionality
	*						Add comment functionality
	*						Implement email alert functionality
	*						Contact Moderator functionality
	*						Change in search logic to search insight based on Issue.
	*						Display recently added comment on result page.
 */

 # Define Preview Word count of Insight Summary for Email notifications.
 define("WORD_COUNT_INSIGHT_SUMMARY", 60);
 define("LNG_UK_INSIGHT_TRACKER_EMAIL", "David.Coleman@lexisnexis.co.uk");
 define("LNG_UK_INSIGHT_ERROR_EMAIL", "David.Coleman@lexisnexis.co.uk");
 
class ProductsController extends AppController
{
	#Controller Class Name
	var $name = 'Products';
    # Array of helpers used in this controller.
	var $helpers = array('Html','Javascript','Ajax','Form', 'Custom');
	# Array of components used in this controller.
	var $components = array('utility', 'Session', 'Cookie', 'Email');
	#Flag to check error messages.
	var $flagErrMsg = 0;
	#Flag to check error messages.
	var $flagSuccessMsg = 0;
	# Array for error messages.
	var $arrErrMsg = array();
	#Flag to check success messages.
	var $arrSucMsg = array();
	# Save data array
	var $arrProductSaveData;
	# Save data array
	var $arrProductEditData;	
	
    /**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
	 * @modified by Pragya Dave
	 * @modified on 10/02/2011
     * This function is to display insight page. When the user clicks from the homepage screen 'Add Insight', it redirects to this page (calling the function), initialize default database values in dynamic fields for the form and save the values of insight in database - Add  Insight page
	 * @params flagSuccessMsg - Checks whether form is submitted with a flag value bit. Default 0. If flagSuccessMsg set other than 0 then displays msg - The records is saved (value as 1)
    */
    function index($flagSuccessMsg = 0)
    {
		# Include Layout
		$this->layout='front';		
		# Call function to set default display value for error messages.
		$this->setMessageDivDefaultStatus();		
		# Set current timestamp value
		$timeStamp = strtotime("now");	
                            
        /* fetaching insight types data for insight type dropdown form
                 * 
        */
        # Import Insighttype model
	App::import('Model', 'Insighttype');
	$this->Insighttype = new Insighttype();
	$arrinsighttype = $this->Insighttype->getinsightTypesValues();
        /*
         * @sukhvir change due adding new field type and change field type calle what_insight_type field enum to int
         */
//            foreach($arrinsighttype as $key=>$value) 
//            {
//                    $arr[0] = "";		
//                    $arr[$key] = $value;			
//            }
//            $arrinsighttype[] = $arr;
	$this->set('insighttypevalues', $arrinsighttype);
                
    	# Set How Come Array
    	# Import Content Type model
    	App::import('Model', 'Insightabout');
    	# Create Content Type model object
		$this->Insightabout = new Insightabout();
    	# Set Who Content Type Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
    	# Import Content Type model
    	App::import('Model', 'Contenttype');
    	# Create Content Type model object
		$this->Contenttype = new Contenttype();
    	# Set Who Content Type Array
    	$this->set('arrContentTypes',$this->Contenttype->getContentTypes());    	
    	# Import Practicearea model
    	App::import('Model', 'Practicearea');
    	# Create Practicearea model object
		$this->Practicearea = new Practicearea();
    	# Set Who Practicearea Array
    	$this->set('arrPracticeArea',$this->Practicearea->getPracticeArea());    	
		# Import Sellingobstacle model
    	App::import('Model', 'Sellingobstacle');
    	# Create Sellingobstacle model object
		$this->Sellingobstacle = new Sellingobstacle();
    	# Set Sellingobstacle Array
    	$this->set('arrSellingObstacles',$this->Sellingobstacle->getSellingObstacle());
		# Import Productfamilyname model
		App::import('Model', 'Productfamilyname');
		# Create Productfamilyname model object
		$this->Productfamilyname = new Productfamilyname();
		$this->set('arrProductFamilynames',$this->Productfamilyname->getProductFamilyNames());
    	if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');    	
    	# Check if the form is submitted. First If step (loop) checks the form is submitted, then in next step the form validates the mandatory fields.
		# After validation, in the third step it checks each form value and then save them in an array variable $arrProductSaveData
    	if(isset($this->data) && !empty($this->data))
    	{	
			# array variable set to fetch insight submitted data
    		$arrProductSaveData = $this->data;
		# Check the values are validated before saving in database
    		if($this->serverValidate($arrProductSaveData))
    		{    	
                      # adding "I" value for flag_logtype 
                      $arrProductSaveData['Product']['flag_logtype'] = "I";
                      # if what_insight_type is zero or null then it should be set as a Blank(11)
                      
                      if(!$arrProductSaveData['Product']['what_insight_type']){
                           $arrProductSaveData['Product']['what_insight_type'] = 11;
                        }
                            		# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();				
					# Check Organization name is blank or not
					if(isset($arrProductSaveData['Firm']['what_firm_name']) && trim($arrProductSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrProductSaveData['Firm']['what_firm_name']);						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrProductSaveData['Product']['what_firm_name'] = $firmParentID;
						else
							$arrProductSaveData['Product']['what_firm_name_text'] = $arrProductSaveData['Firm']['what_firm_name'];
					}
					# Check Productfamily name is blank or not	
					if(isset($arrProductSaveData['Productfamilyname']['who_product_family_name']) && trim($arrProductSaveData['Productfamilyname']['who_product_family_name']) > 0)
					{
						# Get product family name key if exists.	
						# Set Product family name Field Text value from filled autosearch field.
						$productFamilyNameID = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
						$arrProductSaveData['Product']['product_family_id'] = $productFamilyNameID;
					}
					else 
					{
							$arrProductSaveData['Product']['who_product_family_name_text'] = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
					}
					# Check Product name is blank or not
					if(isset($arrProductSaveData['Productname']['who_product_name']) && trim($arrProductSaveData['Productname']['who_product_name']) != '')
					{
						# Get product name key if exists.	
						$productNameID = $this->processProductNameExistance($arrProductSaveData['Productname']['who_product_name']);
						# Set Product name Field Text value from filled autosearch field.
						if(isset($productNameID) && $productNameID > 0)
							$arrProductSaveData['Product']['product_id'] = $productNameID;
						else
							$arrProductSaveData['Product']['who_product_name_text'] = $arrProductSaveData['Productname']['who_product_name'];
					}								
					# verify competitor id and save id else save name.
					if(isset($arrProductSaveData['Competitorname']['who_competitor_name']) && trim($arrProductSaveData['Competitorname']['who_competitor_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$competitorID = $this->processCompetitorExistance($arrProductSaveData['Competitorname']['who_competitor_name']);
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($competitorID) && $competitorID > 0)
							$arrProductSaveData['Product']['competitor_id'] = $competitorID;
						else
							$arrProductSaveData['Product']['who_competitor_name_text'] = $arrProductSaveData['Competitorname']['who_competitor_name'];
					}
					#Save User Id of the current user into the insights table.
					$arrProductSaveData['Product']['user_id'] = $this->Session->read('current_user_id');				
					#Set Attachment Name with current timestamp								
					# Check if attachment is there.
					if (isset($arrProductSaveData['ProductAttachment']['attachment_name']['name']) && !empty($arrProductSaveData['ProductAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrProductSaveData['ProductAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrProductSaveData['ProductAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
							if($this->utility->uploadAttachment($arrProductSaveData['ProductAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,PRODUCT_ATTACHMENT_UPLOAD_PATH))
							{
								
								# If file exists physically then only file name will be saved in database.
								$filename = ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/'.$attachmentNewName;
								if(file_exists($filename)) 
								{
									# Save Attachment New Name into database.
									$arrProductSaveData['Product']['attachment_name'] = $attachmentNewName;
									# Save Attachment Original Name into database.
									$arrProductSaveData['Product']['attachment_real_name'] = $arrProductSaveData['ProductAttachment']['attachment_name']['name'];
								}
							}
						}
					}				
				# Code by Pragya Dave - fixed special characters insertion
				$arrProductSaveData['Product']['insight_summary'] = $this->utility->parseString($this->data['Product']['insight_summary']);
				# Verify if there is no error.
				if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
				{
					//$arrProductSaveData['Product']['user_id'] = null;
			
					if($arrProductSaveData['Product']['user_id'] > 0){
					
							#Save Product Insight into database.
							$this->Insight->save($arrProductSaveData['Product']);
				
							$last_inserted_id = $this->Insight->id; 
							# Import Pilotgroup model
							
							App::import('Model', 'Pilotgroup');
							# Create Pilotgroup model object
							$this->Pilotgroup = new Pilotgroup();
					
							# Get Moderator Email Address.
							$arrModeratorAddress = $this->Pilotgroup->getModeratorEmailAddress();
                                                        #backup lines 
							//$moderator_email_address = $arrModeratorAddress;
							$moderator_email_address = "David.Coleman@lexisnexis.co.uk";
							
							# Send Moderator a notification mail informing him that new Insight is added.
							$this->send_new_insight_mail_to_moderator($last_inserted_id, $moderator_email_address);		
							
							# Redirect if save is successful.
							$this->redirect(SITE_URL . '/products/index/1');
						}
						else
						{	
							# Send Error email.							
							$this->send_error_mail($arrProductSaveData['Product'], $_SESSION['current_user_name']);
							
							# Serializing data to store for Error log purpose.
							$arrProductSaveData['Product']['current_user_name'] = $_SESSION['current_user_name'];
							$serializeData = serialize($arrProductSaveData['Product']);

							# Log error in error.log file.
							$this->log('=====>>'.$serializeData);

							$this->redirect(SITE_URL . '/products/oops');
						}
								
				}				
    		} # End validation check if
    	} # End form value posted if    	
    } # End function
	
	
	 /**
	 * @author Gaurav Saini
	 * @created on 18/08/2011 
     * This function is used to display insight page. When the user clicks from the homepage screen 'Add Insight', it redirects to this page (calling the function), initialize default database values in dynamic fields for the form and save the values of insight in database - Add  Insight page
	 * @params flagSuccessMsg - Checks whether form is submitted with a flag value bit. Default 0. If flagSuccessMsg set other than 0 then displays msg - The records is saved (value as 1)
    */
    function mindex($flagSuccessMsg = 0)
    {
		# Include Layout
		$this->layout='mobile';		
		# Call function to set default display value for error messages.
		$this->setMessageDivDefaultStatus();		
		# Set current timestamp value
		$timeStamp = strtotime("now");		
    	# Set How Come Array
    	# Import Content Type model
    	App::import('Model', 'Insightabout');
    	# Create Content Type model object
		$this->Insightabout = new Insightabout();
    	# Set Who Content Type Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
    	# Import Content Type model
    	App::import('Model', 'Contenttype');
    	# Create Content Type model object
		$this->Contenttype = new Contenttype();
    	# Set Who Content Type Array
    	$this->set('arrContentTypes',$this->Contenttype->getContentTypes());    	
    	# Import Practicearea model
    	App::import('Model', 'Practicearea');
    	# Create Practicearea model object
		$this->Practicearea = new Practicearea();
    	# Set Who Practicearea Array
    	$this->set('arrPracticeArea',$this->Practicearea->getPracticeArea());    	
		# Import Sellingobstacle model
    	App::import('Model', 'Sellingobstacle');
    	# Create Sellingobstacle model object
		$this->Sellingobstacle = new Sellingobstacle();
    	# Set Sellingobstacle Array
    	$this->set('arrSellingObstacles',$this->Sellingobstacle->getSellingObstacle());
		# Import Productfamilyname model
		App::import('Model', 'Productfamilyname');
		# Create Productfamilyname model object
		$this->Productfamilyname = new Productfamilyname();
		$this->set('arrProductFamilynames',$this->Productfamilyname->getProductFamilyNames());
    	if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');    	
    	# Check if the form is submitted. First If step (loop) checks the form is submitted, then in next step the form validates the mandatory fields.
		# After validation, in the third step it checks each form value and then save them in an array variable $arrProductSaveData
    	if(isset($this->data) && !empty($this->data))
    	{	
			# array variable set to fetch insight submitted data
    		$arrProductSaveData = $this->data;
    		# Check the values are validated before saving in database
    		if($this->serverValidate($arrProductSaveData))
    		{    			
					# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();				
					# Check Organization name is blank or not
					if(isset($arrProductSaveData['Firm']['what_firm_name']) && trim($arrProductSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrProductSaveData['Firm']['what_firm_name']);						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrProductSaveData['Product']['what_firm_name'] = $firmParentID;
						else
							$arrProductSaveData['Product']['what_firm_name_text'] = $arrProductSaveData['Firm']['what_firm_name'];
					}
					# Check Productfamily name is blank or not	
					if(isset($arrProductSaveData['Productfamilyname']['who_product_family_name']) && trim($arrProductSaveData['Productfamilyname']['who_product_family_name']) > 0)
					{
						# Get product family name key if exists.	
						# Set Product family name Field Text value from filled autosearch field.
						$productFamilyNameID = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
						$arrProductSaveData['Product']['product_family_id'] = $productFamilyNameID;
					}
					else 
					{
							$arrProductSaveData['Product']['who_product_family_name_text'] = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
					}
					# Check Product name is blank or not
					if(isset($arrProductSaveData['Productname']['who_product_name']) && trim($arrProductSaveData['Productname']['who_product_name']) != '')
					{
						# Get product name key if exists.	
						$productNameID = $this->processProductNameExistance($arrProductSaveData['Productname']['who_product_name']);
						# Set Product name Field Text value from filled autosearch field.
						if(isset($productNameID) && $productNameID > 0)
							$arrProductSaveData['Product']['product_id'] = $productNameID;
						else
							$arrProductSaveData['Product']['who_product_name_text'] = $arrProductSaveData['Productname']['who_product_name'];
					}								
					# verify competitor id and save id else save name.
					if(isset($arrProductSaveData['Competitorname']['who_competitor_name']) && trim($arrProductSaveData['Competitorname']['who_competitor_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$competitorID = $this->processCompetitorExistance($arrProductSaveData['Competitorname']['who_competitor_name']);
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($competitorID) && $competitorID > 0)
							$arrProductSaveData['Product']['competitor_id'] = $competitorID;
						else
							$arrProductSaveData['Product']['who_competitor_name_text'] = $arrProductSaveData['Competitorname']['who_competitor_name'];
					}
					#Save User Id of the current user into the insights table.
					$arrProductSaveData['Product']['user_id'] = $this->Session->read('current_user_id');				
						
				# Code by Pragya Dave - fixed special characters insertion
				$arrProductSaveData['Product']['insight_summary'] = $this->utility->parseString($this->data['Product']['insight_summary']);
				# Verify if there is no error.
				if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
				{
					$arrProductSaveData['Product']['flag_mobile'] = 'Y';
					#Save Product Insight into database.
				 	$this->Insight->save($arrProductSaveData['Product']);
					$last_inserted_id = $this->Insight->id; 
					# Import Pilotgroup model
					
					App::import('Model', 'Pilotgroup');
					# Create Pilotgroup model object
					$this->Pilotgroup = new Pilotgroup();
			
					# Get Moderator Email Address.
					$arrModeratorAddress = $this->Pilotgroup->getModeratorEmailAddress();
					$moderator_email_address = $arrModeratorAddress;
					
					# Send Moderator a notification mail informing him that new Insight is added.
					$this->send_new_insight_mail_to_moderator($last_inserted_id, $moderator_email_address);
					
					# Redirect if save is successful.
					$this->redirect(SITE_URL . '/products/mthank/1');
				}				
    		} # End validation check if
    	} # End form value posted if 
    } # End function
	
	/**
	 * @author Gaurav Saini
	 * @created on 18/08/2011 
	 * This functiion is used to display thank you message to the user after the submission of feedback.
	 */
    function mthank()
    {
		# Stop caching for Browser
		header("Cache-Control: no-cache, must-revalidate");

		$this->layout='mobile';
	}
	
	/**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
	 * @modified by Pragya Dave
	 * @modified on 10/02/2011
	 * This functiion is used to display information for Edit insight records page. When the user clicks from the homepage Search Insight, it redirects to search page, if user search by Insight Id then it call this page function. Also under search result page, 'Click Here' link for each insight record leads to this page.
	 * The function fetches value based in the id passed in the url and retreive data for that insight id. When a user submit the request the fields data are update for that insight.
	 * @params id - Insight id
	 * @params flagSuccessMsg - Checks whether form is submitted with a flag value 1 or 0. If flagSuccessMsg=1 then displays msg-The records is saved otherwise no msg displayed.
	 */
    function records($id='', $flagSuccessMsg=0)
    {
			$_SESSION['RedirectToHomePage'] = '';

			# Include Layout
			$this->layout='front';	
                    
                        /* fetaching insight types data for insight type dropdown form
                         * 
                         */
                        
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();			
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
			$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));			
			# Import Content Type model
			App::import('Model', 'Contenttype');
			# Create Content Type model object
			$this->Contenttype = new Contenttype();
			# Set Who Content Type Array
			$this->set('arrContentTypes',$this->Contenttype->getContentTypes());
			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();
			# Set Who Practicearea Array
			$this->set('arrPracticeArea',$this->Practicearea->getPracticeArea());
			# Import Statusinsight model
			App::import('Model', 'Statusinsight');
			# Create Statusinsight model object
			$this->Statusinsight = new Statusinsight();
			# Set Who Statusinsight Array
			$this->set('arrCurrentStatus',$this->Statusinsight->getStatusList(TRUE));		
			# Import Sellingobstacle model
			App::import('Model', 'Sellingobstacle');
			# Create Sellingobstacle model object
			$this->Sellingobstacle = new Sellingobstacle();
			# Set Sellingobstacle Array
			$this->set('arrSellingObstacles',$this->Sellingobstacle->getSellingObstacle());
			
			# Set Who Market Array
			# Import Market model
			App::import('Model', 'Market');
			# Create Sellingobstacle model object
			$this->Market = new Market();
			$this->set('arrWhoMarket',$this->Market->getMarkets());		
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();
			$this->set('arrProductFamilynames',$this->Productfamilyname->getProductFamilyNames());		
			# Set model competitorname.
			App::import('Model', 'Competitorname');
			$this->Competitorname = new Competitorname(); // Object
			# Import Insighttype model
			App::import('Model', 'Insighttype');
			$this->Insighttype = new Insighttype();
			$arrinsighttype = $this->Insighttype->getinsightTypesValues();
			//$arrinsighttype[0] = " ";
                        /*
                         * @sukhvir change due adding new field type and change field type calle what_insight_type field enum to int
                         */
                //            foreach($arrinsighttype as $key=>$value) 
                //            {
                //                    $arr[0] = "";		
                //                    $arr[$key] = $value;			
                //            }
                //            $arrinsighttype[] = $arr;
                        $this->set('arrinsighttype', $arrinsighttype);
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Statusinsight model object
			$this->Pilotgroup = new Pilotgroup();
			# Set Who Pilotgroup Array
			//$this->set('arrDelegatedTo',$this->Pilotgroup->getPilotGroups(TRUE));				
			$this->set('arrDelegatedTo',$this->Pilotgroup->getPilotGroupsSME(TRUE));				
			if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
				$this->set('successDivSave','block');
				
			# Import Replyresponse model
			App::import('Model', 'Replyresponse');
			# Create Replyresponse model object
			$this->Replyresponse = new Replyresponse();
			
                        #set log insight value 
                        $this->Insight->flag_logtype = "I";
                        
			# Set id value for edit.
			$this->set('id',$id);
			$this->Insight->id = $id;
			$this->set('edit_flag', "");
			# Set Product Array. Check whether value is submitted from the form. The nested If next validate the mandatory form value. Once validated it checks each individual form value and store it in an array $arrProductSaveData which finally save all the form values into the database.
			if(isset($this->data) && !empty($this->data))
			{	
				$insight_status_changed	= false;
				$Ownership_taken = false;
				$insight_delegated_to = false;
				# Set form data values to user defined array.
				$arrProductSaveData = $this->data;
				//echo "<pre>"; print_r($arrProductSaveData); die;
				# Set flag for edit mode.
				$this->set('edit_flag', 'edit');				
				if($this->serverEditValidate($arrProductSaveData))
				{					
					# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();	
					# verify firm id and save id else save name.
					if(isset($arrProductSaveData['Product']['ownership_taken']) && $arrProductSaveData['Product']['ownership_taken'] !='')
					{
						$Ownership_taken = true;
					}
					if(isset($arrProductSaveData['Firm']['what_firm_name']))
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrProductSaveData['Firm']['what_firm_name']);						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrProductSaveData['Product']['what_firm_name'] = $firmParentID;
						else
							$arrProductSaveData['Product']['what_firm_name_text'] = $arrProductSaveData['Firm']['what_firm_name'];
					}					
					# verify Product Family id and save id .
					if(isset($arrProductSaveData['Productfamilyname']['who_product_family_name']) && trim($arrProductSaveData['Productfamilyname']['who_product_family_name']) >= 0)
					{
							$productFamilyNameID = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
							$arrProductSaveData['Product']['product_family_id'] = $productFamilyNameID;
					}						
					# verify Product id and save id or who_product_name_text.
					if(isset($this->data['Productname']['who_product_name']) )
					{
						# Get product name key if exists.	
						$productNameID = $this->processProductNameExistance($arrProductSaveData['Productname']['who_product_name']);
						# Set Product name Field Text value from filled autosearch field.
						if(isset($productNameID) && $productNameID > 0) 
						{
							$arrProductSaveData['Product']['product_id'] = $productNameID;
							$arrProductSaveData['Product']['who_product_name_text'] = "";
						}
						else 
						{
							$arrProductSaveData['Product']['who_product_name_text'] = $arrProductSaveData['Productname']['who_product_name'];
							$arrProductSaveData['Product']['product_id'] = "";
						}
					}
					#Save User Id of the current user into the insights table.
					$arrProductSaveData['Product']['user_id'] = $this->Insight->getCreatedById($arrProductSaveData['Product']['id']);
					#Check if attachment is there.
					if (isset($arrProductSaveData['ProductAttachment']['attachment_name']['name']) && !empty($arrProductSaveData['ProductAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrProductSaveData['ProductAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrProductSaveData['ProductAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
							if($this->utility->uploadAttachment($arrProductSaveData['ProductAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,PRODUCT_ATTACHMENT_UPLOAD_PATH))
							{
								# Set old file to be removed.
								$file = new File(PRODUCT_ATTACHMENT_UPLOAD_PATH . '/' . $arrProductSaveData['ProductAttachment']['old_attachment_name']);
								# Remove file.
								$file->delete();
								
								# If file exists physically then only file name will be saved in database.
								$filename = ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/'.$attachmentNewName;
								if(file_exists($filename)) 
								{								
									# Save Attachment New Name into database.
									$arrProductSaveData['Product']['attachment_name'] = $attachmentNewName;
									# Save Attachment Original Name into database.
									$arrProductSaveData['Product']['attachment_real_name'] = $arrProductSaveData['ProductAttachment']['attachment_name']['name'];
								}
							}
						}
					}
					elseif(isset($arrProductSaveData['ProductAttachment']['old_attachment_name']))
					{
						# If file exists physically then only file name will be saved in database.
						$old_filename = ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/'.$arrProductSaveData['ProductAttachment']['old_attachment_name'];
						if(file_exists($old_filename)) 
						{
							$arrProductSaveData['Product']['attachment_name'] = $arrProductSaveData['ProductAttachment']['old_attachment_name'];
						}
					}
					else
					{
						$arrProductSaveData['Product']['attachment_name'] = NULL;
					}
					# verify insight status if changed.
					if(isset($arrProductSaveData['Product']['insight_status']) && $arrProductSaveData['Product']['insight_status']> 0 )
					{
						$old_insight_status = $this->Session->read('old_insight_status');
						if($old_insight_status != $arrProductSaveData['Product']['insight_status'] )
						{
							$insight_status_changed = true;
						}
						#updated date
						$arrProductSaveData['Product']['date_updated'] = date('Y-m-d H:i:s', time());
					}
					# verify Delegated to if changed.
					if(isset($arrProductSaveData['Product']['deligated_to']) && $arrProductSaveData['Product']['deligated_to'] > 0)
					{
						$old_deligated_to = $this->Session->read('old_deligated_to');
						if($old_deligated_to != $arrProductSaveData['Product']['deligated_to'] )
						{
							$insight_delegated_to = true;
							$arrProductSaveData['Product']['delegation_confirmed'] = 'N';
						}
					}
					#data updated by
					$arrProductSaveData['Product']['updated_by'] = $this->Session->read('current_user_id');						
					if(isset($arrProductSaveData['Firm']['what_firm_name']))
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrProductSaveData['Firm']['what_firm_name']);						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0) 
						{
							$arrProductSaveData['Product']['what_firm_name'] = $firmParentID;
							$arrProductSaveData['Product']['what_firm_name_text'] = "";
						}
						else 
						{
							$arrProductSaveData['Product']['what_firm_name_text'] = $arrProductSaveData['Firm']['what_firm_name'];
							$arrProductSaveData['Product']['what_firm_name'] = "";
						}
					}
					# verify competitor id and save id else save name.
					if(isset($arrProductSaveData['Competitorname']['who_competitor_name']))
					{
						# Verify if a firm with this parent_id exists in the database.
						$competitorID = $this->processCompetitorExistance($arrProductSaveData['Competitorname']['who_competitor_name']);
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($competitorID) && $competitorID > 0) 
						{
							$arrProductSaveData['Product']['competitor_id'] = $competitorID;
							$arrProductSaveData['Product']['who_competitor_name_text'] = "";
						}
						else 
						{
							$arrProductSaveData['Product']['who_competitor_name_text'] = $arrProductSaveData['Competitorname']['who_competitor_name'];
							$arrProductSaveData['Product']['competitor_id'] = "";
						}
					}
					#Code by Pragya Dave - fixed special characters edit (08/02/2011)
					if(isset($arrProductSaveData['Product']['insight_summary']) && trim($arrProductSaveData['Product']['insight_summary']) != '') 
					{
						$arrProductSaveData['Product']['insight_summary'] = $this->utility->parseString($arrProductSaveData['Product']['insight_summary']);
				    }
					//$arrProductSaveData['Product']['do_action'] = $this->utility->parseString($arrProductSaveData['Product']['do_action']);
					#Verify if there is no error.
					if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
					{
							
										//		echo "<pre>"; print_R($arrProductSaveData['Product']);die;


						#Save Product Insight into database.
						$this->Insight->save($arrProductSaveData['Product']);	
						
						#Send E-Mail
						
						# If Status and Delegated to of Insight changes (both fields) then only delegation email will be sent. Status mail will not be sent. In delegation mail status will be removed if value of both fields changes.
						$showStatusInDelegationMail = true;
						
						# Send Insight Status Change Email.
						# Status notification Email will be sent only if Status of Insight is changed. If Status of Insight and Delegated To both fields are changes then Status mail will not be sent.
						$status_mail_send = false;
						if($insight_status_changed && !$insight_delegated_to)	
						{
							$this->sendstatusmailtocontributor($id);							
							$status_mail_send = true;
						}
						
						if($insight_status_changed && $insight_delegated_to)
						{
							$showStatusInDelegationMail = false;
						}
						
						# If delegate to field is changed then mail will be sent to SME
						if($insight_delegated_to)
						{
							$this->sendblankdelegeatedmailtosme($id, $showStatusInDelegationMail);	
						}
													
						$send_reply_mail = $this->Session->read('send_reply_mail');
						
						/*	
						if reply added to the insight but status is not changed, then send mail for new comment added notification.
						*/
						if($send_reply_mail && !$insight_status_changed) 
						{ 
							$loggedIn_user_role = $this->Session->read('current_user_role');
							//if($loggedIn_user_role == '' || $loggedIn_user_role == 'S'){
							
								# Set Contenttype Controller.
								App::import('Model', 'Replyresponse');
								# Create Contenttype model object.
								$this->Replyresponse = new Replyresponse();
								# Fetch recently added Response for Insight
								$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($id);
								
								if(count($RecentResponseInfo) > 0)
								{
									$recent_reply = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
								}
								else
								{
									$recent_reply = "";
								}
							
								$this->sendcommentmailtosme($id, $recent_reply, $loggedIn_user_role);
							//}
							$this->Session->write('send_reply_mail', false);
						}
						/*	
						if reply added to the insight and status is changed, then send mail for change in status notification.
						OR
						if reply not added to the insight and status is changed, then send mail for change in status notification.
						*/
						else if(!$status_mail_send && (($send_reply_mail && $insight_status_changed) || (!$send_reply_mail && $insight_status_changed && !$insight_delegated_to)))
						{
							$this->sendstatusmailtocontributor($id);
						}
						
						# Redirect if save is successful.
						$this->redirect(SITE_URL . '/products/records/'.$id.'/1');
					}
			}
		}	
		else 
    	{
                    # Reading insight record on the basis of $id.
                  
                    // $this->data =$this->Insight->read(); (It is back code ) adding extra condition for checking this is insight log on
                      
                       $this->data = $this->Insight->find('all', array('conditions' => array('Insight.id =' => $id,'AND'=>array('Insight.flag_logtype ='=>'I'))));
                       $this->data =  $this->data[0];
                      # Check condition in case of if record not in insight 
                       if(empty($this->data)){
                           $this->redirect(SITE_URL.'/'.strtolower('product').'s/search/'.$id.'/notfound');
                       }
                      
			

			# Import Product model
			$this->data['Product']['what_how_come'] = $this->data['Insight']['what_how_come'];
			$this->data['Product']['what_source_name'] = $this->data['Insight']['what_source_name'];
			$this->data['Product']['insight_summary'] = $this->utility->parseString($this->data['Insight']['insight_summary']);
			$this->data['Product']['customer_pain_points'] = ($this->data['Insight']['customer_pain_points']=='')?'TBC':$this->utility->parseString($this->data['Insight']['customer_pain_points']);
			$this->data['Product']['recommended_actions'] = $this->utility->parseString($this->data['Insight']['recommended_actions']);
			$this->data['Product']['relates_product_family_id'] = $this->data['Insight']['relates_product_family_id'];
			$this->data['Product']['practice_area_id'] = $this->data['Insight']['practice_area_id'];
			$this->data['Product']['do_action'] = $this->data['Insight']['do_action'];			
			
			if(isset($this->data['Insight']['product_family_id']) && is_numeric(intval($this->data['Insight']['product_family_id'])))
			{
				# Import Productfamilyname model
				App::import('Model', 'Productfamilyname');
				# Create Productfamilyname model object
				$this->Productfamilyname = new Productfamilyname();
				# Fetch product famliy name by Id
				$productFamilyNameResultArr = $this->Productfamilyname->getProductFamilyInfoById($this->data['Insight']['product_family_id']);
				$this->data['Productfamilyname']['product_family_id'] = $productFamilyNameResultArr['Productfamilyname']['family_name'];
			}
			else
			{
				$this->data['Productfamilyname']['product_family_id'] = $this->data['Insight']['who_product_family_name_text'];
			}
			if(isset($this->data['Insight']['product_id']) && is_numeric(intval($this->data['Insight']['product_id'])))
			{
				# Import Productname model
				App::import('Model', 'Productname');
				# Create Productname model object
				$this->Productname = new Productname();
				# Fetch product name by Id
				$productNameValue = $this->Productname->getProductInfoByID($this->data['Insight']['product_id']);
				# Set value for product name.
				$this->data['Productfamilyname']['product_id'] = $productNameValue;
			}
			else
			{
				$this->data['Productfamilyname']['product_id'] = $this->data['Insight']['who_product_name_text'];
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
			
			# Fetch Response Replies by Insight Id
			$productReplyValue = $this->Replyresponse->getResponseReplies($this->data['Insight']['id']);
			$this->set('productReplyValue', $productReplyValue);
						
			# Set variable values for view.
			$this->set('flag_mobile', $this->data['Insight']['flag_mobile']); 
			$this->set('what_how_come', $this->data['Insight']['what_how_come']); 
			$this->set('relates_content_type_label', $this->data['Insight']['content_type_id']);
			$this->set('relates_practice_area_label', $this->data['Insight']['practice_area_id']);
			$this->set('do_action_dummy', $this->data['Insight']['do_action']);
			$this->set('attachment_real_name', $this->data['Insight']['attachment_real_name']);
			$this->set('attachment_name', $this->data['Insight']['attachment_name']);
			$this->set('current_status_label', $this->data['Insight']['insight_status']);
			$this->set('deligated_to_selected', $this->data['Insight']['deligated_to']);
			$this->set('insight_summary', $this->data['Product']['insight_summary']);
			//$this->set('selected_product_ares_id', $this->data['Insight']['product_area_id']);
			$this->set('selected_product_family_id', $this->data['Insight']['product_family_id']);
			$this->set('selected_issue_field', $this->data['Insight']['issue_field']);
			$this->set('issue_description', $this->data['Issue']['issue_description']);
			$this->Session->write('old_insight_status', $this->data['Insight']['insight_status']);
			$this->Session->write('old_deligated_to', $this->data['Insight']['deligated_to']);
			$this->set('delegation_confirmed', $this->data['Insight']['delegation_confirmed']);

			# Import Issue model
			App::import('Model', 'Issue');
			# Create Issue model object
			$this->Issue = new Issue();
			# Fetch product name by Id
			
			# Get issues for matching combination of Product Family Name, Practice Area and Selling Obstacles.
			$productIssueValue = $this->Issue->getIssuesForCombination($this->data['Insight']['product_family_id'], $this->data['Insight']['practice_area_id'], $this->data['Insight']['selling_obstacle_id']);

			$arrIssueExist = array();
			$arr1[0] = "";
			
			//$arr1[-1] = "Not linked to an Issue";
			if(count($productIssueValue) > 0)
			{
				foreach($productIssueValue as $key=>$value) 
				{
					$key = $value['Issue']['id'];
					$arr1['Most Relevant Issues'][$key] = $value['Issue']['issue_title'];
					$arrIssueExist[] = $key;
				}
			}
			if(count($arrIssueExist)> 0){
				$IssuesToExclude = implode(",", array_unique($arrIssueExist));
			}

			# Get issues for matching Product Family Name but exclude the previously fetched records.
			$arrProductFamily = $this->Issue->getIssuesForProductFamily($this->data['Insight']['product_family_id'], $IssuesToExclude);
			if(count($arrProductFamily) > 0)
			{
				foreach($arrProductFamily as $key=>$value) 
				{
					$key = $value['Issue']['id'];
					//$arr1[$key] = $value['Issue']['issue_title'];
					$arr1['Same Product Family'][$key] = $value['Issue']['issue_title'];
					$arrIssueExist[] = $key;
				}
			}
			if(count($arrIssueExist)> 0){
				$IssuesToExclude = implode(",", array_unique($arrIssueExist));
			}
			
			$this->set('arrissue', $arr1);
			
			if( $this->data['Insight']['product_id'] > 0)
			{
				$productInfo = $this->Productname->getProductInfoByID( $this->data['Insight']['product_id']);
			}
			else
			{
				$productInfo = $this->data['Insight']['who_product_name_text'];
			}
			$this->set('selected_product_id', $productInfo);
			$this->set("selected_market_id", $this->data['Insight']['market_id']);
			$this->set("selected_insight_type", ucfirst(strtolower($this->data['Insight']['what_insight_type'])));	
			$this->set('currentSellingObstacle', $this->data['Insight']['selling_obstacle_id']);
			$this->set('contact_name', $this->data['Insight']['who_contact_name']);
			$competitorInfo = $this->Competitorname->getCompetitorName($this->data['Insight']['competitor_id']);
			if($competitorInfo > 0)
			{
				$competitorInfo = $competitorInfo['Competitorname']['competitor_name'];
			}
			else
			{
				$competitorInfo = $this->data['Insight']['who_competitor_name_text'];
			}
			$this->set('currentCompetitorid', $competitorInfo);
			$this->set('who_contact_role', $this->data['Insight']['who_contact_role']);
			$this->set( "created_by",$this->Pilotgroup->getPilotgroupNameByID($this->data['Insight']['user_id']));
			list($y,$m,$d) =split('[- ]',$this->data['Insight']['date_submitted']);
			$cdate =  date('dS M Y', mktime(0, 0, 0, $m, $d, $y));
			$this->set( "created_date",$cdate);
			$this->set("submitted_date",$this->data['Insight']['date_submitted']);			
			if($this->data['Insight']['what_firm_name'] > 0)
			{
				$firmInfo = $this->Firm->getFirmInfoByID($this->data['Insight']['what_firm_name']);
			}
			else
			{
				$firmInfo = $this->data['Insight']['what_firm_name'];
			}
			$this->set('currentOrganisationid', $firmInfo);
			# URL to go back to search result page.
			$this->set('backUrl', $this->Cookie->read('backUrl'));			
    	}
    } # End function    

	/**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
     * This function sets the default display status for the error divs on the view page that will be 'none'.
	 * These messages will be active on Add/Edit screens if any error msg is posted with a flag otherwise the divs will be hidden by default. The function is called in add and edit insight pages.  
     */
    function setMessageDivDefaultStatus()
    {
    	# Set the default display status for the recommended action error div.
    	$this->set('errProductRecommendedAction','none');
    	# Set the default display status for the customer pain points error div.
    	$this->set('errDivProductCustomerPainPoints','none');
    	# Set the default display status for the summary error div.
    	$this->set('errDivProductInsightSummary','none');
    	# Set the default display status for the atatchment error div.
    	$this->set('errDivAttachment','none');
    	# Set the default display status for the atatchment size error div.
    	$this->set('errDivAttachmentSize','none');
    	# Set the default display status for the save success message div.
    	$this->set('successDivSave','none');
    } # End function
    
	/**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
     * This function validates the form on the server side in case there is no client side validation or any failure. Insight summary field is mandatory in the aplication so it cannot be left blank. Function used in Add/Edit insight.
	 * @param $arrProductSaveData 
     * @return true/false
     */
    function serverValidate($arrProductSaveData)
    {
    	# Validate value of insight summary/customer feedback.    
		if(isset($arrProductSaveData['Product']['insight_summary']) && trim($arrProductSaveData['Product']['insight_summary']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errDivProductInsightSummary','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}   	
    	# Validate value of customer pain points.    
		if(isset($arrProductSaveData['Product']['customer_pain_points']) && trim($arrProductSaveData['Product']['customer_pain_points']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errDivProductCustomerPainPoints','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}    	
    	# Validate value of recommended action.    
		/*if(isset($arrProductSaveData['Product']['recommended_actions']) && trim($arrProductSaveData['Product']['recommended_actions']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errProductRecommendedAction','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}*/	

    	# Return error flag.
    	return $this->utility->getErrorFlagReturnStatus($this->flagErrMsg);
    } # End function    
    

    function serverEditValidate($arrProductSaveData)
    {
    	# Validate value of insight summary/customer feedback.    
		/*if(isset($arrProductSaveData['Product']['insight_summary']) && trim($arrProductSaveData['Product']['insight_summary']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errDivProductInsightSummary','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	} */  	
    	# Validate value of customer pain points.    
		/*if(isset($arrProductSaveData['Product']['customer_pain_points']) && trim($arrProductSaveData['Product']['customer_pain_points']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errDivProductCustomerPainPoints','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	} */   	
    	# Validate value of recommended action.    
		/*if(isset($arrProductSaveData['Product']['recommended_actions']) && trim($arrProductSaveData['Product']['recommended_actions']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errProductRecommendedAction','block');
    		# Set error flag value.
    		$this->flagErrMsg = 1;
    	}*/	

    	# Return error flag.
    	return $this->utility->getErrorFlagReturnStatus($this->flagErrMsg);
    } # End function    
    

	/**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
     * This function validates attachment on server side. It verified that the specified file exists on the given location of not. If exists returns the flag true else false. Function used in Edit insight.
     * @param String $attachmentExtension
     * @param Integer $attachmentSize
     * @return True/False Returns Error Flag
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
    } # End function    
    
	/**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
     * This validates and returns entered firm information. It checks the Firm id in the database and if exists then returns the parent_id associated with the firm id else return 0. Function used in Add/Edit insight.
     * @param String $firmParentID - Checks for the firm id that if the passed firm id exists in database or not.
     * @return Firm Parent ID
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
    } # End function
	
	/**
	 * @author Pragya Dave
	 * @created on 10/02/2011
     * This validates and returns entered firm information. It checks the Market name in the database (table Markets) and return the market id if exists. Function used in Add/Edit insight.
     * @param String $marketName - checks if the supplied marketName exists in database. If exists then return the id of market name else return 0.
     * @return Market ID
     */
    function processMarketExistance($market='')
    {
    	# Import Market model
    	App::import('Model', 'Market');
    	# Create Market model object
		$this->Market = new Market();		
		# Get array for market name id.
    	$marketId = $this->Market->getMarketDataByName($market);
    	# Check if market id exists.
    	if(isset($marketId) && $marketId > 0)
    		return $marketId;
    	else
    		return 0;
    } # End function    

    /**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
     * This validates and returns entered product family name information. It checks the product family name in the database table Productfamilynames by calling the function getProductFamilyName() and return the id associated with that name. Otherwise returns 0. 
     * @param String $productFamilyName - checks if the supplied Product Family Name exists in database. If exists then return the id of Product Family Name else return 0.
     * @return Product Family Name ID
     */
    function processProductFamilyNameExistance($productFamilyName)
    {
    	# Import Product Family Name model
    	App::import('Model', 'Productfamilyname');
    	# Create Product Family Name model object
		$this->Productfamilyname = new Productfamilyname();
		# Get array for product family name id.
    	$arrProductFamilyNameID = $this->Productfamilyname->getProductFamilyName($productFamilyName);
    	# Check if product family name id exists.
    	if(isset($arrProductFamilyNameID) && is_array($arrProductFamilyNameID) && count($arrProductFamilyNameID) > 0)
    		return $arrProductFamilyNameID['Productfamilyname']['id'];
    	else
    		return 0;
    } # End function    

    /**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
     * This validates and returns entered product name information. It checks the product name in the database table Productnames by calling the function getProductNameCodeData() and return the id associated with that name. Otherwise returns 0. Function used in Add/Edit/Search insight.
     * @param String $productName - checks if the supplied Product Name exists in database. If exists then return the id of Product Family Name else return 0.
     * @return Product Name ID
     */
    function processProductNameExistance($productName)
    {
    	# Import Firm model
    	App::import('Model', 'Productname');
    	# Create Firm model object
		$this->Productname = new Productname();		
		# Get array for product name id.
    	$arrProductNameID = $this->Productname->getProductNameCodeData($productName);
    	# Check if product family name id exists.
    	if(isset($arrProductNameID) && is_array($arrProductNameID) && count($arrProductNameID) > 0)
    		return $arrProductNameID['Productname']['id'];
    	else
    		return 0;
    } # End function    
    
	/**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
     * This functiion is to display insight search page. When the user clicks the RUN A SEARCH button from the homepage, this functions is called.
	 * This functionality set values for search page with dynamic field values / autocomplete fields.
     * @param integer $insightId - sets the insight id
     * @param integer $found - helps in defining date message. 
     */
    function search($insightId = '', $found = '')
    {
		# Set layout
		$this->layout='front';
		# Setting current url to redirect in case no result found.
		$this->Cookie->write('currentUrl', SITE_URL.'/products/search');
		$this->Cookie->write('backUrl', SITE_URL.'/products/search');
		# Removing conditions array from session if any.
		$this->Session->delete('conditionsArr');
                    
    	# Set How Come Array
    	# Import Insightabout model
    	App::import('Model', 'Insightabout');
    	# Create Insightabout model object
		$this->Insightabout = new Insightabout();
    	# Set Who Insightabout Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
    	# Import Content Type model
    	App::import('Model', 'Contenttype');
    	# Create Content Type model object
		$this->Contenttype = new Contenttype();
    	# Set Who Content Type Array
    	$this->set('arrContentTypes', $this->Contenttype->getContentTypes(TRUE));    	
    	# Import Practicearea model
    	App::import('Model', 'Practicearea');
    	# Create Practicearea model object
		$this->Practicearea = new Practicearea();
    	# Set Practicearea Names Array
    	$this->set('arrPracticeArea', $this->Practicearea->getPracticeArea(TRUE));
		# Import Pilotgroup model
		App::import('Model', 'Pilotgroup');
		# Create Pilotgroup model object
		$this->Pilotgroup = new Pilotgroup();
		# Set Pilotgroup names array for search view.
		$arrCreatedBy = $this->Pilotgroup->getPilotGroups(TRUE); //True passed for protect pilot group key id for dropdown.
		$this->set('arrCreatedBy', $arrCreatedBy);
		
    	# Import Statusinsight model
    	App::import('Model', 'Statusinsight');
    	# Create Statusinsight model object
		$this->Statusinsight = new Statusinsight();
    	# Set Statusinsight Names Array
		# Set db values for dropdown with the caption
		foreach($this->Statusinsight->getStatusList() as $key=>$value) 
		{				
			$arrstatus[$key] = $value;			
		}
		$arrinsightstatus[] = $arrstatus;		
    	$this->set('arrStatusinsight', $arrinsightstatus);		
		# Set CreatedBy users Array
    	$this->set('arrDelegatedTo', $arrCreatedBy); // Current owner users list.		
		# Import Sellingobstacle model
    	App::import('Model', 'Sellingobstacle');
    	# Create Sellingobstacle model object
		$this->Sellingobstacle = new Sellingobstacle();
    	# Set Sellingobstacle Array
    	$this->set('arrSellingObstacles',$this->Sellingobstacle->getSellingObstacle());

		# Set Who Market Array
		# Import Market model
    	App::import('Model', 'Market');
    	# Create Sellingobstacle model object
		$this->Market = new Market();
		$this->set('arrWhoMarket',$this->Market->getMarkets());		
		# Import Productfamilyname model
		App::import('Model', 'Productfamilyname');
		# Create Productfamilyname model object
		$this->Productfamilyname = new Productfamilyname();
		$this->set('arrProductFamilynames',$this->Productfamilyname->getProductFamilyNames());	
		# Code by Pragya Dave
		# Import Insighttype model
			App::import('Model', 'Insighttype');
			$this->Insighttype = new Insighttype();
			$arrinsighttype = $this->Insighttype->getinsightTypesValues();
			$arrinsighttype[0] = "Insight Type";
                       // echo '<pre>'; print_r($arrinsighttype); exit; 
                        /*
                         * @sukhvir change due adding new field type and change field type calle what_insight_type field enum to int
                         */
                //            foreach($arrinsighttype as $key=>$value) 
                //            {
                //                    $arr[0] = "";		
                //                    $arr[$key] = $value;			
                //            }
                //            $arrinsighttype[] = $arr;
                        $this->set('arrinsighttype', $arrinsighttype);
               
		# End code Pragya Dave
		# To show error message not found in case result not found
		# by search by insight id.
		if($found == 'date_mismatch') 
		{
			$this->set('datemismatch', $found);
		}elseif($found == 'incorrect_start_date') 
		{
			$this->set('incorrect_start_date', $found);
		}elseif($found == 'incorrect_end_date') 
		{
			$this->set('incorrect_end_date', $found);
		}else {
			$this->set('found', $found);
		}
		$this->set('insightId', $insightId);		
    } # End function
	
    /**
	* @author Mohit Khurana
	* @created on 01/09/2010
    * This functiion is to display insight search results page. When the user clicks Search button, then the search page leads to search result page as per the critera applied for searching by user.
	* The function will return results of Insights based on the search request posted. If Search is by Insight Id then the request goes to Edit insight page otherwise to search result page for further refining if required.
	* @Modified By: Gaurav Saini
	* @Modified Date: 07/22/2011
	* @Modified Description: This function is changed to add search logic based on Issue. Now insight can be searched on the basis of issue. The issue will be selected by issue lookup control.
	* @param Int $insightId - checks if insightid is passed then searchs for the specific insight id else search for other passed parameters.
	 
     */
    function results($insightId = '')
    {	
			header("Cache-Control: no-cache, must-revalidate");

			# Set layout
			$this->layout='front';		
			# Initialize Models /Tables
                        /* fetaching insight types data for insight type dropdown form
                         * 
                         */
                        # Import feedback Type model
                        App::import('Model', 'Insighttype');
                        # Create Insight Type model object
                        $this->Insighttype = new Insighttype();
                        # Set Insight types array   
                        $insighttypevalues = $this->Insighttype->getinsightTypesValues();
                        $this->set('insighttypevalues',$insighttypevalues);
                        
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();
			$conditionsArr = array();
			App::import('Model', 'Firm');
			# Create Insight model object
			$this->Firm = new Firm();
			# Set How Come Array
			# Import Insightabout model
			App::import('Model', 'Insightabout');
			# Create Content Type model object
			$this->Insightabout = new Insightabout();			
			# Set Who Content Type Array
			# Set db values for dropdown with the caption
			foreach($this->Insightabout->returnStaticData(TRUE) as $key=>$value) 
			{						
				$arrabout[$key] = $value;			
			}
			# Set default dropdown value
			$arrabout[0] = "Origin of Feedback?";				
			$this->set('arrHowCome',$arrabout);
			# Import Content Type model
			App::import('Model', 'Contenttype');
			# Create Content Type model object
			$this->Contenttype = new Contenttype();
			# Set Who Content Type Array
			# Set db values for dropdown with the caption
			foreach($this->Contenttype->getContentTypes(TRUE) as $key=>$value) 
			{							
				$arrcontent[$key] = $value;			
			}	
			# Set default dropdown value
			$arrcontent[0] = "Content Type";			
			$this->set('arrContentTypes', $arrcontent);			
			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();
			# Set Practicearea Array
			# Set db values for dropdown with the caption
			foreach($this->Practicearea->getPracticeArea(TRUE) as $key=>$value) 
			{	
				$arrapractice_area[$key] = $value;			
			}
			# Set default dropdown value	
			$arrapractice_area[0] = "Practice Area";			
			$this->set('arrPracticeArea',$arrapractice_area );
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();
			# Set Pilotgroup names array for search view.
			$arrCreatedBys = $this->Pilotgroup->getPilotGroups(TRUE); //True passed for protect pilot group key id for dropdown.
			# Set db values for dropdown with the caption
			foreach($arrCreatedBys as $key=>$value) 
			{						
				$arrCreatedBy[$key] = $value;			
			}	
			# Set default dropdown value	
			$arrCreatedBy[0] = "Created By";			
			$this->set('arrCreatedBy', $arrCreatedBy);
			
			# Create Issues model object
			$this->Issue = new Issue();
			# Set Issue names array for search view.
			$arrIssues = $this->Issue->getIssues(TRUE);
			# Set db values for dropdown with the caption
			foreach($arrIssues as $key=>$value) 
			{						
				$arrIssues[$key] = $value;			
			}	
			# Set default dropdown value	
			$arrIssues[0] = "Issue";			
			//$arrIssues[-1] = "Not linked to an Issue";			
			$this->set('arrIssues', $arrIssues);			
			
			# Import Statusinsight model
			App::import('Model', 'Statusinsight');
			# Create Statusinsight model object
			$this->Statusinsight = new Statusinsight();
			# Set Statusinsight Array
			# Set db values for dropdown with the caption
			foreach($this->Statusinsight->getStatusList() as $key=>$value) 
			{
				$arrstatus[$key] = $value;			
			}
			$arrinsightstatus[] = $arrstatus;		
			$this->set('arrStatusinsight', $arrinsightstatus);
			# Set db values for dropdown with the caption
			foreach($arrCreatedBys as $key=>$value) 
			{						
				$arrDelegated[$key] = $value;			
			}	
			# Set default dropdown value
			$arrDelegated[0] = "Current Owner";	
			$this->set('arrDelegatedTo', $arrDelegated); // Current owner users list.			
			# Import Sellingobstacle model
			App::import('Model', 'Sellingobstacle');
			# Create Sellingobstacle model object
			$this->Sellingobstacle = new Sellingobstacle();
			# Set Sellingobstacle Array
			# Set db values for dropdown with the caption
			foreach($this->Sellingobstacle->getSellingObstacle() as $key=>$value) 
			{					
				$arrselling_obstacle[$key] = $value;			
			}
			$arrselling_obstacle[0] = "Selling obstacles";		
			$this->set('arrSellingObstacles', $arrselling_obstacle);

			# Set Who Market Array
			# Import Market model
			App::import('Model', 'Market');
			# Create Market model object
			$this->Market = new Market();
			# Set db values for dropdown with the caption
			foreach($this->Market->getMarkets() as $key=>$value) 
			{						
				$arrMarket[$key] = $value;			
			}	
			# Set default dropdown value
			$arrMarket[0] = "Market Segment";	
			$this->set('arrWhoMarket',$arrMarket);			
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();
			# Set db values for dropdown with the caption
			foreach($this->Productfamilyname->getProductFamilyNames() as $key=>$value) 
			{				
				$arraprod_family[$key] = $value;			
			}	
			# Set default dropdown value	
			$arraprod_family[0] = "Product Family Name";						
			$this->set('arrProductFamilynames',$arraprod_family);
			# Import Competitorname model
			App::import('Model', 'Competitorname');
                        # Create Competitorname model object
			$this->Competitorname = new Competitorname();
			# Import Insighttype model
			App::import('Model', 'Insighttype');
			$this->Insighttype = new Insighttype();
			$arrinsighttype = $this->Insighttype->getinsightTypesValues();		
			# Set db values for dropdown with the caption			
			$arrinsighttype[0] = "Insight Type";
                       // echo '<pre>'; print_r($arrinsighttype); exit;
                        
                        /*
                         * @sukhvir change due adding new field type and change field type calle what_insight_type field enum to int
                         */
                //            foreach($arrinsighttype as $key=>$value) 
                //            {
                //                    $arr[0] = "";		
                //                    $arr[$key] = $value;			
                //            }
                //            $arrinsighttype[] = $arr;
                        $this->set('arrinsighttype', $arrinsighttype);
			# Set array for Sorting fields for the view result page (dropdown selection).
			$sort_type = array('-' => 'Sort Results By',
					'what_insight_type' => 'Insight Type',
					'what_how_come' => 'Origin of Feedback?',					
				       # 'who_contact_role' => 'Contact Name / Role',
				       #'who_contact_name' => 'Contact Name / Publication',
                                        'who_contact_role' => 'Role',
                                        'who_contact_name' => 'Contact Name',
                                        'product_family_id,who_product_family_name_text' => 'Product Family Name',
					'product_id,who_product_name_text' => 'Product Name',
					'content_type_id' => 'Content Type',
					'practice_area_id' => 'Practice Area',
					'competitor_id,who_competitor_name_text' => 'Competitor',
					'selling_obstacle_id' => 'Selling Obstacles',
					'user_id' => 'Created By',
					'insight_status' => 'Feedback Status',
					'deligated_to' => 'Current Owner',
					'issue_field' => 'Issue',
					//'product_area_id' => 'Product Area',
					'market_id' => 'Market Segment'
			);	
			$this->set('sort_type', $sort_type);			
			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();			
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();			
			# Set Productname Controller.
			App::import('Model', 'Productname');
			# Create Productname model object.
			$this->Productname = new Productname();				
			# Set Contenttype Controller.
			App::import('Model', 'Contenttype');
			# Create Contenttype model object.
			$this->Contenttype = new Contenttype();
			# Search process (conditions composing) start.
			$postval = $this->Session->read('postdata');
			$this->log("===Post Data===>" .$this->data, LOG_DEBUG);
			# Start Insight process 
			$this->Insight->begin();			
			try 
			{		
				# Search by id.
				if(isset($this->data) && !empty($this->data) && isset($this->data['Insight']['id']) && $this->data['Insight']['id'] != "Search By Feedback Id")
				{
					if(!is_numeric($this->data['Insight']['id']) && $this->data['Insight']['id'] != "") 
					{
						$this->redirect($this->Cookie->read('currentUrl') .'/'. $this->data['Insight']['id'] .'/notfound');
					}	
					$insightType = $this->Insight->field('id', array('id' => $this->data['Insight']['id']));
					if(trim($insightType) != "") 
					{				
							$this->redirect(SITE_URL.'/'.strtolower('product').'s/records/'.$this->data['Insight']['id']);
					}
					elseif($this->data['Insight']['id']>0)
					{
							$this->redirect($this->Cookie->read('currentUrl') .'/'. $this->data['Insight']['id'] .'/notfound');
					}				
				}
				# Retreive Post data
				if(isset($this->data) && !empty($this->data) || $insightId>0 || isset($postval))
				{
					if(!isset($this->data) ) 
					{
						$this->data = $postval['data'];
					}
					# Retreive Post Free text string
					# This code fetched the text entered in free text search box from basic search page. The text string will be searched among all the fields in the Insight table. For those in which id is used as foreign key to other table, it will first check for that id in the respective table and then check if that text exists in the field, if matches returns to the Insight record else display - No record found.
					$search_string	= trim($this->data['Product']['free_search_text']);
					$this->set('search_string', $search_string);					
					# Set value of search string into POST variable so that it gets maintained into session later
					$_POST['data']['free_search_text']= $search_string;					
					# If the text is Free Text Search then the string is as null and all the Insights will be fetched in this case
					if($search_string == "Free Text Search")
					{
						$search_string = "";
					}		
					# Check if Insight Type is set to All or nothing is selected, then fetch the records of all Insight types. 
					if( ( trim($this->data['Product']['what_insight_type']) != "All" &&  $this->data['Product']['what_insight_type']) != 0) 
					{
						$conditionsArr = array('LOWER(Insight.what_insight_type)' => strtolower($this->data['Product']['what_insight_type']));
					}
					$parentId = "";
					$mparentId = "";
					$productFamilyNameID = "";
					$productNameID = "";
					$competitorID ="";
					$practiceareaID = "";
					$contentTypeID = "";
					$SellingobstacleID = "";
					//$ProductAreaID = "";
					$InsightStatusID = "";
					$UserId = "";
					$insightid = "";					
					# Retreive submitted Free text string if not Empty/Null
					if(isset($search_string) && $search_string != "" ) 
					{					
						# Check for firm name/ Condition for firm name.	If name exists check the parent_id is equal to what_firm_name field.		
						$parentId = $this->Firm->getsearchFirmData($search_string);
						if($parentId > 0)
							$what_firm_name = ' OR Insight.what_firm_name = '.$parentId;	
						else $what_firm_name = '';						
						# Check for Market name/ Condition for Market name.	If name exists check the id is equal to market_id field.
						$mparentId = $this->Market->getsearchMarketData($search_string);
						if($mparentId > 0)
							$market_id = ' OR Insight.market_id = '.$mparentId;	
						else $market_id = '';						
						# Check for Productfamilyname name/ Condition for Productfamilyname name.If name exists check the id is equal to product_family_id field.
						$productFamilyNameIDs = $this->Productfamilyname->getsearchProductfamilynameData($search_string);					
						if($productFamilyNameIDs > 0) 
							$productFamilyNameID = ' OR Insight.product_family_id = '.$productFamilyNameIDs;					
						else $productFamilyNameID = '';
						# Check for productName name/ Condition for productName name.If name exists check the id is equal to product_id field.
						$productNameID = $this->Productname->getsearchProductNameData($search_string);
						if($productNameID > 0)
							$productNameID = ' OR Insight.product_id = '.$productNameID;	
						else $productNameID = '';
						
						# Check for Competitor name/ Condition for Competitorn name.If name exists check the id is equal to competitor_id field.
						$competitorIDs = $this->Competitorname->getsearchCompetitorNameData($search_string);
						if($competitorIDs > 0) 
							$competitorID = ' OR Insight.competitor_id = '.$competitorIDs;						
						else $competitorID = '';
						# Check for practicearea name/ Condition for practicearea name.If name exists check the id is equal to practice_area_id field.
						$practiceareaID = $this->Practicearea->getsearchPracticeareaData($search_string);
						if($practiceareaID > 0)
							$practiceareaID = ' OR Insight.practice_area_id = '.$practiceareaID;	
						else $practiceareaID = '';
						# Check for Contenttype name/ Condition for Contenttype name.If name exists check the id is equal to content_type_id field.
						$contentTypeID = $this->Contenttype->getsearchContentTypeIData($search_string);
						if($contentTypeID > 0)
							$contentTypeID = ' OR Insight.content_type_id = '.$contentTypeID;	
						else $contentTypeID = '';
						# Check for Sellingobstacle name/ Condition for Sellingobstacle name.If name exists check the id is equal to selling_obstacle_id field.
						$SellingobstacleID = $this->Sellingobstacle->getsearchSellingobstacleData($search_string);
						if($SellingobstacleID > 0)
							$SellingobstacleID = ' OR Insight.selling_obstacle_id = '.$SellingobstacleID;	
						else $SellingobstacleID = '';
						/*# Check for Productarea name/ Condition for Productarea name.If name exists check the id is equal to product_area_id field.
						$ProductAreaID = $this->Productarea->getsearchProductareaData($search_string);
						if($ProductAreaID > 0)
							$ProductAreaID = ' OR Insight.product_area_id = '.$ProductAreaID;	
						else $ProductAreaID = '';*/
						# Check for Statusinsight name/ Condition for Statusinsight name.If name exists check the id is equal to insight_status field.
						$InsightStatusID = $this->Statusinsight->getsearchStatusData($search_string);
						if($InsightStatusID > 0)
							$InsightStatusID = ' OR Insight.insight_status = '.$InsightStatusID;	
						else $InsightStatusID = '';
						# Check for Pilotgroup name/ Condition for Pilotgroup name. If name exists check the id is equal to user_id,deligated_to,updated_by field.
						$UserIds = $this->Pilotgroup->getsearchPilotgroupUserIData($search_string);
						if($UserIds > 0) 
						{
							$UserId = ' OR Insight.user_id = '.$UserIds;	
							$UserId .= ' OR Insight.deligated_to = '.$UserIds;	
							$UserId .= ' OR Insight.updated_by = '.$UserIds;	
						}
						else $UserId = '';						
						# Check if the string is numeric then checks the Insight id
						if(isset($search_string) && is_numeric($search_string) )
							$insightid = ' OR Insight.id = "'.$search_string. '"';	
                                                                                                                                             
						# Search in all field values
						$conditionsArr = array_merge($conditionsArr, 
							array(' ( Insight.what_insight_type LIKE "'.'%'. strtolower($search_string). '%' .'"'.
							$insightid.
							' OR LOWER(Insight.what_how_come) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.				
							' OR LOWER(Insight.who_contact_name) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_contact_role) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.insight_summary) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.do_action) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.what_firm_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_firm_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_product_family_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_product_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_competitor_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							$what_firm_name.
							$market_id.
							$productFamilyNameID.
							$productNameID.
							$competitorID.
							$practiceareaID.
							$contentTypeID.
							$SellingobstacleID.
							//$ProductAreaID.
							$InsightStatusID.
							$UserId.
							' OR Insight.date_updated = "'.$search_string.'"'.
							' OR Insight.date_submitted = "'.$search_string.'" )'
						));
					}
					# If not Free text string then check other posted values
					if($insightId<1) 
					{					
					
							# Check the date range lies less than current date and is inbetween the range otherwise generate respective errors
							if( ( ( isset($this->data['Product']['created_from']) && trim($this->data['Product']['created_from'])) != '') && ( isset($this->data['Product']['created_to']) && trim($this->data['Product']['created_to'])) != '') 
							{
								 $start = strtotime($this->data['Product']['created_from']);
								 $end = strtotime($this->data['Product']['created_to']);
								 if ($start-$end > 0)
									 $this->redirect($this->Cookie->read('currentUrl') .'/'. 'yes/date_mismatch' );							
							}			
							if( ( ( isset($this->data['Product']['created_from']) && trim($this->data['Product']['created_from'])) != '') )  
							{
								 $start = strtotime($this->data['Product']['created_from']);
								 $end = strtotime(date('Y-m-d'));
								 if ($start-$end > 0) 
									 $this->redirect($this->Cookie->read('currentUrl') .'/'. 'yes/incorrect_start_date' );								 
							}
							if( ( ( isset($this->data['Product']['created_to']) && trim($this->data['Product']['created_to'])) != '') )  
							{
								 $start = strtotime($this->data['Product']['created_to']);
								 $end = strtotime(date('Y-m-d'));
								 if ($start-$end > 0) 
									 $this->redirect($this->Cookie->read('currentUrl') .'/'. 'yes/incorrect_end_date' );							 
							}	
							# Check insight type value from post data
							if( (  isset($this->data['Product']['what_insight_type']) && trim($this->data['Product']['what_insight_type'])) != 0) 
							{
								if(trim($this->data['Product']['what_insight_type']) != "All" )					
									$conditionsArr = array('Insight.what_insight_type' => $this->data['Product']['what_insight_type']);
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_what_insight_type"] == "yes")) || $this->data['Product']["basic_search_what_insight_type"] == "yes" )  
								{	
									$this->set('basic_search_what_insight_type', "yes");
								}
								$this->set('value_what_insight_type', $this->data['Product']['what_insight_type']);
							}
							# Check what how come value from post data
							if(isset($this->data['Product']['what_how_come']) && $this->data['Product']['what_how_come'] != "0" && $this->data['Product']['what_how_come'] != "Origin of Feedback?" ) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.what_how_come' => $this->data['Product']['what_how_come']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" &&$this->data['Product']["basic_search_what_how_come"] == "yes" )) || $this->data['Product']["basic_search_what_how_come"] == "yes" )  	
								{
										$this->set('basic_search_what_how_come', "yes");
								}
								$this->set('value_what_how_come', $this->data['Product']['what_how_come']);
							}		
							# Check for source name.
							if(isset($this->data['Product']['what_source_name']) && trim($this->data['Product']['what_source_name']) != "") 
							{
								$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_source_name) LIKE' => '%' . strtolower($this->data['Product']['what_source_name']) . '%'));				
							}
							# Check for firm name/ Condition for firm name.
							if(isset($this->data['Firm']['what_firm_name']) && trim($this->data['Firm']['what_firm_name']) != ""  && $this->data['Firm']['what_firm_name'] != "Organisation Name") 
							{
								$parentId = $this->processFirmExistance($this->data['Firm']['what_firm_name']);
								if($parentId>0) 
								{
									$conditionsArr = array_merge($conditionsArr, array('Insight.what_firm_name' => $parentId));
								}
								else 
								{
										$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_firm_name_text) LIKE' => '%' . strtolower($this->data['Firm']['what_firm_name']) . '%'));
								}
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_what_firm_name"] == "yes")) || $this->data['Product']["basic_search_what_firm_name"] == "yes" ) 
								{ 		
										$this->set('basic_search_what_firm_name', "yes");
								}
								$this->set('value_what_firm_name', $this->data['Firm']['what_firm_name']);
							}
							# Check / Condition for contact Name / role.
                                                        /*
                                                         * @sukhvir update text only here contact Name / role should be "Role" and Contact Name / Publication "Contact Name"
                                                         */
							if(isset($this->data['Product']['who_contact_role']) && trim($this->data['Product']['who_contact_role']) != "" && $this->data['Product']['who_contact_role'] != "Role") 
							{
								$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_contact_role) LIKE' => '%' . strtolower($this->data['Product']['who_contact_role']) . '%'));		
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_contact_role"] == "yes")) || $this->data['Product']["basic_search_who_contact_role"] == "yes" ) 
								{ 		
										$this->set('basic_search_who_contact_role', "yes");
								}
								$this->set('value_who_contact_role', $this->data['Product']['who_contact_role']);
							}
							# Check / Condition for contact name.
							if(isset($this->data['Product']['who_contact_name']) && trim($this->data['Product']['who_contact_name']) != "" && $this->data['Product']['who_contact_name'] != "Contact Name") 
							{
								$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_contact_name) LIKE' => '%' . strtolower($this->data['Product']['who_contact_name']) . '%'));	
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_contact_name"] == "yes")) || $this->data['Product']["basic_search_who_contact_name"] == "yes" ) 
								{ 		
									$this->set('basic_search_who_contact_name', "yes");
								}
								$this->set('value_who_contact_name', $this->data['Product']['who_contact_name']);
							}
							# Set condition for product family name
							if(isset($this->data['Productfamilyname']['who_product_family_name']) && trim($this->data['Productfamilyname']['who_product_family_name']) > 0)
							{
									# Check whether product family id matched with Insight product_family_id
									$conditionsArr = array_merge($conditionsArr, array('Insight.product_family_id' => $this->data['Productfamilyname']['who_product_family_name']));					
									# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
									if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_product_family_name"] == "yes")) || $this->data['Product']["basic_search_who_product_family_name"] == "yes" ) 
									{ 		
										$this->set('basic_search_who_product_family_name', "yes");
									}
									$this->set('value_who_product_family_name', $this->data['Productfamilyname']['who_product_family_name']);
							}
							# Set condition for product name
							if(isset($this->data['Productname']['who_product_name']) && trim($this->data['Productname']['who_product_name']) != ''  && $this->data['Productname']['who_product_name'] != "Product Name")
							{
								# Get product name key if exists.	
								$productNameID = $this->processProductNameExistance($this->data['Productname']['who_product_name']);
								# Check whether id matched with the Insight product_id otherwise check the value from who_product_name_text
								if(isset($productNameID) && $productNameID > 0)
									$conditionsArr = array_merge($conditionsArr, array('Insight.product_id' => $productNameID));
								else
									$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_product_name_text) LIKE' => '%' . strtolower($this->data['Productname']['who_product_name']) . '%'));				
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_product_name"] == "yes")) || $this->data['Product']["basic_search_who_product_name"] == "yes" ) 
								{ 		
										$this->set('basic_search_who_product_name', "yes");
								}
								$this->set('value_who_product_name', $this->data['Productname']['who_product_name']);
							}						
							# Check for created by/Pilotgroup id.
							if(isset($this->data['Product']['user_id']) && trim($this->data['Product']['user_id'])>0) 
							{
									$conditionsArr = array_merge($conditionsArr, array('Insight.user_id' => $this->data['Product']['user_id']));						
									# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
									if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_user_id"] == "yes")) || $this->data['Product']["basic_search_user_id"] == "yes" ) 
									{ 		
										$this->set('basic_search_user_id', "yes");
									}
									$this->set('value_user_id', $this->data['Product']['user_id']);
							}					
							                                                       
							# Check the created date with the date range requested from the search form
							if(trim($this->data['Product']['created_from']) != "") 
							{
									$startTimeArr = explode('-', $this->data['Product']['created_from']);
									$startTime = mktime(0,0,0,$startTimeArr[1],$startTimeArr[2],$startTimeArr[0]);											
									if(trim($this->data['Product']['created_to']) == "" ) 
									{
										$endTime = time();
									}
									else
									{
										$endTimeArr = explode('-', 	$this->data['Product']['created_to']);
										$endTime = mktime(23,59,59,$endTimeArr[1],$endTimeArr[2],$endTimeArr[0]);
									}
									$conditionsArr = array_merge($conditionsArr, array('UNIX_TIMESTAMP(Insight.date_submitted) BETWEEN ? AND ?' => array($startTime, $endTime)));	
									$this->set('created_from', $this->data['Product']['created_from']);
									$this->set('created_to', $this->data['Product']['created_to']);
							}			
							# Check for Content type id.
							if(isset($this->data['Product']['content_type_id']) && $this->data['Product']['content_type_id']>0) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.content_type_id' => $this->data['Product']['content_type_id']));				
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_content_type_id"] == "yes")) || $this->data['Product']["basic_search_content_type_id"] == "yes" ) 
								{ 		
									$this->set('basic_search_content_type_id', "yes");
								}
								$this->set('value_content_type_id', $this->data['Product']['content_type_id']);
							}		
							# Search for current status value.
							# Check where search from basic search page or search result page and value is set for the insight status
							if(isset($this->data['Product']['insight_status']) && !is_array($this->data['Product']['insight_status']) && trim($this->data['Product']['insight_status']) != '' ) 
							{
									$this->data['Product']['insight_status'] = explode(',', $this->data['Product']['insight_status']);	
							}
							if( isset($this->data['Product']['insight_status']) && is_array($this->data['Product']['insight_status']) ) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.insight_status' => $this->data['Product']['insight_status']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_insight_status"] == "yes")) || $this->data['Product']["basic_search_insight_status"] == "yes" ) 
								{ 		
										$this->set('basic_search_insight_status', "yes");
								}					
								$this->set('value_insight_status', $this->data['Product']['insight_status']);
							}
							
							# Search for Insight created by mobile
							if( isset($this->data['Product']['flag_mobile']) && ($this->data['Product']['flag_mobile'] == 'Y' || $this->data['Product']['flag_mobile'] == 'N') ) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.flag_mobile' => $this->data['Product']['flag_mobile']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_flag_mobile"] == "yes")) || $this->data['Product']["basic_search_flag_mobile"] == "yes" ) 
								{ 		
										$this->set('basic_search_insight_status', "yes");
								}					
								$this->set('value_flag_mobile', $this->data['Product']['flag_mobile']);
							}
							# Search for Current owner value.
							if(isset($this->data['Product']['deligated_to']) && $this->data['Product']['deligated_to']>0) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.deligated_to' => $this->data['Product']['deligated_to']));				
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_deligated_to"] == "yes")) || $this->data['Product']["basic_search_deligated_to"] == "yes" ) 
								{ 		
									$this->set('basic_search_deligated_to', "yes");
								}
								$this->set('value_deligated_to', $this->data['Product']['deligated_to']);
							}						
							# verify competitor id and save id else save name.
							if(isset($this->data['Competitorname']['who_competitor_name']) && trim($this->data['Competitorname']['who_competitor_name']) != '' && $this->data['Competitorname']['who_competitor_name'] != "Competitor")
							{
								# Verify if a competitor with this parent_id exists in the database.
								$competitorID = $this->processCompetitorExistance($this->data['Competitorname']['who_competitor_name']);						
								# Set Firm name Field Text value from filled autosearch field.
								if(isset($competitorID) && $competitorID > 0)
									$conditionsArr = array_merge($conditionsArr, array('Insight.competitor_id' => $competitorID));
								else
									$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_competitor_name) LIKE' => '%' . strtolower($this->data['Competitorname']['who_competitor_name']) . '%'));				
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_competitor_name"] == "yes")) || $this->data['Product']["basic_search_who_competitor_name"] == "yes" ) 
								{ 		
									$this->set('basic_search_who_competitor_name', "yes");
								}
								$this->set('value_who_competitor_name', $this->data['Competitorname']['who_competitor_name']);
							}						
							# verify selling_obstacles id
							if(isset($this->data['Product']['selling_obstacle_id']) && trim($this->data['Product']['selling_obstacle_id']) > 0)
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.selling_obstacle_id' => $this->data['Product']['selling_obstacle_id']));						
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_selling_obstacle_id"] == "yes")) || $this->data['Product']["basic_search_selling_obstacle_id"] == "yes" ) 
								{ 		
									$this->set('basic_search_selling_obstacle_id', "yes");
								}
								$this->set('value_selling_obstacle_id', $this->data['Product']['selling_obstacle_id']);
							}
							
							
							# verify issue id and save id else save name.
							if(isset($this->data['Issue']['issue_title']) && trim($this->data['Issue']['issue_title']) != '' && trim($this->data['Issue']['issue_title']) != '' && $this->data['Issue']['issue_title'] != "Issue")
							{
								# Verify if a issue exists in the database.
								$issueID = $this->processIssueExistance($this->data['Issue']['issue_title']);						
								# Set Firm name Field Text value from filled autosearch field.
								
								$conditionsArr = array_merge($conditionsArr, array('Insight.issue_field' => $issueID));

								
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_issue_field"] == "yes")) || $this->data['Product']["basic_search_issue_field"] == "yes" ) 
								{ 		
										$this->set('basic_search_issue_field', "yes");
								}
								$this->set('value_issue_field', $this->data['Issue']['issue_title']);
							}
													
										
							# Check / Conditon for market.
							if(isset($this->data['Product']['market_id']) && $this->data['Product']['market_id']>0) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.market_id' => $this->data['Product']['market_id']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_market_id"] == "yes")) || $this->data['Product']["basic_search_market_id"] == "yes" ) 
								{ 		
										$this->set('basic_search_market_id', "yes");
								}
								$this->set('value_market_id', $this->data['Product']['market_id']);
							}
							# Check for practice_area_id.
							if(isset($this->data['Product']['practice_area_id']) && $this->data['Product']['practice_area_id']>0) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.practice_area_id' => $this->data['Product']['practice_area_id']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_practice_area_id"] == "yes")) || $this->data['Product']["basic_search_practice_area_id"] == "yes" ) 
								{ 		
											$this->set('basic_search_practice_area_id', "yes");
								}
								$this->set('value_practice_area_id', $this->data['Product']['practice_area_id']);
							}
						}
						else
						{
							$conditionsArr = array('Insight.id' => $insightId);
							# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
							if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search"] == "yes")) || $this->data['Product']["basic_search"] == "yes" ) 
							{ 		
								$this->set('basic_search', "yes");
							}
						}
                                               
                                             /* @sukhvir
                                                 * Check search value perform by default insight
                                                 */
                                                $flag_logtype  = "I";
                                               
                                                # Check flag for user log on from insight
                                              if($flag_logtype){
                                                 $conditionsArr = array_merge($conditionsArr, array('Insight.flag_logtype' =>$flag_logtype));
                                               }
                                                
						# Write session value
						$this->Session->write('conditionsArr', serialize($conditionsArr));
						$this->Session->write('exportType', 'product');
						# Set sorting and ordering value in POST data
						$_POST['data']['Product']['sort_type'] = $this->data['Product']['sort_type'];
						$_POST['data']['Product']['ordering'] = $this->data['Product']['ordering'];
						$_POST['data']['Product']= $this->data['Product'];
						# Maintain session of posted values
						$this->Session->write('postdata', $_POST);					
				}
				# Read the session post data
				$postval = $this->Session->read('postdata');
				# In case searched other type from other tab.
				if($this->Session->read('exportType') != 'product')
				{
					$this->redirect(SITE_URL.'/products/search');	
				}
				# Looking for search condition.
				if($this->Session->read('conditionsArr') != "")
				{	
					# Set pagination array and condition for total count of records
					
					/*$this->paginate = array(
							'conditions' => unserialize($this->Session->read('conditionsArr')),		
							'limit' => 1000
					);
					
					# Retrieving results form paginator.
					$totalresult = $this->paginate('Insight');					
					*/

					$totalresult = $this->Insight->find('all', array('conditions' => unserialize($this->Session->read('conditionsArr'))));

					# Fetching Sorting criteria and based on the submitted field type apply sorting by that field
					if(isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != '-') 
					{
						$sort_option = $this->data['Product']['sort_type'];
							switch($sort_option){
								case "what_insight_type":
										$sort_option = "LOWER(Insight.what_insight_type)";	
										break;
								case "user_id":
										$sort_option = "LOWER(Pilotgroup.name)";	
										break;
								case "deligated_to":
										$sort_option = "LOWER(Pilotgroup_D.name)";	
										break;
								case "what_firm_name,what_firm_name_text":						
										$sort_option = "CONCAT_WS('',Firm.firm_name, Insight.what_firm_name_text)";
										break;
								case "product_family_id,who_product_family_name_text":								
										$sort_option = "`Productfamilyname`.`family_name`";
										break;
									case "product_id,who_product_name_text":								
										$sort_option = "CONCAT_WS('',Productname.`product_name`, Insight.who_product_name_text)";
										break;
									case "content_type_id":
										$sort_option = "Contenttype.content_type";
										break;
									case "practice_area_id":
										$sort_option = "Practicearea.practice_area";
										break;
									case "competitor_id,who_competitor_name_text":								
										$sort_option = "CONCAT_WS('',Competitorname.competitor_name, Insight.who_competitor_name_text)";
										break;
									case "selling_obstacle_id":
										$sort_option = "Sellingobstacle.selling_obstacles";
										break;
									case "insight_status":
										$sort_option = "Statusinsight.status";
										break;
									case "issue_field":
										$sort_option = "Issue.issue_title";
										break;	
									/*case "product_area_id":
										$sort_option = "Productarea.product_area";
										break;*/
									case "market_id":
										$sort_option = "Market.market";
										break;
								default:
										$sort_option = 'Insight.'.$this->data['Product']['sort_type'];
										break;
							}						
					} 
					else
						$sort_option = 'Insight.'."date_submitted";

					if(isset($this->data['Product']['ordering']) && $this->data['Product']['ordering'] != '') 
					{
						$ordering = " ".$this->data['Product']['ordering'];
					}
					else
						$ordering = " desc";
					# Set ordering
					$this->set('ordering', $ordering);
					
                                        # Set pagination and condition values for database
					$this->paginate = array(
							'conditions' => unserialize($this->Session->read('conditionsArr')),
							'order' => $sort_option . $ordering,
							//'group' => 'Insight.id',
							'limit' => RECORD_PER_PAGE
					);	
					# Retrieving results from paginator.
					$result = $this->paginate('Insight');
					$this->Insight->commit(); // Persist the data
				}			
		}
		catch(exception $ex) 
		{
			# Write error in log if there is problem in saving purchase xml in database.
			$this->log("Error Occur at the time of fetching records " . $this->data, LOG_DEBUG);
		}
		# Processing result. If data is submitted than store form values in $final_result
	
		if(!empty($result))
		{
			$i = 0;
			foreach($result as $row)
			{
				# Retreiving Insight
				$final_result[$i] = $row['Insight'];
				# Set user_id based on Pilotgroup id details
				$final_result[$i]['userSubmittedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['user_id']);
				# If what_how_come is 0, then set value to null not 0
				if($final_result[$i]['what_how_come'] == "0")
					$final_result[$i]['what_how_come'] = "";										
				# Composing firm name on basis of Id if any else direct name from db field.
				if(isset($row['Insight']['what_firm_name']) && trim($row['Insight']['what_firm_name']) != "") 
				{
					$final_result[$i]['firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
				}
				else {
					$final_result[$i]['firmName'] = $final_result[$i]['what_firm_name_text'];
				}				
				# Composing Content type details by id.
				if(isset($row['Insight']['content_type_id']) && trim($row['Insight']['content_type_id']) != "") 
				{
					$prod_contenttype_arr = array();
					$prod_contenttype_arr = $this->Contenttype->getContentTypeById($row['Insight']['content_type_id']);
					$final_result[$i]['relates_contentType'] = $prod_contenttype_arr['Contenttype']['content_type'];
				}
				else 
					$final_result[$i]['relates_contentType'] ="";
				# Composing product family name details by id
				if(isset($row['Insight']['product_family_id']) && trim($row['Insight']['product_family_id'])>0) 
				{
					$prod_familiy_name_arr = array();
					$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['product_family_id']);
					$final_result[$i]['who_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
				}
				else
				{
					$final_result[$i]['who_product_familyName'] = '';
				}
				# Composing product name details by id
				if(isset($row['Insight']['product_id']) && trim($row['Insight']['product_id'])>0) 
				{
					//$prod_name_arr = array();
					$prod_name = $this->Productname->getProductInfoByID($row['Insight']['product_id']);
					$final_result[$i]['who_productName'] = $prod_name;
				}
				else
				{
					$final_result[$i]['who_productName'] = $row['Insight']['who_product_name_text'];
				}						
				# Retreiving practice area details by id
				if(isset($row['Insight']['practice_area_id']) && trim($row['Insight']['practice_area_id']) != "") 
				{
					$final_result[$i]['practice_area_id'] = $this->Practicearea->getPracticeareaNameById($row['Insight']['practice_area_id']);
				}
				# Verify if a competitorInfo exists in the database. If yes get competitor_id else get who_competitor_name_text
				$competitorInfo = $this->Competitorname->getCompetitorName($row['Insight']['competitor_id']);
				if($competitorInfo > 0)
				{
					$final_result[$i]['competitor_id'] = $competitorInfo['Competitorname']['competitor_name'];
				}
				else
				{
					$final_result[$i]['competitor_id'] = $row['Insight']['who_competitor_name_text'];
				}
				
				# Set Contenttype Controller.
				App::import('Model', 'Replyresponse');
				# Create Contenttype model object.
				$this->Replyresponse = new Replyresponse();
				# Fetch recently added Response for Insight
				$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($row['Insight']['id']);
				
				if(count($RecentResponseInfo) > 0)
				{
					//$final_result[$i]['do_action'] = $RecentResponseInfo['Replyresponse']['reply_text'];
					$final_result[$i]['recent_reply'] = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
				}
				else
				{
					$final_result[$i]['recent_reply'] = "";
				}

				# Composing Issue Details
				if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) > 0) 
				{
					$final_result[$i]['issue_title'] = $row['Issue']['issue_title'] ;
				}
				/*else if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) == -1) 
				{
					$final_result[$i]['issue_title'] = "Not linked to an Issue" ;
				}*/
				else
				{
					$final_result[$i]['issue_title'] =  '';
				}

				
				# Retreiving Selling Obstacles.
				$selling_obstacleInfo = $this->Sellingobstacle->getSellingobstacleNameById($row['Insight']['selling_obstacle_id']);
				$final_result[$i]['selling_obstacle_id'] = $selling_obstacleInfo;				
				
				# Retreiving Market
				$marketInfo = $this->Market->getMarketById($row['Insight']['market_id']);
				$final_result[$i]['market_id'] = $marketInfo;	
				$i++;
			}
		}
		# Setting current url to come from edit mode.
		$this->Cookie->write('backUrl', $this->here);
		
		
		# Finally set the value of the results and total count for the view page
		if(!empty($final_result)) 
		{
			$this->set('final_result', $final_result);
			$this->set('total_count', count($totalresult));
		}	
    } # End function
	
	/**
		* @author Pragya Dave
		* @created on 10/02/2011
		* This function checks weather insight exists or not. It takes insight id as the input and using function findById() it checks if that id exists or not. If not exists then redirects to the site main page. It is called during edit insight page.
		* @param Insight id $id - check if insightid does not exists in the system then redirect it to home page.
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
	} # End function    
	
	/**
	 * @author Pragya Dave
	 * @created on 10/02/2011
	 * This function Compose firm by name and parent id. It takes firm id as input and using function findByParentId() it returns the firm name with parent_id string.
	 * The function is called in edit insight and export to excel functions.
 	 * @param Firm id $id - check in the database for firm name by parent Id.
	 * return string
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
	} # End function	
	
	/**
	 * @author Mohit Khurana
	 * @created on 01/09/2010 
	 * This function Export data to excel as per the search request. This function is called on search reults page to export the records based on the search criteria. 
	 * @param $type (by default product) - type defines which excel sheet should be prepared.
	 */
	function exportToExcel($type = '')
	{
		$this->layout="empty";
		if(!isset($type) || trim($type) == "") 
		{
			$type =	$this->Session->read('exportType');
		}
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
		# Set Productname Controller.
		App::import('Model', 'Productname');
		# Create Productname model object.
		$this->Productname = new Productname();		
		# Set Contenttype Controller.
		App::import('Model', 'Contenttype');
		# Create Contenttype model object.
		$this->Contenttype = new Contenttype();
		# Set model competitorname.
		App::import('Model', 'Competitorname');
		$this->Competitorname = new Competitorname(); // Object
		# Set model Market.
		App::import('Model', 'Market');
		$this->Market = new Market(); // Object
		# Import Statusinsight model
		App::import('Model', 'Statusinsight');
		# Create Statusinsight model object
		$this->Statusinsight = new Statusinsight();
		# Import Sellingobstacle model
                App::import('Model', 'Sellingobstacle');
                # Create Sellingobstacle model object
		$this->Sellingobstacle = new Sellingobstacle();		
                # Import Insighttype model
		App::import('Model', 'Insighttype');
		$this->Insighttype = new Insighttype();
		$arrinsighttype = $this->Insighttype->getinsightTypesValues();	
		
		# Find data based on the search criteria
		$result = $this->Insight->find('all', array('conditions' => unserialize($this->Session->read('conditionsArr')), 'order' => 'Insight.date_submitted DESC'));
		# Set loop variable
		$i = 0;
		# Processing result.
		if(!empty($result))
		{
			$i = 0;
			foreach($result as $row)
			{
                                # Set all field values in an array to form search records
				$final_result[$i] = $row['Insight'];
				#$final_result[$i]['what_insight_type'] = $row['Insight']['what_insight_type'];
				$final_result[$i]['what_insight_type'] = $arrinsighttype[$row['Insight']['what_insight_type']];
				$final_result[$i]['userSubmittedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['user_id']);
				$final_result[$i]['userUpdatedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['updated_by']);				
				$final_result[$i]['currentStatus'] = $this->Statusinsight->getStatusById($row['Insight']['insight_status']);				
				$final_result[$i]['delegatedTo'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['deligated_to']);							
				if(trim($final_result[$i]['what_how_come']) == "0")
				{
					$final_result[$i]['what_how_come'] = "";	
				}
				# Composing firm name on basis of Id if any else direct name from db field.
				if(isset($row['Insight']['what_firm_name']) && trim($row['Insight']['what_firm_name']) != "") 
				{
					$final_result[$i]['what_firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
				}
				else 
				{
					$final_result[$i]['what_firmName'] = $final_result[$i]['what_firm_name_text'];
				}
				# Composing whose firm name on basis of Id if any else direct name from db field.
				if($type == 'customer') 
				{
					if(isset($row['Insight']['who_firm_name']) && trim($row['Insight']['who_firm_name']) != "") 
					{
						$final_result[$i]['who_firmName'] = $this->processFirmId($row['Insight']['who_firm_name']);
					}
					else 
					{
						$final_result[$i]['who_firmName'] = $final_result[$i]['who_firm_name_text'];
					}
					if(isset($row['Insight']['relates_competitor_id']) && $row['Insight']['relates_competitor_id']>0) 
					{
						$compNameArr = $this->Competitorname->getCompetitorName($row['Insight']['relates_competitor_id']);
						$final_result[$i]['relates_competitor_id'] = $compNameArr['Competitorname']['competitor_name'];
					}
				}				
				#in case of type product (product family name, product name & content type).
				if($type == 'product') 
				{
					if(isset($row['Insight']['product_family_id']) && trim($row['Insight']['product_family_id']) > 0) 
					{
						$prod_familiy_name_arr = array();
						$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['product_family_id']);
						$final_result[$i]['who_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
					}
					else 
					{
						$final_result[$i]['who_product_familyName'] = '';
					}	
					# Check for Id if not then pick from text field.
					if(isset($row['Insight']['product_id']) && $row['Insight']['product_id']>0) 
					{
						$final_result[$i]['who_productName'] = $this->Productname->getProductNameByProdId($row['Insight']['product_id']);
					}
					else 
					{
						$final_result[$i]['who_productName'] = $row['Insight']['who_product_name_text'];
					}
                                        # no need of conent type 
//					$relates_content_arr = array();
//					$relates_content_arr = $this->Contenttype->getContentTypeById($row['Insight']['content_type_id']);
//					$final_result[$i]['relates_contentType'] = $relates_content_arr['Contenttype']['content_type'];
				}
				# Fetch all values i.e. Competitorname, Product Family name	, Market id, Practice area, Selling obstacles, Product area, Contact name / Publication, Contact Name / role
				$competitorNameArr = $this->Competitorname->getCompetitorName($final_result[$i]['competitor_id']);
				$final_result[$i]['who_competitorName'] = $competitorNameArr['Competitorname']['competitor_name'];
				# Composing product family name.
				if(isset($row['Insight']['relates_product_family_id']) && trim($row['Insight']['relates_product_family_id'])>0) 
				{
					$prod_familiy_name_arr = array();
					$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['relates_product_family_id']);
					$final_result[$i]['relates_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
				}
				else
				{
					$final_result[$i]['relates_product_familyName'] = "";
				}			
				# Set Market id if not null or 0				
				if(isset($row['Insight']['market_id']) && $row['Insight']['market_id']>0) 
				{
					$final_result[$i]['who_marketName'] = $this->Market->getMarketById($row['Insight']['market_id']);
				}
				else
				{
					$final_result[$i]['who_marketName'] = "";
				}	
				# Set Practice area id if not null or 0
				if(isset($row['Insight']['practice_area_id']) && $row['Insight']['practice_area_id']>0) 
				{
					$practiceArea = $this->Practicearea->getPracticeareaNameById($row['Insight']['practice_area_id']);
					$final_result[$i]['practice_area_id'] = $practiceArea;
				}
				else 
				{
					$final_result[$i]['practice_area_id'] = "";
				}
				# Set Selling obstacles id if not null or 0
				if(isset($row['Insight']['selling_obstacle_id']) && $row['Insight']['selling_obstacle_id']>0) 
				{
					$selling_obstacleInfo = $this->Sellingobstacle->getSellingobstacleNameById($row['Insight']['selling_obstacle_id']);
					$final_result[$i]['selling_obstacle_id'] = $selling_obstacleInfo;
				}
				else 
				{
					$final_result[$i]['selling_obstacle_id'] = "";
				}
				
				/**		Latest Reply for Insight in excel start		**/
				# Set Contenttype Controller.
				App::import('Model', 'Replyresponse');
				# Create Contenttype model object.
				$this->Replyresponse = new Replyresponse();
				# Fetch recently added Response for Insight
				$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($row['Insight']['id']);
				
				if(count($RecentResponseInfo) > 0)
				{
					//$final_result[$i]['do_action'] = $RecentResponseInfo['Replyresponse']['reply_text'];
					$final_result[$i]['recent_reply'] = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
				}
				else
				{
					$final_result[$i]['recent_reply'] = "";
				}
				/**		Latest Reply for Insight in excel end 	**/
				
				/**		Issue for Insight in excel start		**/
				# Composing Issue Details
				if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) > 0) 
				{
					$final_result[$i]['issue_title'] = $row['Issue']['issue_title'] ;
				}
				/*else if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) == -1) 
				{
					$final_result[$i]['issue_title'] = "Not linked to an Issue" ;
				}*/
				else
				{
					$final_result[$i]['issue_title'] =  '';
				}
				/**		Issue for Insight in excel end		**/
				
				# Set who_contact_name to the array index
				$final_result[$i]['who_contact_name'] = $row['Insight']['who_contact_name'];
				# Set who_contact_role to the array index
				$final_result[$i]['who_contact_role'] = $row['Insight']['who_contact_role'];
				                              
                               $i++;
			}
		}
                
                $i=0;
		#Set excel file header names
		if($type == 'product') 
		{
                        $arrExcelInfo[0] = array(                                   'Id',				
                                                                                    'Created On',
                                                                                    'Created By',
                                                                                    'Date Status Changed',
                                                                                    'Current Status',
                                                                                    'Current owner',										
                                                                                    'Insight Type',		
                                                                                    'Origin of Feedback',									
                                                                                    //'Feedback Come About',									
                                                                                    'Organisation Name',
                                                                                    'Role',
                                                                                    'Contact Name',
                                                                                    'LexisNexis Product Family Name',
                                                                                    'LexisNexis Product Name',
                                                                                    //'Content Type',
                                                                                    'Practice Area',
                                                                                    'Competitor',
                                                                                    'Selling Obstacles',
                                                                                    //'Product Area',	
                                                                                    'Market Segment',																				
                                                                                    'Response to Feedback',
                                                                                    'Issue',
                                                                                    'Added via Mobile',
                                                                                    'What did the customer/prospect say?',
                                                                                    'How does this impact their activities/business?',
                                                                                    'Required actions /Suggested Next Steps'
                                                                                    );
                         
                   
		} 
		else if($type == 'competitor') 
		{
                                  $arrExcelInfo[0] = array(                    'Id',
										'Created On',
										'Created By',
										'Date Status Changed',
										'Current Status',
										'Current owner',
										'Feedback Come About',
										'Source of Feedback',
										'Firm Name',
										'Competitor Name',
										'LexisNexis Product Family Name',
										'Practice Area',
										'Response to Feedback',
										'Issue',
										'Added via Mobile',
										'What did the customer/prospect say?',
										'How does this impact their activities/business?',
										'Suggested Next Steps'
										);	
		} 
		else if($type == 'customer') 
		{
			
                        $arrExcelInfo[0] = array(	'Id',
										'Created On',
										'Created By',
										'Date Status Changed',
										'Current Status',
										'Current owner',
										'Feedback Come About',
										'Source of Feedback',
										'Firm Name (What)',
										'Firm Name (Who)',										
										'Account Number',
										'Contact Name / Publication',
										'Contact Name / Role',
										'Competitor Name',										
										'LexisNexis Product Family Name',
										'Practice Area',
										'Response to Feedback',
										'Issue',
										'Added via Mobile',
										'What did the customer/prospect say?',
										'How does this impact their activities/business?',
										'Suggested Next Steps'
										);		
                        
		} 
		else if($type == 'market') 
		{
                             
                                 $arrExcelInfo[0] = array(	'Id',
										'Created On',
										'Created By',
										'Date Status Changed',
										'Current Status',
										'Current owner',
										'Feedback Come About',
										'Source of Feedback',
										'Firm Name',
										'Market',
										'LexisNexis Product Family Name',
										'Practice Area',
										'Response to Feedback',
										'Issue',
										'Added via Mobile',
										'What did the customer/prospect say?',
										'How does this impact their activities/business?',
										'Suggested Next Steps'
										);	
                }	
		$arrExcelInfo[1] = array();		
		App::import('Vendor', 'excelVendor', array('file' =>'excel'.DS.'class.export_excel.php'));		
		$fileName = $type."_export.xls";
		#create the instance of the exportexcel format
		$excel_obj = new ExportExcel("$fileName");
              foreach ($final_result as $keySet => $valueSet)
		{
				$arrExcelInfo[1][$i] = $this->add_export_array($valueSet, $type);
                               
		
				$i++;
		}
 		#setting the values of the headers and data of the excel file 
		#and these values comes from the other file which file shows the data
		$excel_obj->setHeadersAndValues($arrExcelInfo[0],$arrExcelInfo[1]); 		
		#now generate the excel file with the data and headers set
		$excel_obj->GenerateExcelFile();
	} # End function	
	
	/* 
		* @author Mohit Khurana
		* @created on 01/09/2010 
		* This function is Assigning related array to its type (Mapping fields for excel sheet). It sets the rows renders for each type into an array.
		* @param $valueSet - array of values
		* @param $type - type of data to be exported.
		* @return array of fields data
	*/
	function add_export_array($valueSet, $type) 
	{
		# In case blank date.
		$valueSet['date_updated'] = (trim($valueSet['date_updated']) != "")?date('dS M, Y', strtotime($valueSet['date_updated'])):"";
		# Mapping common fields.
                $arr[0] = $valueSet['id'];
		$arr[1] = date('dS M, Y', strtotime($valueSet['date_submitted']));
		$arr[2] = $valueSet['userSubmittedName'];
		$arr[3] = $valueSet['date_updated'];
		$arr[4] = $valueSet['currentStatus'];
		$arr[5] = $valueSet['delegatedTo'];
                $arr[6] = ucwords(strtolower($valueSet['what_insight_type']));		
		$arr[7] = $valueSet['what_how_come'];			
		$arr[8] = ucwords(strtolower($valueSet['what_firmName']));
		# Mapping related fields
		if($type == 'product') 
		{
                            $arr[9] = $valueSet['who_contact_role'];
                            $arr[10] = $valueSet['who_contact_name'];
                            $arr[11] = ucwords(strtolower($valueSet['who_product_familyName']));
                            $arr[12] = ucwords(strtolower($valueSet['who_productName']));
                            //$arr[13] = $valueSet['relates_contentType'];		
                            $arr[13] = $valueSet['practice_area_id'];
                            $arr[14] = ucwords(strtolower($valueSet['who_competitorName']));
                            $arr[15] = $valueSet['selling_obstacle_id'];
                            //$arr[17] = $valueSet['product_area_id'];
                            $arr[16] = $valueSet['who_marketName'];

                            //$arr[20] = $valueSet['do_action'];		
                            $arr[17] = $valueSet['recent_reply'];		
                            $arr[18] = $valueSet['issue_title'];		
                            $arr[19] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
                            $arr[20] = $valueSet['insight_summary'];
                            $arr[21] = $valueSet['customer_pain_points'];		
                            $arr[22] = $valueSet['recommended_actions'];
                }
		else if($type == 'competitor')
		{       
                      
			$arr[9] = $valueSet['who_competitorName'];
						
			$arr[10] = $valueSet['relates_product_familyName'];
			$arr[11] = $valueSet['practice_area_id'];
			//$arr[13] = $valueSet['do_action'];
			$arr[12] = $valueSet['recent_reply'];
			$arr[13] = $valueSet['issue_title'];	
			$arr[14] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
			$arr[15] = $valueSet['insight_summary'];
			$arr[16] = $valueSet['customer_pain_points'];		
			$arr[17] = $valueSet['recommended_actions'];
                      
		}
		else if($type == 'customer') 
		{   
                         
			$arr[9] = $valueSet['who_firmName'];
			$arr[10] = $valueSet['who_account_no'];
			$arr[11] = $valueSet['who_contact_name'];
			$arr[12] = $valueSet['who_contact_role'];
///			$arr[13] = $valueSet['insight_summary'];			
			$arr[13] = $valueSet['relates_competitor_id'];
			$arr[14] = $valueSet['relates_product_familyName'];
			$arr[15] = $valueSet['practice_area_id'];				
			//$arr[17] = $valueSet['do_action'];
			$arr[16] = $valueSet['recent_reply'];
			$arr[17] = $valueSet['issue_title'];
			$arr[18] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
			$arr[19] = $valueSet['insight_summary'];
			$arr[20] = $valueSet['customer_pain_points'];		
			$arr[21] = $valueSet['recommended_actions'];		
                       
		}
		else if($type == 'market') 
		{ 
                             $arr[9] = $valueSet['who_marketName'];
		//	$arr[10] = $valueSet['insight_summary'];			
			$arr[10] = $valueSet['relates_product_familyName'];
			$arr[11] = $valueSet['practice_area_id'];				
			//$arr[13] = $valueSet['do_action'];
			$arr[12] = $valueSet['recent_reply'];
			$arr[13] = $valueSet['issue_title'];
			$arr[14] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
			$arr[15] = $valueSet['insight_summary'];
			$arr[16] = $valueSet['customer_pain_points'];		
			$arr[17] = $valueSet['recommended_actions'];
                       
		}			
		return $arr;
	} # End function
	
	/* 
		* @author Mohit Khurana
		* @created on 01/09/2010 
		* This function is used to remove attachment file from the provided location and update the database field to Null. The function is executed when editing insight.
		* @param $id Insight id - id of insight against which the attachment to be removed.
		* @param $filename Name of the file - name of the file which is to be removed.
	*/
	function remove_attachment($id = NULL, $filename = NULL) 
	{
		if($filename != "" || $id = "")
		{
			$file = base64_decode($filename);
			# Set Insight Controller.
			App::import('Model', 'Insight');
			# Create Insight model object.
			$this->Insight = new Insight();
			$this->Insight->updateAll(array('Insight.attachment_name' => NULL),array('Insight.id'=>$id));
			$this->Insight->updateAll(array('Insight.attachment_real_name' => NULL),array('Insight.id'=>$id));						
			$msg = TRUE;
			if(file_exists(PRODUCT_ATTACHMENT_UPLOAD_PATH."/".$file)) 
			{
				if(unlink(PRODUCT_ATTACHMENT_UPLOAD_PATH."/".$file))
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
	} # End function	
	
	/**
	 * @author Pragya Dave
	 * @created on 10/02/2011
     * This function validates and returns entered competitor information. It checks the competitor name in the table competitorname using the function getCompetitorId() declared in model and returns competitor id if exists else returns 0.
	 * The function is executed when Adding , editing insight.
     * @param String $competitorName - check for competitor Name if it exists in database. If record exists then return his id else return 0.
     * @return Competitor ID
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
    } # End function	
	
	
	/**
	 * @author Gaurav Saini
	 * @created on 22/07/2011
     * This function validates and returns entered Issue information. It checks the issue title in the table issues using the function getIssueId() declared in model and returns issue id if exists else returns 0.
	 * The function is executed when searching insight.
     * @param String $IssueTitle  - check for Issue if it exists in database. If record exists then return his id else return 0.
     * @return Issue ID
     */
    function processIssueExistance($IssueTitle = '')
    {
    	# Import Issue model
    	App::import('Model', 'Issue');
    	# Create Issue model object
		$this->Issue = new Issue();		
		# Get array for Issue id.
    	$arrIssueId = $this->Issue->getIssueId($IssueTitle);
    	
    	if(isset($arrIssueId) && is_array($arrIssueId) && count($arrIssueId) > 0)
    		return $arrIssueId['Issue']['id'];
    	else
    		return 0;
    } # End function	
		
	
	/**
	 * @author Pragya Dave
	 * @created on 10/02/2011
     * This function downloads the file as an attachment from the specified file location. It downloads the file using headers from the location ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/ and reads the file from the location.
	 * It also check the mine type of the file to be download. The function is called when user click on download attachment on Edit Insight page and Search Result page.
     * @param String $filename - check if the specified file exists on the server to download. If the file exists then user can download the file and if the file doesnot exits then a message is displayed that File not found.
     * @return file
     */
	function downloadfile($filename1)
	{
		$filename = ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/'.$filename1;
		if(file_exists($filename)) 
		{
			$content_type = $this->mime_content_typedata($filename1);			
			header('Content-Description: File Transfer');		
			header("Content-type: ".$content_type); 
			header("Content-Disposition: attachment; filename=".basename($filename)); 
			header('Content-Transfer-Encoding: binary');		
			header('Pragma: public');
			header("Expires: 0"); 
			ob_clean();
			flush();
			readfile($filename);
			die;	
		}else
		{
			echo "File not found";
			die;
		}
	} # End function
	
	/**
	 * @author Pragya Dave
	 * @created on 10/02/2011
     * This function return mine content types of a file. It takes the name of the file and checks the extension (extracts the . separator) and create mine type for that file based on the array supplied.
	 * The function is called when user click on download attachment on Edit Insight page and Search Result page.
     * @param String $filename
     * @return file
     */
    function mime_content_typedata($filename) 
	{
		# Set Mine types array values
        $mime_types = array(
			# text
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            # images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
			'ico' => 'image/x-icon',
            # archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            # audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
			'avi' => 'video/x-msvideo',
			'mp2' => 'video/mpeg',
			'mp3' => 'audio/mpeg',
			'mp4' => 'audio/mpeg',
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg',
			'mpp' => 'application/vnd.ms-project',
			'msg' =>'application/vnd.ms-outlook',
			'wav' => 'audio/x-wav',
            # adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            # ms office
            'doc' => 'application/msword',
			'docx' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
			'xlsx' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
		    'pptx' => 'application/vnd.ms-powerpoint',
            # open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			# ram
			'ram' => ' 	audio/x-pn-realaudio',
        );
        $ext = strtolower(array_pop(explode('.',$filename)));
		# Checks for mine type exists in array
        if (array_key_exists($ext, $mime_types)) 
		{
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) 
		{
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else 
		{
            return 'application/octet-stream';
        }
    } # End function  

	/**
	 * @author Gaurav Saini
	 * @created on 10/06/2011
     * This function send mail to contributor when Insight status change.
	 * @param Integer id (Insight_id) - Insight Id is passed for the information retrival
	 * Email Template 3 
     */
	function sendstatusmailtocontributor($id = '')
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value for edit.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
			$contributor_user_name	= $this->data['Pilotgroup']['name'];
			$contributor_first_name	= $this->data['Pilotgroup']['first_name'];
			$contributor_sur_name	= $this->data['Pilotgroup']['sur_name'];
			$sme_name	 		= $this->data['Pilotgroup_D']['name'];
			$sme_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$sme_cc_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}		
			
			
			# Set Contenttype Controller.
			App::import('Model', 'Replyresponse');
			# Create Contenttype model object.
			$this->Replyresponse = new Replyresponse();
			# Fetch recently added Response for Insight
			$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($id);
			//echo "<pre>";print_r($RecentResponseInfo);die;
			if(count($RecentResponseInfo) > 0)
			{
				$recent_reply = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
			}
			else
			{
				$recent_reply = "";
			}
			
			$contributor_user_name = ($contributor_first_name!='')?trim($contributor_first_name.' '.$contributor_sur_name):$contributor_user_name;
			
			$this->set('contributor_name',$contributor_user_name);
			$this->set('insight_summary',$insight_summary);
			$this->set('recent_reply',$recent_reply);
			$this->set('insight_url', SITE_URL . '/products/records/'.$id);
			
			$loggedIn_user_role = $this->Session->read('current_user_role');
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			$EmailTo = 	strtolower($this->data['Pilotgroup']['emailaddress']);
						
			$arrCc = array();
			if($sme_cc_emailaddress !='')
			{
				if(strstr($sme_cc_emailaddress, ';')){
					$pieces = explode(";", strtolower($sme_cc_emailaddress));
					$arrCc = $pieces;
				}
				else
				{
					$arrCc[] = strtolower($sme_cc_emailaddress);
				}
			}
			if($sme_emailaddress !=''){
				$arrCc[] = strtolower($sme_emailaddress);
			}
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Cc To field of the mail	
			$this->Email->cc = $arrCc;
			
			#Subject field of the mail
				$this->Email->subject = "Feedback Tracker notification: Feedback Ref ".$id." status changed (".$insight_status.")";
			
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			
			
			
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_3';	
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}
		
	}

	/**
	 * @author Gaurav Saini
	 * @created on 10/06/2011
     * This function send mail to contributor when any comment is added to Insight.
	 * @param Integer id (Insight_id)
	 * Email Template 4 
     */
	function sendcommentmailtosme($id = '', $comment_text = '', $commentPostedBy = '')
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value for edit.
			$this->set('id',$id);
			$this->set('comment_text',$comment_text);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>"; print_r($this->data);die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
			$sme_user_name	 	= $this->data['Pilotgroup_D']['name'];
			$sme_first_name		= $this->data['Pilotgroup_D']['first_name'];
			$sme_sur_name		= $this->data['Pilotgroup_D']['sur_name'];
			$sme_emailaddress 	="David.Coleman@lexisnexis.co.uk";
			$sme_cc_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$contributor_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$contributor_first_name 	= $this->data['Pilotgroup']['first_name'];
			$contributor_sur_name 		= $this->data['Pilotgroup']['sur_name'];
			$contributor_user_name 		= $this->data['Pilotgroup']['name'];
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/records/'.$id);
			
			$loggedIn_user_role = $this->Session->read('current_user_role');
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			$arrCc = array();
			if($commentPostedBy == '' || $commentPostedBy == 'A'){	// If comment posted by Contributor OR Moderator then send email to SME
				
				$current_user_id = 	$this->Session->read('current_user_id');
				if($current_user_id == $this->data['Insight']['user_id'])	
				{
					$contributor_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;
					$EmailTo = 	strtolower($sme_emailaddress);
					$CC_Email = strtolower($contributor_emailaddress);
				}
				else{
					$contributor_name = ($contributor_first_name!='')?trim($contributor_first_name.' '.$contributor_sur_name):$contributor_user_name;	
					$EmailTo = 	strtolower($contributor_emailaddress);
					$CC_Email = strtolower($sme_emailaddress);
				}
				$this->set('sme_name',$contributor_name);
				
				//$sme_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;	
				//$this->set('sme_name',$sme_name);
				//$EmailTo = 	strtolower($sme_emailaddress);
				
				
				/*
				*	Adding SME Cc email addresses to Cc field.
				*/
				if($sme_cc_emailaddress !='')
				{
					if(strstr($sme_cc_emailaddress, ';')){
						$pieces = explode(";", strtolower($sme_cc_emailaddress));
						$arrCc = $pieces;
					}
					else
					{
						$arrCc[] = strtolower($sme_cc_emailaddress);
					}
				}
				/*
				*	Adding Contributor email addresses to Cc field.
				*/
				if($contributor_emailaddress !=''){
					$arrCc[] = $CC_Email;
					//$arrCc[] = strtolower($contributor_emailaddress);
				}
			}
			else if($commentPostedBy == "S")  // If comment posted by SME then send email to Contributor
			{
				// If SME is treated as Contributor and he addes any comment then mail should address to SME.
				$current_user_id = 	$this->Session->read('current_user_id');
				if($current_user_id == $this->data['Insight']['user_id'])	
				{
					$contributor_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;
					$EmailTo = 	strtolower($sme_emailaddress);
					$CC_Email = strtolower($contributor_emailaddress);
				}
				else{
					$contributor_name = ($contributor_first_name!='')?trim($contributor_first_name.' '.$contributor_sur_name):$contributor_user_name;	
					$EmailTo = 	strtolower($contributor_emailaddress);
					$CC_Email = strtolower($sme_emailaddress);
				}
				$this->set('sme_name',$contributor_name);
				//$EmailTo = 	strtolower($contributor_emailaddress);				
				
				/*
				*	Adding SME Cc email addresses to Cc field.
				*/
				if($sme_cc_emailaddress !='')
				{
					if(strstr($sme_cc_emailaddress, ';')){
						$pieces = explode(";", strtolower($sme_cc_emailaddress));
						$arrCc = $pieces;
					}
					else
					{
						$arrCc[] = strtolower($sme_cc_emailaddress);
					}
				}
				/*
				*	Adding SME email address to Cc field.
				*/
				if($sme_emailaddress !=''){
					//$arrCc[] = strtolower($sme_emailaddress);
					$arrCc[] = $CC_Email;
				}				
			}
	 			
			#To field of the mail
				$this->Email->to = $EmailTo;
			#Subject field of the mail
				$this->Email->subject = "Feedback Tracker: Feedback Ref ".$id." (status: comment added)";
			#Cc To field of the mail
				$this->Email->cc = $arrCc;
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_4';
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}
		
	}
	
	
	/**
	 * @author Gaurav Saini
	 * @created on 10/06/2011
     * This function send mail to contributor when ownership of Insight is taken by any SME.
	 * @param Integer id (Insight_id)
     */
	function sendownershipmailtocontributor($id = '')
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
			$sme_name	 		= $this->data['Pilotgroup_D']['name'];
			$sme_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}		
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/records/'.$id);
			$this->set('ownership_taken_by',$sme_name);
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
			$EmailTo = 	"David.Coleman@lexisnexis.co.uk";
			
			#To field of the mail
				$this->Email->to = $EmailTo;
			#Subject field of the mail
				$this->Email->subject = "Ownership of Feedback has been taken by SME."; 
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'insight_ownership_mail';
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}		
	}
	
	
	/**
	 * @author Gaurav Saini
	 * @created on 10/06/2011
     * This function send mail to SME when moderator changes insight status from blank to delegated and Delegated To is not blank.
	 * @param Integer id (Insight_id)
	 * Template 1 & 2
     */
	function sendblankdelegeatedmailtosme($id = '', $showStatusInDelegationMail)
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
		
			$sme_user_name	 		= $this->data['Pilotgroup_D']['name'];
			$sme_first_name	 		= $this->data['Pilotgroup_D']['first_name'];
			$sme_sur_name	 		= $this->data['Pilotgroup_D']['sur_name'];
		//	$sme_emailaddress 	= $this->data['Pilotgroup_D']['emailaddress'];
		//	$sme_cc_emailaddress 	= $this->data['Pilotgroup_D']['cc_emailaddress'];
		//	$contributor_emailaddress 	= $this->data['Pilotgroup']['emailaddress'];
		
                        $sme_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$sme_cc_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$contributor_emailaddress= "David.Coleman@lexisnexis.co.uk";
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}	
			
			$sme_user_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;				
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/records/'.$id);
			$this->set('new_status',$insight_status);
			$this->set('delegated_to',$sme_user_name);
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			$EmailTo = 	$sme_emailaddress;
			
			$arrCc = array();			
			if($sme_cc_emailaddress !='')
			{
				if(strstr($sme_cc_emailaddress, ';')){
					$pieces = explode(";", strtolower($sme_cc_emailaddress));
					$arrCc = $pieces;
				}
				else
				{
					$arrCc[] = strtolower($sme_cc_emailaddress);
				}
			}
			$arrCc[] = strtolower($contributor_emailaddress);
		
			#To field of the mail
				$this->Email->to = $EmailTo;
			
			#Cc To field of the mail
			$this->Email->cc = $arrCc;
			$EmailSubject = "Feedback Tracker notification: Feedback Ref ".$id;
			if($showStatusInDelegationMail)
			{
				$EmailSubject = $EmailSubject . " (status: delegated)";
			}			
			
			#Subject field of the mail
				$this->Email->subject = $EmailSubject; 
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_1';
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}
		
	}	
	
	# expecting a comment
	function owner_success($modeltype = '')
	{
		# Include Layout
		$this->layout='front_pop';
	} //End of success action.
	
	/**
	 * @author Gaurav Saini
	 * @created on 03/06/2011
     * This function return the issue description for the combination of Product Family Name, Practice Area & Selling Obstacle.
	 * The function is called when user change Product Family Name, Practice Area & Selling Obstacle on Edit Insight page.
     * @param Integer $product_family
     * @param Integer $practice_area
     * @param Integer $selling_obstacle
     * @return issue details
     */
	function getissue($product_family='', $practice_area='', $selling_obstacle='')
	{
		#Import Issue model
		App::import('Model', 'Issue');
		# Create Content type model object.
		$this->Issue = new Issue();	
		
		$product_family 	= intval(trim($product_family));
		$practice_area 		= intval(trim($practice_area));
		$selling_obstacle 	= intval(trim($selling_obstacle));
		
		# Get issues for matching combination of Product Family Name, Practice Area and Selling Obstacles.
		$productIssueValue = $this->Issue->getIssuesForCombination($product_family, $practice_area, $selling_obstacle);
	//	echo "<pre>"; print_r($productIssueValue);die;
		if(count($productIssueValue) > 0)
		{
			foreach($productIssueValue as $key=>$value) 
			{
				$key = $value['Issue']['id'];
				$arr1['Most Relevant Issues'][$key] = $value['Issue']['issue_title'];
				$arrIssueExist[] = $key;
			}
		}
		if(count($arrIssueExist)> 0){
			$IssuesToExclude = implode(",", array_unique($arrIssueExist));
		}
		
		# Get issues for matching Product Family Name but exclude the previously fetched records.
		$arrProductFamily = $this->Issue->getIssuesForProductFamily($product_family, $IssuesToExclude);	
		if(count($arrProductFamily) > 0)
		{
			foreach($arrProductFamily as $key=>$value) 
			{
				$key = $value['Issue']['id'];
				$arr1['Same Product Family'][$key] = $value['Issue']['issue_title'];
				$arrIssueExist[] = $key;
			}
		}
		if(count($arrIssueExist)> 0){
			$IssuesToExclude = implode(",", array_unique($arrIssueExist));
		}
		
		if(count($arr1) > 0){ 
		$str = "";
		$str_key = "";
		
			foreach($arr1 as $key=>$value) 
			{	
				if($str_key != $key) {
					$str_key = $key;
					$str .= "!!".$str_key."$$";
					
				}
				if(count($value) > 0) {
					foreach($value as $k =>$v)
					{
						$str .= $k."@@".$v;
						$str .= "||";
					}
				}				
			}
			echo $str;
		}
		else
			echo "";

		die;
	}	
	
	/**
	 * @author Gaurav Saini
	 * @created on 07/06/2011
     * This function return the issue description for the specific issue_id.
	 * The function is called when user changes Issue.
     * @param Integer issue_id
     * @return issue description
     */
	function getissuedetail($issue_id='')
	{
		header("Cache-Control: no-cache, must-revalidate");
		#Import Issue model
		App::import('Model', 'Issue');
		# Create Content type model object.
		$this->Issue = new Issue();	
		$issue_rec = $this->Issue->find('all', array('conditions' => array(
															'id'=>intval(trim($issue_id))
															)));
		if(count($issue_rec) > 0){ 
			echo $issue_rec[0]['Issue']['issue_description'];
		}
		else
			echo "";
		die;
	}
	
	/**
	 * @author Gaurav Saini
	 * @created on 03/06/2011
     * This function saves the Issue status for the combination of Product Family Name, Practice Area & Selling Obstacle.
	 * The function is called when user change Issue dropdown.
     * @param Integer product_family_id
     * @param Integer practice_area_id
     * @param Integer selling_obstacle_id
     * @param Integer issue_id
     */
	function addissue($product_family_id='', $practice_area_id='', $selling_obstacle_id='', $issue_id='')
	{
		#Import Issue model
		App::import('Model', 'Issue');
		# Create Content type model object.
		$this->Issue = new Issue();	
		$this->layout='front_pop';
		$this->set('product_family_id',$product_family_id);
		$this->set('practice_area_id',$practice_area_id);
		$this->set('selling_obstacle_id',$selling_obstacle_id);
		$this->set('errDisplay','none');
		
		if(isset($this->data) && $this->data !='')
		{
			$arr_data = $this->data;	
			
			//echo "<pre>"; print_r($arr_data);die;

			$product_family_id 		= $arr_data['Issue']['product_family_id']; 
			$practice_area_id		= $arr_data['Issue']['practice_area_id'];
			$selling_obstacle_id	= $arr_data['Issue']['selling_obstacle_id'];
			$checkDuplicateIssue = $this->Issue->getIssuesByTitle($arr_data['Issue']['issue_title']);
			//echo "<pre>"; print_r($checkDuplicateIssue);die;
			if(count($checkDuplicateIssue) == 0){
			
			$aa = $this->Issue->save($arr_data);	
			$last_inserted_id = $this->Issue->id;
			$this->set('selectedIssue',$last_inserted_id);
			//$this->Session->write('recent_added_issue_id',$last_inserted_id);
			
			?>
			
			<script language="javascript" type="text/javascript">
					
					parent.parent.parent.document.getElementById("ProductIssueEditIcon").style.display = 'block';
					parent.parent.parent.document.getElementById("ProductIssueAddIcon").style.display = 'none';
		
					parent.parent.parent.document.getElementById("recent_added_issue_id").value = <?php echo $last_inserted_id;?>;

					parent.parent.parent.document.getElementById("HDNIssue").style.display = "block";
					parent.parent.parent.document.getElementById("HDNIssue").focus();
					parent.parent.parent.document.getElementById("HDNIssue").style.display = "none";
					parent.parent.GB_hide();
					
			</script>
			<?php	}
			else{
				$this->set('product_family_id', $arr_data['Issue']['product_family_id']);
				$this->set('practice_area_id', $arr_data['Issue']['practice_area_id']);
				$this->set('selling_obstacle_id', $arr_data['Issue']['selling_obstacle_id']);
				$this->set('issue_title', $arr_data['Issue']['issue_title']);
				$this->set('issue_status', $arr_data['Issue']['issue_description']);
				$this->set('errDisplay','block');
			}
			
		}
	}

	/**
	 * @author Gaurav Saini
	 * @created on 03/06/2011
     * This function saves the Issue status for the combination of Product Family Name, Practice Area & Selling Obstacle.
	 * The function is called when user change Issue dropdown.
     * @param Integer product_family_id
     * @param Integer practice_area_id
     * @param Integer selling_obstacle_id
     * @param Integer issue_id
     */
	function editissue($product_family_id='', $practice_area_id='', $selling_obstacle_id='', $issue_id='')
	{
		#Import Issue model
		App::import('Model', 'Issue');
		# Create Content type model object.
		$this->Issue = new Issue();	
		$this->layout='front_pop';
		$this->set('product_family_id', $product_family_id);
		$this->set('practice_area_id', $practice_area_id);
		$this->set('selling_obstacle_id', $selling_obstacle_id);
		$this->set('errDisplay','none');
		$issue_rec = $this->Issue->find('first', array('conditions' => array('id' => $issue_id)));
		
		$this->set('issue_rec', $issue_rec);
		if(isset($this->data) && $this->data !='')
		{
			$arr_data = $this->data;			
	
			$checkDuplicateIssue = $this->Issue->getIssuesByTitle_Id($arr_data['Issue']['issue_title'], $arr_data['Issue']['id']);
			//echo "<pre>"; print_r($checkDuplicateIssue);die;
			if(count($checkDuplicateIssue) == 0){
			$this->Issue->save($arr_data);	?>
			<script language="javascript" type="text/javascript">
							parent.parent.GB_hide();
			</script>
			<?php	}
				else{
					$this->set('product_family_id', $arr_data['Issue']['product_family_id']);
					$this->set('practice_area_id', $arr_data['Issue']['practice_area_id']);
					$this->set('selling_obstacle_id', $arr_data['Issue']['selling_obstacle_id']);
					$issue_rec['Issue']['id'] = $arr_data['Issue']['id'];
					$issue_rec['Issue']['issue_title'] = $arr_data['Issue']['issue_title'];
					$issue_rec['Issue']['issue_description'] = $arr_data['Issue']['issue_description'];
					
					$this->set('issue_rec', $issue_rec);
					$this->set('errDisplay','block');
				}			
		}
	}


	/**
	 * @author Gaurav Saini
	 * @created on 03/06/2011
     * This function saves the reply for Response.
	 * The function is called when user click the submit button from popup.
     * @param Integer insight_id
     * @param Integer success flag
     */
	function addreply($insight_id='', $loginUserId='', $flagSuccessMsg = 0)
	{	
		# Set the title of popup window.
		$this->set("title_for_layout","Add Comment");
		
		#Import Replyresponse model
		App::import('Model', 'Replyresponse');
		
		# Create Content type model object.
		$this->Replyresponse = new Replyresponse();	
		$this->layout='front_pop';
		$this->set('insight_id', $insight_id);
		$this->set('loginUserId', $loginUserId);
		
		# Call function to set default display value for error messages.
		$this->setMessageDivDefaultStatus();	
		
		# Set current timestamp value		
		$timeStamp = strtotime("now");
		
		# Set success Message.
		if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');    	
		
		if(isset($this->data) && $this->data !='')
		{
			$arrProductSaveData = $this->data;

			$this->set('insight_id', $arrProductSaveData['Product']['insight_id']);
			$this->set('loginUserId', $arrProductSaveData['Product']['user_id']);
			
			#Check if attachment is there.
			if (isset($arrProductSaveData['Product']['attachment_name']['name']) && !empty($arrProductSaveData['Product']['attachment_name']['name']))
			{
				# Get attachment extension.
			 	$attachmentExtension =  pathinfo($arrProductSaveData['Product']['attachment_name']['name'],PATHINFO_EXTENSION);
			
				#Get new name for attachment to be saved into database.
				$attachmentNewName = str_replace(pathinfo($arrProductSaveData['Product']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrProductSaveData['Product']['attachment_name']['name']);
				
				if($this->serverValidateAttachment($attachmentExtension,$arrProductSaveData['Product']['attachment_name']['size']))
				{
					# Verify if attachment saved.
					if($this->utility->uploadAttachment($arrProductSaveData['Product']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,PRODUCT_ATTACHMENT_UPLOAD_PATH))
					{
						# Save Attachment Original Name into database.
						$arrProductSaveData['Product']['attachment_real_name'] = $arrProductSaveData['Product']['attachment_name']['name'];
						# Save Attachment New Name into database.
						$arrProductSaveData['Product']['attachment_name'] = $attachmentNewName;						
					}
				}
			}
			else
			{
				$arrProductSaveData['Product']['attachment_name'] = NULL;
			}
			$insight_id = $arrProductSaveData['Product']['insight_id'];
			$loginUserId = $arrProductSaveData['Product']['user_id'];
		
			$this->Replyresponse->save($arrProductSaveData['Product']);	
			$this->Session->write('send_reply_mail', 'done');	
			$this->redirect(SITE_URL . '/products/addreply/'.$insight_id.'/'.$loginUserId.'/1');			
		}
	}

	
	/**
	 * @author Gaurav Saini
	 * @created on 15/06/2011
	 * This function send mail to Moderator when SME contact the Moderator.
	 * @param Integer $id (Insight_id)
	 * @param string $moderator_email_address
	 * @param string $subject
	 * @param string $message
	 * @param string $sme_email_address
	 */
	function sendcontactmailtomoderator($id = '', $moderator_email_address = '', $subject = '', $message = '', $sme_email_address = '')
	{		
			$this->set('insight_url', SITE_URL . '/products/records/'.$id);
			$this->set('insight_id', $id);
			$this->set('message_body', $message);
			$this->set('requestedBy', $this->Session->read('current_user_name'));
			//$EmailTo = 	$moderator_email_address;
			
			$EmailTo = 	"David.Coleman@lexisnexis.co.uk";
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Subject field of the mail
				$this->Email->subject = $subject; 
				
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
				
			#sending type
				$this->Email->sendAs = 'html';
				
			#for layouts/email/default.ctp 
				$this->Email->template = 'insight_contact_moderator_mail';
				
			#sending mail
			//if($EmailTo != ''){
				$sending = $this->Email->send();
			//}
		
	}	
		
	/**
	 * @author Gaurav Saini
	 * @created on 15/06/2011
	 * This function send mail to Moderator when new Insight is added to the system.
	 * @param Integer $id (Insight_id)
	 * @param string $moderator_email_address
	 * Template 6
	 */
	function send_new_insight_mail_to_moderator($id='', $moderator_email_address='')
	{			
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			
			$array_insight = explode(" ", $insight_summary);
			//echo "<pre>";print_r($array_insight);echo "</pre>";die;
			
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}	
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/records/'.$id);

//			$EmailTo = 	$moderator_email_address;
//			
//			$sme_email_address = strtolower($this->Session->read('current_user_emailaddress'));
//			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
               
                 /* email hard coded for testing purpose
                     * @author:sukhvir
                   */    
			$EmailTo = 	"David.Coleman@lexisnexis.co.uk";
			
			$sme_email_address = "David.Coleman@lexisnexis.co.uk";
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Subject field of the mail
				$this->Email->subject = "Feedback Tracker notification: Feedback Ref ".$id." (status: Awaiting delegation)"; 
				
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
				
			#sending type
				$this->Email->sendAs = 'html';
				
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_6';
				
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();
			}
		
	}	

	/**
	 * @author Gaurav Saini
	 * @created on 15/06/2011
     * This function send the mail to moderator when SME want to contact the Moderator.
	 * The function is called when SME clicks the Contact Moderator link on Insight page.
     * @param Integer insight_id
     * @param Integer success flag
     */
	function contact($insight_id='', $flagSuccessMsg = 0)
	{	
		$this->layout='front_pop';
		$this->set('insight_id', $insight_id);

		# Call function to set default display value for error messages.
		$this->setMessageDivDefaultStatus();	
		
		# Set success Message.
		if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');    	
		
		if(isset($this->data) && $this->data !='')
		{
			$arrProductData = $this->data;
			
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			$this->Insight->id = $arrProductData['Product']['insight_id'];
			
			# Reading insight record on the basis of $id.
			//$this->insight_data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();
	
			# Get Moderator Email Address.
			$arrModeratorAddress = $this->Pilotgroup->getModeratorEmailAddress();
			//$sme_email_address = strtolower($this->Session->read('current_user_emailaddress'));
			
                        $moderator_email_address = "David.Coleman@lexisnexis.co.uk";
			
			# Import Insight model
			$insight_id		= $arrProductData['Product']['insight_id'];
			$email_subject 	= $arrProductData['Product']['subject'];
			$email_message 	= $arrProductData['Product']['message_text'];			
			$sme_email_address = "David.Coleman@lexisnexis.co.uk";
			
			# Sending Email to Moderator.
			$this->sendcontactmailtomoderator($insight_id, $moderator_email_address, $email_subject, $email_message, $sme_email_address);
			$this->redirect(SITE_URL . '/products/contact/'.$insight_id.'/1');
		}
	}
	
	/**
	 * This function is created to import user data from csv file. This code is commented because it is not required to execute.
	 * But this is kept for future reference.
	
	function importdata()
	{
		$this->layout='front_pop';
		
		if(isset($this->data) && $this->data !='')
		{
			$arrProductSaveData = $this->data;
			
			#Check if attachment is there.
			if (isset($arrProductSaveData['attachment_name']['name']) && !empty($arrProductSaveData['attachment_name']['name']))
			{
				
			 	#Get new name for attachment to be saved into database.
				$attachmentNewName = "userdata.csv";
				
					# Verify if attachment saved.
					if($this->utility->uploadAttachment($arrProductSaveData['attachment_name']['tmp_name'],$attachmentNewName,'csv',PRODUCT_ATTACHMENT_UPLOAD_PATH))
					{
							
						
						# Import Pilotgroup model
						App::import('Model', 'Pilotgroup');
						# Create Pilotgroup model object
						$this->Pilotgroup = new Pilotgroup();
						
						$handle = fopen(PRODUCT_ATTACHMENT_UPLOAD_PATH."/userdata.csv", "r");
						while (($data = fgetcsv($handle)) !== FALSE) {						
							$query = "";
							$UserName_csv 		= $data[1];
							$FirstName_csv 		= $data[2];
							$LastName_csv 		= $data[3];
							$Role_csv 			= $data[4];
							$email_csv 			= $data[5];
							$cc_email_csv 		= $data[6];
							$departmentName_csv = $data[7];
							
							
														
							if($UserName_csv !=''){
							
								$arrUserData = $this->Pilotgroup->find('all', array('conditions' => array('Pilotgroup.NAME'=>$UserName_csv)));
								if(count($arrUserData) > 0){
									
										$query = "UPDATE pilotgroups SET 
												first_name = '".mysql_real_escape_string($FirstName_csv)."' , 
												sur_name = '".mysql_real_escape_string($LastName_csv)."' , 
												role = '".trim($Role_csv)."' , 
												emailaddress = '".mysql_real_escape_string($email_csv)."' , 
												department_id = (select id from departmentnames where department_name = '".mysql_real_escape_string($departmentName_csv)."'), 
												cc_emailaddress = '".mysql_real_escape_string($cc_email_csv)."'										
												WHERE
												NAME = '".mysql_real_escape_string($UserName_csv)."'";
								}
								else {

									$query = "INSERT INTO pilotgroups (NAME, first_name, sur_name, role, isactive, emailaddress, cc_emailaddress, department_id )
									select '".mysql_real_escape_string($UserName_csv)."','".mysql_real_escape_string($FirstName_csv)."' ,'".mysql_real_escape_string($LastName_csv)."' ,'".trim($Role_csv)."' , 1, '".mysql_real_escape_string($email_csv)."', '".mysql_real_escape_string($cc_email_csv)."', id from departmentnames where department_name = '".mysql_real_escape_string($departmentName_csv)."'";
								}
								
								try{
									echo $query.";<br/>"; 
									//	$this->Pilotgroup->query( $query );
									}
									catch(Exception $e)
									{}
							}
							
						}die;
						fclose($handle);
						if (file_exists(PRODUCT_ATTACHMENT_UPLOAD_PATH."/userdata.csv")) {
							unlink(PRODUCT_ATTACHMENT_UPLOAD_PATH."/userdata.csv");
						}				
					}
					
					
				
			}
			
		}

	}*/


	/**
	 * @author Gaurav Saini
	 * @created on 18/07/2011
     * This function return all the comments for the specific issue_id.
	 * The function is called when user close the comment popup.
     * @param Integer issue_id
     * @return list of replies.
     */
	function getreplies($issue_id='')
	{
		header("Cache-Control: no-cache, must-revalidate");

		#Import Replyresponse model
		App::import('Model', 'Replyresponse');
		# Create Content type model object.
		$this->Replyresponse = new Replyresponse();	
		$productReplyValue = $this->Replyresponse->getResponseReplies(intval(trim($issue_id)));
			
			echo "<table cellpadding='0' cellspacing='0' border='0' style='width:100% !important;#width:96% !important;'>";
						 if(count($productReplyValue)>0){
							$row = 1;
							foreach($productReplyValue as $key=>$value)
							{	$row++;
								if($row % 2)
								{
									$bgcolor = "#FFF";
								}
								else
								{
									$bgcolor = "#E2E2E2";
								}
								
								echo "<tr><td style='padding:5px;background:". $bgcolor."'>";
								$DateMsg = "";
								$CommentAddedBy = ($value['Pilotgroup']['name']!='')?$value['Pilotgroup']['name']:'Historical Data';
								echo "<b>".$CommentAddedBy.":</b><br/>";
								echo nl2br($this->utility->parseString(trim($value['Replyresponse']['reply_text'])))."<br/>";
								if($value['Replyresponse']['attachment_real_name']!=''){
								echo '<a id="attachment_namelink" href="javascript:open_attachment(\''.SITE_URL.'/products/downloadfile/'.$value['Replyresponse']['attachment_name'].'\');">'.$value['Replyresponse']['attachment_real_name'].'</a><br/>';
								}
								$DateMsgDate= date('dS M Y', strtotime($value['Replyresponse']['date_submitted']));
								$tempDateTime=substr($value['Replyresponse']['date_submitted'], 11, 8);					
								$time_hour=substr($tempDateTime,0,2);
								$time_minute=substr($tempDateTime,3,2);
								$time_seconds=substr($tempDateTime,6,2);		
								$time=date("g:i A", mktime($time_hour,$time_minute,$time_seconds));
								$DateMsg= $time.' '.$DateMsgDate;
								echo $DateMsg;						
								echo "</td></tr>";
							}			
						
						}
				echo "</table>";
		die;
	}

	/**
	 * @author Gaurav Saini
	 * @created on 15/06/2011
	 * This function send mail when Contributor will save the insight but unfortunately User Id is NULL or Zero.
	 * @param Array $post
	 * Template ErrorMail
	 */
	function send_error_mail($PostData, $current_user_name)
	{
			App::import('Model', 'Firm');
			# Create Insight model object
			$this->Firm = new Firm();			

			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();
			
			# Import Sellingobstacle model
			App::import('Model', 'Sellingobstacle');
			# Create Sellingobstacle model object
			$this->Sellingobstacle = new Sellingobstacle();			
			
			# Import Competitorname model
			App::import('Model', 'Competitorname');
    		# Create Competitorname model object
			$this->Competitorname = new Competitorname();	
			
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();			
			
			# Set Productname Controller.
			App::import('Model', 'Productname');
			# Create Productname model object.
			$this->Productname = new Productname();				
			
			# Set Contenttype Controller.
			App::import('Model', 'Contenttype');
			# Create Contenttype model object.
			$this->Contenttype = new Contenttype();

			
			# Composing firm name on basis of Id if any else direct name from db field.
			if(isset($PostData['what_firm_name']) && trim($PostData['what_firm_name']) != "") 
			{
				$firmName = $this->processFirmId($PostData['what_firm_name']);
			}
			else {
				$firmName = $PostData['what_firm_name_text'];
			}

			# Composing Content type details by id.
			if(isset($PostData['content_type_id']) && trim($PostData['content_type_id']) != "") 
			{
				$prod_contenttype_arr = array();
				$prod_contenttype_arr = $this->Contenttype->getContentTypeById($PostData['content_type_id']);
				$contentType = $prod_contenttype_arr['Contenttype']['content_type'];
			}
			else 
				$contentType ="";

			# Composing product family name details by id
			if(isset($PostData['product_family_id']) && trim($PostData['product_family_id'])>0) 
			{
				$prod_familiy_name_arr = array();
				$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($PostData['product_family_id']);
				$product_familyName = $prod_familiy_name_arr['Productfamilyname']['family_name'];
			}
			else
			{
				$product_familyName = '';
			}

			# Composing product name details by id
			if(isset($PostData['product_id']) && trim($PostData['product_id'])>0) 
			{
				$productName = $this->Productname->getProductInfoByID($PostData['product_id']);
			}
			else
			{
				$productName = $PostData['who_product_name_text'];
			}						
			# Retreiving practice area details by id
			if(isset($PostData['practice_area_id']) && trim($PostData['practice_area_id']) != "") 
			{
				$practice_area = $this->Practicearea->getPracticeareaNameById($PostData['practice_area_id']);
			}

			# Verify if a competitorInfo exists in the database. If yes get competitor_id else get who_competitor_name_text
			if($PostData['competitor_id'] > 0)
			{
				$competitorInfo = $this->Competitorname->getCompetitorName($PostData['competitor_id']);
				if($competitorInfo > 0)
				{
					$competitor = $competitorInfo['Competitorname']['competitor_name'];
				}
			}
			else
			{
				$competitor = $PostData['who_competitor_name_text'];
			}
			
			# Retreiving Selling Obstacles.
			if($PostData['selling_obstacle_id'] > 0){
				$selling_obstacle = $this->Sellingobstacle->getSellingobstacleNameById($PostData['selling_obstacle_id']);
			}
			
			$insight_summary		= $PostData['insight_summary'];
			$customer_pain_points	= $PostData['customer_pain_points'];
			$recommended_actions	= $PostData['recommended_actions'];
			$what_how_come			= $PostData['what_how_come'];
			$who_contact_role		= $PostData['who_contact_role'];
			
			$this->set('current_user_name',		$current_user_name);
			$this->set('insight_summary',		$insight_summary);
			$this->set('customer_pain_points',	$customer_pain_points);
			$this->set('recommended_actions',	$recommended_actions);
			$this->set('what_how_come',			$what_how_come);
			$this->set('who_contact_role',		$who_contact_role);
			$this->set('selling_obstacle',		$selling_obstacle);
			$this->set('competitor',			$competitor);
			$this->set('practice_area',			$practice_area);
			$this->set('productName',			$productName);
			$this->set('product_familyName',	$product_familyName);
			$this->set('contentType',			$contentType);
			$this->set('firmName',				$firmName);
			

			if(strstr(SITE_URL, 'uat')){
				$Environment = "UAT Environment";
			}
			else if(strstr(SITE_URL, 'prod')){
				$Environment = "PRODUCTION  Environment";
			}
			else {
				$Environment = "Local Development Environment";
			}
			
			$this->set('Environment', $Environment);
			
			
			$EmailTo = 	LNG_UK_INSIGHT_ERROR_EMAIL;
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Subject field of the mail
				$this->Email->subject = "Error occured while adding insight."; 
				
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
				
			#sending type
				$this->Email->sendAs = 'html';
				
			#for layouts/email/default.ctp 
				$this->Email->template = 'errorMail';
			
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();
			}
	}	


	/**
	 * @author Gaurav Saini
	 * @created on 15/06/2011
     * This function display the error message. If Logged in user id become blank anyhow then it will be directed to error page..
     * @param Integer insight_id
     * @param Integer success flag
     */
	function oops()
	{	
		$this->layout='front';
	}


	/**
	 * @author Gaurav Saini
	 * @created on 10/21/2011
     * This function confirms that the User trying to access the insight is a delegated SME or the Contributor of the insight.
	 * The function is called when SME try to edit the insight details from search result page.
     * @param Integer insight_id
     */
	function confirmationpage($insight_id='', $delegated_SME_Id='')
	{	
		$this->layout='front_pop';
		$this->set('delegated_SME_Id', $delegated_SME_Id);
		$this->set('insight_id', $insight_id);
	}

	/**
	 * @author Gaurav Saini
	 * @created on 10/21/2011
     * This function confirms that the User trying to access the insight is a delegated SME or the Contributor of the insight.
	 * The function is called when SME try to edit the insight details from search result page.
     * @param Integer insight_id
     */
	function claiminsight($insight_id='')
	{	
		$this->layout='front_pop';
		$this->set('insight_id', $insight_id);
	}


	/**
	 * @author Gaurav Saini
	 * @created on 10/31/2011
     * This function send mail to contributor when Insight has been claimed by any SME.
	 * @param Integer id (Insight_id) - Insight Id is passed for the information retrival
	 * Email Template :  insight_ownership_mail
     */
	function send_insight_claim_email($id = '')
	{
			
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
		
			$sme_user_name	 		= $this->data['Pilotgroup_D']['name'];
			$sme_first_name	 		= $this->data['Pilotgroup_D']['first_name'];
			$sme_sur_name	 		= $this->data['Pilotgroup_D']['sur_name'];
			$sme_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$sme_cc_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$contributor_emailaddress= "David.Coleman@lexisnexis.co.uk";
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}	
			
			$sme_user_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;				
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/records/'.$id);
			$this->set('new_status',$insight_status);
			$this->set('delegated_to',$sme_user_name);
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			$EmailTo = 	$sme_emailaddress;
			
			$arrCc = array();			
			if($sme_cc_emailaddress !='')
			{
				if(strstr($sme_cc_emailaddress, ';')){
					$pieces = explode(";", strtolower($sme_cc_emailaddress));
					$arrCc = $pieces;
				}
				else
				{
					$arrCc[] = strtolower($sme_cc_emailaddress);
				}
			}
			$arrCc[] = strtolower($contributor_emailaddress);
		
			#To field of the mail
				$this->Email->to = $EmailTo;
			
			#Cc To field of the mail
			$this->Email->cc = $arrCc;
			$EmailSubject = "Feedback Tracker notification: Feedback Ref ".$id." claimed";
			
			#Subject field of the mail
				$this->Email->subject = $EmailSubject; 
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_7';
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}
		
	}


	/**
	 * @author Gaurav Saini
	 * @created on 10/21/2011
     * This function confirms that the User trying to access the insight is a delegated SME or the Contributor of the insight.
	 * The function is called when SME try to edit the insight details from search result page.
     * @param Integer insight_id
     */
	function claimpath($insight_id='', $delegate_id='')
	{	

		# Import Insight model
		App::import('Model', 'Insight');
		# Create Insight model object
		$this->Insight = new Insight();	
		$arrProductSaveData['Product']['id']					= $insight_id;
		$arrProductSaveData['Product']['deligated_to']			= $delegate_id;
		$arrProductSaveData['Product']['delegation_confirmed']	= 'Y';
		

		# Saving data into Insight table for the insight
		$arrData = $this->Insight->save($arrProductSaveData['Product']);
		
		# Sending Email to Moderator.
		$this->send_insight_claim_email($insight_id);
		
		echo "success";die;
	}

	/**
	 * @author Gaurav Saini
	 * @created on 10/31/2011
     * This function confirms that the User trying to access the insight is a delegated SME or the Contributor of the insight.
	 * The function is called when SME try to edit the insight details from search result page.
     * @param Integer insight_id
     */
	function confirmeddelegation($insight_id='')
	{	

		# Import Insight model
		App::import('Model', 'Insight');
		# Create Insight model object
		$this->Insight = new Insight();	
		$arrProductSaveData['Product']['id']					= $insight_id;
		$arrProductSaveData['Product']['delegation_confirmed']	= 'Y';
		
		# Saving data into Insight table for the insight
		$arrData = $this->Insight->save($arrProductSaveData['Product']);
		echo "success";die;
	}
	
	/**
	 * @author Gaurav Saini
	 * @created on 11/24/2011
     * This function displays the popup when delegated SME try to submit the insight without changing the current status.
     */
	function oldstatus()
	{	
		$this->layout='front_pop';
	}
        
        /* New Proccess start called Legal Q&A @sukhvir
         * @author :Sukhvir
         */
        /**
            * @author Sukhvir Singh
            * @created on 19/11/2013
            * This function is to legal Q&A form page. User is able to add legal Q&A form data.
          */      
        
    function legalqaindex($flagSuccessMsg = 0)
    {
	# Include Layout
		$this->layout='front';		
		# Call function to set default display value for error messages.
		$this->setMessageDivDefaultStatus();		
		# Set current timestamp value
		$timeStamp = strtotime("now");	
                            
        /* fetaching insight types data for insight type dropdown form
                 * 
        */
        # Import Insighttype model
	App::import('Model', 'Insighttype');
	$this->Insighttype = new Insighttype();
	$arrinsighttype = $this->Insighttype->returnStaticData(TRUE);
	foreach($arrinsighttype as $key=>$value) 
	{
		$arr[0] = "";		
		$arr[$key] = $value;			
	}
	$arrinsighttype[] = $arr;
	$this->set('insighttypevalues', $arrinsighttype[1]);
                
    	# Set How Come Array
    	# Import Content Type model
    	App::import('Model', 'Insightabout');
    	# Create Content Type model object
		$this->Insightabout = new Insightabout();
    	# Set Who Content Type Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
    	# Import Content Type model
    	App::import('Model', 'Contenttype');
    	# Create Content Type model object
		$this->Contenttype = new Contenttype();
    	# Set Who Content Type Array
    	$this->set('arrContentTypes',$this->Contenttype->getContentTypes());    	
    	# Import Practicearea model
    	App::import('Model', 'Practicearea');
    	# Create Practicearea model object
		$this->Practicearea = new Practicearea();
    	# Set Who Practicearea Array
    	$this->set('arrPracticeArea',$this->Practicearea->getPracticeArea());    	
		# Import Sellingobstacle model
    	App::import('Model', 'Sellingobstacle');
    	# Create Sellingobstacle model object
		$this->Sellingobstacle = new Sellingobstacle();
    	# Set Sellingobstacle Array
    	$this->set('arrSellingObstacles',$this->Sellingobstacle->getSellingObstacle());
		# Import Productfamilyname model
		App::import('Model', 'Productfamilyname');
		# Create Productfamilyname model object
		$this->Productfamilyname = new Productfamilyname();
		$this->set('arrProductFamilynames',$this->Productfamilyname->getProductFamilyNames());
    	if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');    	
    	# Check if the form is submitted. First If step (loop) checks the form is submitted, then in next step the form validates the mandatory fields.
		# After validation, in the third step it checks each form value and then save them in an array variable $arrProductSaveData
    	if(isset($this->data) && !empty($this->data))
    	{	
			# array variable set to fetch insight submitted data
    		$arrProductSaveData = $this->data;
		# Check the values are validated before saving in database
    		if($this->serverValidate($arrProductSaveData))
    		{    	
                      # adding "L" value for legal Q&A Form Post
                      $arrProductSaveData['Product']['flag_logtype'] = "L";
                           
					# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();				
					# Check Organization name is blank or not
					if(isset($arrProductSaveData['Firm']['what_firm_name']) && trim($arrProductSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrProductSaveData['Firm']['what_firm_name']);						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrProductSaveData['Product']['what_firm_name'] = $firmParentID;
						else
							$arrProductSaveData['Product']['what_firm_name_text'] = $arrProductSaveData['Firm']['what_firm_name'];
					}
					# Check Productfamily name is blank or not	
					if(isset($arrProductSaveData['Productfamilyname']['who_product_family_name']) && trim($arrProductSaveData['Productfamilyname']['who_product_family_name']) > 0)
					{
						# Get product family name key if exists.	
						# Set Product family name Field Text value from filled autosearch field.
						$productFamilyNameID = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
						$arrProductSaveData['Product']['product_family_id'] = $productFamilyNameID;
					}
					else 
					{
							$arrProductSaveData['Product']['who_product_family_name_text'] = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
					}
					# Check Product name is blank or not
					if(isset($arrProductSaveData['Productname']['who_product_name']) && trim($arrProductSaveData['Productname']['who_product_name']) != '')
					{
						# Get product name key if exists.	
						$productNameID = $this->processProductNameExistance($arrProductSaveData['Productname']['who_product_name']);
						# Set Product name Field Text value from filled autosearch field.
						if(isset($productNameID) && $productNameID > 0)
							$arrProductSaveData['Product']['product_id'] = $productNameID;
						else
							$arrProductSaveData['Product']['who_product_name_text'] = $arrProductSaveData['Productname']['who_product_name'];
					}								
					# verify competitor id and save id else save name.
					if(isset($arrProductSaveData['Competitorname']['who_competitor_name']) && trim($arrProductSaveData['Competitorname']['who_competitor_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$competitorID = $this->processCompetitorExistance($arrProductSaveData['Competitorname']['who_competitor_name']);
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($competitorID) && $competitorID > 0)
							$arrProductSaveData['Product']['competitor_id'] = $competitorID;
						else
							$arrProductSaveData['Product']['who_competitor_name_text'] = $arrProductSaveData['Competitorname']['who_competitor_name'];
					}
					#Save User Id of the current user into the insights table.
					$arrProductSaveData['Product']['user_id'] = $this->Session->read('current_user_id');				
					#Set Attachment Name with current timestamp								
					# Check if attachment is there.
					if (isset($arrProductSaveData['ProductAttachment']['attachment_name']['name']) && !empty($arrProductSaveData['ProductAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrProductSaveData['ProductAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrProductSaveData['ProductAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
							if($this->utility->uploadAttachment($arrProductSaveData['ProductAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,PRODUCT_ATTACHMENT_UPLOAD_PATH))
							{
								
								# If file exists physically then only file name will be saved in database.
								$filename = ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/'.$attachmentNewName;
								if(file_exists($filename)) 
								{
									# Save Attachment New Name into database.
									$arrProductSaveData['Product']['attachment_name'] = $attachmentNewName;
									# Save Attachment Original Name into database.
									$arrProductSaveData['Product']['attachment_real_name'] = $arrProductSaveData['ProductAttachment']['attachment_name']['name'];
								}
							}
						}
					}				
				# Code by Pragya Dave - fixed special characters insertion
				$arrProductSaveData['Product']['insight_summary'] = $this->utility->parseString($this->data['Product']['insight_summary']);
				# Verify if there is no error.
				if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
				{
					//$arrProductSaveData['Product']['user_id'] = null;
			
					if($arrProductSaveData['Product']['user_id'] > 0){
					
							#Save Product Insight into database.
							$this->Insight->save($arrProductSaveData['Product']);
				
							$last_inserted_id = $this->Insight->id; 
							# Import Pilotgroup model
							
							App::import('Model', 'Pilotgroup');
							# Create Pilotgroup model object
							$this->Pilotgroup = new Pilotgroup();
					
							# Get Moderator Email Address.
							$arrModeratorAddress = $this->Pilotgroup->getModeratorEmailAddress();
							$moderator_email_address = $arrModeratorAddress;
							# Send Moderator a notification mail informing him that new Insight is added.
							$this->send_new_legalqa_mail_to_moderator($last_inserted_id, $moderator_email_address);		
							
							# Redirect if save is successful.
							$this->redirect(SITE_URL . '/products/legalqaindex/1');
						}
						else
						{	
							# Send Error email.							
							$this->legalqa_send_error_mail($arrProductSaveData['Product'], $_SESSION['current_user_name']);
							
							# Serializing data to store for Error log purpose.
							$arrProductSaveData['Product']['current_user_name'] = $_SESSION['current_user_name'];
							$serializeData = serialize($arrProductSaveData['Product']);

							# Log error in error.log file.
							$this->log('=====>>'.$serializeData);

							$this->redirect(SITE_URL . '/products/oops');
						}
			    }				
    		} # End validation check if
    	} # End form value posted if    	
    } # End function
	
     /**
	* @author Sukhvir Singh
	* @created on 19/11/2013
        * This function is to display legal search form page. When the user clicks Search button, then the search page leads to search result page.
      */
     function legalqasearch($insightId = '', $found = '')
    {
		# Set layout
		$this->layout='front';
		# Setting current url to redirect in case no result found.
		$this->Cookie->write('currentUrl', SITE_URL.'/products/search');
		$this->Cookie->write('backUrl', SITE_URL.'/products/search');
		# Removing conditions array from session if any.
		$this->Session->delete('conditionsArr');
        # Set How Come Array
    	# Import Insightabout model
    	App::import('Model', 'Insightabout');
    	# Create Insightabout model object
		$this->Insightabout = new Insightabout();
    	# Set Who Insightabout Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
    	# Import Content Type model
    	App::import('Model', 'Contenttype');
    	# Create Content Type model object
		$this->Contenttype = new Contenttype();
    	# Set Who Content Type Array
    	$this->set('arrContentTypes', $this->Contenttype->getContentTypes(TRUE));    	
    	# Import Practicearea model
    	App::import('Model', 'Practicearea');
    	# Create Practicearea model object
		$this->Practicearea = new Practicearea();
    	# Set Practicearea Names Array
    	$this->set('arrPracticeArea', $this->Practicearea->getPracticeArea(TRUE));
		# Import Pilotgroup model
		App::import('Model', 'Pilotgroup');
		# Create Pilotgroup model object
		$this->Pilotgroup = new Pilotgroup();
		# Set Pilotgroup names array for search view.
		$arrCreatedBy = $this->Pilotgroup->getPilotGroups(TRUE); //True passed for protect pilot group key id for dropdown.
		$this->set('arrCreatedBy', $arrCreatedBy);
		
    	# Import Statusinsight model
    	App::import('Model', 'Statusinsight');
    	# Create Statusinsight model object
		$this->Statusinsight = new Statusinsight();
    	# Set Statusinsight Names Array
		# Set db values for dropdown with the caption
		foreach($this->Statusinsight->getStatusList() as $key=>$value) 
		{				
			$arrstatus[$key] = $value;			
		}
		$arrinsightstatus[] = $arrstatus;		
    	$this->set('arrStatusinsight', $arrinsightstatus);		
		# Set CreatedBy users Array
    	$this->set('arrDelegatedTo', $arrCreatedBy); // Current owner users list.		
		# Import Sellingobstacle model
    	App::import('Model', 'Sellingobstacle');
    	# Create Sellingobstacle model object
		$this->Sellingobstacle = new Sellingobstacle();
    	# Set Sellingobstacle Array
    	$this->set('arrSellingObstacles',$this->Sellingobstacle->getSellingObstacle());

		# Set Who Market Array
		# Import Market model
                App::import('Model', 'Market');
                # Create Sellingobstacle model object
		$this->Market = new Market();
		$this->set('arrWhoMarket',$this->Market->getMarkets());		
		# Import Productfamilyname model
		App::import('Model', 'Productfamilyname');
		# Create Productfamilyname model object
		$this->Productfamilyname = new Productfamilyname();
		$this->set('arrProductFamilynames',$this->Productfamilyname->getProductFamilyNames());	
		# To show error message not found in case result not found
		# by search by insight id.
		if($found == 'date_mismatch') 
		{
			$this->set('datemismatch', $found);
		}elseif($found == 'incorrect_start_date') 
		{
			$this->set('incorrect_start_date', $found);
		}elseif($found == 'incorrect_end_date') 
		{
			$this->set('incorrect_end_date', $found);
		}else {
			$this->set('found', $found);
		}
		$this->set('insightId', $insightId);		
    } # End function
     /**
	* @author Sukhvir Singh
	* @created on 20/11/2013
    * This functiion is to display legal search results page. When the user clicks Search Legal Q&A button, then the search page leads to search result page as per the critera applied for searching by user.
	* The function will return results of Insights based on the search request posted. If Search is by Insight Id then the request goes to Edit insight page otherwise to search result page for further refining if required.
	 
     */
    function legalqaresults($insightId = '')
    {	
			header("Cache-Control: no-cache, must-revalidate");

			# Set layout
			$this->layout='front';		
			# Initialize Models /Tables
                       	# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();
			$conditionsArr = array();
			App::import('Model', 'Firm');
			# Create Insight model object
			$this->Firm = new Firm();
			# Set How Come Array
			# Import Insightabout model
			App::import('Model', 'Insightabout');
			# Create Content Type model object
			$this->Insightabout = new Insightabout();			
			# Set Who Content Type Array
			# Set db values for dropdown with the caption
			foreach($this->Insightabout->returnStaticData(TRUE) as $key=>$value) 
			{						
				$arrabout[$key] = $value;			
			}
			# Set default dropdown value
			$arrabout[0] = "Origin of Question?";				
			$this->set('arrHowCome',$arrabout);
                        # Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();
			# Set Practicearea Array
			# Set db values for dropdown with the caption
			foreach($this->Practicearea->getPracticeArea(TRUE) as $key=>$value) 
			{	
				$arrapractice_area[$key] = $value;			
			}
			# Set default dropdown value	
			$arrapractice_area[0] = "Practice Area";			
			$this->set('arrPracticeArea',$arrapractice_area );
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();
			# Set Pilotgroup names array for search view.
			$arrCreatedBys = $this->Pilotgroup->getPilotGroups(TRUE); //True passed for protect pilot group key id for dropdown.
			# Set db values for dropdown with the caption
			foreach($arrCreatedBys as $key=>$value) 
			{						
				$arrCreatedBy[$key] = $value;			
			}	
			# Set default dropdown value	
			$arrCreatedBy[0] = "Created By";			
			$this->set('arrCreatedBy', $arrCreatedBy);
			
			# Create Issues model object
			$this->Issue = new Issue();
			# Set Issue names array for search view.
			$arrIssues = $this->Issue->getIssues(TRUE);
			# Set db values for dropdown with the caption
			foreach($arrIssues as $key=>$value) 
			{						
				$arrIssues[$key] = $value;			
			}	
			# Set default dropdown value	
			$arrIssues[0] = "Issue";			
			//$arrIssues[-1] = "Not linked to an Issue";			
			$this->set('arrIssues', $arrIssues);			
			
			# Import Statusinsight model
			App::import('Model', 'Statusinsight');
			# Create Statusinsight model object
			$this->Statusinsight = new Statusinsight();
			# Set Statusinsight Array
			# Set db values for dropdown with the caption
			foreach($this->Statusinsight->getStatusList() as $key=>$value) 
			{
				$arrstatus[$key] = $value;			
			}
			$arrinsightstatus[] = $arrstatus;		
			$this->set('arrStatusinsight', $arrinsightstatus);
			# Set db values for dropdown with the caption
			foreach($arrCreatedBys as $key=>$value) 
			{						
				$arrDelegated[$key] = $value;			
			}	
			# Set default dropdown value
			$arrDelegated[0] = "Current Owner";	
			$this->set('arrDelegatedTo', $arrDelegated); // Current owner users list.			
			# Import Sellingobstacle model
			App::import('Model', 'Sellingobstacle');
			# Create Sellingobstacle model object
			$this->Sellingobstacle = new Sellingobstacle();
			# Set Sellingobstacle Array
			# Set db values for dropdown with the caption
			foreach($this->Sellingobstacle->getSellingObstacle() as $key=>$value) 
			{					
				$arrselling_obstacle[$key] = $value;			
			}
			$arrselling_obstacle[0] = "Selling obstacles";		
			$this->set('arrSellingObstacles', $arrselling_obstacle);

			# Set Who Market Array
			# Import Market model
			App::import('Model', 'Market');
			# Create Market model object
			$this->Market = new Market();
			# Set db values for dropdown with the caption
			foreach($this->Market->getMarkets() as $key=>$value) 
			{						
				$arrMarket[$key] = $value;			
			}	
			# Set default dropdown value
			$arrMarket[0] = "Market Segment";	
			$this->set('arrWhoMarket',$arrMarket);			
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();
			# Set db values for dropdown with the caption
			foreach($this->Productfamilyname->getProductFamilyNames() as $key=>$value) 
			{				
				$arraprod_family[$key] = $value;			
			}	
			# Set default dropdown value	
			$arraprod_family[0] = "Product Family Name";						
			$this->set('arrProductFamilynames',$arraprod_family);
			
			# Set array for Sorting fields for the view result page (dropdown selection).
			$sort_type = array('-' => 'Sort Results By',
					'what_how_come' => 'Origin of Question?',					
				        'who_contact_name' => 'Contact Name',
                                        'product_family_id,who_product_family_name_text' => 'Product Family Name',
					'product_id,who_product_name_text' => 'Product Name',
					'practice_area_id' => 'Practice Area',
					'user_id' => 'Created By',
					'insight_status' => 'Feedback Status',
					'deligated_to' => 'Current Owner',
					'issue_field' => 'Issue',
					'market_id' => 'Market Segment'
			);	
			$this->set('sort_type', $sort_type);			
			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();			
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();			
			# Set Productname Controller.
			App::import('Model', 'Productname');
			# Create Productname model object.
			$this->Productname = new Productname();				
			# Search process (conditions composing) start.
			$postval = $this->Session->read('postdata');
			$this->log("===Post Data===>" .$this->data, LOG_DEBUG);
			# Start Insight process 
			$this->Insight->begin();			
			try 
			{		
				# Search by id.
				if(isset($this->data) && !empty($this->data) && isset($this->data['Insight']['id']) && $this->data['Insight']['id'] != "Search By Feedback Id")
				{
					if(!is_numeric($this->data['Insight']['id']) && $this->data['Insight']['id'] != "") 
					{
						$this->redirect($this->Cookie->read('currentUrl') .'/'. $this->data['Insight']['id'] .'/notfound');
					}	
					$insightType = $this->Insight->field('id', array('id' => $this->data['Insight']['id']));
					if(trim($insightType) != "") 
					{				
							$this->redirect(SITE_URL.'/'.strtolower('product').'s/legalqarecords/'.$this->data['Insight']['id']);
					}
					elseif($this->data['Insight']['id']>0)
					{
							$this->redirect($this->Cookie->read('currentUrl') .'/'. $this->data['Insight']['id'] .'/notfound');
					}				
				}
				# Retreive Post data
				if(isset($this->data) && !empty($this->data) || $insightId>0 || isset($postval))
				{
					if(!isset($this->data) ) 
					{
						$this->data = $postval['data'];
					}
					# Retreive Post Free text string
					# This code fetched the text entered in free text search box from basic search page. The text string will be searched among all the fields in the Insight table. For those in which id is used as foreign key to other table, it will first check for that id in the respective table and then check if that text exists in the field, if matches returns to the Insight record else display - No record found.
					$search_string	= trim($this->data['Product']['free_search_text']);
					$this->set('search_string', $search_string);					
					# Set value of search string into POST variable so that it gets maintained into session later
					$_POST['data']['free_search_text']= $search_string;					
					# If the text is Free Text Search then the string is as null and all the Insights will be fetched in this case
					if($search_string == "Free Text Search")
					{
						$search_string = "";
					}		
					
					$parentId = "";
					$mparentId = "";
					$productFamilyNameID = "";
					$productNameID = "";
					$competitorID ="";
					$practiceareaID = "";
					$SellingobstacleID = "";
					$InsightStatusID = "";
					$UserId = "";
					$insightid = "";					
					# Retreive submitted Free text string if not Empty/Null
					if(isset($search_string) && $search_string != "" ) 
					{					
						# Check for firm name/ Condition for firm name.	If name exists check the parent_id is equal to what_firm_name field.		
						$parentId = $this->Firm->getsearchFirmData($search_string);
						if($parentId > 0)
							$what_firm_name = ' OR Insight.what_firm_name = '.$parentId;	
						else $what_firm_name = '';						
						# Check for Market name/ Condition for Market name.	If name exists check the id is equal to market_id field.
						$mparentId = $this->Market->getsearchMarketData($search_string);
						if($mparentId > 0)
							$market_id = ' OR Insight.market_id = '.$mparentId;	
						else $market_id = '';						
						# Check for Productfamilyname name/ Condition for Productfamilyname name.If name exists check the id is equal to product_family_id field.
						$productFamilyNameIDs = $this->Productfamilyname->getsearchProductfamilynameData($search_string);					
						if($productFamilyNameIDs > 0) 
							$productFamilyNameID = ' OR Insight.product_family_id = '.$productFamilyNameIDs;					
						else $productFamilyNameID = '';
						# Check for productName name/ Condition for productName name.If name exists check the id is equal to product_id field.
						$productNameID = $this->Productname->getsearchProductNameData($search_string);
						if($productNameID > 0)
							$productNameID = ' OR Insight.product_id = '.$productNameID;	
						else $productNameID = '';
						# Check for practicearea name/ Condition for practicearea name.If name exists check the id is equal to practice_area_id field.
						$practiceareaID = $this->Practicearea->getsearchPracticeareaData($search_string);
						if($practiceareaID > 0)
							$practiceareaID = ' OR Insight.practice_area_id = '.$practiceareaID;	
						else $practiceareaID = '';
						# Check for Statusinsight name/ Condition for Statusinsight name.If name exists check the id is equal to insight_status field.
						$InsightStatusID = $this->Statusinsight->getsearchStatusData($search_string);
						if($InsightStatusID > 0)
							$InsightStatusID = ' OR Insight.insight_status = '.$InsightStatusID;	
						else $InsightStatusID = '';
						# Check for Pilotgroup name/ Condition for Pilotgroup name. If name exists check the id is equal to user_id,deligated_to,updated_by field.
						$UserIds = $this->Pilotgroup->getsearchPilotgroupUserIData($search_string);
						if($UserIds > 0) 
						{
							$UserId = ' OR Insight.user_id = '.$UserIds;	
							$UserId .= ' OR Insight.deligated_to = '.$UserIds;	
							$UserId .= ' OR Insight.updated_by = '.$UserIds;	
						}
						else $UserId = '';						
						# Check if the string is numeric then checks the Insight id
						if(isset($search_string) && is_numeric($search_string) )
							$insightid = ' OR Insight.id = "'.$search_string. '"';	
                                                                                                                                             
						# Search in all field values
						$conditionsArr = array_merge($conditionsArr, 
							array(' ( Insight.what_insight_type LIKE "'.'%'. strtolower($search_string). '%' .'"'.
							$insightid.
							' OR LOWER(Insight.what_how_come) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.				
							' OR LOWER(Insight.who_contact_name) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.insight_summary) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.do_action) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.what_firm_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_firm_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_product_family_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							' OR LOWER(Insight.who_product_name_text) LIKE "'.'%'.strtolower($search_string) . '%'.'"'.
							$what_firm_name.
							$market_id.
							$productFamilyNameID.
							$productNameID.
							$practiceareaID.
							$InsightStatusID.
							$UserId.
							' OR Insight.date_updated = "'.$search_string.'"'.
							' OR Insight.date_submitted = "'.$search_string.'" )'
						));
					}
					# If not Free text string then check other posted values
					if($insightId<1) 
					{					
					
							# Check the date range lies less than current date and is inbetween the range otherwise generate respective errors
							if( ( ( isset($this->data['Product']['created_from']) && trim($this->data['Product']['created_from'])) != '') && ( isset($this->data['Product']['created_to']) && trim($this->data['Product']['created_to'])) != '') 
							{
								 $start = strtotime($this->data['Product']['created_from']);
								 $end = strtotime($this->data['Product']['created_to']);
								 if ($start-$end > 0)
									 $this->redirect($this->Cookie->read('currentUrl') .'/'. 'yes/date_mismatch' );							
							}			
							if( ( ( isset($this->data['Product']['created_from']) && trim($this->data['Product']['created_from'])) != '') )  
							{
								 $start = strtotime($this->data['Product']['created_from']);
								 $end = strtotime(date('Y-m-d'));
								 if ($start-$end > 0) 
									 $this->redirect($this->Cookie->read('currentUrl') .'/'. 'yes/incorrect_start_date' );								 
							}
							if( ( ( isset($this->data['Product']['created_to']) && trim($this->data['Product']['created_to'])) != '') )  
							{
								 $start = strtotime($this->data['Product']['created_to']);
								 $end = strtotime(date('Y-m-d'));
								 if ($start-$end > 0) 
									 $this->redirect($this->Cookie->read('currentUrl') .'/'. 'yes/incorrect_end_date' );							 
							}	
							# Check what how come value from post data
							if(isset($this->data['Product']['what_how_come']) && $this->data['Product']['what_how_come'] != "0" && $this->data['Product']['what_how_come'] != "Origin of Question?" ) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.what_how_come' => $this->data['Product']['what_how_come']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" &&$this->data['Product']["basic_search_what_how_come"] == "yes" )) || $this->data['Product']["basic_search_what_how_come"] == "yes" )  	
								{
										$this->set('basic_search_what_how_come', "yes");
								}
								$this->set('value_what_how_come', $this->data['Product']['what_how_come']);
							}		
							# Check for source name.
							if(isset($this->data['Product']['what_source_name']) && trim($this->data['Product']['what_source_name']) != "") 
							{
								$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_source_name) LIKE' => '%' . strtolower($this->data['Product']['what_source_name']) . '%'));				
							}
							# Check for firm name/ Condition for firm name.
							if(isset($this->data['Firm']['what_firm_name']) && trim($this->data['Firm']['what_firm_name']) != ""  && $this->data['Firm']['what_firm_name'] != "Organisation Name") 
							{
								$parentId = $this->processFirmExistance($this->data['Firm']['what_firm_name']);
								if($parentId>0) 
								{
									$conditionsArr = array_merge($conditionsArr, array('Insight.what_firm_name' => $parentId));
								}
								else 
								{
										$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_firm_name_text) LIKE' => '%' . strtolower($this->data['Firm']['what_firm_name']) . '%'));
								}
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_what_firm_name"] == "yes")) || $this->data['Product']["basic_search_what_firm_name"] == "yes" ) 
								{ 		
										$this->set('basic_search_what_firm_name', "yes");
								}
								$this->set('value_what_firm_name', $this->data['Firm']['what_firm_name']);
							}
							# Check / Condition for contact Name / role.
                                                        /*
                                                         * @sukhvir update text only here contact Name / role should be "Role" and Contact Name / Publication "Contact Name"
                                                         */
							# Check / Condition for contact name.
							if(isset($this->data['Product']['who_contact_name']) && trim($this->data['Product']['who_contact_name']) != "" && $this->data['Product']['who_contact_name'] != "Contact Name") 
							{
								$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_contact_name) LIKE' => '%' . strtolower($this->data['Product']['who_contact_name']) . '%'));	
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_contact_name"] == "yes")) || $this->data['Product']["basic_search_who_contact_name"] == "yes" ) 
								{ 		
									$this->set('basic_search_who_contact_name', "yes");
								}
								$this->set('value_who_contact_name', $this->data['Product']['who_contact_name']);
							}
							# Set condition for product family name
							if(isset($this->data['Productfamilyname']['who_product_family_name']) && trim($this->data['Productfamilyname']['who_product_family_name']) > 0)
							{
									# Check whether product family id matched with Insight product_family_id
									$conditionsArr = array_merge($conditionsArr, array('Insight.product_family_id' => $this->data['Productfamilyname']['who_product_family_name']));					
									# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
									if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_product_family_name"] == "yes")) || $this->data['Product']["basic_search_who_product_family_name"] == "yes" ) 
									{ 		
										$this->set('basic_search_who_product_family_name', "yes");
									}
									$this->set('value_who_product_family_name', $this->data['Productfamilyname']['who_product_family_name']);
							}
							# Set condition for product name
							if(isset($this->data['Productname']['who_product_name']) && trim($this->data['Productname']['who_product_name']) != ''  && $this->data['Productname']['who_product_name'] != "Product Name")
							{
								# Get product name key if exists.	
								$productNameID = $this->processProductNameExistance($this->data['Productname']['who_product_name']);
								# Check whether id matched with the Insight product_id otherwise check the value from who_product_name_text
								if(isset($productNameID) && $productNameID > 0)
									$conditionsArr = array_merge($conditionsArr, array('Insight.product_id' => $productNameID));
								else
									$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_product_name_text) LIKE' => '%' . strtolower($this->data['Productname']['who_product_name']) . '%'));				
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_who_product_name"] == "yes")) || $this->data['Product']["basic_search_who_product_name"] == "yes" ) 
								{ 		
										$this->set('basic_search_who_product_name', "yes");
								}
								$this->set('value_who_product_name', $this->data['Productname']['who_product_name']);
							}						
							# Check for created by/Pilotgroup id.
							if(isset($this->data['Product']['user_id']) && trim($this->data['Product']['user_id'])>0) 
							{
									$conditionsArr = array_merge($conditionsArr, array('Insight.user_id' => $this->data['Product']['user_id']));						
									# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
									if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_user_id"] == "yes")) || $this->data['Product']["basic_search_user_id"] == "yes" ) 
									{ 		
										$this->set('basic_search_user_id', "yes");
									}
									$this->set('value_user_id', $this->data['Product']['user_id']);
							}					
							                                                       
							# Check the created date with the date range requested from the search form
							if(trim($this->data['Product']['created_from']) != "") 
							{
									$startTimeArr = explode('-', $this->data['Product']['created_from']);
									$startTime = mktime(0,0,0,$startTimeArr[1],$startTimeArr[2],$startTimeArr[0]);											
									if(trim($this->data['Product']['created_to']) == "" ) 
									{
										$endTime = time();
									}
									else
									{
										$endTimeArr = explode('-', 	$this->data['Product']['created_to']);
										$endTime = mktime(23,59,59,$endTimeArr[1],$endTimeArr[2],$endTimeArr[0]);
									}
									$conditionsArr = array_merge($conditionsArr, array('UNIX_TIMESTAMP(Insight.date_submitted) BETWEEN ? AND ?' => array($startTime, $endTime)));	
									$this->set('created_from', $this->data['Product']['created_from']);
									$this->set('created_to', $this->data['Product']['created_to']);
							}			
							# Search for current status value.
							# Check where search from basic search page or search result page and value is set for the insight status
							if(isset($this->data['Product']['insight_status']) && !is_array($this->data['Product']['insight_status']) && trim($this->data['Product']['insight_status']) != '' ) 
							{
									$this->data['Product']['insight_status'] = explode(',', $this->data['Product']['insight_status']);	
							}
							if( isset($this->data['Product']['insight_status']) && is_array($this->data['Product']['insight_status']) ) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.insight_status' => $this->data['Product']['insight_status']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_insight_status"] == "yes")) || $this->data['Product']["basic_search_insight_status"] == "yes" ) 
								{ 		
										$this->set('basic_search_insight_status', "yes");
								}					
								$this->set('value_insight_status', $this->data['Product']['insight_status']);
							}
							
							# Search for Insight created by mobile
							if( isset($this->data['Product']['flag_mobile']) && ($this->data['Product']['flag_mobile'] == 'Y' || $this->data['Product']['flag_mobile'] == 'N') ) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.flag_mobile' => $this->data['Product']['flag_mobile']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_flag_mobile"] == "yes")) || $this->data['Product']["basic_search_flag_mobile"] == "yes" ) 
								{ 		
										$this->set('basic_search_insight_status', "yes");
								}					
								$this->set('value_flag_mobile', $this->data['Product']['flag_mobile']);
							}
							# Search for Current owner value.
							if(isset($this->data['Product']['deligated_to']) && $this->data['Product']['deligated_to']>0) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.deligated_to' => $this->data['Product']['deligated_to']));				
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_deligated_to"] == "yes")) || $this->data['Product']["basic_search_deligated_to"] == "yes" ) 
								{ 		
									$this->set('basic_search_deligated_to', "yes");
								}
								$this->set('value_deligated_to', $this->data['Product']['deligated_to']);
							}						
							# verify issue id and save id else save name.
							if(isset($this->data['Issue']['issue_title']) && trim($this->data['Issue']['issue_title']) != '' && trim($this->data['Issue']['issue_title']) != '' && $this->data['Issue']['issue_title'] != "Issue")
							{
								# Verify if a issue exists in the database.
								$issueID = $this->processIssueExistance($this->data['Issue']['issue_title']);						
								# Set Firm name Field Text value from filled autosearch field.
								
								$conditionsArr = array_merge($conditionsArr, array('Insight.issue_field' => $issueID));

								
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_issue_field"] == "yes")) || $this->data['Product']["basic_search_issue_field"] == "yes" ) 
								{ 		
										$this->set('basic_search_issue_field', "yes");
								}
								$this->set('value_issue_field', $this->data['Issue']['issue_title']);
							}
													
										
							# Check / Conditon for market.
							if(isset($this->data['Product']['market_id']) && $this->data['Product']['market_id']>0) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.market_id' => $this->data['Product']['market_id']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_market_id"] == "yes")) || $this->data['Product']["basic_search_market_id"] == "yes" ) 
								{ 		
										$this->set('basic_search_market_id', "yes");
								}
								$this->set('value_market_id', $this->data['Product']['market_id']);
							}
							# Check for practice_area_id.
							if(isset($this->data['Product']['practice_area_id']) && $this->data['Product']['practice_area_id']>0) 
							{
								$conditionsArr = array_merge($conditionsArr, array('Insight.practice_area_id' => $this->data['Product']['practice_area_id']));
								# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
								if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search_practice_area_id"] == "yes")) || $this->data['Product']["basic_search_practice_area_id"] == "yes" ) 
								{ 		
											$this->set('basic_search_practice_area_id', "yes");
								}
								$this->set('value_practice_area_id', $this->data['Product']['practice_area_id']);
							}
						}
						else
						{
							$conditionsArr = array('Insight.id' => $insightId);
							# Check whether value is posted from basic search form, then set variable to yes (used to disable that form field)
							if( ((! $_POST["refine"]  && $this->data['Product']['sort_type'] == "" ) || (isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != "" && $this->data['Product']["basic_search"] == "yes")) || $this->data['Product']["basic_search"] == "yes" ) 
							{ 		
								$this->set('basic_search', "yes");
							}
						}
                                               
                                           /* @sukhvir
                                                 * Check search value perform by only Legal Q&A condition
                                           */
                                                $flag_logtype  = "L";
                                               
                                                # Check flag for user log on from which section insight or legalqa
                                              if($flag_logtype){
                                                 $conditionsArr = array_merge($conditionsArr, array('Insight.flag_logtype' =>$flag_logtype));
                                               }
                                                
						# Write session value
						$this->Session->write('conditionsArr', serialize($conditionsArr));
						$this->Session->write('exportType', 'product');
						# Set sorting and ordering value in POST data
						$_POST['data']['Product']['sort_type'] = $this->data['Product']['sort_type'];
						$_POST['data']['Product']['ordering'] = $this->data['Product']['ordering'];
						$_POST['data']['Product']= $this->data['Product'];
						# Maintain session of posted values
						$this->Session->write('postdata', $_POST);					
				}
				# Read the session post data
				$postval = $this->Session->read('postdata');
				# In case searched other type from other tab.
				if($this->Session->read('exportType') != 'product')
				{
					$this->redirect(SITE_URL.'/products/legalqasearch');	
				}
				# Looking for search condition.
				if($this->Session->read('conditionsArr') != "")
				{	
					# Set pagination array and condition for total count of records
					
					/*$this->paginate = array(
							'conditions' => unserialize($this->Session->read('conditionsArr')),		
							'limit' => 1000
					);
					
					# Retrieving results form paginator.
					$totalresult = $this->paginate('Insight');					
					*/

					$totalresult = $this->Insight->find('all', array('conditions' => unserialize($this->Session->read('conditionsArr'))));

					# Fetching Sorting criteria and based on the submitted field type apply sorting by that field
					if(isset($this->data['Product']['sort_type']) && $this->data['Product']['sort_type'] != '-') 
					{
						$sort_option = $this->data['Product']['sort_type'];
							switch($sort_option){
								case "user_id":
										$sort_option = "LOWER(Pilotgroup.name)";	
										break;
								case "deligated_to":
										$sort_option = "LOWER(Pilotgroup_D.name)";	
										break;
								case "what_firm_name,what_firm_name_text":						
										$sort_option = "CONCAT_WS('',Firm.firm_name, Insight.what_firm_name_text)";
										break;
								case "product_family_id,who_product_family_name_text":								
										$sort_option = "`Productfamilyname`.`family_name`";
										break;
									case "product_id,who_product_name_text":								
										$sort_option = "CONCAT_WS('',Productname.`product_name`, Insight.who_product_name_text)";
										break;
										case "practice_area_id":
										$sort_option = "Practicearea.practice_area";
									case "insight_status":
										$sort_option = "Statusinsight.status";
										break;
									case "issue_field":
										$sort_option = "Issue.issue_title";
										break;	
									case "market_id":
										$sort_option = "Market.market";
										break;
								default:
										$sort_option = 'Insight.'.$this->data['Product']['sort_type'];
										break;
							}						
					} 
					else
						$sort_option = 'Insight.'."date_submitted";

					if(isset($this->data['Product']['ordering']) && $this->data['Product']['ordering'] != '') 
					{
						$ordering = " ".$this->data['Product']['ordering'];
					}
					else
						$ordering = " desc";
					# Set ordering
					$this->set('ordering', $ordering);
					
                                        # Set pagination and condition values for database
					$this->paginate = array(
							'conditions' => unserialize($this->Session->read('conditionsArr')),
							'order' => $sort_option . $ordering,
							//'group' => 'Insight.id',
							'limit' => RECORD_PER_PAGE
					);	
					# Retrieving results from paginator.
					$result = $this->paginate('Insight');
					$this->Insight->commit(); // Persist the data
				}			
		}
		catch(exception $ex) 
		{
			# Write error in log if there is problem in saving purchase xml in database.
			$this->log("Error Occur at the time of fetching records " . $this->data, LOG_DEBUG);
		}
		# Processing result. If data is submitted than store form values in $final_result
	
		if(!empty($result))
		{
			$i = 0;
			foreach($result as $row)
			{
				# Retreiving Insight
				$final_result[$i] = $row['Insight'];
				# Set user_id based on Pilotgroup id details
				$final_result[$i]['userSubmittedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['user_id']);
				# If what_how_come is 0, then set value to null not 0
				if($final_result[$i]['what_how_come'] == "0")
					$final_result[$i]['what_how_come'] = "";										
				# Composing firm name on basis of Id if any else direct name from db field.
				if(isset($row['Insight']['what_firm_name']) && trim($row['Insight']['what_firm_name']) != "") 
				{
					$final_result[$i]['firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
				}
				else {
					$final_result[$i]['firmName'] = $final_result[$i]['what_firm_name_text'];
				}				
				# Composing product family name details by id
				if(isset($row['Insight']['product_family_id']) && trim($row['Insight']['product_family_id'])>0) 
				{
					$prod_familiy_name_arr = array();
					$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['product_family_id']);
					$final_result[$i]['who_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
				}
				else
				{
					$final_result[$i]['who_product_familyName'] = '';
				}
				# Composing product name details by id
				if(isset($row['Insight']['product_id']) && trim($row['Insight']['product_id'])>0) 
				{
					//$prod_name_arr = array();
					$prod_name = $this->Productname->getProductInfoByID($row['Insight']['product_id']);
					$final_result[$i]['who_productName'] = $prod_name;
				}
				else
				{
					$final_result[$i]['who_productName'] = $row['Insight']['who_product_name_text'];
				}						
				# Retreiving practice area details by id
				if(isset($row['Insight']['practice_area_id']) && trim($row['Insight']['practice_area_id']) != "") 
				{
					$final_result[$i]['practice_area_id'] = $this->Practicearea->getPracticeareaNameById($row['Insight']['practice_area_id']);
				}
				
				
				# Set Contenttype Controller.
				App::import('Model', 'Replyresponse');
				# Create Contenttype model object.
				$this->Replyresponse = new Replyresponse();
				# Fetch recently added Response for Insight
				$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($row['Insight']['id']);
				
				if(count($RecentResponseInfo) > 0)
				{
					//$final_result[$i]['do_action'] = $RecentResponseInfo['Replyresponse']['reply_text'];
					$final_result[$i]['recent_reply'] = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
				}
				else
				{
					$final_result[$i]['recent_reply'] = "";
				}

				# Composing Issue Details
				if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) > 0) 
				{
					$final_result[$i]['issue_title'] = $row['Issue']['issue_title'] ;
				}
				/*else if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) == -1) 
				{
					$final_result[$i]['issue_title'] = "Not linked to an Issue" ;
				}*/
				else
				{
					$final_result[$i]['issue_title'] =  '';
				}

                                # Retreiving Market
				$marketInfo = $this->Market->getMarketById($row['Insight']['market_id']);
				$final_result[$i]['market_id'] = $marketInfo;	
				$i++;
			}
		}
		# Setting current url to come from edit mode.
		$this->Cookie->write('backUrl', $this->here);
		
		
		# Finally set the value of the results and total count for the view page
		if(!empty($final_result)) 
		{
			$this->set('final_result', $final_result);
			$this->set('total_count', count($totalresult));
		}	
    } # End function
    
    /**
	 * @author Sukhvir
	 * @created on 01/12/2013
	 * This functiion is used to display information for Edit Legal qa  records page. When the user clicks from the homepage Search Legal QA, it redirects to search page, if user search by Insight Id then it call this page function. Also under search result page, 'Click Here' link for each Legal Qa record leads to this page.
	 * The function fetches value based in the id passed in the url and retreive data for that insight id. When a user submit the request the fields data are update for that insight.
	 * @params id - Insight id
	 * @params flagSuccessMsg - Checks whether form is submitted with a flag value 1 or 0. If flagSuccessMsg=1 then displays msg-The records is saved otherwise no msg displayed.
	 */
    function legalqarecords($id='', $flagSuccessMsg=0)
    {
			$_SESSION['RedirectToHomePage'] = '';

			# Include Layout
			$this->layout='front';	
                    
                        /* fetaching insight types data for insight type dropdown form
                         * 
                         */
                        
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();			
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
			$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));			
			# Import Content Type model
			App::import('Model', 'Contenttype');
			# Create Content Type model object
			$this->Contenttype = new Contenttype();
			# Set Who Content Type Array
			$this->set('arrContentTypes',$this->Contenttype->getContentTypes());
			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();
			# Set Who Practicearea Array
			$this->set('arrPracticeArea',$this->Practicearea->getPracticeArea());
			# Import Statusinsight model
			App::import('Model', 'Statusinsight');
			# Create Statusinsight model object
			$this->Statusinsight = new Statusinsight();
			# Set Who Statusinsight Array
			$this->set('arrCurrentStatus',$this->Statusinsight->getStatusList(TRUE));		
			# Import Sellingobstacle model
			App::import('Model', 'Sellingobstacle');
			# Create Sellingobstacle model object
			$this->Sellingobstacle = new Sellingobstacle();
			# Set Sellingobstacle Array
			$this->set('arrSellingObstacles',$this->Sellingobstacle->getSellingObstacle());
			
			# Set Who Market Array
			# Import Market model
			App::import('Model', 'Market');
			# Create Sellingobstacle model object
			$this->Market = new Market();
			$this->set('arrWhoMarket',$this->Market->getMarkets());		
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();
			$this->set('arrProductFamilynames',$this->Productfamilyname->getProductFamilyNames());		
			# Set model competitorname.
			App::import('Model', 'Competitorname');
			$this->Competitorname = new Competitorname(); // Object
			# Import Insighttype model
			App::import('Model', 'Insighttype');
			$this->Insighttype = new Insighttype();
			$arrinsighttype = $this->Insighttype->returnStaticData(TRUE);
			foreach($arrinsighttype as $key=>$value) 
			{
				$arr[0] = "";		
				$arr[$key] = $value;			
			}
			$arrinsighttype[] = $arr;
			$this->set('arrinsighttype', $arrinsighttype[1]);
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Statusinsight model object
			$this->Pilotgroup = new Pilotgroup();
			# Set Who Pilotgroup Array
			//$this->set('arrDelegatedTo',$this->Pilotgroup->getPilotGroups(TRUE));				
			$this->set('arrDelegatedTo',$this->Pilotgroup->getPilotGroupsSME(TRUE));				
			if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
				$this->set('successDivSave','block');
				
			# Import Replyresponse model
			App::import('Model', 'Replyresponse');
			# Create Replyresponse model object
			$this->Replyresponse = new Replyresponse();
			
                        #set log insight value 
                        $this->Insight->flag_logtype = "I";
                        
			# Set id value for edit.
			$this->set('id',$id);
			$this->Insight->id = $id;
			$this->set('edit_flag', "");
			# Set Product Array. Check whether value is submitted from the form. The nested If next validate the mandatory form value. Once validated it checks each individual form value and store it in an array $arrProductSaveData which finally save all the form values into the database.
			if(isset($this->data) && !empty($this->data))
			{	
				$insight_status_changed	= false;
				$Ownership_taken = false;
				$insight_delegated_to = false;
				# Set form data values to user defined array.
				$arrProductSaveData = $this->data;
				//echo "<pre>"; print_r($arrProductSaveData); die;
				# Set flag for edit mode.
				$this->set('edit_flag', 'edit');				
				if($this->serverEditValidate($arrProductSaveData))
				{					
					# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();	
					# verify firm id and save id else save name.
					if(isset($arrProductSaveData['Product']['ownership_taken']) && $arrProductSaveData['Product']['ownership_taken'] !='')
					{
						$Ownership_taken = true;
					}
					if(isset($arrProductSaveData['Firm']['what_firm_name']))
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrProductSaveData['Firm']['what_firm_name']);						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrProductSaveData['Product']['what_firm_name'] = $firmParentID;
						else
							$arrProductSaveData['Product']['what_firm_name_text'] = $arrProductSaveData['Firm']['what_firm_name'];
					}					
					# verify Product Family id and save id .
					if(isset($arrProductSaveData['Productfamilyname']['who_product_family_name']) && trim($arrProductSaveData['Productfamilyname']['who_product_family_name']) >= 0)
					{
							$productFamilyNameID = $arrProductSaveData['Productfamilyname']['who_product_family_name'];
							$arrProductSaveData['Product']['product_family_id'] = $productFamilyNameID;
					}						
					# verify Product id and save id or who_product_name_text.
					if(isset($this->data['Productname']['who_product_name']) )
					{
						# Get product name key if exists.	
						$productNameID = $this->processProductNameExistance($arrProductSaveData['Productname']['who_product_name']);
						# Set Product name Field Text value from filled autosearch field.
						if(isset($productNameID) && $productNameID > 0) 
						{
							$arrProductSaveData['Product']['product_id'] = $productNameID;
							$arrProductSaveData['Product']['who_product_name_text'] = "";
						}
						else 
						{
							$arrProductSaveData['Product']['who_product_name_text'] = $arrProductSaveData['Productname']['who_product_name'];
							$arrProductSaveData['Product']['product_id'] = "";
						}
					}
					#Save User Id of the current user into the insights table.
					$arrProductSaveData['Product']['user_id'] = $this->Insight->getCreatedById($arrProductSaveData['Product']['id']);
					#Check if attachment is there.
					if (isset($arrProductSaveData['ProductAttachment']['attachment_name']['name']) && !empty($arrProductSaveData['ProductAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrProductSaveData['ProductAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrProductSaveData['ProductAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrProductSaveData['ProductAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
							if($this->utility->uploadAttachment($arrProductSaveData['ProductAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,PRODUCT_ATTACHMENT_UPLOAD_PATH))
							{
								# Set old file to be removed.
								$file = new File(PRODUCT_ATTACHMENT_UPLOAD_PATH . '/' . $arrProductSaveData['ProductAttachment']['old_attachment_name']);
								# Remove file.
								$file->delete();
								
								# If file exists physically then only file name will be saved in database.
								$filename = ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/'.$attachmentNewName;
								if(file_exists($filename)) 
								{								
									# Save Attachment New Name into database.
									$arrProductSaveData['Product']['attachment_name'] = $attachmentNewName;
									# Save Attachment Original Name into database.
									$arrProductSaveData['Product']['attachment_real_name'] = $arrProductSaveData['ProductAttachment']['attachment_name']['name'];
								}
							}
						}
					}
					elseif(isset($arrProductSaveData['ProductAttachment']['old_attachment_name']))
					{
						# If file exists physically then only file name will be saved in database.
						$old_filename = ABSOLUTE_URL. "/".WEBSITE_FOLDER.'/app/webroot/files/product/'.$arrProductSaveData['ProductAttachment']['old_attachment_name'];
						if(file_exists($old_filename)) 
						{
							$arrProductSaveData['Product']['attachment_name'] = $arrProductSaveData['ProductAttachment']['old_attachment_name'];
						}
					}
					else
					{
						$arrProductSaveData['Product']['attachment_name'] = NULL;
					}
					# verify insight status if changed.
					if(isset($arrProductSaveData['Product']['insight_status']) && $arrProductSaveData['Product']['insight_status']> 0 )
					{
						$old_insight_status = $this->Session->read('old_insight_status');
						if($old_insight_status != $arrProductSaveData['Product']['insight_status'] )
						{
							$insight_status_changed = true;
						}
						#updated date
						$arrProductSaveData['Product']['date_updated'] = date('Y-m-d H:i:s', time());
					}
					# verify Delegated to if changed.
					if(isset($arrProductSaveData['Product']['deligated_to']) && $arrProductSaveData['Product']['deligated_to'] > 0)
					{
						$old_deligated_to = $this->Session->read('old_deligated_to');
						if($old_deligated_to != $arrProductSaveData['Product']['deligated_to'] )
						{
							$insight_delegated_to = true;
							$arrProductSaveData['Product']['delegation_confirmed'] = 'N';
						}
					}
					#data updated by
					$arrProductSaveData['Product']['updated_by'] = $this->Session->read('current_user_id');						
					if(isset($arrProductSaveData['Firm']['what_firm_name']))
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrProductSaveData['Firm']['what_firm_name']);						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0) 
						{
							$arrProductSaveData['Product']['what_firm_name'] = $firmParentID;
							$arrProductSaveData['Product']['what_firm_name_text'] = "";
						}
						else 
						{
							$arrProductSaveData['Product']['what_firm_name_text'] = $arrProductSaveData['Firm']['what_firm_name'];
							$arrProductSaveData['Product']['what_firm_name'] = "";
						}
					}
					# verify competitor id and save id else save name.
					if(isset($arrProductSaveData['Competitorname']['who_competitor_name']))
					{
						# Verify if a firm with this parent_id exists in the database.
						$competitorID = $this->processCompetitorExistance($arrProductSaveData['Competitorname']['who_competitor_name']);
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($competitorID) && $competitorID > 0) 
						{
							$arrProductSaveData['Product']['competitor_id'] = $competitorID;
							$arrProductSaveData['Product']['who_competitor_name_text'] = "";
						}
						else 
						{
							$arrProductSaveData['Product']['who_competitor_name_text'] = $arrProductSaveData['Competitorname']['who_competitor_name'];
							$arrProductSaveData['Product']['competitor_id'] = "";
						}
					}
					#Code by Pragya Dave - fixed special characters edit (08/02/2011)
					if(isset($arrProductSaveData['Product']['insight_summary']) && trim($arrProductSaveData['Product']['insight_summary']) != '') 
					{
						$arrProductSaveData['Product']['insight_summary'] = $this->utility->parseString($arrProductSaveData['Product']['insight_summary']);
				    }
					//$arrProductSaveData['Product']['do_action'] = $this->utility->parseString($arrProductSaveData['Product']['do_action']);
					#Verify if there is no error.
					if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
					{
							
									//		echo "<pre>"; print_R($arrProductSaveData['Product']);die;


						#Save Product Insight into database.
						$this->Insight->save($arrProductSaveData['Product']);	
						
						#Send E-Mail
						
						# If Status and Delegated to of Insight changes (both fields) then only delegation email will be sent. Status mail will not be sent. In delegation mail status will be removed if value of both fields changes.
						$showStatusInDelegationMail = true;
						
						# Send Insight Status Change Email.
						# Status notification Email will be sent only if Status of Insight is changed. If Status of Insight and Delegated To both fields are changes then Status mail will not be sent.
						$status_mail_send = false;
						if($insight_status_changed && !$insight_delegated_to)	
						{
							$this->sendstatusmailtocontributorlegalqa($id);							
							$status_mail_send = true;
						}
						
						if($insight_status_changed && $insight_delegated_to)
						{
							$showStatusInDelegationMail = false;
						}
						
						# If delegate to field is changed then mail will be sent to SME
						if($insight_delegated_to)
						{
							$this->sendblankdelegeatedmailtosmelegalqa($id, $showStatusInDelegationMail);	
						}
													
						$send_reply_mail = $this->Session->read('send_reply_mail');
						
						/*	
						if reply added to the insight but status is not changed, then send mail for new comment added notification.
						*/
						if($send_reply_mail && !$insight_status_changed) 
						{ 
							$loggedIn_user_role = $this->Session->read('current_user_role');
							//if($loggedIn_user_role == '' || $loggedIn_user_role == 'S'){
							
								# Set Contenttype Controller.
								App::import('Model', 'Replyresponse');
								# Create Contenttype model object.
								$this->Replyresponse = new Replyresponse();
								# Fetch recently added Response for Insight
								$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($id);
								
								if(count($RecentResponseInfo) > 0)
								{
									$recent_reply = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
								}
								else
								{
									$recent_reply = "";
								}
							
								$this->sendcommentmailtosmelegalqa($id, $recent_reply, $loggedIn_user_role);
							//}
							$this->Session->write('send_reply_mail', false);
						}
						/*	
						if reply added to the insight and status is changed, then send mail for change in status notification.
						OR
						if reply not added to the insight and status is changed, then send mail for change in status notification.
						*/
						else if(!$status_mail_send && (($send_reply_mail && $insight_status_changed) || (!$send_reply_mail && $insight_status_changed && !$insight_delegated_to)))
						{
							$this->sendstatusmailtocontributorlegalqa($id);
						}
                                                # Redirect if save is successful.
						$this->redirect(SITE_URL . '/products/legalqarecords/'.$id.'/1');
					}
			}
		}	
		else 
    	{
                    # Reading insight record on the basis of $id.
                  
                    // $this->data =$this->Insight->read(); (It is back code ) adding extra condition for checking request from legal q & a 
                      
                       $this->data = $this->Insight->find('all', array('conditions' => array('Insight.id =' => $id,'AND'=>array('Insight.flag_logtype ='=>'L'))));
                       $this->data =  $this->data[0];
                     	
                      # Check condition in case of if record not in insight 
                       if(empty($this->data)){
                           $this->redirect(SITE_URL.'/'.strtolower('product').'s/legalqasearch/'.$id.'/notfound');
                       }
                      
			

			# Import Product model
			$this->data['Product']['what_how_come'] = $this->data['Insight']['what_how_come'];
			$this->data['Product']['what_source_name'] = $this->data['Insight']['what_source_name'];
			$this->data['Product']['insight_summary'] = $this->utility->parseString($this->data['Insight']['insight_summary']);
			$this->data['Product']['customer_pain_points'] = ($this->data['Insight']['customer_pain_points']=='')?'TBC':$this->utility->parseString($this->data['Insight']['customer_pain_points']);
			$this->data['Product']['recommended_actions'] = $this->utility->parseString($this->data['Insight']['recommended_actions']);
			$this->data['Product']['relates_product_family_id'] = $this->data['Insight']['relates_product_family_id'];
			$this->data['Product']['practice_area_id'] = $this->data['Insight']['practice_area_id'];
			$this->data['Product']['do_action'] = $this->data['Insight']['do_action'];			
			$this->data['Product']['respons_sent_to_customer'] = $this->data['Insight']['respons_sent_to_customer'];			
			
			if(isset($this->data['Insight']['product_family_id']) && is_numeric(intval($this->data['Insight']['product_family_id'])))
			{
				# Import Productfamilyname model
				App::import('Model', 'Productfamilyname');
				# Create Productfamilyname model object
				$this->Productfamilyname = new Productfamilyname();
				# Fetch product famliy name by Id
				$productFamilyNameResultArr = $this->Productfamilyname->getProductFamilyInfoById($this->data['Insight']['product_family_id']);
				$this->data['Productfamilyname']['product_family_id'] = $productFamilyNameResultArr['Productfamilyname']['family_name'];
			}
			else
			{
				$this->data['Productfamilyname']['product_family_id'] = $this->data['Insight']['who_product_family_name_text'];
			}
			if(isset($this->data['Insight']['product_id']) && is_numeric(intval($this->data['Insight']['product_id'])))
			{
				# Import Productname model
				App::import('Model', 'Productname');
				# Create Productname model object
				$this->Productname = new Productname();
				# Fetch product name by Id
				$productNameValue = $this->Productname->getProductInfoByID($this->data['Insight']['product_id']);
				# Set value for product name.
				$this->data['Productfamilyname']['product_id'] = $productNameValue;
			}
			else
			{
				$this->data['Productfamilyname']['product_id'] = $this->data['Insight']['who_product_name_text'];
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
			
			# Fetch Response Replies by Insight Id
			$productReplyValue = $this->Replyresponse->getResponseReplies($this->data['Insight']['id']);
			$this->set('productReplyValue', $productReplyValue);
						
			# Set variable values for view.
			$this->set('flag_mobile', $this->data['Insight']['flag_mobile']); 
			$this->set('what_how_come', $this->data['Insight']['what_how_come']); 
			$this->set('relates_content_type_label', $this->data['Insight']['content_type_id']);
			$this->set('relates_practice_area_label', $this->data['Insight']['practice_area_id']);
			$this->set('do_action_dummy', $this->data['Insight']['do_action']);
			$this->set('attachment_real_name', $this->data['Insight']['attachment_real_name']);
			$this->set('attachment_name', $this->data['Insight']['attachment_name']);
			$this->set('current_status_label', $this->data['Insight']['insight_status']);
			$this->set('deligated_to_selected', $this->data['Insight']['deligated_to']);
			$this->set('insight_summary', $this->data['Product']['insight_summary']);
			//$this->set('selected_product_ares_id', $this->data['Insight']['product_area_id']);
			$this->set('selected_product_family_id', $this->data['Insight']['product_family_id']);
			$this->set('selected_issue_field', $this->data['Insight']['issue_field']);
			$this->set('issue_description', $this->data['Issue']['issue_description']);
			$this->Session->write('old_insight_status', $this->data['Insight']['insight_status']);
			$this->Session->write('old_deligated_to', $this->data['Insight']['deligated_to']);
			$this->set('delegation_confirmed', $this->data['Insight']['delegation_confirmed']);
			$this->set('respons_sent_to_customer', $this->data['Insight']['respons_sent_to_customer']);

			# Import Issue model
			App::import('Model', 'Issue');
			# Create Issue model object
			$this->Issue = new Issue();
			# Fetch product name by Id
			
			# Get issues for matching combination of Product Family Name, Practice Area and Selling Obstacles.
			$productIssueValue = $this->Issue->getIssuesForCombination($this->data['Insight']['product_family_id'], $this->data['Insight']['practice_area_id'], $this->data['Insight']['selling_obstacle_id']);

			$arrIssueExist = array();
			$arr1[0] = "";
			
			//$arr1[-1] = "Not linked to an Issue";
			if(count($productIssueValue) > 0)
			{
				foreach($productIssueValue as $key=>$value) 
				{
					$key = $value['Issue']['id'];
					$arr1['Most Relevant Issues'][$key] = $value['Issue']['issue_title'];
					$arrIssueExist[] = $key;
				}
			}
			if(count($arrIssueExist)> 0){
				$IssuesToExclude = implode(",", array_unique($arrIssueExist));
			}

			# Get issues for matching Product Family Name but exclude the previously fetched records.
			$arrProductFamily = $this->Issue->getIssuesForProductFamily($this->data['Insight']['product_family_id'], $IssuesToExclude);
			if(count($arrProductFamily) > 0)
			{
				foreach($arrProductFamily as $key=>$value) 
				{
					$key = $value['Issue']['id'];
					//$arr1[$key] = $value['Issue']['issue_title'];
					$arr1['Same Product Family'][$key] = $value['Issue']['issue_title'];
					$arrIssueExist[] = $key;
				}
			}
			if(count($arrIssueExist)> 0){
				$IssuesToExclude = implode(",", array_unique($arrIssueExist));
			}
			
			$this->set('arrissue', $arr1);
			
			if( $this->data['Insight']['product_id'] > 0)
			{
				$productInfo = $this->Productname->getProductInfoByID( $this->data['Insight']['product_id']);
			}
			else
			{
				$productInfo = $this->data['Insight']['who_product_name_text'];
			}
			$this->set('selected_product_id', $productInfo);
			$this->set("selected_market_id", $this->data['Insight']['market_id']);
			$this->set("selected_insight_type", ucfirst(strtolower($this->data['Insight']['what_insight_type'])));	
			$this->set('currentSellingObstacle', $this->data['Insight']['selling_obstacle_id']);
			$this->set('contact_name', $this->data['Insight']['who_contact_name']);
			$competitorInfo = $this->Competitorname->getCompetitorName($this->data['Insight']['competitor_id']);
			if($competitorInfo > 0)
			{
				$competitorInfo = $competitorInfo['Competitorname']['competitor_name'];
			}
			else
			{
				$competitorInfo = $this->data['Insight']['who_competitor_name_text'];
			}
			$this->set('currentCompetitorid', $competitorInfo);
			$this->set('who_contact_role', $this->data['Insight']['who_contact_role']);
			$this->set( "created_by",$this->Pilotgroup->getPilotgroupNameByID($this->data['Insight']['user_id']));
			list($y,$m,$d) =split('[- ]',$this->data['Insight']['date_submitted']);
			$cdate =  date('dS M Y', mktime(0, 0, 0, $m, $d, $y));
			$this->set( "created_date",$cdate);
			$this->set("submitted_date",$this->data['Insight']['date_submitted']);			
			if($this->data['Insight']['what_firm_name'] > 0)
			{
				$firmInfo = $this->Firm->getFirmInfoByID($this->data['Insight']['what_firm_name']);
			}
			else
			{
				$firmInfo = $this->data['Insight']['what_firm_name'];
			}
			$this->set('currentOrganisationid', $firmInfo);
			# URL to go back to search result page.
			$this->set('backUrl', $this->Cookie->read('backUrl'));			
    	}
    } # End function  
    
    function legalqaexporttoexcel($type = '')
	{
		$this->layout="empty";
		if(!isset($type) || trim($type) == "") 
		{
			$type =	$this->Session->read('exportType');
		}
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
		# Set Productname Controller.
		App::import('Model', 'Productname');
		# Create Productname model object.
		$this->Productname = new Productname();		
		# Set Contenttype Controller.
		App::import('Model', 'Contenttype');
		# Create Contenttype model object.
		$this->Contenttype = new Contenttype();
		# Set model competitorname.
		App::import('Model', 'Competitorname');
		$this->Competitorname = new Competitorname(); // Object
		# Set model Market.
		App::import('Model', 'Market');
		$this->Market = new Market(); // Object
		# Import Statusinsight model
		App::import('Model', 'Statusinsight');
		# Create Statusinsight model object
		$this->Statusinsight = new Statusinsight();
		# Import Sellingobstacle model
                App::import('Model', 'Sellingobstacle');
                # Create Sellingobstacle model object
		$this->Sellingobstacle = new Sellingobstacle();		
		
		# Find data based on the search criteria
		$result = $this->Insight->find('all', array('conditions' => unserialize($this->Session->read('conditionsArr')), 'order' => 'Insight.date_submitted DESC'));
		# Set loop variable
		$i = 0;
		# Processing result.
		if(!empty($result))
		{
			$i = 0;
			foreach($result as $row)
			{
                                # Set all field values in an array to form search records
				$final_result[$i] = $row['Insight'];
				$final_result[$i]['what_insight_type'] = $row['Insight']['what_insight_type'];
				$final_result[$i]['userSubmittedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['user_id']);
				$final_result[$i]['userUpdatedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['updated_by']);				
				$final_result[$i]['currentStatus'] = $this->Statusinsight->getStatusById($row['Insight']['insight_status']);				
				$final_result[$i]['delegatedTo'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['deligated_to']);							
				if(trim($final_result[$i]['what_how_come']) == "0")
				{
					$final_result[$i]['what_how_come'] = "";	
				}
				# Composing firm name on basis of Id if any else direct name from db field.
				if(isset($row['Insight']['what_firm_name']) && trim($row['Insight']['what_firm_name']) != "") 
				{
					$final_result[$i]['what_firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
				}
				else 
				{
					$final_result[$i]['what_firmName'] = $final_result[$i]['what_firm_name_text'];
				}
				# Composing whose firm name on basis of Id if any else direct name from db field.
				if($type == 'customer') 
				{
					if(isset($row['Insight']['who_firm_name']) && trim($row['Insight']['who_firm_name']) != "") 
					{
						$final_result[$i]['who_firmName'] = $this->processFirmId($row['Insight']['who_firm_name']);
					}
					else 
					{
						$final_result[$i]['who_firmName'] = $final_result[$i]['who_firm_name_text'];
					}
					if(isset($row['Insight']['relates_competitor_id']) && $row['Insight']['relates_competitor_id']>0) 
					{
						$compNameArr = $this->Competitorname->getCompetitorName($row['Insight']['relates_competitor_id']);
						$final_result[$i]['relates_competitor_id'] = $compNameArr['Competitorname']['competitor_name'];
					}
				}				
				#in case of type product (product family name, product name & content type).
				if($type == 'product') 
				{
					if(isset($row['Insight']['product_family_id']) && trim($row['Insight']['product_family_id']) > 0) 
					{
						$prod_familiy_name_arr = array();
						$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['product_family_id']);
						$final_result[$i]['who_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
					}
					else 
					{
						$final_result[$i]['who_product_familyName'] = '';
					}	
					# Check for Id if not then pick from text field.
					if(isset($row['Insight']['product_id']) && $row['Insight']['product_id']>0) 
					{
						$final_result[$i]['who_productName'] = $this->Productname->getProductNameByProdId($row['Insight']['product_id']);
					}
					else 
					{
						$final_result[$i]['who_productName'] = $row['Insight']['who_product_name_text'];
					}
                                        # no need of conent type 
//					$relates_content_arr = array();
//					$relates_content_arr = $this->Contenttype->getContentTypeById($row['Insight']['content_type_id']);
//					$final_result[$i]['relates_contentType'] = $relates_content_arr['Contenttype']['content_type'];
				}
				# Fetch all values i.e. Competitorname, Product Family name	, Market id, Practice area, Selling obstacles, Product area, Contact name / Publication, Contact Name / role
				$competitorNameArr = $this->Competitorname->getCompetitorName($final_result[$i]['competitor_id']);
				$final_result[$i]['who_competitorName'] = $competitorNameArr['Competitorname']['competitor_name'];
				# Composing product family name.
				if(isset($row['Insight']['relates_product_family_id']) && trim($row['Insight']['relates_product_family_id'])>0) 
				{
					$prod_familiy_name_arr = array();
					$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['relates_product_family_id']);
					$final_result[$i]['relates_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
				}
				else
				{
					$final_result[$i]['relates_product_familyName'] = "";
				}			
				# Set Market id if not null or 0				
				if(isset($row['Insight']['market_id']) && $row['Insight']['market_id']>0) 
				{
					$final_result[$i]['who_marketName'] = $this->Market->getMarketById($row['Insight']['market_id']);
				}
				else
				{
					$final_result[$i]['who_marketName'] = "";
				}	
				# Set Practice area id if not null or 0
				if(isset($row['Insight']['practice_area_id']) && $row['Insight']['practice_area_id']>0) 
				{
					$practiceArea = $this->Practicearea->getPracticeareaNameById($row['Insight']['practice_area_id']);
					$final_result[$i]['practice_area_id'] = $practiceArea;
				}
				else 
				{
					$final_result[$i]['practice_area_id'] = "";
				}
				# Set Selling obstacles id if not null or 0
				if(isset($row['Insight']['selling_obstacle_id']) && $row['Insight']['selling_obstacle_id']>0) 
				{
					$selling_obstacleInfo = $this->Sellingobstacle->getSellingobstacleNameById($row['Insight']['selling_obstacle_id']);
					$final_result[$i]['selling_obstacle_id'] = $selling_obstacleInfo;
				}
				else 
				{
					$final_result[$i]['selling_obstacle_id'] = "";
				}
				
				/**		Latest Reply for Insight in excel start		**/
				# Set Contenttype Controller.
				App::import('Model', 'Replyresponse');
				# Create Contenttype model object.
				$this->Replyresponse = new Replyresponse();
				# Fetch recently added Response for Insight
				$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($row['Insight']['id']);
				
				if(count($RecentResponseInfo) > 0)
				{
					//$final_result[$i]['do_action'] = $RecentResponseInfo['Replyresponse']['reply_text'];
					$final_result[$i]['recent_reply'] = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
				}
				else
				{
					$final_result[$i]['recent_reply'] = "";
				}
				/**		Latest Reply for Insight in excel end 	**/
				
				/**		Issue for Insight in excel start		**/
				# Composing Issue Details
				if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) > 0) 
				{
					$final_result[$i]['issue_title'] = $row['Issue']['issue_title'] ;
				}
				/*else if(isset($row['Insight']['issue_field']) && trim($row['Insight']['issue_field']) == -1) 
				{
					$final_result[$i]['issue_title'] = "Not linked to an Issue" ;
				}*/
				else
				{
					$final_result[$i]['issue_title'] =  '';
				}
				/**		Issue for Insight in excel end		**/
				
				# Set who_contact_name to the array index
				$final_result[$i]['who_contact_name'] = $row['Insight']['who_contact_name'];
				# Set who_contact_role to the array index
				$final_result[$i]['who_contact_role'] = $row['Insight']['who_contact_role'];
				                              
                               $i++;
			}
		}
                
                $i=0;
		#Set excel file header names
		if($type == 'product') 
		{
                              $arrExcelInfo[0] = array( 'Id',				
										'Created On',
										'Created By',
										'Date Status Changed',
										'Current Status',
										'Current owner',										
										'Origin of Question',									
										'Organisation Name',
										'Contact Name',
										'LexisNexis Product Family Name',
										'LexisNexis Product Name',
										'Practice Area',
										'Market Segment',																				
										'Response to Feedback',
										'Issue',
										'Added via Mobile',
										'Question?',
										);
                                          
		} 
		else if($type == 'competitor') 
		{
                                   $arrExcelInfo[0] = array(                   'Id',
										'Created On',
										'Created By',
										'Date Status Changed',
										'Current Status',
										'Current owner',
                                                                                'Origin of Question',
                                                                                'Firm Name',
                                                                                'LexisNexis Product Family Name',
										'Practice Area',
										'Response to Feedback',
										'Issue',
										'Added via Mobile',
										'Question?',
										);
                         
		} 
		else if($type == 'customer') 
		{
			      $arrExcelInfo[0] = array(                         'Id',									'Created On',
										'Created By',
										'Date Status Changed',
										'Current Status',
										'Current owner',
										'Origin of Question',
										'Firm Name (What)',
										'Firm Name (Who)',										
										'Account Number',
										'Contact Name',
										'LexisNexis Product Family Name',
										'Practice Area',
										'Response to Feedback',
										'Issue',
										'Added via Mobile',
										'Question?',
										);	
                      
		} 
		else if($type == 'market') 
		{
                               $arrExcelInfo[0] = array(                       'Id',
										'Created On',
										'Created By',
										'Date Status Changed',
										'Current Status',
										'Current owner',
										'Origin of Question',
                                                                                'Firm Name',
										'Market',
										'LexisNexis Product Family Name',
										'Practice Area',
										'Response to Feedback',
										'Issue',
										'Added via Mobile',
										'Question?',
										);		
                            
                }	
		$arrExcelInfo[1] = array();		
		App::import('Vendor', 'excelVendor', array('file' =>'excel'.DS.'class.export_excel.php'));		
		$fileName = $type."_export.xls";
                #create the instance of the exportexcel format
		$excel_obj = new ExportExcel("$fileName");
               
              foreach ($final_result as $keySet => $valueSet)
		{
				$arrExcelInfo[1][$i] = $this->legalqa_add_export_array($valueSet, $type);
                               
		
				$i++;
		}
                 
 		#setting the values of the headers and data of the excel file 
		#and these values comes from the other file which file shows the data
		$excel_obj->setHeadersAndValues($arrExcelInfo[0],$arrExcelInfo[1]); 		
		#now generate the excel file with the data and headers set
		$excel_obj->GenerateExcelFile();
                exit;
	} # End function
        
        function legalqa_add_export_array($valueSet, $type) 
	{
                # In case blank date.
		$valueSet['date_updated'] = (trim($valueSet['date_updated']) != "")?date('dS M, Y', strtotime($valueSet['date_updated'])):"";
		# Mapping common fields.
                $arr[0] = $valueSet['id'];
		$arr[1] = date('dS M, Y', strtotime($valueSet['date_submitted']));
		$arr[2] = $valueSet['userSubmittedName'];
		$arr[3] = $valueSet['date_updated'];
		$arr[4] = $valueSet['currentStatus'];
		$arr[5] = $valueSet['delegatedTo'];
                $arr[6] = $valueSet['what_how_come'];			
		$arr[7] = ucwords(strtolower($valueSet['what_firmName']));
		# Mapping related fields
		if($type == 'product') 
		{
                            $arr[8] = $valueSet['who_contact_name'];
                            $arr[9] = ucwords(strtolower($valueSet['who_product_familyName']));
                            $arr[10] = ucwords(strtolower($valueSet['who_productName']));
                            //$arr[13] = $valueSet['relates_contentType'];		
                            $arr[11] = $valueSet['practice_area_id'];
                            $arr[12] = $valueSet['who_marketName'];

                            //$arr[20] = $valueSet['do_action'];		
                            $arr[13] = $valueSet['recent_reply'];		
                            $arr[14] = $valueSet['issue_title'];		
                            $arr[15] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
                            $arr[16] = $valueSet['insight_summary'];
                            
		}
		else if($type == 'competitor')
		{       
                       	$arr[8] = $valueSet['relates_product_familyName'];
			$arr[9] = $valueSet['practice_area_id'];
			//$arr[13] = $valueSet['do_action'];
			$arr[10] = $valueSet['recent_reply'];
			$arr[11] = $valueSet['issue_title'];	
			$arr[12] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
			$arr[13] = $valueSet['insight_summary'];
			
                      
		}
		else if($type == 'customer') 
		{   
                            $arr[8] = $valueSet['who_firmName'];
                            $arr[9] = $valueSet['who_account_no'];
                            $arr[10] = $valueSet['who_contact_name'];
                            $arr[11] = $valueSet['relates_product_familyName'];
                            $arr[12] = $valueSet['practice_area_id'];				
                            $arr[13] = $valueSet['recent_reply'];
                            $arr[14] = $valueSet['issue_title'];
                            $arr[15] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
                            $arr[16] = $valueSet['insight_summary'];
                            $arr[17] = $valueSet['recommended_actions'];	
                     
		}
		else if($type == 'market') 
		{ 
                                $arr[8] = $valueSet['who_marketName'];
                                $arr[9] = $valueSet['relates_product_familyName'];
                                $arr[10] = $valueSet['practice_area_id'];				
                                $arr[11] = $valueSet['recent_reply'];
                                $arr[12] = $valueSet['issue_title'];
                                $arr[13] = ($valueSet['flag_mobile']=='Y')?'Yes':'No';
                                $arr[14] = $valueSet['insight_summary'];
                               
		}	
               return $arr;
	} # End function
        
        /**
	 * @author Sukhvir Singh
	 * @created on 12/12/2013
     * This function confirms that the User trying to access the legalqa is a delegated SME or the Contributor of the legalqa.
	 * The function is called when SME try to edit the legalqa details from search result page.
     * @param Integer insight_id
     */
	function legalqaconfirmationpage($insight_id='', $delegated_SME_Id='')
	{	
		$this->layout='front_pop';
		$this->set('delegated_SME_Id', $delegated_SME_Id);
		$this->set('insight_id', $insight_id);
	}

	/**
	 * @author Sukhvir Singh
	 * @created on 12/12/2013
     * This function confirms that the User trying to access the legalqa is a delegated SME or the Contributor of the legalqa.
	 * The function is called when SME try to edit the legalqa details from search result page.
     * @param Integer insight_id
     */
	function legalqaclaiminsight($insight_id='')
	{	
		$this->layout='front_pop';
		$this->set('insight_id', $insight_id);
	}

	/**
	 * @author Sukhvir Singh
	 * @created on 12/12/2013
     * This function confirms that the User trying to access the insight is a delegated SME or the Contributor of the insight.
	 * The function is called when SME try to edit the insight details from search result page.
     * @param Integer insight_id
     */
	function legalqaclaimpath($insight_id='', $delegate_id='')
	{	

		# Import Insight model
		App::import('Model', 'Insight');
		# Create Insight model object
		$this->Insight = new Insight();	
		$arrProductSaveData['Product']['id']					= $insight_id;
		$arrProductSaveData['Product']['deligated_to']			= $delegate_id;
		$arrProductSaveData['Product']['delegation_confirmed']	= 'Y';
		

		# Saving data into Insight table for the insight
		$arrData = $this->Insight->save($arrProductSaveData['Product']);
		
		# Sending Email to Moderator.
		$this->send_insight_claim_email($insight_id);
		
		echo "success";die;
	}
/**
	 * @author Sukhvir Singh
	 * @created on 12/12/2013
     * This function send the mail to moderator when SME want to contact the Moderator.
	 * The function is called when SME clicks the Contact Moderator link on Legal QA page.
     * @param Integer insight_id
     * @param Integer success flag
     */
	function legalqacontact($insight_id='', $flagSuccessMsg = 0)
	{	
		$this->layout='front_pop';
		$this->set('insight_id', $insight_id);

		# Call function to set default display value for error messages.
		$this->setMessageDivDefaultStatus();	
		
		# Set success Message.
		if(isset($flagSuccessMsg) && $flagSuccessMsg == 1)
    		$this->set('successDivSave','block');    	
		
		if(isset($this->data) && $this->data !='')
		{
			$arrProductData = $this->data;
			
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			$this->Insight->id = $arrProductData['Product']['insight_id'];
			
			# Reading insight record on the basis of $id.
			//$this->insight_data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();
	
			# Get Moderator Email Address.
			$arrModeratorAddress = $this->Pilotgroup->getModeratorEmailAddress();
			$moderator_email_address = "David.Coleman@lexisnexis.co.uk";
			
			# Import Insight model
			$insight_id		= $arrProductData['Product']['insight_id'];
			$email_subject 	= $arrProductData['Product']['subject'];
			$email_message 	= $arrProductData['Product']['message_text'];			
			//$sme_email_address = strtolower($this->Session->read('current_user_emailaddress'));
			
                        $sme_email_address = "David.Coleman@lexisnexis.co.uk";
			
			# Sending Email to Moderator.
			$this->sendcontactmailtomoderatorlegalqa($insight_id, $moderator_email_address, $email_subject, $email_message, $sme_email_address);
			$this->redirect(SITE_URL . '/products/legalqacontact/'.$insight_id.'/1');
		}
	}
        
        /**
	 * @author Sukhvir Singh
	 * @created on 13/12/2013
	 * This function send mail to Moderator when new Legal QA is added to the system.
	 * @param Integer $id (Insight_id)
	 * @param string $moderator_email_address
	 * Template 6
	 */
	function send_new_legalqa_mail_to_moderator($id='', $moderator_email_address='')
	{			
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			
			$array_insight = explode(" ", $insight_summary);
			//echo "<pre>";print_r($array_insight);echo "</pre>";die;
			
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}	
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/legalqarecords/'.$id);
                      
//                        $EmailTo = 	$moderator_email_address;
//			
//			$sme_email_address = strtolower($this->Session->read('current_user_emailaddress'));
//			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
                        
                        
                    /* email hard coded for testing purpose
                     * @author:sukhvir
                     */    
			$EmailTo = 	"David.Coleman@lexisnexis.co.uk";
			
			$sme_email_address = "David.Coleman@lexisnexis.co.uk";
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Subject field of the mail
				$this->Email->subject = "Feedback Tracker notification: Feedback Ref ".$id." (status: Awaiting delegation)"; 
				
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
				
			#sending type
				$this->Email->sendAs = 'html';
				
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_6';
				
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();
			}
		
	}	

	/**
	 * @author Sukhvir Singh
	 * @created on 13/12/2013
	 * This function send mail when Contributor will save the legal qa  but unfortunately User Id is NULL or Zero.
	 * @param Array $post
	 * Template ErrorMail
	 */
	function legalqa_send_error_mail($PostData, $current_user_name)
	{
			App::import('Model', 'Firm');
			# Create Insight model object
			$this->Firm = new Firm();			

			# Import Practicearea model
			App::import('Model', 'Practicearea');
			# Create Practicearea model object
			$this->Practicearea = new Practicearea();
			
			# Import Sellingobstacle model
			App::import('Model', 'Sellingobstacle');
			# Create Sellingobstacle model object
			$this->Sellingobstacle = new Sellingobstacle();			
			
			# Import Competitorname model
			App::import('Model', 'Competitorname');
    		# Create Competitorname model object
			$this->Competitorname = new Competitorname();	
			
			# Import Productfamilyname model
			App::import('Model', 'Productfamilyname');
			# Create Productfamilyname model object
			$this->Productfamilyname = new Productfamilyname();			
			
			# Set Productname Controller.
			App::import('Model', 'Productname');
			# Create Productname model object.
			$this->Productname = new Productname();				
			
			# Set Contenttype Controller.
			App::import('Model', 'Contenttype');
			# Create Contenttype model object.
			$this->Contenttype = new Contenttype();

			
			# Composing firm name on basis of Id if any else direct name from db field.
			if(isset($PostData['what_firm_name']) && trim($PostData['what_firm_name']) != "") 
			{
				$firmName = $this->processFirmId($PostData['what_firm_name']);
			}
			else {
				$firmName = $PostData['what_firm_name_text'];
			}

			# Composing Content type details by id.
			if(isset($PostData['content_type_id']) && trim($PostData['content_type_id']) != "") 
			{
				$prod_contenttype_arr = array();
				$prod_contenttype_arr = $this->Contenttype->getContentTypeById($PostData['content_type_id']);
				$contentType = $prod_contenttype_arr['Contenttype']['content_type'];
			}
			else 
				$contentType ="";

			# Composing product family name details by id
			if(isset($PostData['product_family_id']) && trim($PostData['product_family_id'])>0) 
			{
				$prod_familiy_name_arr = array();
				$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($PostData['product_family_id']);
				$product_familyName = $prod_familiy_name_arr['Productfamilyname']['family_name'];
			}
			else
			{
				$product_familyName = '';
			}

			# Composing product name details by id
			if(isset($PostData['product_id']) && trim($PostData['product_id'])>0) 
			{
				$productName = $this->Productname->getProductInfoByID($PostData['product_id']);
			}
			else
			{
				$productName = $PostData['who_product_name_text'];
			}						
			# Retreiving practice area details by id
			if(isset($PostData['practice_area_id']) && trim($PostData['practice_area_id']) != "") 
			{
				$practice_area = $this->Practicearea->getPracticeareaNameById($PostData['practice_area_id']);
			}

			# Verify if a competitorInfo exists in the database. If yes get competitor_id else get who_competitor_name_text
			if($PostData['competitor_id'] > 0)
			{
				$competitorInfo = $this->Competitorname->getCompetitorName($PostData['competitor_id']);
				if($competitorInfo > 0)
				{
					$competitor = $competitorInfo['Competitorname']['competitor_name'];
				}
			}
			else
			{
				$competitor = $PostData['who_competitor_name_text'];
			}
			
			# Retreiving Selling Obstacles.
			if($PostData['selling_obstacle_id'] > 0){
				$selling_obstacle = $this->Sellingobstacle->getSellingobstacleNameById($PostData['selling_obstacle_id']);
			}
			
			$insight_summary		= $PostData['insight_summary'];
			$customer_pain_points	= $PostData['customer_pain_points'];
			$recommended_actions	= $PostData['recommended_actions'];
			$what_how_come			= $PostData['what_how_come'];
			$who_contact_role		= $PostData['who_contact_role'];
			
			$this->set('current_user_name',		$current_user_name);
			$this->set('insight_summary',		$insight_summary);
			$this->set('customer_pain_points',	$customer_pain_points);
			$this->set('recommended_actions',	$recommended_actions);
			$this->set('what_how_come',			$what_how_come);
			$this->set('who_contact_role',		$who_contact_role);
			$this->set('selling_obstacle',		$selling_obstacle);
			$this->set('competitor',			$competitor);
			$this->set('practice_area',			$practice_area);
			$this->set('productName',			$productName);
			$this->set('product_familyName',	$product_familyName);
			$this->set('contentType',			$contentType);
			$this->set('firmName',				$firmName);
			

			if(strstr(SITE_URL, 'uat')){
				$Environment = "UAT Environment";
			}
			else if(strstr(SITE_URL, 'prod')){
				$Environment = "PRODUCTION  Environment";
			}
			else {
				$Environment = "Local Development Environment";
			}
			
			$this->set('Environment', $Environment);
			
			
			$EmailTo = 	LNG_UK_INSIGHT_ERROR_EMAIL;
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Subject field of the mail
				$this->Email->subject = "Error occured while adding insight."; 
				
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
				
			#sending type
				$this->Email->sendAs = 'html';
				
			#for layouts/email/default.ctp 
				$this->Email->template = 'errorMail';
			
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();
			}
	}	
        	/**
	 * @author Sukhvir Singh
	 * @created on 13/12/2013
     * This function send mail to contributor when Insight status change.
	 * @param Integer id (Insight_id) - Insight Id is passed for the information retrival
	 * Email Template 3 
     */
	function sendstatusmailtocontributorlegalqa($id = '')
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value for edit.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
			$contributor_user_name	= $this->data['Pilotgroup']['name'];
			$contributor_first_name	= $this->data['Pilotgroup']['first_name'];
			$contributor_sur_name	= $this->data['Pilotgroup']['sur_name'];
			$sme_name	 		= $this->data['Pilotgroup_D']['name'];
			//$sme_emailaddress 	= $this->data['Pilotgroup_D']['emailaddress'];
			//$sme_cc_emailaddress 	= $this->data['Pilotgroup_D']['cc_emailaddress'];
			
                        $sme_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$sme_cc_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}		
			
			
			# Set Contenttype Controller.
			App::import('Model', 'Replyresponse');
			# Create Contenttype model object.
			$this->Replyresponse = new Replyresponse();
			# Fetch recently added Response for Insight
			$RecentResponseInfo = $this->Replyresponse->getRecentResponseForInsight($id);
			//echo "<pre>";print_r($RecentResponseInfo);die;
			if(count($RecentResponseInfo) > 0)
			{
				$recent_reply = $RecentResponseInfo[0]['Replyresponse']['reply_text'];
			}
			else
			{
				$recent_reply = "";
			}
			
			$contributor_user_name = ($contributor_first_name!='')?trim($contributor_first_name.' '.$contributor_sur_name):$contributor_user_name;
			
			$this->set('contributor_name',$contributor_user_name);
			$this->set('insight_summary',$insight_summary);
			$this->set('recent_reply',$recent_reply);
			$this->set('insight_url', SITE_URL . '/products/legalqarecords/'.$id);
			
			$loggedIn_user_role = $this->Session->read('current_user_role');
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			$EmailTo = 	strtolower($this->data['Pilotgroup']['emailaddress']);
						
			$arrCc = array();
			if($sme_cc_emailaddress !='')
			{
				if(strstr($sme_cc_emailaddress, ';')){
					$pieces = explode(";", strtolower($sme_cc_emailaddress));
					$arrCc = $pieces;
				}
				else
				{
					$arrCc[] = strtolower($sme_cc_emailaddress);
				}
			}
			if($sme_emailaddress !=''){
				$arrCc[] = strtolower($sme_emailaddress);
			}
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Cc To field of the mail	
			$this->Email->cc = $arrCc;
			
			#Subject field of the mail
				$this->Email->subject = "Feedback Tracker notification: Feedback Ref ".$id." status changed (".$insight_status.")";
			
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			
			
			
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_3';	
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}
		
	}

	/**
	 * @author Sukhvir Singh
	 * @created on 13/12/2013
     * This function send mail to contributor when any comment is added to Legal QA.
	 * @param Integer id (Insight_id)
	 * Email Template 4 
     */
	function sendcommentmailtosmelegalqa($id = '', $comment_text = '', $commentPostedBy = '')
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value for edit.
			$this->set('id',$id);
			$this->set('comment_text',$comment_text);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>"; print_r($this->data);die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
			$sme_user_name	 	= $this->data['Pilotgroup_D']['name'];
			$sme_first_name		= $this->data['Pilotgroup_D']['first_name'];
			$sme_sur_name		= $this->data['Pilotgroup_D']['sur_name'];
			//$sme_emailaddress 	= $this->data['Pilotgroup_D']['emailaddress'];
			//$sme_cc_emailaddress 	= $this->data['Pilotgroup_D']['cc_emailaddress'];
			//$contributor_emailaddress 	= $this->data['Pilotgroup']['emailaddress'];
			
                        $sme_emailaddress 	="David.Coleman@lexisnexis.co.uk";
			$sme_cc_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$contributor_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			
                        $contributor_first_name 	= $this->data['Pilotgroup']['first_name'];
			$contributor_sur_name 		= $this->data['Pilotgroup']['sur_name'];
			$contributor_user_name 		= $this->data['Pilotgroup']['name'];
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/legalqarecords/'.$id);
			
			$loggedIn_user_role = $this->Session->read('current_user_role');
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			$arrCc = array();
			if($commentPostedBy == '' || $commentPostedBy == 'A'){	// If comment posted by Contributor OR Moderator then send email to SME
				
				$current_user_id = 	$this->Session->read('current_user_id');
				if($current_user_id == $this->data['Insight']['user_id'])	
				{
					$contributor_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;
					$EmailTo = 	strtolower($sme_emailaddress);
					$CC_Email = strtolower($contributor_emailaddress);
				}
				else{
					$contributor_name = ($contributor_first_name!='')?trim($contributor_first_name.' '.$contributor_sur_name):$contributor_user_name;	
					$EmailTo = 	strtolower($contributor_emailaddress);
					$CC_Email = strtolower($sme_emailaddress);
				}
				$this->set('sme_name',$contributor_name);
				
				//$sme_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;	
				//$this->set('sme_name',$sme_name);
				//$EmailTo = 	strtolower($sme_emailaddress);
				
				
				/*
				*	Adding SME Cc email addresses to Cc field.
				*/
				if($sme_cc_emailaddress !='')
				{
					if(strstr($sme_cc_emailaddress, ';')){
						$pieces = explode(";", strtolower($sme_cc_emailaddress));
						$arrCc = $pieces;
					}
					else
					{
						$arrCc[] = strtolower($sme_cc_emailaddress);
					}
				}
				/*
				*	Adding Contributor email addresses to Cc field.
				*/
				if($contributor_emailaddress !=''){
					$arrCc[] = $CC_Email;
					//$arrCc[] = strtolower($contributor_emailaddress);
				}
			}
			else if($commentPostedBy == "S")  // If comment posted by SME then send email to Contributor
			{
				// If SME is treated as Contributor and he addes any comment then mail should address to SME.
				$current_user_id = 	$this->Session->read('current_user_id');
				if($current_user_id == $this->data['Insight']['user_id'])	
				{
					$contributor_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;
					$EmailTo = 	strtolower($sme_emailaddress);
					$CC_Email = strtolower($contributor_emailaddress);
				}
				else{
					$contributor_name = ($contributor_first_name!='')?trim($contributor_first_name.' '.$contributor_sur_name):$contributor_user_name;	
					$EmailTo = 	strtolower($contributor_emailaddress);
					$CC_Email = strtolower($sme_emailaddress);
				}
				$this->set('sme_name',$contributor_name);
				//$EmailTo = 	strtolower($contributor_emailaddress);				
				
				/*
				*	Adding SME Cc email addresses to Cc field.
				*/
				if($sme_cc_emailaddress !='')
				{
					if(strstr($sme_cc_emailaddress, ';')){
						$pieces = explode(";", strtolower($sme_cc_emailaddress));
						$arrCc = $pieces;
					}
					else
					{
						$arrCc[] = strtolower($sme_cc_emailaddress);
					}
				}
				/*
				*	Adding SME email address to Cc field.
				*/
				if($sme_emailaddress !=''){
					//$arrCc[] = strtolower($sme_emailaddress);
					$arrCc[] = $CC_Email;
				}				
			}
	 			
			#To field of the mail
				$this->Email->to = $EmailTo;
			#Subject field of the mail
				$this->Email->subject = "Feedback Tracker: Feedback Ref ".$id." (status: comment added)";
			#Cc To field of the mail
				$this->Email->cc = $arrCc;
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_4';
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}
		
	}
	
	
	/**
	 * @author  Sukhvir Singh
	 * @created on 10/06/2011
     * This function send mail to contributor when ownership of Insight is taken by any SME.
	 * @param Integer id (Insight_id)
     */
	function sendownershipmailtocontributorlegalqa($id = '')
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
			$sme_name	 		= $this->data['Pilotgroup_D']['name'];
		//	$sme_emailaddress 	= $this->data['Pilotgroup_D']['emailaddress'];
			
                        $sme_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}		
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/legalqarecords/'.$id);
			$this->set('ownership_taken_by',$sme_name);
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			//$EmailTo = 	$this->data['Pilotgroup']['emailaddress'];
			
			$EmailTo = 	"David.Coleman@lexisnexis.co.uk";
			
			#To field of the mail
				$this->Email->to = $EmailTo;
			#Subject field of the mail
				$this->Email->subject = "Ownership of Feedback has been taken by SME."; 
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'insight_ownership_mail';
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}		
	}
	
	
	/**
	 * @author Sukhvir Singh
	 * @created on 13/12/2013
     * This function send mail to SME when moderator changes legal qa insight status from blank to delegated and Delegated To is not blank.
	 * @param Integer id (Insight_id)
	 * Template 1 & 2
     */
	function sendblankdelegeatedmailtosmelegalqa($id = '', $showStatusInDelegationMail)
	{
			# Import Insight model
			App::import('Model', 'Insight');
			# Create Insight model object
			$this->Insight = new Insight();	
			
			# Set id value.
			$this->set('id',$id);
			$this->Insight->id = $id;
	
			# Reading insight record on the basis of $id.
        	$this->data = $this->Insight->read();
			//echo "<pre>";print_r($this->data);echo "</pre>";die;
			$insight_summary 	= $this->data['Insight']['insight_summary'];
			$insight_status 	= $this->data['Statusinsight']['status'];
		
			$sme_user_name	 		= $this->data['Pilotgroup_D']['name'];
			$sme_first_name	 		= $this->data['Pilotgroup_D']['first_name'];
			$sme_sur_name	 		= $this->data['Pilotgroup_D']['sur_name'];
		//	$sme_emailaddress 	= $this->data['Pilotgroup_D']['emailaddress'];
		//	$sme_cc_emailaddress 	= $this->data['Pilotgroup_D']['cc_emailaddress'];
		//	$contributor_emailaddress 	= $this->data['Pilotgroup']['emailaddress'];
			
                        $sme_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$sme_cc_emailaddress 	= "David.Coleman@lexisnexis.co.uk";
			$contributor_emailaddress= "David.Coleman@lexisnexis.co.uk";
			
			$array_insight = explode(" ", $insight_summary);
			if (count($array_insight) <= WORD_COUNT_INSIGHT_SUMMARY)
			{
				$insight_summary = $insight_summary;
			}
			else
			{
				array_splice($array_insight, WORD_COUNT_INSIGHT_SUMMARY);
				$insight_summary = implode(" ", $array_insight)." ...";
			}	
			
			$sme_user_name = ($sme_first_name!='')?trim($sme_first_name.' '.$sme_sur_name):$sme_user_name;				
			
			$this->set('insight_summary',$insight_summary);			
			$this->set('insight_url', SITE_URL . '/products/legalqarecords/'.$id);
			$this->set('new_status',$insight_status);
			$this->set('delegated_to',$sme_user_name);
			
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			$EmailTo = 	$sme_emailaddress;
			
			$arrCc = array();			
			if($sme_cc_emailaddress !='')
			{
				if(strstr($sme_cc_emailaddress, ';')){
					$pieces = explode(";", strtolower($sme_cc_emailaddress));
					$arrCc = $pieces;
				}
				else
				{
					$arrCc[] = strtolower($sme_cc_emailaddress);
				}
			}
			$arrCc[] = strtolower($contributor_emailaddress);
		
			#To field of the mail
				$this->Email->to = $EmailTo;
			
			#Cc To field of the mail
			$this->Email->cc = $arrCc;
			$EmailSubject = "Feedback Tracker notification: Feedback Ref ".$id;
			if($showStatusInDelegationMail)
			{
				$EmailSubject = $EmailSubject . " (status: delegated)";
			}			
			
			#Subject field of the mail
				$this->Email->subject = $EmailSubject; 
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
			#sending type
				$this->Email->sendAs = 'html';
			#for layouts/email/default.ctp 
				$this->Email->template = 'template_1';
			#sending mail
			if($EmailTo != ''){
				$sending = $this->Email->send();		
			}
		
	}	
        /**
	 * @author Sukhvir Singh
	 * @created on 13/12/2013
	 * This function send mail to Moderator when SME contact the Moderator.
	 * @param Integer $id (Insight_id)
	 * @param string $moderator_email_address
	 * @param string $subject
	 * @param string $message
	 * @param string $sme_email_address
	 */
	function sendcontactmailtomoderatorlegalqa($id = '', $moderator_email_address = '', $subject = '', $message = '', $sme_email_address = '')
	{		
			$this->set('insight_url', SITE_URL . '/products/legalqarecords/'.$id);
			$this->set('insight_id', $id);
			$this->set('message_body', $message);
			$this->set('requestedBy', $this->Session->read('current_user_name'));
			//$EmailTo = 	$moderator_email_address;
			
			$EmailTo = 	"David.Coleman@lexisnexis.co.uk";
			$EmailFrom = 	LNG_UK_INSIGHT_TRACKER_EMAIL;	
			
			#To field of the mail
				$this->Email->to = $EmailTo;
				
			#Subject field of the mail
				$this->Email->subject = $subject; 
				
			#from field of the mail
				$this->Email->from = $EmailFrom;
				//$this->set('mail_body',$mail_body);
				
			#sending type
				$this->Email->sendAs = 'html';
				
			#for layouts/email/default.ctp 
				$this->Email->template = 'insight_contact_moderator_mail';
				
			#sending mail
			//if($EmailTo != ''){
				$sending = $this->Email->send();
			//}
		
	}	
} 
# End Class
?>