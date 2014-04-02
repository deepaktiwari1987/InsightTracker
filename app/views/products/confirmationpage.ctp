<div id="" class = "hr-row" >

	<input type="hidden"  id="HdnInsightId" value="<?php echo $insight_id; ?>" />
	<input type="hidden"  id="delegated_SME_Id" value="<?php echo $delegated_SME_Id; ?>" />
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
									<input type="button" name="BtnSME" value="Yes" onClick = "javascript:hideNconfirm();" />
									&nbsp;&nbsp;
									<input type="button" name="BtnContributor" value="No" onclick="javascript:OpenContactModeratorPopup();" />
								</div>		
							</td>
						</tr>
			</table>  
		</div>  


<script>


function hideNconfirm()
{
	var delegated_SME_Id	= document.getElementById('delegated_SME_Id').value;
	var current_user_id		= document.getElementById('current_user_id').value;

	if(current_user_id == delegated_SME_Id)
	{
		/*
		*	Setting the owner variable to Yes for Delegated SME.
		*/
		parent.parent.document.getElementById('HdnInsightOwner').value = 'Y';

		/*
		*	Make a ajax call to update the confirm_delegation field as Yes. After updating the Confirmed Delegation flag, parent page will be reloaded.
		*/
		var ConfirmDelegationURL	= "<?php echo SITE_URL.'/products/confirmeddelegation/'. $insight_id;?>";
		var RedirectURL = "<?php print SITE_URL . '/products/records/' . $insight_id; ?>";
		
		confirmDelegation(ConfirmDelegationURL, RedirectURL);
	}


}

function OpenContactModeratorPopup()
{	
	var list = window.parent.parent.document.getElementById('GB_window');
	var items = list.getElementsByTagName("td");
	for (keyitems in items ) {
		if (items[keyitems].className == 'caption')
		{
			items[keyitems].innerHTML = "Contact Moderator";
		}
	}
	<?php $_SESSION['RedirectToHomePage'] = 'Y';?>
	var popupURL = "<?php echo SITE_URL.'/products/contact/'. $insight_id;?>";
	window.location.href = popupURL;
}

</script>