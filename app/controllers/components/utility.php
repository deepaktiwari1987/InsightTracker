<?php 

class UtilityComponent extends Object 
{     
	/**
	 * Define components.
	 * @var Array
	 */
	var $components = array("Session");
	
	/**
	 * This function is called before Controller::beforeFilter() function.
	 * @param Object $controller
	 * @param Array $settings
	 * @author Mohit Khurana
	 */
	function initialize(&$controller, $settings = array())
	{
		/**
		 * saving the controller reference for later use.
		 */
		$this->controller =& $controller;
		
		/*$this->MarketModel = ClassRegistry::init('Market');
		$this->ProductFamilyNamesModel = ClassRegistry::init('Productfamilyname');
		$this->PracticeAreaModel = ClassRegistry::init('Productfamilyname');*/
		
	}
	
	
   
    function returnStaticData($dataType = '')
    {
    	if(isset($dataType) && trim($dataType) == 'how_come')
    	{
	    	# Set How Come Array
	    	$returnData = array('Conversation'=>'Conversation','DirectMail'=>'Direct Mail');
    	}
    	elseif(isset($dataType) && trim($dataType) == 'product_family')
    	{
    		$returnData = array('Legal'=>'Legal','Tax'=>'Tax');
    	}
    	
    	return $returnData;

    }
    
    /**
     * This function uploads the file from temp file to new file location.
     * @param string $tempFileName
     * @param string $newFileName
     * @param string $fileExt
     * @return Upload result
     * @author Mohit Khurana
     */
    function uploadAttachment($tempFileName,$newFileName,$fileExt,$fileUploadPath)
	{
		#Set new file location.
		$newFileLocation = $fileUploadPath . "/". $newFileName;
		#Save file to new location.
		return copy($tempFileName, $newFileLocation);
	}
	
	function attachmentAllowedExtensions()
	{
		//return array('jpg','jpeg','gif','tif','bmp','png','doc','docx','xls','xlsx', 'txt', 'pdf', 'ppt', 'pptx');
		return array('aac', 'aob', 'avi', 'bmml', 'bmp', 'divx', 'doc', 'docx', 'dts', 'flv', 'gif', 'htm', 'html', 
						'ico', 'jpeg', 'jpg', 'm1a', 'm4a', 'm4b', 'mac', 'mp2', 'mp3', 'mp4', 'mpeg', 'mpg', 'mpp', 'msg', 
						'pdf', 'png', 'ppt', 'pptx', 'psd', 'ra', 'ram', 'rar', 'tif', 'tiff', 'txt', 'vsd', 'wav', 'wma', 
						'xls', 'xlsx', 'xml', 'zip', );		
	}
	
	function getErrorFlagReturnStatus($errFlag = 0)
	{
		if(isset($errFlag) && $errFlag == 1)
    		return false;
    	
    	return true;
    	
	}

	function getFirmArray($firmValue)
	{
		if(isset($firmValue) && trim($firmValue) != '')
		{
			$arrFirmValue = explode('(',$firmValue);
			
			$arrFirmValue[1] = str_replace(')','',$arrFirmValue[1]);
			
			return array(trim($arrFirmValue[0]),trim($arrFirmValue[1]));
		}
		else
			return array();
	}
	
	function getWhatComePrintValue($dbValue='')
	{
		if(isset($dbValue) && trim($dbValue) == 'DirectMail')
			return 'Direct Mail';
		elseif(isset($dbValue) && trim($dbValue) == 'Conversation')
			return 'Conversation';
	}
	function parseString($str)
	{
		  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', '“', '”', ' – ', '‘', '’', '	');
		  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '"', '"', ' - ', "'", "'", ' ');
		  return str_replace($a, $b, $str);
	}
}
?> 
