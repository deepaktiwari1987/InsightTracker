/* @author sanchali bishnoi
  to check if add new from fields are empty or not
*/
objWin3 = null;
//document.write('<input type="hidden" name="HDNChildWindow" id="HDNChildWindow" value="0" >');

function idvalidate(id1,id2,id3)
{
	//alert("hello");

	var msg = "";
	
	$(id3).removeClassName('showElement');
	$(id3).addClassName('hideElement');

	
	if($F(id1) == '')
	{		msg += "enter value in field one\n";
			$(id1+'_err1').removeClassName('hideElement');
			$(id1+'_err1').addClassName('showElement');
			//$(id1+'_err1').setStyle({display:"block"});
			
	}
	else
	{
		$(id1+'_err1').removeClassName('showElement');
		$(id1+'_err1').addClassName('hideElement');
	
	}
	if($F(id2) == '')
		{
			msg += "enter value in field two\n";
				$(id2+'_err1').removeClassName('hideElement');
				$(id2+'_err1').addClassName('showElement');
			
		}
		else if($F(id2).length <6 || $F(id2).length > 20)
		{
			msg += "length of value must be between 6-20 in field two\n";
			$(id2+'_err1').removeClassName('showElement');
			$(id2+'_err1').addClassName('hideElement');
			$(id2+'_err2').removeClassName('hideElement');
			$(id2+'_err2').addClassName('showElement')
		}
		else
		{
			$(id2+'_err2').removeClassName('showElement');
			$(id2+'_err2').addClassName('hideElement');
		}
		if(msg.length>0) {
			return false;
		}
		else
		{
			//return setFormSubmit();
			return true;

		}
		
}

/**
*	Function Name: 	blank_specialfunc
*	Purpose:		Purpose of this function is to validate the form control (For single control only).
**/
function blankfunc1(id1, check1)
{
	
		$(check1+'_err1').removeClassName('showElement');
		$(check1+'_err1').addClassName('hideElement');

	if($F(id1) == '')
	{
			//alert('id1');
			$(id1+'_err1').removeClassName('hideElement');
			$(id1+'_err1').addClassName('showElement');
			//$(id1+'1_err').setStyle({display:"block"});
			return false;
	}
	else
	{
		$(id1+'_err1').removeClassName('showElement');
		$(id1+'_err1').addClassName('hideElement');
	
	}
	/*if(check1 == 'one')
	{
			return alpha_numeric(id1);
	}
	else if(check1 == 'two')
	{
		return alpha(id1);
	}
	else if(check1 == 'three')
	{
		return numeric(id1);
	}*/
	return true;
}

/**
*	Function Name: 	blank_specialfunc
*	Purpose:		Purpose of this function is to validate the form control (For single control only).
**/
function blank_specialfunc(id1, check1)
{	
	$(check1+'_err1').removeClassName('showElement');
	$(check1+'_err1').addClassName('hideElement');
	//var illegalChars = /\s/; // allow letters, numbers, and underscores	
	var iChars = "!@#$%^&*()+=-[]\';,./{}|\":<>?";
	if($F(id1) == '')
	{
			$(id1+'_err1').removeClassName('hideElement');
			$(id1+'_err1').addClassName('showElement');
			return false;
	}
	else
	{
		/*var iChars = "!@#$%^&*()+=-[]\';,./{}|\":<>?";   
		for (var i = 0; i < $F(id1).length; i++) 
		{   
			if (iChars.indexOf($F(id1).charAt(i)) != -1)    
			{   	
				$(id1+'_err2').removeClassName('hideElement');
				$(id1+'_err2').addClassName('showElement');
				return false;
			}  
		}*/
	}
	//return true;
	
}

/**
*	Function Name: 	blankfunc2
*	Purpose:		Purpose of this function is to validate the form control (For two controls only).
**/
function blankfunc2(id1,id2,check2,id3)
{
	$(check2+'_err1').removeClassName('showElement');
	$(check2+'_err1').addClassName('hideElement');
	var msg = "";
	if($F(id1) == '')
	{
		msg += "enter value in field one\n";
			$(id1+'_err1').removeClassName('hideElement');
			$(id1+'_err1').addClassName('showElement');
			//alert('enter value');
		//	return false;
	}
	else
	{
		$(id1+'_err1').removeClassName('showElement');
		$(id1+'_err1').addClassName('hideElement');
		
	}
	
	if($F(id2) == '')
	{
				msg += "enter value in field two\n";
				$(id2+'_err1').removeClassName('hideElement');
				$(id2+'_err1').addClassName('showElement');
	}
	else
	{
			$(id2+'_err1').removeClassName('showElement');
			$(id2+'_err1').addClassName('hideElement');
	}
	if($F(id3) == '')
	{
				msg += "enter value in field two\n";
				$(id3+'_err1').removeClassName('hideElement');
				$(id3+'_err1').addClassName('showElement');
	}
	else
	{
			$(id3+'_err1').removeClassName('showElement');
			$(id3+'_err1').addClassName('hideElement');
	}	
		if(msg.length>0) {
			return false;
		}
		
		return true;
}

function blank_func2(id1, id2, check2)
{
	//$(check2+'_err1').removeClassName('showElement');
	//$(check2+'_err1').addClassName('hideElement');
	var msg = "";
	if($F(id1) == '')
	{
		msg += "enter value in field one\n";
			$(id1+'_err1').removeClassName('hideElement');
			$(id1+'_err1').addClassName('showElement');
			//alert('enter value');
		//	return false;
	}
	else
	{
		$(id1+'_err1').removeClassName('showElement');
		$(id1+'_err1').addClassName('hideElement');
		
	}
	
	if($F(id2) == '')
	{
				msg += "enter value in field two\n";
				$(id2+'_err1').removeClassName('hideElement');
				$(id2+'_err1').addClassName('showElement');
	}
	else
	{
			$(id2+'_err1').removeClassName('showElement');
			$(id2+'_err1').addClassName('hideElement');
	}
		if(msg.length>0) {
			return false;
		}
		
		return true;
}

/**
*	Function Name: 	blankfunc3
*	Purpose:		Purpose of this function is to validate the form controls (For three controls only).
**/
function blankfunc3(id1,id2,id3,check3)
{
	/*$(id1+'_err1').removeClassName('showElement');
	$(id1+'_err2').removeClassName('showElement');
	$(id2+'_err1').removeClassName('showElement');
	$(id2+'_err2').removeClassName('showElement');
	$(id3+'_err1').removeClassName('showElement');
	$(id3+'_err2').removeClassName('showElement');*/
		$(check3+'_err1').removeClassName('showElement');
	  $(check3+'_err1').addClassName('hideElement');
	var msg = "";
	if($F(id1) == '')
	{
		msg += "enter value in field one\n";
			$(id1+'_err1').removeClassName('hideElement');
			$(id1+'_err1').addClassName('showElement');
			//alert('enter value in parent id');
			
			//return false;
	}
	else
		{
			$(id1+'_err1').removeClassName('showElement');
			$(id1+'_err1').addClassName('hideElement');
		}
	if($F(id2) == '')
		{
			msg += "enter value in field two\n";
		    $(id2+'_err1').removeClassName('hideElement');
				$(id2+'_err1').addClassName('showElement');
			//alert('enter value in account number');
			//return false;
		}
		else
		{
			$(id2+'_err1').removeClassName('showElement');
			$(id2+'_err1').addClassName('hideElement');
		}
	if($F(id3) == '')
		{
			msg += "enter value in field three\n";
			  $(id3+'_err1').removeClassName('hideElement');
				$(id3+'_err1').addClassName('showElement');
			//alert('enter value in firm name');
			//return false;
		}
		else
		{
			$(id3+'_err1').removeClassName('showElement');
			$(id3+'_err1').addClassName('hideElement');
		}
		
		/*if(numeric(id1) == true && alpha_numeric(id2) == true && alpha_numeric(id3) == true)
		{
			return true;
		}
		else
		{
			return false;
		}*/
		if(msg.length>0) {
			return false;
		}
		
		return true;
}

/**
*	Function Name: 	userAddNewformValidate
*	Purpose:		Purpose of this function is to validate the form control.
**/
function userAddNewformValidate(id1, id2, id3, id4, id5, id6, check2)
{
			$(check2+'_err1').removeClassName('showElement');
			$(check2+'_err1').addClassName('hideElement');
			
			var illegalChars = /\s/; // allow letters, numbers, and underscores
			var emailRegEx = /^[A-Z0-9'._%&+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
			var iChars = "!@#$%^&*()+=-[]\';,./{}|\":<>?";   
			
			var msg = "";
			if($F(id1) == '') {/* check for user name validations */
		
				msg += "enter value in field one\n"; $(id1+'_err1').removeClassName('hideElement'); $(id1+'_err1').addClassName('showElement');
				$(id1+'_err2').removeClassName('showElement'); $(id1+'_err2').addClassName('hideElement');
			}	else {
				if(illegalChars.test($F(id1))) {
					msg += "enter value in field one\n"; $(id1+'_err1').removeClassName('showElement'); $(id1+'_err1').addClassName('hideElement');
					$(id1+'_err2').addClassName('showElement'); $(id1+'_err2').removeClassName('hideElement');
					
				} else {
					$(id1+'_err1').removeClassName('showElement'); $(id1+'_err1').addClassName('hideElement');
				}
			}
		
		
			if($F(id3) == '') {/* check for email address validations */
		
				msg += "enter value in field one\n"; $(id3+'_err1').removeClassName('hideElement'); $(id3+'_err1').addClassName('showElement');
				$(id3+'_err2').removeClassName('showElement'); $(id3+'_err2').addClassName('hideElement');
			}	
			else if($F(id3).search(emailRegEx) == -1) { 
					msg += "enter value in field one\n"; $(id3+'_err1').removeClassName('showElement'); $(id3+'_err1').addClassName('hideElement');
					$(id3+'_err2').addClassName('showElement'); $(id3+'_err2').removeClassName('hideElement');
					
			} 
			else { 
				$(id3+'_err1').removeClassName('showElement'); $(id3+'_err1').addClassName('hideElement');
				$(id3+'_err2').removeClassName('showElement'); $(id3+'_err2').addClassName('hideElement');
			}
			
			if($F(id4) != '') { 
				if($F(id4).search(";") != -1)
				{					
					var splitCCEmailAddress = $F(id4).split(";");
					for(var x = 0; x < splitCCEmailAddress.length; x++)		
					{
						if (trim(splitCCEmailAddress[x])!='' && splitCCEmailAddress[x].search(emailRegEx) == -1){
							msg += "enter value in field one\n"; $(id4+'_err1').addClassName('showElement'); $(id4+'_err1').removeClassName('hideElement');
						}
					}					
				}
				else if ($F(id4).search(emailRegEx) == -1){
					msg += "enter value in field one\n"; $(id4+'_err1').addClassName('showElement'); $(id4+'_err1').removeClassName('hideElement');
				}
			}
			
			if(trim($F(id5)) == '') {/* check for First name validations */
		
				msg += "enter value in field one\n"; $(id5+'_err1').removeClassName('hideElement'); $(id5+'_err1').addClassName('showElement');
				$(id5+'_err2').removeClassName('showElement'); $(id5+'_err2').addClassName('hideElement');
			}	/*else {
				
					for (var i = 0; i < $F(id5).length; i++) 
					{   
						if (iChars.indexOf($F(id5).charAt(i)) != -1)    
						{   	
							$(id5+'_err1').removeClassName('showElement'); $(id5+'_err1').addClassName('hideElement');
							$(id5+'_err2').removeClassName('hideElement');
							$(id5+'_err2').addClassName('showElement');
							return false;
						}  
					}
					
					$(id5+'_err1').removeClassName('showElement'); $(id5+'_err1').addClassName('hideElement');	
					$(id5+'_err2').removeClassName('showElement'); $(id5+'_err2').addClassName('hideElement');	
					*/				
				/*if(illegalChars.test($F(id5))) {
					msg += "enter value in field one\n"; $(id5+'_err1').removeClassName('showElement'); $(id5+'_err1').addClassName('hideElement');
					$(id5+'_err2').addClassName('showElement'); $(id5+'_err2').removeClassName('hideElement');
					
				} else {
					$(id5+'_err1').removeClassName('showElement'); $(id5+'_err1').addClassName('hideElement');
				}
			}*/
			
			/*if($F(id6) == '') {
		
				msg += "enter value in field one\n"; $(id6+'_err1').removeClassName('hideElement'); $(id6+'_err1').addClassName('showElement');
				$(id6+'_err2').removeClassName('showElement'); $(id6+'_err2').addClassName('hideElement');
			}	else {				
					for (var i = 0; i < $F(id6).length; i++) 
					{   
						if (iChars.indexOf($F(id6).charAt(i)) != -1)    
						{   	
							$(id6+'_err1').removeClassName('showElement'); $(id6+'_err1').addClassName('hideElement');
							$(id6+'_err2').removeClassName('hideElement');
							$(id6+'_err2').addClassName('showElement');
							return false;
						}  
					}
					$(id6+'_err1').removeClassName('showElement'); $(id6+'_err1').addClassName('hideElement');	
					$(id6+'_err2').removeClassName('showElement'); $(id6+'_err2').addClassName('hideElement');
				
			}*/
			
			if($F(id2) == '' && !$(id2).hasAttribute('disabled'))
			{
				 if($F('PilotgroupRole') == "A") {
						msg += "enter value in field two\n";
						$('passwordErrorMsg').innerHTML = 'Enter Password'; $('passwordErrorMsg').removeClassName('hideElement'); $('passwordErrorMsg').addClassName('showElement');
				 }
			}
			
			else if(!$(id2).hasAttribute('disabled')) {
				var pass = $F(id2);
				var pass2 = $F("Confirm"+id2);
				if(pass2 == ''){$('passwordErrorMsg').innerHTML = 'Enter Confirm Password'; $('passwordErrorMsg').addClassName('showElement'); msg += "enter value in field two\n"; return false;}
		
				if(pass.length < 6 || pass.length > 20) {
					msg += "password length should be beetween 6 - 20 characters\n";
					$('passwordErrorMsg').innerHTML = 'Password length should be 6-20 characters'; 	$('passwordErrorMsg').removeClassName('hideElement'); $('passwordErrorMsg').addClassName('showElement'); 
				}else if(illegalChars.test($F(id2))) {
					msg += "enter value in field one\n";
					$('passwordErrorMsg').innerHTML = 'Password cannot contain space'; 	$('passwordErrorMsg').removeClassName('hideElement'); $('passwordErrorMsg').addClassName('showElement'); 			
				}else if(pass != pass2){
					msg += "enter value in field one\n";
					$('passwordErrorMsg').innerHTML = 'Password and confirm password do not match'; 	$('passwordErrorMsg').removeClassName('hideElement'); $('passwordErrorMsg').addClassName('showElement'); 			
				} else {
					$('passwordErrorMsg').removeClassName('showElement');
					$('passwordErrorMsg').addClassName('hideElement');
				}
			}			
			else
			{
					$('passwordErrorMsg').removeClassName('showElement');
					$('passwordErrorMsg').addClassName('hideElement');
			}
		
		if(msg.length>0) {
			return false;
		}
		
		return true;
}
/* Show password boxes on add edit user */
function changeShowPass(obj, action) {
	//$('showPassBoxLink'));
	if(obj.value == 'A') {
		if(action != 'edit'){
			$('PilotgroupPassword').enable();
			$('ConfirmPilotgroupPassword').enable();
		}else{
			if($('showPassBoxLink') != null)
				$('showPassBoxLink').addClassName('showElement');
			else{
				$('PilotgroupPassword').enable();
				$('ConfirmPilotgroupPassword').enable();
			}
		}
	}else{
		$('PilotgroupPassword').disable();
		$('ConfirmPilotgroupPassword').disable();		
		if(action == 'edit'){$('showPassBoxLink').removeClassName('showElement'); $('showPassBoxLink').addClassName('hideElement');}		
	}
}
function showPassBox(selfLinkId) {
		$(selfLinkId).removeClassName('showElement');
		$(selfLinkId).addClassName('hideElement');
		$('PilotgroupPassword').enable();
		$('ConfirmPilotgroupPassword').enable();
}
function initial(){
	if (document.getElementsByTagName)
	{
		var as=document.getElementsByTagName("a");
		for (i=0;i<as.length;i++)
		{
			var a=as[i];
			if (a.getAttribute("href") && a.getAttribute("rel")=="external") a.target="_blank";
		}
	}
	var ds=document.getElementsByTagName("input");
	for (i=0;i<ds.length;i++)
	{
		if (ds[i].className=="date")
		{
			ds[i].onclick=clickDate;
			ds[i].onchange=changeDate;
		}
		//alert(ds[i]);
		if (ds[i].className=="noSpace") ds[i].onkeypress=restrictSpace;
	}
}

function restrictSpace(event)
{
	if (window.event) k=window.event.keyCode;
	else if (event) k=event.which;
	else return true;
	kC=String.fromCharCode(k);
	//alert(k);return false;
	if ((k==32)) return false;
	else if ((k==13)) return true;
	else if ((("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ").indexOf(kC)>-1)) return true;
	else return true;
}


/**
*	Function Name: 	setOwnershipForInsight
*	Purpose:		Purpose of this function is to set the Ownership values if ownership of any issue has been taken by any SME.
**/
function setOwnershipForInsight()
{
	var delegated_user_length = document.getElementById("ProductDeligatedTo").length;
	var insight_status_length = document.getElementById("ProductInsightStatus").length;
	document.getElementById("HdnDelegatedTo").value=current_user_id;
	document.getElementById("HdnOwnershipTaken").value="yes";
	
	// set Delegated to
	for(var i=0; i < delegated_user_length; i++)
	{
		var dropdownvalue = document.getElementById("ProductDeligatedTo").options[i].value;
		if(current_user_id == dropdownvalue)
		{
			document.getElementById("ProductDeligatedTo").selectedIndex=i;
			break;
		}
	}
	
	// set Insight status as Ownership Taken
	for(var i=0; i < insight_status_length; i++)
	{
		var statusnvalue = document.getElementById("ProductInsightStatus").options[i].text;
		if(statusnvalue == 'Ownership Taken')
		{
			document.getElementById("ProductInsightStatus").selectedIndex=i;
			document.getElementById("HdnInsightStatus").value = document.getElementById("ProductInsightStatus").value;
			break;
		}
	}
	
	//document.getElementById("ProductDeligatedTo").disabled=false;
	//document.getElementById("ProductInsightStatus").disabled= false;
	
	//	Change Button text as Ownership Taken.
	document.getElementById("BtnOwnership").value = "Ownership Taken";
	//document.getElementById("ProductOwnershipTaken").value = "yes";
}

/**
*	Function Name: 	openPopupWindow
*	Purpose:		Purpose of this function is to open a child popup window. Additionally it will check that wheather the child window already
					opened or not.
**/
function openPopupWindow(poplink, insight_id, loginUserId){
	
		if (objWin3 && !objWin3.closed) {
				alert('Child window already open.  Attempting focus...');
			try {
				objWin3.focus();
			}
			catch(e) {}
			
			return false;
		}

		var replyURL = poplink+"addreply/"+insight_id+"/"+loginUserId+"/0";	
		
		window.objWin3 = window.open(replyURL, 'childwindow', 'left=400,top=300,width=400,height=300,toolbar=no,scrollbars=no,location=no,resizable=no');
		
		//document.getElementById("HDNChildWindow").value = "1";
		//alert(window.document.getElementById("HDNChildWindow").value);
}


window.onload = initial;
