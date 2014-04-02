<!-- defining layout for mail -->
<?php //echo $mail_body;?>
<!-- Template 1 & 2 -->
<table border="0" cellspacing = "5" cellpadding = "5">
	<tr>
		<td>Dear SME <?php echo  htmlentities($delegated_to);?></td>
	</tr>
	<tr>
		<td>Please be advised new feedback (ref <?php echo  htmlentities($id);?>) has been delegated to you in the Feedback Tracker; <br/>
Follow the link to:
<br/>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td width="3%" align="center">1.</td>
			<td>&nbsp;review the feedback</td>
		  </tr>
		  <tr>
			<td align="center">2.</td>
			<td>&nbsp;add your comments</td>
		  </tr>
		  <tr>
			<td align="center">3.</td>
			<td>&nbsp;update the feedback status</td>
		  </tr>
		  <tr>
			<td align="center">4.</td>
			<td>&nbsp;assign the feedback to a new / existing issue </td>
		  </tr>
		</table>
</td>
	</tr>
	
	<tr>	
		<td><a href="<?php echo $insight_url;?>" target="_blank"><?php echo $insight_url;?></a></td>
	</tr>
	<tr>	
		<td>Please note the expected response time is 48 hrs.</td>
	</tr>
	<tr>	
		<td><b>Feedback summary:</b><br/>
		<?php echo  htmlentities($insight_summary);?></td>
	</tr>
	<tr>	
		<td>Thank you for your support,<br/>The iKnow Insight Tracker Team</td>
	</tr>
	<tr>	
		<td><b>* This is an automated message: Do not reply to this email.</b></td>
	</tr>
</table>
