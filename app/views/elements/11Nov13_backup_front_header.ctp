<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo WEBSITE_TITLE ?></title>
<script language="javascript" type="text/javascript">
var GB_ROOT_DIR = "<?php echo JAVASCRIPT_URL?>/greybox/";
</script>
<?php echo $javascript->link('/js/greybox/AJS.js')?>
<?php echo $javascript->link('/js/greybox/AJS_fx.js')?>
<?php echo $javascript->link('/js/greybox/gb_scripts.js')?>
<?php echo $html->css('gb_styles.css')?>

<?php echo $html->css('style')?>
<?php echo $html->css('reset')?>
<?php echo $html->css('calendarview')?>
<?php echo $javascript->link('prototype')?>
<?php echo $javascript->link('autocomplete')?>
<?php echo $javascript->link('scriptaculous')?>
<?php echo $javascript->link('general')?>
<?php echo $javascript->link('front')?>
<?php echo $javascript->link('validation')?>
<?php echo $javascript->link('calendarview')?>
<?php echo $javascript->link('customvalidate')?>

<LINK REL="SHORTCUT ICON" HREF="<?php print SITE_URL; ?>/favicon.ico">
</head>

<body>
<div id="main">
<input type="hidden" name="hdnImageURL" id="hdnImageURL" value="<?php echo IMAGE_URL?>"/>
        <!-- start header -->
        <div id="banner">
	<div class="logobox"><a href="<?php echo SITE_URL?>/"><img src="<?php echo IMAGE_URL?>/logo.png" alt="logo" class="logo" /></a></div>
          <div class="search" id="top_header_box">
                  <?php
 
                  $current_user_name = $this->Session->read('current_user_name');
                  $current_user_id = $this->Session->read('current_user_id');
				  $current_user_role = $this->Session->read('current_user_role');

									$ptr = 1;
								  if($_GET['url'] == '/' ){ $ptr = 0;}
                 if (empty($current_user_name) && empty($current_user_id)) { 
											setcookie('CAKEPHP','', time()-1800);
                      if ($_GET['url'] != '/' && $_GET['url'] != 'admin'  )
											{
												if($ptr == 0){ 
													@header('Location: ' . SITE_URL);
												} else {
											 		@header('Location: ' . SITE_URL.'/');
												}
											}
                  ?>
					<?php echo $javascript->link('validator')?>
					<?php if($_GET['url'] == '/' ){ ?>
                    <span>
                                   <div class="clear"></div>
                                <div style = "color:#FF0000">
                                <?php
									if($errArr !='')
                                        {
                                                echo $errArr;
										}
										
                                ?></div>
                                <div id="username_valid" class="validated_status">&nbsp;</div>
                                
                        </span>
					<?php } ?>
                        <?php }
                              else{
                         ?>
						 										<?php echo $javascript->link('calenderload')?>
                                <div class="text_loggedin"><?php print "Username: " . $current_user_name; ?>&nbsp;&nbsp;<!--<a href="<?php echo SITE_URL ?>/customers/unsetuser/<?php echo $ptr;?>">Logout</a>--></div> 
                                <div class="clear"></div>
                                <div id="username_notvalid" class="notvalid_err">&nbsp;</div>
                                <div id="username_valid" class="validated_status">&nbsp;</div>
								<script type="text/javascript"> 
									var current_user_id = "<?php print $this->Session->read('current_user_id'); ?>"; 
									var current_user_emailaddress = "<?php print $this->Session->read('current_user_emailaddress'); ?>"; 
								</script>
																<?php if($current_user_role == ACCESS_EDIT_ROLE) { ?>
																		<script type="text/javascript"> var current_user_role = "<?php print ACCESS_EDIT_ROLE; ?>"; </script>
																<?php } else if($current_user_role == SUBJECT_MATTER_EXPERT) { ?>
																		<script type="text/javascript"> var current_user_role = "<?php print SUBJECT_MATTER_EXPERT; ?>"; </script>
																<?php } else { ?>
																		<script type="text/javascript"> var current_user_role = ""; </script>
																<?php } ?>																
                        <?php } ?>
          </div>
       </div>
       <!-- end header -->