<?php
/*
* File Name :  mindex.ctp
* Developer :  Gaurav Saini
* @author LexisNexis Development Team
* Cake Version : 1.3.4 
* @copyright Copyright (c) 2010, LexisNexis
* Functionality / Description : The purpose of this file is to display a form to add an insight to the application on mobile phone. 
*/
?>
<?php 
//$DefaultText = "Provide a summary of the feedback including details of how important the issue is to the customer/prospect and which parts of their business this impacts.  Your specific recommendations on actions to be taken will help to ensure a speedy response and effective resolution!";
$DefaultText = "";
?>
<div id="textcontainer">
	<?php echo $form->create('Product', array('type'=>'file','action'=>'/mindex','id'=>'productInsightForm','name'=>'productInsightForm','onsubmit'=>'return validateCustomerInsight("add");'));?>

				<table width="" border="0" cellspacing="0" cellpadding="0" class="insight_mb">				
				<tr>
					<td>How did this feedback come about?:<br />
					<label>
						<?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false)); ?>
					</label>
					</td>
				</tr>	
				<tr>			
				<td>Organisation Name:<br />
				
				<?php echo $form->input('Firm.what_firm_name', array('label'=>false,'div'=>false,'class'=>'search_mb-input-new','maxlength'=>'200')); ?>
				<script type="text/javascript">
				  new Autocomplete('FirmWhatFirmName', { 
				    serviceUrl:'<?php echo SITE_URL?>/firms/autoComplete',
				    minChars:2, 
				    maxHeight:400,
				    width:600,
				    deferRequestBy:100,
				    // callback function:
				    onSelect: function(value, data){
				        //alert('You selected: ' + value + ', ' + data);
				      }
				  });
				</script>
				
			</td>
			</tr>
			<tr>
				<td>Contact Name / Role: <br />
					<?php echo $form->input('who_contact_role',array('label'=>false,'class'=>'text-box1', 'maxlength'=>'200','div'=>false));?>
				</td> 
			</tr>
			<tr style="height:60px;">
				<td><h3>What is the feedback about?</h3></td>
			</tr>
			<tr>
					<td>Product Family Name:<br />					
					<?php echo $form->input('Productfamilyname.who_product_family_name', array('label'=>false,'options' => $arrProductFamilynames,'div'=>false, 'onchange'=>'javascript:document.getElementById("prodfamilyname").value=this.value;document.getElementById("ProductnameWhoProductName").value="";')); ?>
						<input type="hidden" name="prodfamilyname" id ="prodfamilyname" value="">
				
					</td>
			</tr>
				
			<tr>
				<td  valign="top">Practice Area:<br />				
					<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false)); ?>
				
				</td>
			</tr>
			<tr>		
					<td width="30%" class="last_td">Selling Obstacles:<br />					
							<?php echo $form->input('selling_obstacle_id', array('label'=>false,'options' => $arrSellingObstacles,'div'=>false, 'class'=>'sellingobs')); ?>
					</td>
				</tr>
				<tr>
					<td>Content Type: <br />					
						<?php echo $form->input('content_type_id', array('label'=>false,'options' => $arrContentTypes,'div'=>false)); ?>
					</td> 
				</tr>
				<tr>
				<td>Competitor: <br />
					<?php echo $form->input('Competitorname.who_competitor_name', array('label'=>false,'div'=>false,'class'=>'search_mb-input-new','maxlength'=>'200')); ?>
						<script type="text/javascript">
						  new Autocomplete('CompetitornameWhoCompetitorName', { 
						    serviceUrl:'<?php echo SITE_URL?>/Competitors/autoComplete',
						    minChars:2, 
						    maxHeight:400,
						    width:600,
						    deferRequestBy:100,
						    // callback function:
						    onSelect: function(value, data){
							//alert('You selected: ' + value + ', ' + data);
						      }
						  });
						</script>
				</td> 
			</tr>
			
				<tr>
					<td>Product Name: <br />
						<?php echo $form->input('Productname.who_product_name', array('label'=>false,'div'=>false,'class'=>'search_mb-input-new','maxlength'=>'200','onfocus'=>'javascript:document.getElementById("prodfamilyname").value =document.getElementById("ProductfamilynameWhoProductFamilyName").value;')); ?>
						<script type="text/javascript">
						  new Autocomplete('ProductnameWhoProductName', { 
						    serviceUrl:'<?php echo SITE_URL?>/productnames/autoComplete',
						    minChars:2, 
						    maxHeight:400,
						    width:600,
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

		<table width="100%" border="0" cellspacing="1" cellpadding="0" class="insight_mb">
		<tr>
			<td><h3>What did the customer/prospect say? <span class="red">*</span></h3></td>
		</tr>
		<tr>
			<td><?php echo $form->input('insight_summary',array('rows'=>'6','cols'=>'35','label'=>false,'div'=>false));?>
				<div id="errProductInsightSummary" class="errormsg" style="display:<?php echo $errDivProductInsightSummary?>;">Required.</div>
			</td>
		</tr>
		<tr>
			<td><h3>How does this impact their activities/business? <span class="red">*</span></h3></td>
		</tr>
		<tr>
			<td>
			<?php echo $form->input('customer_pain_points',array('rows'=>'6','cols'=>'35','label'=>false,'div'=>false));?>
					<div id="errProductCustomerPainPoints" class="errormsg" style="display:<?php echo $errDivProductCustomerPainPoints?>;">Required.</div>
			</td>
		</tr>
		<tr>
			<td><h3>Suggested Next Steps:</h3></td>
		</tr>
		<tr>
			<td><?php echo $form->input('recommended_actions',array('rows'=>'6','cols'=>'35','label'=>false,'div'=>false));?>
			</td>
		</tr>
		</table>


	<div class="buttonrow_mb"><!-- <input name="cancel" type="button" value="Cancel"/> -->&nbsp;<input name="submitProductInsight" type="submit" value="Submit" /></div>
	<?php echo $form->end(); ?>
</div>
<script language="javascript">
function fill_default(element_id)
{
	if(trim(element_id.value)=="")
	{
		element_id.value=element_id.getAttribute("defaultText",0);		
	}			
}
function remove_default(element_id)
{
	if(element_id.value === element_id.getAttribute("defaultText",0))
	{
		element_id.value='';	
	}	
}
</script>