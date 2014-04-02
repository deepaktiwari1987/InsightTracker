<!-- defining layout for mail -->
<?php //echo $mail_body;?>


<table border="0" cellspacing="5" cellpadding="5">
	
	<tr>	
		<td>Dear <?php echo ucfirst($contributor_name);?>,</td>
	</tr>
	<tr>	
		<td>Insight <?php echo $id;?> has been claimed by <?php echo  ucfirst($ownership_taken_by);?>.</td>
	</tr>
	<tr>	
		<td><a href="<?php echo $insight_url;?>" target="_blank"><?php echo $insight_url;?></a></td>
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