<?php
/*
* File Name :  legalqasearch.ctp
* Developer :  Sukhvir Singh
* @author LexisNexis Development Team
* Cake Version : 1.3.4 
* @copyright Copyright (c) 2013, LexisNexis
* Functionality / Description : For new for legal Q&A search form
   *Following  changes
        a. How did this feedback come about rename to “Origin of question”
        b. Content Type removed
        d. Contact Name /Role  divided into two fields called Contact Name and Role only contact name required on this form
        e. Suggested Next Steps to be renamed with ‘Required actions / suggested next steps’ removed
        f: Competitor Not required removed
        g: Selling Obstacles Not required removed
        h: What did the customer/prospect say to be renamed to “Question”
        i: How does this impact their activities/business? Not required removed
*/
?>
<?php echo $form->create('Product', array('action'=>'/legalqaresults','id'=>'productSearchInsightForm','name'=>'productSearchInsightForm', 'method' => 'post'));?>

<div id="textcontainer">
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<?php if(isset($found) && trim($found) != "") { ?>
			<tr>
				<td colspan="4"><div class="red">No result found for Feedback Id - <?php echo $insightId;?></div></td>
			</tr>
			
		<?php } ?>
		<?php if(isset($datemismatch) && trim($datemismatch) != "") { ?>
			<tr>
				<td colspan="4"><div class="red">Start Date cannot be later than End Date</div></td>
			</tr>
			
		<?php } ?>
		<?php if(isset($incorrect_start_date) && trim($incorrect_start_date) != "") { ?>
			<tr>
				<td colspan="4"><div class="red">Start Date cannot be later than Current Date</div></td>
				
			</tr>
			
		<?php } ?>
			<?php if(isset($incorrect_end_date) && trim($incorrect_end_date) != "") { ?>
			<tr>
				<td colspan="4"><div class="red">End Date cannot be later than Current Date</div></td>
				
			</tr>
			
		<?php } ?>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td width="25%">
			<input type="text" onfocus="if(this.value=='Search By Feedback Id') this.value='';" onblur="if(this.value=='') this.value='Search By Feedback Id';" value="Search By Feedback Id" class="text-box" alt="Search" maxlength="150" size="30" id="data[Insight][id]" name="data[Insight][id]"></td>
			<td width="5%">OR</td>
			<td width="25%">
			<input type="text" onfocus="if(this.value=='Free Text Search') this.value='';" onblur="if(this.value=='') this.value='Free Text Search';" value="Free Text Search" class="text-box" alt="Search" maxlength="150" size="50" id="data[Product][free_search_text]" name="data[Product][free_search_text]"></td>
                        <td>&nbsp;</td>
                </tr>
		</table>

		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		
		<!--<tr>
			<td colspan="6">Insight Id: <?php echo $form->input('Insight.id',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'value' => $insightId));?> OR</td>
		</tr>-->
		
			
			<tr>
				<td>Origin of Question?:&nbsp;<br />
					<label>
						<?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false , 'style' => 'width:240px')); ?>
					</label>
				</td>
				
				<td>Organisation Name: &nbsp;<br />
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
				
				<td>Contact Name &nbsp; <br /> <?php echo $form->input('who_contact_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false));?></td> 
			</tr>
			
		</table>
	</div>
	
	<div class="hr-row">
		<table width="" border="0" cellpadding="0" cellspacing="5" class="tbl_width1">
		<tr>
			<td colspan="4"><h3>Select Search Options</h3></td>
		</tr>
		
		<!--<tr>
			<td colspan=4>Contact Name / Publication: &nbsp;&nbsp;<?php echo $form->input('who_contact_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false));?></td>
		</tr>-->
		
		<tr>
		
				<td>Product Family Name:&nbsp;
				<div>
					<?php //echo $ajax->autoComplete('Productfamilyname.who_product_family_name', '/productfamilynames/autoCompleteProductFamilyNames', array('minChars' => 3,'class'=>'search-input-new'))?>
					<?php //echo $form->input('Productfamilyname.who_product_family_name', array('label'=>false,'div'=>false,'class'=>'search-input-new','onblur'=>'javascript:document.getElementById("prodfamilyname").value=this.value;')); ?>
					<!--<script type="text/javascript">
					  new Autocomplete('ProductfamilynameWhoProductFamilyName', { 
					    serviceUrl:'<?php echo SITE_URL?>/productfamilynames/autoComplete',
					    minChars:2, 
					    maxHeight:400,
					    width:300,
					    deferRequestBy:100,
					    // callback function:
					    onSelect: function(value, data){
						//alert('You selected: ' + value + ', ' + data);
					      }
					  });
					</script>-->
					<?php echo $form->input('Productfamilyname.who_product_family_name', array('label'=>false,'options' => $arrProductFamilynames,'div'=>false, 'onchange'=>'javascript:document.getElementById("prodfamilyname").value=this.value;document.getElementById("ProductnameWhoProductName").value="";')); ?>

					</div>
					<input type="hidden" name="prodfamilyname" id ="prodfamilyname" value="">
				</td>
				<td >Product Name:&nbsp;
				<div>
					<?php //echo $ajax->autoComplete('Productname.who_product_name', '/productnames/autoCompleteProductNames', array('minChars' => 3,'class'=>'search-input-new'))?>
					<?php echo $form->input('Productname.who_product_name', array('label'=>false,'div'=>false,'class'=>'search-input-new','onfocus'=>'javascript:document.getElementById("prodfamilyname").value=document.getElementById("ProductfamilynameWhoProductFamilyName").value;')); ?>
					<script type="text/javascript">
					  new Autocomplete('ProductnameWhoProductName', { 
					    serviceUrl:'<?php echo SITE_URL?>/productnames/autoComplete',
					    minChars:2, 
					    maxHeight:400,
					    width:450,
					    deferRequestBy:100,
					    // callback function:
					    onSelect: function(value, data){
						//alert('You selected: ' + value + ', ' + data);
					      }
					  });
					</script>
				</div>
				</td>
                  	</tr>
			
			<tr>
				<td>Practice Area:&nbsp;
					<div>
						<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false)); ?>
					</div>
				</td>
			
			</tr>
		
			</table>
		
	</div>
	
	<div class="hr-row">
	<table border="0" cellpadding="0" cellspacing="5" width="70%">
		<tr>
			<td colspan="8" style="padding-bottom:2px;"><h3>Select Created By and Date<span class="red"></span></h3></td>
		</tr>
		
		<tr>
			<td width="">Created By:&nbsp;</td>
			<td width="25%" align="left"><div><?php echo $form->input('user_id', array('label'=>false,'options' => $arrCreatedBy,'div'=>false)); ?></div></td>
			<td width="">Date From:&nbsp;</td>
			<td width="" align="left"><?php echo $form->input('created_from',array('value'=>"", 'label'=>false,'class'=>'text-box1 calender-width','size'=>'25','div'=>false, 'id'=>'date_from', 'readonly'=>true));?>
			</td>
			<td width="10%" align="left" ><a href="javascript:void(0);" id="calendarButton_from"><img src="<?php echo IMAGE_URL?>/cal.png" /></a></td>
			<td width="">Date To:&nbsp;</td>
			<td width="" align="left">
				<?php echo $form->input('created_to',array('value'=>"",'label'=>false,'class'=>'text-box1 calender-width','size'=>'25','div'=>false, 'id'=>'date_to', 'readonly'=>true));?>
			</td>
			<td><a href="#" id="calendarButton_to"><img src="<?php echo IMAGE_URL?>/cal.png" align="absmiddle" /></a></td>
		</tr>
				
		</table>

	</div>
	
	
	<div class="hr-row">
			<table width="100%" border="0" cellspacing="5" cellpadding="0">
			<tr>
				<td width="30%" colspan="3"><h3>Select additional criteria to narrow your search</h3></td>
			</tr>
			
			<tr>
				<td colspan="3">
					<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr>
						<td  valign="top" nowrap="nowrap">Current Status: &nbsp;	</td>						
						<td>		<?php //echo $form->input('insight_status', array('label'=>false,'options' => $arrStatusinsight,'div'=>false, 'class' => 'selectBox1')); ?>
								<?php echo $form->input('insight_status', array('label'=>false,'div'=>false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $arrStatusinsight)) ?>
							
						</td>
					</tr>
					</table>
					<div class="clear"></div>
					<table width="80%" border="0" align="left" cellpadding="0" cellspacing="0" style="margin-top:10px;" class="tbl_width">
					<tr>
						<td valign="top">Current Owner:					
								<?php echo $form->input('deligated_to', array('label'=>false,'options' => $arrDelegatedTo,'div'=>false, 'class' => 'selectBox1')); ?>
							
						</td>						
						<td valign="top">Market Segment:
							<?php echo $form->input('market_id', array('label'=>false,'options' => $arrWhoMarket,'div'=>false, 'class' => 'selectBox1')); ?>	
						</td>
						<td valign="top">Issue:						
							<?php //echo $form->input('issue_field', array('label'=>false,'options' => $arrIssues,'div'=>false, 'class' => 'selectBox1')); ?>	
							
							<?php echo $form->input('Issue.issue_title', array('label'=>false,'div'=>false,'class'=>'search-input-new')); ?>
							<script type="text/javascript">
							  new Autocomplete('IssueIssueTitle', { 
								serviceUrl:'<?php echo SITE_URL?>/issues/autoComplete',
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
					<div class="clear"></div>
					<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr>
						<td  valign="top" nowrap="nowrap" width="10%" style="padding-top:10px;">Added by Mobile: &nbsp;	</td>						
						<td align="left" style="padding-top:10px;">
							<?php //echo $form->input('flag_mobile', array('label'=>false,'div'=>false, 'type' => 'radio', 'value' => 'Y')) ?> 
							
							<input type="radio" name="data[Product][flag_mobile]" value="Y"/>Yes&nbsp;&nbsp;
							<input type="radio" name="data[Product][flag_mobile]" value="N"/>No&nbsp;&nbsp;
							<input type="radio" name="data[Product][flag_mobile]" value="" checked/>Both

						</td>
					</tr>
					</table>
				</td>
			</tr>
		
			</table>
		</div>	
	
	<div class="buttonrow"><input name="cancel" type="button" value="Cancel" onclick="javascript:redirectUrl('<?php echo SITE_URL?>/');" />&nbsp;&nbsp;<input name="" type="submit" value="Search" /></div>
</div>
<?php echo $form->end(); ?>	
<script type="text/javascript">
	setupCalendar();
</script>