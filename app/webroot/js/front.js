function redirectUrl(urlPath)
{
	window.location.href = urlPath;	
}

function changeInsightType(typeValue,controllerName,actionName,siteUrl)
{
	urlPath = siteUrl + '/' + controllerName + '/' + actionName;
	
	window.location.href = urlPath;	
	
}

function checkSearchOptions(SearchInsightId, radioCheckInsightType) {
	//alert($(radioCheckInsightType).checked));
	var InsightId = trim(document.getElementById(SearchInsightId).value);
	var checked = document.getElementById(radioCheckInsightType).checked;

	if(InsightId == "" && checked == false) {
			alert('Enter insight id or select insight type to search');
			return false;
	}
	return true;
}

function showSearchForm() {
	if($('what_insight_type1').checked == true && $('searchInsightForm').hasClassName('hideElement')) {
		$('searchInsightForm').removeClassName('hideElement');
		$('searchInsightForm').addClassName('showElement');
	}
}