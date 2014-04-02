var mobile_check = typeof(mobile_check) != 'undefined' ? mobile_check : '';
function redirectUrl(urlPath)
{
      window.location.href = urlPath;
}

function isEmpty(textvalue)
{
	textvalue = textvalue.replace(/\s/g,"");
	if(textvalue.length > 0)
		return false;
	else
		return true;
}

// Removes leading whitespaces
function LTrim( value ) {
	
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
	
}

// Removes ending whitespaces
function RTrim( value ) {
	
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
	
}

// Removes leading and ending whitespaces
function trim( value ) {
	
	return LTrim(RTrim(value));
	
}

// For go back to history.
function goback(){
	history.back();
}



function openAddNewWindow(windowTitle,urlPath,windowHeight,windowWidth)
{
	/*GB_myShow = function(caption, url, height, width, is_reload_on_close) {
		var options = {
			caption: caption,
			height: height || 500,
			width: width || 500,
			fullscreen: false,
			overlay_click_close: false,
			show_loading: true,
			reload_on_close: is_reload_on_close || false
		}
		var win = new GB_Window(options);
	
		return win.show(url);
	}
	
	GB_myShow(windowTitle,urlPath,windowHeight,windowWidth,true);*/

	GB_showCenter(windowTitle,urlPath,windowHeight,windowWidth);
}

