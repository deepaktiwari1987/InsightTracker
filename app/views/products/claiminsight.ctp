<div id="" class = "hr-row" >

	<input type="hidden"  id="HdnInsightId" value="<?php echo $insight_id; ?>" />
	<input type="hidden"  id="current_user_id" value="<?php echo $this->Session->read('current_user_id'); ?>" />
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td align="center"><h3>Is this feedback record correctly allocated to you as the SME?</h3></td>
						</tr>	
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td style="padding: 0px !important;">		
								<div class="buttonrow" style="text-align:center;">
									<input type="button" name="BtnSME" value="Yes" onclick="ClaimInsight();"/>
									&nbsp;&nbsp;
									<input type="button" name="BtnContributor" value="No" onclick="javascript:DontClaimInsight();"/>
								</div>		
							</td>
						</tr>
			</table>  
		</div>  


<script>


function ClaimInsight()
{
	var current_user_id		= document.getElementById('current_user_id').value;
	var HdnInsightId		= document.getElementById('HdnInsightId').value;

	if(current_user_id > 0)
	{
		
		/*
		*	Make a ajax call to update the confirm_delegation field as Yes. (To be added...) and send notification email to Contributor informing him about the ownership taken for the insight.
		*/
		var ClaimURL	= "<?php echo SITE_URL.'/products/claimpath/'. $insight_id.'/'.$this->Session->read('current_user_id');?>";
		var RedirectURL = "<?php print SITE_URL . '/products/records/' . $insight_id; ?>";
		claimInsight(ClaimURL, RedirectURL);
	}
	else
	{
		parent.parent.GB_hide();
	}
}

function DontClaimInsight()
{	
	/*
	*	Setting the owner variable to Yes for Delegated SME.
	*/	
	parent.parent.document.getElementById('HdnInsightOwner').value = 'N';
	//parent.parent.document.getElementById('EditBtn').style.display = 'none';
	parent.parent.activateEditFields('ProductContentTypeId', 'ProductPracticeAreaId', 'ProductInsightSummary', 'ProductDoAction', 'ProductIssueIcon', 'submitProductInsight', 'Product', 'Competitorname', 'Productfamilyname', 'Productname', 'Firm');	
	parent.parent.GB_hide();
}


</script>