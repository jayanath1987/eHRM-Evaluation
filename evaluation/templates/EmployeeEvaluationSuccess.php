<?php
if ($lockMode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
$encrypt = new EncryptionHandler();

require_once '../../lib/common/LocaleUtil.php';
$sysConf = OrangeConfig::getInstance()->getSysConf();
$sysConf = new sysConf();
$inputDate = $sysConf->dateInputHint;
$format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());
$e = getdate();
$today = date("Y-m-d", $e[0]);
?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>

<style type="text/css">

    .total{

        margin-top: 10px;
    }   

    .thSupervisor{

        /*    background-color: #0000FF;
            display: none;*/
    }
    .trSupervisor{
        width :75px;
        /*    background-color: #0000FF;
            display: none;*/
    }

    .trModerator{

        /*    background-color: yellowgreen;
            display: none;*/
    }

    .thModerator{

        /*    background-color: yellowgreen;  
            display: none;*/
    }

    /*.thTopCnt{
         display: none;
    background-color: yellowgreen;
    }*/

    .thTopRaw{
        /*    display: none;
        background-color: yellowgreen;*/
    }

    .tdRaw{
        /*    display: none;
        background-color: yellowgreen;*/
    }

</style>



<div class="formpage4col" style="width: 910px;" >
    <div class="navigation">
        <style type="text/css">
            div.formpage4col input[type="text"]{
                width: 180px;
            }
        </style>

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Employee Evaluation") ?></h2></div>
        <?php echo message() ?>
        <?php echo $form['_csrf_token']; ?>
        <form name="frmSave" id="frmSave" method="post"  action="">

            <br class="clear"/>    

            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Work/Professional Category") ?> </label>
            </div>

            <div class="centerCol">
                <label for="txtLocationCode"><?php echo $EvalEmployee->EvaluationCompany->eval_name; ?></label>


                <select name="cmbCompEval" id="cmbCompEval" class="formSelect"  tabindex="4" style="display: none">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <?php foreach ($EvaluationList as $Evaluation) {
                        ?>
                        <option value="<?php echo $Evaluation->eval_id ?>" <?php if ($Evaluation->eval_id == $EvalEmployee->eval_id) {
                        echo " selected=selected";
                    } ?> ><?php
                            if ($myCulture == 'en') {
                                $abcd = "eval_name";
                            } else {
                                $abcd = "eval_name_" . $myCulture;
                            }
                            if ($Evaluation->$abcd == "") {
                                echo $Evaluation->eval_name;
                            } else {
                                echo $Evaluation->$abcd;
                            }
                            ?></option>
<?php } ?>
                </select>
            </div>
            <br class="clear"/>         


            <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name") ?> </label>
            </div>

            <div class="centerCol" >
                <label class="controlLabel" for="txtLocationCode"><?Php echo $EvalEmployee->Employee->emp_display_name; ?> </label>



                <input class="formInputText" style="padding-left: 0px; display: none;" type="text" name="txtEmployeeName" disabled="disabled"
                       id="txtEmployee" value="<?php if ($EvalEmployee->emp_number) {
    echo $EvalEmployee->Employee->emp_display_name;
} else {
    echo $EmpDisplayName;
} ?>" readonly="readonly" style="color: #222222"/>
                <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $EvalEmployee->emp_number; ?>" style="display: none;"/> 
            </div>
            <div class="centerCol">
                <input class="button"  style="margin-top: 10px; display: none" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
            </div>


            <input id="txtid"  name="txtid" type="hidden"  class="formInputText" maxlength="10" value="<?php echo $EvalEmployee->ev_id; ?>" style="display: none" />
            <br class="clear"/>


            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode"><?php echo __("Functional Review Active") ?> </label>
            </div>

            <div class="centerCol" style="width: 80px;">
                <input id="chkFTActive"  name="chkFTActive" type="checkbox"   value="1"  <?php
;
                if ($EvalEmployee->ev_fn_rv_active_flg == "1") {
                    echo "checked";
                }
?> <?php echo $disabled; ?> />
            </div>
            <div class="centerCol"><input style="width: 50px;" class="total" type="text" name="txtFNPersentage"  id="txtFNPersentage" maxlength="6" onkeypress='return validationFNAVGTot(event, this.id);' value="<?php echo $EvalEmployee->ev_fn_rv_percentage; ?>" > % </div>

            <br class="clear"/>

            <div name="FTDiv" id="FTDiv">

            </div>



            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode" style="width: 180px;"><?php echo __("Managerial Skills Review Active") ?> </label>
            </div>

            <div class="centerCol"  style="width: 80px;">
                <input id="chkMSActive"  name="chkMSActive" type="checkbox"   value="1" <?php
                if ($EvalEmployee->ev_ms_rv_active_flg == "1") {
                    echo "checked";
                }
?> <?php echo $disabled; ?> />
            </div>
            <div class="centerCol"><input style="width: 50px;" class="total" type="text" name="txtMSPersentage"  id="txtMSPersentage" maxlength="6" onkeypress='return validationFNAVGTot(event, this.id);' value="<?php echo $EvalEmployee->ev_ms_rv_percentage; ?>" > % </div>
            <br class="clear"/>

            <div name="MSDiv" id="MSDiv"  ></div>


            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode"><?php echo __("360 Degree Review Active") ?> </label>
            </div>

            <div class="centerCol"  style="width: 80px;">
                <input id="chk360Active"  name="chk360Active" type="checkbox"   value="1" <?php
                if ($EvalEmployee->ev_ts_rv_active_flg == "1") {
                    echo "checked";
                }
?> <?php echo $disabled; ?> />
            </div>
            <div class="centerCol"><input style="width: 50px;" class="total" type="text" name="txtTSPersentage"  id="txtTSPersentage" maxlength="6" onkeypress='return validationFNAVGTot(event, this.id);' value="<?php echo $EvalEmployee->ev_ts_rv_percentage; ?>" > % </div>

            <br class="clear"/>



            <div name="360Div" id="360Div"  ></div>            


            <br class="clear"/>
            <!--            <div class="leftCol" style="width: 200px;">
                                <label for="txtLocationCode"><?php echo __("Total Percentage") ?> </label>
                            </div>
             <div class="centerCol"><input style="width: 50px;" class="" type="text" name="txtTotalPersentage"  id="txtTotalPersentage" maxlength="6" readonly="readonly" > </div>
                        
                   <br class="clear"/>         -->

            <br class="clear"/>  
            <input   class='editbutton' type='button' id='btnCalcuate' name='btnCalcuate' onclick='clacuateFinal()' value='Final Calculation' >
            <div name="FinalDiv" id="FinalDiv"  >

                <br class="clear"/>
                <div id ="SupervisorDiv" >
                <div class="leftCol" style="width: 200px;">
                    <label for="txtLocationCode"><?php echo __("Supervisor Final Mark") ?> </label>
                </div>
                <div class="centerCol" style="width: 150px;">
                    <input style="width: 50px;" class="" type="text" name="txtSEMark"  id="txtSEMark" maxlength="6" readonly="readonly" value="<?php echo $EvalEmployee->ev_ts_sup_mark_tot; ?>" ></div>
                 <div class="leftCol" style="width: 150px;">   
                    <label id="supgrade" for="txtLocationCode" style="width: 150px"> </label>
                </div>
                <div class="leftCol" style="width: 200px;">
                    <label for="txtLocationCode" style="width: 300px"><?php echo __("Supervisor Evaluation Completed") ?> </label>
                </div>

                <div class="centerCol" style="width: 80px;">
                    <input id="chkSupcomplete"  name="chkSupcomplete" type="checkbox"  value="1"  <?php
;
                    if ($EvalEmployee->ev_complete_flg >= "1") {
                        echo "checked";
                    }
?> <?php echo $disabled; ?> />
                </div>
                <br class="clear"/>
                <div class="leftCol" style="width: 200px;">
                    <label for="txtLocationCode"><?php echo __("Supervisor Comment") ?> </label>
                </div>
                <div class="centerCol" style="width: 500px;">
                    <textarea  style="margin-left: 0px; width: 500px;" id="supcomment" name="supcomment" maxlength="500" ><?php echo $EvalEmployee->ev_appraiser_comment; ?></textarea>
                </div>
                <br class="clear"/>
                </div>

                <br class="clear"/>
                <br class="clear"/>
                <div id ="moderatormarlsDiv" >
                    <div class="leftCol" style="width: 200px;">
                        <label for="txtLocationCode"><?php echo __("Moderator Final Mark") ?> </label>
                    </div>
                    <div class="centerCol" style="width: 150px;"><input style="width: 50px;" class="" type="text" name="txtMEMark"  id="txtMEMark" maxlength="6" readonly="readonly"  value="<?php echo $EvalEmployee->ev_ts_mod_mark_tot; ?>" > </div>
                    <div class="leftCol" style="width: 150px;">
                        <label id="modgrade" for="txtLocationCode" style="width: 150px"> </label></div>
                    <div class="leftCol" style="width: 200px;">
                        <label for="txtLocationCode" style="width: 300px"><?php echo __("Moderator Evaluation Completed") ?> </label>
                    </div>

                    <div class="centerCol" style="width: 80px;">
                        <input id="chkModcomplete"  name="chkModcomplete" type="checkbox"   value="1"  <?php
;
                        if ($EvalEmployee->ev_complete_flg == "2") {
                            echo "checked";
                        }
?> <?php echo $disabled; ?> />
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" style="width: 200px;">
                        <label for="txtLocationCode"><?php echo __("Moderator Comment") ?> </label>
                    </div>
                    <div class="centerCol" style="width: 500px;">
                        <textarea  style="margin-left: 0px; width: 500px;" id="modcomment" name="modcomment" maxlength="500" ><?php echo $EvalEmployee->ev_moderator_comment; ?></textarea>
                    </div>

                </div>
                
                <br class="clear"/>
                <br class="clear"/>
                <div id ="EmployeeAgreeDiv" >

                    <div class="leftCol" style="width: 400px;">
                        <label for="txtLocationCode" style="width: 400px"><?php echo __("Employee Evaluation Completed & Agreed Employee.") ?> </label>
                    </div>

                    <div class="centerCol" style="width: 80px;">
                        <input id="chkEmpcomplete"  name="chkEmpcomplete" type="checkbox"   value="1"  <?php
;
                        if ($EvalEmployee->ev_employee_agree == "1") {
                            echo "checked";
                        }
?> />
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" style="width: 200px;">
                        <label for="txtLocationCode"><?php echo __("Employee Comment") ?> </label>
                    </div>
                    <div class="centerCol" style="width: 500px;">
                        <textarea  style="margin-left: 0px; width: 500px;" id="empcomment" name="empcomment" maxlength="500"  ><?php echo $EvalEmployee->ev_employee_comment; ?></textarea>
                    </div>

                </div>
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
                <?php if($type!= '2'){ ?>
                <input type="button" class="backbutton" id="btnBack"
                       value="<?php echo __("Back") ?>" tabindex="18"  onclick="goBack();"/>
                <?php } ?>
                <input type="button" class="backbutton" id="btnModerator"
                       value="<?php echo __("Appeal Moderator") ?>" tabindex="18"  onclick="appearlmoderator();"/>
            </div>
        </form>
    </div>
    <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
    <br class="clear" />
</div>


<script type="text/javascript">
                var comeval = null;
                var a = 0;
                var b = 0;
                var c = 0;
                var ajaxFT = 0;
                var ajaxMS = 0;
                var ajaxTS = 0;
                
                function myJavaScriptFunction(type,id){
               
                    
                var popup=window.open('<?php echo public_path('../../symfony/web/index.php/evaluation/AddComment?type='); ?>'+type+'&id='+id,'Locations','height=450,width=800,resizable=1,scrollbars=1');

                if(!popup.opener) popup.opener=self;
                popup.focus();
        
                }

                function appearlmoderator() {
                    var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo url_for('evaluation/AjaxModeratorAppeal') ?>",
                        data: {ev_id: ev_id},
                        dataType: "json",
                        success: function(data) {
                            alert(data);
                        }
                    });
                }

                $(document).ready(function() {


                    calTOT();


                    buttonSecurityCommon("null", "null", "editBtn", "null");


                    $("#FTDiv").hide();
                    $("#MSDiv").hide();
                    $("#360Div").hide();




<?php if ($editMode == true) { ?> //$('#frmSave input').removeAttr("readonly", true); 
                        $('#frmSave :input').attr('disabled', true);
                        $('#editBtn').removeAttr('disabled');
                        $('#btnBack').removeAttr('disabled');
                        $('#btnModerator').removeAttr('disabled');

<?php } else { ?>
                        //$('#frmSave input').removeAttr("readonly", true); 
<?php } ?>

                    $('#empRepPopBtn').click(function() {

                        if ($("#cmbCompEval").val() == "all") {
                            alert("please select Work/Professional Category");
                            return false;
                        }

                        var popup = window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee'); ?>', 'Locations', 'height=450,width=800,resizable=1,scrollbars=1');
                        if (!popup.opener)
                            popup.opener = self;
                        popup.focus();
                    });

                    $("#txtFromDate").datepicker({dateFormat: '<?php echo $inputDate; ?>'});
                    $("#txtToDate").datepicker({dateFormat: '<?php echo $inputDate; ?>'});

                    //Validate the form
                    $("#frmSave").validate({
                        rules: {
                            cmbCompEval: {required: true},
                            txtFTName: {required: true, noSpecialCharsOnly: true, maxlength: 200},
                            txtFromDate: {noSpecialCharsOnly: true, required: true},
                            txtToDate: {noSpecialCharsOnly: true, required: true},
                            txtFTDesc: {required: true, noSpecialCharsOnly: true, maxlength: 500},
                            txtEmpId: {required: true}

                        },
                        messages: {
                            cmbCompEval: {required: "<?php echo __("This field is required") ?>"},
                            txtFTName: {required: "<?php echo __("This field is required") ?>", maxlength: "<?php echo __("Maximum 200 Characters") ?>", noSpecialCharsOnly: "<?php echo __("Special Characters are not allowed") ?>"},
                            txtFromDate: {required: "<?php echo __("This field is required") ?>", noSpecialCharsOnly: "<?php echo __("Special Characters are not allowed") ?>"},
                            txtToDate: {required: "<?php echo __("This field is required") ?>", noSpecialCharsOnly: "<?php echo __("Special Characters are not allowed") ?>"},
                            txtFTDesc: {required: "<?php echo __("This field is required") ?>", maxlength: "<?php echo __("Maximum 200 Characters") ?>", noSpecialCharsOnly: "<?php echo __("Special Characters are not allowed") ?>"},
                            txtEmpId: {required: "<?php echo __("This field is required") ?>"}


                        }
                    });

                    // When click edit button
                    $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                    $("#editBtn").click(function() {

                        var editMode = $("#frmSave").data('edit');
                        if (editMode == 1) {


                            // Set lock = 1 when requesting a table lock

                            location.href = "<?php echo url_for('evaluation/EmployeeEvaluation?id=' . $encrypt->encrypt($EvalEmployee->ev_id) . '&lock=1&type=' . $type) ?>";
                        }
                        else {
                            var errors = 0;
                            if (document.getElementById("chkFTActive").checked) {
                                if ($("#txtFNPersentage").val() == "") {
                                    alert("Functional Review percentage invalid");
                                    errors++;
                                }

                                if ($("#txtev_fn_tot").val() == "") {
                                    alert("Functional Weight Total should be 100");
                                    errors++;
                                }
                            }

                            if (document.getElementById("chkMSActive").checked) {
                                if ($("#txtMSPersentage").val() == "") {
                                    alert("Managerial Skills Review percentage invalid");
                                    errors++;
                                }
                                if ($("#txtev_ms_tot").val() == "") {
                                    alert("Managerial Skills Weight Total should be 100");
                                    errors++;
                                }
                            }


                            if (document.getElementById("chk360Active").checked) {

                                if ($("#txtTSPersentage").val() == "") {
                                    alert("360 Degree Review percentage invalid");
                                    errors++;
                                }


                                if ($("#txtev_ts_tot").val() == "") {
                                    alert("360 Degree Weight Total should be 100");
                                    errors++;
                                }

                            }

                            if ($("#txtTotalPersentage").val() == "") {
                                alert("Total Percentage should be equal to 100");
                                errors++;
                            }

                            if (errors == 0) {
                                $('#frmSave').submit();
                            }
                        }


                    });

                    //When Click back button
                    $("#btnBack").click(function() {
                        location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/DefineEmployeeEvaluation')) ?>";
                    });

                    //When click reset buton
                    $("#btnClear").click(function() {
                        // Set lock = 0 when resetting table lock
<?php if ($EvalEmployee->ev_id) { ?>
                            location.href = "<?php echo url_for('evaluation/EmployeeEvaluation?id=' . $encrypt->encrypt($EvalEmployee->ev_id) . '&lock=0&type=' . $type) ?>";
<?php } else { ?>
                            location.href = "<?php echo url_for('evaluation/EmployeeEvaluation') ?>";
<?php } ?>
                    });



                    $("#chkFTActive").change(function() {
                        if (this.checked) {
                            $("#FTDiv").show();
                        } else {
                            $("#FTDiv").hide();
                        }
                    });
                    $("#chkMSActive").change(function() {
                        if (this.checked) {
                            $("#MSDiv").show();
                        } else {
                            $("#MSDiv").hide();
                        }
                    });
                    $("#chk360Active").change(function() {
                        if (this.checked) {
                            $("#360Div").show();
                        } else {
                            $("#360Div").hide();
                        }
                    });


//                        $("#txtftweight_"+a).blur(function() {
//                        //alert('Handler for .blur() called.');
//                        });


                    jQuery(".fnweight").live("blur", function(e) {

                        FTcalAVGTOT();
                    });
                    jQuery(".msweight").live("blur", function(e) {

                        MScalAVGTOT();
                    });
                    jQuery(".tsweight").live("blur", function(e) {

                        theresixtycalAVGTOT();
                    });

                    jQuery(".total").live("blur", function(e) {

                        calTOT();
                    });


<?php if ($EvalEmployee->ev_fn_rv_active_flg == "1") { ?>

                        getEmplyeeEvaldetail("<?php echo $EvalEmployee->emp_number ?>");

                        $("#FTDiv").show();


<?php } ?>
<?php if ($EvalEmployee->ev_ms_rv_active_flg == "1") { ?>
                        getEmplyeeEvaldetail("<?php echo $EvalEmployee->emp_number ?>");

                        $("#MSDiv").show();

<?php } ?>
<?php if ($EvalEmployee->ev_ts_rv_active_flg == "1") { ?>

                        getEmplyeeEvaldetail("<?php echo $EvalEmployee->emp_number ?>");

                        $("#360Div").show();

<?php } ?>

<?php if ($type == "0") { ?>
                        //$('#frmSave input').attr('readonly', 'readonly');         
                        $(".thModerator").hide();
                        $(".trModerator").hide();
                        $(".tdRaw").css('width', '400');
                        $(".trSupervisor").css('width', '98px');

                        $('.trSupervisor > input').each(function() {
                            $(this).width($(this).parent().width());
                        });
                        $("#moderatormarlsDiv").hide();


    <?php } else if ($type == "1") { 
    ?>
                        //$('#frmSave input').attr('readonly', 'readonly');
                        <?php if($EvalEmployee->ev_complete_flg == 1 ){ ?>
                        $("#moderatormarlsDiv").show();
                        $("#btnModerator").hide();
                        <?php } else { ?>
                        $("#moderatormarlsDiv").hide();
                        $("#btnModerator").show();
                        <?php } ?>    
                        //}
                <?php } else if ($type == "2") { ?>
                    $("#editBtn").show();
                    $("#btnClear").hide();
                     $('#frmSave input').attr('readonly', 'readonly');
                     $('#frmSave input').css('color', 'black');
                     $("#btnFTCalcuate").hide();
                     $("#btnMSCalcuate").hide();
                     $("#btnCalcuate").hide();
                     $("#btnTSCalcuate").hide();
                <?php } else { //die('asdasdasda'); ?>
                        $("#editBtn").hide();
                        $("#btnClear").hide();
                        $('#frmSave input').attr('readonly', 'readonly');
                        $('#frmSave input').css('color', 'black');
                        $("#moderatorm arlsDiv").hide();
                        //$("#chkEmpcomplete").attr("readonly", false);
                        //$("#empcomment").attr("disable", false);
<?php } ?>
                    //$('#frmSave input').attr('readonly', 'readonly');      
                });


                function SelectEmployee(data) {

                    myArr = data.split('|');
                    $("#txtEmpId").val("");
                    $("#txtEmpId").val(myArr[0]);
                    $("#txtEmployee").val(myArr[1]);
                    getEmplyeeEvaldetail(myArr[0]);
                }

                function getEmplyeeEvaldetail(eno) {
                    //alert(eno);
                    comeval = $("#cmbCompEval").val();
                    if (ajaxFT == 0) {
                        FTDetaiils(eno, comeval);
                        ajaxFT++;
                    }
                    if (ajaxMS == 0) {
                        SMDetaiils(eno, comeval);
                        ajaxMS++;
                    }
                    if (ajaxTS == 0) {
                        threesixtyDetaiils(eno, comeval);
                        ajaxTS++;
                    }


                }

                function FTDetaiils(eno, comeval) {
                    var html = "";
                    var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";

                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo url_for('evaluation/AjaxGetFTDataEval') ?>",
                        data: {comeval: comeval, eno: eno, ev_id: ev_id},
                        dataType: "json",
                        success: function(data) {
                            html += "<div><br>";
                            $.each(data, function(key, value) {
                                html += "<table border='1'><tr>";
                                html += "<th width='300px;'>Function/Task</th>";
                                html += "<th width='60px;'>From</th>";
                                html += "<th width='60px;'>To</th>";
                                html += "<th width='75px;'>Target / Indicator</th>";
                                html += "<th width='50px;'>Weight</th>";
                                html += "<th width='450px;' class='thTopRaw' >";
                                html += "<table border='1' class='thTopCnt' width='450px;'><caption></caption><col /><col /><col /><col /><col /><col /><tbody>";
                                html += "<tr><td class='thSupervisor' colspan='4' style='text-align:center;margin-left:auto;margin-right:auto; width:300px;'>Supervisor</td>";
                                html += "<td colspan='2' rowspan='2' class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Moderator</td>";
                                html += "</tr><tr>";
                                html += "<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Mid Year</td>";
                                html += "<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>End Year</td>";
                                html += "</tr><tr>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto;  width:75px;'>Achieve %</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Achieve %</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                html += "<td  class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Achieve %</td>";
                                html += "<td class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                html += "</tr></tbody></table>";
                                html += "</th><th width='1px;'>Active</th></tr>";
                                $.each(value, function(key1, value1) {
                                    //console.log(value1.emp_number);
                                    html += "<tr><td><input style='width:60px;' type='hidden' id='txtfnid_" + a + "' name='txtfnid_" + a + "' value='" + value1.ft_id + "'>";
                                    html += "<input style='width:60px;' type='hidden' id='txtftid[]' name='txtftid[]' value='" + a + "'>";
                                    html += "<a href='#' onclick='myJavaScriptFunction(1,"+value1.ft_id+");'>";
                                    if (value1.ft_title != null) {
                                        html += value1.ft_title;
                                    }
                                    html += "</a></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txtfnfromdate_" + a + "' name='txtfnfromdate_" + a + "' value='";
                                    if (value1.ft_from_date != null) {
                                        html += value1.ft_from_date;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txtfttodate_" + a + "' name='txtfttodate_" + a + "' value='";
                                    if (value1.ft_to_date != null) {
                                        html += value1.ft_to_date;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txtfttargetindicater_" + a + "' name='txtfttargetindicater_" + a + "' value='";
                                    if (value1.ft_target_indicater != null) {
                                        html += value1.ft_target_indicater;
                                    }
                                    html += "' title='";
                                    if (value1.ft_target_indicater != null) {
                                        html += value1.ft_target_indicater;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' class='fnweight' type='text' id='txtftweight_" + a + "' name='txtftweight_" + a + "' maxlength='6' onkeypress='return validationFNAVGTot(event,this.id);'  value='";
                                    if (value1.ft_weight != null) {
                                        html += value1.ft_weight;
                                    }
                                    html += "'></td>";
                                    html += "<td class='tdRaw' ><table border='0'><tr><td  class='trSupervisor' ><input class='txtFTSMA' style='width:60px;' type='text' id='txtftsupmidachive_" + a + "' name='txtftsupmidachive_" + a + "' value='";
                                    if (value1.ft_sup_mid_achive != null) {
                                        html += value1.ft_sup_mid_achive;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td><td  class='trSupervisor'><input class='txtFTSMM'  style='width:60px;' type='text' id='txtftsupmidmarks_" + a + "' name='txtftsupmidmarks_" + a + "' value='";
                                    if (value1.ft_sup_mid_marks != null) {
                                        html += value1.ft_sup_mid_marks;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td  class='trSupervisor'><input   class='txtFTSEA' style='width:60px;' type='text' id='txtftsupendachive_" + a + "' name='txtftsupendachive_" + a + "' value='";
                                    if (value1.ft_sup_end_achive != null) {
                                        html += value1.ft_sup_end_achive;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td  class='trSupervisor'><input  class='txtFTSEM' style='width:60px;' type='text' id='txtftsupendmarks_" + a + "' name='txtftsupendmarks_" + a + "' value='";
                                    if (value1.ft_sup_end_marks != null) {
                                        html += value1.ft_sup_end_marks;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td  class='trModerator' width='75px;'><input class='txtFTMEA' style='width:60px;' type='text' id='txtftmodendachive_" + a + "' name='txtftmodendachive_" + a + "' value='";
                                    if (value1.ft_mod_end_achive != null) {
                                        html += value1.ft_mod_end_achive;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td  class='trModerator' width='75px;'><input class='txtFTMEM' style='width:60px;' type='text' id='txtftmodendmarks_" + a + "' name='txtftmodendmarks_" + a + "' value='";
                                    if (value1.ft_mod_end_marks != null) {
                                        html += value1.ft_mod_end_marks;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></tr></table></td>";
                                    html += "<td   width='10px;'><input type='checkbox' name='chkftactiveflg_" + a + "' id='chkftactiveflg_" + a + "' value='1' ";
                                    if (value1.ft_active_flg != null) {
                                        html += "checked";
                                    } else {
                                        html += "";
                                    }
                                    html += "></td></tr>";
                                    a++;
                                });
                                html += "<tr>";
                                html += "<td colspan='4'>Total as per Functional Review Average</td><td><input style='width:60px;' type='text' id='txtev_fn_avg' name='txtev_fn_avg'></td>";
                                html += "<td><table  border='0'><tr><td class='trSupervisor'><input class='txtFTSMAA' style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_avg' name='txtev_fn_sup_mid_ach_avg' value='";
<?php if ($EvalEmployee->ev_fn_sup_mid_ach_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_mid_ach_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor' ><input  class='txtFTSMMA' style='width:60px;' type='text' id='txtev_fn_sup_mid_mark_avg' name='txtev_fn_sup_mid_mark_avg' value='";
<?php if ($EvalEmployee->ev_fn_sup_mid_mark_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_mid_mark_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtFTSEAA' style='width:60px;' type='text' id='txtev_fn_sup_end_ach_avg' name='txtev_fn_sup_end_ach_avg' value='";
<?php if ($EvalEmployee->ev_fn_sup_end_ach_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_end_ach_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtFTSEMA' style='width:60px;' type='text' id='txtev_fn_sup_end_mark_avg' name='txtev_fn_sup_end_mark_avg' value='";
<?php if ($EvalEmployee->ev_fn_sup_end_mark_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_end_mark_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trModerator' width='75px;'><input  class='txtFTMEAA' style='width:60px;' type='text' id='txtev_fn_mod_end_ach_avg' name='txtev_fn_mod_end_ach_avg' value='";
<?php if ($EvalEmployee->ev_fn_mod_end_ach_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_mod_end_ach_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trModerator' width='75px;'><input  class='txtFTMEMA' style='width:60px;' type='text' id='txtev_fn_mod_end_mark_avg' name='txtev_fn_mod_end_mark_avg' value='";
<?php if ($EvalEmployee->ev_fn_mod_end_mark_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_mod_end_mark_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td></tr></table></td>";
                                html += "<td class='trModerator'></td></tr>";
                                html += "<td colspan='4'>Total</td><td><input style='width:60px;' type='text' id='txtev_fn_tot' name='txtev_fn_tot'>";
                                html += "</td>";
                                html += "<td><table  border='0'><tr><td class='trSupervisor'><input  class='txtFTSMAT' style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_tot' name='txtev_fn_sup_mid_ach_tot' value='";
<?php if ($EvalEmployee->ev_fn_sup_mid_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_mid_ach_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtFTSMMT' style='width:60px;' type='text' id='txtev_fn_sup_mid_mark_tot' name='txtev_fn_sup_mid_mark_tot' value='";
<?php if ($EvalEmployee->ev_fn_sup_mid_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_mid_mark_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtFTSEAT' style='width:60px;' type='text' id='ev_fn_sup_end_ach_tot' name='ev_fn_sup_end_ach_tot' value='";
<?php if ($EvalEmployee->ev_fn_sup_end_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_end_ach_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtFTSEMT' style='width:60px;' type='text' id='ev_fn_sup_end_mark_tot' name='ev_fn_sup_end_mark_tot' value='";
<?php if ($EvalEmployee->ev_fn_sup_end_mark_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_sup_end_mark_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trModerator' width='75px;'><input  class='txtFTMEAT' style='width:60px;' type='text' id='ev_fn_mod_end_ach_tot' name='ev_fn_mod_end_ach_tot' value='";
<?php if ($EvalEmployee->ev_fn_mod_end_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_mod_end_ach_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trModerator' width='75px;'><input  class='txtFTMEMT' style='width:60px;' type='text' id='ev_fn_mod_end_mark_tot' name='ev_fn_mod_end_mark_tot' value='";
<?php if ($EvalEmployee->ev_fn_mod_end_mark_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_fn_mod_end_mark_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td></tr></table></td>";
                                html += "<td class='trModerator'></td>";

                                html += "</tr></table>";
                            });

                            html += "<br/><input   class='editbutton' type='button' id='btnFTCalcuate' name='btnFTCalcuate' onclick='clacuateFT()' value='Calculate Functional Review' >";
                            html += "</div><br/>";

                        }

                    });

                    $("#FTDiv").append(html);
                    if (ev_id != "") {
                        FTcalAVGTOT();
                    }
                }


                function SMDetaiils(eno, comeval) {
                    var html = "";
                    var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo url_for('evaluation/AjaxGetSMDataEval') ?>",
                        data: {comeval: comeval, eno: eno, ev_id: ev_id},
                        dataType: "json",
                        success: function(data) {
                            html += "<div><br>";
                            $.each(data, function(key, value) {
                                html += "<table border='1'><tr>";
                                html += "<th width='300px;'>Function/Task</th>";
                                html += "<th width='60px;'>From</th>";
                                html += "<th width='60px;'>To</th>";
                                html += "<th width='75px;'>Target / Indicator</th>";
                                html += "<th width='50px;'>Weight</th>";
                                html += "<th width='450px;' class='thTopRaw' >";
                                html += "<table border='1' class='thTopCnt' width='450px;'><caption></caption><col /><col /><col /><col /><col /><col /><tbody>";
                                html += "<tr><td class='thSupervisor' colspan='4' style='text-align:center;margin-left:auto;margin-right:auto; width:300px;'>Supervisor</td>";
                                html += "<td colspan='2' rowspan='2' class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Moderator</td>";
                                html += "</tr><tr>";
                                html += "<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Mid Year</td>";
                                html += "<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>End Year</td>";
                                html += "</tr><tr>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto;  width:75px;'>Achieve %</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Achieve %</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                html += "<td  class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Achieve %</td>";
                                html += "<td class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                html += "</tr></tbody></table>";
                                html += "</th><th width='1px;'>Active</th></tr>";
                                $.each(value, function(key1, value1) {
                                    //console.log(value1.EvaluationSkill.skill_title);
                                    html += "<tr><td><input style='width:60px;' type='hidden' id='txtmsid_" + b + "' name='txtmsid_" + b + "' value='" + value1.skill_id + "'>";
                                    html += "<input style='width:60px;' type='hidden' id='txtmsid[]' name='txtmsid[]' value='" + b + "'>";
                                    html += "<a href='#' onclick='myJavaScriptFunction(2,"+value1.emp_skill_id+");'>";
                                    if (ev_id == "") {
                                        if (value1.skill_title != null) {
                                            html += value1.skill_title;
                                        }
                                    } else {
                                        if (value1.EvaluationSkill.skill_title != null) {
                                            html += value1.EvaluationSkill.skill_title;
                                        }
                                    }
                                    html += "</a></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txtmsfromdate_" + b + "' name='txtmsfromdate_" + b + "' value='";
                                    if (value1.emp_skill_from_date != null) {
                                        html += value1.emp_skill_from_date;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txtmstodate_" + b + "' name='txtmstodate_" + b + "' value='";
                                    if (value1.emp_skill_to_date != null) {
                                        html += value1.emp_skill_to_date;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txtmstargetindicater_" + b + "' name='txtmstargetindicater_" + b + "' value='";
                                    if (value1.emp_skill_target_indicater != null) {
                                        html += value1.emp_skill_target_indicater;
                                    }
                                    html += "' title='";
                                    if (value1.emp_skill_target_indicater != null) {
                                        html += value1.emp_skill_target_indicater;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' class='msweight' type='text' id='txtmsweight_" + b + "' name='txtmsweight_" + b + "' maxlength='6' onkeypress='return validationFNAVGTot(event,this.id);'  value='";
                                    if (value1.emp_skill_weight != null) {
                                        html += value1.emp_skill_weight;
                                    }
                                    html += "'></td>";
                                    html += "<td class='tdRaw' ><table border='0'><tr ><td class='trSupervisor' ><input class='txtMSSMA' style='width:60px;' type='text' id='txtemp_skillsupmidachive_" + b + "' name='txtemp_skillsupmidachive_" + b + "' value='";
                                    if (value1.emp_skill_sup_mid_achive != null) {
                                        html += value1.emp_skill_sup_mid_achive;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td><td class='trSupervisor'><input class='txtMSSMM' style='width:60px;' type='text' id='txtemp_skillsupmidmarks_" + b + "' name='txtemp_skillsupmidmarks_" + b + "' value='";
                                    if (value1.emp_skill_sup_mid_marks != null) {
                                        html += value1.emp_skill_sup_mid_marks;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='trSupervisor'><input class='txtMSSEA' style='width:60px;' type='text' id='txtemp_skillsupendachive_" + b + "' name='txtemp_skillsupendachive_" + b + "' value='";
                                    if (value1.emp_skill_sup_end_achive != null) {
                                        html += value1.emp_skill_sup_end_achive;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='trSupervisor'><input class='txtMSSEM' style='width:60px;' type='text' id='txtemp_skillsupendmarks_" + b + "' name='txtemp_skillsupendmarks_" + b + "' value='";
                                    if (value1.emp_skill_sup_end_marks != null) {
                                        html += value1.emp_skill_sup_end_marks;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='thModerator' width='75px;'><input class='txtMSMEA' style='width:60px;' type='text' id='txtemp_skillmodendachive_" + b + "' name='txtemp_skillmodendachive_" + b + "' value='";
                                    if (value1.emp_skill_mod_end_achive != null) {
                                        html += value1.emp_skill_mod_end_achive;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='thModerator' width='75px;'><input class='txtMSMEM' style='width:60px;' type='text' id='txtemp_skillmodendmarks_" + b + "' name='txtemp_skillmodendmarks_" + b + "' value='";
                                    if (value1.emp_skill_mod_end_marks != null) {
                                        html += value1.emp_skill_mod_end_marks;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></tr></table></td>";
                                    html += "<td width='10px;'><input type='checkbox' name='chkemp_skillactiveflg_" + b + "' id='chkemp_skillactiveflg_" + b + "' value='1' ";
                                    if (value1.emp_skill_active_flg != null) {
                                        html += "checked";
                                    } else {
                                        html += "";
                                    }
                                    html += "></td></tr>";
                                    b++;
                                });
                                html += "<tr>";
                                html += "<td colspan='4'>Total as per Managerial Skills Average</td><td><input style='width:60px;' type='text' id='txtev_ms_avg' name='txtev_ms_avg'></td>";
                                html += "<td><table  border='0'><tr><td class='trSupervisor'><input class='txtMSSMAA' style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_avg' name='txtev_ms_sup_mid_ach_avg' value='";
<?php if ($EvalEmployee->ev_ms_sup_mid_ach_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_mid_ach_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input class='txtMSSMMA' style='width:60px;' type='text' id='txtev_ms_sup_mid_mark_avg' name='txtev_ms_sup_mid_mark_avg' value='";
<?php if ($EvalEmployee->ev_ms_sup_mid_mark_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_mid_mark_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtMSSEAA' style='width:60px;' type='text' id='txtev_ms_sup_end_ach_avg' name='txtev_ms_sup_end_ach_avg' value='";
<?php if ($EvalEmployee->ev_ms_sup_end_ach_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_end_ach_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtMSSEMA' style='width:60px;' type='text' id='txtev_ms_sup_end_mark_avg' name='txtev_ms_sup_end_mark_avg' value='";
<?php if ($EvalEmployee->ev_ms_sup_end_mark_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_end_mark_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input  class='txtMSMEAA' style='width:60px;' type='text' id='txtev_ms_mod_end_ach_avg' name='txtev_ms_mod_end_ach_avg' value='";
<?php if ($EvalEmployee->ev_ms_mod_end_ach_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_mod_end_ach_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input  class='txtMSMEMA' style='width:60px;' type='text' id='txtev_ms_mod_end_mark_avg' name='txtev_ms_mod_end_mark_avg' value='";
<?php if ($EvalEmployee->ev_ms_mod_end_mark_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_mod_end_mark_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td></tr></table></td>";
                                html += "<td class='tdRaw' ></td></tr>";
                                html += "<tr>";
                                html += "<td colspan='4'>Total</td><td><input style='width:60px;' type='text' id='txtev_ms_tot' name='txtev_ms_tot'>";
                                html += "</td>";
                                html += "<td><table  border='0'><tr><td class='trSupervisor'><input  class='txtMSSMAT' style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_tot' name='txtev_ms_sup_mid_ach_tot' value='";
<?php if ($EvalEmployee->ev_ms_sup_mid_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_mid_ach_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtMSSMMT' style='width:60px;' type='text' id='txtev_ms_sup_mid_mark_tot' name='txtev_ms_sup_mid_mark_tot' value='";
<?php if ($EvalEmployee->ev_ms_sup_mid_mark_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_mid_mark_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input  class='txtMSSEAT' style='width:60px;' type='text' id='txtev_ms_sup_end_ach_tot' name='txtev_ms_sup_end_ach_tot' value='";
<?php if ($EvalEmployee->ev_ms_sup_end_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_end_ach_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input   class='txtMSSEMT' style='width:60px;' type='text' id='txtev_ms_sup_end_mark_tot' name='txtev_ms_sup_end_mark_tot' value='";
<?php if ($EvalEmployee->ev_ms_sup_end_mark_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_sup_end_mark_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input   class='txtMSMEAT' style='width:60px;' type='text' id='txtev_ms_mod_end_ach_tot' name='txtev_ms_mod_end_ach_tot' value='";
<?php if ($EvalEmployee->ev_ms_mod_end_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_mod_end_ach_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input   class='txtMSMEMT' style='width:60px;' type='text' id='txtev_ms_mod_end_mark_tot' name='txtev_ms_mod_end_mark_tot' value='";
<?php if ($EvalEmployee->ev_ms_mod_end_mark_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ms_mod_end_mark_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td></tr></table></td>";
                                html += "<td class='tdRaw'></td>";

                                html += "</tr></table>";
                            });

                            html += "<br/><input   class='editbutton' type='button' id='btnMSCalcuate' name='btnMSCalcuate' onclick='clacuateMS()' value='Calculate Managerial Skill Review' >";
                            html += "</div><br/>";

                        }

                    });

                    $("#MSDiv").append(html);
                    if (ev_id != "") {
                        MScalAVGTOT();
                    }

                }

                function threesixtyDetaiils(eno, comeval) {
                    var html = "";
                    var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";

                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo url_for('evaluation/AjaxGet360DataEval') ?>",
                        data: {comeval: comeval, eno: eno, ev_id: ev_id},
                        dataType: "json",
                        success: function(data) {
                            html += "<div><br>";
                            $.each(data, function(key, value) {
                                html += "<table border='1'><tr>";
                                html += "<th width='300px;'>Function/Task</th>";
                                html += "<th width='60px;'>From</th>";
                                html += "<th width='60px;'>To</th>";
                                html += "<th width='75px;'>Target / Indicator</th>";
                                html += "<th width='50px;'>Weight</th>";
                                html += "<th width='450px;' class='thTopRaw' >";
                                html += "<table border='1' class='thTopCnt' width='450px;'><caption></caption><col /><col /><col /><col /><col /><col /><tbody>";
                                html += "<tr><td class='thSupervisor' colspan='4' style='text-align:center;margin-left:auto;margin-right:auto; width:300px;'>Client</td>";
                                html += "<td colspan='2' rowspan='2' class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Moderator</td>";
                                html += "</tr>";
                                html += "<tr>";
//                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Mid Year</td>";
//                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>End Year</td>";
                                html += "</tr>";
                                html += "<tr>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto;  width:75px;'>Client 1</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Client 2</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Client 3</td>";
                                html += "<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Summary</td>";
                                html += "<td  class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Achieve %</td>";
                                html += "<td class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                html += "</tr></tbody></table>";
                                html += "</th><th width='1px;'>Active</th></tr>";
                                $.each(value, function(key1, value1) {
                                    //console.log(value1.EvaluationSkill.skill_title);
                                    html += "<tr><td><input style='width:60px;' type='hidden' id='txttsid_" + c + "' name='txttsid_" + c + "' value='" + value1.ts_id + "'>";
                                    html += "<input style='width:60px;' type='hidden' id='txttsid[]' name='txttsid[]' value='" + c + "'>";
                                    html += "<a href='#' onclick='myJavaScriptFunction(3,"+value1.emp_ts_id+");'>";
                                    
                                    if (ev_id == "") {
                                        if (value1.ts_title != null) {
                                            html += value1.ts_title;
                                        }
                                    } else {
                                        if (value1.EvaluationTSMaster.ts_title != null) {
                                            html += value1.EvaluationTSMaster.ts_title;
                                        }
                                    }
                                    html += "</a></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txttsfromdate_" + c + "' name='txttsfromdate_" + c + "' value='";
                                    if (value1.emp_ts_from_date != null) {
                                        html += value1.emp_ts_from_date;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' type='text' id='txttstodate_" + c + "' name='txttstodate_" + c + "' value='";
                                    if (value1.emp_ts_to_date != null) {
                                        html += value1.emp_ts_to_date;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' class='txtTSIndicator' type='text' id='txttstargetindicater_" + c + "' name='txttstargetindicater_" + c + "' value='";
                                    if (value1.emp_ts_target_indicater != null) {
                                        html += value1.emp_ts_target_indicater;
                                    }
                                    html += "' title='";
                                    if (value1.emp_ts_target_indicater != null) {
                                        html += value1.emp_ts_target_indicater;
                                    }
                                    html += "'></td>";
                                    html += "<td><input style='width:60px;' class='tsweight' type='text' id='txttsweight_" + c + "'' name='txttsweight_" + c + "' maxlength='6' onkeypress='return validationFNAVGTot(event,this.id);'  value='";
                                    if (value1.emp_ts_weight != null) {
                                        html += value1.emp_ts_weight;
                                    }
                                    html += "'></td>";
                                    html += "<td class='tdRaw'><table border='0'><tr class='trSupervisor'><td class='trSupervisor' ><input class='txtC1' style='width:60px;' type='text' id='txtemp_ts_marks_client_1_" + c + "' name='txtemp_ts_marks_client_1_" + c + "' value='";
                                    if (value1.emp_ts_marks_client_1 != null) {
                                        html += value1.emp_ts_marks_client_1;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td><td class='trSupervisor'><input class='txtC2' style='width:60px;' type='text' id='emp_ts_marks_client_2_" + c + "' name='emp_ts_marks_client_2_" + c + "' value='";
                                    if (value1.emp_ts_marks_client_2 != null) {
                                        html += value1.emp_ts_marks_client_2;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='trSupervisor'><input class='txtC3' style='width:60px;' type='text' id='emp_ts_marks_client_3_" + c + "' name='emp_ts_marks_client_3_" + c + "' value='";
                                    if (value1.emp_ts_marks_client_3 != null) {
                                        html += value1.emp_ts_marks_client_3;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='trSupervisor'><input class='txtC3S' style='width:60px;' type='text' id='txtemp_tssupendmarks_" + c + "' name='txtemp_tssupendmarks_" + c + "' value='";
                                    if (value1.emp_ts_marks_client_summary != null) {
                                        html += value1.emp_ts_marks_client_summary;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='thModerator' width='75px;'><input class='txtTSMEA' style='width:60px;' type='text' id='txtemp_tsmodendachive_" + c + "' name='txtemp_tsmodendachive_" + c + "' value='";
                                    if (value1.emp_ts_mod_end_achive != null) {
                                        html += value1.emp_ts_mod_end_achive;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></td>";
                                    html += "<td class='thModerator' width='75px;'><input class='txtTSMEM' style='width:60px;' type='text' id='txtemp_tsmodendmarks_" + c + "' name='txtemp_tsmodendmarks_" + c + "' value='";
                                    if (value1.emp_ts_mod_end_marks != null) {
                                        html += value1.emp_ts_mod_end_marks;
                                    } else {
                                        html += "";
                                    }
                                    html += "'></tr></table></td>";
                                    html += "<td width='10px;'><input type='checkbox' name='chkemp_tsactiveflg_" + c + "' id='chkemp_tsactiveflg_" + c + "' value='1' ";
                                    if (value1.emp_ts_active_flg != null) {
                                        html += "checked";
                                    } else {
                                        html += "";
                                    }
                                    html += "></td></tr>";
                                    c++;
                                });
                                html += "<tr>";
                                html += "<td colspan='4'>Total as per 360 Degree Average</td><td><input style='width:60px;' type='text' id='txtev_ts_avg' name='txtev_ts_avg'></td>";
                                html += "<td><table  border='0'><tr class='trSupervisor'><td class='trSupervisor'><input class='txtC1A' style='width:60px;' type='text' id='ev_avg_client_1' name='ev_avg_client_1' value='";
<?php if ($EvalEmployee->ev_avg_client_1 != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_avg_client_1; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input class='txtC2A' style='width:60px;' type='text' id='ev_avg_client_2' name='ev_avg_client_2' value='";
<?php if ($EvalEmployee->ev_avg_client_2 != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_avg_client_2; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input class='txtC3A' style='width:60px;' type='text' id='ev_avg_client_3' name='ev_avg_client_3' value='";
<?php if ($EvalEmployee->ev_avg_client_3 != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_avg_client_3; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input class='txtCSA' style='width:60px;' type='text' id='emp_ts_marks_client_avg_summary' name='emp_ts_marks_client_avg_summary' value='";
<?php if ($EvalEmployee->emp_ts_marks_client_avg_summary != null) { ?>
                                    html += "<?php echo $EvalEmployee->emp_ts_marks_client_avg_summary; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input class='txtTSMEAA' style='width:60px;' type='text' id='txtev_ts_mod_end_ach_avg' name='txtev_ts_mod_end_ach_avg' value='";
<?php if ($EvalEmployee->ev_ts_mod_end_ach_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ts_mod_end_ach_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input class='txtTSMEMA' style='width:60px;' type='text' id='txtev_ts_mod_end_mark_avg' name='txtev_ts_mod_end_mark_avg' value='";
<?php if ($EvalEmployee->ev_ts_mod_end_mark_avg != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ts_mod_end_mark_avg; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td></tr></table></td>";
                                html += "<td class='tdRaw'></td></tr>";
                                html += "<tr>";
                                html += "<td colspan='4'>Total</td><td><input style='width:60px;' type='text' id='txtev_ts_tot' name='txtev_ts_tot'>";
                                html += "</td>";
                                html += "<td><table  border='0'><tr class='trSupervisor' ><td class='trSupervisor' ><input class='txtC1T' style='width:60px;' type='text' id='ev_tot_client_1' name='ev_tot_client_1' value='";
<?php if ($EvalEmployee->ev_tot_client_1 != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_tot_client_1; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor' ><input class='txtC2T' style='width:60px;' type='text' id='ev_tot_client_2' name='ev_tot_client_2' value='";
<?php if ($EvalEmployee->ev_tot_client_2 != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_tot_client_2; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'><input class='txtC3T' style='width:60px;' type='text' id='ev_tot_client_3' name='ev_tot_client_3' value='";
<?php if ($EvalEmployee->ev_tot_client_3 != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_tot_client_3; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='trSupervisor'  width='75px;'><input class='txtCST' style='width:60px;' type='text' id='emp_ts_marks_client_tot_summary' name='emp_ts_marks_client_tot_summary' value='";
<?php if ($EvalEmployee->emp_ts_marks_client_tot_summary != null) { ?>
                                    html += "<?php echo $EvalEmployee->emp_ts_marks_client_tot_summary; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input class='txtTSMEAT' style='width:60px;' type='text' id='txtev_ts_mod_end_ach_tot' name='txtev_ts_mod_end_ach_tot' value='";
<?php if ($EvalEmployee->ev_ts_mod_end_ach_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ts_mod_end_ach_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td>";
                                html += "<td class='thModerator' width='75px;'><input class='txtTSMEMT' style='width:60px;' type='text' id='txtev_ts_mod_end_mark_tot' name='txtev_ts_mod_end_mark_tot' value='";
<?php if ($EvalEmployee->ev_ts_mod_end_mark_tot != null) { ?>
                                    html += "<?php echo $EvalEmployee->ev_ts_mod_end_mark_tot; ?>";
<?php } else { ?>
                                    html += "";
<?php } ?>
                                html += "'></td></tr></table></td>";
                                html += "<td class='tdRaw'></td>";

                                html += "</tr></table>";
                            });

                            html += "<br/><input   class='editbutton' type='button' id='btnTSCalcuate' name='btnTSCalcuate' onclick='clacuateTS()' value='Calculate 360 Review' >";
                            html += "</div><br/>";

                        }

                    });

                    $("#360Div").append(html);
                    if (ev_id != "") {
                        theresixtycalAVGTOT();
                    }

                }
                function validationFNAVGTot(event, id) { //alert(event.keyCode);
                    var code = event.which || event.keyCode;

                    // 65 - 90 for A-Z and 97 - 122 for a-z 95 for _ 45 for - 46 for .
                    if (!(code >= 48 && code <= 57 || code == 46 || code == 11 || code == 10 || code == 13 || code == 177))
                    {
                        $('#' + id).val("");
                        return false;
                    }

                    if ($('#' + id).val() > 100) {
                        alert("<?php echo __('Maximum Amount is 100.00') ?>");
                        $('#' + id).val("");
                        return false;
                    }

                }

                function FTcalAVGTOT() {

                    var Avg = 0;
                    var Tot = 0;
                    var error = 0;
                    var FNPersentage = $("#txtFNPersentage").val();

                    $(".fnweight").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            error = 1;
                            Tot += 0;
                        } else {
                            Tot += parseFloat($(this).val());
                        }
                    });


                    Tot = parseFloat(Tot).toFixed(2)

                    Avg = (Tot * FNPersentage) / 100;
                    Avg = parseFloat(Avg).toFixed(2)

                    if (Tot != 100 && error == 0) {
                        alert("Functional Tasks Weight total should be equal to 100");

                    }

                    $("#txtev_fn_tot").val(Tot);
                    $("#txtev_fn_avg").val(Avg);
                }

                function MScalAVGTOT() {

                    var Avg = 0;
                    var Tot = 0;
                    var error = 0;
                    var MSPersentage = $("#txtMSPersentage").val();

                    $(".msweight").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            error = 1;
                            Tot += 0;
                        } else {
                            Tot += parseFloat($(this).val());
                        }
                    });


                    Tot = parseFloat(Tot).toFixed(2)

                    Avg = (Tot * MSPersentage) / 100;
                    Avg = parseFloat(Avg).toFixed(2)

                    if (Tot != 100 && error == 0 && $("#txtFNPersentage").val() != "") {
                        alert("Managerial Skills Weight total should be equal to 100");

                    }

                    $("#txtev_ms_tot").val(Tot);
                    $("#txtev_ms_avg").val(Avg);
                }

                function theresixtycalAVGTOT() {

                    var Avg = 0;
                    var Tot = 0;
                    var error = 0;
                    var TSPersentage = $("#txtMSPersentage").val();

                    $(".tsweight").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            error = 1;
                            Tot += 0;
                        } else {
                            Tot += parseFloat($(this).val());
                        }
                    });


                    Tot = parseFloat(Tot).toFixed(2)

                    Avg = (Tot * TSPersentage) / 100;
                    Avg = parseFloat(Avg).toFixed(2)

                    if (Tot != 100 && error == 0 && $("#txtTSPersentage").val() != "" ) {
                        alert("360 evaluation Weight total should be equal to 100");

                    }

                    $("#txtev_ts_tot").val(Tot);
                    $("#txtev_ts_avg").val(Avg);
                }

                function calTOT() {

                    var Avg = 0;
                    var Tot = 0;
                    var error = 0;

                    $(".total").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            error = 1;
                            Tot += 0;
                        } else {
                            Tot += parseFloat($(this).val());
                        }
                    });


                    Tot = parseFloat(Tot).toFixed(2)

                    if (Tot != 100 && error == 0 ) {
                        alert("Total Percentage should be equal to 100");

                    }

                    $("#txtTotalPersentage").val(Tot);

                }


                function clacuateFT() {

                    var SMAvg = 0;
                    var SMArchTot = 0;
                    var SMArchAvg = 0;
                    var SMerror = 0;
                    var SMi = 0;
                    var SMj = 0;
                    var SMTEM = 0;
                    var SMTotal = 0;
                    var FNPersentage = $("#txtFNPersentage").val();

                    $(".txtFTSMM").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            SMerror = 1;
                            //SMTot +=  0;
                        } else {
                            //Tot += parseFloat($(this).val());
                            SMTEM = (parseFloat($(this).val())) * (parseFloat($("#txtftweight_" + SMi).val())) / 100;
                            SMTotal += SMTEM;

                        }
                        SMi++;
                    });

                    $(".txtFTSMA").each(function(s) {
                        if ($(this).val() == '') {
                            SMerror = 1;
                            //SMTot +=  0;
                        } else {
                            SMArchTot += parseFloat($(this).val());

                        }
                        SMj++;
                    });

                    SMAvg = parseFloat(SMTotal * FNPersentage / 100).toFixed(2);
                    SMArchAvg = parseFloat(SMArchTot * FNPersentage / 100).toFixed(2);
                    SMArchAvg = parseFloat(SMArchTot/SMi).toFixed(2);
                    $("#txtev_fn_sup_mid_ach_avg").val(SMArchAvg);
                    //$("#txtev_fn_sup_mid_ach_tot").val(SMArchTot);

                    $("#txtev_fn_sup_mid_mark_avg").val(SMAvg);
                    $("#txtev_fn_sup_mid_mark_tot").val(SMTotal);


                    var SEAvg = 0;
                    var SEArchTot = 0;
                    var SEArchAvg = 0;
                    var SEerror = 0;
                    var SEi = 0;
                    var SEj = 0;
                    var SETEM = 0;
                    var SETotal = 0;

                    $(".txtFTSEM").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            SEerror = 1;
                            //SETot +=  0;
                        } else {
                            //Tot += parseFloat($(this).val());
                            SETEM = (parseFloat($(this).val())) * (parseFloat($("#txtftweight_" + SEi).val())) / 100;
                            SETotal += SETEM;

                        }
                        SEi++;
                    });

                    $(".txtFTSEA").each(function(s) {
                        if ($(this).val() == '') {
                            SEerror = 1;
                            //SETot +=  0;
                        } else {
                            SEArchTot += parseFloat($(this).val());

                        }
                        SEj++;
                    });


                    SEAvg = parseFloat(SETotal * FNPersentage / 100).toFixed(2);
                    SEArchAvg = parseFloat(SEArchTot * FNPersentage / 100).toFixed(2);

                    SEArchAvg = parseFloat(SEArchTot/SEj).toFixed(2);
                    $("#txtev_fn_sup_end_ach_avg").val(SEArchAvg);

                    $("#txtev_fn_sup_end_mark_avg").val(SEAvg);

                    $("#ev_fn_sup_end_mark_tot").val(SETotal);

                    //$("#ev_fn_sup_end_ach_tot").val(SEArchTot);


                    var MEAvg = 0;
                    var MEArchTot = 0;
                    var MEArchAvg = 0;
                    var MEerror = 0;
                    var MEi = 0;
                    var MEj = 0;
                    var METEM = 0;
                    var METotal = 0;


                    $(".txtFTMEM").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            MEerror = 1;
                            //METot +=  0;
                        } else {
                            //Tot += parseFloat($(this).val());
                            METEM = (parseFloat($(this).val())) * (parseFloat($("#txtftweight_" + MEi).val())) / 100;
                            METotal += METEM;

                        }
                        MEi++;
                    });

                    $(".txtFTMEA").each(function(s) {
                        if ($(this).val() == '') {
                            MEerror = 1;
                            //METot +=  0;
                        } else {
                            MEArchTot += parseFloat($(this).val());

                        }
                        MEj++;
                    });


                    MEAvg = parseFloat(METotal * FNPersentage / 100).toFixed(2);
                    MEArchAvg = parseFloat(MEArchTot * FNPersentage / 100).toFixed(2);

                    MEArchAvg = parseFloat(METotal/MEj).toFixed(2);
                    $("#txtev_fn_mod_end_ach_avg").val(MEArchAvg);

                    $("#txtev_fn_mod_end_mark_avg").val(MEAvg);

                    $("#ev_fn_mod_end_mark_tot").val(METotal);

                    //$("#ev_fn_mod_end_ach_tot").val(MEArchTot);


                }

                function clacuateMS() {

                    var MSMAvg = 0;
                    var MSMArchTot = 0;
                    var MSMArchAvg = 0;
                    var MSMerror = 0;
                    var MSMi = 0;
                    var MSMj = 0;
                    var MSMTEM = 0;
                    var MSMTotal = 0;
                    var MSPersentage = $("#txtMSPersentage").val();

                    $(".txtMSSMM").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            MSMerror = 1;
                            //MSMTot +=  0;
                        } else {
                            //Tot += parseFloat($(this).val());
                            MSMTEM = (parseFloat($(this).val())) * (parseFloat($("#txtmsweight_" + MSMi).val())) / 100;
                            MSMTotal += MSMTEM;

                        }
                        MSMi++;
                    });

                    $(".txtMSSMA").each(function(s) {
                        if ($(this).val() == '') {
                            MSMerror = 1;
                            //MSMTot +=  0;
                        } else {
                            MSMArchTot += parseFloat($(this).val());

                        }
                        MSMj++;
                    });

                    MSMAvg = parseFloat(MSMTotal * MSPersentage / 100).toFixed(2);
                    MSMArchAvg = parseFloat(MSMArchTot * MSPersentage / 100).toFixed(2);
                    
                    MSMArchAvg = parseFloat(MSMArchTot/MSMj).toFixed(2);
                    $("#txtev_ms_sup_mid_ach_avg").val(MSMArchAvg);
                    //$("#txtev_ms_sup_mid_ach_tot").val(MSMArchTot);

                    $("#txtev_ms_sup_mid_mark_avg").val(MSMAvg);
                    $("#txtev_ms_sup_mid_mark_tot").val(MSMTotal);


                    var MSEAvg = 0;
                    var MSEArchTot = 0;
                    var MSEArchAvg = 0;
                    var MSEerror = 0;
                    var MSEi = 0;
                    var MSEj = 0;
                    var MSETEM = 0;
                    var MSETotal = 0;

                    $(".txtMSSEM").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            MSEerror = 1;
                            //MSETot +=  0;
                        } else {
                            //Tot += parseFloat($(this).val());
                            MSETEM = (parseFloat($(this).val())) * (parseFloat($("#txtmsweight_" + MSEi).val())) / 100;
                            MSETotal += MSETEM;

                        }
                        MSEi++;
                    });

                    $(".txtMSSEA").each(function(s) {
                        if ($(this).val() == '') {
                            MSEerror = 1;
                            //MSETot +=  0;
                        } else {
                            MSEArchTot += parseFloat($(this).val());

                        }
                        MSEj++;
                    });


                    MSEAvg = parseFloat(MSETotal * MSPersentage / 100).toFixed(2);
                    MSEArchAvg = parseFloat(MSEArchTot * MSPersentage / 100).toFixed(2);

                    MSEArchAvg = parseFloat(MSEArchTot/MSEj).toFixed(2);
                    $("#txtev_ms_sup_end_ach_avg").val(MSEArchAvg);

                    $("#txtev_ms_sup_end_mark_avg").val(MSEAvg);

                    $("#txtev_ms_sup_end_mark_tot").val(MSETotal);

                    //$("#txtev_ms_sup_end_ach_tot").val(MSEArchTot);


                    var MMEAvg = 0;
                    var MMEArchTot = 0;
                    var MMEArchAvg = 0;
                    var MMEerror = 0;
                    var MMEi = 0;
                    var MMEj = 0;
                    var MMETEM = 0;
                    var MMETotal = 0;

                    $(".txtMSMEM").each(function(s) {
                        //alert($(this).val());
                        if ($(this).val() == '') {
                            MMEerror = 1;
                            //MMETot +=  0;
                        } else {
                            //Tot += parseFloat($(this).val());
                            MMETEM = (parseFloat($(this).val())) * (parseFloat($("#txtmsweight_" + MMEi).val())) / 100;
                            MMETotal += MMETEM;

                        }
                        MMEi++;
                    });

                    $(".txtMSMEA").each(function(s) {
                        if ($(this).val() == '') {
                            MMEerror = 1;
                            //MMETot +=  0;
                        } else {
                            MMEArchTot += parseFloat($(this).val());

                        }
                        MMEj++;
                    });


                    MMEAvg = parseFloat(MMETotal * MSPersentage / 100).toFixed(2);
                    MMEArchAvg = parseFloat(MMEArchTot * MSPersentage / 100).toFixed(2);

                    MMEArchAvg = parseFloat(MMETotal/MMEj).toFixed(2);
                    $("#txtev_ms_mod_end_ach_avg").val(MMEArchAvg);

                    $("#txtev_ms_mod_end_mark_avg").val(MMEAvg);

                    $("#txtev_ms_mod_end_mark_tot").val(MMETotal);

                    //$("#txtev_ms_mod_end_ach_tot").val(MMEArchTot);



                }

                function clacuateTS() { //alert("asdadssad");

                    var TSClient1Avg = 0;
                    var TSClient2Avg = 0;
                    var TSClient3Avg = 0;
                    var TSClient1Tot = 0;
                    var TSClient2Tot = 0;
                    var TSClient3Tot = 0;
                    var TSClientTot = 0;
                    var TSClientAVG = 0;
                    var TSClientAVGAVG = 0;
                    var TSMerror = 0;
                    var TSPersentage = $("#txtTSPersentage").val()
                    var TSC1i = 0;
                    var TSC1 = 0;
                    var TSC2i = 0;
                    var TSC2 = 0;
                    var TSC3i = 0;
                    var TSC4i = 0;
                    var TSC3 = 0;
                    var TSC1C2 = 0;
                    var TSC1C2C3 = 0;

                    $(".txtC1").each(function(s) {
                        if ($(this).val() == '') {
                            TSMerror = 1;
                        } else {

                            TSC1 = (parseFloat($(this).val())) * (parseFloat($("#txttsweight_" + TSC1i).val())) / 100;
                            $("#txtemp_tssupendmarks_" + TSC1i).val(TSC1.toFixed(2));
                            TSClient1Tot += TSC1;
                        }
                        TSC1i++;
                    });
                    TSClient1Avg = (TSClient1Tot * (($("#txtTSPersentage").val()) / 100)).toFixed(2);
                    TSClient1Tot = TSClient1Tot.toFixed(2);
                    $("#ev_avg_client_1").val(TSClient1Avg);
                    $("#ev_tot_client_1").val(TSClient1Tot);

                    $(".txtC2").each(function(s) {
                        if ($(this).val() == '') {
                            TSMerror = 1;
                        } else {

                            TSC2 = (parseFloat($(this).val())) * (parseFloat($("#txttsweight_" + TSC2i).val())) / 100;
                            TSClient2Tot += TSC2;
                        }
                        TSC2i++;
                    });
                    TSClient2Avg = (TSClient2Tot * ($("#txtTSPersentage").val()) / 100).toFixed(2);
                    TSClient2Tot = TSClient2Tot.toFixed(2);
                    $("#ev_avg_client_2").val(TSClient2Avg);
                    $("#ev_tot_client_2").val(TSClient2Tot);

                    $(".txtC3").each(function(s) {
                        if ($(this).val() == '') {
                            TSMerror = 1;
                        } else {

                            TSC3 = (parseFloat($(this).val()).toFixed(2)) * (parseFloat($("#txttsweight_" + TSC3i).val()).toFixed(2)) / 100;
                            TSClient3Tot += TSC3;
                        }
                        TSC3i++;
                    });
                    TSClient3Avg = (TSClient3Tot * ($("#txtTSPersentage").val()) / 100).toFixed(2);
                    TSClient3Tot = TSClient3Tot.toFixed(2);
                    $("#ev_avg_client_3").val(TSClient3Avg);
                    $("#ev_tot_client_3").val(TSClient3Tot);

                    $(".txtC3S").each(function(s) {
                        var i = 0;
                        TSC1C2C3 = 0;
                        if ($("#txtemp_ts_marks_client_1_" + TSC4i).val() != "") {
                            TSC1C2C3 += (parseFloat($("#txtemp_ts_marks_client_1_" + TSC4i).val())) * (parseFloat($("#txttsweight_" + TSC4i).val())) / 100;
                            i = 2;
                        }
                        if ($("#emp_ts_marks_client_2_" + TSC4i).val() != "") {
                            TSC1C2C3 += (parseFloat($("#emp_ts_marks_client_2_" + TSC4i).val())) * (parseFloat($("#txttsweight_" + TSC4i).val())) / 100;
                            i += 3;
                        }
                        if ($("#emp_ts_marks_client_3_" + TSC4i).val() != "") {
                            TSC1C2C3 += (parseFloat($("#emp_ts_marks_client_3_" + TSC4i).val())) * (parseFloat($("#txttsweight_" + TSC4i).val())) / 100;
                            i += 4;
                        }
                        //alert(parseFloat($("#txtemp_ts_marks_client_1_"+TSC4i).val()) *( parseFloat($("#txttsweight_"+TSC4i).val()))/100 );
                        switch (i)
                        {
                            case 0:
                                alert('All cient are not completed');
                                break;
                            case 2:
                                TSC1C2C3 = TSC1C2C3.toFixed(2);
                                break;
                            case 3:
                                TSC1C2C3 = TSC1C2C3.toFixed(2);
                                break;
                            case 4:
                                TSC1C2C3 = TSC1C2C3.toFixed(2);
                                break;
                            case 5:
                                TSC1C2C3 = parseFloat(TSC1C2C3 / 2).toFixed(2);
                                break;
                            case 6:
                                TSC1C2C3 = parseFloat(TSC1C2C3 / 2).toFixed(2);
                                break;
                            case 7:
                                TSC1C2C3 = parseFloat(TSC1C2C3 / 2).toFixed(2);
                                break;
                            case 9:
                                TSC1C2C3 = parseFloat(TSC1C2C3 / 3).toFixed(2);
                                break;
                        }

                        $("#txtemp_tssupendmarks_" + TSC4i).val(TSC1C2C3);
                        TSClientTot = parseFloat(TSClientTot) + parseFloat(TSC1C2C3);
                        $("#emp_ts_marks_client_tot_summary").val(TSClientTot);
                        TSClientAVGAVG = parseFloat((parseFloat(TSClientTot).toFixed(2)) * (parseFloat($("#txtTSPersentage").val()) / 100)).toFixed(2);
                        $("#emp_ts_marks_client_avg_summary").val(TSClientAVGAVG);
                        TSC4i++;
                    });

                            
                                 var TMEAvg = 0;
                                 var TMEArchTot = 0;  
                                 var TMEArchAvg = 0;
                                 var TMEerror = 0;
                                 var TMEi=0 ;
                                 var TMEj=0 ;
                                 var TMETEM = 0;
                                 var TMETotal=0;

                            $(".txtTSMEM").each( function(s) {
                                //alert($(this).val());
                                if($(this).val() == ''){  
                                     TMEerror = 1;
                                     //TMETot +=  0;
                                 }else{
                                     //Tot += parseFloat($(this).val());
                                     TMETEM = (parseFloat($(this).val()))*( parseFloat($("#txttsweight_"+TMEi).val()))/100;
                                     TMETotal+=TMETEM; 
                                     
                                 }
                                TMEi++;
                            });
                            
                            $(".txtTSMEA").each( function(s) {
                                if($(this).val() == ''){  
                                     TMEerror = 1;
                                     //TMETot +=  0;
                                 }else{
                                     TMEArchTot += parseFloat($(this).val());
                                     
                                 }
                                TMEj++;
                            });
                            
                                 
                                 TMEAvg = parseFloat(TMETotal*TSPersentage/100).toFixed(2);
                                 TMEArchAvg = parseFloat(TMEArchTot*TSPersentage/100).toFixed(2);

                                 TMEArchAvg = parseFloat(TMEArchTot/TMEj).toFixed(2);
                                 $("#txtev_ts_mod_end_ach_avg").val(TMEArchAvg);
                                 
                                 $("#txtev_ts_mod_end_mark_avg").val(TMEAvg);
                                 
                                 $("#txtev_ts_mod_end_mark_tot").val(TMETotal);                                 

                                 //$("#txtev_ts_mod_end_ach_tot").val(TMEArchTot);
//                                 

                }

                function clacuateFinal() {
                    var fntotal = 0;
                    var mstotal = 0;
                    var tstotal = 0;
                    var stotal = 0;

                    if ($("#txtFNPersentage").val() != '' && $("#ev_fn_sup_end_mark_tot").val() != '') {
                        fntotal = parseFloat($("#ev_fn_sup_end_mark_tot").val()).toFixed(2) * parseFloat(($("#txtFNPersentage").val()) / 100).toFixed(2);
                    }
                    if ($("#txtMSPersentage").val() != '' && $("#txtev_ms_sup_end_mark_tot").val() != '') {
                        mstotal = parseFloat($("#txtev_ms_sup_end_mark_tot").val()).toFixed(2) * parseFloat(($("#txtMSPersentage").val()) / 100).toFixed(2);
                    }
//                                if($("#txtTSPersentage").val() != '' && $("#txtev_ts_sup_end_mark_tot").val() != ''){
//                                tstotal = parseFloat($("#txtev_ts_sup_end_mark_tot").val()).toFixed(2)*parseFloat(($("#txtTSPersentage").val())/100).toFixed(2);
//                                }
                    //alert(fntotal); alert(mstotal); alert(tstotal);
                    if ($("#txtTSPersentage").val() != '' && $("#txtev_ts_sup_end_mark_tot").val() != '') {
                        tstotal = parseFloat($("#emp_ts_marks_client_avg_summary").val()).toFixed(2);
                    }


                    stotal = parseFloat(parseFloat(fntotal) + parseFloat(mstotal) + parseFloat(tstotal)).toFixed(2);

                    $("#txtSEMark").val(stotal);

                    var Mfntotal = 0;
                    var Mmstotal = 0;
                    var Mtstotal = 0;
                    var Mstotal = 0;

                    if ($("#txtFNPersentage").val() != '' && $("#ev_fn_mod_end_mark_tot").val() != '') {
                        Mfntotal = parseFloat($("#ev_fn_mod_end_mark_tot").val()).toFixed(2) * parseFloat(($("#txtFNPersentage").val()) / 100).toFixed(2);
                    }
                    if ($("#txtMSPersentage").val() != '' && $("#txtev_ms_mod_end_mark_tot").val() != '') {
                        Mmstotal = parseFloat($("#txtev_ms_mod_end_mark_tot").val()).toFixed(2) * parseFloat(($("#txtMSPersentage").val()) / 100).toFixed(2);
                    }
                    if ($("#txtTSPersentage").val() != '' && $("#txtev_ts_mod_end_mark_tot").val() != '') {
                        Mtstotal = parseFloat($("#txtev_ts_mod_end_mark_tot").val()).toFixed(2) * parseFloat(($("#txtTSPersentage").val()) / 100).toFixed(2);
                    }
                    //alert(fntotal); alert(mstotal); alert(tstotal);
                    Mstotal = Mfntotal + Mmstotal + Mtstotal;
                    Mstotal = parseFloat(Mstotal).toFixed(2);

                    $("#txtMEMark").val(Mstotal);
                    
                    var grade=getfinalGrade($("#txtSEMark").val());
                    document.getElementById('supgrade').innerHTML = grade;
                    
                    if($("#txtMEMark").val()!= ""){
                    var grade=getfinalGrade($("#txtMEMark").val());
                    document.getElementById('modgrade').innerHTML = grade;
                    }
                    //$("#supgrade").text(grade);
                    //$("#supgrade").text("dasadadsa");
                }

                    
                    

                function sleep(milliseconds) {
                    var start = new Date().getTime();
                    for (var i = 0; i < 1e7; i++) {
                        if ((new Date().getTime() - start) > milliseconds) {
                            break;
                        }
                    }
                }

function getfinalGrade(Marks){
    
                     var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";
                     var tst;
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?php echo url_for('evaluation/AjaxFinalRate') ?>",
                        data: {ev_id: ev_id, marks: Marks },
                        dataType: "json",
                        success: function(data) {
                            tst = data;
                        }
                        
                    });   
                    return tst;
}

</script>
