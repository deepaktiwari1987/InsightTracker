<div id="" class = "hr-row" >
		<table  border="0" cellspacing="0" cellpadding="5" align="center">
		<tr><td>&nbsp;</td></tr>
		<tr>
					<td>
							<?php print '<h2>Edit User</h2>'; ?>
					</td>
					<td>&nbsp;</td>
				</tr>
		</table>
		<div class="hr-row"  style="width:auto; overflow-x:auto; overflow-y:hidden;" ></div>
    <table  width="65%" border="0" cellspacing="4" cellpadding="5" align="center">
  <?php echo $form->create(null, array('action'=>'edituser/pilotgroup/'.$usre_data['Pilotgroup']['id'],'id'=>'PilotgroupForm','name'=>'PilotgroupForm','onSubmit'=>'return userAddNewformValidate("PilotgroupName", "PilotgroupPassword", "PilotgroupEmailAddress", "PilotgroupCCEmailAddress", "PilotgroupFirstName", "PilotgroupSurName", "Pilotgroup")'));?>
	<input type="hidden"  name="data[Pilotgroup][id]" value="<?php print $usre_data['Pilotgroup']['id']; ?>" />  
	<input type="hidden"  name="data[Pilotgroup][confirm_name]" value="<?php print $usre_data['Pilotgroup']['name']; ?>" />  	
	<tr>
		<td colspan="2" nowrap="nowrap" align="center"><span id="Pilotgroup_err1" class="<?php echo $printmsg;?> errormsg">User name already exists.</span>&nbsp;</td>
	</tr>
	<tr>
        <td width="40%">Name :</td>
		<td><input class="noSpace" type="text" id="PilotgroupName" maxlength="40" name="data[Pilotgroup][name]" value="<?php echo isset($usre_data['Pilotgroup']['name']) ? htmlentities($usre_data['Pilotgroup']['name']) : ''?>" autocomplete="off" style="width:180px;" /></td>
	</tr>
	<tr>
        <td>First Name :</td>
		<td><input type="text" id="PilotgroupFirstName" maxlength="255" name="data[Pilotgroup][first_name]" value="<?php echo isset($usre_data['Pilotgroup']['first_name']) ? $usre_data['Pilotgroup']['first_name'] : ''?>" autocomplete="off" style="width:180px;" /></td>
	</tr>
	<tr>
        <td>Last Name :</td>
		<td><input type="text" id="PilotgroupSurName" maxlength="255" name="data[Pilotgroup][sur_name]" value="<?php echo isset($usre_data['Pilotgroup']['sur_name']) ? $usre_data['Pilotgroup']['sur_name'] : ''?>" autocomplete="off" style="width:180px;" /></td>
	</tr>
	<tr>
        <td>Role :</td>
				<?php //$selectedAdmin = ($usre_data['Pilotgroup']['role'] == "A")?'selected="selected"':''; ?> 
				<?php $selectedAdmin = $selectedSME = $selectedNormal = '';
					if(trim($usre_data['Pilotgroup']['role']) == "A")
					{
						$selectedAdmin = 'selected="selected"';
					}
					else if(trim($usre_data['Pilotgroup']['role']) == "S")
					{
						$selectedSME = 'selected="selected"';
					}
					else
					{
						$selectedNormal = 'selected="selected"';
					}
				?>
		<td><select id="PilotgroupRole" name="data[Pilotgroup][role]" style="width:190px !important;height:18px;border:1px solid #CCCCCC;" onchange="changeShowPass(this, 'edit')" >
														<option <?php print $selectedNormal; ?> value="">Contributor</option>
														<option <?php print $selectedAdmin; ?> value="A">Moderator</option>														
														<option <?php print $selectedSME; ?> value="S">Subject Matter Expert (SME)</option>
												</select></td>
			</tr>
		  <tr>
        <td>Active :</td>
				<?php $selectedIsactive = ($usre_data['Pilotgroup']['isactive'] == 1)?'selected="selected"':''; ?> 
		<td><select name="data[Pilotgroup][isactive]" style="width:190px !important;height:18px;border:1px solid #CCCCCC;">
														<option <?php print $selectedIsactive; ?> value="1">Yes</option>
														<option <?php print ($selectedIsactive == '')?'selected="selected"':''; ?> value="0">No</option>
												</select></td>
			</tr>
			  <tr>
				<td valign="bottom">Password :</td>
				<td><?php if($usre_data['Pilotgroup']['role'] == 'A') { ?> <a href="javascript:void(0);" id="showPassBoxLink" onclick="showPassBox('showPassBoxLink')">Change password</a><?php } ?><div style="clear:both"></div><input type="password" id="PilotgroupPassword" maxlength="40" name="data[Pilotgroup][password]" value="" class="pilotgroupPassword" disabled="disabled" style="width:185px !important;" /> </td>
			</tr>
			  <tr>
				<td>Confirm Password :</td>
				<td><input type="password" id="ConfirmPilotgroupPassword" maxlength="40" name="data[Pilotgroup][confim_password]" value="" class="pilotgroupPassword" disabled="disabled" style="width:185px !important;" /></td>
			</tr>
			<tr>
				<td>Email Address :</td>
				<td><input class="noSpace" type="text" id="PilotgroupEmailAddress" maxlength="100" name="data[Pilotgroup][emailaddress]" value="<?php echo isset($usre_data['Pilotgroup']['emailaddress']) ? htmlentities($usre_data['Pilotgroup']['emailaddress']) : ''?>" autocomplete="off" style="width:180px;" /></td>
			</tr>
		<tr>
        <td valign="top">CC To :</td>
		<td>
		<textarea id="PilotgroupCCEmailAddress" rows="3" style="width:185px;" name="data[Pilotgroup][cc_emailaddress]" class="noSpace"><?php echo isset($usre_data['Pilotgroup']['cc_emailaddress']) ? $usre_data['Pilotgroup']['cc_emailaddress'] : ''?></textarea>
		
		<!-- <input type="text" id="PilotgroupCCEmailAddress" maxlength="150" name="data[Pilotgroup][cc_emailaddress]" value="<?php //echo isset($usre_data['Pilotgroup']['cc_emailaddress']) ? $usre_data['Pilotgroup']['cc_emailaddress'] : ''?>" class="noSpace" style="width:180px;" /> -->
		</td>
			</tr>
		<tr>			
			<tr>
				<td>Department Name :</td>
				<td>
				<?php //echo $form->input('product_family_id', array('label'=>false,'options' => $arrProductFamilyNames,'div'=>false)); ?>			
						<select id="PilotgroupDepartmentName" name="data[Pilotgroup][department_id]" style="width:190px !important; height:18px; border:1px solid #CCCCCC">
						<?php foreach($arrDepartmentnames as $key=>$value): ?>								
								<option value="<?php echo $key?>" <?php if($usre_data['Pilotgroup']['department_id']== $key) {echo "selected";}?>><?php echo $value; ?></option>					
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

 <tr>
		<td colspan="2"><div class="buttonrow"><input type="submit" name="submit" value="Submit" id="add_submit" />
		<!--<div class="buttonrow cancel_addnew ">&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" id="add_cancel" onClick = "window.location.href='<?php //echo SITE_URL;?>/editrecords/showlist/<?php //echo $table_name."s";?>'"/></div>-->
		&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" id="add_cancel" onClick = "javascript:parent.parent.GB_hide();"/></div>
		<!--<a href="<?php //echo SITE_URL;?>/editrecords/showlist/<?php //echo $table_name."s";?>" class="cancelbtn" style="text-decoration:none; color:#FFFFFF;">Cancel</a>-->
		</td>
 </tr>
<?php echo $form->end(); ?>

 </table>  
</div>  