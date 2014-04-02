<div id="" class = "hr-row">
		
		<div class="hr-row"  style="display:<?php echo $errDisplay;?>;font-weight:bold;color:red;width:auto; overflow-x:auto; overflow-y:hidden;">Issue Description already exists.</div>
    <table  width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
  <?php echo $form->create(null, array('action'=>'editissue','id'=>'IssueForm','name'=>'IssueForm','onSubmit'=>'return blank_func2("EditrecordIssueTitle", "EditrecordIssueDescription", "Issue")')); ?>
  	<input type="hidden"  name="data[Issue][id]" value="<?php print $issue_rec['Issue']['id']; ?>" />  
  	<input type="hidden"  name="data[Issue][product_family_id]" value="<?php echo $product_family_id; ?>" />  
  	<input type="hidden"  name="data[Issue][practice_area_id]" value="<?php echo $practice_area_id; ?>" />  
  	<input type="hidden"  name="data[Issue][selling_obstacle_id]" value="<?php echo $selling_obstacle_id; ?>" />  
		<tr>
			<td nowrap><B>Issue Description:<span class="red">*</span></B> </td>
		</tr>
		<tr>
			<td><input type="text" id="EditrecordIssueTitle" maxlength="50" size="55" name="data[Issue][issue_title]" value="<?php print $issue_rec['Issue']['issue_title']?>" autocomplete="off" disabled="true" /></td>
		</tr>
		<tr>
			<td nowrap>&nbsp;</td>
		</tr>
		<tr>
			<td nowrap><B>Issue Status:<span class="red">*</span></B> </td>
		</tr>
		<tr>
			<td><textarea id="EditrecordIssueDescription" disabled="true" style="width:420px" rows="6" name="data[Issue][issue_description]"><?php print $issue_rec['Issue']['issue_description']?></textarea></td>
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
			<td>				
			<div class="buttonrow" style="text-align:center !important;">	<input type="button" name="cancel" value="Cancel" id="add_cancel" onClick = "javascript:parent.parent.GB_hide();"/>		
			&nbsp;&nbsp;<input type="button" name="edit" value="Edit" id="edit_submit" onclick="javascript:document.getElementById('EditrecordIssueTitle').disabled=false;document.getElementById('EditrecordIssueDescription').disabled=false;"/>	
			&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" id="add_submit" onclick="javascript:parent.parent.parent.document.getElementById('ProductIssueDescription').value=document.getElementById('EditrecordIssueDescription').value; parent.parent.parent.document.getElementById('ProductIssueField').options[parent.parent.parent.document.getElementById('ProductIssueField').selectedIndex].text=document.getElementById('EditrecordIssueTitle').value;" /></div>		
			</td>
		</tr>
<?php echo $form->end(); ?>

 </table>  
</div>  