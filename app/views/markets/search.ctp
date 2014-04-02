<?php echo $form->create('Market', array('action'=>'/results','id'=>'marketSearchInsightForm','name'=>'marketSearchInsightForm', 'method' => 'post'));?>
<div id="textcontainer">
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<?php if(isset($found) && trim($found) != "") { ?>
			<tr>
				<td colspan="6"><div class="red">No result found.</div></td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="6"><h3>Select one of the following insight types you would like to search by : <span class="red">*</span></h3></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6">Insight Id: <?php echo $form->input('Insight.id',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'value' => $insightId));?> OR</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="6" class="selectoption">
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type1" type="radio" value="CUSTOMER"  onclick="javascript:changeInsightType(this.value,'customers','search/checked','<?php echo SITE_URL?>');" />
					Customer Insight
				</label>
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type2" type="radio" value="MARKET"  checked="checked" />
					Market Insight
				</label>
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type3" type="radio" value="PRODUCT"  onclick="javascript:changeInsightType(this.value,'products','search','<?php echo SITE_URL?>');" /> 
					Product Insight
				</label>
				<label>
					<input name="data[Market][what_insight_type]" id="what_insight_type4" type="radio" value="COMPETITOR" onclick="javascript:changeInsightType(this.value,'competitors','search','<?php echo SITE_URL?>');" />
					Competitor Insight
				</label>
			</td>
		</tr>
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<tr>
				<td width="16%">Source of Insight :</td>
				<td width="19%">
					<label>
						<?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false)); ?>
					</label>
				</td>
				<td width="14%">Name of Source :</td>
				<td width="18%">
					<label>
						<?php echo $form->input('what_source_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false));?>
					</label>
				</td>
				<td width="16%">Firm Name Source :</td>
				<td width="17%">
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
			<td colspan="2"><h3>Select Market :</h3></td>
		</tr>
		<tr>
			<td colspan="2" height="5px"></td>
		</tr>
		<tr>
			<td width="7%">Market :</td>
			<td >
				<?php echo $form->input('market_id', array('label'=>false,'options' => $arrWhoMarket,'div'=>false)); ?>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row">
		<table border="0" cellpadding="0" cellspacing="2">
		<tr>
			<td colspan="8"><h3>Select Created by and Date :<span class="red"></span></h3></td>
		</tr>
		<tr>
			<td colspan="8" height="5px"></td>
		</tr>
		<tr>
			<td width="">Created by :</td>
			<td width="25%" align="left"><div><?php echo $form->input('user_id', array('label'=>false,'options' => $arrCreatedBy,'div'=>false)); ?></div>
			<td width="">Date from :</td>
			<td width="" align="left"><?php echo $form->input('created_from',array('label'=>false,'class'=>'text-box1 calender-width','size'=>'25','div'=>false, 'id'=>'date_from', 'readonly'=>true));?>
			</td>
			<td width="15%" align="left" ><a href="#" id="calendarButton_from"><img src="<?php echo IMAGE_URL?>/cal.png" /></a></td>
			<td width="">Date to :</td>
			<td width="" align="left">
				<?php echo $form->input('created_to',array('label'=>false,'class'=>'text-box1 calender-width','size'=>'25','div'=>false, 'id'=>'date_to', 'readonly'=>true));?>
			</td>
			<td><a href="#" id="calendarButton_to"><img src="<?php echo IMAGE_URL?>/cal.png" align="absmiddle" /></a></td>
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
								<?php echo $form->input('relates_product_family_id', array('label'=>false,'options' => $arrFamilyNames,'div'=>false)); ?>
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
				<td><h3>Select Insight status and current owner :</h3></td>
			</tr>
			<tr>
				<td>
					<table width="80%" border="0" align="left" cellpadding="0" cellspacing="5">
					<tr>
						<td width="33%" valign="top" class="current_status_chkbox"><h4>Insight Status</h4>
							<div>
								<?php //echo $form->input('insight_status', array('label'=>false,'options' => $arrStatusinsight,'div'=>false, 'class' => 'selectBox1')); ?>
								<?php echo $form->input('insight_status', array('label'=>false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $arrStatusinsight)) ?>
							</div>
						</td>
						<td width="*" valign="top"><h4>Current owner</h4>
							<div>
								<?php echo $form->input('deligated_to', array('label'=>false,'options' => $arrDelegatedTo,'div'=>false, 'class' => 'selectBox1')); ?>
							</div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</div>	
	<div class="buttonrow"><input name="cancel" type="button" value="Cancel" onclick="javascript:redirectUrl('<?php echo SITE_URL?>/customers/home');" />&nbsp;<input name="submit" type="submit" value="Search" /></div>
</div>
<?php echo $form->end(); ?>	
<script type="text/javascript">
	setupCalendar();
</script>