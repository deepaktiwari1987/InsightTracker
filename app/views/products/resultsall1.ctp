<div id="textcontainer"> 
<?php 
			$exportlink = "#";
			$class = 'class="link_disabled"';
			if(isset($final_result) && count($final_result)>0) {
				$exportlink = SITE_URL."/products/exportToExcel/product";
			$class = 'class="link_enabled"';
			}	
?>
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td width="50%" ><h2>Product Insight Records</h2></td>
			<td width="50%" align="right" ><a href="<?php print $exportlink; ?>" <?php print $class; ?>>Export Results</a></td>
		</tr>
		</table>
	</div>
	
	<div class="hr-row" style="width:980px; overflow-x:auto; overflow-y:hidden;" >
		<table width="2600" border="0" cellspacing="0" cellpadding="0" class="grid">
			<tr>
			<th>ID</th>
			<th>Date</th>
			<th>Created By</th>

			<th>Date Status Changed</th>
			<th>Current Status</th>
			<th>Current Owner</th>		
						
			<th>Come About</th>	
			<th>Source of Insight</th>
			<th>What Firm Name</th>
			<th>Product Family Name</th>
			<th>Product Name</th>
			<th>Summary</th>
			<th>Content Type</th>			
			<th>Practice Area</th>
			<th>Should Be Done</th>
			<th>Attachment</th>			
			</tr>
		<?php 
			if(isset($final_result) && count($final_result)>0) {
				for($i = 0; $i < count($final_result); $i++) { 
					//pr($final_result[$i]);				
				?>
			<tr>
				<td width="2%"><a href="<?php print SITE_URL . '/products/records/' . $final_result[$i]['id'] ; ?>"><?php print $final_result[$i]['id']; ?></a></td>
				<td width="4%"><?php print $custom->formatDate($final_result[$i]['date_submitted']); ?></td>
				<td width="4%"><?php print $final_result[$i]['userSubmittedName']; ?></td>

				<td width="6%"><?php print $custom->formatDate($final_result[$i]['date_updated']); ?></td>
				<td width="6%"><?php print $custom->getCurrentStatus($final_result[$i]['insight_status']); ?></td>
				<td width="4%"><?php print $custom->getUserNameById($final_result[$i]['deligated_to']); ?></td>
								
				<td width="6%"><?php print (trim($final_result[$i]['what_how_come']) != "")?$final_result[$i]['what_how_come']:'--'; ?></td>
				<td width="7%"><?php print (trim($final_result[$i]['what_source_name']) != "")?$final_result[$i]['what_source_name']:'--'; ?></td>
				<td width="8%"><?php print (trim($final_result[$i]['firmName']) != "")?$final_result[$i]['firmName']:'--'; ?></td>
				<td width="8%"><?php print (trim($final_result[$i]['who_product_familyName']) != "")?$final_result[$i]['who_product_familyName']:'--'; ?></td>				
				<td width="8%"><?php print (trim($final_result[$i]['who_productName']) != "")?$final_result[$i]['who_productName']:'--'; ?></td>
				<td width="8%"><?php print (trim($final_result[$i]['insight_summary']) != "")?substr($final_result[$i]['insight_summary'],0,50).'...':'--'; ?></td>
				<td width="6%"><?php print (trim($final_result[$i]['relates_contentType']) != "")?$final_result[$i]['relates_contentType']:'--'; ?></td>
				<td width="6%"><?php print (trim($final_result[$i]['practice_area_id']) != "")?$final_result[$i]['practice_area_id']:'--'; ?></td>
				<td width="*"><?php print (trim($final_result[$i]['do_action']) != "")?substr($final_result[$i]['do_action'],0,50):'--'; ?></td>
				<td width="5%">
								<?php 
								if($final_result[$i]['attachment_real_name']!="")
									print '<a id="attachment_namelink" href="#" onclick="javascript:open_attachment(\''.SITE_URL.'/files/product/'.$final_result[$i]['attachment_name'].'\'); return false;">'.$final_result[$i]['attachment_real_name'].'</a>'; 
								else
									print "No attachment";	
								?>				
				</td>				
			</tr>
		<?php 	}
			} else {
		?>
			<tr>
				<td colspan="8" align="center" >No result found.</td>
				<td colspan="5" align="center" >&nbsp;</td>
			</tr>
		<?php	
			}
		?>
		</table>
	</div>
	<div class="buttonrow"><table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td width="41%" align="left"><div class="pagedisplay">
			<?php if(isset($final_result) && count($final_result)>0) { ?>
			<!-- Shows the page numbers -->
			<?php echo $this->Paginator->numbers(); ?>
			<!-- prints X of Y, where X is current page and Y is number of pages -->
			<?php echo $this->Paginator->counter(array('format' => '<br />Page %page% of %pages%')); ?>
			</div>
			<?php } ?> &nbsp;
			</div></td>
			<td width="59%" align="left"><input name="cancel" type="button" value="Cancel" onclick="javascript:redirectUrl('<?php echo SITE_URL?>/customers/home');" />&nbsp;<input name="" type="button" value="New Search" onclick="javascript:redirectUrl('<?php echo SITE_URL?>/products/search');" /></td>
		</tr>
		</table>
	</div>
</div>