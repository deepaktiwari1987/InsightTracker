<div id="" class = "hr-row" >
		<table  border="0" cellspacing="5" cellpadding="5" align="center">
		<tr><td height="12"></td></tr>
				<tr>
					<td >
							<?php switch($table_name) {
										case 'competitorname':
										$addcotnent = 'Competitor Name';
										print '<h2>Add '. $addcotnent .' </h2>';
										break;			

										case 'contenttype':
										$addcotnent = 'Content Type';										
										print '<h2>Add Content Type </h2>';
										break;			

										case 'insighttype':
										$addcotnent = 'Insight Type';										
										print '<h2>Add Inisght Type </h2>';
										break;			
										case 'firm':
										$addcotnent = 'Organisation';										
										print '<h2>Add Organisation</h2>';
										break;			

										case 'insightabout':
										$addcotnent = 'Feedback come about';										
										print '<h2>Add Feedback come about </h2>';
										break;			

										case 'market':
										$addcotnent = 'Market Segment';										
										print '<h2>Add Market Segment</h2>';
										break;			

										case 'pilotgroup':
										$addcotnent = 'User name ';											
										print '<h2>Add User name</h2>';
										break;			

										case 'practicearea':
										$addcotnent = 'Practice Area';										
										print '<h2>Add Practice Area </h2>';
										break;			

										case 'productfamilyname':
										$addcotnent = 'Product Family Name';										
										print '<h2>Add Product Family Name </h2>';
										break;			

										case 'productname':
										$addcotnent = 'Product Name';										
										print '<h2>Add Product Name </h2>';
										break;			

										case 'statusinsight':
										$addcotnent = 'Feedback status';										
										print '<h2>Add Feedback Status </h2>';
										break;			
										
										case 'productarea':
										$addcotnent = 'Product Area';										
										print '<h2>Add Product Area </h2>';
										break;
										
										case 'sellingobstacle':
										$addcotnent = 'Selling Obstacle';										
										print '<h2>Add Selling Obstacles </h2>';
										break;
										
										case 'departmentname':
										$addcotnent = 'Department Name';										
										print '<h2>Add Department Name </h2>';
										break;	
										
										default:
										$addcotnent = 'Record';										
										print '<h2>Add Record</h2>';
										break;
							} ?>

					</td>
					<td>&nbsp;</td>
				</tr>
		</table>
		
		<div class="hr-row"  style="width:auto; overflow-x:auto; overflow-y:hidden;" ></div>
    <table  width="65%" border="0" cellspacing="4" cellpadding="5" align="center">

<?php if($table_name == 'competitorname') {?>
  <?php echo $form->create(null, array('action'=>'addnew/competitorname','id'=>'CompetitornameForm','name'=>'CompetitornameForm','onSubmit'=>'return blankfunc1("EditrecordCompetitorName","Competitorname")'));
  //echo $form->create();?>
	
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center" ><span id="Competitorname_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	 </tr>
      <tr>
        <td width="50%" nowrap>Competitor Name :</td>
				<td width="50%"><input type="text" id="EditrecordCompetitorName" maxlength="255" name="data[Competitorname][competitor_name]" value="<?php echo isset($data['Competitorname']['competitor_name']) ? $data['Competitorname']['competitor_name'] : ''?>" autocomplete="off" /></td>
      </tr>
		  <tr>
				<td colspan="2" nowrap="nowrap" align="center">
				  	<span id="EditrecordCompetitorName_err1" class="hideElement errormsg">Enter Competitor Name</span>
				   	<span id="EditrecordCompetitorName_err2" class="hideElement errormsg">Enter alphanumeric entries</span>
				</td>
      </tr>			

   
<?php } ?>

<?php if($table_name == 'contenttype') {?>
   <?php echo $form->create(null, array('action'=>'addnew/contenttype','id'=>'ContenttypeForm','name'=>'ContenttypeForm','onSubmit'=>'return blankfunc1("EditrecordContentType","Contenttype")'));?>
  
	<tr>
		<td colspan="2" nowrap="nowrap" align="center"><span id="Contenttype_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%">Content Type :</td>
				<td width="50%"><input type="text" id="EditrecordContentType" maxlength="255" name="data[Contenttype][content_type]" value="<?php echo isset($data['Contenttype']['content_type']) ? $data['Contenttype']['content_type'] : ''?>" autocomplete="off" /></td>
		  </tr>
			 <tr>
				<td colspan="2" nowrap="nowrap" align="center">
				  <span id="EditrecordContentType_err1" class="hideElement errormsg">Enter Content Type</span>
			   	<span id="EditrecordContentType_err2" class="hideElement errormsg">Enter alphabets only</span>
				</td>
      </tr>	
     
<?php } ?>
  <?php /* @insighttype changes
   */
  ?>    
  
  <?php if($table_name == 'insighttype') {?>
   <?php echo $form->create(null, array('action'=>'addnew/insighttype','id'=>'InsighttypeForm','name'=>'InsighttypeForm','onSubmit'=>'return blankfunc1("EditrecordInsightType","Insighttype")'));?>
  
	<tr>
		<td colspan="2" nowrap="nowrap" align="center"><span id="Insighttype_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%">Insight Type :</td>
				<td width="50%"><input type="text" id="EditrecordInsightType" maxlength="255" name="data[Insighttype][insight_type]" value="<?php echo isset($data['Insighttype']['insight_type']) ? $data['Insighttype']['insight_type'] : ''?>" autocomplete="off" /></td>
		  </tr>
			 <tr>
				<td colspan="2" nowrap="nowrap" align="center">
				  <span id="EditrecordInsightType_err1" class="hideElement errormsg">Enter Insight Type</span>
			   	<span id="EditrecordInsightType_err2" class="hideElement errormsg">Enter alphabets only</span>
				</td>
      </tr>	
     
<?php } ?>

<?php if($table_name == 'firm') {?>
  <?php echo $form->create(null, array('action'=>'addnew/firm','id'=>'FirmForm','name'=>'FirmForm','onSubmit'=>'return blankfunc3("FirmParentId","EditrecordAccountNumber","EditrecordFirmName","Firm")'));?>
 
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Firm_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			 <tr>
        <td width="50%">Parent Id :</td>
				<td width="50%"><input type="text" id="FirmParentId" maxlength="40" name="data[Firm][parent_id]" value="<?php echo isset($data['Firm']['parent_id']) ? $data['Firm']['parent_id'] : ''?>" autocomplete="off" /></td>
			 </tr>
			 <tr>
        <td width="50%">&nbsp;</td>
				<td width="50%" >
				  <span id="FirmParentId_err1" class="hideElement errormsg">Enter Parent Id</span>
				   <span id="FirmParentId_err2" class="hideElement errormsg">Enter numbers only</span>
					 
				</td>
      </tr>	
      <tr>
        <td width="50%">Account Number :</td>
				<td width="50%"><input type="text" id="EditrecordAccountNumber" maxlength="40" name="data[Firm][account_number]" value="<?php echo isset($data['Firm']['account_number']) ? $data['Firm']['account_number'] : ''?>" autocomplete="off" /></td>
		  </tr>
			<tr>
        <td width="50%">&nbsp;</td>
				<td width="50%">
				  <span id="EditrecordAccountNumber_err1" class="hideElement errormsg">Enter Account Number</span>
				   <span id="EditrecordAccountNumber_err2" class="hideElement errormsg">Enter Alphanumeric entries</span>
					 
				</td>
      </tr>	
       <tr>
        <td width="50%">Organisation Name :</td>
				<td width="50%"><input type="text" id="EditrecordFirmName" maxlength="255" name="data[Firm][firm_name]" value="<?php echo isset($data['Firm']['firm_name']) ? $data['Firm']['firm_name'] : ''?>" autocomplete="off" /></td>
       </tr>
			 <tr>
        <td width="50%">&nbsp;</td>
				<td width="50%" >
				  <span id="EditrecordFirmName_err1" class="hideElement errormsg">Enter Organisation Name</span>
				   <span id="EditrecordFirmName_err2" class="hideElement errormsg">Enter alphanumeric entries</span>
					
				</td>
      </tr>	
 
<?php } ?>

<?php if($table_name == 'insightabout') {?>
   <?php echo $form->create(null, array('action'=>'addnew/insightabout','id'=>'InsightaboutForm','name'=>'InsightaboutForm','onSubmit'=>'return blankfunc1("InsightaboutInsightType","Insightabout")'));?>
   
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Insightabout_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%">Feedback come about :</td>
				<td width="50%"><input type="text" id="InsightaboutInsightType" maxlength="255" name="data[Insightabout][insight_type]" value="<?php echo isset($data['Insightabout']['insight_type']) ? $data['Insightabout']['insight_type'] : ''?>" autocomplete="off" /></td>
			</tr>
			 <tr>
				<td colspan="2" nowrap="nowrap" align="center">
				  <span id="InsightaboutInsightType_err1" class="hideElement errormsg">Enter feedback come about</span>
				   <span id="InsightaboutInsightType_err2" class="hideElement errormsg">Enter alphabets only</span>
					
				</td>
      </tr>
     
<?php } ?>

<?php if($table_name == 'market') {?>
  <?php echo $form->create(null, array('action'=>'addnew/market','id'=>'MarketForm','name'=>'MarketForm','onSubmit'=>'return blankfunc1("MarketMarket","Market")'));?>
     
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Market_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
		  <tr>
        <td width="50%">Market Name :</td>
				<td width="50%"><input type="text" id="MarketMarket" maxlength="255" name="data[Market][market]" value="<?php echo isset($data['Market']['market']) ? $data['Market']['market'] : ''?>" autocomplete="off" /></td>
			  <span id="err" ></span>
      </tr>
			 <tr>
				<td colspan="2" nowrap="nowrap" align="center">
				  <span id="MarketMarket_err1" class="hideElement errormsg">Enter Market Name</span>
				   <span id="MarketMarket_err2" class="hideElement errormsg">Enter alphabets only</span>
				</td>
      </tr>
   

<?php } ?>
<?php if($table_name == 'pilotgroup') {?>
<?php $selectedAdmin = $normalSelected = $SMESelected = "";
	if(isset($data['Pilotgroup']['role']) && $data['Pilotgroup']['role'] == 'A')
	{ 
			$selectedAdmin = "selected='selected'"; 
			$normalSelected = ""; 
			$SMESelected=""; 
	} 
	else if(isset($data['Pilotgroup']['role']) && $data['Pilotgroup']['role'] == 'S') 
	{ 
		$SMESelected = "selected='selected'";
		$selectedAdmin = ""; 
		$normalSelected = "";
	} 
	else
	{ 
		$normalSelected = "selected='selected'";
		$selectedAdmin = ""; 
		$normalSelected = "";
	} ?>
  <?php echo $form->create(null, array('action'=>'addnew/pilotgroup','id'=>'PilotgroupForm','name'=>'PilotgroupForm','onSubmit'=>'return userAddNewformValidate("PilotgroupName", "PilotgroupPassword", "PilotgroupEmailAddress", "PilotgroupCCEmailAddress", "PilotgroupFirstName", "PilotgroupSurName", "Pilotgroup")'));?>
  
	<tr>
		<td colspan="2" height="10" nowrap="nowrap" align="center"><span id="Pilotgroup_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
	<tr>
        <td width="40%">User Name :</td>
		<td><input class="noSpace" type="text" id="PilotgroupName" maxlength="255" name="data[Pilotgroup][name]" value="<?php echo isset($data['Pilotgroup']['name']) ? $data['Pilotgroup']['name'] : ''?>" autocomplete="off"  style="width:180px;"/></td>
	</tr>
	<tr>
        <td>First Name :</td>
		<td><input type="text" id="PilotgroupFirstName" maxlength="50" name="data[Pilotgroup][first_name]" value="<?php echo isset($data['Pilotgroup']['first_name']) ? $data['Pilotgroup']['first_name'] : ''?>" style="width:180px;"/></td>
	</tr>
	<tr>
        <td>Last Name :</td>
		<td><input type="text" id="PilotgroupSurName" maxlength="50" name="data[Pilotgroup][sur_name]" value="<?php echo isset($data['Pilotgroup']['sur_name']) ? $data['Pilotgroup']['sur_name'] : ''?>" style="width:180px;" /></td>
	</tr>	
	<tr>		  
        <td>Role :</td>
		<td>
					<select id="PilotgroupRole" name="data[Pilotgroup][role]" style="width:190px !important;height:18px;border:1px solid #CCCCCC" onchange="changeShowPass(this, 'add')" >
														<option <?php print $normalSelected; ?> value="">Contributor</option>
														<option <?php print $selectedAdmin; ?> value="A">Moderator</option>
														<option <?php print $SMESelected; ?> value="S">Subject Matter Expert (SME)</option>
												</select>							
												
				</td>
	</tr>
	<tr>
        <td>Password :</td>
		<td><input type="password" id="PilotgroupPassword" maxlength="20" name="data[Pilotgroup][password]" value="" class="pilotgroupPassword" disabled="disabled" style="width:185px !important;"/></td>
			</tr>
		  <tr>
        <td>Confirm Password :</td>
		<td><input type="password" id="ConfirmPilotgroupPassword" maxlength="20" name="data[Pilotgroup][confim_password]" value="" class="pilotgroupPassword" disabled="disabled" style="width:185px !important;" /></td>
			</tr>
		<tr>
        <td>Email Address :</td>
		<td><input type="text" id="PilotgroupEmailAddress" maxlength="150" name="data[Pilotgroup][emailaddress]" value="<?php echo isset($data['Pilotgroup']['emailaddress']) ? $data['Pilotgroup']['emailaddress'] : ''?>" class="noSpace" style="width:180px;" /></td>
			</tr>
		<tr>
		<tr>
        <td valign="top">CC To :</td>
		<td>
		<textarea id="PilotgroupCCEmailAddress" rows="3" style="width:185px;" name="data[Pilotgroup][cc_emailaddress]" class="noSpace"><?php echo isset($data['Pilotgroup']['cc_emailaddress']) ? $data['Pilotgroup']['cc_emailaddress'] : ''?></textarea>		
		<!-- <input type="text" id="PilotgroupCCEmailAddress" maxlength="150" name="data[Pilotgroup][cc_emailaddress]" value="<?php //echo isset($data['Pilotgroup']['cc_emailaddress']) ? $data['Pilotgroup']['cc_emailaddress'] : ''?>" class="noSpace" style="width:180px;" />
		<br/><font>Multiple email address can be passed separated by semi colon (;)</font> -->
		</td>
			</tr>
		<tr>		
        <td>Department Name :</td>
		<td>
				<?php //echo $form->input('product_family_id', array('label'=>false,'options' => $arrProductFamilyNames,'div'=>false)); ?>			
						<select id="PilotgroupDepartmentName" name="data[Pilotgroup][department_id]" style="width:190px !important; height:18px; border:1px solid #CCCCCC">
						<?php foreach($arrDepartmentnames as $key=>$value): ?>								
								<option value="<?php echo $key?>"><?php echo $value; ?></option>					
						<?php endforeach; ?>
						</select>
				
				</td>
			</tr>		
			<tr>
        
				<td nowrap="nowrap" colspan="2" align="center">
					<span id="PilotgroupName_err1" class="hideElement errormsg">Enter Username</span>
					<span id="PilotgroupName_err2" class="hideElement errormsg">Space character is not allowed</span>
					<span id="PilotgroupFirstName_err1" class="hideElement errormsg">Enter First Name</span>
					<span id="PilotgroupFirstName_err2" class="hideElement errormsg">Special character is not allowed in First Name</span>
					<span id="PilotgroupSurName_err1" class="hideElement errormsg">Enter Last name</span>
					<span id="PilotgroupSurName_err2" class="hideElement errormsg">Special character is not allowed in Last Name</span>
					<span id="PilotgroupPassword_err1" class="hideElement errormsg">Enter Password</span>
					<span id="PilotgroupPassword_err2" class="hideElement errormsg">Password length be 6 - 20 characters.</span>
					<span id="PilotgroupPassword_err3" class="hideElement errormsg">Password can not have spaces.</span>
					<span id="PilotgroupEmailAddress_err1" class="hideElement errormsg">Enter Email Address</span>
					<span id="PilotgroupEmailAddress_err2" class="hideElement errormsg">Enter valid Email Address</span>
					<span id="PilotgroupCCEmailAddress_err1" class="hideElement errormsg">Enter valid Email Address for CC To</span>
					
					<div id="passwordErrorMsg" class="hideElement errormsg">Error</div>
				</td>
      </tr>
       
    
<?php } ?>

<?php if($table_name == 'practicearea') {?>
  <?php echo $form->create(null, array('action'=>'addnew/practicearea','id'=>'PracticeareaForm','name'=>'PracticeareaForm','onSubmit'=>'return  blankfunc1("PracticeareaPracticeArea","Practicearea")'));?>
   
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Practicearea_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%">Practice Area :</td>
				<td width="50%"><input type="text" id="PracticeareaPracticeArea" maxlength="255" name="data[Practicearea][practice_area]" value="<?php echo isset($data['Practicearea']['practice_area']) ? $data['Practicearea']['practice_area'] : ''?>" autocomplete="off" /></td>
			</tr>
				<tr>
				<td colspan="2" nowrap="nowrap" align="center">
				   <span id="PracticeareaPracticeArea_err1" class="hideElement errormsg">Enter Practice Area</span>
				   <span id="PracticeareaPracticeArea_err2" class="hideElement errormsg">Enter alphabets only</span>
				</td>
      </tr>
    
<?php } ?>

<?php if($table_name == 'productfamilyname') {?>
  <?php echo $form->create(null, array('action'=>'addnew/productfamilyname','id'=>'ProductfamilynameForm','name'=>'ProductfamilynameForm','onSubmit'=>'return  blankfunc1("ProductfamilynameFamilyName","Productfamilyname")'));?>
   
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Productfamilyname_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%" nowrap>Product Family Name :</td>
				<td width="50%"><input type="text" id="ProductfamilynameFamilyName" maxlength="255" name="data[Productfamilyname][family_name]" value="<?php echo isset($data['Productfamilyname']['family_name']) ? $data['Productfamilyname']['family_name'] : ''?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td colspan="2" nowrap="nowrap" align="center">
				  <span id="ProductfamilynameFamilyName_err1" class="hideElement errormsg">Enter Product Family Name</span>
				   <span id="ProductfamilynameFamilyName_err2" class="hideElement errormsg">Enter alphabets only</span>
					
				</td>
      </tr>
   
<?php } ?>

<?php if($table_name == 'statusinsight') {?>
  <?php echo $form->create(null, array('action'=>'addnew/statusinsight','id'=>'StatusinsightForm','name'=>'StatusinsightForm','onSubmit'=>'return  blankfunc1("StatusinsightStatus","Statusinsight")'));?>
   
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Statusinsight_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%" nowrap>Feedback Status :</td>
				<td width="50%"><input type="text" id="StatusinsightStatus" maxlength="255" name="data[Statusinsight][status]" value="<?php echo isset($data['Statusinsight']['status']) ? $data['Statusinsight']['status'] : ''?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td colspan="2" nowrap="nowrap" align="center">
				  <span id="StatusinsightStatus_err1" class="hideElement errormsg">Enter Feedback Status</span>
				   <span id="StatusinsightStatus_err2" class="hideElement errormsg">Enter alphabets only</span>
					
				</td>
      </tr>
   
<?php } ?>

<?php if($table_name == 'productname') {?>
  <?php echo $form->create(null, array('action'=>'addnew/productname','id'=>'ProductnameForm','name'=>'ProductnameForm','onSubmit'=>'return blankfunc2("ProductnameProductCode","ProductnameProductName","Productname","ProductnameProductFamilyId")'));?>
    
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Productname_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="60%">Product Code :</td>
				<td width="40%"><input type="text"  id="ProductnameProductCode" maxlength="40" name="data[Productname][product_code]" value="<?php echo isset($data['Productname']['product_code']) ? $data['Productname']['product_code'] : ''?>" autocomplete="off" /></td>
			</tr>
				<tr>
        <td width="60%">&nbsp;</td>
				<td width="40%">
				  <span id="ProductnameProductCode_err1" class="hideElement errormsg">Enter Product Code</span>
				   <span id="ProductnameProductCode_err2" class="hideElement errormsg">Enter alphanumeric entries</span>
					
				</td>
      </tr>
       <tr>
        <td width="60%">Product Name :</td>
		<td width="40%"><input type="text" id="ProductnameProductName" maxlength="255" name="data[Productname][product_name]" value="<?php echo isset($data['Productname']['product_name']) ? $data['Productname']['product_name'] : ''?>" autocomplete="off" /></td>
	</tr>
	<tr>
        <td width="60%">&nbsp;</td>
				<td width="40%">
				  <span id="ProductnameProductName_err1" class="hideElement errormsg">Enter Product Name</span>
				   <span id="ProductnameProductName_err2" class="hideElement errormsg">Enter alphanumeric entries</span>
					 
				</td>
      </tr>
	  <tr>
		<td width="60%">Product Family Name :</td>
		<td width="40%">
					<?php //echo $form->input('product_family_id', array('label'=>false,'options' => $arrProductFamilyNames,'div'=>false)); ?>			
						<select id="ProductnameProductFamilyId" name="data[Productname][product_family_id]" style="border:1px solid #CCCCCC">
						<option value=""></option>
						<?php foreach($arrProductFamilyNames as $key=>$value): ?>								
								<option value="<?php echo $key?>"><?php echo $value; ?></option>					
						<?php endforeach; ?>
						</select>
					</td>
	</tr>
	<tr>
        <td width="60%">&nbsp;</td>
	<td width="40%">
	  <span id="ProductnameProductFamilyId_err1" class="hideElement errormsg">Select Product Family Name</span>					 
	</td>
      </tr>
  
<?php } ?>

<?php if($table_name == 'productarea') {?>
  <?php echo $form->create(null, array('action'=>'addnew/productarea','id'=>'ProductareaForm','name'=>'ProductareaForm','onSubmit'=>'return  blankfunc1("ProductareaProductArea","Productarea")'));?>
   
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Productarea_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%">Product Area :</td>
				<td width="50%"><input type="text" id="ProductareaProductArea" maxlength="255" name="data[Productarea][product_area]" value="<?php echo isset($data['Productarea']['product_area']) ? $data['Productarea']['product_area'] : ''?>" autocomplete="off" /></td>
			</tr>
				<tr>
				<td colspan="2" nowrap="nowrap" align="center">
				   <span id="ProductareaProductArea_err1" class="hideElement errormsg">Enter Product Area</span>
				   <span id="ProductareaProductArea_err2" class="hideElement errormsg">Enter alphabets only</span>
				</td>
      </tr>
    
<?php } ?>
<?php if($table_name == 'sellingobstacle') {?>
  <?php echo $form->create(null, array('action'=>'addnew/sellingobstacle','id'=>'SellingobstacleForm','name'=>'SellingobstacleForm','onSubmit'=>'return  blankfunc1("SellingobstacleSellingObstacle","Sellingobstacle")'));?>
   
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Sellingobstacle_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
			<tr>
        <td width="50%">Selling Obstacles :</td>
				<td width="50%"><input type="text" id="SellingobstacleSellingObstacle" maxlength="255" name="data[Sellingobstacle][selling_obstacles]" value="<?php echo isset($data['Sellingobstacle']['selling_obstacles']) ? $data['Sellingobstacle']['selling_obstacles'] : ''?>" autocomplete="off" /></td>
			</tr>
				<tr>
				<td colspan="2" nowrap="nowrap" align="center">
				   <span id="SellingobstacleSellingObstacle_err1" class="hideElement errormsg">Enter Selling Obstacle</span>
				   <span id="SellingobstacleSellingObstacle_err2" class="hideElement errormsg">Enter alphabets only</span>
				</td>
      </tr>
    
<?php } ?>
<?php if($table_name == 'departmentname') {?>
  <?php echo $form->create(null, array('action'=>'addnew/departmentname','id'=>'DepartmentnameForm','name'=>'DepartmentnameForm','onSubmit'=>'return blank_specialfunc("DepartmentnameDepartmentName","Departmentname")'));?>
     
	<tr>
		<td colspan="2" nowrap="nowrap"  align="center"><span id="Departmentname_err1" class="<?php echo $printmsg;?> errormsg"><?php print $addcotnent; ?> already exists</span>&nbsp;</td>
	</tr>
	<tr>
        <td width="40%">Department Name :</td>
		<td><input type="text" id="DepartmentnameDepartmentName" maxlength="50" name="data[Departmentname][department_name]" value="<?php echo isset($data['Departmentname']['department_name']) ? $data['Departmentname']['department_name'] : ''?>" autocomplete="off" style="width:180px;" /></td>
			  <span id="err" ></span>
    </tr>
	<tr>
				<td colspan="2" nowrap="nowrap" align="center" height="20">
					<span id="DepartmentnameDepartmentName_err1" class="hideElement errormsg">Enter Department Name</span>
					<span id="DepartmentnameDepartmentName_err2" class="hideElement errormsg">Special Characters are not allowed.</span>
				</td>
      </tr>   

<?php } ?>

 <tr>
		<td colspan="2">	
		
		<div class="buttonrow" style="text-align:center !important;"><input type="submit" name="submit" value="Submit" id="add_submit" />
		<!--<div class="buttonrow cancel_addnew ">&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" id="add_cancel" onClick = "window.location.href='<?php //echo SITE_URL;?>/editrecords/showlist/<?php //echo $table_name."s";?>'"/></div>-->
		&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" id="add_cancel" onClick = "javascript:parent.parent.GB_hide();"/></div>
		<!--<a href="<?php //echo SITE_URL;?>/editrecords/showlist/<?php //echo $table_name."s";?>" class="cancelbtn" style="text-decoration:none; color:#FFFFFF;">Cancel</a>-->
		
		</td>
 </tr>
<?php echo $form->end(); ?>

 </table>  
</div>  