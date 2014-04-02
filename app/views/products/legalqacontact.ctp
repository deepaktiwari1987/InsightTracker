<div id="" class = "hr-row" >

		<?php if($successDivSave=="block"){ ?>
		<table  border="0" cellspacing="5" cellpadding="5" align="center">
			<tr>
				<td height="12" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td height="12" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td height="12" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td height="12" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td height="12" align="center">
					<div id="errProductAttachmentExtension" class="successmsg" style="text-align:center;font-weight:bold;display:<?php echo $successDivSave?>;">E-Mail sent successfully to the Moderator.</div></td>
			</tr>	
			<tr>
				<td height="12" align="center">&nbsp;</td>
			</tr>	
			<tr>
				<td height="12" align="center"><div class="buttonrow" style="text-align:center;">
								<input type="button" name="Close" value="Close" id="" onClick = "javascript:closePopup();"/>
							</div>		</td>
			</tr>	
		</table>
		
		<?php }else{?>
				<table  border="0" cellspacing="5" cellpadding="5" align="center" width="100%">
				<?php echo $form->create(null, array('action'=>'contact','id'=>'ContactForm','name'=>'ContactForm','onSubmit'=>'return blank_func2( "EditrecordProductSubject","EditrecordProductMessage","Product")')); ?>
				<input type="hidden"  name="data[Product][insight_id]" value="<?php echo $insight_id; ?>" />
				<tr>
					<td height="12" align="right"><b><?php echo ($this->Session->read('current_user_name')!='')?$this->Session->read('current_user_name'):'';?></b></td>
				</tr>	
				</table>		
					<table  width="90%" border="0" cellspacing="2" cellpadding="2" align="center">
						<tr>
							<td colspan="2" nowrap="nowrap" align="left"><B>Subject:<span class="red">*</span></B><br/>
							<input type="text" id="EditrecordProductSubject" maxlength="255" name="data[Product][subject]" value="" size="65"/></td>
						</tr>
						<tr>
							<td colspan="2"  align="center">&nbsp;</td>
						</tr>	
						<tr>
							<td colspan="2" nowrap="nowrap" align="left"><B>Message:<span class="red">*</span></B><br/>
							<textarea id="EditrecordProductMessage" style="width:360px" rows="6" name="data[Product][message_text]"></textarea>
							
							<span id="EditrecordProductSubject_err1" class="hideElement errormsg">Enter subject for message.</span>
							<span id="EditrecordProductMessage_err1" class="hideElement errormsg">Enter message for Moderator.</span>
							
							</td>
						</tr>
						<tr>
							<td colspan="2">		
							<div class="buttonrow" style="text-align:center;">
								<input type="button" name="Cancel" value="Cancel" id="" onClick = "javascript:parent.parent.GB_hide();"/>
								&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" id="add_submit"/>
							</div>		
							</td>
						</tr>
						<?php echo $form->end(); ?>
				<?php }?>
			</table>  
		</div>  

<script>
function closePopup()
{
	<?php if($_SESSION['RedirectToHomePage'] =='Y'){ ?>
	parent.parent.window.location.href = '<?php echo SITE_URL;?>';
	<?php }	?>

	parent.parent.GB_hide();

}

</script>