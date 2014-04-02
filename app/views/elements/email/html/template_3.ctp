<!-- defining layout for mail -->
<?php //echo $mail_body;?>
<!-- Email Template 3  --->

<table border="0" cellspacing = "5" cellpadding = "5">
	<tr>
		<td>Dear Contributor <?php echo  htmlentities($contributor_name);?></td>
	</tr>
	<tr>
		<td>Please be advised a response / resolution to the above feedback ref <?php echo  htmlentities($id);?> has been logged in the Tracker – please follow the link below to review the response:</td>
	</tr>
	<tr>	
		<td><a href="<?php echo $insight_url;?>" target="_blank"><?php echo $insight_url;?></a></td>
	</tr>
	<tr>	
		<td><b>Feedback summary:</b><br/>
		<?php echo  htmlentities($insight_summary);?></td>
	</tr>
	<?php if($recent_reply!=''){?>
	<tr>	
		<td><b>Latest Comment:</b><br/>
		<?php echo  htmlentities($recent_reply);?></td>
	</tr>
	<?php }?>
	<tr>	
		<td>Thank you for your support,<br/>The iKnow Insight Tracker Team</td>
	</tr>
	<tr>	
		<td><b>* This is an automated message: Do not reply to this email.</b></td>
	</tr>
</table>