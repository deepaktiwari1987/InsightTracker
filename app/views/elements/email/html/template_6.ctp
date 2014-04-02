<!-- defining layout for mail -->
<?php //echo $mail_body;?>
<!--	Template 6 ---->
<table border="0" cellspacing = "5" cellpadding = "5">
	<!--<tr>
		<td>Dear <?php //echo  htmlentities($sme_name);?></td>
	</tr> -->
	<tr>
		<td>Please be advised new feedback (ref <?php echo  htmlentities($id);?>) has been added to the Feedback Tracker and is now awaiting moderation and delegation.</td>
	</tr>
	<tr>	
		<td>Please follow the link below:<br/>
		<a href="<?php echo $insight_url;?>" target="_blank"><?php echo $insight_url;?></a></td>
	</tr>
	<tr>	
		<td>Please note the expected time to complete the moderation/delegation activities is 3 hrs.</td>
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
