<div id="textcontainer">
	    <?php echo $form->create('Market', array('type'=>'file','action'=>'/index','id'=>'marketInsightForm','name'=>'marketInsightForm', 'method' => 'post'));?>
	<div class="hr-row">
		<div id="errMarketAttachmentExtension" class="successmsg" style="display:<?php echo $successDivSave?>;">New market insight record saved successfully.</div>
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td colspan="6"><h3>What is your insight about? Select one option only: <span class="red">*</span></h3></td>
		</tr>
		<tr>
			<td colspan="6" class="selectoption">
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type1" type="radio" value="CUSTOMER" onclick="javascript:changeInsightType(this.value,'customers','index','<?php echo SITE_URL?>');" />
					Customer Insight
				</label>
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type2" type="radio" value="MARKET" checked="checked" />
					Market Insight
				</label>
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type3" type="radio" value="PRODUCT" onclick="javascript:changeInsightType(this.value,'products','index','<?php echo SITE_URL?>');" />
					Product Insight
				</label>
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type4" type="radio" value="COMPETITOR" onclick="javascript:changeInsightType(this.value,'competitors','index','<?php echo SITE_URL?>');" />
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
					<?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false)); ?>
				</label>
			</td>
			<td width="12%">Name of Source :</td>
			<td width="21%">
				<label>
					<?php echo $form->input('what_source_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false));?>
				</label>
			</td>
			<td width="9%">Firm Name :</td>
			<td width="19%">
				<?php //echo $ajax->autoComplete('Firm.what_firm_name', '/firms/autoCompleteFirms', array('minChars' => 2,'class'=>'search-input-new'))?>
				<?php echo $form->input('Firm.what_firm_name', array('label'=>false,'div'=>false,'class'=>'search-input-new')); ?>
				<script type="text/javascript">
				  new Autocomplete('FirmWhatFirmName', { 
				    serviceUrl:'<?php echo SITE_URL?>/firms/autoComplete',
				    minChars:2, 
				    maxHeight:400,
				    width:400,
				    deferRequestBy:100,
				    // callback function:
				    onSelect: function(value, data){
				        //alert('You selected: ' + value + ', ' + data);
				      }
				  });
				</script>
			</td>
		</tr>
		</table>
	</div>

	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td colspan="2"><h3>What market is this insight about ? :</h3></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="9%">Market :</td>
			<td width="91%">
				<?php echo $form->input('market_id', array('label'=>false,'options' => $arrWhoMarket,'div'=>false)); ?>
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
			<td><?php echo $form->input('insight_summary',array('rows'=>'7','label'=>false,'class'=>'summary','div'=>false));?>
				<div id="errMarketInsightSummary" class="errormsg" style="display:<?php echo $errDivMarketInsightSummary; ?>;" >Required.</div>
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
					<td width="33%" valign="top">
						<h4>LexisNexis Product Family Name</h4>
						<div>
							<?php echo $form->input('relates_product_family_id', array('label'=>false,'options' => $arrProductFamilyNames,'div'=>false)); ?>
						</div>
					</td>
					<td width="34%" valign="top"><h4>Practice Area</h4>
						<div>
							<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false)); ?>
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
			<td><?php echo $form->input('do_action',array('rows'=>'7','label'=>false,'class'=>'summary','div'=>false));?></td>
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
							<?php echo  $form->file('MarketAttachment.attachment_name',array('size'=>'30')) ?>
							<div id="errMarketAttachmentExtension" class="errormsg" style="display:<?php echo $errDivAttachment?>;">Invalid Attachment.</div>
							<div id="errMarketAttachmentSize" class="errormsg" style="display:<?php echo $errDivAttachmentSize?>;">Attachment size can not be more than 5 MB.</div>
						</label>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
	<div class="buttonrow"><input name="cancel" type="button" value="Cancel" onclick="javascript:redirectUrl('<?php echo SITE_URL?>/customers/home');" />&nbsp;<input name="submitMarketInsight" type="submit" value="Submit" /></div>
	<?php echo $form->end(); ?>
</div>