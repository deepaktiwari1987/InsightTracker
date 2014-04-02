<?php
/**
 * This is the master customers class that will handle all the master pages for customer insignt.
 * @author Mohit Khurana
 */
class CustomersController extends AppController
{
    #Controller Class Name
    var $name = 'Customers';
    # Array of helpers used in this controller.
    var $helpers = array('Javascript','Ajax','Form', 'Custom');
		# Array of components used in this controller.
		var $components = array('utility', 'Session', 'Cookie','Email');	
		#Flag to check error messages.
		var $flagErrMsg = 0;
		#Flag to check error messages.
		var $flagSuccessMsg = 0;
		# Array for error messages.
		var $arrErrMsg = array();
		#Flag to check success messages.
		var $arrSucMsg = array();
		# Save data array
		var $arrCustomerSaveData;
		/**
		 * Added By: Gaurav Saini <gauravs2@damcogroup.com>
		 * this variable stores the information like if the request has come from a mobile application or from a desktop
		 * @mobile
		 */
		var $_mobile = false;
		
    /**
     * This functiion is to display home page.
     */
    function admin()
    {
        $this->layout='admin';
        $this->set('printmsg','hideElement');
        
        $loginId = $this->Session->read('current_admin_id');
        
        if(isset($loginId) && trim($loginId) != '') {
        	$this->redirect(SITE_URL.'/customers/welcome');
        }
        
	   
			if(isset($this->data) && !empty($this->data) )
			{	
						$arr_data = $this->data;
						$pass = md5($arr_data['Pilotgroup']['password']);
					
						App::import('Model', 'Pilotgroup');
						# Create Pilotgroup model object
						$this->Pilotgroup = new Pilotgroup();
						# Finding data in database
        		$res = $this->Pilotgroup->find('first', array('conditions' => array('Pilotgroup.name' => $arr_data['Pilotgroup']['name'], 'Pilotgroup.isactive' => 1)));						
						//$res = $this->Pilotgroup->findByName($arr_data['Pilotgroup']['name']);
						
						# Password comparision 
						if(count($res)>0 && $res['Pilotgroup']['password'] == $pass) {
								$this->Session->write('current_admin_name', $res['Pilotgroup']['name']);
								$this->Session->write('current_admin_id', $res['Pilotgroup']['id']);
								$this->Session->write('current_admin_role', strtoupper($res['Pilotgroup']['role']));
								$this->Session->write('password_login' , TRUE);
								$this->redirect(SITE_URL.'/customers/welcome');
								
						}
						else
						{
							  # If username or password invalid,shows error message
							 	$this->set('errorlogin','invalid');
								$this->set('printmsg','showElement');
						}
			}
    }
		
		# Welcome Screen
    function welcome()
    {
      $this->layout = 'admin';
			
			$this->isAdmin();
					
      $current_user_name = $this->Session->read('current_admin_name');
      $this->set('user_name', $current_user_name);
    }

	/**
	 * @author Gaurav Saini
	 * @created on 18/08/2011 
	 * This function is used to login into the application when the application is being accessed by mobile device.
	 */
	function mhome()
	{
		# Stop caching for Browser
		header("Cache-Control: no-cache, must-revalidate");

		$this->layout='mobile';			
		if($_POST && $_POST['mLogin']=="Y")
		{
			$timestamp = md5(time());
			$UserName = trim($_POST['username']);
			
			$res = array();
			$this->Session->write('current_check_value', $timestamp);
				
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();
			$res = $this->Pilotgroup->findByName($UserName); 

			if(count($res)>0 && $res['Pilotgroup']['name'] == $UserName) {
				$this->Session->write('current_user_name', $res['Pilotgroup']['name']);
				$this->Session->write('current_user_id', $res['Pilotgroup']['id']);
				$this->Session->write('current_user_role', strtoupper($res['Pilotgroup']['role']));
				$this->Session->write('current_user_emailaddress', strtoupper($res['Pilotgroup']['emailaddress']));
				# Stop caching for Browser
				header("Cache-Control: no-cache, must-revalidate");
				$sec = "1";
				header("Refresh: $sec; url=".SITE_URL);
			}
			$_mobile = true;
		}
		else if($_POST && $_POST['HdnRedirect']=="Y")
		{
			$this->redirect(SITE_URL . '/products/mindex');
		}
	}

	
    function home()
    {
        
		if(!$this->_isMobile())
		{
			$this->layout='front';
		}
		else
		{		
			# Detecting Mobile version & device.
			$MobileSpecificationArray	= explode("/", $_SERVER["HTTP_USER_AGENT"]);	
			$MobileDevice				= $MobileSpecificationArray[0];
			$this->Session->write('MobileDevice', $MobileDevice);
			
			if($MobileSpecificationArray[1] !=""){
				$MobileVersionArray			= explode(" ", $MobileSpecificationArray[1]);
				$MobileVersion				= $MobileVersionArray[0]; 
				$this->Session->write('MobileVersion', $MobileVersion);
			}

			# Stop caching for Browser
			header("Cache-Control: no-cache, must-revalidate");
			$_mobile = true;
			$this->layout='mobile';	
			$this->render('mhome');			
		}
		
        $this->Session->delete('conditionsArr');
		/** Start ldap code **/
		/*	if(!$this->Session->check('current_user_id')){
			$userArray = $this->autologin();
			if(isset($userArray) && !empty($userArray['uid']) && !empty($userArray['uname'])) {
				$this->Session->write('current_user_name', $userArray['uname']);
				$this->Session->write('current_user_id', $userArray['uid']);			
			}
		}*/
		/** end **/

		/**
		 * This script of code is used to maintain Cookie which maintained through out the application for infinite duration, so that once a user is logged in, his session is maintianed on that browser.
		 * @created on 20/04/2011
		 * @author Pragya Dave
		 */
		# Check whether Cookie for user name exists or not. If Cookie is set, then set that Cookie into Session otherwise set the Cookie and also initialize the session for both username and userid
		# This function checks whether cookie is enabled on browser or not. If not enabled then alert a message to enable cookie.
		//$this->check_cookie();
		
		# this adds 365 days to the current time, i.e. cookie/session is maintained for 365 days
		$Year = 31536000 + time();
			
		if(isset($_COOKIE["current_user_name"])) {		

			$current_user_name = $_COOKIE["current_user_name"];
			$current_user_id = $_COOKIE["current_user_id"];
			$current_user_role  = $_COOKIE["current_user_role"];
			$current_user_emailaddress  = $_COOKIE["current_user_emailaddress"];
			//setcookie(current_user_name, $current_user_name, $Year);
			//setcookie(current_user_id, $current_user_id, $Year);
			$this->Session->write('current_user_name', $current_user_name);
			$this->Session->write('current_user_id', $current_user_id);	
			$this->Session->write('current_user_role', $current_user_role);		
			$this->Session->write('current_user_emailaddress', $current_user_emailaddress);		
		}	
		else {
			       $current_user_name = $this->Session->read('current_user_name');
				   $current_user_id = $this->Session->read('current_user_id');
			 	   $current_user_role = $this->Session->read('current_user_role');
				   $current_user_emailaddress = $this->Session->read('current_user_emailaddress');
				   setcookie(current_user_name, $current_user_name, $Year);
				   setcookie(current_user_id, $current_user_id, $Year);
				   setcookie(current_user_role, $current_user_role, $Year);
				   setcookie(current_user_emailaddress, $current_user_emailaddress, $Year);
		}			 
		# End Code

        if (isset($current_user_id) && $current_user_name != "")
        {
          $this->set('current_user_name', $current_user_name);
          $this->set('current_user_id', $current_user_id);
        }
    }
   
	 /**
     * This functiion check whether Cookie is enabled on the browser
     */

	function check_cookie(){		
		setcookie("test", "test", time() +31536000);
		
		if (!isset ($_COOKIE['test']))
			{
				echo "<script language=javascript>alert('Your browser doesnot support cookie. Please enable Cookies.');</script>";
				return false;
			}		
	}
    /**
     * This functiion is to display customer insight page.
     */
    function index($flagSuccessMsg = 0)
    {
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

    	# Import Productfamilyname model
    	App::import('Model', 'Competitorname');
    	# Create Productfamilyname model object
			$this->Competitorname = new Competitorname();
    	# Set Who Market Array
    	$this->set('arrWhoCompetetior',$this->Competitorname->getCompetitors());
			$otherexists = $this->Competitorname->getCompetitorId('Other');
			if(!$otherexists['Competitorname']['id']>0) {
				$otherarr['competitor_name'] = 'Other';
				$this->Competitorname->save($otherarr);
			}
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
    		$arrCustomerSaveData = $this->data;
				if($this->serverValidate($arrCustomerSaveData))
				{					
					# Import Insight model
					App::import('Model', 'Insight');
					# Create Insight model object
					$this->Insight = new Insight();
	
					# verify firm id and save id else save name.
					if(isset($arrCustomerSaveData['Firm']['what_firm_name']) && trim($arrCustomerSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrCustomerSaveData['Firm']['what_firm_name']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrCustomerSaveData['Customer']['what_firm_name'] = $firmParentID;
						else
							$arrCustomerSaveData['Customer']['what_firm_name_text'] = $arrCustomerSaveData['Firm']['what_firm_name'];
					}

					# verify firm id and save id else save name.
					if(isset($arrCustomerSaveData['Firm']['who_firm_name']) && trim($arrCustomerSaveData['Firm']['who_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrCustomerSaveData['Firm']['who_firm_name']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrCustomerSaveData['Customer']['who_firm_name'] = $firmParentID;
						else
							$arrCustomerSaveData['Customer']['who_firm_name_text'] = $arrCustomerSaveData['Firm']['who_firm_name'];
					}				

					# verify account number.
					if(isset($arrCustomerSaveData['Firm']['who_account_no']) && trim($arrCustomerSaveData['Firm']['who_account_no']) != '')
						$arrCustomerSaveData['Customer']['who_account_no'] = $arrCustomerSaveData['Firm']['who_account_no'];
				
					#Save User Id of the current user into the insights table.
					$arrCustomerSaveData['Customer']['user_id'] = $this->Session->read('current_user_id');
	
					# Check if attachment is there.
					if (isset($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name']) && !empty($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrCustomerSaveData['CustomerAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrCustomerSaveData['CustomerAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
	
							if($this->utility->uploadAttachment($arrCustomerSaveData['CustomerAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,CUSTOMER_ATTACHMENT_UPLOAD_PATH))
							{
								# Save Attachment New Name into database.
								$arrCustomerSaveData['Customer']['attachment_name'] = $attachmentNewName;
								# Save Attachment Original Name into database.
								$arrCustomerSaveData['Customer']['attachment_real_name'] = $arrCustomerSaveData['CustomerAttachment']['attachment_name']['name'];
							}
						}
					}
					// Code by Pragya Dave - fixed special characters insertion
					$arrCustomerSaveData['Customer']['insight_summary'] = $this->utility->parseString($this->data['Customer']['insight_summary']);
					$arrCustomerSaveData['Customer']['do_action'] = $this->utility->parseString($this->data['Customer']['do_action']);
					# Verify if there is no error.
					if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
					{
						#Save Customer Insight into database.
						$this->Insight->save($arrCustomerSaveData['Customer']);
						# Redirect if save is successful.
						$this->redirect(SITE_URL . '/customers/index/1');
					}
				}
			}
    } //End of index action.
    

/**
     * This functiion is to display update customer insight records page.
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

    	# Import Productfamilyname model
    	App::import('Model', 'Competitorname');
    	# Create Productfamilyname model object
			$this->Competitorname = new Competitorname();
    	# Set Who Market Array
    	$this->set('arrRelatesCompetetiors',$this->Competitorname->getCompetitors());
		
    	# Import Insight model
    	App::import('Model', 'Insight');
    	# Create Insight model object
			$this->Insight = new Insight();

    	# Import Insight model
    	App::import('Model', 'Statusinsight');
    	# Create Insight model object
			$this->Statusinsight = new Statusinsight();
			$this->set('arrStatusList', $this->Statusinsight->getStatusList(TRUE));

			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();
			# Set Pilotgroup names array for search view.
			$arrCreatedBy = $this->Pilotgroup->getPilotGroups(TRUE); //True passed for protect pilot group key id for dropdown.
			$this->set('arrCreatedBy', $arrCreatedBy);
						
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
    		$arrCustomerSaveData = $this->data;
				//pr($arrCustomerSaveData);die;
    		if($this->serverValidate($arrCustomerSaveData))
				{					
			    # Set flag for edit mode.
					$this->set('edit_flag', 'edit');
					# verify firm id and save id else save name.
					if(isset($arrCustomerSaveData['Firm']['what_firm_name']) && trim($arrCustomerSaveData['Firm']['what_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrCustomerSaveData['Firm']['what_firm_name']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrCustomerSaveData['Customer']['what_firm_name'] = $firmParentID;
						else
							$arrCustomerSaveData['Customer']['what_firm_name_text'] = $arrCustomerSaveData['Firm']['what_firm_name'];
					}
					# verify firm id and save id else save name.
					if(isset($arrCustomerSaveData['Firm']['who_firm_name']) && trim($arrCustomerSaveData['Firm']['who_firm_name']) != '')
					{
						# Verify if a firm with this parent_id exists in the database.
						$firmParentID = $this->processFirmExistance($arrCustomerSaveData['Firm']['who_firm_name']);
						
						# Set Firm name Field Text value from filled autosearch field.
						if(isset($firmParentID) && $firmParentID > 0)
							$arrCustomerSaveData['Customer']['who_firm_name'] = $firmParentID;
						else
							$arrCustomerSaveData['Customer']['who_firm_name_text'] = $arrCustomerSaveData['Firm']['who_firm_name'];
					}	
					# Account number
					$arrCustomerSaveData['Customer']['who_account_no'] = $arrCustomerSaveData['Firm']['who_account_no'];
					#Save User Id of the current user into the insights table.
					$arrCustomerSaveData['Customer']['user_id'] = $this->Insight->getCreatedById($arrCustomerSaveData['Customer']['id']);


					# Check if attachment is there.
					if (isset($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name']) && !empty($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name']))
					{
						# Get attachment extension.
						$attachmentExtension =  pathinfo($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name'],PATHINFO_EXTENSION);
						#Get new name for attachment to be saved into database.
						$attachmentNewName = str_replace(pathinfo($arrCustomerSaveData['CustomerAttachment']['attachment_name']['name'],PATHINFO_FILENAME), $timeStamp, $arrCustomerSaveData['CustomerAttachment']['attachment_name']['name']);
						if($this->serverValidateAttachment($attachmentExtension,$arrCustomerSaveData['CustomerAttachment']['attachment_name']['size']))
						{
							# Verify if attachment saved.
							if($this->utility->uploadAttachment($arrCustomerSaveData['CustomerAttachment']['attachment_name']['tmp_name'],$attachmentNewName,$attachmentExtension,CUSTOMER_ATTACHMENT_UPLOAD_PATH))
							{
								# Set old file to be removed.
								$file = new File(CUSTOMER_ATTACHMENT_UPLOAD_PATH . '/' . $arrCustomerSaveData['CustomerAttachment']['old_attachment_name']);
								# Remove file.
								$file->delete();
	
								# Save Attachment New Name into database.
								$arrCustomerSaveData['Customer']['attachment_name'] = $attachmentNewName;
								# Save Attachment Original Name into database.
								$arrCustomerSaveData['Customer']['attachment_real_name'] = $arrCustomerSaveData['CustomerAttachment']['attachment_name']['name'];
							}
						}
					}
					else if(isset($arrCustomerSaveData['CustomerAttachment']['old_attachment_name']))
					{
							$arrCustomerSaveData['Customer']['attachment_name'] = $arrCustomerSaveData['CustomerAttachment']['old_attachment_name'];
					}
					else {
							$arrCustomerSaveData['Customer']['attachment_name'] = NULL;				
					}
				
					if($this->Session->check('current_user_role')) {
						$current_user_role = $this->Session->read('current_user_role');
						if($current_user_role == ACCESS_EDIT_ROLE && !isset($arrCustomerSaveData['Customer']['deligated_to'])) {
							//$arrCustomerSaveData['Customer']['deligated_to'] = 0;
						}
					}
					
					# Check for current status is changed/set by current user.
					if(isset($arrCustomerSaveData['Customer']['insight_status']) && $arrCustomerSaveData['Customer']['insight_status']>0 && isset($arrCustomerSaveData['Customer']['insight_status_changed'])) {
						# Updated Time.
						$arrCustomerSaveData['Customer']['date_updated'] = date('Y-m-d H:i:s', time());
					}
					// Code by Pragya Dave - fixed special characters edit (08/02/2011)
					$arrCustomerSaveData['Customer']['insight_summary'] = $this->utility->parseString($arrCustomerSaveData['Customer']['insight_summary']);
					$arrCustomerSaveData['Customer']['do_action'] = $this->utility->parseString($arrCustomerSaveData['Customer']['do_action']);

					# Verify if there is no error.
					if(isset($this->flagErrMsg) && $this->flagErrMsg != 1)
					{
						# Update by user_id
						$arrCustomerSaveData['Customer']['updated_by'] = $this->Session->read('current_user_id');
						
						#Save Customer Insight into database.
						$this->Insight->save($arrCustomerSaveData['Customer']);
						# Redirect if save is successful.
						$this->redirect(SITE_URL . '/customers/records/'.$id.'/1');
					}
				}		
			}	
			//else 
   		//{
				# Reading insight record on the basis of $id.
				$this->data = $this->Insight->read();

				$this->data['Customer']['what_how_come'] = $this->data['Insight']['what_how_come'];
				$this->data['Customer']['what_source_name'] = $this->data['Insight']['what_source_name'];
				$this->data['Customer']['who_contact_name'] = $this->data['Insight']['who_contact_name'];			
				$this->data['Customer']['who_contact_role'] = $this->data['Insight']['who_contact_role'];
				$this->data['Customer']['insight_summary'] = $this->utility->parseString($this->data['Insight']['insight_summary']);
				$this->data['Customer']['relates_product_family_id'] = $this->data['Insight']['relates_product_family_id'];
				$this->data['Customer']['practice_area_id'] = $this->data['Insight']['practice_area_id'];
				$this->data['Customer']['do_action'] = $this->data['Insight']['do_action'];
			
				# Import Firm model
				if (isset($this->data['Insight']['what_firm_name']) && $this->data['Insight']['what_firm_name']>0) 
				{
					$this->data['Firm']['what_firm_name'] = $this->processFirmId($this->data['Insight']['what_firm_name']);
				}
				else
				{
					$this->data['Firm']['what_firm_name'] = $this->data['Insight']['what_firm_name_text'];
				}
	
				# Import Firm model
				if (isset($this->data['Insight']['who_firm_name']) && $this->data['Insight']['who_firm_name']>0) 
				{
					$this->data['Firm']['who_firm_name'] = $this->processFirmId($this->data['Insight']['who_firm_name']);
				}
				else
				{
					$this->data['Firm']['who_firm_name'] = $this->data['Insight']['who_firm_name_text'];
				}
				$this->data['Firm']['who_account_no'] = $this->data['Insight']['who_account_no'];
				if($this->data['Insight']['insight_status']>0) {
					$this->data['Insight']['current_status_text'] = $this->Statusinsight->getStatusById($this->data['Insight']['insight_status']); 
				}

				# Set variable values for view.

				$this->set('what_how_come', $this->data['Insight']['what_how_come']); 
				$this->set('relates_competitor_name_label', $this->data['Insight']['relates_competitor_id']);
				$this->set('product_family_name_label', $this->data['Insight']['relates_product_family_id']);
				$this->set('practice_area_label', $this->data['Insight']['practice_area_id']);
				$this->set('do_action_dummy', $this->data['Insight']['do_action']);
				$this->set('attachment_real_name', $this->data['Insight']['attachment_real_name']);
				$this->set('attachment_name', $this->data['Insight']['attachment_name']);
				$this->set('current_status_label', $this->data['Insight']['insight_status']);
				$this->set('deligated_to_selected', $this->data['Insight']['deligated_to']);
				/*if($this->data['Insight']['current_status_text'] == 'Delegated')	{
							$this->set('deligated_to_selected', $this->data['Insight']['deligated_to']);
						$this->set('deligated_disable', FALSE);
				}else{
						$this->set('deligated_to_selected', 0);
						$this->set('deligated_disable', TRUE);
				}*/
				# URL to go back to search result page.
				$this->set('backUrl', $this->Cookie->read('backUrl'));
   		//}
  } // End of records action
	
    /**
     * This functiion is to display customer insight search page.
     */
    function search($insightId = '', $found = '')
    {
      $this->layout='front';
			if($insightId == 'checked') {
				$this->set('insightTypeChecked', $insightId);
				$insightId = '' ;
			}
			# Setting current url to redirect in case no result found.
			$this->Cookie->write('currentUrl', SITE_URL.'/customers/search');
			$this->Cookie->write('backUrl', SITE_URL.'/customers/search');
		
			# Removing conditions array from session if any.
			$this->Session->delete('conditionsArr');

    	# Import Content Type model
    	App::import('Model', 'Insightabout');
    	# Create Content Type model object
			$this->Insightabout = new Insightabout();
    	# Set Who Content Type Array
    	$this->set('arrHowCome',$this->Insightabout->returnStaticData(TRUE));
		
			# Import Pilotgroup model
			App::import('Model', 'Pilotgroup');
			# Create Pilotgroup model object
			$this->Pilotgroup = new Pilotgroup();
			# Set Pilotgroup names array for search view.
			$arrCreatedBy = $this->Pilotgroup->getPilotGroups(TRUE); //True passed for protect pilot group key id for dropdown.
			$this->set('arrCreatedBy', $arrCreatedBy);

    	# Import Productfamilyname model
    	App::import('Model', 'Competitorname');
    	# Create Productfamilyname model object
			$this->Competitorname = new Competitorname();
    	# Set Who Market Array
    	$this->set('arrWhoCompetetior',$this->Competitorname->getCompetitors());
		
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
										
    } // End of search action

    /**
     * This functiion is to display customer insight search results page.
     */
    function results($insightId = '')
    {
				$this->layout='front';
				
				# Import Insight model
				App::import('Model', 'Insight');
				# Create Insight model object
				$this->Insight = new Insight();
				$conditionsArr = array();

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

					/*if(trim($insightType) != "" && $insightType != 'CUSTOMER') {
						$this->redirect(SITE_URL.'/'.strtolower($insightType).'s/results/'.$this->data['Insight']['id']);
					}elseif($this->data['Insight']['id']>0){
						$insightId = $this->data['Insight']['id'];
					}*/
				}

				

				# Import Pilotgroup model
				App::import('Model', 'Pilotgroup');
				# Create Pilotgroup model object
				$this->Pilotgroup = new Pilotgroup();
	
				# Import Practicearea model
				App::import('Model', 'Practicearea');
				# Create Practicearea model object
				$this->Practicearea = new Practicearea();
	
				# Import Productfamilyname model
				App::import('Model', 'Productfamilyname');
				# Create Practicearea model object
				$this->Productfamilyname = new Productfamilyname();
		
				# Import Competitorname model
				App::import('Model', 'Competitorname');
				# Create Competitorname model object
				$this->Competitorname = new Competitorname();
			
				# Search process (conditions composing) start.
				if((isset($this->data) && !empty($this->data)) || $insightId>0)
				{
					if($insightId<1) {
						$conditionsArr = array('Insight.what_insight_type' => $this->data['Customer']['what_insight_type']);
			
						if(isset($this->data['Customer']['what_how_come']) && $this->data['Customer']['what_how_come'] != "0" ) {
							$conditionsArr = array_merge($conditionsArr, array('Insight.what_how_come' => $this->data['Customer']['what_how_come']));
						}
			
						# Check for source name.
						if(isset($this->data['Customer']['what_source_name']) && trim($this->data['Customer']['what_source_name']) != "") {
							$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_source_name) LIKE' => '%' . strtolower($this->data['Customer']['what_source_name']) . '%'));
						}
						# Check / Condition for firm name (What).
						if(isset($this->data['Firm']['what_firm_name']) && trim($this->data['Firm']['what_firm_name']) != "") {
								$parentId = $this->processFirmExistance($this->data['Firm']['what_firm_name'],1);
								
							if($parentId>0) {
								$conditionsArr = array_merge($conditionsArr, array('Insight.what_firm_name' => $parentId));
							}
							else {
									$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.what_firm_name_text) LIKE' => '%' . strtolower($this->data['Firm']['what_firm_name']) . '%'));
							}
						}
			
						# Check / Condition for firm name (Who).
						if(isset($this->data['Firm']['who_firm_name']) && trim($this->data['Firm']['who_firm_name']) != "") {
								$parentId = $this->processFirmExistance($this->data['Firm']['who_firm_name']);
							if($parentId>0) {
								$conditionsArr = array_merge($conditionsArr, array('Insight.who_firm_name' => $parentId));
							}
							else {
									$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_firm_name_text) LIKE' => '%' . strtolower($this->data['Firm']['who_firm_name']) . '%'));
							}
						}			
			
						# Check / Condition for accout number.
						if(isset($this->data['Firm']['who_account_no']) && trim($this->data['Firm']['who_account_no']) != "") {
							$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_account_no) LIKE' => '%' . strtolower($this->data['Firm']['who_account_no']) . '%'));
						}
			
						# Check / Condition for contact name..
						if(isset($this->data['Customer']['who_contact_name']) && trim($this->data['Customer']['who_contact_name']) != "") {
							$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_contact_name) LIKE' => '%' . strtolower($this->data['Customer']['who_contact_name']) . '%'));
						}
						
						# Check / Condition for contact role.
						if(isset($this->data['Customer']['who_contact_role']) && trim($this->data['Customer']['who_contact_role']) != "") {
							$conditionsArr = array_merge($conditionsArr, array('LOWER(Insight.who_contact_role) LIKE' => '%' . strtolower($this->data['Customer']['who_contact_role']) . '%'));
						}
			
						# Check / Condition for created by.
						if(isset($this->data['Customer']['user_id']) && trim($this->data['Customer']['user_id'])>0) {
								$conditionsArr = array_merge($conditionsArr, array('Insight.user_id' => $this->data['Customer']['user_id']));
						}
			
						# Check / Condition for created from and to date.
						if(trim($this->data['Customer']['created_from']) != "") {
								$startTimeArr = explode('-', $this->data['Customer']['created_from']);
								$startTime = mktime(0,0,0,$startTimeArr[1],$startTimeArr[2],$startTimeArr[0]);
			
								if(trim($this->data['Customer']['created_to']) == "" ) {
									$endTime = time();
								}else{
									$endTimeArr = explode('-', 	$this->data['Customer']['created_to']);
									$endTime = mktime(23,59,59,$endTimeArr[1],$endTimeArr[2],$endTimeArr[0]);
								}
			
									$conditionsArr = array_merge($conditionsArr, array('UNIX_TIMESTAMP(Insight.date_submitted) BETWEEN ? AND ?' => array($startTime, $endTime)));
						}
						
						# Check / Condition for competitor name.
						if(isset($this->data['Customer']['relates_competitor_id']) && trim($this->data['Customer']['relates_competitor_id'])>0) {
							$conditionsArr = array_merge($conditionsArr, array('Insight.relates_competitor_id' => $this->data['Customer']['relates_competitor_id']));
						}
									
						# Set condition for product family name
						if(isset($this->data['Customer']['relates_product_family_id']) && $this->data['Customer']['relates_product_family_id']>0)
						{
							$conditionsArr = array_merge($conditionsArr, array('Insight.relates_product_family_id' => $this->data['Customer']['relates_product_family_id']));
						}												
						# Check for relates_practice_area.
						if(isset($this->data['Customer']['practice_area_id']) && $this->data['Customer']['practice_area_id']>0) {
							$conditionsArr = array_merge($conditionsArr, array('Insight.practice_area_id' => $this->data['Customer']['practice_area_id']));
						}

						# Search for current status value.
						if(isset($this->data['Customer']['insight_status']) && $this->data['Customer']['insight_status']>0) {
							$conditionsArr = array_merge($conditionsArr, array('Insight.insight_status' => $this->data['Customer']['insight_status']));
						}

						# Search for delegated to value.
						if(isset($this->data['Customer']['deligated_to']) && $this->data['Customer']['deligated_to']>0) {
							$conditionsArr = array_merge($conditionsArr, array('Insight.deligated_to' => $this->data['Customer']['deligated_to']));
						}

					}else{
						$conditionsArr = array('Insight.id' => $insightId);
					}
					
					$this->Session->write('conditionsArr', serialize($conditionsArr));
					$this->Session->write('exportType', 'customer');			
			}

			# In case searched other type from other tab.
			if($this->Session->read('exportType') != 'customer')
				$this->redirect(SITE_URL.'/customers/search');

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
			//$result = $this->Insight->find('all', array('conditions' => unserialize($this->Session->read('conditionsArr'))));

			# Processing result.
			if(!empty($result))
			{
				$i = 0;
				foreach($result as $row)
				{
					$final_result[$i] = $row['Insight'];
					$final_result[$i]['userSubmittedName'] = $this->Pilotgroup->getPilotgroupNameByID($row['Insight']['user_id']);
	
					if($final_result[$i]['what_how_come'] == "0")
						$final_result[$i]['what_how_come'] = "";
	
					# Composing firm name on basis of Id if any else direct name from db field.
					if(isset($row['Insight']['what_firm_name']) && $row['Insight']['what_firm_name']>0) {
						$final_result[$i]['firmName'] = $this->processFirmId($row['Insight']['what_firm_name']);
					}
					else {
						$final_result[$i]['firmName'] = $final_result[$i]['what_firm_name_text'];
					}
					
					# Composing firm name on basis of Id if any else direct name from db field.
					if(isset($row['Insight']['who_firm_name']) && $row['Insight']['who_firm_name']>0) {
						$final_result[$i]['who_firmName'] = $this->processFirmId($row['Insight']['who_firm_name']);
					}
					else {
						$final_result[$i]['who_firmName'] = $final_result[$i]['who_firm_name_text'];
					}				
					# Composing firm name on basis of Id if any else direct name from db field.
					if(isset($row['Insight']['relates_competitor_id']) && trim($row['Insight']['relates_competitor_id'])>0) {
						$comp_nameArr = $this->Competitorname->getCompetitorName($row['Insight']['relates_competitor_id']);
						$final_result[$i]['relates_competitorName'] = $comp_nameArr['Competitorname']['competitor_name'];
					}else {
						$final_result[$i]['relates_competitorName'] = "";
					}	
					
					# Composing product family name.
					if(isset($row['Insight']['relates_product_family_id']) && $row['Insight']['relates_product_family_id']>0) {
						$prod_familiy_name_arr = array();
						$prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($row['Insight']['relates_product_family_id']);
						$final_result[$i]['relates_product_familyName'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
					}else {
						$final_result[$i]['relates_product_familyName'] = "";
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
    * This function is to validate the customer name in insight records.
    */
     function validateuser($uname, $timestamp)
    {
				$res = array();
        # Import Pilotgroup model
        App::import('Model', 'Pilotgroup');
        # Create Pilotgroup model object
        $this->Pilotgroup = new Pilotgroup();
				$this->Session->write('current_check_value', $timestamp);
				
        $res = $this->Pilotgroup->find('first', array('conditions' => array('Pilotgroup.name' => $uname, 'Pilotgroup.isactive' => 1)));
				
        if(count($res)>0 && $res['Pilotgroup']['name'] == $uname) {
            print $uname;
        }else{
            print "FALSE";
        }
        die;
				/*$result = mysql_fetch_object(mysql_query('SELECT * FROM pilotgroups WHERE name = "'.$uname.'" AND isactive = 1'));
				if(isset($result->name) && $result->name == $uname){
				  print $result->name;
				}else{
            print "FALSE";
        }*/

    }

    /**
    * This function is to set username to proceed with insight.
    */
    function setuser($uname)
    {
        # Import Pilotgroup model
        App::import('Model', 'Pilotgroup');
        # Create Pilotgroup model object
        $this->Pilotgroup = new Pilotgroup();
        $res = $this->Pilotgroup->findByName($uname);
		if(count($res)>0 && $res['Pilotgroup']['name'] == $uname) {
            $this->Session->write('current_user_name', $res['Pilotgroup']['name']);
            $this->Session->write('current_user_id', $res['Pilotgroup']['id']);
            $this->Session->write('current_user_role', strtoupper($res['Pilotgroup']['role']));
            $this->Session->write('current_user_emailaddress', strtoupper($res['Pilotgroup']['emailaddress']));
            $this->redirect(SITE_URL);
        }
		else
		{
            print "Technical error occurred, contact administrator.";
        }
        die;
    }
    
    /**
    * This function is to unset username for insight.
    */
    function unsetuser($param = '')
    {
        $this->Session->delete('current_user_name');
        $this->Session->delete('current_user_id');
        //$this->Session->delete('current_user_role');
        $this->Session->delete('current_user_emailaddress');
				$this->Session->delete('exportType');

				//session_destroy();
				//print $param;die;
				if($param == 0){	
				
					$this->redirect(SITE_URL."/");
				}
				else
				{
					$this->redirect(SITE_URL."/admin");
				}
    }

    /**
    * This function is to unset username for insight.
    */
    function unsetadmin($param = '')
    {
        $this->Session->delete('current_admin_name');
        $this->Session->delete('current_admin_id');
        $this->Session->delete('current_admin_role');
				$this->Session->delete('exportType');

				//session_destroy();
				//print $param;die;
				$this->redirect(SITE_URL."/admin");
    }

    /**
     * This function validates the form on the server side in case there is no client side validation or any failure.
     * @return true/false
     * @author Mohit Khurana
     */
    function serverValidate($arrCustomerSaveData)
    {
    	# Validate value of insight summary.
    	if(trim($arrCustomerSaveData['Customer']['insight_summary']) == '')
    	{
    		# Set error div to display mode.
    		$this->set('errDivCustomerInsightSummary','block');
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
     * This validates and returns entered firm information for search.
     * @param String $firmParentID
     * @return Firm Parent ID
     * @author Mohit Khurana
     */
    function processFirmExistanceForSearch($firmParentID='',$noConcat=0)
    {
    	# Import Firm model
    	App::import('Model', 'Firm');
    	# Create Firm model object
		$this->Firm = new Firm();
		
		$firms = array();
		
		# Get array for firm name id.
    	$arrFirmParentID = $this->Firm->getFirmParentIDData($firmParentID,$noConcat);
    	
    	# Check if firm parent id exists.
    	if(isset($arrFirmParentID) && is_array($arrFirmParentID) && count($arrFirmParentID) > 0) {
    		foreach ($arrFirmParentID as $valFirmParentID) {
    			if(isset($valFirmParentID['Firm']['parent_id']) && trim($valFirmParentID['Firm']['parent_id']) != '') {
    				$firms[] = $valFirmParentID['Firm']['parent_id'];
    			}
    		}
    		return implode(',',$firms);
    	}
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
     * This function sets the default display status for the error divs on the view page that will be 'none'.
     * @author Mohit Khurana
     */
    function setMessageDivDefaultStatus()
    {
    	# Set the default display status for the summary error div.
    	$this->set('errDivCustomerInsightSummary','none');
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
			if(file_exists(CUSTOMER_ATTACHMENT_UPLOAD_PATH."/".$file)) {
				if(unlink(CUSTOMER_ATTACHMENT_UPLOAD_PATH."/".$file))
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

	# Function to check whether the user is admin or not.
	function isAdmin() {

					$current_user_role = $this->Session->read('current_admin_role');
					$pass_login = $this->Session->read('password_login');
					$current_login_id = $this->Session->read('current_admin_id');
					$adminUrl = '';

					if($current_user_role != 'A') { //Not admin.
						if($this->params['action'] == 'welcome' && !$current_login_id) {
							$adminUrl = 'admin';
						}

						$this->redirect(SITE_URL.'/'.$adminUrl);
					}
					
					if($pass_login != TRUE) {
						$this->redirect(SITE_URL.'/');
					}
					//else if($this->params['action'] == 'welcome' && $pass_login == TRUE){
						//$this->redirect(SITE_URL.'/customers/welcome');					
					//}
	}	
	
	function autologin() {
		
		$errArr = array();
		# Import Pilotgroup model
        App::import('Model', 'Pilotgroup');
        # Create Pilotgroup model object
        $this->Pilotgroup = new Pilotgroup();
		
		//ldap settings
		$settings = array(
			'server' => LDAP_SERVER,
			'account' => LDAP_ACCOUNT, 
			'password' => LDAP_PASSWORD, 
			'path' => LDAP_PATH
		);
		
		//connecting to an ldap server

		if(!$ds = ldap_connect($settings['server'])){
			$errArr[] = 'Unable to connect to LDAP server at '. $settings['server'];
		}

		//setting the options
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ds, LDAP_OPT_REFERRALS, true);
		ldap_set_option($ds, LDAP_OPT_DEREF, LDAP_DEREF_ALWAYS);

		//binding the connection with the ldap server
		if(!$r = ldap_bind($ds, $settings['account'], $settings['password']))
		{
			$errArr[] = 'Unable to bind to LDAP server at '.$settings['server'] .'. Error: '.ldap_errno($ds).'-'.ldap_error($ds);
		}
		//getting the username
		$username = ($_SERVER['REMOTE_USER'] != "")?$_SERVER['REMOTE_USER']:$_SERVER['AUTH_USER'];
		
		if(empty($username)){
			$errArr[] = 'Username value not fetched from SERVER array.';
		}
		
		// An array of the required attributes 
		$attributes = array('mail','sn','givenName');
		
		//searching ldap tree for the presence of the username
		$sr = ldap_search($ds, $settings['path'], '(sAMAccountName=' . $username.')', $attributes);
		
		//checking the presence of username in the database
		$rec_exists = $this->Pilotgroup->find('count', array('conditions' => array( 'name' => $username)));
		
		//if the username is not present in database or ldap or both, error will be displayed
		if ((!ldap_count_entries($ds,$sr)) && $rec_exists < 1){
			$errArr[] =  'User:'. $username .'not found.';
		}
		
		// Get all result entries
		$ad_info=ldap_get_entries($ds,$sr);
		
		//closing the ldap connection
		ldap_close($ds);
		if(count($errArr)>0) {
			$this->Session->write('errArr', $errArr);
			return false;
		}
		$res = $this->Pilotgroup->findByName($username);
		$array['uid'] = $res['Pilotgroup']['id'];
		$array['uname'] = $username;
		return $array;
		
	}

	/**
	* @this function is added by Gaurav Saini
	*/
	private function _isMobile()
	{
		if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) return true;
	    if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) return true;

		if(isset($_SERVER["HTTP_USER_AGENT"])){
			// Quick Array to kill out matches in the user agent
			// that might cause false positives
			$badmatches = array("OfficeLiveConnector","MSIE\  8\.0","OptimizedIE8","MSN\ Optimized","Creative\ AutoUpdate","Swapper");
		
			foreach($badmatches as $badstring){
				if(preg_match("/".$badstring."/i",$_SERVER["HTTP_USER_AGENT"])) return  false;
			}

			// Now we'll go for positive matches with the list of all possible types of request processors
			$uamatches = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\  ce", "mmp\/", 		
						 "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC",
						 "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover"
						 , "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb",
						 "\d\d\di", "moto", "webos"); 

			foreach($uamatches as $uastring){
				if(preg_match("/".$uastring."/i",$_SERVER["HTTP_USER_AGENT"])) {
					return  true;
				}
			}
		}
		return false;
	}
}
?>