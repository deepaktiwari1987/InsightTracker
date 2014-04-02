<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="ROBOTS" content="NOINDEX" />
        <title>Lexis&reg;Calculate</title>
        <script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="/js/jquery.form.js"></script>
        <script type="text/javascript" src="/js/jquery.validate.js"></script>
        <script type="text/javascript" src="/js/jquery.validate.additional.methods.js"></script>
        <script type="text/javascript" src="/js/jquery.modal.js"></script>
        <script type="text/javascript" src="/js/ui.core.js"></script>
        <script type="text/javascript" src="/js/ui.datepicker.js"></script>
        <script type="text/javascript" src="/js/jquery.effects.core.js"></script>
        <script type="text/javascript" src="/js/jquery.effects.slide.js"></script> 
        <script type="text/javascript" src="/js/jquery.formatCurrency-1.3.0.min.js"></script>
        <script type="text/javascript" src="/js/lexisCalculate.js"></script> 
        <link href="/css/styles.css" rel="stylesheet" type="text/css" />
        <link href="/css/calculators.css" rel="stylesheet" type="text/css" />
        <link href="/css/jquery-modal.css" rel="stylesheet" type="text/css" />
        <link href="/css/jquery-datepicker.css" rel="stylesheet" type="text/css" />
        <link href="/css/jquery-ui.theme.css" rel="stylesheet" type="text/css" />
        <link href="/css/jquery-ui-1.7.3.custom.css" rel="stylesheet" type="text/css">
        <link REL="SHORTCUT ICON" HREF="http://www.lexisnexis.co.uk/favicon.ico" />
		<script type="text/javascript">
			function blockField(flage){
			if(flage == 'set'){
				if(document.getElementById('claimant').value != ''){
					//document.getElementById('claimant').disabled = true;
				}
				if(document.getElementById('defendant').value != ''){
					//document.getElementById('defendant').disabled = true;
				}
				if(document.getElementById('ref').value != ''){	
					//document.getElementById('ref').disabled = true;
				}
				//document.getElementById('clsButton').disabled = true;	
				updateClaimantDefendant();
			  }
			if(flage == 'edit')
			{
			 	document.getElementById('ref').disabled = false;
				document.getElementById('defendant').disabled = false;
				document.getElementById('claimant').disabled = false;
			}
			if(flage == 'clr'){
				$.get("http://" + document.location.hostname + "/index.php/calculators/deleteCalculation" );
				$('#calculator-results-1').remove();
				$('#calculator-results-2').remove();
				$('#calculator-results-3').remove();
				$('#calculator-results-4').remove();
				$('#calculator-results-5').remove();
				$('#calculator-results-6').remove();
				$('#calculator-results-7').remove();
				$('#calculator-results-8').remove();
				$('#calculator-results-9').remove();
				cancelCalculator();
			}
			if(flage == 'cls'){
			 	document.getElementById('ref').disabled = false;
				document.getElementById('defendant').disabled = false;
				document.getElementById('claimant').disabled = false;
			
				$('#calculators-claimant-defendant').html("");
			/*	
				$('#calculator-results-1').remove();
				$('#calculator-results-2').remove();
				$('#calculator-results-3').remove();
				$('#calculator-results-4').remove();
				$('#calculator-results-5').remove();
				$('#calculator-results-6').remove();
				$('#calculator-results-7').remove();
				$('#calculator-results-8').remove();
				$('#calculator-results-9').remove();
				*/
				// load any exsiting calculations
     			deleteCookie('caseInfo',1,4);
				createCookie('caseInfo','',1);
				createCookie('csi','',1);
				
				//for(var k=1;k <=8;k++){
				//$.get("http://" + document.location.hostname + "/index.php/calculators/deleteCalculation" );
				//}
				
				// $.getJSON(jsonUrl, displayResults);
				 // resize
				 
				 //resizeWindow();
				 
				 // focus
				 
				 window.focus();
				
			 }	
			}
	function deleteCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()-(days*24*60*60*1000));
			var expires = "; expires="-date.toGMTString();
			
		}
	else  var expires = "";
		document.cookie = name+"="+expires+"; path=/";
}
	</script>
    </head>
    <body>
    	<div id="container">
        <div id="min790">
	  	      <div id="calculators-title">
	          	<h1>
	          		<div style="font-size:24px; margin:0px 0; border:0px; border-left:none;border-right:none;"><img src="/images/logo.gif" height="50" width="202"></div>
	          	</h1>
	          </div>
	          <!--<div id="calculators-title">
	          	<h1>
	          		<div style="font-size:24px; margin:3px 0; border:2px solid 	#D8D8B2; border-left:none;border-right:none;">Lexis<sup style="font-size:10px;color:#6a5c51;"><img src="/images/reg.gif" style="padding-bottom:0px;*padding-bottom:7px;padding-top:1px;*padding-top:0px;" /></sup></font>Calculate</div>
	          	</h1>
	          </div>-->
	          <div id="calculators-menu">
	            <ul>
	                <li style="backgroun-color: #f5f5eb; height: 110px;">
	                   <form id="claimant-defendant" onSubmit="return false;">
	                       <div style="height: 26px; margin-top: 4px;">
	                           <div class="width-100"><span>Claimant</span></div><div class="width-160"><input type="text" id="claimant" /></div><div class="width-100" style="text-align:right;margin-top:-7px;"><a style="font-weight:normal;" href="javascript:void(0);" onclick="showHelp(0)">Help</a>&nbsp;</div>
	                       </div>
	                       <div style="height: 26px;">
	                           <div class="width-100"><span>Defendant</span></div><div class="width-160"><input type="text" id="defendant" /></div>
	                       </div>
	                      <div style="height: 26px;">
	                           <div class="width-100"><span>Case Ref</span></div><div class="width-160"><input id="ref" type="text"/></div><div class="width-100"></div>
	                      </div>
						  
						  <div style="height: 26px;">
						  <div class="width-90"><span>&nbsp;</span></div>
						   <!--<div class="width-55"><input type="button" class="button secondary"  value="Edit" onclick="blockField('edit');"/></div>-->
						   <div class="width-65"><input type="reset" id="clsButton" class="button secondary" value="Clear" onclick="blockField('cls');" /></div>
						   <div class="width-45"><input type="button" class="button"  value="Set" onclick="blockField('set');"/></div>
						  </div>            
						  <div id='helpDiv' style='z-index:1000; position:absolute;top:40px;left:368px;display:none;'>
						   <input type="image" onclick="hidHelp()" id="cancel" name="cancel" src="/images/close.png" style="z-index:1001; position:absolute;top:4px;left:405px;">
						   <table  width='425px' style='background:#F5F5EB; border:#CCCCCC 1px solid;' cellpadding='10' cellspacing=10>
						     <tr><td id='helpContent' style='padding-right:23px;line-height:1.5em;'></td></tr>
						   </table>
						  </div>
		
					   </form>
	                </li>
	            	<li class="border-none" style="background: #F5F5EB url(http://www.lexisnexis.com/uk/lexispsl/img/TTPodBkg.gif) repeat-x; display: none;">
		            	<div id="calculator-container">
			            <h2><span></span></h2>
							<form id="calculator" name="calculator" action="javascript:calculate();" onsubmit="return false;">
							  <input type="hidden" name="hidNumPayments" id="hidNumPayments" value="0">
							  <input type="hidden" id="calculators_id" name="calculators[id]" value="" />
							  <div id="calculator-form-values"></div>
								<div class="calculator-buttons"><input type="submit" class="button" value="Submit" name="submit" id="submit"/><input type="reset" class="button secondary" value="Clear" name="clear" id="clear" /><input type="button" class="button secondary" value="Cancel" name="cancel" id="cancel" onclick="cancelCalculator()"/></div>
							</form>
		          		</div>
	            	</li>
<?php foreach ($calculator_menu_list as $id => $name):
		if($id == 7){
			$name = 'Past periodic loss';
		} ?>
							<li id="calculator-menu-li-<?php echo $id; ?>"><a href="javascript:void(0);" onclick="return addCalculator(<?php echo $id ?>);"><?php echo $name ?> calculator</a></li>
<?php endforeach; ?>               
	        	</ul>     
	       	</div>
	       	<div id="calculators-export">
				<a href="/index.php/calculators/calculations/format/doc" alt="Save" class="right"><img src="/images/save.png" /></a><a href="/index.php/calculators/calculations/format/doc" alt="Save" class="right">Save to word</a>
		        <a href="/index.php/calculators/calculations/format/print" alt="Print" class="right" target="_new"><img src="/images/print.png" /></a><a href="/index.php/calculators/calculations/format/print" alt="Save" class="right" target="_new">Print</a>
	        </div>
	        <div id="calculators-results">
	        	<h2 id="calculators-results-title">
	        		<table width="98%" cellpadding="0" cellspacing="0" align="center">
	        			<tr>
	        				<td>Results:</td>
	        				<td style="text-align:right" class="clearCalculations" >
	        					[<a href="javascript:void(0)" onclick="blockField('clr')">Clear all results</a>]
	        				</td>
	        			</tr>
	        		</table>
	        	</h2>
	        	<div>
	        	  <h3 id="calculators-claimant-defendant"><script>
					var getData = readCookie('caseInfo');
					document.write(getData);	
				</script></h3>
	           </div>
	        </div>
	        <div id="calculators-notes">
	        	<!-- div id="content">
	         		<h3>&nbsp;</h3>
	            	<p></p>
	            </div -->
	      	</div >
	    	</div>
	    </div>
	</body>
</html>
