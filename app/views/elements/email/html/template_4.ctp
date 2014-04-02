<!-- defining layout for mail -->
<?php //echo $mail_body;?>
<!--	Template 4 ---->
<table border="0" cellspacing = "5" cellpadding = "5">
	<tr>
		<td>Dear <?php echo  htmlentities($sme_name);?></td>
	</tr>
	<tr>
		<td>Please be advised a comment relating to feedback ref <?php echo  htmlentities($id);?> has been added to the Tracker for your attention;</td>
	</tr>
	<tr>	
		<td>To access the details and review the comments, please follow the link below:<br/>
		<a href="<?php echo $insight_url;?>" target="_blank"><?php echo $insight_url;?></a></td>
	</tr>
	<tr>	
		<td><b>Feedback summary:</b><br/>
		<?php echo  htmlentities($insight_summary);?></td>
	</tr>
	<?php if($comment_text!=''){?>
	<tr>	
		<td><b>Latest Comment:</b><br/>
		<?php echo  htmlentities($comment_text);?></td>
	</tr>
	<?php }?>
	<tr>	
		<td>Thank you for your support,<br/>The iKnow Insight Tracker Team</td>
	</tr>
	<tr>	
		<td><b>* This is an automated message: Do not reply to this email.</b></td>
	</tr>
</table>