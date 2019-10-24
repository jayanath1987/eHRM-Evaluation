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
        <div class="mainHeading"><h2><?php echo __("Define Work/Professional Category Information") ?></h2></div>
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
                <input id="txtEvaluationCode"  name="txtEvaluationCode" type="text"  class="formInputText" value="" maxlength="10" />
            </div>

            <br class="clear"/>

            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Evaluation Name") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtEvaluationName"  name="txtEvaluationName" type="text"  class="formInputText" value="" maxlength="100" />
            </div>


            <div class="centerCol">
                <input id="txtEvaluationNameSi"  name="txtEvaluationNameSi" type="text"  class="formInputText" value="" maxlength="100" />

            </div>
            <div class="centerCol">
                <input id="txtEvaluationNameTa"  name="txtEvaluationNameTa" type="text"  class="formInputText" value="" maxlength="100" />

            </div>
            <br class="clear"/>

            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Evaluation Description") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtEvaluationDesc"  name="txtEvaluationDesc"  class="formTextArea" rows="3" cols="5" tabindex="1" ></textarea>
            </div>


            <div class="centerCol">
                <textarea id="txtEvaluationDescSi" class="txtEvaluationDescSi" rows="3" cols="5"  tabindex="2" name="txtEvaluationDescSi"></textarea>

            </div>
            <div class="centerCol">
                <textarea id="txtEvaluationDescTa" class="txtEvaluationDescTa" rows="3" cols="5"  tabindex="3" name="txtEvaluationDescTa" ></textarea>

            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Evaluation Year") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <select name="cmbYear" id="cmbYear" class="formSelect" style="width: 150px;" tabindex="4">
                    <option value=""><?php echo __("--Select--"); ?></option>
                    <?php foreach ($YearList as $iYears) { ?>
                        <option value="<?php echo $iYears; ?>"  ><?php echo $iYears; ?></option>
                 <?php  }
                    ?>                   
                </select>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Overall Rating") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <select name="cmbRate" id="cmbRate" class="formSelect" style="width: 150px;" tabindex="4">
                    <option value=""><?php echo __("--Select--"); ?></option>
                    <?php foreach ($RateList as $Rate) {
                    ?>
                        <option value="<?php echo $Rate->rate_id; ?>"><?php
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
            <div class="centerCol" style="width: 500px; padding-left: 145px;">
                <label> <span class="required">*</span><input type="radio" name="optrate" id="optrate1"   value="1" /><?php echo __("Active"); ?></label>
                <label><input type="radio" name="optrate" id="optrate2"   value="0" /><?php echo __("Inactive"); ?></label>
            </div>
            <br class="clear"/>
            <div class="formbuttons">
                <input type="button" class="savebutton" id="editBtn"

                       value="<?php echo __("Save") ?>" tabindex="8" />
                <input type="button" class="clearbutton"  id="resetBtn"
                       value="<?php echo __("Reset") ?>" tabindex="9" />
                <input type="button" class="backbutton" id="btnBack"
                       value="<?php echo __("Back") ?>" tabindex="10" />
            </div>
        </form>
    </div>
    <div class="requirednotice"><?php echo __("Fields marked with an asterisk"); ?><span class="required"> * </span> <?php echo __("are required"); ?></div>
    <br class="clear" />
</div>

<script type="text/javascript">

    $(document).ready(function() {
        buttonSecurityCommon("null","editBtn","null","null");


        //Validate the form
        $("#frmSave").validate({

            rules: {
                cmbYear:{required: true},
                cmbRate:{required: true},
                txtEvaluationCode:{required: true, noSpecialCharsOnly: true, maxlength:10},
                txtEvaluationName: { required: true, noSpecialCharsOnly: true, maxlength:100 },
                txtEvaluationNameSi: {noSpecialCharsOnly: true, maxlength:100 },
                txtEvaluationNameTa: {noSpecialCharsOnly: true, maxlength:100 },
                txtEvaluationDesc: { required: true, noSpecialCharsOnly: true, maxlength:200 },
                txtEvaluationDescSi: {noSpecialCharsOnly: true, maxlength:200 },
                txtEvaluationDescTa: {noSpecialCharsOnly: true, maxlength:200 }
            },
            messages: {
                cmbYear:{required:"<?php echo __("This field is required"); ?>"},
                cmbRate:{required:"<?php echo __("This field is required"); ?>"},
                txtEvaluationCode:{required:"<?php echo __("This field is required"); ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed"); ?>",maxlength:"<?php echo __("Maximum 10 Characters"); ?>"},
                txtEvaluationName: {required:"<?php echo __("This field is required"); ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed"); ?>",maxlength:"<?php echo __("Maximum 100 Characters"); ?>"},
                txtEvaluationNameSi:{noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed"); ?>",maxlength:"<?php echo __("Maximum 100 Characters"); ?>"},
                txtEvaluationNameTa:{noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed"); ?>",maxlength:"<?php echo __("Maximum 100 Characters"); ?>"},
                txtEvaluationDesc: {required:"<?php echo __("This field is required") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed"); ?>",maxlength:"<?php echo __("Maximum 200 Characters"); ?>"},
                txtEvaluationDescSi:{noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed"); ?>",maxlength:"<?php echo __("Maximum 200 Characters"); ?>"},
                txtEvaluationDescTa:{noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed"); ?>",maxlength:"<?php echo __("Maximum 200 Characters"); ?>"}

            }

        });

        // When click edit button
        $("#editBtn").click(function() {
            $('#frmSave').submit();
        });

        //When click reset buton
        $("#resetBtn").click(function() {
            document.forms[0].reset('');
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/CompanyEvaluationInfo')) ?>";
        });

    });
</script>
