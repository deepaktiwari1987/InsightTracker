<?php
	if($this->Session->check('errArr')) {
		$err = $this->Session->read('errArr');
		if(is_array($err)) {
			print implode('<br />', $err);
		}
		$this->Session->delete('errArr');
	}
?>
<div id="textcontainer">
	<div class="innercontainer">
        <div class="left-text">
			<p>Welcome to the iKnow Insight Tracker !</p>
			<p>Building Customer Insight to drive marketing, product development, content, and innovation.</p>
			<p>Just click to submit customer feedback.</p>
			<p>The iKnow Insight Tracker Team</p>
			<!-- <p>Welcome to the Insight Tracker ! </p>
			<p>The new one-stop-shop for capturing insightful information about our markets, customers, competitors and products.  The information we want to capture should be insightful and preferably actionable as the end game is to support the development of compelling value propositions, to help identify new business opportunities and resolve customer pain-points.</p>
			<p>Just click to add new insight or retrieve existing insight.</p>
			<p>The iKnow Team</p> -->
		</div>        
        <div class="right-nav"> 
			  <?php 
			  $current_user_name = $this->Session->read('current_user_name');
			  $current_user_id = $this->Session->read('current_user_id');		 
			  if (isset($current_user_name) && isset($current_user_id) && !empty($current_user_name)) { ?>
				  <a href="<?php echo SITE_URL?>/products/index"><img src="<?php echo IMAGE_URL?>/add_insight.png" /></a>
				  <a href="<?php echo SITE_URL?>/products/search"><img src="<?php echo IMAGE_URL?>/search_insight.png" /></a>
				  <!--<a href="#"><img src="<?php //echo IMAGE_URL?>/search_insight.png" /></a> -->
			  <?php }else{ ?>
				  <a><img src="<?php echo IMAGE_URL?>/add_insight-d.png" /></a>
				  <a><img src="<?php echo IMAGE_URL?>/search_insight-d.png" /></a>
			  <?php } ?>
				
			  
			
				
        </div>
        <div class="clear"></div>
    </div>
</div>