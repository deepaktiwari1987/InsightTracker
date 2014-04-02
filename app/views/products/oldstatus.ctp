<div id="" class = "hr-row" >

					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
							<td align="center"><h3>Record status has not been changed!<br/> Would you like to change it now?</h3></td>
						</tr>	
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td style="padding: 0px !important;">		
								<div class="buttonrow" style="text-align:center;">
									<input type="button" name="BtnSME" value="Yes" onclick="javascript:YesButtonClick();"/>
									&nbsp;&nbsp;
									<input type="button" name="BtnContributor" value="No" onclick="javascript:NoButtonClick();"/>
								</div>		
							</td>
						</tr>
			</table>  
		</div>  


<script>


function YesButtonClick()
{	
	parent.parent.GB_hide();
}

function NoButtonClick()
{	
	parent.parent.document.productInsightForm.submit();
	parent.parent.GB_hide();
}


</script>