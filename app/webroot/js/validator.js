// Validates username.

function validateUserName( uname, timestamp ) {
	new Ajax.Request('customers/validateuser/' + uname + '/' + timestamp,{method:'get', onSuccess: function(transport){
		var data = transport.responseText || "no response text";
		  if(trim(data) == trim(uname)) {
				//$('username_notvalid').setStyle({display: "none"});
				//$('username_valid').setStyle({display: "block"});
				$('setusername').href = 'customers/setuser/' + uname;
				window.location = 'customers/setuser/' + uname;
				return;
		  }else {
				$('setusername').href = '#';
				$('username_valid').setStyle({display: "none"});
				$('username_notvalid').setStyle({display: "block"});
		  }
	  }, onFailure: function(){ alert('Something went wrong...') }
	});
}
/*
// Document load and click event on ok button.
document.observe("dom:loaded", function() {
	Event.observe('setusername', 'click', function(event) {
      var uname = trim($('username').getValue());
      if (uname == "") {
            alert("Please enter your user ID");
            $('username').focus(); return false;
      }
      else{
		  validateUserName(uname);
      }
   });
});*/
function setFormSubmit(timestamp)
{
      var uname = trim($('username').getValue());
      if (uname == "") {
            alert("Please enter your user ID");
            $('username').focus(); return false;
      }
      else{
	   validateUserName(uname,timestamp);
      }
	  return false;
}




