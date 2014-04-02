<div id="textcontainer">
	<div class="hr-row">
	    <?php echo $form->create('Customer', array('type'=>'file','action'=>'/records/'.$id,'id'=>'customerInsightForm','name'=>'customerInsightForm', 'method' => 'post'));?>
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<div id="errCustomerAttachmentExtension" class="successmsg" style="display:<?php echo $successDivSave?>;">Record saved successfully.</div>
		<tr>
			<td colspan="6"><h3>What is your insight about? Select one option only: <span class="red">*</span></h3></td>
		</tr>
		<tr>
			<td colspan="6" class="selectoption">
				<label>
					<input name="data[Customer][what_insight_type]" id="what_insight_type1" type="radio" value="CUSTOMER" onclick="javascript:changeInsightType(this.value,'customers','index','<?php echo SITE_URL?>');" disabled="disabled" checked="checked" />
					Customer Insight
				</label>
				<label>
					<input name="data[Customer][what_insight_type]" id="what_insight_type2" type="radio" value="MARKET" onclick="javascript:changeInsightType(this.value,'markets','index','<?php echo SITE_URL?>');"  disabled="disabled" />
					Market Insight
				</label>
				<label>
					<input name="data[Customer][what_insight_type]" id="what_insight_type3" type="radio" value="PRODUCT" onclick="javascript:changeInsightType(this.value,'products','index','<?php echo SITE_URL?>');"  disabled="disabled"/>
					Product Insight
				</label>
				<label>
					<input name="data[Customer][what_insight_type]" id="what_insight_type4" type="radio" value="COMPETITOR" onclick="javascript:changeInsightType(this.value,'competitors','index','<?php echo SITE_URL?>');" disabled="disabled"/>
					Competitor Insight
				</label>					
			</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<td width="21%">How did this insight come about? :</td>
			<td width="18%">
				<label>
					<?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false,'disabled'=>true)); ?>					
					<?php echo $form->hidden('what_how_come'); ?>
				</label>
			</td>
			<td width="12%">Name of Source :</td>
			<td width="21%">
				<label>
					<?php echo $form->input('what_source_name',array('label'=>false,'class'=>'text-box1 readonlycls','size'=>'25','div'=>false, 'readonly' => 'readonly'));?>
				</label>
			</td>
			<td width="9%">Firm Name :</td>
			<td width="19%"><?php echo $ajax->autoComplete('Firm.what_firm_name', '/firms/autoCompleteFirms', array('minChars' => 2,'size'=>'25', 'class'=>'search-input-new',  'readonly' => 'readonly', 'class' => 'text-box1 readonlycls'))?></td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row">
		<table width="100%" border="0" cellpadding="0" cellspacing="5">
		<tr>
			<td colspan="6"><h3>Who is the insight about? If the Firm Name of Account Number is not listed please add it to the relevant field:</h3></td>
		</tr>
		<tr>
			<td width="16%">Firm Name :</td>
			<td width="19%"><?php echo $ajax->autoComplete('Firm.who_firm_name', '/firms/autoCompleteOtherFirms', array('minChars' => 2, 'readonly' => 'readonly', 'class' => 'search-input-new readonlycls'))?></td>
			<td width="14%">&nbsp;</td>
			<td width="18%">&nbsp;</td>
			<td width="16%">&nbsp;</td>
			<td width="17%">&nbsp;</td>
		</tr>
		<tr>
			<td>Account Number :</td>
			<td><?php echo $ajax->autoComplete('Firm.who_account_no', '/firms/autoCompleteFirmsAccountNo', array('minChars' => 2, 'readonly' => 'readonly', 'class' => 'search-input-new readonlycls'))?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6"><h3>Enter the Contact Name and Role :</h3></td>
		</tr>
		<tr>
			<td>Contact Name :</td>
			<td><?php echo $form->input('who_contact_name',array('label'=>false,'class'=>'text-box1 readonlycls','size'=>'25','div'=>false, 'readonly' => 'readonly'));?></td>
			<td>Contact Role :</td>
			<td><?php echo $form->input('who_contact_role',array('label'=>false,'class'=>'text-box1 readonlycls','size'=>'25','div'=>false, 'readonly' => 'readonly'));?></td> 
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td><h3>Add your summary of the insight here : <span class="red">*</span></h3></td>
		</tr>
		<tr>
			<td>
				<?php echo $form->input('insight_summary',array('rows'=>'7','label'=>false,'class'=>'summary readonlycls','div'=>false, 'readonly' => 'readonly'));?>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td><h3>Classify what your insight relates to below. This will help to ensure the right department is informed :</h3></td>
		</tr>
		<tr>
			<td>
				<table width="80%" border="0" align="left" cellpadding="0" cellspacing="5">
				<tr>
					<td width="33%" valign="top"><h4>Competitor Name</h4>
						<div>
						<?php if($edit_flag == "") { ?>						
							<span id='CustomerRelatesCompetitorId_dummy_label'><?php echo $form->input('relates_competitor_id_dummy', array('label'=>false,'options' => $arrRelatesCompetetiors,'div'=>false, 'disabled' => 'disabled', 'selected'=>$relates_competitor_name_label)); ?></span>
							<?php echo $form->input('relates_competitor_id', array('label'=>false,'options' => $arrRelatesCompetetiors,'div'=>false, 'style' => 'display: none', 'selected'=>$relates_competitor_name_label)); ?>
						<?php }else{ ?>
							<?php echo $form->input('relates_competitor_id', array('label'=>false,'options' => $arrRelatesCompetetiors,'div'=>false, 'selected'=>$relates_competitor_name_label)); ?>
						<?php } ?>
						</div>
					</td>
					<td width="33%" valign="top"><h4>LexisNexis Product Family Name</h4>
						<div>
						<?php if($edit_flag == "") { ?>						
							<span id='CustomerRelatesProductFamilyId_dummy_label'><?php echo $form->input('relates_product_family_id_dummy', array('label'=>false,'options' => $arrProductFamilyNames,'div'=>false, 'disabled' => 'disabled', 'selected'=>$product_family_name_label)); ?></span>
							<?php echo $form->input('relates_product_family_id', array('label'=>false,'options' => $arrProductFamilyNames,'div'=>false, 'style' => 'display: none')); ?>
						<?php }else{ ?>
							<?php echo $form->input('relates_product_family_id', array('label'=>false,'options' => $arrProductFamilyNames,'div'=>false,)); ?>
						<?php } ?>
						</div>
					</td>
					<td width="34%" valign="top"><h4>Practice Area</h4>
						<div>
						<?php if($edit_flag == "") { ?>						
							<span id='CustomerPracticeAreaId_dummy_label'><?php echo $form->input('practice_area_id_dummy', array('label'=>false,'options' => $arrPracticeArea,'div'=>false,  'disabled' => 'disabled', 'selected' => $practice_area_label)); ?></span>
							<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false,  'style' => 'display: none')); ?>
						<?php }else{ ?>
							<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false)); ?>
						<?php } ?>
						</div>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td><h3>If anything. What should be done? :</h3></td>
		</tr>
		<tr>
			<td>
				<?php if($edit_flag == "") { ?>				
					<span id='CustomerDoAction_dummy_label'><?php echo $form->input('do_action_dummy',array('rows'=>'7','label'=>false,'class'=>'summary readonlycls','div'=>false, 'readonly' => 'readonly', 'value' => $do_action_dummy));?></span>
					<?php echo $form->input('do_action',array('rows'=>'7','label'=>false,'class'=>'summary','div'=>false, 'style' => 'display:none;'));?>
				<?php }else{ ?>
					<?php echo $form->input('do_action',array('rows'=>'7','label'=>false,'class'=>'summary','div'=>false));?>
				<?php } ?>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td><h3>Attachment</h3></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="5" cellpadding="0">
					<tr><?php 
							if(isset($attachment_real_name) && trim($attachment_real_name) != ""){
								$fileattached = '<a id="attachment_namelink"  href="#" onclick="javascript:open_attachment(\''.SITE_URL.'/files/customer/'.$attachment_name.'\'); return false;">'.$attachment_real_name.'</a>
												<a class="attachment_removelink" id="attachment_removelink" style="display:none;" href="" onclick="javscript: remove_attachment(\''.SITE_URL.'/customers/remove_attachment/'.$id.'/'.base64_encode($attachment_name).'\'); return false;"><img align="absmiddle" id="remicon_record" src="'.SITE_URL.'/img/remove.gif"/></a><script>var attachmentfound = true;</script>';
							}else{
								$fileattached = "No attachment";
							} ?>
						<td colspan="2" align="left">Filename :&nbsp;&nbsp;<?php print $fileattached; ?></td>
					</tr>
					<tr>
						<td width="17%" align="left">&nbsp;</td>
						<td width="83%">
							<label>
								<?php if($edit_flag == "") { ?>
									<?php echo $form->hidden('CustomerAttachment.old_attachment_name',array('value'=>$attachment_name)); ?>
									<?php echo $form->hidden('CustomerAttachment.old_attachment_real_name',array('value'=>$attachment_real_name)); ?>
									<span id="attach_file" style="display:none;">
										<?php echo  $form->file('CustomerAttachment.attachment_name',array('size'=>'30')) ?>
										<div id="errCustomerAttachmentExtension" class="errormsg" style="display:<?php echo $errDivAttachment?>;">Invalid Attachment.</div>
										<div id="errCustomerAttachmentSize" class="errormsg" style="display:<?php echo $errDivAttachmentSize?>;">Attachment size can not be more than 5 MB.</div>
									</span>	
								<?php }else{ ?>
										<?php echo  $form->file('CustomerAttachment.attachment_name',array('size'=>'30')) ?>
										<div id="errCustomerAttachmentExtension" class="errormsg" style="display:<?php echo $errDivAttachment?>;">Invalid Attachment.</div>
										<div id="errCustomerAttachmentSize" class="errormsg" style="display:<?php echo $errDivAttachmentSize?>;">Attachment size can not be more than 5 MB.</div>
								<?php } ?>
							</label>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
	<div class="hr-row" id="current_status_nk">
			<table width="60%" border="0" cellspacing="5" cellpadding="0">
			<tr>
			<?php 
						if($this->Session->check('current_user_role')) {
							$current_user_role = $this->Session->read('current_user_role');
						}

						if(isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) {
			?>
							<?php echo $form->hidden('insight_status_changed', array('value'=>1, 'disabled' => TRUE)); ?>
							<td width="50%">Current Status: <?php echo $form->input('insight_status', array('label'=>false,'options' => $arrStatusList, 'div'=>false, 'selected'=>$current_status_label, 'disabled' => TRUE, 'onchange' => 'show_delegated_to("Customer");')); ?></td>
							<td width="*"><div id="delegated_user">Delegated to: <?php echo $form->input('deligated_to', array('label'=>false,'options' => $arrCreatedBy, 'div'=>false, 'selected' => $deligated_to_selected, 'disabled' => TRUE)); ?></div></td>
				<?php } else { ?>
							<td width="50%">Current Status: <?php echo $form->input('insight_status', array('label'=>false,'options' => $arrStatusList, 'div'=>false, 'selected'=>$current_status_label, 'disabled' => TRUE)); ?></td>
							<td width="*"><div id="delegated_user">Delegated to: <?php echo $form->input('deligated_to', array('label'=>false,'options' => $arrCreatedBy, 'div'=>false, 'disabled' => TRUE, 'selected' => $deligated_to_selected )); ?></div></td>
        <?php } ?>

			</tr>
			</table>
	</div>
	<div class="history_link buttonrow"><a href="<?php print $backUrl;?>">Back to search result page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php print SITE_URL; ?>/">Home</a></div>	
	<div class="buttonrow"><input name="cancel" type="button" value="Edit" onclick="activateCustomerEditFields('CustomerRelatesCompetitorId', 'CustomerRelatesProductFamilyId', 'CustomerPracticeAreaId', 'CustomerDoAction');" />&nbsp;
	<input name="submitCustomersInsight" id="submitCustomersInsight" type="submit" value="Submit" /></div>
	<?php echo $form->hidden('user_id',array('value'=>1)); ?>
	<?php echo $form->hidden('id',array('value'=>$id)); ?>	
	<?php echo $form->end(); ?>	
</div>