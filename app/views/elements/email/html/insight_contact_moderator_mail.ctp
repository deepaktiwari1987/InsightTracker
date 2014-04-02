<!-- defining layout for mail -->
<?php //echo $mail_body;?>

<table border="0" cellspacing = "5" cellpadding = "5">
	<tr>
		<td><?php echo $requestedBy;?> want to contact Moderator (ref <?php echo  $insight_id;?>) </td>
	</tr>
	<tr>	
		<td>
		Follow the link to:<br/>
		<a href="<?php echo $insight_url;?>" target="_blank"><?php echo $insight_url;?></a></td>
	</tr>
	<tr>	
		<td><b>Message:</b><br/>
		<?php echo  htmlentities($message_body);?></td>
	</tr>
	<tr>	
		<td>Thank you for your support,<br/>The iKnow Insight Tracker Team</td>
	</tr>
	<tr>	
		<td><b>* This is an automated message: Do not reply to this email.</b></td>
	</tr>
</table>