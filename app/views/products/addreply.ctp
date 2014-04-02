<?php
/*
* File Name :  addreply.ctp
* Developer :  Gaurav Saini
* @author LexisNexis Development Team
* Cake Version : 1.3.4 
* @copyright Copyright (c) 2010, LexisNexis
* Functionality / Description : The purpose of this file is to display a form to add comments to the application. 
*				This form contains textarea for comments and file control to upload any file as attachment.

*/
?>

<div id="" class = "hr-row">
		<!-- <div class="hr-row"  style="width:auto; overflow-x:auto; overflow-y:hidden;" ></div> -->
    <table  width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
	<div id="errProductAttachmentExtension" class="successmsg" style="display:<?php echo $successDivSave?>;">Record saved successfully.</div>
  <?php echo $form->create(null, array('type'=>'file','action'=>'addreply','id'=>'ReplyForm','name'=>'ReplyForm','onSubmit'=>'return blankfunc1( "ProductReplyText","ProductReplyText")')); ?>
  	<input type="hidden"  name="data[Product][insight_id]" value="<?php echo $insight_id; ?>" />  
  	<input type="hidden"  name="data[Product][user_id]" value="<?php echo $loginUserId; ?>" />  
 
		<tr>
			<td nowrap><b>Add Comment:<span class="red">*</span></b> </td>
		</tr>
		<tr>
			<td>
				<?php echo $form->input('reply_text',array('rows'=>'5','label'=>false,'class'=>'','div'=>false,'readonly'=>false, 'value'=>$reply_text,'style'=>'width:300px;','onKeyDown' => 'countEm();','onKeyUp'=>'countEm();maybeReset();' ));?>			
			<br/>
			<span><input id='charCnt' type="text" name="charCnt" value="200" size="2" readonly /></span> <font color="#6B6B6B">words left.</font>
			<span id="ProductReplyText_err1" class="hideElement errormsg">Enter Comment</span>
			</td>
		</tr>
		<tr>
			<td nowrap>&nbsp;</td>
		</tr>
		<tr>
			<td nowrap><b>Attach a File:</b> </td>
		</tr>
		<tr>
			<td>
			<?php echo  $form->file('attachment_name',array('size'=>'30')) ?>
			<div id="errProductAttachmentExtension" class="errormsg" style="display:<?php echo $errDivAttachment;?>;">Invalid Attachment.</div>
			<div id="errProductAttachmentSize" class="errormsg" style="display:<?php echo $errDivAttachmentSize?>;">Attachment size can not be more than 5 MB.</div>
			</td>
		</tr>	  
		<tr>
			<td nowrap="nowrap" align="center">
				  	
			</td>
		</tr>
		<tr>
			<td nowrap>&nbsp;</td>
		</tr>
		<tr>
			<td>				
			<div class="buttonrow" style="text-align:center !important;float:left;padding:0px !important;">			
			<input type="button" name="Clear_comment" value="Clear Comment" onClick = "javascript:ClearComment();" style="width:120px !important;"/>&nbsp;&nbsp;<input type="submit" name="submit" value="Add & Close Comment" id="add_submit" style="width:150px !important;"/><!-- <input type="button" name="Close" value="Close" id="add_cancel" onClick = "javascript:closechildwindow();"/>&nbsp;&nbsp; --></div>		
			</td>
		</tr>
<?php echo $form->end(); ?>

 </table>  
</div>  
<script>
var wordLimit = 200;
var holdText;
var disabledBox = false;

function countEm()
{
	var text1 = document.getElementById("ProductReplyText").value;
	if(text1 !='')
	{
	
		var numberOfWords = doCount(text1);

		if(numberOfWords > wordLimit)
		{		
			//replace all instances of one-or-more spaces with a single space
				var text2 = text1.replace(/\s+/g, ' ');

			//trim leading and tailing spaces
			while(text2.substring(0, 1) == ' ')
				text2 = text2.substring(1);
			while(text2.substring(text2.length-2, text2.length-1) == ' ')
				text2 = text2.substring(0,text2.length-1);
			var text3 = text2.split(' ');
			holdText = '';
			for(var i=0; i < wordLimit; i++)
			{
				holdText = holdText + text3[i] + ' ';
			}			
		}
		else
		{
			holdText = text1;
		}
		

		numberOfWords = doCount(holdText);
		var remainingWords = wordLimit - numberOfWords;
		if(remainingWords < 0)
		{
			remainingWords = 0;
		}
		document.getElementById("charCnt").value = remainingWords;

		if(numberOfWords >= wordLimit)
			disabledBox = true;
		else
			disabledBox = false;
		
	}	
}//end function

function doCount(textParam)
{
	//replace all instances of one-or-more spaces with a single space
	var text2 = textParam.replace(/\s+/g, ' ');

	//trim leading and tailing spaces
	while(text2.substring(0, 1) == ' ')
	text2 = text2.substring(1);
	while(text2.substring(text2.length-2, text2.length-1) == ' ')
	text2 = text2.substring(0,text2.length-1);

	var text3 = text2.split(' ');

	return text3.length;
}//end function

function maybeReset()
{
	if(disabledBox)
	{
	var currText = document.getElementById("ProductReplyText").value;
	var newLength = doCount(currText);

		//prevent user from adding words, but not taking them away
		if(newLength > wordLimit)
		{
			document.getElementById("ProductReplyText").value = holdText;
		}//end if
	}//end if
}//end function

function ClearComment()
{
	document.getElementById('ProductReplyText').value = '';
	document.getElementById('charCnt').value = 200;
}

try{
	window.onload = loadReply(<?php echo $insight_id; ?>, '<?php echo $successDivSave?>');
}
catch(e)
{}

</script>