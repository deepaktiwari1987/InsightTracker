<?php
/*
* File Name :  legalqaindex.ctp
* Developer :  Sukhvir Singh
 *@Modified By: Sukhvir Singh
      *Following  changes
        a. How did this feedback come about rename to “Origin of question”
        b. Content Type removed
        d. Contact Name /Role  divided into two fields called Contact Name and Role only contact name required on this form
        e. Suggested Next Steps to be renamed with ‘Required actions / suggested next steps’ removed
        f: Competitor Not required removed
        g: Selling Obstacles Not required removed
        h: What did the customer/prospect say to be renamed to “Question”
        i: How does this impact their activities/business? Not required removed
      
 */?>
<?php 
//$DefaultText = "Please enter the Customers Questions(quoting verbatim where possible) -  this will help to ensure a speedy and effective response!";
$DefaultText = "Please enter the Customers Questions here (Paste it verbatim)";
?>

<div id="textcontainer">
	<?php echo $form->create('Product', array('type'=>'file','action'=>'/legalqaindex','id'=>'productInsightForm','name'=>'productInsightForm','onsubmit'=>'return validateCustomerLegalQA("new");'));?>
	
	<div class="hr-row">	
		<div id="errProductAttachmentExtension" class="successmsg" style="display:<?php echo $successDivSave?>;">New feedback record saved successfully.</div>
		<table width="100%" border="0" cellspacing="3" cellpadding="0">

		<tr>
			<td  colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td width="34%">Origin of Question?:<br />
				<label>
					<?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false, 'style' => 'width:210px')); ?>
				</label>
			</td>
			
			<td>Organisation Name:<br />
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
			<td>Contact Name:<br /> <?php echo $form->input('who_contact_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false));?></td> 
		</tr>
		</table>
	</div>

	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td colspan="4"><h3>What is the feedback about?</h3></td>
		</tr>
		
		<tr>
			<td>
				<table width="" border="0" cellspacing="0" cellpadding="0" class="tbl_width1">
				<tr>
					<td >Product Family Name:<br />
					<div>
					<?php echo $form->input('Productfamilyname.who_product_family_name', array('label'=>false,'options' => $arrProductFamilynames,'div'=>false, 'onchange'=>'javascript:document.getElementById("prodfamilyname").value=this.value;document.getElementById("ProductnameWhoProductName").value="";')); ?>
						<input type="hidden" name="prodfamilyname" id ="prodfamilyname" value="">
					</div>
					</td>
					<td >Product Name:<br />
					<div>
						<?php echo $form->input('Productname.who_product_name', array('label'=>false,'div'=>false,'class'=>'search-input-new','onfocus'=>'javascript:document.getElementById("prodfamilyname").value =document.getElementById("ProductfamilynameWhoProductFamilyName").value;')); ?>
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
					<td  valign="top">Practice Area:<br />
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
				  
				<h3 style="padding: 5px 0px 10px 10px;cursor:pointer;" onclick="toggleDiv('InsightSummary');"><img id="imgInsightSummary" src="<?php echo IMAGE_URL?>/arrow-up.gif"/> 
                                       Question?<span class="red">*</span>
                                </h3>
				<div id="DivInsightSummary" style="display:none;padding-left:10px !important;">
					<?php echo $form->input('insight_summary',array('rows'=>'7','label'=>false,'class'=>'summary','div'=>false, 'onfocus'=>'remove_default(this);', 'onblur'=>'fill_default(this);', 'value'=>$DefaultText, 'defaultText' => $DefaultText));?>
					<div id="errProductInsightSummary" class="errormsg" style="display:<?php echo $errDivProductInsightSummary?>;">Required.</div>					
				</div>
         </div>

	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td><h3>Attachment</h3></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="7%" align="left">Filename: &nbsp;
						<label>
							<?php echo  $form->file('ProductAttachment.attachment_name',array('size'=>'30')) ?>
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
	<div class="buttonrow"><input name="cancel" type="button" value="Cancel" onclick="javascript:redirectUrl('<?php echo SITE_URL?>/');" />&nbsp;<input name="submitProductInsight" type="submit" value="Submit" /></div>
	<?php echo $form->end(); ?>
</div>
<script language="javascript">
function fill_default(element_id)
{
	if(trim(element_id.value)=="")
	{
		element_id.value=element_id.getAttribute("defaultText",0);
		//element_id.style.cssText ="color:#CCC !important";
	}
		
}
function remove_default(element_id)
{
	if(element_id.value === element_id.getAttribute("defaultText",0))
	{
		element_id.value='';
	//	element_id.style.cssText = "color:#000 !important";
	}	
}
</script>