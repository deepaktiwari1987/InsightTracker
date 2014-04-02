<?php
/*
* File Name :  results.ctp
* Developer :  Mohit Khurana
* @author LexisNexis Development Team
* Cake Version : 1.3.4 
* @copyright Copyright (c) 2010, LexisNexis
* Functionality / Description : The purpose of this file is to display the results returned after searching.
* @Modified By: Gaurav Saini
* @Modified Functionality: a. New lookup field added to search Issues.
			   b. Recently added comments displayed with insight.
			   c. Issue field displayed with Insight.
			   d. SME Confirmation implemented before displaying the Insight details.
*@Modified By: Sukhvir Singh
      *Following changes
        a. How did this feedback come about rename to “Origin of feedback”
        b. Content Type removed
        c. Feedback type rename Insight Type
        d. Contact Name /Role  divided into two fields called Contact Name and Role
        e. Suggested Next Steps to be renamed with ‘Required actions / suggested next steps’.
*/
?>

<div id="textcontainer">
  <?php 
  //  echo '<pre>'; print_r($final_result); die;
  
                        $exportlink = "#";
			$class = 'class="link_disabled"';
			if(isset($final_result) && count($final_result)>0) {
				$exportlink = SITE_URL."/products/exportToExcel/product";
			$class = 'class="link_enabled"';
			}
			
	if($this->Session->check('current_user_role')) {
	      $current_user_id = intval($this->Session->read('current_user_id'));
	      $current_user_role = $this->Session->read('current_user_role');
	}

?>
<?php echo $form->create('Product', array('action'=>'/results','id'=>'productInsightForm','name'=>'productInsightForm', 'method' => 'post'));?>

  <div class="hr-row">
    <table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
     <?php if( $search_string != "Free Text Search" && trim($search_string) != "") { ?>  <td width="15%" ><h2>Search Results for <?php if(strlen($search_string) > 15) echo $str = substr($search_string,0,13)."..."; else { echo $search_string; }?>. </h2></td><?php } ?>
        <td width="15%" class="subhead"><?php if($total_count > 0) echo $total_count; else echo '0';?> Results returned</td>
        <td align="left">
	<?php echo $form->input('sort_type', array('label'=>false,'options' => $sort_type,'div'=>false, 'onchange' =>'javascript:document.productInsightForm.submit();')); ?>&nbsp;
	<?php
		$checked1 =$checked ='';
		if(strcmp(trim($ordering), "asc") == 0)  $checked1 = "checked=checked"; elseif(strcmp(trim($ordering), "desc") == 0)  $checked ="checked=checked"; 
	?>
	<input type="radio" name="data[Product][ordering]" value="asc" onclick='javascript:document.productInsightForm.submit();'  <?php echo $checked1; ?> >Ascending &nbsp; 
	<input type="radio" name="data[Product][ordering]" value="desc"  onclick='javascript:document.productInsightForm.submit();' <?php echo  $checked; ?> >Descending
	</td>
        <td width="12%" align="right" ><a href="<?php print $exportlink; ?>" <?php print $class; ?>>Export Results</a></td>
      </tr>
    </table>
  </div>
  <div class="hr-row-new" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
      <tr>
        <td style="padding:0px 0px 0px 5px !important; border-left:0px; width:266px;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="curve-top">
		</td>
	</tr>

	<tr>
		<td  class="refine-search" style="padding:0px 4px !important;*padding:0px 6px !important; _padding:0px 6px !important;">

		<table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
              <td><h3>Refine Search</h3></td>
            </tr>
              <tr>
                         <td>
                                 <input type="hidden" name="data[Product][free_search_text]" value="<?php echo $search_string;?>" />
                                  <?php if($basic_search_what_insight_type == "yes") { ?>
                                                  <?php echo $form->input('what_insight_type', array('label'=>false,'options' => $arrinsighttype,'div'=>false, 'disabled' => true)); ?>
                                                  <input type="hidden" name="data[Product][what_insight_type]" value="<?php echo $value_what_insight_type;?>" />
                                                   <input type="hidden" name="data[Product][basic_search_what_insight_type]" value="yes" />			
                                  <?php } else {?>
                                          <?php echo $form->input('what_insight_type', array('label'=>false,'options' => $arrinsighttype,'div'=>false)); ?>		
                                   <?php } ?>
                                   </td>
                                                 
	    </tr>
            <tr>
              <td>
	      <?php if($basic_search_what_how_come == "yes") { ?>
		      <?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false, 'disabled' => true)); ?>
		      <input type="hidden" name="data[Product][what_how_come]" value="<?php echo $value_what_how_come;?>" />
		      <input type="hidden" name="data[Product][basic_search_what_how_come]" value="yes" />
	      <?php } else {?>
		  <?php echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false)); ?>
	      <?php } ?>
	      </td>
            </tr>
	     <tr>
		<td>
		  <?php if($basic_search_what_firm_name == "yes") { ?>
			<?php echo $form->input('Firm.what_firm_name', array('label'=>false,'div'=>false,'class'=>'search-input-new','disabled'=>true,'class'=>'readonlycls','readonly'=>'readonly')); ?>
			<input type="hidden" name="data[Firm][what_firm_name]" value="<?php echo $value_what_firm_name;?>" />
			 <input type="hidden" name="data[Product][basic_search_what_firm_name]" value="yes" />
		<?php } else {
				$value_what_firm_name  = $value_what_firm_name == '' ? 'Organisation Name':$value_what_firm_name;
		?>
				<?php echo $form->input('Firm.what_firm_name', array('label'=>false,'div'=>false,'class'=>'search-input-new', 'onfocus'=>"if(this.value=='Organisation Name') this.value='';", 'onblur'=>"if(this.value=='') this.value='Organisation Name';", 'value'=>$value_what_firm_name)); ?>
		<?php } ?>
			<script type="text/javascript">
			  new Autocomplete('FirmWhatFirmName', { 
			    serviceUrl:'<?php echo SITE_URL?>/firms/autoComplete',
			    minChars:2, 
			    maxHeight:400,
			    width:400,
			    deferRequestBy:100,
			    // callback function:
			    onSelect: function(value, data){
				//alert('You selected: ' + value + ', ' + data);
			      }
			  });
			</script>
		</td>
	    </tr>
             
	    <tr>
		<td>
		 <?php if($basic_search_who_contact_name == "yes") { ?>
			<?php echo $form->input('who_contact_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false ,'disabled'=>true,'class'=>'readonlycls','readonly'=>'readonly'));?>
			<input type="hidden" name="data[Product][who_contact_name]" value="<?php echo $value_who_contact_name;?>" />
			 <input type="hidden" name="data[Product][basic_search_who_contact_name]" value="yes" />
		
		<?php } else {
				$value_who_contact_name  = $value_who_contact_name == '' ? 'Contact Name':$value_who_contact_name;
		?>
			<?php echo $form->input('who_contact_name',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false ,'value'=>$value_who_contact_name, 'onfocus'=>"if(this.value=='Contact Name') this.value='';", 'onblur'=>"if(this.value=='') this.value='Contact Name';"));?>
			 <?php } ?>
		</td>
	    </tr>
             <tr>
               <td>
	       <?php if($basic_search_who_contact_role == "yes") { ?>
					<?php echo $form->input('who_contact_role',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false,'disabled'=>true,'class'=>'readonlycls','readonly'=>'readonly'));?>
					<input type="hidden" name="data[Product][who_contact_role]" value="<?php echo $value_who_contact_role;?>" />
					 <input type="hidden" name="data[Product][basic_search_who_contact_role]" value="yes" />			
			<?php } else {
				//$value_who_contact_role  = $value_who_contact_role == '' ? 'Contact Name / Role':$value_who_contact_role;
				$value_who_contact_role  = $value_who_contact_role == '' ? 'Role':$value_who_contact_role;
		?>
			<?php echo $form->input('who_contact_role',array('label'=>false,'class'=>'text-box1','size'=>'25','div'=>false,'value'=>$value_who_contact_role, 'onfocus'=>"if(this.value=='Role') this.value='';", 'onblur'=>"if(this.value=='') this.value='Role';"));?>
		 <?php } ?>
		</td>
           </tr>
	    <tr>
		<td>
		 <?php if($basic_search_who_product_family_name == "yes") { ?>
			<?php echo $form->input('Productfamilyname.who_product_family_name', array('label'=>false,'options' => $arrProductFamilynames,'div'=>false,'disabled'=>true)); ?>
			 <input type="hidden" name="data[Productfamilyname][who_product_family_name]" value="<?php echo $value_who_product_family_name;?>" />
			  <input type="hidden" name="data[Product][basic_search_who_product_family_name]" value="yes" />
		<?php } else {?>
			<?php echo $form->input('Productfamilyname.who_product_family_name', array('label'=>false,'options' => $arrProductFamilynames,'div'=>false)); ?>
		<?php } ?>
		</td>
	    </tr>
	    <tr>
	    <td>
			
		<?php //echo $ajax->autoComplete('Productname.who_product_name', '/productnames/autoCompleteProductNames', array('minChars' => 3,'class'=>'search-input-new'))?>
		 <?php if($basic_search_who_product_name == "yes") { ?>
			<?php echo $form->input('Productname.who_product_name', array('label'=>false,'div'=>false,'class'=>'search-input-new' ,'disabled'=>true,'class'=>'readonlycls','readonly'=>'readonly')); ?>
			<input type="hidden" name="data[Productname][who_product_name]" value="<?php echo $value_who_product_name;?>" />
			 <input type="hidden" name="data[Product][basic_search_who_product_name]" value="yes" />
		
		<?php } else {
			$value_who_product_name  = $value_who_product_name == '' ? 'Product Name':$value_who_product_name;
		?>
			<?php echo $form->input('Productname.who_product_name', array('label'=>false,'div'=>false,'class'=>'search-input-new' , 'onfocus'=>"if(this.value=='Product Name') this.value='';", 'onblur'=>"if(this.value=='') this.value='Product Name';", 'value'=>$value_who_product_name)); ?>
		 <?php } ?>
		<script type="text/javascript">
		  new Autocomplete('ProductnameWhoProductName', { 
		    serviceUrl:'<?php echo SITE_URL?>/productnames/autoComplete',
		    minChars:2, 
		    maxHeight:400,
		    width:450,
		    deferRequestBy:100,
		    // callback function:
		    onSelect: function(value, data){
			//alert('You selected: ' + value + ', ' + data);
		      }
		  });
		</script>
			
		</td>
	    </tr>
	 
	     <tr>
		<td>
		<?php if($basic_search_practice_area_id == "yes") { ?>
			<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false,'disabled'=>true)); ?>
			<input type="hidden" name="data[Product][practice_area_id]" value="<?php echo $value_practice_area_id;?>" />
			 <input type="hidden" name="data[Product][basic_search_practice_area_id]" value="yes" />
		<?php } else {?>
			<?php echo $form->input('practice_area_id', array('label'=>false,'options' => $arrPracticeArea,'div'=>false)); ?>
		<?php } ?>
		</td>
	    </tr>  
            <?php if($legalqaFlag!=1){
                   
                  ?>
            <tr>
              <td>	      
	      <?php if($basic_search_who_competitor_name == "yes") { ?>
			<?php echo $form->input('Competitorname.who_competitor_name', array('label'=>false,'div'=>false,'class'=>'search-input-new','disabled'=>true,'class'=>'readonlycls','readonly'=>'readonly')); ?>
			<input type="hidden" name="data[Competitorname][who_competitor_name]" value="<?php echo $value_who_competitor_name;?>" />
			 <input type="hidden" name="data[Product][basic_search_who_competitor_name]" value="yes" />
	     <?php } else {
				$value_who_competitor_name  = $value_who_competitor_name == '' ? 'Competitor':$value_who_competitor_name;
	     ?>
			<?php echo $form->input('Competitorname.who_competitor_name', array('label'=>false,'div'=>false,'class'=>'search-input-new', 'onfocus'=>"if(this.value=='Competitor') this.value='';", 'onblur'=>"if(this.value=='') this.value='Competitor';", 'value'=>$value_who_competitor_name)); ?>
		<?php } ?>
					<script type="text/javascript">
					  new Autocomplete('CompetitornameWhoCompetitorName', { 
					    serviceUrl:'<?php echo SITE_URL?>/Competitors/autoComplete',
					    minChars:2, 
					    maxHeight:400,
					    width:300,
					    deferRequestBy:100,
					    // callback function:
					    onSelect: function(value, data){
						//alert('You selected: ' + value + ', ' + data);
					      }
					  });
					</script></td>
            </tr>
	     <tr>
              <td>
	        <?php if($basic_search_selling_obstacle_id == "yes") { ?>
			  <?php echo $form->input('selling_obstacle_id', array('label'=>false,'options' => $arrSellingObstacles,'div'=>false,'disabled'=>true)); ?>
			  <input type="hidden" name="data[Product][selling_obstacle_id]" value="<?php echo $value_selling_obstacle_id;?>" />
			  <input type="hidden" name="data[Product][basic_search_selling_obstacle_id]" value="yes" />
		 <?php } else {?>
			  <?php echo $form->input('selling_obstacle_id', array('label'=>false,'options' => $arrSellingObstacles,'div'=>false)); ?>
		     <?php } ?>
		 </td>
            </tr>
            <?php } ?>
	    <tr>
		 <td>
		   <?php if($basic_search_user_id == "yes") { ?>
			<?php echo $form->input('user_id', array('label'=>false,'options' => $arrCreatedBy,'div'=>false,'disabled'=>true)); ?>
			 <input type="hidden" name="data[Product][user_id]" value="<?php echo $value_user_id;?>" />
			  <input type="hidden" name="data[Product][basic_search_user_id]" value="yes" />
		<?php } else {?>
			<?php echo $form->input('user_id', array('label'=>false,'options' => $arrCreatedBy,'div'=>false)); ?>
		 <?php } ?>
		</td>
	    </tr>
		
	    <tr>
		<td>	<h4>Current Status:</h4>
		  <?php if($basic_search_insight_status == "yes") {
				 
				$full = "";
				foreach($arrStatusinsight[0] as $key =>$val) {					
					foreach($value_insight_status as $k1 => $v1)
					{
						if($key == $v1) $full .= $val .", ";
					}
				}

				$full = substr($full, 0,-2);
				echo "<span class='text01'>".$full."</span>";
				
				foreach($value_insight_status as $k1 => $v1)
				{
					$val_insight .= $v1.",";
				}
				$val_insight = substr($val_insight, 0,-1);
				
		  ?>
			<?php echo $form->input('insight_status', array('label'=>false,'div'=>false, 'options' => $arrStatusinsight[0],'disabled'=>true,'style'=>'display:none')) ?>
			 <input type="hidden" name="data[Product][insight_status]" value="<?php echo( $val_insight);?>" />
			  <input type="hidden" name="data[Product][basic_search_insight_status]" value="yes" />
		<?php } else {?>
			<?php echo $form->input('insight_status', array('label'=>false,'div'=>false, 'options' => $arrStatusinsight, 'type' => 'select', 'multiple' => 'checkbox')) ?>
		 <?php } ?>
		</td>
	    </tr>
	    <tr>
		<td>	
		 <?php if($basic_search_deligated_to == "yes") { ?>
			<?php echo $form->input('deligated_to', array('label'=>false,'options' => $arrDelegatedTo,'div'=>false, 'class' => 'selectBox1','disabled'=>true)); ?>
			<input type="hidden" name="data[Product][deligated_to]" value="<?php echo $value_deligated_to;?>" />
			<input type="hidden" name="data[Product][basic_search_deligated_to]" value="yes" />
		<?php } else {?>
			<?php echo $form->input('deligated_to', array('label'=>false,'options' => $arrDelegatedTo,'div'=>false, 'class' => 'selectBox1')); ?>	
		 <?php } ?>
		</td>
	    </tr>
     	    <tr>
			<td>	
			 <?php if($basic_search_market_id == "yes") { ?>
				<?php echo $form->input('market_id', array('label'=>false,'options' => $arrWhoMarket,'div'=>false, 'class' => 'selectBox1','disabled'=>true)); ?>
				<input type="hidden" name="data[Product][market_id]" value="<?php echo $value_market_id;?>" />
			<input type="hidden" name="data[Product][basic_search_market_id]" value="yes" />
			<?php } else {?>
				<?php echo $form->input('market_id', array('label'=>false,'options' => $arrWhoMarket,'div'=>false, 'class' => 'selectBox1')); ?>	
			 <?php } ?>

		</td>
		</tr>
		<!--- Issue list ----->
		<tr>
		 <td>
		 
		 
		 <?php if($basic_search_issue_field == "yes") { ?>
			<?php echo $form->input('Issue.issue_title', array('label'=>false,'div'=>false,'class'=>'search-input-new','disabled'=>true,'class'=>'readonlycls','readonly'=>'readonly')); ?>
			<input type="hidden" name="data[Issue][issue_title]" value="<?php echo $value_issue_field;?>" />
			<input type="hidden" name="data[Product][basic_search_issue_field]" value="yes" />
	     <?php } else {
				$value_issue_field  = ($value_issue_field == '') ? 'Issue':$value_issue_field;
	     ?>
			<?php echo $form->input('Issue.issue_title', array('label'=>false,'div'=>false,'class'=>'search-input-new', 'onfocus'=>"if(this.value=='Issue') this.value='';", 'onblur'=>"if(this.value=='') this.value='Issue';", 'value'=>$value_issue_field)); ?>
		<?php } ?>
		 
			<script type="text/javascript">
			  new Autocomplete('IssueIssueTitle', { 
				serviceUrl:'<?php echo SITE_URL?>/issues/autoComplete',
				minChars:2, 
				maxHeight:400,
				width:400,
				deferRequestBy:100,
				// callback function:
				onSelect: function(value, data){
					//alert('You selected: ' + value + ', ' + data);
				  }
			  });
			</script>
		 
		   <?php /*if($basic_search_user_id == "yes") { ?>
			<?php echo $form->input('issue_field', array('label'=>false,'options' => $arrIssues,'div'=>false,'disabled'=>true)); ?>
			 <input type="hidden" name="data[Product][issue_field]" value="<?php echo $value_issue_field;?>" />
			  <input type="hidden" name="data[Product][basic_search_issue_field]" value="yes" />
		<?php } else {?>
			<?php echo $form->input('issue_field', array('label'=>false,'options' => $arrIssues,'div'=>false)); ?>
		 <?php } */?>
		</td>
	    </tr>
		<!--- Issue list ----->
		
		<!--- Mobile Option ----->
		<tr>
		<td><h4>Added by Mobile:</h4>
		  <div class="checkbox">
			<input type="radio" name="data[Product][flag_mobile]" value="Y" <?php if($value_flag_mobile =='Y') {echo "checked";}?>/>Yes&nbsp;&nbsp;
			<input type="radio" name="data[Product][flag_mobile]" value="N" <?php if($value_flag_mobile =='N') {echo "checked";}?>/>No&nbsp;&nbsp;
			<input type="radio" name="data[Product][flag_mobile]" value="" <?php if($value_flag_mobile =='') {echo "checked";}?>/>Both

		  <?php 		  
		  /*
		  if($basic_search_flag_mobile == "yes") { ?>
			<?php //echo $form->input('flag_mobile', array('label'=>false,'div'=>false, 'value' => 'Y','disabled'=>true,'style'=>'display:none')) ?> 
			 <input type="radio" name="data[Product][flag_mobile]" value="Y"/>Yes&nbsp;&nbsp;
			<input type="radio" name="data[Product][flag_mobile]" value="N"/>No&nbsp;&nbsp;
			<input type="radio" name="data[Product][flag_mobile]" value=""/>Both
			<input type="hidden" name="data[Product][flag_mobile]" value="Y" />
			 <input type="hidden" name="data[Product][basic_search_flag_mobile]" value="yes" />
		<?php } else {?>
			<?php //echo $form->input('flag_mobile', array('label'=>false,'div'=>false, 'value' => 'Y', 'type' => 'checkbox')) ?> 
			<input type="radio" name="data[Product][flag_mobile]" value="Y"/>Yes&nbsp;&nbsp;
			<input type="radio" name="data[Product][flag_mobile]" value="N"/>No&nbsp;&nbsp;
			<input type="radio" name="data[Product][flag_mobile]" value=""/>Both
		 <?php } */?>
		 </div>
		</td>
	    </tr>
		<!--- Mobile Option ----->		
          <tr>
		  <td>
			  <div class="buttonrow1">
				<input name="refine" type="submit" value="Refine" style="width:110px; *width:110px"/>
				&nbsp;
				<input name="reset" type="Reset" value="Clear" style="width:70px" />
			  </div>
		  </td>
	  </tr>
	  </table>
	  
	  
		</td>
	</tr>

	<tr>
		<td class="curve-bottom">
		</td>
	</tr>

</table>	
	  
	  </td> 
	 <td style="border-left:none;">
 <?php 
	if(isset($final_result) && count($final_result)>0) {
		for($i = 0; $i < count($final_result); $i++) { 

			$DeligatedSME		= intval($final_result[$i]['deligated_to']);
			$InsightContributor = intval($final_result[$i]['user_id']);
			
			$bgcolor = "";
			$bgcolor_left ="class='left-block'";
			$bgcolor_right ="class='right-block1'";
			
			if($i % 2 ==0) {
			$bgcolor_left ="class='left-block'";
			$bgcolor_right ="class='right-block'";
			}
?>
       <div style="position:relative;"> 
	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
	<tr>
		 <td width="270px"  style="padding:10px 10px 10px 10px !important" <?php echo $bgcolor_left; ?> >
		 	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			    <tr>
				<td><b>Feedback Id</b> - <?php print $final_result[$i]['id']; ?>				
				</td>
			    </tr>
			       <tr>
				<td><b>Insight type</b> <?php //print (trim($final_result[$i]['what_insight_type']) != "")?" - ".$final_result[$i]['what_insight_type']:'-'; ?>
				<?php 
                                        # check if what insight type is zero then should be show Blank
						// $whatinsighttype = $final_result[$i]['what_insight_type'];
						$whatinsighttype = $arrinsighttype[($final_result[$i]['what_insight_type']==0?11:$final_result[$i]['what_insight_type'])]; //$arrinsighttype[$final_result[$i]['what_insight_type']];
						if(stristr($whatinsighttype, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$whatinsighttype = str_ireplace($search_string,$replace,$whatinsighttype);
						}
						print (trim($final_result[$i]['what_insight_type']) != "")?" - ".ucwords(strtolower($whatinsighttype)):'-'; ?>
				</td>
			    </tr>
                
			     <tr>
				<td><b>Source of Feedback</b>? <?php //print (trim($final_result[$i]['what_how_come']) != "")?" - ".$final_result[$i]['what_how_come']:'-'; ?>
				<?php 
						$source = $final_result[$i]['what_how_come'];
						if(stristr($source, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$source = str_ireplace($search_string,$replace,$source);
						}
						print (trim($final_result[$i]['what_how_come']) != "")?" - ".$source:'-'; ?>
				</td>
			    </tr>
			     <tr>
				<td><b>Organisation</b> <?php //print (trim($final_result[$i]['firmName']) != "")?" - ".$final_result[$i]['firmName']:'-'; ?>
				<?php
						$firmName = $final_result[$i]['firmName'];
						if(stristr($firmName, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$firmName = str_ireplace($search_string,$replace,$firmName);
						}
						print (trim($final_result[$i]['firmName']) != "")?" - ".ucwords(strtolower($firmName)):'-'; ?>
				</td>
			    </tr>
			     <tr>
				<td><b>Contact Name</b> <?php //print (trim($final_result[$i]['who_contact_name']) != "")?" - ".$final_result[$i]['who_contact_name']:'-'; ?>
				<?php 
						$contactname = $final_result[$i]['who_contact_name'];
						if(stristr($contactname, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$contactname = str_ireplace($search_string,$replace,$contactname);
						}
						print (trim($final_result[$i]['who_contact_name']) != "")?" - ".$contactname:'-'; ?>
				</td>
			    </tr>
                            <?php if($legalqaFlag!=1){
                   
                             ?>
                            <tr>
				<td><b>Role</b> <?php //print (trim($final_result[$i]['who_contact_role']) != "")?" - ".$final_result[$i]['who_contact_role']:'-'; ?>
				<?php 
						$contactrole = $final_result[$i]['who_contact_role'];
						if(stristr($contactrole, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$contactrole = str_ireplace($search_string,$replace,$contactrole);
						}
						print (trim($final_result[$i]['who_contact_role']) != "")?" - ".$contactrole:'-'; ?>
				</td>
			    </tr>
                            <?php } ?>
			    
			      <tr>
				<td><b>Product Family Name</b> <?php //print (trim($final_result[$i]['who_product_familyName']) != "")?" - ".$final_result[$i]['who_product_familyName']:'-'; ?>
				<?php 
						$prodfamilyname = $final_result[$i]['who_product_familyName'];
						if(stristr($prodfamilyname, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$prodfamilyname = str_ireplace($search_string,$replace,$prodfamilyname);
						}
						print (trim($final_result[$i]['who_product_familyName']) != "")?" - ".ucwords(strtolower($prodfamilyname)):'-'; ?>
				</td>
			    </tr>
			     <tr>
				<td><b>Product Name </b>
				<?php
						$prodname = $final_result[$i]['who_productName'];
						if(stristr($prodname, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$prodname = str_ireplace($search_string,$replace,$prodname);
						}
						print (trim($final_result[$i]['who_productName']) != "")?" - ".ucwords(strtolower($prodname)):'-'; ?>
				</td>
			    </tr>
                            
                          
			     <tr>
				<td><b>Practice Area </b><?php //print (trim($final_result[$i]['practice_area_id']) != "")?" - ".$final_result[$i]['practice_area_id']:'-'; ?>
				<?php 
						$practicearea = trim($final_result[$i]['practice_area_id']);
						if(stristr($practicearea, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$practicearea = str_ireplace($search_string,$replace,$practicearea);
						}
						print (trim($final_result[$i]['practice_area_id']) != "")?" - ".$practicearea:'-'; ?>
				</td>
			    </tr>
                             <?php if($legalqaFlag!=1){
                   
                              ?>
			     <tr>
				<td><b>Competitor </b><?php //print (trim($final_result[$i]['competitor_id']) != "")?" - ".$final_result[$i]['competitor_id']:'-'; ?>
				<?php 
						$competitor = $final_result[$i]['competitor_id'];
						if(stristr($competitor, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$competitor = str_ireplace($search_string,$replace,$competitor);
						}
						print (trim($final_result[$i]['competitor_id']) != "")?" - ".ucwords(strtolower($competitor)):'-'; ?>
				</td>
			    </tr>
			      <tr>
				<td><b>Selling Obstacles </b><?php //print (trim($final_result[$i]['selling_obstacle_id']) != "")?" - ".$final_result[$i]['selling_obstacle_id']:'-'; ?>
				<?php 
						$sellingobs = $final_result[$i]['selling_obstacle_id'];
						if(stristr($sellingobs, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$sellingobs = str_ireplace($search_string,$replace,$sellingobs);
						}
						print (trim($final_result[$i]['selling_obstacle_id']) != "")?" - ".$sellingobs:'-'; ?>
				</td>
			    </tr>
                             <?php } ?>
			     <tr>
				<td><b>Created By </b><?php //print (trim($final_result[$i]['userSubmittedName']) != "")?" - ".$final_result[$i]['userSubmittedName']:'-'; ?>
				<?php 
						$usersub = $final_result[$i]['userSubmittedName'];
						if(stristr($usersub, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$usersub = str_ireplace($search_string,$replace,$usersub);
						}
						print (trim($final_result[$i]['userSubmittedName']) != "")?" - ".$usersub:'-'; ?>
				</td>
			    </tr>
				<tr>
				<td><b>Creation Date</b> - 
				<?php
                  if(isset($final_result[$i]['date_submitted']) && $final_result[$i]['date_submitted'] != ''){  				
				    list($y,$m,$d) =split('[- ]',$final_result[$i]['date_submitted']);
			        echo date('dS M Y', mktime(0, 0, 0, $m, $d, $y));
				  }
				?>				
				</td>
			    </tr>
			     <tr>
				<td><b>Current Status </b><?php //print (trim($custom->getCurrentStatus($final_result[$i]['insight_status'])) != "")?" - ".$custom->getCurrentStatus($final_result[$i]['insight_status']):'-'; ?>
				<?php 
						$status = $custom->getCurrentStatus($final_result[$i]['insight_status']);
						if(stristr($status, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$status = str_ireplace($search_string,$replace,$status);
						}
						print (trim($custom->getCurrentStatus($final_result[$i]['insight_status'])) != "")?" - ".$status:'-'; ?>
				</td>
			    </tr>
			     <tr>
				<td><b>Current Owner </b><?php //print (trim($custom->getUserNameById($final_result[$i]['deligated_to'])) != "")?" - ".$custom->getUserNameById($final_result[$i]['deligated_to']):'-'; ?>
				<?php 
						$deligated = $custom->getUserNameById($final_result[$i]['deligated_to']);
						if(stristr($deligated, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$deligated = str_ireplace($search_string,$replace,$deligated);
						}
						print (trim($custom->getUserNameById($final_result[$i]['deligated_to'])) != "")?" - ".$deligated:'-'; ?>
				</td>
			    </tr>
			   <!--  <tr>
				<td><b>Product Area </b><?php //print (trim($final_result[$i]['product_area_id']) != "")?" - ".$final_result[$i]['product_area_id']:'-'; ?>
				<?php 
						$prodarea = $final_result[$i]['product_area_id'];
						if(stristr($prodarea, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$prodarea = str_ireplace($search_string,$replace,$prodarea);
						}
						print ($final_result[$i]['product_area_id'] != "")?" - ".$prodarea:'-'; ?>
				</td>
			    </tr>-->
			     <tr>
				<td><b>Market Segment</b><?php //print (trim($final_result[$i]['market_id']) != "")?" - ".$final_result[$i]['market_id']:'-'; ?>
				<?php 
						$marketid = $final_result[$i]['market_id'];
						if(stristr($marketid, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$marketid = str_ireplace($search_string,$replace,$marketid);
						}
						print ($final_result[$i]['market_id'] != "")?" - ".$marketid:'-'; ?>
				</td>
			    </tr>
				<tr>
				<td><b>Issue</b>
				<?php 	$issue_title = $final_result[$i]['issue_title'];
						if(stristr($issue_description, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$issue_title = str_ireplace($search_string,$replace,$issue_title);
						}
						print ($final_result[$i]['issue_title'] != "")?" - ".$issue_title:'-'; ?>
				</td>
			    </tr>
				
			</table>
		  </td>
		  
		  <td style="padding-right:15px !important; padding-left:8px !important; _position:relative; vertical-align:top; " <?php echo $bgcolor_right; ?>  valign="top" >
		  	<div style="position:absolute;  margin-top:0px;">
			<table border="0" cellspacing="0" cellpadding="0" width="410px" height="50%">
			    <tr>
			      <td colspan="2"><h3><?php if( $search_string != "Free Text Search" && trim($search_string) != "") {echo $search_string ;}?></h3></td>
			    </tr>
			    <tr>
			      <td  height="25px;" valign="bottom"><h4><?php if($legalqaFlag==1){  echo 'Feedback/ Question';
                             }else{ echo 'What did the customer/prospect say'; } ?>?</h4></td>
			      <td valign="top"><?php if($final_result[$i]['flag_mobile']=="Y"){?>	
					<img src="<?php echo IMAGE_URL?>/mobile_icon.png" height="25" alt="Added by Mobile device" title="Feedback added by mobile device" />		
					<?php }?></td>
			    </tr>
			    <tr>
			      <td colspan="2" style="height:120px"><p><?php //print (trim($final_result[$i]['insight_summary']) != "")?substr($final_result[$i]['insight_summary'],0,350).'...':'-'; ?>
			      <?php 
						$summary = $final_result[$i]['insight_summary'];
						if(stristr($summary, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$summary = str_ireplace($search_string,$replace,$summary);
						}
						if(strlen($summary) > 400) $str_summary = substr($summary,0,400)."..."; else {  $str_summary = $summary; }
						print (trim($final_result[$i]['insight_summary']) != "")? $str_summary:'-'; ?>
			      </p>
				<br />
			      </td>
			    </tr>
			  </table>
			  <div class="border-top">&nbsp;</div>
			  <table border="0" cellspacing="0" cellpadding="0" width="410px" height="50%">
			    
			    <tr>
			      <td colspan="2"><h4>Response to Feedback</h4></td>
			    </tr>
			    <tr>
			        <td style="height:95px" colspan="2"><p><?php //print (trim($final_result[$i]['do_action']) != "")?substr($final_result[$i]['do_action'],0,350).'...':'-'; ?>
				<?php 
						//$doaction = $final_result[$i]['do_action'];
						$doaction = $final_result[$i]['recent_reply'];
						if(stristr($doaction, $search_string)) {
							$replace = '<span class="yellow">'.$search_string.'</span>';
							$doaction = str_ireplace($search_string,$replace,$doaction);
						}
						if(strlen($doaction) > 400) $str_doaction = substr($doaction,0,400)."..."; else {  $str_doaction = $doaction; }
						print (trim($final_result[$i]['recent_reply']) != "")? $str_doaction:'-'; ?>
				</p></td>
			     </tr>
			     <tr>
				<td colspan="2">&nbsp;</td>
			     </tr>
			     </table>
			</div><div style="clear:both"></div>
			<div style="position: absolute; bottom:15px;  *bottom:25px; _bottom:5px;  margin-left:0px;">
			 <table border="0" cellspacing="0" cellpadding="0" >
			     <tr>			   
					<td align="left" style="white-space:nowrap"  width="340px">
						<?php 
							if($final_result[$i]['attachment_real_name']!="")
								print '<a id="attachment_namelink" href="javascript:open_attachment(\''.SITE_URL.'/products/downloadfile/'.$final_result[$i]['attachment_name'].'\');" >'.$final_result[$i]['attachment_real_name'].'</a>'; 
							else
								print "[No attachment]";	
						?>						
					</td>					
					<td align="left" style="white-space:nowrap">

						<a href="<?php print SITE_URL . '/products/records/' . $final_result[$i]['id']; ?>" title="Click Here">Click Here</a><br/>
						
					 </td>
			    </tr>
			  
			  </table>
			  </div>
		</td>
	</tr>
	</table>
	<hr style="color:#d10542;" />
	</div>
      <?php } 
      }else {
		echo "<div class='red' style='text-align:center; padding:15px 0px 0px 0px;' >No result found</div>";
      }
      ?>
   </td>
   </tr> 
	  <tr>
		   <td align="right" valign="bottom" colspan="2">
		   <div class="pagedisplay">
			  <?php if(isset($final_result) && count($final_result)>0) { ?>
				  <!-- Shows the page numbers -->
				  <?php echo $paginator->first();?>&nbsp;
				  <?php if(trim($this->Paginator->numbers(array('modulus'=>9)))!="") { ?>
					  <?php echo $paginator->prev('< '.__('prev', true), array(), null, array('class'=>'disabled'));?>&nbsp;
					  <?php echo $this->Paginator->numbers(); ?>&nbsp;					  
					  <?php echo $paginator->next(__('next', true).' >', array(), null, array('class'=>'disabled'));?>&nbsp;
					  <?php echo $paginator->last();?>
				<?php } ?>
				<!-- prints X of Y, where X is current page and Y is number of pages -->				
				<?php echo $this->Paginator->counter(array('format' => '<br />Page %page% of %pages%')); ?>	
			</div>
			<?php } ?>
			
	  </td>
	
	  </tr>

   </table>
  </div>
<input type="hidden" name="data[Product][created_from]" value="<?php echo $created_from;?>" >
<input type="hidden" name="data[Product][created_to]" value="<?php echo $created_to ;?>" >
<?php echo $form->end(); ?>
</div>