<!-- defining layout for mail -->

<!--	errorMail ---->
<table border="0" cellspacing = "5" cellpadding = "5">
	<tr>
		<td colspan="2">Dear Administrator,<br/><br/>
		Some error occured during adding Insight into the Insight Tracker application. Find the details of Insight below for the ref.:</td>
	</tr>
	<tr>	
		<td width="30%"><b>Environment:</b></td>
		<td valign="top"><?php echo $Environment;?></td>
	</tr>
	<tr>	
		<td><b>Logged in User:</b></td>
		<td valign="top"><?php echo $current_user_name;?></td>
	</tr>
	<tr>	
		<td><b>How did this feedback come about?:</b></td>
		<td valign="top"><?php echo $what_how_come;?></td>
	</tr>
	<tr>	
		<td><b>Organisation Name:</b></td>
		<td valign="top"><?php echo $firmName;?></td>
	</tr>
	<tr>	
		<td><b>Contact Name / Role:</b></td>
		<td valign="top"><?php echo $who_contact_role;?></td>
	</tr>
	<tr>	
		<td><b>Product Family Name:</b></td>
		<td valign="top"><?php echo $product_familyName;?></td>
	</tr>
	<tr>	
		<td><b>Product Name:</b></td>
		<td valign="top"><?php echo $productName;?></td>
	</tr>
	<tr>	
		<td><b>Content Type:</b></td>
		<td valign="top"><?php echo $contentType;?></td>
	</tr>
	<tr>	
		<td><b>Practice Area:</b></td>
		<td valign="top"><?php echo $practice_area;?></td>
	</tr>
	<tr>	
		<td><b>Competitor:</b></td>
		<td><?php echo $competitor;?></td>
	</tr>
	<tr>	
		<td><b>Selling Obstacles:</b></td>
		<td><?php echo $selling_obstacle;?></td>
	</tr>
	<tr>	
		<td colspan="2"><b>What did the customer/prospect say?</b><br/>
		<?php echo  htmlentities($insight_summary);?></td>
	</tr>
	<tr>	
		<td colspan="2"><b>How does this impact their activities/business?</b><br/>
		<?php echo  htmlentities($customer_pain_points);?></td>
	</tr>
	<tr>	
		<td colspan="2"><b>Suggested Next Steps:</b><br/>
		<?php echo  htmlentities($recommended_actions);?></td>
	</tr>
	<tr>	
		<td colspan="2">Thank you for your support,<br/>The iKnow Insight Tracker Team</td>
	</tr>
	<tr>	
		<td colspan="2"><b>* This is an automated message: Do not reply to this email.</b></td>
	</tr>
</table>
