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
        <div class="mainHeading"><h2><?php echo __("Define Function \ Task") ?></h2></div>
            <?php echo message() ?>
            <?php echo $form['_csrf_token']; ?>
        <form name="frmSave" id="frmSave" method="post"  action="">

               <br class="clear"/>         
          <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name")?> <span class="required">*</span></label>
                </div>
                
               <div class="centerCol" style="padding-left: 10px;">
                    <input type="text" name="txtEmployeeName" disabled="disabled"
               id="txtEmployee" value="<?php if($EvalFunctionTask->emp_number){ echo $EvalFunctionTask->Employee->emp_display_name; }else{ echo $EmpDisplayName; }  ?>" readonly="readonly" style="color: #222222"/>
               <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php if($EvalFunctionTask->emp_number){ echo $EvalFunctionTask->emp_number; }else{ echo $EmployeeNumber; } ?>"/> 
               </div>
                 <div class="centerCol">
                <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
            </div>
            
            <br class="clear"/>
                        <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Work/Professional Category") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                 <select name="cmbCompEval" id="cmbCompEval" class="formSelect"  tabindex="4">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($EvaluationList as $Evaluation) {
 ?>
                            <option value="<?php echo $Evaluation->eval_id ?>" <?php if($Evaluation->eval_id== $EvalFunctionTask->eval_id){ echo " selected=selected"; }  ?> ><?php
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
<?php                     } ?>
                </select>
            </div>
            <input id="txtid"  name="txtid" type="hidden"  class="formInputText" maxlength="10" value="<?php echo $EvalFunctionTask->ft_id; ?>" />
            <br class="clear"/>

             <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Function \ Task Title") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtFTName"  name="txtFTName" type="text"  class="formInputText" value="<?php echo $EvalFunctionTask->ft_title; ?>" maxlength="120" />
            </div>
            <br class="clear"/>
                         <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Indicator/s") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtIndicator"  name="txtIndicator" type="text"  class="formInputText" ><?php echo $EvalFunctionTask->ft_target_indicater; ?></textarea>
            </div>

            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("From Date") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                    <input id="txtFromDate"  name="txtFromDate" type="text"  class="formInputText" maxlength="10" value="<?php echo $EvalFunctionTask->ft_from_date; ?>" <?php echo $disabled; ?> />

            </div>
            
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("To Date") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                    <input id="txtToDate"  name="txtToDate" type="text"  class="formInputText" maxlength="10" value="<?php echo $EvalFunctionTask->ft_to_date; ?>" <?php echo $disabled; ?> />

            </div>
            
            <br class="clear"/>
             <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Description") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtFTDesc"  name="txtFTDesc" type="text"  class="formInputText" ><?php echo $EvalFunctionTask->ft_description; ?></textarea>
            </div>

            <br class="clear"/>
            <div class="leftCol">
                    <label for="txtLocationCode"><?php echo __("Active") ?> </label>
                </div>
                <div class="centerCol">
                    <input id="chkActive"  name="chkActive" type="checkbox"  class="formInputText" value="1" <?php
            if ($EvalFunctionTask->ft_active_flg == "1") {
                echo "checked";
            }
                ?> <?php echo $disabled; ?> />
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
    
                          $('#empRepPopBtn').click(function() {

                                var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');
                                if(!popup.opener) popup.opener=self;
                                popup.focus();
                            });
    
     $("#txtFromDate").datepicker({ dateFormat: '<?php echo $inputDate; ?>' });
     $("#txtToDate").datepicker({ dateFormat: '<?php echo $inputDate; ?>' });

                       //Validate the form
                       $("#frmSave").validate({

            rules: {
                cmbCompEval:{required: true},
                txtFTName: { required: true,noSpecialCharsOnly: true, maxlength:200 },
		txtIndicator: { required: true,noSpecialCharsOnly: true, maxlength:500 },
                txtFromDate: {noSpecialCharsOnly: true, required: true },
                txtToDate: {noSpecialCharsOnly: true , required: true},
                txtFTDesc: { required: true,noSpecialCharsOnly: true, maxlength:500 },
                txtEmpId:{required: true}
                
            },
            messages: {
                cmbCompEval:{required:"<?php echo __("This field is required") ?>"},
                txtFTName: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 200 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
		txtIndicator: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 500 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtFromDate:{ required:"<?php echo __("This field is required") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtToDate:{required:"<?php echo __("This field is required") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtFTDesc: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 200 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtEmpId:{required:"<?php echo __("This field is required") ?>"}
                

            }
        });

                       // When click edit button
                       $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                       $("#editBtn").click(function() {

                           var editMode = $("#frmSave").data('edit');
                           if (editMode == 1) {
                               // Set lock = 1 when requesting a table lock

            location.href="<?php echo url_for('evaluation/UpdateEvalFunctionTask?id=' . $encrypt->encrypt($EvalFunctionTask->ft_id) . '&lock=1') ?>";
                           }
                           else {

                               $('#frmSave').submit();
                           }


                       });

                       //When Click back button
                       $("#btnBack").click(function() {
                           location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/EvalFunctionTask')) ?>";
                       });

                       //When click reset buton
                       $("#btnClear").click(function() {
                           // Set lock = 0 when resetting table lock
                           <?php if($EvalFunctionTask->ft_id){ ?>
                           location.href="<?php echo url_for('evaluation/UpdateEvalFunctionTask?id=' . $encrypt->encrypt($EvalFunctionTask->ft_id) . '&lock=0') ?>";
                           <?php }else{?>
                           location.href="<?php echo url_for('evaluation/UpdateEvalFunctionTask') ?>";
                           <?php } ?>
                        });
                   });
                   
                   
                        function SelectEmployee(data){

                            myArr = data.split('|');
                            $("#txtEmpId").val("");
                            $("#txtEmpId").val(myArr[0]);
                            $("#txtEmployee").val(myArr[1]);
                        }
                   
</script>
