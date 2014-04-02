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
<?php echo $javascript->link('dropdowntabs')?>
<LINK REL="SHORTCUT ICON" HREF="<?php print SITE_URL; ?>/favicon.ico">
</head>

<body>
<div id="main">
        <!-- start header -->
				<?php 
					$current_admin_name = $this->Session->read('current_admin_name');
					$current_admin_id = $this->Session->read('current_admin_id');
					$current_admin_role = $this->Session->read('current_admin_role');
					$adminUrl = "admin";
					if($current_admin_role == ACCESS_EDIT_ROLE && isset($current_admin_id) && $current_admin_id>0) { 
						 $adminUrl = 'admin';
					}

				?>

        <div id="banner"> <a href="<?php echo SITE_URL?>/<?php print $adminUrl;?>"><img src="<?php echo IMAGE_URL?>/logo.png" alt="logo" class="logo" /></a>
          <div class="search" id="top_header_box">
									<?php
									$ptr = 1;
								  if($_GET['url'] == '/' ){ $ptr = 0;}
                  if (!isset($current_admin_name) && !isset($current_admin_id)) {
                      if ($_GET['url'] != '/' && $_GET['url'] != 'admin'  )
											{
												if($ptr == 0)
											 	{ @header('Location: ' . SITE_URL);}
												else
												{@header('Location: ' . SITE_URL.'/admin');}
											}
                  ?>
					<?php echo $javascript->link('validator')?>
					<?php if($_GET['url'] == '/' ){ ?>
                    <span>
                                <div class="searchbox">
								<form action="#" onsubmit="return setFormSubmit();">
								<table border="0">
									<tr>
										<td><div class="text">Please enter your user ID: </div></td>
										<td><input name="username" type="text" id="username"/></td>
										<td><div class="ok"><a href="#" id="setusername" onclick="setFormSubmit();"><img src="<?php echo IMAGE_URL?>/ok.jpg" alt="ok" align="absmiddle" /></a></div></td>
									</tr>
									<tr><td>&nbsp;</td>
										<td colspan="2">
											<div id="username_notvalid" class="notvalid_err">Username not recognised.</div>
										</td>
									</tr>
								</table>
								</form>
								</div>
                                
                                <div class="clear"></div>
                                <div id="username_notvalid" class="notvalid_err">Username not recognised.</div>
                                <div id="username_valid" class="validated_status">&nbsp;</div>
                        </span>
					<?php } ?>
                        <?php }
                              else{
                         ?>
						 										<?php echo $javascript->link('calenderload')?>
                                <div class="text_loggedin"><?php print "Username: " . $current_admin_name; ?>&nbsp;&nbsp;<a href="<?php echo SITE_URL ?>/customers/unsetadmin/<?php echo $ptr;?>">Logout</a></div> 
                                <div class="clear"></div>
                                <div id="username_notvalid" class="notvalid_err">&nbsp;</div>
                                <div id="username_valid" class="validated_status">&nbsp;</div>
																<?php if($current_admin_role == ACCESS_EDIT_ROLE) { ?>
																		<script type="text/javascript"> var current_admin_role = "<?php print ACCESS_EDIT_ROLE; ?>"; </script>
																<?php } else { ?>
																		<script type="text/javascript"> var current_admin_role = ""; </script>
																<?php } ?>																
                        <?php } ?>
          </div>
       </div>
       
    <?php if (isset($current_admin_name) && isset($current_admin_id)) { ?>   
			<div id="nav">
		
				<div id="colortab" class="ddcolortabs">
					<ul>
						<li ><a href="<?php echo SITE_URL;?>/editrecords/showlist/pilotgroups" title="User Management" class="firstnav"><span>User Management</span></a></li>
						<li><a href="#" title="Data Management" rel="dropmenu1_a"><span>Data Management</span></a></li>
					</ul>
				</div>
	
				<div id="dropmenu1_a" class="dropmenudiv_a">
					<a href="<?php echo SITE_URL?>/editrecords/showlist/competitornames/showall" title="Competitor Name">Competitor Name</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/contenttypes/showall" title="Content Type">Content Type</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/insighttypes/showall" title="Insight Type">Insight Type</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/firms/showall" title="Organisation">Organisation</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/insightabouts/showall" title="Insight Come About">Feedback Come About/Origin of Question</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/statusinsights/showall" title="Insight Status">Feedback Status</a>				
					<a href="<?php echo SITE_URL?>/editrecords/showlist/markets/showall" title="Market">Market Segment</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/practiceareas/showall" title="Practice Area">Practice Area</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/productfamilynames/showall" title="Product Family Name">Product Family Name</a>
					<a href="<?php echo SITE_URL?>/editrecords/showlist/productnames/showall" title="Product Name">Product Name</a>
					<!--<a href="<?php echo SITE_URL?>/editrecords/showlist/productareas/showall" title="Product Area">Product Area</a>-->
					<a href="<?php echo SITE_URL?>/editrecords/showlist/sellingobstacles/showall" title="Selling Obstacles">Selling Obstacles</a>
					
				</div>

		</div>
		<?php }else{ ?>
			<div id="colortab" class="ddcolortabs">
			</div>
		<?php } ?>		

		<script type="text/javascript">
			//SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
				tabdropdown.init("colortab", 3)
		</script>
       <!-- end header -->