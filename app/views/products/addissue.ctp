<?php
/*
* File Name :  addissue.ctp
* Developer :  Gaurav Saini
* @author LexisNexis Development Team
* Cake Version : 1.3.4 
* @copyright Copyright (c) 2010, LexisNexis
* Functionality / Description : The purpose of this file is to display a form to add issue to the application. 
				This form contains textbox for Issue Title and textarea for issue description.

*/
?>
<div id="" class = "hr-row">
		
		<div class="hr-row"  style="display:<?php echo $errDisplay;?>;font-weight:bold;color:red;width:auto; overflow-x:auto; overflow-y:hidden;">Issue Description already exists.</div>
    <table  width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
  <?php echo $form->create(null, array('action'=>'addissue','id'=>'IssueForm','name'=>'IssueForm','onSubmit'=>'return blank_func2("EditrecordIssueTitle", "EditrecordIssueDescription","Issue")')); ?>
  	<input type="hidden"  name="data[Issue][product_family_id]" value="<?php echo $product_family_id; ?>" />  
  	<input type="hidden"  name="data[Issue][practice_area_id]" value="<?php echo $practice_area_id; ?>" />  
  	<input type="hidden"  name="data[Issue][selling_obstacle_id]" value="<?php echo $selling_obstacle_id; ?>" />  
		<tr>
			<td nowrap><B>Issue Description:<span class="red">*</span></B> </td>
		</tr>
		<tr>
			<td><input type="text" id="EditrecordIssueTitle" maxlength="50" size="55" name="data[Issue][issue_title]" value="<?php echo $issue_title?>" autocomplete="off" /></td>
		</tr>
		<tr>
			<td nowrap>&nbsp;</td>
		</tr>
		<tr>
			<td nowrap><B>Issue Status:<span class="red">*</span></B> </td>
		</tr>
		<tr>
			<td><textarea id="EditrecordIssueDescription" style="width:420px" rows="6" name="data[Issue][issue_description]"><?php echo $issue_status;?></textarea></td>
		</tr>	  
		<tr>
			<td nowrap="nowrap" align="left">
				  	<span id="EditrecordIssueTitle_err1" class="hideElement errormsg"><b>*Please provide Issue description.</b></span>
				   	<span id="EditrecordIssueTitle_err2" class="hideElement errormsg">Enter alphanumeric entries</span>
					<span id="EditrecordIssueDescription_err1" class="hideElement errormsg"><b>*Please provide a brief description / context for the Issue you have created.</b></span>
				   	<span id="EditrecordIssueDescription_err2" class="hideElement errormsg">Enter alphanumeric entries</span>
			</td>
		</tr>
		<tr>
			<td align="center">				
			<div class="buttonrow" style="text-align:center !important;">			
			<input type="button" name="cancel" value="Cancel" id="add_cancel" onClick = "javascript:parent.parent.GB_hide();"/>&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" id="add_submit" /></div>		
			</td>
		</tr>
<?php echo $form->end(); ?>

 </table>  
</div>