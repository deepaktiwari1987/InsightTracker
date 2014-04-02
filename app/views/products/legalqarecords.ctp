<?php
/*
 * File Name :  legalqarecords.ctp
 * Developer :  Sukhvir Singh
 * @author LexisNexis Development Team
 * Cake Version : 1.3.4 
 * @copyright Copyright (c) 2010, LexisNexis
 * Functionality / Description : The purpose of this file is to display a form to edit an Legal Q&A available in the application.
 * @Modified By: Sukhvir Singh
 *  *Following  changes
  a. How did this feedback come about rename to “Origin of question”
  b. Content Type removed
  d. Contact Name /Role  divided into two fields called Contact Name and Role only contact name required on this form
  e. Suggested Next Steps to be renamed with ‘Required actions / suggested next steps’ removed
  f: Competitor Not required removed
  g: Selling Obstacles Not required removed
  h: What did the customer/prospect say to be renamed to “Question”
  i: How does this impact their activities/business? Not required removed
 */
?>

<?php
if ($this->Session->check('current_user_role')) {
    $current_user_role = $this->Session->read('current_user_role');
    $current_user_name = $this->Session->read('current_user_name');
    $current_user_id = $this->Session->read('current_user_id');
}
?>
<style>
    .questionMark {
        position:relative;
    }
    .toolTip {
        position:absolute;
        left:26px; /*Moves it to the right beside the question mark*/
        top:20;
        display:none;
        /*The attributes below make it look pretty*/
        width:700px;
        padding:5px;
        border:1px solid #ffffff;
        background-color:#eeeeee;
        font:10px/12px Arial, Helvetica, sans-serif;
    }

</style>
<div id="textcontainer">
    <?php echo $form->create('Product', array('type' => 'file', 'action' => '/legalqarecords/' . $id, 'id' => 'productInsightForm', 'name' => 'productInsightForm', 'onsubmit' => 'return validateCustomerLegalQA("add");')); ?>



    <div class="hr-row">
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
            <div id="errProductAttachmentExtension" class="successmsg" style="display:<?php echo $successDivSave ?>;">Record saved successfully.</div>
            <tr>
                <td colspan="6" align="center">&nbsp;</td>
            </tr>
            <tr>
                <td width="20%" valign="bottom"><h3>Feedback id: &nbsp;<?php echo $id; ?> <?php if ($flag_mobile == "Y") { ?>	<img src="<?php echo IMAGE_URL ?>/mobile_icon.png" height="30" alt="Feedback added by mobile device" title="Feedback added by mobile device"/>		<?php } ?></h3></td>
                <td width="15%" valign="bottom"><h3>Created by: &nbsp;<?php echo $created_by; ?></h3></td>
                <td width="25%" valign="bottom"><h3>Date: &nbsp;<?php echo $created_date; ?></h3></td>
                <td colspan=3 align="right" valign="bottom"><?php if ($current_user_role != ACCESS_EDIT_ROLE) { ?>
                        [ <a href="javascript:void(0);" onclick="GB_showCenter('Contact Moderator', '<?php echo SITE_URL ?>/products/legalqacontact/<?php echo $id; ?>', 350, 500);">Contact Moderator</a> ]
                    <?php } ?></td>
            <input type="hidden" name="data[Product][id]" value="<?php echo $id; ?>" >
            <input type="hidden" name="data[Product][user_id]" value="<?php echo $created_by; ?>" >
            <input type="hidden" name="data[Product][date_submitted]" value="<?php echo $submitted_date; ?>" >
            <input type="hidden" name="Hdn_product_family_id" id="Hdn_product_family_id" value="<?php echo $selected_product_family_id; ?>" >
            <input type="hidden" name="Hdn_relates_practice_area_label" id="Hdn_relates_practice_area_label" value="<?php echo $relates_practice_area_label; ?>" >
            <input type="hidden" name="Hdn_currentSellingObstacle" id="Hdn_currentSellingObstacle" value="<?php echo $currentSellingObstacle; ?>" >
            <input type="hidden" name="HdnInsightOwner" id="HdnInsightOwner" value="" >
            <input type="hidden" name="HdnOldCurrentStatusValue" id="HdnOldCurrentStatusValue" value="<?php echo $current_status_label; ?>" >
            <input type="hidden" name="SiteURL" id="SiteURL" value="<?php echo SITE_URL; ?>" >
            </tr>
            <!--<tr>
                    <td colspan="6">&nbsp;</td>
            </tr>-->
        </table>
        <table width="100%" border="0" cellspacing="3" cellpadding="0">

            <tr>
                <td>Origin of Question?:</td>
                <td>
                    <label>
                        <?php if ($edit_flag == "") { ?>
                            <span id='ProductWhatHowCome_dummy_label'><?php echo $form->input('what_how_come', array('label' => false, 'options' => $arrHowCome, 'div' => false, 'disabled' => true, 'selected' => $what_how_come, 'style' => 'width:210px')); ?></span>
                            <?php //echo $form->input('what_how_come', array('label'=>false,'options' => $arrHowCome,'div'=>false,'style'=>'display:none;', 'selected' => $what_how_come)); ?>
                        <?php } else { ?>
                            <?php echo $form->input('what_how_come', array('label' => false, 'options' => $arrHowCome, 'div' => false, 'disabled' => true)); ?>
                        <?php } ?>
                        <?php //echo $form->hidden('what_how_come'); ?>
                    </label>
                </td>

                <td>Organisation Name:</td>
                <td>
                    <?php if (isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) { ?>
                        <span id='FirmWhatFirmName_dummy_label'><?php echo $form->input('Firm.what_firm_name', array('label' => false, 'div' => false, 'disabled' => true, 'class' => 'search-input-new readonlycls', 'value' => $currentOrganisationid)); ?></span>
                        <?php //echo $form->input('Firm.what_firm_name', array('label'=>false,'div'=>false,'style'=>'display:none' , 'class'=>'search-input-new','value' => $currentOrganisationid)); ?>	
                    <?php } else { ?>
                        <?php //echo $ajax->autoComplete('Firm.what_firm_name', '/firms/autoCompleteFirms', array('minChars' => 3,'class'=>'search-input-new readonlycls','readonly'=>'readonly'))?>
                        <?php echo $form->input('Firm.what_firm_name', array('label' => false, 'div' => false, 'disabled' => true, 'class' => 'search-input-new readonlycls', 'readonly' => 'readonly', 'value' => $currentOrganisationid)); ?>

                    <?php } ?>
                    <script type="text/javascript">
                        new Autocomplete('FirmWhatFirmName', {
                            serviceUrl: '<?php echo SITE_URL ?>/firms/autoComplete',
                            minChars: 2,
                            maxHeight: 400,
                            width: 400,
                            deferRequestBy: 100,
                            // callback function:
                            onSelect: function(value, data) {
                                //alert('You selected: ' + value + ', ' + data);
                            }
                        });
                    </script>
                </td>
            </tr>
        </table>
    </div>
    <?php
    $issue_url = SITE_URL . '/products/getissue';
    $add_issue_url = SITE_URL . '/products/';
    ?>
    <div class="hr-row">
        <table width="" border="0" cellspacing="5" cellpadding="0" class="tbl_width1">
            <tr>
                <td colspan="4"><h3>What is the feedback about?</h3></td>
            </tr>

            <tr>
                <td class="last_td">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td>Product Family Name:<br />
                                <div>
                                    <?php //echo $ajax->autoComplete('Productfamilyname.product_family_id', '/productfamilynames/autoCompleteProductFamilyNames', array('minChars' => 3,'class'=>'search-input-new readonlycls','readonly'=>'readonly'))?>
                                    <?php //if(isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) { ?>
                                    <?php if ($edit_flag == "") { ?>
                                        <span id='ProductfamilynameProductFamilyName_dummy_label'><?php echo $form->input('Productfamilyname.who_product_family_name', array('label' => false, 'div' => false, 'disabled' => true, 'options' => $arrProductFamilynames, 'value' => $selected_product_family_id, 'onchange' => 'javascript:document.getElementById("prodfamilyname").value=this.value;document.getElementById("ProductnameWhoProductName").value="";getIssueForInsight(ProductfamilynameWhoProductFamilyName, ProductPracticeAreaId, ProductSellingObstacleId, issue_url);')); ?></span>
                                        <?php //echo $form->input('Productfamilyname.who_product_family_name', array('label'=>false,'div'=>false,'options' => $arrProductFamilynames,'style'=>'display:none;','onchange'=>'javascript:document.getElementById("prodfamilyname").value=this.value;document.getElementById("ProductnameWhoProductName").value="";')); ?>

                                    <?php } else { ?>			
                                        <?php echo $form->input('Productfamilyname.who_product_family_name', array('label' => false, 'options' => $arrProductFamilynames, 'div' => false, 'disabled' => true, 'class' => 'readonlycls', 'value' => $selected_product_family_id, 'readonly' => 'readonly', 'onchange' => 'javascript:document.getElementById("prodfamilyname").value=this.value;document.getElementById("ProductnameWhoProductName").value="";getIssueForInsight(ProductfamilynameWhoProductFamilyName, ProductPracticeAreaId, ProductSellingObstacleId, issue_url);')); ?>
                                    <?php } ?>
                                    <?php //echo $form->hidden('product_family_id'); ?>
                                    <input type="hidden" name="prodfamilyname" id ="prodfamilyname" value="">
                                    <input type="hidden" name="issue_url" id ="issue_url" value="<?php echo $issue_url; ?>">
                                    <input type="hidden" name="add_issue_url" id ="add_issue_url" value="<?php echo $add_issue_url; ?>">
                                    <input type="hidden" name="recent_added_issue_id" id ="recent_added_issue_id" value="<?php echo $recent_added_issue_id; ?>">
                                </div>
                            </td>
                            <td >Product Name:<br />
                                <div>

                                    <?php //if(isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) { ?>
                                    <?php if ($edit_flag == "") { ?>
                                        <span id='ProductnameWhoProductName_dummy_label'>						
                                            <?php //echo $ajax->autoComplete('Productname.who_product_name', '/productnames/autoCompleteProductNames', array('minChars' => 3,'class'=>'search-input-new','label'=>false,'div'=>false,'disabled'=>true,'value'=>$selected_product_id ))?>
                                            <?php echo $form->input('Productname.who_product_name', array('label' => false, 'class' => 'search-input-new readonlycls', 'div' => false, 'disabled' => true, 'value' => $selected_product_id, 'onfocus' => 'javascript:document.getElementById("prodfamilyname").value=document.getElementById("ProductfamilynameWhoProductFamilyName").value;')); ?>
                                        </span>
                                        <?php //echo $ajax->autoComplete('Productname.who_product_name', '/productnames/autoCompleteProductNames', array('minChars' => 3,'class'=>'search-input-new','label'=>false,'div'=>false,'style'=>'display:none','value'=>$selected_product_id ))?>

                                    <?php } else { ?>
                                        <?php //echo $ajax->autoComplete('Productfamilyname.who_product_name', '/productnames/autoCompleteProductNames', array('minChars' => 3,'class'=>'search-input-new readonlycls','readonly'=>'readonly'))?>
                                        <?php echo $form->input('Productname.who_product_name', array('label' => false, 'class' => 'search-input-new readonlycls', 'readonly' => 'readonly', 'value' => $selected_product_id, 'onfocus' => 'javascript:document.getElementById("prodfamilyname").value=document.getElementById("ProductfamilynameWhoProductFamilyName").value;')); ?>
                                    <?php } ?>
                                    <script type="text/javascript">
                                        new Autocomplete('ProductnameWhoProductName', {
                                            serviceUrl: '<?php echo SITE_URL ?>/productnames/autoComplete',
                                            minChars: 2,
                                            maxHeight: 400,
                                            width: 450,
                                            deferRequestBy: 100,
                                            // callback function:
                                            onSelect: function(value, data) {
                                                //alert('You selected: ' + value + ', ' + data);
                                            }
                                        });
                                    </script>
                                </div></td>
                        </tr>

                        <tr>
                            <td  valign="top">Practice Area:<br />
                                <div>
                                    <?php if ($edit_flag == "") { ?>
                                        <span id='ProductPracticeAreaId_dummy_label'><?php echo $form->input('practice_area_id_dummy', array('label' => false, 'options' => $arrPracticeArea, 'div' => false, 'disabled' => true, 'selected' => $relates_practice_area_label)); ?></span>
                                        <?php echo $form->input('practice_area_id', array('label' => false, 'options' => $arrPracticeArea, 'div' => false, 'style' => 'display:none;', 'selected' => $relates_practice_area_label, 'onchange' => 'javascript:getIssueForInsight(ProductfamilynameWhoProductFamilyName, ProductPracticeAreaId, ProductSellingObstacleId, issue_url);')); ?>
                                    <?php } else { ?>
                                        <?php echo $form->input('practice_area_id', array('label' => false, 'options' => $arrPracticeArea, 'div' => false, 'onchange' => 'javascript:getIssueForInsight(ProductfamilynameWhoProductFamilyName, ProductPracticeAreaId, ProductSellingObstacleId, issue_url);')); ?>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>

            </tr>
        </table>
    </div>

    <div class="hr-row">	
        <h3 style="padding: 5px 0px 10px 10px;cursor:pointer;" onclick="toggleDiv('InsightSummary');"><img id="imgInsightSummary" src="<?php echo IMAGE_URL ?>/arrow-down.gif"/>   

            Question ? </h3>
        <div id="DivInsightSummary" style="display:block;padding-left:10px !important;">
            <?php if (isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) { ?>
                <span id='ProductInsightSummary_dummy_label'>
                    <?php echo $form->input('insight_summary', array('rows' => '7', 'label' => false, 'div' => false, 'readonly' => true, 'class' => 'summary', 'value' => $insight_summary)); ?>
                </span>
            <?php } else { ?>
                <?php echo $form->input('insight_summary', array('rows' => '7', 'label' => false, 'class' => 'summary', 'div' => false, 'readonly' => true)); ?></textarea>
            <?php } ?>
            <div id="errProductInsightSummary" class="errormsg" style="display:<?php echo $errDivProductInsightSummary ?>;">Required.</div>
        </div>

    </div>

    <div class="hr-row">
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
            <tr>
                <td><h3>Attachment</h3></td>
            </tr>

            <tr style="height:5px;">
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr><?php
                            if (isset($attachment_real_name) && trim($attachment_real_name) != "") {
                                $fileattached = '<a id="attachment_namelink" href="javascript:open_attachment(\'' . SITE_URL . '/products/downloadfile/' . $attachment_name . '\');">' . $attachment_real_name . '</a>
											<a class="attachment_removelink" id="attachment_removelink" style="display:none;" href="#" onclick="javscript: remove_attachment(\'' . SITE_URL . '/products/remove_attachment/' . $id . '/' . base64_encode($attachment_name) . '\'); return false;"><img align="absmiddle" src="' . SITE_URL . '/img/remove.gif"/></a><script>var attachmentfound = true;</script>';
                            } else {
                                $fileattached = "No attachment";
                            }
                            ?>
                            <td colspan="2" align="left">Filename:&nbsp;&nbsp;<?php print $fileattached; ?></td>
                        </tr>
                        <tr>

                            <td>
                                <label>
                                    <?php if ($edit_flag == "") { ?>
                                            <?php echo $form->hidden('ProductAttachment.old_attachment_name', array('value' => $attachment_name)); ?>
                                        <span id="attach_file" style="display:none;">
    <?php echo $form->file('ProductAttachment.attachment_name', array('size' => '30')) ?>
                                            <div id="errProductAttachmentExtension" class="errormsg" style="display:<?php echo $errDivAttachment ?>;">Invalid Attachment.</div>
                                            <div id="errProductAttachmentSize" class="errormsg" style="display:<?php echo $errDivAttachmentSize ?>;">Attachment size can not be more than 5 MB.</div>
                                        </span>	
                                    <?php } else { ?>
    <?php echo $form->file('ProductAttachment.attachment_name', array('size' => '30')) ?>
                                        <div id="errProductAttachmentExtension" class="errormsg" style="display:<?php echo $errDivAttachment ?>;">Invalid Attachment.</div>
                                        <div id="errProductAttachmentSize" class="errormsg" style="display:<?php echo $errDivAttachmentSize ?>;">Attachment size can not be more than 5 MB.</div>
<?php } ?>						
                                </label>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="hr-row">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl_width">

            <tr>		
                <?php
                if (isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) {
                    ?>
    <?php echo $form->hidden('insight_status_changed', array('value' => 1, 'disabled' => TRUE)); ?>
                    <td valign="top" width="10%" style="padding-right:10px;">

                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="200px" style="padding-right:0px !important;"><div style="display:block;">Current Status: <span class="red">*</span></div> 	<?php echo $form->input('insight_status', array('label' => false, 'options' => $arrCurrentStatus, 'div' => false, 'selected' => $current_status_label, 'disabled' => TRUE, 'onchange' => 'show_delegated_to("Product");', 'style' => 'width:150px;float:left')); ?> </td>
                                <td width="50px" valign="bottom" style="padding-right:0px !important;"><div onmouseover="document.getElementById('tt1').style.display = 'block'" onmouseout="document.getElementById('tt1').style.display = 'none'" class="questionMark"><img src="<?php echo IMAGE_URL ?>/information.png"/><div id="tt1" class="toolTip">
                                            <table width="100%" border="0" cellspacing="5" cellpadding="2">
                                                <tr>
                                                    <td style="white-space: nowrap">Awaiting Delegation</td>
                                                    <td style="white-space: nowrap">Waiting for moderator to delegate feedback record to SME</td>
                                                </tr>
                                                <tr>
                                                    <td>Considering Response</td>
                                                    <td>SME needs more time/information to provide an informed response</td>
                                                </tr>
                                                <tr>
                                                    <td>Delegated</td>
                                                    <td>Delegated to SME to provide a response</td>
                                                </tr>
                                                <tr>
                                                    <td>Issue Resolved</td>
                                                    <td style="white-space: nowrap">Linked to an issue that has now been resolved</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response1: Query Resolved</td>
                                                    <td style="white-space: nowrap">SME has responded to a query</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response2: Issue - Under Review</td>
                                                    <td>Linked to an issue currently under evaluation</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response3: Issue - Out of Scope</td>
                                                    <td style="white-space: nowrap">Linked to an issue currently out of scope for development (will continue to be monitored)</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response4: Issue - On Roadmap</td>
                                                    <td style="white-space: nowrap">Linked to an issue currently on the development roadmap</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:100px;white-space: nowrap">To Be Deleted</td>
                                                    <td>Feedback record scheduled for deletion</td>
                                                </tr>
                                            </table>

                                        </div></div></td>
                            </tr>
                        </table>	


                        <br/><div id="errCurrentStatus" class="errormsg" style="display:none;"></div>
                    </td>
                    <td width="20%" valign="top" style="margin-left: 15px;" ><div id="delegated_user" style="display:block;">Delegated to:</div> <?php echo $form->input('deligated_to', array('label' => false, 'options' => $arrDelegatedTo, 'div' => false, 'selected' => $deligated_to_selected, 'disabled' => TRUE)); ?></td>
<?php } else { ?>
                    <td  valign="top" width="10%" style="padding-right:90px;">

                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="200px" style="padding-right:0px !important;"><?php echo $form->hidden('insight_status_changed', array('value' => 1, 'disabled' => TRUE)); ?>
                                    <div style="display:block;">Current Status: <span class="red">*</span></div><?php echo $form->input('insight_status', array('label' => false, 'options' => $arrCurrentStatus, 'div' => false, 'selected' => $current_status_label, 'disabled' => TRUE, 'onchange' => 'show_delegated_to("Product");', 'style' => 'width:150px;float:left')); ?></td>
                                <td width="50px" valign="bottom" style="padding-right:0px !important;"><div onmouseover="document.getElementById('tt1').style.display = 'block'" onmouseout="document.getElementById('tt1').style.display = 'none'" class="questionMark"><img src="<?php echo IMAGE_URL ?>/information.png"/><div id="tt1" class="toolTip">
                                            <table width="100%" border="0" cellspacing="5" cellpadding="2">
                                                <tr>
                                                    <td style="white-space: nowrap">Awaiting Delegation</td>
                                                    <td style="white-space: nowrap">Waiting for moderator to delegate feedback record to SME</td>
                                                </tr>
                                                <tr>
                                                    <td>Considering Response</td>
                                                    <td>SME needs more time/information to provide an informed response</td>
                                                </tr>
                                                <tr>
                                                    <td>Delegated</td>
                                                    <td>Delegated to SME to provide a response</td>
                                                </tr>
                                                <tr>
                                                    <td>Issue Resolved</td>
                                                    <td style="white-space: nowrap">Linked to an issue that has now been resolved</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response1: Query Resolved</td>
                                                    <td style="white-space: nowrap">SME has responded to a query</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response2: Issue - Under Review</td>
                                                    <td>Linked to an issue currently under evaluation</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response3: Issue - Out of Scope</td>
                                                    <td style="white-space: nowrap">Linked to an issue currently out of scope for development (will continue to be monitored)</td>
                                                </tr>
                                                <tr>
                                                    <td style="white-space: nowrap">Response4: Issue - On Roadmap</td>
                                                    <td style="white-space: nowrap">Linked to an issue currently on the development roadmap</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:100px;white-space: nowrap">To Be Deleted</td>
                                                    <td>Feedback record scheduled for deletion</td>
                                                </tr>
                                            </table>


                                        </div></div></td>
                            </tr>
                        </table>



                        <br/><div id="errCurrentStatus" class="errormsg" style="display:none;"></div>
                    </td>
                    <td width="30%" valign="top" style="padding-right:10px;margin-left: 15px;" >
                        <?php if (isset($current_user_role) && $current_user_role == SUBJECT_MATTER_EXPERT) { ?>
                                <!-- <input type="hidden" name="data[Product][deligated_to]" id="HdnDelegatedTo" value=""/> -->
                    <?php } ?>
                        <div id="delegated_user" style="display:block;">Delegated to:</div> <?php echo $form->input('deligated_to', array('label' => false, 'options' => $arrDelegatedTo, 'div' => false, 'disabled' => TRUE, 'selected' => $deligated_to_selected)); ?></td>
<?php } ?>


                <td  valign="top" style="padding-right:10px;"><div style="display:block;">Market Segment: </div>
                    <?php if (isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) { ?>
                        <span id='ProductMarketId_dummy_label'><?php echo $form->input('market_id', array('label' => false, 'options' => $arrWhoMarket, 'div' => false, 'disabled' => true, 'selected' => $selected_market_id)); ?></span>
                        <?php //echo $form->input('market_id', array('label'=>false,'options' => $arrWhoMarket,'div'=>false, 'class' => 'selectBox1','style'=>'display:none', 'selected' => $selected_market_id)); ?>	
                    <?php } else { ?>
                        <?php echo $form->input('market_id', array('label' => false, 'options' => $arrWhoMarket, 'div' => false, 'disabled' => TRUE, 'class' => 'selectBox1 readonlycls', 'readonly' => 'readonly', 'selected' => $selected_market_id)); ?>
<?php } ?>
                </td>
            </tr>
        </table>
    </div>


    <div class="hr-row">
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
            <tr>
                <td>
                    <input type="hidden" id='HdnchildPopup' name='HdnchildPopup'/>
                    <div id="CommentHeading"><h3 style="float:left;display:block;">Add Comment</h3></div><span style="float:left;width:250px;display:none;" id="spanReply">
                        <a href="javascript:void(0);" onclick="javascript:openPopupWindow('<?php echo $add_issue_url ?>', '<?php echo $id; ?>', '<?php echo $this->Session->read('current_user_id'); ?>');"><b>Add Comment</b></a></span><div class="clear"></div></td>
                <?php //if(isset($current_user_role) && $delegation_confirmed == 'Y') { ?>
<!--                        <td><h3 style="float:left;display:block;">Response sent to Customer</h3></td>-->
                <?php //} ?>
                <?php //if(isset($current_user_role) && $current_user_role == ACCESS_EDIT_ROLE) {  ?>
                <td><h3 style="float:left;display:block;">Response sent to Customer</h3></td>
<?php //}  ?>
            </tr>
            <tr>
                <td width="50%" valign="top">
<?php if ($edit_flag == "") { ?>

                        <div id='ProductDoAction_dummy_label' style="width:477px;height:150px;overflow-x:hidden;overflow-y:auto;">
                            <table cellpadding="0" cellspacing="0" border="0" style="width:100% !important;#width:96% !important;">
                                <?php
                                if (count($productReplyValue) > 0) {
                                    $row = 1;
                                    foreach ($productReplyValue as $key => $value) {
                                        $row++;
                                        if ($row % 2) {
                                            $bgcolor = "#FFF";
                                        } else {
                                            $bgcolor = "#E2E2E2";
                                        }

                                        echo "<tr><td style='padding:5px;background:" . $bgcolor . "'>";
                                        $DateMsg = "";
                                        $CommentAddedBy = ($value['Pilotgroup']['name'] != '') ? $value['Pilotgroup']['name'] : 'Historical Data';
                                        echo "<b>" . $CommentAddedBy . ":</b><br/>";
                                        echo nl2br(UtilityComponent::parseString(trim($value['Replyresponse']['reply_text']))) . "<br/>";
                                        if ($value['Replyresponse']['attachment_real_name'] != '') {
                                            echo '<a id="attachment_namelink" href="javascript:open_attachment(\'' . SITE_URL . '/products/downloadfile/' . $value['Replyresponse']['attachment_name'] . '\');">' . $value['Replyresponse']['attachment_real_name'] . '</a><br/>';
                                        }
                                        $DateMsgDate = date('dS M Y', strtotime($value['Replyresponse']['date_submitted']));
                                        $tempDateTime = substr($value['Replyresponse']['date_submitted'], 11, 8);
                                        $time_hour = substr($tempDateTime, 0, 2);
                                        $time_minute = substr($tempDateTime, 3, 2);
                                        $time_seconds = substr($tempDateTime, 6, 2);
                                        $time = date("g:i A", mktime($time_hour, $time_minute, $time_seconds));
                                        $DateMsg = $time . ' ' . $DateMsgDate;
                                        echo $DateMsg;
                                        echo "</td></tr>";
                                    }
                                }
                                ?>
                            </table>
                        </div>
                        <?php //echo $form->input('do_action',array('rows'=>'7','label'=>false,'div'=>false,'style'=>'display:none;width:477px;')); ?>
                    <?php } else { ?>

                        <?php //echo $form->input('do_action',array('rows'=>'7','label'=>false,'div'=>false,'style'=>'width:477px;')); ?>				
                    <?php } ?>				
                </td>
                <?php if (isset($current_user_role) && $current_user_role == SUBJECT_MATTER_EXPERT && $current_user_id == $deligated_to_selected && $deligated_to_selected > 0) { //if(isset($current_user_role) && $current_user_role == SUBJECT_MATTER_EXPERT) { ?>
                    <td width="50%" valign="top">
                        <span id='ProductResponseSentToCustomer_dummy_label' style="display: block;"><?php echo $form->input('respons_sent_to_customer', array('rows' => '7', 'label' => false, 'div' => false, 'value' => $respons_sent_to_customer, 'style' => 'width:477px;height:150px;')); ?></span>
                    </td>
                <?php } else { ?>

                    <td width="50%" valign="top">
                        <span id='ProductResponseSentToCustomer_dummy_label' style="display: block;"><?php echo $form->input('respons_sent_to_customer', array('rows' => '7', 'label' => false, 'div' => false, 'readonly' => true, 'value' => $respons_sent_to_customer, 'style' => 'width:477px;height:150px;')); ?></span>
                    </td>
                <?php } ?>
            </tr>
        </table>
    </div>	

    <div class="history_link buttonrow"><a href="<?php print $backUrl; ?>">Back to search results page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php print SITE_URL; ?>/">Home</a></div>
    <div class="buttonrow">

        <?php
        /**
         * 	If Logged in User is SME then he will be presented a confirmation popup.
         * */
        if ($current_user_role == SUBJECT_MATTER_EXPERT) {

            if ($delegation_confirmed == 'Y' && $current_user_id == $deligated_to_selected && $deligated_to_selected > 0) {

                /**
                 * 	If Delegated SME has confirmed the delegation and currently logged in user id and delegated user id are same then no confirmation popup will be display and reply button will not be visible to logged in SME.
                 * */
                ?>
                <script>
                                    document.getElementById('HdnInsightOwner').value = 'Y';
                </script>
                <!-- <input name="cancel" type="button" id="EditBtn" value="Reply" onclick="activateEditFields('ProductContentTypeId', 'ProductPracticeAreaId', 'ProductInsightSummary', 'ProductDoAction', 'ProductIssueIcon', 'submitProductInsight', 'Product', 'Competitorname', 'Productfamilyname', 'Productname', 'Firm');" />&nbsp;	-->		

            <?php
            } elseif ($delegation_confirmed == 'N' && $current_user_id == $deligated_to_selected && $deligated_to_selected > 0) {

                /**
                 * 	If Delegated SME has not confirmed the delegation but currently logged in user id and delegated user id are same then confirmation popup will be display.
                 * */
                ?>		

                <input name="cancel" type="button" value="Reply" id="EditBtn" onclick="GB_showCenter('Subject Matter Expert (SME) validation', '<?php echo SITE_URL . "/products/legalqaconfirmationpage/" . $id . "/" . $deligated_to_selected; ?>', 350, 500);" />&nbsp;	

            <?php
            } elseif ($current_user_id != $deligated_to_selected && $deligated_to_selected > 0) {
//			elseif (($delegation_confirmed == 'N' && $current_user_id == $deligated_to_selected && $deligated_to_selected > 0) || ($current_user_id != $deligated_to_selected  && $deligated_to_selected > 0)){ 

                /**
                 * 	If logged in user's id does not match with delegated SME's id then logged in user will only be able to add comments only.
                 * */
                ?>		

                <input name="cancel" type="button" value="Reply" id="EditBtn" onclick="activateLegalqaEditFields('ProductContentTypeId', 'ProductPracticeAreaId', 'ProductInsightSummary', 'ProductDoAction', 'ProductIssueIcon', 'submitProductInsight', 'Product', 'Competitorname', 'Productfamilyname', 'Productname', 'Firm'), HideReplyBtn();" />&nbsp;	

            <?php
            } else {
                /**
                 * 	For rest of the cases SME have to claim the Insight.
                 * */
                ?>

                <input name="cancel" type="button" value="Reply" id="EditBtn" onclick="GB_showCenter('Subject Matter Expert (SME) validation', '<?php echo SITE_URL . "/products/legalqaclaiminsight/" . $id; ?>', 350, 500);" />&nbsp;	
                <?php
            }
        } else {
            /**
             * 	If Logged in user is either Contributor or Moderator then no confirmation popup will be displayed.
             * */
            ?>

            <input name="cancel" type="button" id="ReplyBtn" value="Reply" onclick="activateLegalqaEditFields('ProductContentTypeId', 'ProductPracticeAreaId', 'ProductInsightSummary', 'ProductDoAction', 'ProductIssueIcon', 'submitProductInsight', 'Product', 'Competitorname', 'Productfamilyname', 'Productname', 'Firm'), HideReplyBtn();" />&nbsp;

<?php } ?>

        <input name="submitProductInsight" id="submitProductInsight" type="submit" value="Submit Response" /></div>

<?php echo $form->hidden('id', array('value' => $id)); ?>
    <?php echo $form->hidden('edit_flag', array('value' => $edit_flag)); ?>
    <?php echo $form->hidden('old_attachment_name', array('value' => $attachment_name)); ?>
    <?php echo $form->hidden('old_attachment_real_name', array('value' => $attachment_real_name)); ?>	
    <?php //echo $form->hidden('ownership_taken',array('value'=>'')); ?>
    <?php echo $form->end(); ?>
</div>
    <?php if ($delegation_confirmed == 'Y' && $current_user_id == $deligated_to_selected && $deligated_to_selected > 0 && $current_user_role == SUBJECT_MATTER_EXPERT) { ?>
    <script>
                                                        /**
                                                         *	If delegation is confirmed and logged in SME is delegated SME for the insight then hide the Edit button and enable all the controls.
                                                         **/
                                                        window.onload = function() {
                                                            //document.getElementById('EditBtn').style.display = 'none';
                                                            document.getElementById('ProductResponseSentToCustomer_dummy_label').style.display = 'block';
                                                            activateLegalqaEditFields('ProductContentTypeId', 'ProductPracticeAreaId', 'ProductInsightSummary', 'ProductDoAction', 'ProductIssueIcon', 'submitProductInsight', 'Product', 'Competitorname', 'Productfamilyname', 'Productname', 'Firm');
                                                        }
    </script>
<?php } ?>

<script>
    function HideReplyBtn()
    {
        document.getElementById('ReplyBtn').style.display = 'none';
        document.getElementById('ProductResponseSentToCustomer_dummy_label').style.display = 'block';
    }

</script>