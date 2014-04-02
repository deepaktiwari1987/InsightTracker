<div id="textcontainer" class = "hr-row" >
  
  <div id="content">
    <h2 align="center" style="padding-top:50px;">Login</h2>
	  <p  style="text-align:center;padding:15px; font-family:Calibri, arial; font-size:14px; line-height:18px; color:#333333;">Registered Users can log-in here:</p>
  </div>  
  <div  class="pad-bot">
  <?php echo $form->create('Customer', array('action'=>'','id'=>'customerLoginForm','name'=>'customerLoginForm','onSubmit'=>'return idvalidate("PilotgroupName","PilotgroupPassword","invalid_user")'));?>
    <div class="form" id="login" >
			<div id="invalid_user" class="<?php echo $printmsg; ?> errormsg " align = "center">Invalid user id or password</div>
    
      <ul>
        <li>
        <label>User Id</label>
          <?php echo $form->input('Pilotgroup.name',array('label'=>false,'class'=>'textbox','maxlength'=>'75','div'=>false));?>
          <span id="PilotgroupName_err1" class="hideElement">Please enter user id</span>
				</li>
        <li>
          <label>Password</label>
          <?php echo $form->input('Pilotgroup.password',array('label'=>false, 'type' => 'password', 'class'=>'textbox password','maxlength'=>'20','div'=>false,'value'=>''));?>
          <span id="PilotgroupPassword_err1" class="hideElement">Please enter password</span>
					<span id="PilotgroupPassword_err2" class="hideElement">Password length must be 6-20 characters</span>
        </li>
        <li style="text-align:center"><input type="submit" name="login" value="Submit" class="btn"  id="login_btn" /></li>
      </ul>
	<?php echo $form->end(); ?>
	</div>
</div>    
  
