<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; minimum-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<!--<meta name="viewport" content="width=320; initial-scale=0;maximum-scale=0; user-scalable=0;"/>-->
<title><?php echo WEBSITE_TITLE ?></title>

<?php 
	$MobileDevice = $this->Session->read('MobileDevice');
	$MobileVersion = $this->Session->read('MobileVersion'); 

	$MobileVersion = floatval($MobileVersion);

	echo $html->css('mobile_style');

	$OkBtnImage = "ok.jpg";
?>
<?php echo $html->css('reset')?>

<?php echo $javascript->link('prototype')?>
<?php echo $javascript->link('autocomplete')?>
<?php echo $javascript->link('scriptaculous')?>
<?php echo $javascript->link('general')?>
<?php echo $javascript->link('front')?>
<?php echo $javascript->link('validation')?>

<?php echo $javascript->link('customvalidate')?>

<LINK REL="SHORTCUT ICON" HREF="<?php print SITE_URL; ?>/favicon.ico">
<script>
mobile_check = '_mb';
</script>
</head>

<body>
<div id="main_mb">
        <!-- start header -->
        <div id="banner_mb">
	<div class="logobox_mb"><a href="<?php echo SITE_URL?>/"><img src="<?php echo IMAGE_URL?>/logo_mb.png" alt="logo" /></a></div>
          <div class="search_mb" id="top_header_box">
                  <?php
					
					$current_user_name = $this->Session->read('current_user_name');
					$current_user_id = $this->Session->read('current_user_id');
					$current_user_role = $this->Session->read('current_user_role');

		  $ptr = 1;
		  if($_GET['url'] == '/' ){ $ptr = 0;}

                  if (empty($current_user_name) && empty($current_user_id)) { 
											setcookie('CAKEPHP','', time()-1800);

                      if ($_GET['url'] != '/' && $_GET['url'] != 'admin' && $_GET['url'] == 'pages/display' )
											{ 
												if($_GET['url'] != 'pages/display')
												{
													if($ptr == 0){ 
														@header('Location: ' . SITE_URL);
													} else {
														@header('Location: ' . SITE_URL.'/');
													}
												}
											}
                  ?>
					<?php echo $javascript->link('validator')?>
					<?php //if($_GET['url'] == '/' ){ ?>
                    <span>
                                   <div class="clear"></div>
                                <div style = "color:#FF0000">
                                <?php
									if($errArr !='')
                                        {
                                                echo $errArr;
										}
										
                                ?></div>
                        </span>
					<?php //} ?>
                        <?php }
                              else{ 
                         ?>
                                <div class="text_mb"><?php print "Username: " . $current_user_name; ?>&nbsp;&nbsp;</div> 
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