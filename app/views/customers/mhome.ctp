<?php
/*
* File Name :  mhome.ctp
* Developer :  Gaurav Saini
* @author LexisNexis Development Team
* Cake Version : 1.3.4 
* @copyright Copyright (c) 2010, LexisNexis
* Functionality / Description : The purpose of this file is to display the home page of application on mobile device.
*/
?>
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
	<!--<div class="innercontainer">
        <div class="left-text_mb">
			 <p>Welcome to the iKnow Insight Tracker application !</p>
			<p>Building Customer Insight to drive marketing, product development, content, and innovation.</p>
			<p>Just click to submit customer feedback.</p>
			<p>The iKnow Insight Tracker Team</p> 
			<br/>
			<br/>
			<br/>
			<br/>-->
			<?php 
			 $current_user_name = $this->Session->read('current_user_name');
			 $current_user_id = $this->Session->read('current_user_id');

			  if(isset($current_user_name) && isset($current_user_id) && !empty($current_user_name)) { ?>
			 <form action="<?php print SITE_URL; ?>/customers/mhome" name="loginform" method="post">
			  <input type="hidden" name="HdnRedirect" value="Y"/>
  				<div class="buttonrow_mb" style="align:center;padding-right:3px !important;">
					<input name="submitProductInsight" id="submitProductInsight" type="submit" value="SUBMIT FEEDBACK" />
				</div>
			</form>
				<!--	<a href="<?php echo SITE_URL?>/products/mindex" style="cursor:pointer;"><img style="float:right" src="<?php echo IMAGE_URL?>/disable-buttonred.png"  style="cursor:pointer;"/></a>				
						-->
			  <?php }else{ ?>
  				<div class="buttonrow_mb" style="align:center;padding-right:3px !important;">
					<input name="submitProductInsight" id="submitProductInsight" type="submit" value="SUBMIT FEEDBACK" disabled />
				</div>

				<!-- <img style="float:right" src="<?php echo IMAGE_URL?>/disable-button.png" />
				  -->
			  <?php } ?>
			  
		<!--</div>        
        <div class="clear"></div>
    </div>-->
</div>