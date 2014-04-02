<div id="textcontainer">
	<?php echo $form->create('Product', array('type'=>'file','action'=>'/records','id'=>'productInsightForm','name'=>'productInsightForm','onsubmit'=>'return validateCustomerInsight("add");'));?>
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<div id="errCompetitorAttachmentExtension" class="successmsg" style="display:<?php echo $successDivSave?>;">New product insight record saved successfully.</div>
		<tr>
			<td colspan="6"><h3>What is your insight about? Select one option only: <span class="red">*</span></h3></td>
		</tr>
		<tr>
			<td colspan="6" class="selectoption">
				<label>
					<input name="data[Product][what_insight_type]" id="what_insight_type1" type="radio" value="CUSTOMER" disabled="disabled" /> <!--onclick="javascript:changeInsightType(this.value,'customers','index','<?php //echo SITE_URL?>');"-->
					Customer Insight
				</label>
				<label>
					<input name="data[Product][what_insight_type]" id="what_insight_type2" type="radio" value="MARKET" disabled="disabled" /> <!--onclick="javascript:changeInsightType(this.value,'markets','index','<?php //echo SITE_URL?>');"-->
					Market Insight
				</label>
				<label>
					<input name="data[Product][what_insight_type]" id="what_insight_type3" type="radio" value="PRODUCT" onclick="javascript:changeInsightType(this.value,'products','index','<?php echo SITE_URL?>');" checked='checked' />
					Product Insight
				</label>
				<label>
					<input name="data[Product][what_insight_type]" id="what_insight_type4" type="radio" value="COMPETITOR" onclick="javascript:changeInsightType(this.value,'competitors','index','<?php echo SITE_URL?>');" />
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
					<?php echo $form->input('what_source_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false,'disabled'=>true));?>
					<?php echo $form->hidden('what_source_name'); ?>
				</label>
			</td>
			<td width="9%">Firm Name :</td>
			<td width="19%">
				<?php echo $ajax->autoComplete('Firm.what_firm_name', '/firms/autoCompleteFirms', array('minChars' => 3,'class'=>'search-input-new','disabled'=>true))?>
				<?php echo $form->hidden('Firm.what_firm_name'); ?>
			</td>
		</tr>
		</table>
	</div>

	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td colspan="4"><h3>What Product is the insight about? If the Product Name is not listed please add it to the field :</h3></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td width="21%">LexisNexis Product Family Name :</td>
			<td width="17%">
				<?php echo $ajax->autoComplete('Productfamilyname.product_family_id', '/productfamilynames/autoCompleteProductFamilyNames', array('minChars' => 3,'class'=>'search-input-new','disabled'=>true))?>
				<?php echo $form->hidden('Productfamilyname.product_family_id'); ?>
			</td>
			<td width="21%">LexisNexis Product Name :</td>
			<td width="41%">
				<?php echo $ajax->autoComplete('Productname.product_id', '/productnames/autoCompleteProductNames', array('minChars' => 3,'class'=>'search-input-new','disabled'=>true))?>
				<?php echo $form->hidden('Productname.product_id'); ?>
			</td>
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
				<?php echo $form->input('insight_summary',array('rows'=>'7','label'=>false,'class'=>'summary','div'=>false,'disabled'=>true));?>
				<?php echo $form->hidden('insight_summary'); ?>
				<div id="errProductInsightSummary" class="errormsg" style="display:<?php echo $errDivProductInsightSummary?>;">Required.</div>
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
			<table width="57%" border="0" align="left" cellpadding="0" cellspacing="5">
			<tr>
				<td width="33%" valign="top"><h4>Content Type</h4>
					<div>
						<?php echo $form->input('content_type_id', array('label'=>false,'options' => $arrContentTypes,'div'=>false,'disabled'=>true)); ?>
						<?php echo $form->hidden('content_type_id'); ?>
					</div>
				</td>
				<td width="34%" valign="top"><h4>Practice Area</h4>
					<div>
						<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false,'disabled'=>true)); ?>
						<?php echo $form->hidden('practice_area_id'); ?>
					</div>
				</td>
			</tr>
			</table></td>
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
				<?php echo $form->input('do_action',array('rows'=>'7','label'=>false,'class'=>'summary','div'=>false,'disabled'=>true));?>
				<?php echo $form->hidden('do_action'); ?>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td><h3>Add Attachment</h3></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="5" cellpadding="0">
				<tr>
					<td width="17%" align="left">Filename :</td>
					<td width="83%">
						<label>
							<?php echo $savedAttachmentName?>
							<?php //echo  $form->file('ProductAttachment.attachment_name',array('size'=>'30','disabled'=>true)) ?>
							<?php echo $form->hidden('attachment_name'); ?>
							<?php echo $form->hidden('attachment_real_name'); ?>
							<div id="errProductAttachmentExtension" class="errormsg" style="display:<?php echo $errDivAttachment?>;">Invalid Attachment.</div>
							<div id="errProductAttachmentSize" class="errormsg" style="display:<?php echo $errDivAttachmentSize?>;">Attachment size can not be more than 5 MB.</div>
						</label>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
	<div class="buttonrow"><input name="cancel" type="button" value="Edit" onclick="activateEditFields('ProductRelatesContentType', 'ProductRelatesPracticeArea', 'ProductDoAction', 'submitProductInsight');" />&nbsp;
	<input name="submitProductInsight" id="submitProductInsight" type="submit" value="Submit" /></div>
	<?php echo $form->hidden('id',array('value'=>$id)); ?>
	<?php echo $form->hidden('hdnEditEnabled'); ?>
	<?php echo $form->end(); ?>
</div>