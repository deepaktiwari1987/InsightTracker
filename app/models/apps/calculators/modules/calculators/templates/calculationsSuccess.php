<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <meta name="ROBOTS" content="NOINDEX" />
        <title>Lexis&reg;Calculate</title>  
        <link href="/css/print.css" rel="stylesheet" type="text/css" />
        <link REL="SHORTCUT ICON" HREF="http://www.lexisnexis.co.uk/favicon.ico">
	<script>
			function readCookie(name) {
				var nameEQ = name + "=";
				var ca = document.cookie.split(';');
					for(var i=0;i < ca.length;i++) {
						var c = ca[i];
						while (c.charAt(0)==' ') c = c.substring(1,c.length);
						if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
					}
				return '';
			}	
		   function eraseCookie(name) {
				createCookie(name,"",-1);
		   }

	</script>
    </head><link href="/css/print.css" rel="stylesheet" type="text/css" />
    <body>
    	<div id="container">
        <div id="min790">
	    	<div id="title">
	    		<h1>
	          		<div style="font-size:24px; margin:0px 0; border:0px; border-left:none;border-right:none;"><img src="/images/logo.gif" height="50" width="202"></div>
	          	</h1>
	        	<!--<h1><font style="font-size:24px;">Lexis<sup style="font-size:10px;color:#6a5c51;"><img src="/images/reg.gif" style="padding-bottom:0px;*padding-bottom:7px;padding-top:1px;*padding-top:0px;" /></sup></font><font style="font-size:24px;">Calculate</font></h1> -->
				<h3>
				<script>
					var getData = readCookie('caseInfo');
					document.write(getData);	
				</script>
				</h3>
	        </div>
	        <div id="calculations">	
				<?php echo $sf_data->getRaw('calculationsOutput'); ?>  
	    	</div>
	    </div>
	</body>
</html>