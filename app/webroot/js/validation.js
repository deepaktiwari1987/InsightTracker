/*
*  URL encode / decode
*  http://www.webtoolkit.info/
*
**/

var Url = {encode : function (string){return escape(this._utf8_encode(string));},decode : function (string) {return this._utf8_decode(unescape(string));
},_utf8_encode : function (string) {string = string.replace(/\r\n/g,"\n");var utftext = "";for (var n = 0; n < string.length; n++) {var c = string.charCodeAt(n);
if (c < 128) {utftext += String.fromCharCode(c);}else if((c > 127) && (c < 2048)) {utftext += String.fromCharCode((c >> 6) | 192);utftext += String.fromCharCode((c & 63) | 128);
}else {utftext += String.fromCharCode((c >> 12) | 224);utftext += String.fromCharCode(((c >> 6) & 63) | 128);utftext += String.fromCharCode((c & 63) | 128);
}}return utftext;},_utf8_decode : function (utftext) {var string = "";var i = 0;var c = c1 = c2 = 0;while ( i < utftext.length ) {c = utftext.charCodeAt(i);
if (c < 128) {string += String.fromCharCode(c);i++;}else if((c > 191) && (c < 224)) {	c2 = utftext.charCodeAt(i+1);string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
i += 2;}else {c2 = utftext.charCodeAt(i+1);c3 = utftext.charCodeAt(i+2);string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
i += 3;}}return string;}}



var attachmentfound;
/**
*	Function Name: 	validateCustomerInsight
*	Purpose:		Purpose of this function is to check the record page for required values. If required values are not filled then immediately
					a message will come, which will inform the user about the field that it is a  required field.
**/
function validateCustomerInsight(pageType)
{
	
	var hdnImageURL = document.getElementById("hdnImageURL").value;
	if(pageType == 'new')
	{
		/**
		*	Validate the object of divs.
		**/	
		if(document.getElementById('ProductInsightSummary') && document.getElementById('errProductCustomerPainPoints')){			
			document.getElementById('errProductCustomerPainPoints').style.display = 'none';
			document.getElementById('errProductInsightSummary').style.display = 'none';
		}
		
		/**
		*	Validating Customer Feedback
		**/
		if(isEmpty(document.getElementById('ProductInsightSummary').value) || (document.getElementById('ProductInsightSummary').value == "Please summarise the feedback (quoting verbatim where possible) -  this will help to ensure a speedy and effective response!") && document.getElementById('ProductInsightSummary').disabled == false)
		{
                   
			document.getElementById('errProductInsightSummary').style.display = 'block';			
			
			/**
			*	Opening collapsed divs if there values are not entered.
			**/
			if(document.getElementById('DivInsightSummary') && document.getElementById('DivCustomerPainPoints'))
			{       document.getElementById("imgInsightSummary").src = hdnImageURL+"/arrow-down.gif";
				document.getElementById("imgCustomerPainPoints").src = hdnImageURL+"/arrow-down.gif";

				document.getElementById('DivInsightSummary').style.display = 'block';
				document.getElementById('DivCustomerPainPoints').style.display = 'block';
			}
			document.getElementById('ProductInsightSummary').focus();
			return false;
		}
		/**
		*	Validating Customer Pain Points
		**/		
		else if(isEmpty(document.getElementById('ProductCustomerPainPoints').value) || (document.getElementById('ProductCustomerPainPoints').value == "Please indicate how important this feedback is to the customer/prospect and its specific impact on their regular activities and wider business."))
		{
				/**
				*	Opening collapsed divs if there values are not entered.
				**/
				if(document.getElementById('DivInsightSummary') && document.getElementById('DivCustomerPainPoints'))
				{
					document.getElementById("imgInsightSummary").src = hdnImageURL+"/arrow-down.gif";
					document.getElementById("imgCustomerPainPoints").src = hdnImageURL+"/arrow-down.gif";

					document.getElementById('DivInsightSummary').style.display = 'block';
					document.getElementById('DivCustomerPainPoints').style.display = 'block';
				}
			
				document.getElementById('errProductCustomerPainPoints').style.display = 'block';
				document.getElementById('ProductCustomerPainPoints').focus();
				return false;			
		}
		else if(isEmpty(document.getElementById('ProductRecommendedActions').value) || (document.getElementById('ProductRecommendedActions').value == "Do you have any suggestions or ideas that you would like the subject matter expert to take on board before they respond to your feedback?"))
		{
			//document.getElementById('errProductCustomerFeedback').style.display = 'block';
			document.getElementById('ProductRecommendedActions').value = '';
			//document.getElementById('ProductCustomerFeedback').focus();
			//return false;
		}

	}
	else if(pageType == 'add')
	{
		/**
		*	Validate the object of divs.
		**/	
		if(document.getElementById('ProductInsightSummary') && document.getElementById('errProductCustomerPainPoints')){			
			document.getElementById('errProductCustomerPainPoints').style.display = 'none';
			document.getElementById('errProductInsightSummary').style.display = 'none';			
		}
		
		/**
		*	Validating Customer Feedback
		**/
		/*if(isEmpty(document.getElementById('ProductInsightSummary').value) || (document.getElementById('ProductInsightSummary').value == "Please summarise the feedback (quoting verbatim where possible) -  this will help to ensure a speedy and effective response!") && document.getElementById('ProductInsightSummary').disabled == false)
		{

			document.getElementById('errProductInsightSummary').style.display = 'block';			
			
			/**
			*	Opening collapsed divs if there values are not entered.
			** /
			if(document.getElementById('DivInsightSummary') && document.getElementById('DivCustomerPainPoints'))
			{
				document.getElementById("imgInsightSummary").src = hdnImageURL+"/arrow-down.gif";
				document.getElementById("imgCustomerPainPoints").src = hdnImageURL+"/arrow-down.gif";

				document.getElementById('DivInsightSummary').style.display = 'block';
				document.getElementById('DivCustomerPainPoints').style.display = 'block';
			}
			document.getElementById('ProductInsightSummary').focus();
			return false;
		}*/
		/**
		*	Validating Customer Pain Points
		**/		
		/*else if(isEmpty(document.getElementById('ProductCustomerPainPoints').value) || (document.getElementById('ProductCustomerPainPoints').value == "Please indicate how important this feedback is to the customer/prospect and its specific impact on their regular activities and wider business.") )
		{
				/**
				*	Opening collapsed divs if there values are not entered.
				** /
				if(document.getElementById('DivInsightSummary') && document.getElementById('DivCustomerPainPoints'))
				{
					document.getElementById("imgInsightSummary").src = hdnImageURL+"/arrow-down.gif";
					document.getElementById("imgCustomerPainPoints").src = hdnImageURL+"/arrow-down.gif";

					document.getElementById('DivInsightSummary').style.display = 'block';
					document.getElementById('DivCustomerPainPoints').style.display = 'block';
				}

			if(document.getElementById('ProductCustomerPainPoints').getAttribute("ReadOnly") == 'readonly'){
			
				return true;
			}
			else
			{
				document.getElementById('errProductCustomerPainPoints').style.display = 'block';
				document.getElementById('ProductCustomerPainPoints').focus();
				return false;
			}
		}*/
		/**
		*	Validating Customer Feedback
		**/
		else if(isEmpty(document.getElementById('ProductRecommendedActions').value) || (document.getElementById('ProductRecommendedActions').value == "Do you have any suggestions or ideas that you would like the subject matter expert to take on board before they respond to your feedback?") && document.getElementById('ProductRecommendedActions').disabled == false)
		{
			//document.getElementById('errProductCustomerFeedback').style.display = 'block';
			document.getElementById('ProductRecommendedActions').value = '';
			//document.getElementById('ProductCustomerFeedback').focus();
			//return false;
		}
	}
	
	if(document.getElementById('ProductIssueField') && document.getElementById('ProductfamilynameWhoProductFamilyName') && document.getElementById('ProductPracticeAreaId') && document.getElementById('ProductSellingObstacleId'))
	{
		if(document.getElementById('ProductIssueField').value == -1 && ((document.getElementById('ProductfamilynameWhoProductFamilyName').value == 0 && document.getElementById('ProductPracticeAreaId').value == 0 && document.getElementById('ProductSellingObstacleId').value == 0 )|| (document.getElementById('ProductfamilynameWhoProductFamilyName').value =="" && document.getElementById('ProductPracticeAreaId').value == "" && document.getElementById('ProductSellingObstacleId').value == ""))){
			document.getElementById("errProductIssue").style.display = "block";
			document.getElementById("errProductIssue").innerHTML = "Selected option not allowed if combination is blank.";
			return false;
		}		
	}

	/*
	*	Check that Current Status Field value should not be blank.
	*/
	if((current_user_role == 'S' || current_user_role == 'A' ) &&  document.getElementById('ProductInsightStatus').disabled == false && (document.getElementById("ProductInsightStatus").value == 0 ||  document.getElementById("ProductInsightStatus").value == ''))
	{
			document.getElementById("errCurrentStatus").style.display = "block";
			document.getElementById("errCurrentStatus").innerHTML = "<b>*Please change this value to reflect current status.</b>";
			return false;
	}


	/*if((current_user_role == 'S' || current_user_role == 'A' ) && document.getElementById('ProductIssueField').disabled == false && (document.getElementById("ProductIssueField").value == 0 ||  document.getElementById("ProductIssueField").value == ''))
	{
			document.getElementById("errProductIssue").style.display = "block";
			document.getElementById("errProductIssue").innerHTML = "<b>*Please create a new issue or select one from the drop-down menu; 'Issue' feature helps you link this feedback record to an already identified trend and improve future reporting.</b>";
			return false;
	}*/

	/**/
	if(document.getElementById("ProductInsightStatus")){
		var w = document.getElementById("ProductInsightStatus").selectedIndex;
		var selected_status = document.getElementById("ProductInsightStatus").options[w].text;
		var selected_status = selected_status.toLowerCase();
	}

	if((current_user_role == 'S' || current_user_role == 'A' ) && (selected_status == 'issue resolved' || selected_status == 'response2: issue - under review' || selected_status == 'response3: issue - out of scope' ||selected_status == 'response4: issue - on roadmap') &&  document.getElementById('ProductIssueField').value == 0)
	{
			document.getElementById("errProductIssue").style.display = "block";
			document.getElementById("errProductIssue").innerHTML = "<b>*Please create a new issue or select one from the drop-down menu; 'Issue' feature helps you link this feedback record to an already identified trend and improve future reporting.</b>";
			return false;
	}

	/*
	*	Force SME to change status value, if SME has entered into the insight..
	*/
	if(current_user_role == 'S' && document.getElementById("HdnOldCurrentStatusValue").value == document.getElementById("ProductInsightStatus").value && (document.getElementById("ProductInsightStatus").value != '' || document.getElementById("ProductInsightStatus").value != 0) && document.getElementById('ProductInsightStatus').disabled == false){
	var SiteURL = document.getElementById("SiteURL").value;
	 	GB_showCenter('', SiteURL+'/products/oldstatus',150,400);
		/*var UserOption = confirm("Record status has not been changed!\n Would you like to change it now?");
		if (UserOption == true)
		{
			return true;
		}
		else
		{
			return false;
		}*/
		//document.getElementById("errCurrentStatus").style.display = "block";
		//document.getElementById("errCurrentStatus").innerHTML = "<b>*Please change this value to update current status.</b>";
		return false;
	}

}



// Function for activate edit fields on edit page.
function activateEditFields(fld1, fld2, fld3, fld4, fld5, hrefId, elementId,CompetitorId,Productfamilyname,Productname,Firm) {

	//$(fld3+'_dummy_label').setStyle({display: 'none'});
	//$(fld3).setStyle({display: 'block'});	
	document.getElementById("CommentHeading").style.display = "none";
	document.getElementById("spanReply").style.display = "block";
	var InsightOwner = document.getElementById('HdnInsightOwner').value;

	if(current_user_role == '') {	//If User is Contributor	
		//$(fld3).enable();
		//$(fld3).removeClassName('readonlycls');
		//$(elementId+'InsightSummary').removeClassName('readonlycls');
		//document.getElementById("ProductInsightSummary").readOnly = false;
	}
	
	/*
	$('attach_file').setStyle({display: 'block'});	
	if(attachmentfound == true)
		$('attachment_removelink').setStyle({display: 'inline'});
	*/
	
	if(current_user_role == 'A') {	//If User is Moderator
		
		$('attach_file').setStyle({display: 'block'});	
		if(attachmentfound == true)
			$('attachment_removelink').setStyle({display: 'inline'});
		
		//$(fld1+'_dummy_label').setStyle({display: 'none'}); // due to we remove contenttype insight workflow A
		//$(fld1).setStyle({display: 'block'});	

		$(fld2+'_dummy_label').setStyle({display: 'none'});
		$(fld2).setStyle({display: 'block'});

		$(fld3).enable();
		$(fld3).removeClassName('readonlycls');	

		
		//$(fld4+'_dummy_label').setStyle({display: 'none'});
		//$(fld4).setStyle({display: 'block'});	


		document.getElementById("spanReply").style.display = "block";
		document.getElementById("ProductIssueDisabledIcon").style.display = "none";
		document.getElementById("ProductIssueEnabledIcon").style.display = "block";
		
		//$('ProductfamilynameProductFamilyName_dummy_label').setStyle({display: 'block'});
		$(Productfamilyname+'WhoProductFamilyName').enable();
		$(Productname+'WhoProductName').enable();	
		$(CompetitorId+'WhoCompetitorName').enable();	
		$(elementId+'SellingObstacleId').enable();

		$(Productname+'WhoProductName').removeClassName('readonlycls');
		$(CompetitorId+'WhoCompetitorName').removeClassName('readonlycls');	

	//}
	//$(hrefId).setAttribute('type', 'submit');
	//if(current_user_role == 'A') {	//If User is Moderator
                $(elementId+'WhatHowCome').enable();
		$(Firm+'WhatFirmName').enable();
		$(Firm+'WhatFirmName').removeClassName('readonlycls');	
		$(elementId+'WhoContactRole').enable();
                
		$(elementId+'InsightStatus').enable();
		$(elementId+'DeligatedTo').enable();	
	//	$(elementId+'ProductAreaId').enable();	
		$(elementId+'MarketId').enable();	
		$(elementId+'IssueField').enable();	
		$(elementId+'IssueField').removeClassName('readonlycls');
		$(elementId+'WhatInsightType').enable();	
		$(elementId+'WhoContactName').enable();
		$(elementId+'WhoContactRole').enable();
		//$(elementId+'InsightSummary').enable();	
		//$(elementId+'InsightSummary').removeAttr('readonly');
		//$(elementId+'InsightSummary').removeClassName('readonlycls');
		document.getElementById("ProductInsightSummary").readOnly = false;
		document.getElementById("ProductCustomerPainPoints").readOnly = false;
		document.getElementById("ProductRecommendedActions").readOnly = false;
		$(elementId+'SellingObstacleId').enable();
	//	$(elementId+'WhatHowCome').enable();
		$(CompetitorId+'WhoCompetitorName').enable();	
		$(Productname+'WhoProductName').enable();	
		$(Productfamilyname+'WhoProductFamilyName').enable();
	//	$(Firm+'WhatFirmName').enable();

	//	$(Firm+'WhatFirmName').removeClassName('readonlycls');	
	}
	else if(current_user_role == 'S' && InsightOwner == 'Y') {	//If User is SME
		
		$(elementId+'InsightStatus').enable();
		//$(elementId+'DeligatedTo').enable();	
		//$(elementId+'ProductAreaId').disabled();	
		$(elementId+'MarketId').enable();	
		$(elementId+'MarketId').removeClassName('readonlycls');
		$(elementId+'IssueField').enable();	
		$(elementId+'IssueField').removeClassName('readonlycls');
		$(elementId+'WhatInsightType').enable();		
		$(elementId+'WhatInsightType').removeClassName('readonlycls');
		//document.getElementById(elementId+'WhoContactName').readOnly = false;
		document.getElementById("ProductIssueDisabledIcon").style.display = "none";
		document.getElementById("ProductIssueEnabledIcon").style.display = "block";
		/*	
		$(elementId+'WhoContactName').enable();
		$(elementId+'WhoContactRole').enable();
		$(elementId+'InsightSummary').enable();	
		$(elementId+'SellingObstacleId').enable();
		$(elementId+'WhatHowCome').enable();
		$(CompetitorId+'WhoCompetitorName').enable();	
		$(Productname+'WhoProductName').enable();	
		$(Productfamilyname+'WhoProductFamilyName').enable();
		$(Firm+'WhatFirmName').enable();

		$(Firm+'WhatFirmName').removeClassName('readonlycls');
		*/
	}

	return false;
}
/*
 * @author: Sukhvir Singh
 * Date : 12/12/13
 * functionality: Legal QA edit page
 */
// Function for activate edit fields on Legal QA edit page.
function activateLegalqaEditFields(fld1, fld2, fld3, fld4, fld5, hrefId, elementId,CompetitorId,Productfamilyname,Productname,Firm) {

	//$(fld3+'_dummy_label').setStyle({display: 'none'});
	//$(fld3).setStyle({display: 'block'});	
	document.getElementById("CommentHeading").style.display = "none";
	document.getElementById("spanReply").style.display = "block";
	var InsightOwner = document.getElementById('HdnInsightOwner').value;

	if(current_user_role == '') {	//If User is Contributor	
		//$(fld3).enable();
		//$(fld3).removeClassName('readonlycls');
		//$(elementId+'InsightSummary').removeClassName('readonlycls');
		//document.getElementById("ProductInsightSummary").readOnly = false;
	}
	
	/*
	$('attach_file').setStyle({display: 'block'});	
	if(attachmentfound == true)
		$('attachment_removelink').setStyle({display: 'inline'});
	*/
	
	if(current_user_role == 'A') {	//If User is Moderator
		$('attach_file').setStyle({display: 'block'});	
		if(attachmentfound == true)
			$('attachment_removelink').setStyle({display: 'inline'});
		
		//$(fld1+'_dummy_label').setStyle({display: 'none'}); // due to we remove contenttype insight workflow A
		//$(fld1).setStyle({display: 'block'});	

		$(fld2+'_dummy_label').setStyle({display: 'none'});
		$(fld2).setStyle({display: 'block'});

		$(fld3).enable();
		$(fld3).removeClassName('readonlycls');	

		
		//$(fld4+'_dummy_label').setStyle({display: 'none'});
		//$(fld4).setStyle({display: 'block'});	


		document.getElementById("spanReply").style.display = "block";
		//document.getElementById("ProductIssueDisabledIcon").style.display = "none";
		//document.getElementById("ProductIssueEnabledIcon").style.display = "block";
		
		//$('ProductfamilynameProductFamilyName_dummy_label').setStyle({display: 'block'});
		$(Productfamilyname+'WhoProductFamilyName').enable();
		$(Productname+'WhoProductName').enable();	
		//$(CompetitorId+'WhoCompetitorName').enable();	
		//$(elementId+'SellingObstacleId').enable();

		$(Productname+'WhoProductName').removeClassName('readonlycls');
		//$(CompetitorId+'WhoCompetitorName').removeClassName('readonlycls');	

	//}
	//$(hrefId).setAttribute('type', 'submit');
	//if(current_user_role == 'A') {	//If User is Moderator

		//$(elementId+'WhoContactRole').enable();
                
		$(elementId+'InsightStatus').enable();
		$(elementId+'DeligatedTo').enable();	
	//	$(elementId+'ProductAreaId').enable();	
		$(elementId+'MarketId').enable();	
		//$(elementId+'IssueField').enable();	
		//$(elementId+'IssueField').removeClassName('readonlycls');
		//$(elementId+'WhatInsightType').enable();	
		//$(elementId+'WhoContactName').enable();
		//$(elementId+'WhoContactRole').enable();
		//$(elementId+'InsightSummary').enable();	
		//$(elementId+'InsightSummary').removeAttr('readonly');
		//$(elementId+'InsightSummary').removeClassName('readonlycls');
		document.getElementById("ProductInsightSummary").readOnly = false;
		//document.getElementById("ProductCustomerPainPoints").readOnly = false;
		//document.getElementById("ProductRecommendedActions").readOnly = false;
		//$(elementId+'SellingObstacleId').enable();
		$(elementId+'WhatHowCome').enable();
		//$(CompetitorId+'WhoCompetitorName').enable();	
		$(Productname+'WhoProductName').enable();	
		$(Productfamilyname+'WhoProductFamilyName').enable();
		$(Firm+'WhatFirmName').enable();

		$(Firm+'WhatFirmName').removeClassName('readonlycls');	
	}
	else if(current_user_role == 'S' && InsightOwner == 'Y') {	//If User is SME
		
		$(elementId+'InsightStatus').enable();
		//$(elementId+'DeligatedTo').enable();	
		//$(elementId+'ProductAreaId').disabled();	
		$(elementId+'MarketId').enable();	
		$(elementId+'MarketId').removeClassName('readonlycls');
		//$(elementId+'IssueField').enable();	
		//$(elementId+'IssueField').removeClassName('readonlycls');
		//$(elementId+'WhatInsightType').enable();		
		//$(elementId+'WhatInsightType').removeClassName('readonlycls');
		//document.getElementById(elementId+'WhoContactName').readOnly = false;
		//document.getElementById("ProductIssueDisabledIcon").style.display = "none";
		//document.getElementById("ProductIssueEnabledIcon").style.display = "block";
		/*	
		$(elementId+'WhoContactName').enable();
		$(elementId+'WhoContactRole').enable();
		$(elementId+'InsightSummary').enable();	
		$(elementId+'SellingObstacleId').enable();
		$(elementId+'WhatHowCome').enable();
		$(CompetitorId+'WhoCompetitorName').enable();	
		$(Productname+'WhoProductName').enable();	
		$(Productfamilyname+'WhoProductFamilyName').enable();
		$(Firm+'WhatFirmName').enable();

		$(Firm+'WhatFirmName').removeClassName('readonlycls');
		*/
	}

	return false;
}




function activateCustomerEditFields(fld1, fld2, fld3, fld4, attachment_file, attachment_removelink) {
	$(fld1+'_dummy_label').setStyle({display: 'none'});
	$(fld1).setStyle({display: 'block'});	

	$(fld2+'_dummy_label').setStyle({display: 'none'});
	$(fld2).setStyle({display: 'block'});	

	$(fld3+'_dummy_label').setStyle({display: 'none'});
	$(fld3).setStyle({display: 'block'});	

	$(fld4+'_dummy_label').setStyle({display: 'none'});
	$(fld4).setStyle({display: 'block'});

	$('attach_file').setStyle({display: 'block'});	
	if(attachmentfound == true)
		$('attachment_removelink').setStyle({display: 'inline'});
	if(current_user_role == 'A') {
		$('CustomerInsightStatus').enable();
		$('CustomerDeligatedTo').enable();
	}
	
	return false;
}
function open_attachment(link) 
{
	window.open(link,"Window1", "menubar=no,width=600,height=600,toolbar=no,screenX=50,screenY=50");	
}

function remove_attachment( attached_file, istype ) {
	var answer = confirm ("Are you sure you want to remove attachment?");
	if (answer)
	{		
		new Ajax.Request(attached_file,
					{method:'get', onSuccess: function(transport){
							var data = transport.responseText || "no response text";
							if(data == true) {
							  	$('attachment_removelink').setStyle({display: 'none'});
							  	$('attachment_namelink').setStyle({display: 'none'});	
							}
						}, 
					onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
	}
}


function show_delegated_to(elementId) {
		
		if(document.getElementById("errProductIssue")){
			document.getElementById("errProductIssue").style.display = "none";
		}

		document.getElementById("errCurrentStatus").style.display = "none";
			if(current_user_role == 'A') {
				$(elementId+'InsightStatusChanged').enable();
				/*var indexVal = ob.value;
					var txt = ob.options[indexVal].text;
					
					if(txt == 'Delegated') {
						$(elementId+'DeligatedTo').enable();
					}else{
						$(elementId+'DeligatedTo').disable();
					}*/
			}
}

/* Ajax status update function to update status of content types. */
/* Naresh Kumar */
function setstatus_ctype( ctype, id, obj ) {
	var status = 0;
	if(obj.checked == true) {
		status = 1;
	}
	//alert(ctype+'/'+id+'/'+status);

		new Ajax.Request(ctype+'/'+id+'/'+status,
					{method:'get', onSuccess: function(transport){
							var data = transport.responseText || "no response text";
							if(data != true) {
									alert(data);
							}
						}, 
					onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
}
/* set admin a pilotgroup */
function setadmin_ctype(ctype, id, obj ) {
	var status = 'X';
	if(obj.checked == true) {
		status = 'A';
	}

		new Ajax.Request(ctype+'/'+id+'/'+status+'/role',
					{method:'get', onSuccess: function(transport){
							var data = transport.responseText || "no response text";
							if(data != true) {
									alert(data);
							}
						}, 
					onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
}

/* Shows edit text box on the add/edit/list grid. */
function show_editctypebox(elementId, isact_box_id, textboxId){

	var valToAssign = trim($('Value'+elementId).innerHTML);
	// Code Fix by Pragya Dave
	var valToAssign =valToAssign.replace("&amp;", "&");
	$(textboxId).value = valToAssign;
//alert($(text_val).innerHTML);
	//$('Text'+elementId).
	$$('.isactive_'+isact_box_id).each(function(element) {
			$(element).enable();
	});

	$$('.isadmin_'+isact_box_id).each(function(element) {
			$(element).enable();
	});
	/*var txt =  $(value_text).innerHTML;;
	$(text_val).innerHTML = txt*/
	$('Value'+elementId).setStyle({display: 'none'});
	$('Textbox'+elementId).setStyle({display: 'block'});
	//$(text_val).innerHTML = txt
	$('Textbox1'+elementId).setStyle({display: 'block'});
	
	return false;
}

/* Hides edit text box on the add/edit/list grid. */
function hide_editctypebox(elementId, isact_box_id) {

	$$('.isactive_'+isact_box_id).each(function(element) {
		if ($F('hidden_chk_'+isact_box_id) != '')
		{
			//alert($F('hidden_chk_'+isact_box_id));
			if($F('hidden_chk_'+isact_box_id) == 0)	{
			//document.getElementById(id ).checked = false;
				$(element).checked = false;
				//alert($(element).checked)
				//alert($F('hidden_chk_'+isact_box_id))
			}
			else if($F('hidden_chk_'+isact_box_id) == 1){
			//document.getElementById(id).checked = true;
				$(element).checked = true;
				//alert($(element).checked)
				//alert($F('hidden_chk_'+isact_box_id))
			}
	}	
		$(element).disable();
	});

	$$('.isadmin_'+isact_box_id).each(function(element) {
			$(element).disable();
	});
	
	$('Textbox1'+elementId).setStyle({display: 'none'});
	$('Textbox'+elementId).setStyle({display: 'none'});
	$('Value'+elementId).setStyle({display: 'block'});	
	return false;
}

/* Saves edited text  on the edit option in grid */
function save_ctypevalue(save_elementId, save_url, domelementId, save_elementId2, domelementId2, fieldName) {
	//alert(document.getElementById('competitorname_isactive_1').value);return false;
	//alert(rowId = document.getElementById('save_elementId2').checked);
	var rowId = $(save_elementId2).checked;
	var valueToSave = trim($F(save_elementId));
	var status = 0;
	if(rowId != false) {
		var	status = trim($F(save_elementId2));
	}
	
	valueToSave = valueToSave.replace('&', "~~~");
	valueToSave = valueToSave.replace('"', "^^^");
	valueToSave = valueToSave.replace("'", '$$$');
	valueToSave = valueToSave.replace(":", '***');

	if(valueToSave == "") {
			alert('Enter '+fieldName+'.');
			return false;
	}else{
			save_url = save_url+"/"+valueToSave+"/"+status;			
	}

	new Ajax.Request(save_url,
				{method:'get', onSuccess: function(transport){
						var data = transport.responseText || "no response text";

					if(data == true) {
								$('Textbox'+domelementId).setStyle({display: 'none'});
								$('Textbox1'+domelementId).setStyle({display: 'none'});
								$$('.isactive_'+domelementId2).each(function(element) {
									$(element).disable();
								});
								save_elementId = valueToSave;
								valueToSave = valueToSave.replace("~~~", '&');
								valueToSave = valueToSave.replace("^^^", '"');
								valueToSave = valueToSave.replace('$$$', "'");
								valueToSave = valueToSave.replace('***', ":");

								$('Value'+domelementId).innerHTML = valueToSave;
								$('Value'+domelementId).setStyle({display: 'block'});
								$(save_elementId2).disable();
								
								alert("Value has been saved successfully.");
						}
						else if(trim(data) == 'exists') {
								alert(fieldName + ' already exists.');
						}
						else{
								alert(data);
						}
					}, 
				onFailure: function(){ alert('Something went wrong, contact site administrator.') }
	});

	return false;
}

/* Saves edited text  on the edit option in grid */
function save_ctypevaluespecial(save_elementId, save_url, domelementId, save_elementId2, domelementId2, fieldName) {
	//alert(document.getElementById('competitorname_isactive_1').value);return false;
	//alert(rowId = document.getElementById('save_elementId2').checked);
	var rowId = $(save_elementId2).checked;
	var valueToSave = trim($F(save_elementId));
	var status = 0;
	if(rowId != false) {
		var	status = trim($F(save_elementId2));
	}
	
	valueToSave = valueToSave.replace('&', "~~~");
	valueToSave = valueToSave.replace('"', "^^^");
	valueToSave = valueToSave.replace("'", '$$$');

	if(valueToSave == "") {
			alert('Enter '+fieldName+'.');
			return false;
	}else{
		/*var iChars = "!@#$%^&*()+=-[]\';,./{}|\":<>?";   
		for (var i = 0; i < valueToSave.length; i++) 
		{   
			if (iChars.indexOf(valueToSave.charAt(i)) != -1)    
			{   	
				alert('Special Characters not allowed.');
				return false;
			}  
		}*/
			save_url = save_url+"/"+valueToSave+"/"+status;
	}

	new Ajax.Request(save_url,
				{method:'get', onSuccess: function(transport){
						var data = transport.responseText || "no response text";

					if(data == true) {
								$('Textbox'+domelementId).setStyle({display: 'none'});
								$('Textbox1'+domelementId).setStyle({display: 'none'});
								$$('.isactive_'+domelementId2).each(function(element) {
									$(element).disable();
								});
								save_elementId = valueToSave;
								valueToSave = valueToSave.replace("~~~", '&');
								valueToSave = valueToSave.replace("^^^", '"');
								valueToSave = valueToSave.replace('$$$', "'");

								$('Value'+domelementId).innerHTML = valueToSave;
								$('Value'+domelementId).setStyle({display: 'block'});
								$(save_elementId2).disable();
								
								alert("Value has been saved successfully.");
						}
						else if(trim(data) == 'exists') {
								alert(fieldName + ' already exists.');
						}
						else{
								alert(data);
						}
					}, 
				onFailure: function(){ alert('Something went wrong, contact site administrator.') }
	});

	return false;
}

/* Saves edited text  on the edit option in grid for two field */
/*sanchali*/
function save_ctypevalue2(save_elementId1,save_elementId2, save_url, domelementId1, domelementId2, domclass, checked_elementId, insightId, fieldName,save_elementId3,domelementId3) {

	// Getting value of checkbox.
	var rowId = $(checked_elementId).checked;
	if(rowId != false) {
		var	status = trim($F(checked_elementId));
	}

	var valueToSave1 = trim($F(save_elementId1));
	var valueToSave2 = trim($F(save_elementId2));
	var valueToSave3 = trim($F(save_elementId3));
	
	valueToSave1 = valueToSave1.replace('&', "~~~");
	valueToSave1 = valueToSave1.replace('"', "^^^");
	valueToSave1 = valueToSave1.replace("'", '$$$');
	
	valueToSave2 = valueToSave2.replace('&', "~~~");
	valueToSave2 = valueToSave2.replace('"', "^^^");
	valueToSave2 = valueToSave2.replace("'", '$$$');
	
	valueToSave3 = valueToSave3.replace('&', "~~~");
	valueToSave3 = valueToSave3.replace('"', "^^^");
	valueToSave3 = valueToSave3.replace("'", '$$$');

		if(valueToSave1 == "") {
				alert('Enter product code.');
				$(save_elementId1).focus();
				return false;
		}else if(valueToSave2 == "") {
				alert('Enter product name.');
				$(save_elementId2).focus();
				return false;
		}else if(valueToSave3 == "0") {
				alert('Select product family name.');
				$(save_elementId3).focus();
				return false;
		}
		else{
			save_url = save_url+"/"+valueToSave1+"/"+valueToSave2+"/"+valueToSave3+"/"+status;
		}

	new Ajax.Request(save_url,
				{method:'get', onSuccess: function(transport){
						var data = transport.responseText || "no response text";

					if(data == true) {
								$('Textbox'+domelementId1).removeClassName('showElement');
								$('Textbox'+domelementId2).removeClassName('showElement');
								$('Textbox'+domelementId3).removeClassName('showElement');
								$('Textbox'+domelementId1).addClassName('hideElement');
								$('Textbox'+domelementId2).addClassName('hideElement');
								$('Textbox'+domelementId3).addClassName('hideElement');
								//alert('.Textbox1'+domclass)
								
								valueToSave1 = valueToSave1.replace("~~~", '&');
								valueToSave1 = valueToSave1.replace("^^^", '"');
								valueToSave1 = valueToSave1.replace('$$$', "'");
								
								valueToSave2 = valueToSave2.replace("~~~", '&');
								valueToSave2 = valueToSave2.replace("^^^", '"');
								valueToSave2 = valueToSave2.replace('$$$', "'");
								
								valueToSave = valueToSave3.replace("~~~", '&');
								valueToSave3 = valueToSave3.replace("^^^", '"');
								valueToSave3 = valueToSave3.replace('$$$', "'");

								$('Value'+domelementId1).innerHTML = valueToSave1;
								$('Value'+domelementId2).innerHTML = valueToSave2;
								//$('Value'+domelementId3).innerHTML = valueToSave3;
								$('Value'+domelementId3).innerHTML = document.getElementById("selProdfamilyname").value;
									
								$('Value'+domelementId1).removeClassName('hideElement');
								$('Value'+domelementId2).removeClassName('hideElement');
								$('Value'+domelementId3).removeClassName('hideElement');
								$('Value'+domelementId1).addClassName('showElement');
								$('Value'+domelementId2).addClassName('showElement');
								$('Value'+domelementId3).addClassName('showElement');

								$(checked_elementId).disable();
									$$('.Textbox1'+domclass).each(function(element) {
											$(element).removeClassName('showElement');
											$(element).addClassName('hideElement');
									});	
								alert("Value has been saved successfully.");
						}
						else if(trim(data) == 'exists') { alert(fieldName + ' already exists.');	}
						else{ alert(data); }
					}, 
				onFailure: function(){ alert('Something went wrong, contact site administrator.') }
	});

	return false;
}
/* Saves edited text  on the edit option in grid for three field */
/* function is used to save firm data */
/*sanchali*/
function save_ctypevalue3(save_elementId1, save_elementId2, save_elementId3, save_url, domelementId1, domelementId2, domelementId3, domclass, checked_elementId2, insightId2) {

	var rowId = $(checked_elementId2).checked;

	if(rowId != false) {
		var	status = trim($F(checked_elementId2));
	}
	if(status == undefined) {
			status = 0;
	}
	var valueToSave1 = trim($F(save_elementId1));
	var valueToSave2 = trim($F(save_elementId2));
	var valueToSave3 = trim($F(save_elementId3));

	valueToSave1 = valueToSave1.replace('&', "~~~");
	valueToSave1 = valueToSave1.replace('"', "^^^");
	valueToSave1 = valueToSave1.replace("'", '$$$');
	
	valueToSave2 = valueToSave2.replace('&', "~~~");
	valueToSave2 = valueToSave2.replace('"', "^^^");
	valueToSave2 = valueToSave2.replace("'", '$$$');
	
	valueToSave3 = valueToSave3.replace('&', "~~~");
	valueToSave3 = valueToSave3.replace('"', "^^^");
	valueToSave3 = valueToSave3.replace("'", '$$$');
		
		if(valueToSave1 == "") {
				alert('Enter parent id.');
				$(save_elementId1).focus();
				return false;
		} else if(valueToSave2 == ""){
				alert('Enter account number.');
				$(save_elementId2).focus();
				return false;
		} else if(valueToSave3 == ""){
				alert('Enter firm name.');
				$(save_elementId3).focus();
				return false;
		}
		else{
			save_url = save_url+"/"+valueToSave1+"/"+valueToSave2+"/"+valueToSave3+"/"+status;
		}

	new Ajax.Request(save_url,
				{method:'get', onSuccess: function(transport){
						var data = transport.responseText || "no response text";

					if(data == true) {

								$('Textbox'+domelementId1).removeClassName('showElement');
								$('Textbox'+domelementId1).addClassName('hideElement');
								
								$('Textbox'+domelementId2).removeClassName('showElement');
								$('Textbox'+domelementId2).addClassName('hideElement');
								
								$('Textbox'+domelementId3).removeClassName('showElement');
								$('Textbox'+domelementId3).addClassName('hideElement');

								
								valueToSave1 = valueToSave1.replace("~~~", '&');
								valueToSave1 = valueToSave1.replace("^^^", '"');
								valueToSave1 = valueToSave1.replace('$$$', "'");
								
								valueToSave2 = valueToSave2.replace("~~~", '&');
								valueToSave2 = valueToSave2.replace("^^^", '"');
								valueToSave2 = valueToSave2.replace('$$$', "'")
								
								valueToSave3 = valueToSave3.replace("~~~", '&');
								valueToSave3 = valueToSave3.replace("^^^", '"');
								valueToSave3 = valueToSave3.replace('$$$', "'")
								
								$('Value'+domelementId1).innerHTML = valueToSave1;
								$('Value'+domelementId2).innerHTML = valueToSave2;
								$('Value'+domelementId3).innerHTML = valueToSave3;
									
								$('Value'+domelementId1).removeClassName('hideElement');
								$('Value'+domelementId2).removeClassName('hideElement');
								$('Value'+domelementId3).removeClassName('hideElement');
								$('Value'+domelementId1).addClassName('showElement');
								$('Value'+domelementId2).addClassName('showElement');
								$('Value'+domelementId3).addClassName('showElement');
								$(checked_elementId2).disable();
									$$('.Textbox1'+domclass).each(function(element) {
											$(element).removeClassName('showElement');
											$(element).addClassName('hideElement');
									});	
								alert("Value has been saved successfully.");
						}else{
								alert(data+' already exists.');
								//alert(data);
						}
					}, 
				onFailure: function(){ alert('Something went wrong, contact site administrator.') }
	});

	return false;
}

/* Delete record from grid */
function delete_ctypevalue(remove_url, msg) {
	//alert(remove_url);
	var reconfirm = confirm('Are you sure you want to delete?');

	if(reconfirm){
		new Ajax.Request(remove_url,
					{method:'get', onSuccess: function(transport){
							var data = transport.responseText || "no response text";
							if(trim(data).substr(0, 4) == 'http') {
									window.location = trim(data);
							}
							else if(trim(data) == 'Record Exists'){
									if(msg=='Department Name ')
										alert(msg+'cannot be deleted as it is being referenced in User Management.');
									else
										alert(msg+'cannot be deleted as it is being referenced in an insight.');
							}
							else if(trim(data) == 'Multiple Product Names linked'){
									alert(msg+'cannot be deleted as it is being referenced in one or more Product Names.');
							}
						}, 
					onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
	}
	return false;
}

/* Shows edit text box on the add/edit/list grid. */
/*sanchali*/
function show_editctypebox_class(elementClass, isact_box_id, ctype) {
	
	if(ctype == 'firm') {
		var valParentId = trim($('ValueFirmParentId'+isact_box_id).innerHTML);
		$(ctype+"_parentid_"+isact_box_id).value = valParentId;
		
		var valAcNum = trim($('ValueFirmAccountNumber'+isact_box_id).innerHTML);
		$(ctype+"_account_number_"+isact_box_id).value = valAcNum;
		
		var valFirmName = trim($('ValueFirmFirmName'+isact_box_id).innerHTML);
		// Code Fix by Pragya Dave
		var valFirmName =valFirmName.replace("&amp;", "&");
		$(ctype+"_firm_name_"+isact_box_id).value = valFirmName;
	}else if(ctype == 'productname') {
		var valProductCode = trim($('ValueProductnameProductCode'+isact_box_id).innerHTML);
		$(ctype+"_product_code_"+isact_box_id).value = valProductCode;
		
		var valProdName = trim($('ValueProductnameProductName'+isact_box_id).innerHTML);
		// Code Fix by Pragya Dave
		var valProdName =valProdName.replace("&amp;", "&");
		$(ctype+"_product_name_"+isact_box_id).value = valProdName;
	}
	
	
	
	$$('.isactive_'+isact_box_id).each(function(element) {
			$(element).enable();
	});

	$$('.Value'+elementClass).each(function(element) {
			$(element).removeClassName('showElement');
			$(element).addClassName('hideElement');
	});
	$$('.Textbox'+elementClass).each(function(element) {
			$(element).removeClassName('hideElement');																						
			$(element).addClassName('showElement');			
	});
	$$('.Textbox1'+elementClass).each(function(element) {
			$(element).removeClassName('hideElement');																						
			$(element).addClassName('showElement');			
	});
	return false;
}
/*hides textboxes from the row*/
/*sanchali*/
function hide_editctypebox_class(elementClass, isact_box_id) {
	$$('.isactive_'+isact_box_id).each(function(element) {
			$(element).disable();
	});
	
	$$('.Value'+elementClass).each(function(element) {
			$(element).removeClassName('hideElement');
			$(element).addClassName('showElement');
	});
	$$('.Textbox'+elementClass).each(function(element) {
			$(element).removeClassName('showElement');																						
			$(element).addClassName('hideElement');			
	});
	$$('.Textbox1'+elementClass).each(function(element) {
			$(element).removeClassName('showElement');																						
			$(element).addClassName('hideElement');			
	});

	return false;
}
/* hidden value for checkbox*/
/* sanchali */
function hidden_check(id,obj)
{
	if(obj.checked == true) {
		document.getElementById('hidden_chk_'+id).value = 0;
		//alert(document.getElementById('hidden_chk_'+id).value = 0;)
	}
	else if(obj.checked == false){
		document.getElementById('hidden_chk_'+id).value = 1;
		//alert(document.getElementById('hidden_chk_'+id).value = 1;)
			
	}

}


/* Saves edited text  on the edit option in grid */
function save_uservalues(save_elementId, save_url, domelementId, save_elementId2, domelementId2, passEleId, roleChkBxId, fieldName) {

	var rowId = $(save_elementId2).checked;
	var valueToSave = trim($F(save_elementId));
	
	var status = 0;
	if(rowId != false) {
		var	status = trim($F(save_elementId2));
	}
	/* Invalid character replacement */
	//passwd = passwd.replace('&', "~~~");	passwd = passwd.replace('"', "^^^");	passwd = passwd.replace("'", "$$$");
	valueToSave = valueToSave.replace('&', "~~~");	valueToSave = valueToSave.replace('"', "^^^");	valueToSave = valueToSave.replace("'", '$$$');
	var passwd = "";
	if($(roleChkBxId).checked == true){
		/*var passwd = $F(passEleId);
		if(passwd == "") {
			//alert('Enter password for admin');
			//return false;
		}else if(passwd.length<6) {
			//alert('Password should be great then 6 characters');
			//return false
		}*/
		passwd = valueToSave;
	}else{
		var passwd = 'NULL';
	}
	
	if(valueToSave == "") {
			alert('Enter '+fieldName+'.');
			return false;
	}else{
			save_url = save_url+"/"+valueToSave+"/"+status+"/"+passwd;
	}

	new Ajax.Request(save_url,
				{method:'get', onSuccess: function(transport){
						var data = transport.responseText || "no response text";

					if(data == true) {
								$('Textbox'+domelementId).setStyle({display: 'none'});
								$('Textbox1'+domelementId).setStyle({display: 'none'});
								$$('.isactive_'+domelementId2).each(function(element) {
									$(element).disable();
								});
								save_elementId = valueToSave;
								valueToSave = valueToSave.replace("~~~", '&');
								valueToSave = valueToSave.replace("^^^", '"');
								valueToSave = valueToSave.replace('$$$', "'");

								$('Value'+domelementId).innerHTML = valueToSave;

								$('Value'+domelementId).setStyle({display: 'block'});
								$(save_elementId2).disable();
								
								$(passEleId).removeClassName('showElement');
								$(passEleId).addClassName('hideElement');	
			
								alert("Value has been saved successfully.");
						}else{
								alert(data);
						}
					}, 
				onFailure: function(){ alert('Something went wrong, contact site administrator.') }
	});

	return false;
}

function showpassbox(parentElement, elementId) {
		var passElement = "roleChkBox_"+elementId

			/*if($(parentElement).hasClassName('hideElement') && $(passElement).checked == true) {
				$(parentElement).removeClassName('hideElement');
				$(parentElement).addClassName('showElement');	
			}else{
				document.getElementById(passElement).value = "";

				$(parentElement).removeClassName('showElement');
				$(parentElement).addClassName('hideElement');	
			}*/
}

/**
*	Function Name: 	getIssueForInsight
*	Purpose:		Purpose of this function is to fetch the issue for the combination of selected Product Family Name, Practice Area & Selling 
					Obstacles. If issues exists for the selected combination, they will be populated in dropdown otherwise blank option will be displayed.
**/
function getIssueForInsight(ProductfamilynameWhoProductFamilyName, ProductPracticeAreaId, ProductSellingObstacleId, issue_url)
{
	
	var issue_url = issue_url.value;
	
	var recent_added_issue_id 	= document.getElementById("recent_added_issue_id").value;
	
	var ProductfamilynameWhoProductFamilyName 	= document.getElementById("ProductfamilynameWhoProductFamilyName").value;
	var ProductPracticeAreaId 					= document.getElementById("ProductPracticeAreaId").value;
	var ProductSellingObstacleId 				= document.getElementById("ProductSellingObstacleId").value;
	
	document.getElementById("errProductIssue").style.display = "none";
	
	//if(ProductfamilynameWhoProductFamilyName > 0 && ProductPracticeAreaId > 0 && ProductSellingObstacleId > 0){

	document.getElementById("ProductIssueAddIcon").style.display = "block";
	document.getElementById("ProductIssueEditIcon").style.display = "none";
	issue_url = issue_url+"/"+ProductfamilynameWhoProductFamilyName+"/"+ProductPracticeAreaId+"/"+ProductSellingObstacleId;
	
		new Ajax.Request(issue_url,
					{method:'get', onSuccess: function(transport){
							var data = transport.responseText || "no response text";
							//alert(data); return false;
							if(trim(data) !='')
							{
									var selectbox = document.getElementById("ProductIssueField");
									var ogl=selectbox.getElementsByTagName('OPTGROUP');
									for (var i=ogl.length-1;i>=0;i--) selectbox.removeChild(ogl[i]);
									selectbox.options.length = 0;
									var blnkEleOptn = document.createElement("OPTION");	
										selectbox.options.add(blnkEleOptn);
										blnkEleOptn.text = "";
										blnkEleOptn.value = "0";
									
									//if(ProductfamilynameWhoProductFamilyName > 0 && ProductPracticeAreaId > 0 && ProductSellingObstacleId> 0){
									
									//var NoIssueEleOptn = document.createElement("OPTION");	
										//selectbox.options.add(NoIssueEleOptn);
										//NoIssueEleOptn.text = "Not linked to an Issue.";
										//NoIssueEleOptn.value = "-1";
										//var optGroups = selectbox.getElementsByTagName("optgroup");
									//}															

									var splitGroupData = trim(data).split('!!');

									for(var z = 0; z < splitGroupData.length ; z++)
									{
										if(splitGroupData[z]!='')
										{
												var splitGroupName = splitGroupData[z].split("$$");
												for(x=0; x < splitGroupName.length; x++)
												{
													if(splitGroupName[x]!='')
													{		
														if(splitGroupName[x].indexOf('@@') == -1)	// OptGroup comes here
														{
															var combinationGroup = document.createElement("OPTGROUP");
															var GroupName = splitGroupName[x];
															combinationGroup.label = GroupName;
															selectbox.appendChild(combinationGroup); 
														}
														if(splitGroupName[x].indexOf('@@') != -1)	// Group Data comes here
														{
															var IssueData = splitGroupName[x].split("||");
															for(k = 0 ; k < IssueData.length; k++)
															{
																if(IssueData[k]!='')
																{
																	var IssueField = IssueData[k].split("@@");
																	var optn = document.createElement("OPTION");
																	optn.value = IssueField[0];
																	if(recent_added_issue_id!='' &&  recent_added_issue_id == IssueField[0] )
																	{
																		optn.selected = true;
																		showIssueDescription(IssueField[0]);
																	}
																	optn.appendChild(document.createTextNode(IssueField[1]));
																	combinationGroup.appendChild(optn);
																}
															}									
														}	
														/* If optgroup has child then add optgroup to Select box	*/
														if (combinationGroup.hasChildNodes()) 
														{ 
															selectbox.appendChild(combinationGroup); 
														}														
													}
												}
										}
										
									}							
								
								document.getElementById("ProductIssueDescription").value = "";
							}
							else
							{								
								var selectbox = document.getElementById("ProductIssueField");
								var ogl=selectbox.getElementsByTagName('optgroup');
								for (var i=ogl.length-1;i>=0;i--) selectbox.removeChild(ogl[i]);
								selectbox.options.length = 0;
								var blnkEleOptn = document.createElement("OPTION");	
									selectbox.options.add(blnkEleOptn);
									blnkEleOptn.text = "";
									blnkEleOptn.value = "0";
								//if(ProductfamilynameWhoProductFamilyName > 0 && ProductPracticeAreaId > 0 && ProductSellingObstacleId> 0){
								//var NoIssueEleOptn = document.createElement("OPTION");	
									//selectbox.options.add(NoIssueEleOptn);
									//NoIssueEleOptn.text = "Not linked to an Issue.";
									//NoIssueEleOptn.value = "-1";
								//}
								document.getElementById("ProductIssueDescription").value = "";
							}
						}, 
					onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
	//}
	return false;

}

/**
*	Function Name: 	showIssueBox
*	Purpose:		Purpose of this function is to display the popup screen to add/edit the Issue.
**/
function showIssueBox( add_issue_url)
{
	var issue_url = add_issue_url;
	var objVal = document.getElementById("ProductIssueField").value;
	var ProductfamilynameWhoProductFamilyName = document.getElementById("ProductfamilynameWhoProductFamilyName").value;
	var ProductPracticeAreaId = document.getElementById("ProductPracticeAreaId").value;
	var ProductSellingObstacleId = document.getElementById("ProductSellingObstacleId").value;
	
	//alert(ProductfamilynameWhoProductFamilyName +" || "+ ProductPracticeAreaId +" || "+ ProductSellingObstacleId); 
	
	if(ProductfamilynameWhoProductFamilyName > 0 && ProductPracticeAreaId > 0 && ProductSellingObstacleId > 0){
		if(objVal == 0 || objVal == -1)
		{
			issue_url = issue_url+"addissue/"+ProductfamilynameWhoProductFamilyName+"/"+ProductPracticeAreaId+"/"+ProductSellingObstacleId;
			openAddNewWindow('Add Issue', issue_url, 350, 500);
		}
		else if(objVal > 0)
		{
			issue_url = issue_url+"editissue/"+ProductfamilynameWhoProductFamilyName+"/"+ProductPracticeAreaId+"/"+ProductSellingObstacleId+"/"+objVal;
			openAddNewWindow('Edit Issue', issue_url, 350, 500);
		}
	}
	return false;
}

/**
*	Function Name: 	showIssueDescription
*	Purpose:		Purpose of this function is to display the description of selected Issue.
**/
function showIssueDescription(objVal)
{
	if(document.getElementById("errProductIssue")){
		document.getElementById("errProductIssue").style.display = "none";
	}

	if(objVal > 0){
		
		document.getElementById("ProductIssueAddIcon").style.display = "none";
		document.getElementById("ProductIssueEditIcon").style.display = "block";
		
		issue_url = document.getElementById("add_issue_url").value;
		issue_url = issue_url+"getissuedetail/"+objVal;
	
		new Ajax.Request(issue_url,
					{method:'get', onSuccess: function(transport){
							var data = transport.responseText || "no response text";
							
							if(trim(data) !='')
							{
								document.getElementById("ProductIssueDescription").value = trim(data);
							}
							else
							{
								document.getElementById("ProductIssueDescription").value = "";
							}
						}, 
					onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
	}
	else{
			if(objVal == -1){
				document.getElementById("ProductIssueEditIcon").style.display = "none";
				document.getElementById("ProductIssueAddIcon").style.display = "block";
				document.getElementById("ProductIssueDescription").value = "";
			}
			else{
				document.getElementById("ProductIssueEditIcon").style.display = "none";
				document.getElementById("ProductIssueAddIcon").style.display = "block";
				document.getElementById("ProductIssueDescription").value = "";
			}
	}
	return false;
}


/**
*	Function Name: 	loadReply
*	Purpose:		Purpose of this function is to display all the replies posted against Insight.
**/
function loadReply(issueId, closePopupEvent)
{	
	issue_url = window.opener.document.getElementById("add_issue_url").value;
	issue_url = issue_url+"getreplies/"+issueId;

	new Ajax.Request(issue_url,
			{method:'get', onSuccess: function(transport){
					var data = transport.responseText || "no response text";
					
					if(trim(data) !='')
					{
						window.opener.document.getElementById("ProductDoAction_dummy_label").innerHTML = data;
						if(closePopupEvent == 'block')
						{
							window.close();
						}
					}
					else
					{
						window.opener.document.getElementById("ProductDoAction_dummy_label").innerHTML = "";
						if(closePopupEvent == 'block')
						{
							window.close();
						}
					}
				}, 
			onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
}

/**
*	Function Name: 	toggleDiv
*	Purpose:		Purpose of this function is to toggle(show/hide) divs.
**/
function toggleDiv(ElementDiv)
{
	//	Fetching Image URL.
	
	var hdnImageURL = document.getElementById("hdnImageURL").value;
	if(document.getElementById("Div"+ElementDiv).style.display == 'none')
	{
		document.getElementById("Div"+ElementDiv).style.display = 'block';
		document.getElementById("img"+ElementDiv).src = hdnImageURL+"/arrow-down.gif";
	}
	else
	{
		document.getElementById("Div"+ElementDiv).style.display = 'none';
		document.getElementById("img"+ElementDiv).src = hdnImageURL+"/arrow-up.gif";
	}
}

/**
*	Function Name: 	claimInsight
*	Purpose:		Purpose of this function is to take the take the ownership of any insight / claiming an Insight by SME.
**/
function claimInsight(claimURL, RedirectURL)
{	
	new Ajax.Request(claimURL,	{
		method:'get', 
		onSuccess: function(transport){
					var data = transport.responseText || "no response text";
					
					if(trim(data) !='' && trim(data) == 'success')
					{
						parent.parent.window.location.href = RedirectURL;						
					}
					else
					{
						
						return false;
					}
				}, 
			onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
}

/**
*	Function Name: 	legalqaclaimInsight
*	Purpose:		Purpose of this function is to take the take the ownership of any legal qa  / claiming an legalqa  by SME.
**/
function legalqaclaimInsight(claimURL, RedirectURL)
{	
	new Ajax.Request(claimURL,	{
		method:'get', 
		onSuccess: function(transport){
					var data = transport.responseText || "no response text";
					
					if(trim(data) !='' && trim(data) == 'success')
					{
						parent.parent.window.location.href = RedirectURL;						
					}
					else
					{
						
						return false;
					}
				}, 
			onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
}

/**
*	Function Name: 	claimInsight
*	Purpose:		Purpose of this function is to take the take the ownership of any insight / claiming an Insight by SME.
**/
function confirmDelegation(ConfirmURL, RedirectURL)
{	
	new Ajax.Request(ConfirmURL,	{
		method:'get', 
		onSuccess: function(transport){
					var data = transport.responseText || "no response text";
					
					if(trim(data) !='' && trim(data) == 'success')
					{
						parent.parent.window.location.href = RedirectURL;
					}
					else
					{						
						return false;
					}
				}, 
			onFailure: function(){ alert('Something went wrong, contact site administrator.') }
		});
}
/*
 * @author:sukhvir
 * @param {type} pageType
 * @returns {Boolean}
 * @functionality: new added function  validateCustomerLegalQA for validate fields
 */
function validateCustomerLegalQA(pageType)
{
	
	var hdnImageURL = document.getElementById("hdnImageURL").value;
	if(pageType == 'new')
	{
		/**
		*	Validate the object of divs.
		**/	
		if(document.getElementById('ProductInsightSummary') && document.getElementById('errProductCustomerPainPoints')){			
			//document.getElementById('errProductCustomerPainPoints').style.display = 'none';
			document.getElementById('errProductInsightSummary').style.display = 'none';
		}
		
		/**
		*	Validating Customer Feedback
		**/
		if(isEmpty(document.getElementById('ProductInsightSummary').value) || (document.getElementById('ProductInsightSummary').value == "Please enter the Customers Questions here (Paste it verbatim)") && document.getElementById('ProductInsightSummary').disabled == false)
		{
                   
			document.getElementById('errProductInsightSummary').style.display = 'block';			
			
			/**
			*	Opening collapsed divs if there values are not entered.
			**/
                  	if(document.getElementById('DivInsightSummary'))
			{       document.getElementById("imgInsightSummary").src = hdnImageURL+"/arrow-down.gif";
				document.getElementById('DivInsightSummary').style.display = 'block';
			}
			document.getElementById('ProductInsightSummary').focus();
			return false;
		}
        }
        else if(pageType == 'add')
	{
		/**
		*	Validate the object of divs.
		**/	
		if(document.getElementById('ProductInsightSummary')){			
			document.getElementById('errProductInsightSummary').style.display = 'none';			
		}
		
		/**
		*	Validating Customer Feedback
		**/
		/*if(isEmpty(document.getElementById('ProductInsightSummary').value) || (document.getElementById('ProductInsightSummary').value == "Please summarise the feedback (quoting verbatim where possible) -  this will help to ensure a speedy and effective response!") && document.getElementById('ProductInsightSummary').disabled == false)
		{

			document.getElementById('errProductInsightSummary').style.display = 'block';			
			
			/**
			*	Opening collapsed divs if there values are not entered.
			** /
			if(document.getElementById('DivInsightSummary') && document.getElementById('DivCustomerPainPoints'))
			{
				document.getElementById("imgInsightSummary").src = hdnImageURL+"/arrow-down.gif";
				document.getElementById("imgCustomerPainPoints").src = hdnImageURL+"/arrow-down.gif";

				document.getElementById('DivInsightSummary').style.display = 'block';
				document.getElementById('DivCustomerPainPoints').style.display = 'block';
			}
			document.getElementById('ProductInsightSummary').focus();
			return false;
		}*/
		/**
		*	Validating Customer Pain Points
		**/		
		/*else if(isEmpty(document.getElementById('ProductCustomerPainPoints').value) || (document.getElementById('ProductCustomerPainPoints').value == "Please indicate how important this feedback is to the customer/prospect and its specific impact on their regular activities and wider business.") )
		{
				/**
				*	Opening collapsed divs if there values are not entered.
				** /
				if(document.getElementById('DivInsightSummary') && document.getElementById('DivCustomerPainPoints'))
				{
					document.getElementById("imgInsightSummary").src = hdnImageURL+"/arrow-down.gif";
					document.getElementById("imgCustomerPainPoints").src = hdnImageURL+"/arrow-down.gif";

					document.getElementById('DivInsightSummary').style.display = 'block';
					document.getElementById('DivCustomerPainPoints').style.display = 'block';
				}

			if(document.getElementById('ProductCustomerPainPoints').getAttribute("ReadOnly") == 'readonly'){
			
				return true;
			}
			else
			{
				document.getElementById('errProductCustomerPainPoints').style.display = 'block';
				document.getElementById('ProductCustomerPainPoints').focus();
				return false;
			}
		}*/
		
	}
	
	if(document.getElementById('ProductIssueField') && document.getElementById('ProductfamilynameWhoProductFamilyName') && document.getElementById('ProductPracticeAreaId') && document.getElementById('ProductSellingObstacleId'))
	{
		if(document.getElementById('ProductIssueField').value == -1 && ((document.getElementById('ProductfamilynameWhoProductFamilyName').value == 0 && document.getElementById('ProductPracticeAreaId').value == 0 && document.getElementById('ProductSellingObstacleId').value == 0 )|| (document.getElementById('ProductfamilynameWhoProductFamilyName').value =="" && document.getElementById('ProductPracticeAreaId').value == "" && document.getElementById('ProductSellingObstacleId').value == ""))){
			document.getElementById("errProductIssue").style.display = "block";
			document.getElementById("errProductIssue").innerHTML = "Selected option not allowed if combination is blank.";
			return false;
		}		
	}

	/*
	*	Check that Current Status Field value should not be blank.
	*/
	if((current_user_role == 'S' || current_user_role == 'A' ) &&  document.getElementById('ProductInsightStatus').disabled == false && (document.getElementById("ProductInsightStatus").value == 0 ||  document.getElementById("ProductInsightStatus").value == ''))
	{
			document.getElementById("errCurrentStatus").style.display = "block";
			document.getElementById("errCurrentStatus").innerHTML = "<b>*Please change this value to reflect current status.</b>";
			return false;
	}


	/*if((current_user_role == 'S' || current_user_role == 'A' ) && document.getElementById('ProductIssueField').disabled == false && (document.getElementById("ProductIssueField").value == 0 ||  document.getElementById("ProductIssueField").value == ''))
	{
			document.getElementById("errProductIssue").style.display = "block";
			document.getElementById("errProductIssue").innerHTML = "<b>*Please create a new issue or select one from the drop-down menu; 'Issue' feature helps you link this feedback record to an already identified trend and improve future reporting.</b>";
			return false;
	}*/

	/**/
	if(document.getElementById("ProductInsightStatus")){
		var w = document.getElementById("ProductInsightStatus").selectedIndex;
		var selected_status = document.getElementById("ProductInsightStatus").options[w].text;
		var selected_status = selected_status.toLowerCase();
	}

	if((current_user_role == 'S' || current_user_role == 'A' ) && (selected_status == 'issue resolved' || selected_status == 'response2: issue - under review' || selected_status == 'response3: issue - out of scope' ||selected_status == 'response4: issue - on roadmap') &&  document.getElementById('ProductIssueField').value == 0)
	{
			document.getElementById("errProductIssue").style.display = "block";
			document.getElementById("errProductIssue").innerHTML = "<b>*Please create a new issue or select one from the drop-down menu; 'Issue' feature helps you link this feedback record to an already identified trend and improve future reporting.</b>";
			return false;
	}

	/*
	*	Force SME to change status value, if SME has entered into the insight..
	*/
	if(current_user_role == 'S' && document.getElementById("HdnOldCurrentStatusValue").value == document.getElementById("ProductInsightStatus").value && (document.getElementById("ProductInsightStatus").value != '' || document.getElementById("ProductInsightStatus").value != 0) && document.getElementById('ProductInsightStatus').disabled == false){
	var SiteURL = document.getElementById("SiteURL").value;
	 	GB_showCenter('', SiteURL+'/products/oldstatus',150,400);
		/*var UserOption = confirm("Record status has not been changed!\n Would you like to change it now?");
		if (UserOption == true)
		{
			return true;
		}
		else
		{
			return false;
		}*/
		//document.getElementById("errCurrentStatus").style.display = "block";
		//document.getElementById("errCurrentStatus").innerHTML = "<b>*Please change this value to update current status.</b>";
		return false;
	}
}