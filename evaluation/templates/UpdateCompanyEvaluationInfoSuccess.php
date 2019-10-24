<?php
if ($lockMode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
$encrypt = new EncryptionHandler();
?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<div class="formpage4col" >
    <div class="navigation">
        <style type="text/css">
            div.formpage4col input[type="text"]{
                width: 180px;
            }
        </style>

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Edit Work/Professional Category Information") ?></h2></div>
        <?php echo message() ?>
        <?php echo $form['_csrf_token']; ?>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <div class="leftCol">
                &nbsp;
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("English") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Sinhala") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Tamil") ?></label>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Evaluation Code") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtEvaluationCode"  name="txtEvaluationCode" type="text"  class="formInputText" value="<?php echo $Evaluation->eval_code ?>" maxlength="10" />
            </div>

            <br class="clear"/>

            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Evaluation Name") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtEvaluationName"  name="txtEvaluationName" type="text"  class="formInputText" value="<?php echo $Evaluation->eval_name ?>" maxlength="100" />
            </div>


            <div class="centerCol">
                <input id="txtEvaluationNameSi"  name="txtEvaluationNameSi" type="text"  class="formInputText" value="<?php echo $Evaluation->eval_name_si ?>" maxlength="100" />

            </div>
            <div class="centerCol">
                <input id="txtEvaluationNameTa"  name="txtEvaluationNameTa" type="text"  class="formInputText" value="<?php echo $Evaluation->eval_name_ta ?>" maxlength="100" />

            </div>
            <br class="clear"/>

            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Evaluation Description") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtEvaluationDesc"  name="txtEvaluationDesc"  class="formTextArea" rows="3" cols="5" tabindex="1" ><?php echo $Evaluation->eval_desc ?></textarea>
            </div>


            <div class="centerCol">
                <textarea id="txtEvaluationDescSi" class="txtEvaluationDescSi" rows="3" cols="5"  tabindex="2" name="txtEvaluationDescSi"><?php echo $Evaluation->eval_desc_si ?></textarea>

            </div>
            <div class="centerCol">
                <textarea id="txtEvaluationDescTa" class="txtEvaluationDescTa" rows="3" cols="5"  tabindex="3" name="txtEvaluationDescTa" ><?php echo $Evaluation->eval_desc_ta ?></textarea>

            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Evaluation Year") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <select name="cmbYear" class="formSelect" style="width: 150px;" tabindex="4">
                   <?php
                    foreach ($YearList as $iYears) {   ?>
                        <option value="<?php echo $iYears; ?>" <?php if ($iYears == $Evaluation->eval_year) {
                            echo " selected=selected";
                        } ?> ><?php echo $iYears; ?></option>
                 <?php
                    }
                    ?>
                </select>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Overall Rating") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <select name="cmbRate" class="formSelect" style="width: 150px;" tabindex="4">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($RateList as $Rate) { ?>
                        <option value="<?php echo $Rate->rate_id ?>" <?php if ($Rate->rate_id == $Evaluation->rate_id) {
                            echo " selected=selected";
                        } ?> ><?php
                        if ($myCulture == 'en') {
                            $abcd = "rate_name";
                        } else {
                            $abcd = "rate_name_" . $myCulture;
                        }
                        if ($Rate->$abcd == "") {
                            echo $Rate->rate_name;
                        } else {
                            echo $Rate->$abcd;
                        }
                    ?></option>
<?php } ?>
                </select>
            </div>
         <br class="clear"/>
            <?php  $currentConfirm=$Evaluation->eval_active;  ?>
            <div class="centerCol" style="width: 500px;padding-left: 145px;">
                <label><span class="required">*</span><input type="radio" name="optrate" id="optrate1"   value="1" <?php if ($currentConfirm == 1)echo "checked" ?> /><?php echo __("Active"); ?></label>
                <label><input type="radio" name="optrate" id="optrate2"   value="0" <?php if ($currentConfirm == 0)echo "checked" ?> /><?php echo __("Inactive") ?></label>
                
            </div>
             <br class="clear"/>
            <div class="formbuttons">
                <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                       value="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                       title="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                <input type="reset" class="clearbutton" id="btnClear" tabindex="5"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled; ?>
                       value="<?php echo __("Reset"); ?>" />
                <input type="button" class="backbutton" id="btnBack"
                       value="<?php echo __("Back") ?>" tabindex="18"  onclick="goBack();"/>
            </div>
        </form>

    </div>
    <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
    <br class="clear" />
</div>


<script type="text/javascript">

    $(document).ready(function() {
        buttonSecurityCommon("null","null","editBtn","null");
<?php if ($editMode == true) { ?>
            $('#frmSave :input').attr('disabled', true);
            $('#editBtn').removeAttr('disabled');
            $('#btnBack').removeAttr('disabled');
<?php } ?>

        //Validate the form
        $("#frmSave").validate({

            rules: {
                cmbYear:{required: true, maxlength:4},
                cmbRate:{required: true},
                txtEvaluationCode:{required: true,noSpecialCharsOnly: true, maxlength:10},
                txtEvaluationName: { required: true,noSpecialCharsOnly: true, maxlength:100 },
                txtEvaluationNameSi: {noSpecialCharsOnly: true, maxlength:100 },
                txtEvaluationNameTa: {noSpecialCharsOnly: true, maxlength:100 },
                txtEvaluationDesc: { required: true,noSpecialCharsOnly: true, maxlength:200 },
                txtEvaluationDescSi: {noSpecialCharsOnly: true, maxlength:200 },
                txtEvaluationDescTa: {noSpecialCharsOnly: true, maxlength:200 }
            },
            messages: {
                cmbYear:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 4 Characters") ?>"},
                cmbRate:{required:"<?php echo __("This field is required") ?>"},
                txtEvaluationCode:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 10 Characters") ?>"},
                txtEvaluationName: {required:"<?php echo __("This field is required") ?>"},
                txtEvaluationNameSi:{maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtEvaluationNameTa:{maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtEvaluationDesc: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 200 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtEvaluationDescSi:{maxlength:"<?php echo __("Maximum 200 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtEvaluationDescTa:{maxlength:"<?php echo __("Maximum 200 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"}
            }
        });

        // When click edit button
        $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

        $("#editBtn").click(function() {

            var editMode = $("#frmSave").data('edit');
            if (editMode == 1) {
                // Set lock = 1 when requesting a table lock

                location.href="<?php echo url_for('evaluation/UpdateCompanyEvaluationInfo?id=' . $encrypt->encrypt($Evaluation->eval_id) . '&lock=1') ?>";
            }
            else {

                $('#frmSave').submit();
            }


        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/CompanyEvaluationInfo')) ?>";
        });

        //When click reset buton
        $("#btnClear").click(function() {
            // Set lock = 0 when resetting table lock
            location.href="<?php echo url_for('evaluation/UpdateCompanyEvaluationInfo?id=' . $encrypt->encrypt($Evaluation->eval_id) . '&lock=0') ?>";
        });
    });
</script>
