<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo WEBSITE_TITLE; ?></title>


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
<?php echo $javascript->link('customvalidate')?>
<?php echo $javascript->link('calendarview')?>
<?php echo $javascript->link('customvalidate')?>


</head>

<body>
<div>
        <!-- start header -->
        
          <div class="search" id="top_header_box">
		<?php 
		$current_user_name = $this->Session->read('current_admin_name');
		$current_user_id = $this->Session->read('current_admin_id');
		$current_user_role = $this->Session->read('current_admin_role');
		
		if (!isset($current_user_name) && !isset($current_user_id)) {
			?>
			<script language="javascript" type="text/javascript">
				parent.parent.GB_hide();
				parent.parent.parent.location.reload();
			</script>
			<?php
		}
		?>
					
       </div>
       <!-- end header -->