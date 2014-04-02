<div id="textcontainer">
<?php
			/*$exportlink = "#";
			$class = 'class="link_disabled"';
			if(isset($result) && count($result)>0) {
			$exportlink = SITE_URL."/products/exportToExcel/competitor";
			$class = 'class="link_enabled"';
			}*/
//echo '<pre>'; print_r($view_name); exit;
?>
	<div class="hr-row">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td width="50%" ><h2><?php 
			switch ($view_name)
			{
				case "firm":
					$title = 'Organisation';
					break;
				case "market":
						$title = 'Market Segment';
						break;
				case "pilotgroup":
					$title = 'User Management';
					break;
				default:
					$title = $heading;
					break;
			}
			print $title;
			//print ($view_name != 'pilotgroup')?($view_name == 'firm')? "Organisation":($view_name == 'market')? "Market Segment":$heading.' ':'User Management'; 
			?>
			</h2></td>
			<td width="25%" align="right" >
			<div id="select_table">
						<?php //print $form->input('add',array('options'=>$table_name,'label'=>false));
						?>
			</div></td>

			<td width="25%" align="right" ><!--<a href="javascript:void(0);" onclick="javascript:openAddNewWindow('<?php //echo 'Add New ' . $heading . ' Record '; ?>','<?php //echo SITE_URL?>/editrecords/addnew/<?php //echo $view_name;?>',290,500)">Add New <?php //print $heading; ?></a>--></td>

		</tr>
		</table>
	</div>
	
	<div class="hr-row1">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td width="50%" ></td>
			<td width="25%" align="right" >
			<div id="select_table">
						<?php //print $form->input('add',array('options'=>$table_name,'label'=>false));	
							$BoxHeight = 320;
							$addNewLabel = ' ' . $heading . '  ';
							if($heading == "Username" || $view_name == 'pilotgroup') {
										$addNewLabel = 'User name';
										$BoxHeight = 500;
							}else if($view_name == 'insightabout') {
										$addNewLabel = ' Feedback come about';
							}
							else if($view_name == 'firm') {
										$addNewLabel = ' Organisation';
							}
							else if($view_name == 'market') {
										$addNewLabel = 'Market Segment';
							}
							else if($view_name == 'departmentname') {
										$addNewLabel = 'Department Name';
							}
							
						?>
			</div></td>
			<td width="25%" align="right" ><b><a href="javascript:void(0);" onclick="javascript:openAddNewWindow('<?php echo 'Add '. $addNewLabel; ?>','<?php echo SITE_URL?>/editrecords/addnew/<?php echo $view_name;?>',<?php echo $BoxHeight;?>,500)">Add <?php print ($heading=='Username')?'User name':$addNewLabel; ?></a></b></td>
		</tr>
		</table>
	</div>
	
	
	<div class="hr-row"  style="width:980px; overflow-x:auto; overflow-y:hidden;" >
	<!--<div class="main_nav_radio">
		<select onchange="javascript:window.location = this.value;">
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/competitornames" <?php //print ($view_name == 'competitorname')?"selected='selected'":""; ?> >Competitor Name</option>
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/contenttypes" <?php //print ($view_name == 'contenttype')?"selected='selected'":""; ?> >Content Types</option>
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/firms" <?php //print ($view_name == 'firm')?"selected='selected'":""; ?> >Organisation</option>
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/insightabouts" <?php //print ($view_name == 'insightabout')?"selected='selected'":""; ?> >Insight About</option>
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/markets" <?php //print ($view_name == 'market')?"selected='selected'":""; ?> >Market</option>
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/pilotgroups" <?php //print ($view_name == 'pilotgroup')?"selected='selected'":""; ?> >Username</option>
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/practiceareas" <?php //print ($view_name == 'practicearea')?"selected='selected'":""; ?> >Practice Area</option>
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/productfamilynames" <?php //print ($view_name == 'productfamilyname')?"selected='selected'":""; ?> >Product Family Name</option>	
			<option value="<?php //echo SITE_URL?>/editrecords/showlist/productnames"  <?php //print ($view_name == 'productname')?"selected='selected'":""; ?> >Product Name</option>																
		</select>
	</div>-->
		<?php if($view_name == 'firm' || $view_name != '') {?>

				<?php echo $form->create('editrecords', array('action'=>'/showlist/'.$view_name.'s')); ?>
				<div class="record_search">
				<table border="0" cellpadding="0" cellspacing="0" width="750">
					<tr>
					<?php if($view_name == 'firm') { $tablewidth = "75%";	?><!-- Firm -->
						<td width="25%">Parent Id:<br /><?php echo $form->input('Firm.parentid', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
						<td width="25%">Account Number:<br /><?php echo $form->input('Firm.accountnumber', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
						<td width="25%">Organisation Name:<br /><?php echo $form->input('Firm.firmname', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>													
						
					<?php }elseif($view_name == 'productname'){ $tablewidth  = "38%"; ?> <!-- product names -->
						<td width="25%">Product Code:<br /><?php echo $form->input('Productname.productcode', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
						<td width="25%">Product Name:<br /><?php echo $form->input('Productname.productname', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>

					<?php }elseif($view_name == 'pilotgroup'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">User name:<br /><?php echo $form->input('Pilotgroup.search_name', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>

					<?php }elseif($view_name == 'competitorname'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Competitor Name:<br /><?php echo $form->input('Competitorname.search_competitor_name', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
                                       
					<?php }elseif($view_name == 'contenttype'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Content Type:<br /><?php echo $form->input('Contenttype.search_content_type', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
                                 	  <?php 
                                         /*
                                          * @sukhvir add insighttypes functionality
                                          */
                                         ?>
                                        <?php }elseif($view_name == 'insighttype'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Insight Type:<br /><?php echo $form->input('Insighttype.search_insight_type', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
					                              
                                         <?php }elseif($view_name == 'insightabout'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Feedback come about:<br /><?php echo $form->input('Insightabout.search_insight_type', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>

					<?php }elseif($view_name == 'market'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Market Segment:<br /><?php echo $form->input('Market.search_market', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
                                        <?php }elseif($view_name == 'practicearea'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Practice Area:<br /><?php echo $form->input('Practicearea.search_practice_area', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>

					<?php }elseif($view_name == 'productfamilyname'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Product Family Name:<br /><?php echo $form->input('Productfamilyname.search_family_name', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
					<?php }elseif($view_name == 'statusinsight'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Feedback Status:<br /><?php echo $form->input('Statusinsight.search_status', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
					<?php }elseif($view_name == 'productarea'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Product Area:<br /><?php echo $form->input('Productarea.search_productarea', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
					<?php }elseif($view_name == 'sellingobstacle'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Selling Obstacles:<br /><?php echo $form->input('Sellingobstacle.search_sellingobstacle', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>
					<?php }elseif($view_name == 'departmentname'){ $tablewidth = "25%";?> <!-- username -->
						<td width="25%">Department Name:<br /><?php echo $form->input('Departmentname.search_departmentname', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'type'=>'text')); ?></td>	
					<?php } ?>
						<td width="*" align="left"><br />
						<input type="submit" name="search" value="Search" class="search_btn" /> &nbsp <input type="button" class="search_btn" onclick="javascript: window.location = '<?php print SITE_URL.'/editrecords/showlist/'.$view_name.'s/showall'; ?>';" value="Show All" />&nbsp; 
						  <?php 
								$exportlink = "#";
								
								if($view_name == 'pilotgroup'){
									$class = 'class="link_disabled"';
									if(isset($result) && count($result)>0) {
										$exportlink = SITE_URL."/editrecords/exportToExcel";
										$class = 'class="link_enabled"';
									}	
							?>
						<a href="<?php print $exportlink; ?>" <?php print $class; ?>>Export Results</a>
						<?php }?>
<!--									<table border="0" cellpadding="0" cellspacing="0" width="<?php //print $tablewidth; ?>" align="left">
											<tr>
												<td width="50%"><input type="submit" name="search" value="Search" class="search_btn" /></td>
												<td width="50%"><input type="button" class="search_btn" onclick="javascript: window.location = '<?php //print SITE_URL.'/editrecords/showlist/'.$view_name.'s/showall'; ?>';" value="Show All" /></td>
											</tr>
									</table>
-->						</td>
					</tr>
				</table>
				</div>
				<?php echo $form->end(); ?>				

			<?php }?>
		<table width="980" border="0" cellspacing="0" cellpadding="0" class="grid">
			<tr>
			<th>ID</th>
			
			<?php if($view_name == 'competitorname') {?>
			<th>Competitor Name</th>
			<?php }?>
			
			<?php if($view_name == 'contenttype') {?>
			<th>Content Type</th>
			<?php }?>
			<?php /*
                         * @sukhvir added insighttypefuctionality in admin section  
                         */
                        ?>
                       <?php if($view_name == 'insighttype') {?>
			<th>Insight Type</th>
			<?php }?>
                       	<?php if($view_name == 'firm') {?>
			<th>Parent Id</th>
			<th>Account Number</th>
			<th>Organisation Name</th>
			<?php }?>
			
			<?php if($view_name == 'insightabout') {?>
			<th>Feedback come about</th>
			<?php }?>
			
			<?php if($view_name == 'market') {?>
			<th>Market Segment</th>
			<?php }?>
			
			<?php if($view_name == 'pilotgroup') {?>
			<th>User name</th>
			<th>Email Address</th>
			<!-- <th>Department Name</th> -->
			<th>Admin</th>
			<?php }?>
			
			<?php if($view_name == 'practicearea') {?>
			<th>Practice Area</th>
			<?php }?>
			
			<?php if($view_name == 'productfamilyname') {?>
			<th>Product Family Name</th>
			<?php }?>
			
			<?php if($view_name == 'productname') {?>
			<th>Product Code</th>
			<th>Product Name</th>
			<th>Product Family Name</th>
			<?php }?>
			
			<?php if($view_name == 'statusinsight') {?>
			<th>Feedback Status</th>
			<?php }?>

			<?php if($view_name == 'productarea') {?>
			<th>Product Area</th>
			<?php }?>

			<?php if($view_name == 'sellingobstacle') {?>
			<th>Selling Obstacles</th>
			<?php }?>
			
			<?php if($view_name == 'departmentname') {?>
			<th>Department Names</th>
			<?php }?>	
			<th>Active</th>
			<th>Edit</th>
      <th>Delete</th>
			
			</tr>
		<?php 
			if(isset($result) && count($result)>0) {
		?>
			
				<!-- Start edit and change status.-->
				<?php if ($view_name == 'competitorname') {?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
						<tr>
							<td width="5%" ><?php print $result[$i]['Competitorname']['id']; ?></td>
							<td ><span style="display:block" id="<?php print 'ValueCompetitornameCompetitorName'.$result[$i]['Competitorname']['id']; ?>">
												<?php print $result[$i]['Competitorname']['competitor_name']; ?>
									</span>
									 <span style="display:none" id="<?php print 'TextboxCompetitornameCompetitorName'.$result[$i]['Competitorname']['id']; ?>">
									 			<?php echo $form->input('Competitorname.competitor_name', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Competitorname']['competitor_name'], 'id' => $view_name.'_'.$result[$i]['Competitorname']['id']));?><br/>
									</span>
							</td>
							<td width="6%" align="center"><?php print $form->checkbox('Competitorname.isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Competitorname', $result[$i]['Competitorname']['id']), 'class' => 'isactive_'.$result[$i]['Competitorname']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Competitorname']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Competitorname']['id'].',this);'));?>
								<span><input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Competitorname']['id']; ?>" value = ""></span>
							</td>
							<td width="6%" align="center" ><a href="#" onclick="show_editctypebox('<?php print 'CompetitornameCompetitorName'.$result[$i]['Competitorname']['id']; ?>', '<?php print $result[$i]['Competitorname']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Competitorname']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1CompetitornameCompetitorName'.$result[$i]['Competitorname']['id']; ?>">			
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Competitorname']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/competitor_name/isactive/'.$result[$i]['Competitorname']['id']; ?>', '<?php print 'CompetitornameCompetitorName'.$result[$i]['Competitorname']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Competitorname']['id'];?>','<?php print $result[$i]['Competitorname']['id']; ?>', 'Competitor name')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'CompetitornameCompetitorName'.$result[$i]['Competitorname']['id']; ?>', '<?php print $result[$i]['Competitorname']['id']; ?>')">Cancel</a>
									 </span>
							</td>		 
							<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Competitorname']['id']; ?>','Competitor name ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
						</tr>
				<?php }}?>
			<!-- End of edit and change status.-->
<!-- Start edit and change status.-->			
				<?php if ($view_name == 'firm') {?>	
					<?php for($i = 0; $i < count($result); $i++) { ?>	
						<tr>
							<td width="5%" ><?php print $result[$i]['Firm']['id']; ?></td>
							<td width="18%"><span id="<?php print 'ValueFirmParentId'.$result[$i]['Firm']['id']; ?>" class="<?php print 'ValueFirmSpan'.$result[$i]['Firm']['id']; ?>">
												<?php print $result[$i]['Firm']['parent_id']; ?>
									</span>
									 <span id="<?php print 'TextboxFirmParentId'.$result[$i]['Firm']['id']; ?>"  class="hideElement <?php print 'TextboxFirmSpan'.$result[$i]['Firm']['id']; ?>">
									 			<?php echo $form->input('parent_id', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'value'=>$result[$i]['Firm']['parent_id'], 'id' => $view_name.'_parentid_'.$result[$i]['Firm']['id']));?><br/>
									</span> 			
							</td>
							<td  width="18%">
									<span id="<?php print 'ValueFirmAccountNumber'.$result[$i]['Firm']['id']; ?>" class="<?php print 'ValueFirmSpan'.$result[$i]['Firm']['id']; ?>">
												<?php print $result[$i]['Firm']['account_number']; ?>
									</span>
									 <span id="<?php print 'TextboxFirmAccountNumber'.$result[$i]['Firm']['id']; ?>"  class="hideElement <?php print 'TextboxFirmSpan'.$result[$i]['Firm']['id']; ?>">
									 			<?php echo $form->input('Firm.account_number', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'value'=>$result[$i]['Firm']['account_number'], 'id' => $view_name.'_account_number_'.$result[$i]['Firm']['id']));?><br/>
									</span>						
							</td>
							<td  width="*">
									<span id="<?php print 'ValueFirmFirmName'.$result[$i]['Firm']['id']; ?>" class="<?php print 'ValueFirmSpan'.$result[$i]['Firm']['id']; ?>">
												<?php print $result[$i]['Firm']['firm_name']; ?>
									</span>
									 <span id="<?php print 'TextboxFirmFirmName'.$result[$i]['Firm']['id']; ?>"  class="hideElement <?php print 'TextboxFirmSpan'.$result[$i]['Firm']['id']; ?>">
												<?php echo $form->input('Firm.firm_name', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Firm']['firm_name'], 'id' => $view_name.'_firm_name_'.$result[$i]['Firm']['id']));?><br />
									</span>						
							</td>
							<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Firm', $result[$i]['Firm']['id']), 'class' => 'isactive_'.$result[$i]['Firm']['id'], 'disabled' => TRUE, 'id' => $view_name.'_isactive_'.$result[$i]['Firm']['id'], 'onclick' => 'hidden_check('.$result[$i]['Firm']['id'].',this);')); ?>
							<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Firm']['id']; ?>" value = ""></td>
							<td width="6%" align="center"><a href="#" onclick="show_editctypebox_class('<?php print 'FirmSpan'.$result[$i]['Firm']['id']; ?>', '<?php print $result[$i]['Firm']['id']; ?>', 'firm')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span class="hideElement <?php print 'Textbox1FirmSpan'.$result[$i]['Firm']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue3('<?php print $view_name.'_parentid_'.$result[$i]['Firm']['id']; ?>','<?php print $view_name.'_account_number_'.$result[$i]['Firm']['id']; ?>','<?php print $view_name.'_firm_name_'.$result[$i]['Firm']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue3/'.$view_name.'/parent_id/account_number/firm_name/isactive/'.$result[$i]['Firm']['id']; ?>', '<?php print 'FirmParentId'.$result[$i]['Firm']['id']; ?>','<?php print 'FirmAccountNumber'.$result[$i]['Firm']['id']; ?>','<?php print 'FirmFirmName'.$result[$i]['Firm']['id']; ?>','<?php print 'FirmSpan'.$result[$i]['Firm']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Firm']['id'];?>','<?php print $result[$i]['Firm']['id']; ?>')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox_class('<?php print 'FirmSpan'.$result[$i]['Firm']['id']; ?>', '<?php print $result[$i]['Firm']['id']; ?>')">Cancel</a>
									 </span>	
							</td>
							<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Firm']['id']; ?>', 'Firm ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
						</tr>
				<?php }?>
			<?php }?>				
			<!-- End of edit and change status.-->			
						
			<!-- edit content type records-->
				<?php if ($view_name == 'contenttype'){?>
					<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>	
						<td width="5%" ><?php print $result[$i]['Contenttype']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueContenttypeContentType'.$result[$i]['Contenttype']['id']; ?>">
										<?php print $result[$i]['Contenttype']['content_type']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxContenttypeContentType'.$result[$i]['Contenttype']['id']; ?>">
									 			<?php echo $form->input('Contenttype.content_type', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Contenttype']['content_type'], 'id' => $view_name.'_'.$result[$i]['Contenttype']['id']));?><br/>
									</span>	
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('Contenttype.isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Contenttype', $result[$i]['Contenttype']['id']), 'onclick' => 'hidden_check('.$result[$i]['Contenttype']['id'].',this);', 'class' => 'isactive_'.$result[$i]['Contenttype']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Contenttype']['id'] )); ?>
							<span><input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Contenttype']['id']; ?>" value = ""></span>
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'ContenttypeContentType'.$result[$i]['Contenttype']['id']; ?>', '<?php print $result[$i]['Contenttype']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Contenttype']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1ContenttypeContentType'.$result[$i]['Contenttype']['id']; ?>">			
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Contenttype']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/content_type/isactive/'.$result[$i]['Contenttype']['id']; ?>', '<?php print 'ContenttypeContentType'.$result[$i]['Contenttype']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Contenttype']['id'];?>','<?php print $result[$i]['Contenttype']['id']; ?>', 'Content type')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'ContenttypeContentType'.$result[$i]['Contenttype']['id']; ?>', '<?php print $result[$i]['Contenttype']['id']; ?>')">Cancel</a>
									 </span>
						</td>			
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Contenttype']['id']; ?>', 'Content type ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>		
				<?php }
						}?>
				<!-- edit finished-->
			<!-- edit Insight type records-->
				<?php if ($view_name == 'insighttype'){?>
					<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>	
						<td width="5%" ><?php print $result[$i]['Insighttype']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueInsighttypeInsighttype'.$result[$i]['Insighttype']['id']; ?>">
										<?php print $result[$i]['Insighttype']['insight_type']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxInsighttypeInsighttype'.$result[$i]['Insighttype']['id']; ?>">
									 			<?php echo $form->input('Insighttype.insight_type', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Insighttype']['insight_type'], 'id' => $view_name.'_'.$result[$i]['Insighttype']['id']));?><br/>
									</span>	
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('Insighttype.isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Insighttype', $result[$i]['Insighttype']['id']), 'onclick' => 'hidden_check('.$result[$i]['Insighttype']['id'].',this);', 'class' => 'isactive_'.$result[$i]['Insighttype']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Insighttype']['id'] )); ?>
							<span><input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Insighttype']['id']; ?>" value = ""></span>
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'InsighttypeInsighttype'.$result[$i]['Insighttype']['id']; ?>', '<?php print $result[$i]['Insighttype']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Insighttype']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1InsighttypeInsighttype'.$result[$i]['Insighttype']['id']; ?>">			
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Insighttype']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/insight_type/isactive/'.$result[$i]['Insighttype']['id']; ?>', '<?php print 'InsighttypeInsighttype'.$result[$i]['Insighttype']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Insighttype']['id'];?>','<?php print $result[$i]['Insighttype']['id']; ?>', 'Insight type')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'InsighttypeInsighttype'.$result[$i]['Insighttype']['id']; ?>', '<?php print $result[$i]['Insighttype']['id']; ?>')">Cancel</a>
									 </span>
						</td>			
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Insighttype']['id']; ?>', 'Insight type ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>		
                                            <?php }
				}?>
				          
			<?php if ($view_name == 'insightabout'){?>
			<?php	for($i = 0; $i < count($result); $i++) {?>
			<tr>
				<td ><?php print $result[$i]['Insightabout']['id']; ?></td>
				<td ><span style="display:block" id="<?php print 'ValueInsightaboutInsightType'.$result[$i]['Insightabout']['id']; ?>">
									<?php print $result[$i]['Insightabout']['insight_type']; ?>
						</span>
						<span style="display:none" id="<?php print 'TextboxInsightaboutInsightType'.$result[$i]['Insightabout']['id']; ?>">
									 			<?php echo $form->input('Insightabout.insight_type', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Insightabout']['insight_type'], 'id' => $view_name.'_'.$result[$i]['Insightabout']['id']));?><br/>
						</span>
				</td>
				<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Insightabout', $result[$i]['Insightabout']['id']), 'class' => 'isactive_'.$result[$i]['Insightabout']['id'], 'disabled' => TRUE, 'id' => $view_name.'_isactive_'.$result[$i]['Insightabout']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Insightabout']['id'].',this);')); ?>
				<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Insightabout']['id']; ?>" value = ""></td>
				<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'InsightaboutInsightType'.$result[$i]['Insightabout']['id']; ?>', '<?php print $result[$i]['Insightabout']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Insightabout']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
						<span style="display:none" id = "<?php print 'Textbox1InsightaboutInsightType'.$result[$i]['Insightabout']['id']; ?>">
									<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Insightabout']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/insight_type/isactive/'.$result[$i]['Insightabout']['id']; ?>', '<?php print 'InsightaboutInsightType'.$result[$i]['Insightabout']['id']; ?>', '<?php print $view_name.'_isactive_'.$result[$i]['Insightabout']['id'];?>','<?php print $result[$i]['Insightabout']['id']; ?>', 'Insight come about')">Save</a>&nbsp;
									<a href="#" onclick="hide_editctypebox('<?php print 'InsightaboutInsightType'.$result[$i]['Insightabout']['id']; ?>', '<?php print $result[$i]['Insightabout']['id']; ?>')">Cancel</a>
						</span>
				</td>
				<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Insightabout']['id']; ?>', 'Insight come about ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
			</tr>
			<?php }}?>
				<!-- edit finished-->
				
			<!-- edit market records-->
				<?php if ($view_name == 'market'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Market']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueMarketMarket'.$result[$i]['Market']['id']; ?>">
										<?php print $result[$i]['Market']['market']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxMarketMarket'.$result[$i]['Market']['id']; ?>">
									 			<?php echo $form->input('Market.market', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Market']['market'], 'id' => $view_name.'_'.$result[$i]['Market']['id']));?><br/>
									</span>		
						</td>
							<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Market', $result[$i]['Market']['id']), 'class' => 'isactive_'.$result[$i]['Market']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Market']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Market']['id'].',this);')); ?>
							<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Market']['id']; ?>" value = "">
							</td>
							<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'MarketMarket'.$result[$i]['Market']['id']; ?>', '<?php print $result[$i]['Market']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Market']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1MarketMarket'.$result[$i]['Market']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Market']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/market/isactive/'.$result[$i]['Market']['id']; ?>', '<?php print 'MarketMarket'.$result[$i]['Market']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Market']['id'];?>','<?php print $result[$i]['Market']['id']; ?>', 'Market')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'MarketMarket'.$result[$i]['Market']['id']; ?>', '<?php print $result[$i]['Market']['id']; ?>')">Cancel</a>
									 </span>
							</td>
							<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Market']['id']; ?>', 'Market ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>
				<?php }}?>
				<!-- edit finished -->
				
					<!-- edit pilotgroup records-->
				<?php if ($view_name == 'pilotgroup'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Pilotgroup']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValuePilotgroupName'.$result[$i]['Pilotgroup']['id']; ?>">
												<?php print $result[$i]['Pilotgroup']['name']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxPilotgroupName'.$result[$i]['Pilotgroup']['id']; ?>">
									 			Username:<?php echo $form->input('Pilotgroup.name', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'value'=>$result[$i]['Pilotgroup']['name'], 'id' => $view_name.'_'.$result[$i]['Pilotgroup']['id']));?><br/>
									</span>			
									<span id="<?php print 'PassboxPilotgroupName'.$result[$i]['Pilotgroup']['id']; ?>" class="hideElement">
									 			Password: <?php echo $form->input('Pilotgroup.password', array('type'=>'password', 'label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'value'=>'', 'id' => $view_name.'_password_'.$result[$i]['Pilotgroup']['id']));?><br/>
									</span>			
						</td>
						<td width="25%"><?php print $result[$i]['Pilotgroup']['emailaddress']; ?></td>
						<!-- <td width="25%"><?php //print ($result[$i]['Pilotgroup']['department_name'] !='')?$result[$i]['Pilotgroup']['department_name']:''; ?></td> --->
						<?php if($result[$i]['Pilotgroup']['role'] == "A") {
										$isadmin = "checked";
									}else{
										$isadmin = "";
									}
						?>
						<td width="6%" align="center"><?php print $form->checkbox('role', array('label'=>false, 'checked' => $isadmin, 'onclick' => 'showpassbox("'.'PassboxPilotgroupName'.$result[$i]['Pilotgroup']['id'].'", "'.$result[$i]['Pilotgroup']['id'].'");', 'class' => 'isactive_'.$result[$i]['Pilotgroup']['id'], 'disabled' => TRUE, 'id' => 'roleChkBox_'.$result[$i]['Pilotgroup']['id'])); ?></td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $result[$i]['Pilotgroup']['isactive'], 'class' => 'isadmin_'.$result[$i]['Pilotgroup']['id'], 'disabled' => TRUE, 'id' => $view_name.'_isactive_'.$result[$i]['Pilotgroup']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Pilotgroup']['id'].',this);')); ?>
						<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Pilotgroup']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><!--<a href="#" onclick="show_editctypebox('<?php print 'PilotgroupName'.$result[$i]['Pilotgroup']['id']; ?>', '<?php print $result[$i]['Pilotgroup']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>-->
					<a href="javascript:void(0);" onclick="javascript:openAddNewWindow('<?php echo 'Edit User'; ?>','<?php echo SITE_URL?>/editrecords/edituser/<?php echo $view_name."/".$result[$i]['Pilotgroup']['id'];?>',<?php echo $BoxHeight;?>,500)"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>						
									<span style="display:none" id = "<?php print 'Textbox1PilotgroupName'.$result[$i]['Pilotgroup']['id']; ?>">
												<a href="#"  onclick="save_uservalues('<?php print $view_name.'_'.$result[$i]['Pilotgroup']['id']; ?>', '<?php print SITE_URL.'/editrecords/saveuser/'.$view_name.'/name/isactive/password/'.$result[$i]['Pilotgroup']['id']; ?>', '<?php print 'PilotgroupName'.$result[$i]['Pilotgroup']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Pilotgroup']['id'];?>','<?php print $result[$i]['Pilotgroup']['id']; ?>', '<?php print $view_name.'_password_'.$result[$i]['Pilotgroup']['id']; ?>', '<?php print 'roleChkBox_'.$result[$i]['Pilotgroup']['id']; ?>', 'User name')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'PilotgroupName'.$result[$i]['Pilotgroup']['id']; ?>', '<?php print $result[$i]['Pilotgroup']['id']; ?>')">Cancel</a>
									 </span>
						</td>
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Pilotgroup']['id']; ?>', 'User name ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>		
				<?php }}?>
				<!-- edit finished-->
				
				<!--edit practicearea records-->
				<?php if ($view_name == 'practicearea'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Practicearea']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValuePracticeareaPracticeArea'.$result[$i]['Practicearea']['id']; ?>">
												<?php print $result[$i]['Practicearea']['practice_area']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxPracticeareaPracticeArea'.$result[$i]['Practicearea']['id']; ?>">
												<?php echo $form->input('Practicearea.practice_area', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Practicearea']['practice_area'], 'id' => $view_name.'_'.$result[$i]['Practicearea']['id']));?><br/>
									</span> 			
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Practicearea', $result[$i]['Practicearea']['id']), 'class' => 'isactive_'.$result[$i]['Practicearea']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Practicearea']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Practicearea']['id'].',this);')); ?>
													<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Practicearea']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'PracticeareaPracticeArea'.$result[$i]['Practicearea']['id']; ?>', '<?php print $result[$i]['Practicearea']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Practicearea']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1PracticeareaPracticeArea'.$result[$i]['Practicearea']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Practicearea']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/practice_area/isactive/'.$result[$i]['Practicearea']['id']; ?>', '<?php print 'PracticeareaPracticeArea'.$result[$i]['Practicearea']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Practicearea']['id'];?>','<?php print $result[$i]['Practicearea']['id']; ?>', 'Practice area')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'PracticeareaPracticeArea'.$result[$i]['Practicearea']['id']; ?>', '<?php print $result[$i]['Practicearea']['id']; ?>')">Cancel</a>
									</span>
						</td>
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Practicearea']['id']; ?>', 'Practice Area ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>
				<?php }}?>
				<!-- edit finished-->
				
				<!-- edit productfamily names records-->
				<?php if ($view_name == 'productfamilyname'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Productfamilyname']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueProductfamilynameFamilyName'.$result[$i]['Productfamilyname']['id']; ?>">
									<?php print $result[$i]['Productfamilyname']['family_name']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxProductfamilynameFamilyName'.$result[$i]['Productfamilyname']['id']; ?>">
												<?php echo $form->input('Productfamilyname.family_name', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Productfamilyname']['family_name'], 'id' => $view_name.'_'.$result[$i]['Productfamilyname']['id']));?><br/>
									</span>		
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Productfamilyname', $result[$i]['Productfamilyname']['id']), 'class' => 'isactive_'.$result[$i]['Productfamilyname']['id'], 'disabled' => TRUE, 'id' => $view_name.'_isactive_'.$result[$i]['Productfamilyname']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Productfamilyname']['id'].',this);')); ?>
						<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Productfamilyname']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'ProductfamilynameFamilyName'.$result[$i]['Productfamilyname']['id']; ?>', '<?php print $result[$i]['Productfamilyname']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Productfamilyname']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1ProductfamilynameFamilyName'.$result[$i]['Productfamilyname']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Productfamilyname']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/family_name/isactive/'.$result[$i]['Productfamilyname']['id']; ?>', '<?php print 'ProductfamilynameFamilyName'.$result[$i]['Productfamilyname']['id']; ?>', '<?php print $view_name.'_isactive_'.$result[$i]['Productfamilyname']['id'];?>','<?php print $result[$i]['Productfamilyname']['id']; ?>', 'Product family name')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'ProductfamilynameFamilyName'.$result[$i]['Productfamilyname']['id']; ?>', '<?php print $result[$i]['Productfamilyname']['id']; ?>')">Cancel</a>
									</span>
						</td>
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Productfamilyname']['id']; ?>', 'Product Family Name ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				
				</tr>
				<?php } }?>
				<!-- edit finished-->
				<!-- edit productfamily names records-->
				<?php if ($view_name == 'statusinsight'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Statusinsight']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueStatusinsightStatus'.$result[$i]['Statusinsight']['id']; ?>">
										<?php print $result[$i]['Statusinsight']['status']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxStatusinsightStatus'.$result[$i]['Statusinsight']['id']; ?>">
												<?php echo $form->input('Statusinsight.status', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Statusinsight']['status'], 'id' => $view_name.'_'.$result[$i]['Statusinsight']['id']));?><br/>
									</span>		
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Statusinsight', $result[$i]['Statusinsight']['id']), 'class' => 'isactive_'.$result[$i]['Statusinsight']['id'], 'disabled' => TRUE, 'id' => $view_name.'_isactive_'.$result[$i]['Statusinsight']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Statusinsight']['id'].',this);')); ?>
						<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Statusinsight']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'StatusinsightStatus'.$result[$i]['Statusinsight']['id']; ?>', '<?php print $result[$i]['Statusinsight']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Statusinsight']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1StatusinsightStatus'.$result[$i]['Statusinsight']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Statusinsight']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/status/isactive/'.$result[$i]['Statusinsight']['id']; ?>', '<?php print 'StatusinsightStatus'.$result[$i]['Statusinsight']['id']; ?>', '<?php print $view_name.'_isactive_'.$result[$i]['Statusinsight']['id'];?>','<?php print $result[$i]['Statusinsight']['id']; ?>', 'Insight status')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'StatusinsightStatus'.$result[$i]['Statusinsight']['id']; ?>', '<?php print $result[$i]['Statusinsight']['id']; ?>')">Cancel</a>
									</span>
						</td>
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Statusinsight']['id']; ?>', 'Insight status ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				
				</tr>
				<?php } }?>
				<!-- edit finished-->
				
				<!-- edit productnames records-->
				<?php if ($view_name == 'productname'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Productname']['id']; ?></td>
						<td width="10%"><span id="<?php print 'ValueProductnameProductCode'.$result[$i]['Productname']['id']; ?>" class="<?php print 'ValueProductnameSpan'.$result[$i]['Productname']['id']; ?>">
									<?php print $result[$i]['Productname']['product_code']; ?>
									</span>
									 <span id="<?php print 'TextboxProductnameProductCode'.$result[$i]['Productname']['id']; ?>"  class="hideElement <?php print 'TextboxProductnameSpan'.$result[$i]['Productname']['id']; ?>">
									 			<?php echo $form->input('Productname.product_code', array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false, 'value'=>$result[$i]['Productname']['product_code'], 'id' => $view_name.'_product_code_'.$result[$i]['Productname']['id']));?><br  />
									</span>
						</td>			 
						<td ><span id="<?php print 'ValueProductnameProductName'.$result[$i]['Productname']['id']; ?>" class="<?php print 'ValueProductnameSpan'.$result[$i]['Productname']['id']; ?>">
									<?php print $result[$i]['Productname']['product_name']; ?>
									</span>
									<span id="<?php print 'TextboxProductnameProductName'.$result[$i]['Productname']['id']; ?>"  class="hideElement <?php print 'TextboxProductnameSpan'.$result[$i]['Productname']['id']; ?>">
									 			<?php echo $form->input('Productname.product_name', array('label'=>false,'class'=>'text-box3','size'=>'25','div'=>false, 'value'=>$result[$i]['Productname']['product_name'], 'id' => $view_name.'_product_name_'.$result[$i]['Productname']['id']));?><br />
									</span>
						</td>
						
						<td ><span id="<?php print 'ValueProductnameProductFamilyId'.$result[$i]['Productname']['id']; ?>" class="<?php print 'ValueProductnameSpan'.$result[$i]['Productname']['id']; ?>">
									<?php print "&nbsp;".$result[$i]['Productname']['product_family_id']; ?>
									</span>
									<span id="<?php print 'TextboxProductnameProductFamilyId'.$result[$i]['Productname']['id']; ?>"  class="hideElement <?php print 'TextboxProductnameSpan'.$result[$i]['Productname']['id']; ?>">
									 		<select id="<?php echo $view_name.'_product_family_id_'.$result[$i]['Productname']['id']?>" name="data[Productname][product_family_id]" style="width: height: 20px; border:1px solid #CCCCCC" onchange="javascript:var str = this.value; var fullstr = str.split(';'); document.getElementById('selProdfamilyname').value =fullstr[1];">
											<?php	
													foreach($arrProductFamilyNames as $key=>$value): 
														$selected = "";
														if ( trim($value) === trim($result[$i]['Productname']['product_family_id'])) $selected = "selected='selected'";?>								
														<option value="<?php echo $key.";".$value?>" <?php echo $selected;?> ><?php echo $value; ?></option>					
												<?php endforeach; ?>
												</select>
											<input type="hidden" value="<?php echo $result[$i]['Productname']['product_family_id'];?>" name="selProdfamilyname" id="selProdfamilyname" />
																								
									<?php //echo $form->input('Productname.product_name', array('label'=>false,'class'=>'text-box3','size'=>'25','div'=>false, 'value'=>$result[$i]['Productname']['product_name'], 'id' => $view_name.'_product_name_'.$result[$i]['Productname']['id']));?><br />
									</span>
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Productname', $result[$i]['Productname']['id']), 'class' => 'isactive_'.$result[$i]['Productname']['id'], 'disabled' => TRUE, 'id' => $view_name.'_isactive_'.$result[$i]['Productname']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Productname']['id'].',this);')); ?>
												<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Productname']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox_class('<?php print 'ProductnameSpan'.$result[$i]['Productname']['id']; ?>', '<?php print $result[$i]['Productname']['id']; ?>', 'productname')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span class="hideElement <?php print 'Textbox1ProductnameSpan'.$result[$i]['Productname']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue2('<?php print $view_name.'_product_code_'.$result[$i]['Productname']['id']; ?>','<?php print $view_name.'_product_name_'.$result[$i]['Productname']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue2/'.$view_name.'/product_code/product_name/isactive/product_family_id/'.$result[$i]['Productname']['id']; ?>','<?php print 'ProductnameProductCode'.$result[$i]['Productname']['id']; ?>','<?php print 'ProductnameProductName'.$result[$i]['Productname']['id']; ?>','<?php print 'ProductnameSpan'.$result[$i]['Productname']['id']; ?>', '<?php print $view_name.'_isactive_'.$result[$i]['Productname']['id'];?>','<?php print $result[$i]['Productname']['id']; ?>', 'Product','<?php print $view_name.'_product_family_id_'.$result[$i]['Productname']['id']; ?>','<?php print 'ProductnameProductFamilyId'.$result[$i]['Productname']['id']; ?>')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox_class('<?php print 'ProductnameSpan'.$result[$i]['Productname']['id']; ?>', '<?php print $result[$i]['Productname']['id']; ?>')">Cancel</a>
									</span>
						</td>			
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Productname']['id']; ?>', 'Product name ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				
				</tr>
				<?php }}?>
				<!-- edit finished -->

				<!-- edit productareas records-->
				<?php if ($view_name == 'productarea'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Productarea']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueProductareaProductArea'.$result[$i]['Productarea']['id']; ?>">
												<?php print $result[$i]['Productarea']['product_area']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxProductareaProductArea'.$result[$i]['Productarea']['id']; ?>">
												<?php echo $form->input('Productarea.product_area', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Productarea']['product_area'], 'id' => $view_name.'_'.$result[$i]['Productarea']['id']));?><br/>
									</span> 			
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Productarea', $result[$i]['Productarea']['id']), 'class' => 'isactive_'.$result[$i]['Productarea']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Productarea']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Productarea']['id'].',this);')); ?>
													<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Productarea']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'ProductareaProductArea'.$result[$i]['Productarea']['id']; ?>', '<?php print $result[$i]['Productarea']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Productarea']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1ProductareaProductArea'.$result[$i]['Productarea']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Productarea']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/product_area/isactive/'.$result[$i]['Productarea']['id']; ?>', '<?php print 'ProductareaProductArea'.$result[$i]['Productarea']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Productarea']['id'];?>','<?php print $result[$i]['Productarea']['id']; ?>', 'Product area')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'ProductareaProductArea'.$result[$i]['Productarea']['id']; ?>', '<?php print $result[$i]['Productarea']['id']; ?>')">Cancel</a>
									</span>
						</td>
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Productarea']['id']; ?>', 'Practice Area ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>
				<?php }}?>
				<!-- edit finished -->
				<!-- edit sessingobstacles records-->
				<?php if ($view_name == 'sellingobstacle'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Sellingobstacle']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueSellingobstacleSellingObstacle'.$result[$i]['Sellingobstacle']['id']; ?>">
												<?php print $result[$i]['Sellingobstacle']['selling_obstacles']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxSellingobstacleSellingObstacle'.$result[$i]['Sellingobstacle']['id']; ?>">
												<?php echo $form->input('Sellingobstacle.selling_obstacles', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Sellingobstacle']['selling_obstacles'], 'id' => $view_name.'_'.$result[$i]['Sellingobstacle']['id']));?><br/>
									</span> 			
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Sellingobstacle', $result[$i]['Sellingobstacle']['id']), 'class' => 'isactive_'.$result[$i]['Sellingobstacle']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Sellingobstacle']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Sellingobstacle']['id'].',this);')); ?>
													<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Sellingobstacle']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'SellingobstacleSellingObstacle'.$result[$i]['Sellingobstacle']['id']; ?>', '<?php print $result[$i]['Sellingobstacle']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Sellingobstacle']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1SellingobstacleSellingObstacle'.$result[$i]['Sellingobstacle']['id']; ?>">
												<a href="#"  onclick="save_ctypevalue('<?php print $view_name.'_'.$result[$i]['Sellingobstacle']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/selling_obstacles/isactive/'.$result[$i]['Sellingobstacle']['id']; ?>', '<?php print 'SellingobstacleSellingObstacle'.$result[$i]['Sellingobstacle']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Sellingobstacle']['id'];?>','<?php print $result[$i]['Sellingobstacle']['id']; ?>', 'Selling Obstacles')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'SellingobstacleSellingObstacle'.$result[$i]['Sellingobstacle']['id']; ?>', '<?php print $result[$i]['Sellingobstacle']['id']; ?>')">Cancel</a>
									</span>
						</td>
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Sellingobstacle']['id']; ?>', 'Selling Obstacle ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>
				<?php }}?>
			<?php if ($view_name == 'departmentname'){?>
				<?php	for($i = 0; $i < count($result); $i++) {?>
				<tr>
						<td width="5%"><?php print $result[$i]['Departmentname']['id']; ?></td>
						<td ><span style="display:block" id="<?php print 'ValueDepartmentnameDepartmentName'.$result[$i]['Departmentname']['id']; ?>">
												<?php print $result[$i]['Departmentname']['department_name']; ?>
									</span>
									<span style="display:none" id="<?php print 'TextboxDepartmentnameDepartmentName'.$result[$i]['Departmentname']['id']; ?>">
												<?php echo $form->input('Departmentname.department_name', array('label'=>false,'class'=>'text-box2','size'=>'25','div'=>false, 'value'=>$result[$i]['Departmentname']['department_name'], 'id' => $view_name.'_'.$result[$i]['Departmentname']['id']));?><br/>
									</span> 			
						</td>
						<td width="6%" align="center"><?php print $form->checkbox('isactive', array('label'=>false, 'checked' => $this->Custom->getStatusActive('Departmentname', $result[$i]['Departmentname']['id']), 'class' => 'isactive_'.$result[$i]['Departmentname']['id'], 'disabled' => TRUE,'id' => $view_name.'_isactive_'.$result[$i]['Departmentname']['id'] , 'onclick' => 'hidden_check('.$result[$i]['Departmentname']['id'].',this);')); ?>
													<input type = "hidden" id = "hidden_chk_<?php print $result[$i]['Departmentname']['id']; ?>" value = "">
						</td>
						<td width="6%" align="center"><a href="#" onclick="show_editctypebox('<?php print 'DepartmentnameDepartmentName'.$result[$i]['Departmentname']['id']; ?>', '<?php print $result[$i]['Departmentname']['id']; ?>', '<?php print $view_name.'_'.$result[$i]['Departmentname']['id']; ?>')"><img src="<?php echo IMAGE_URL?>/images.jpeg" alt="edit" class="edition" /></a>
									<span style="display:none" id = "<?php print 'Textbox1DepartmentnameDepartmentName'.$result[$i]['Departmentname']['id']; ?>">
												<a href="#"  onclick="save_ctypevaluespecial('<?php print $view_name.'_'.$result[$i]['Departmentname']['id']; ?>', '<?php print SITE_URL.'/editrecords/savevalue/'.$view_name.'/department_name/isactive/'.$result[$i]['Departmentname']['id']; ?>', '<?php print 'DepartmentnameDepartmentName'.$result[$i]['Departmentname']['id']; ?>','<?php print $view_name.'_isactive_'.$result[$i]['Departmentname']['id'];?>','<?php print $result[$i]['Departmentname']['id']; ?>', 'Department Name')">Save</a>&nbsp;
												<a href="#" onclick="hide_editctypebox('<?php print 'DepartmentnameDepartmentName'.$result[$i]['Departmentname']['id']; ?>', '<?php print $result[$i]['Departmentname']['id']; ?>')">Cancel</a>
									</span>
						</td>
						<td width="6%" align="center" ><a href="#" onclick="delete_ctypevalue('<?php print SITE_URL.'/editrecords/removerecord/'.$view_name.'/'.$result[$i]['Departmentname']['id']; ?>', 'Department Name ')"><img src="<?php echo IMAGE_URL?>/index.jpeg" alt="delete" class="deletion" /></a></td>
				</tr>
				<?php }}?>				
				<!-- edit finished -->
		<?php 	}
			else {
		?>
			<tr>
				<td colspan="8" align="center" class="red">No result found.</td>
			</tr>
		<?php	
			}
		?>

		<!--<div class="buttonrow">-->
		<div>
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td width="41%" align="left">
			<div class="pagedisplay">
				<?php if(isset($result) && count($result)>0) { ?>
				<!-- Shows the page numbers -->
				<?php echo $paginator->first();?>&nbsp;
				<?php if(trim($this->Paginator->numbers(array('modulus'=>9)))!="") { ?>
					<?php echo $paginator->prev('< '.__('prev', true), array(), null, array('class'=>'disabled'));?>&nbsp;
					<?php echo $this->Paginator->numbers(array('modulus'=>9)); ?>&nbsp;
					<?php echo $paginator->next(__('next', true).' >', array(), null, array('class'=>'disabled'));?>&nbsp;
					<?php echo $paginator->last();?>
				<?php } ?>

				<!-- prints X of Y, where X is current page and Y is number of pages -->
				<?php echo $this->Paginator->counter(array('format' => '<br />Page %page% of %pages%')); ?>
				<?php } ?> 
				</div>
			</td>
			<td width="59%" align="left">&nbsp;
				
			</td>
		</tr>
		</table>
	</div>
</div>
			