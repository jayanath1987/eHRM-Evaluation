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
    display: none;
}
.trSupervisor{


    display: none;
}

.trModerator{


    display: none;
}

.thModerator{

    background-color: #0000FF;
    display: none;
}

/*.thTopCnt{
     display: none;
background-color: yellowgreen;
}*/

.thTopRaw{
    display: none;
background-color: yellowgreen;
}

.tdRaw{
    display: none;
background-color: yellowgreen;
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
        <div class="mainHeading"><h2><?php echo __("Define Employee Evaluation") ?></h2></div>
            <?php echo message() ?>
            <?php echo $form['_csrf_token']; ?>
        <form name="frmSave" id="frmSave" method="post"  action="">

             <br class="clear"/>    
               
                        <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Work/Professional Category") ?> <span class="required">*</span></label>
            </div>
               
            <div class="centerCol">
                 <select name="cmbCompEval" id="cmbCompEval" class="formSelect"  tabindex="4">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <?php foreach ($EvaluationList as $Evaluation) {
 ?>
                            <option value="<?php echo $Evaluation->eval_id ?>" <?php if($Evaluation->eval_id== $EvalEmployee->eval_id){ echo " selected=selected"; }  ?> ><?php
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
               <br class="clear"/>         

               
                     <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name")?> <span class="required">*</span></label>
                </div>
                
               <div class="centerCol" >
                   <input class="formInputText" style="padding-left: 0px;" type="text" name="txtEmployeeName" disabled="disabled"
               id="txtEmployee" value="<?php if($EvalEmployee->emp_number){ echo $EvalEmployee->Employee->emp_display_name; }else{ echo $EmpDisplayName; }  ?>" readonly="readonly" style="color: #222222"/>
               <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php if($EvalEmployee->emp_number){ echo $EvalEmployee->emp_number; }else{ echo $EmployeeNumber; } ?>"/> 
               </div>
                 <div class="centerCol">
                     <input class="button"  style="margin-top: 10px;" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
            </div>
            
           
            <input id="txtid"  name="txtid" type="hidden"  class="formInputText" maxlength="10" value="<?php echo $EvalEmployee->ev_id; ?>" />
            <br class="clear"/>

             
            <div class="leftCol" style="width: 200px;">
                    <label for="txtLocationCode"><?php echo __("Functional Review Active") ?> </label>
                </div>
            
            <div class="centerCol" style="width: 80px;">
                <input id="chkFTActive"  name="chkFTActive" type="checkbox"   value="1"  <?php
            if ($EvalEmployee->ev_fn_rv_active_flg == "1") {
                echo "checked";
               
            }
                ?> <?php echo $disabled; ?> />
                </div>
            <div class="centerCol"><input style="width: 50px;" class="total" type="text" name="txtFNPersentage"  id="txtFNPersentage" maxlength="6" onkeypress='return validationFNAVGTot(event,this.id);' value="<?php echo $EvalEmployee->ev_fn_rv_percentage; ?>" > % </div>
            
 <br class="clear"/>
 
 <div name="FTDiv" id="FTDiv"></div>
 

             
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
                <div class="centerCol"><input style="width: 50px;" class="total" type="text" name="txtMSPersentage"  id="txtMSPersentage" maxlength="6" onkeypress='return validationFNAVGTot(event,this.id);' value="<?php echo $EvalEmployee->ev_ms_rv_percentage; ?>" > % </div>
 <br class="clear"/>
 
 <div name="MSDiv" id="MSDiv"  ></div>
 
             
            <div class="leftCol" style="width: 200px;">
                    <label for="txtLocationCode"><?php echo __("360 Degree Review Active") ?> </label>
                </div>
            
                <div class="centerCol"  style="width: 80px;">
                    <input id="chk360Active"  name="chk360Active" type="checkbox"   value="1" <?php
            if ($EvalEmployee->ev_ts_rv_active_flg   == "1") {
                echo "checked";
            }
                ?> <?php echo $disabled; ?> />
                </div>
                <div class="centerCol"><input style="width: 50px;" class="total" type="text" name="txtTSPersentage"  id="txtTSPersentage" maxlength="6" onkeypress='return validationFNAVGTot(event,this.id);' value="<?php echo $EvalEmployee->ev_ts_rv_percentage; ?>" > % </div>

            <br class="clear"/>
            
            
            
 <div name="360Div" id="360Div"  >
     <br class="clear"/>
          <div class="leftCol">
         <label for="txtLocationCode"><?php echo __("") ?> </label>
     </div>
     <div class="centerCol">
         <label for="txtLocationCode"><?php echo __("E-Mail") ?> </label>
     </div> 
     <div class="centerCol">
         <label for="txtLocationCode"><?php echo __("Name") ?> </label>
     </div> 
     <div class="centerCol">
         <label for="txtLocationCode"><?php echo __("Designation") ?> </label>
     </div> 
     
     <br class="clear"/>
     
     <div class="leftCol">
         <label for="txtLocationCode"><?php echo __("External Review 1") ?> </label>
     </div>
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient1"  id="txtClient1" value="<?php echo $EvalEmployee->ev_email_client_1; ?>"  >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient1Name"  id="txtClient1Name" value="<?php echo $EvalEmployee->ev_name_client_1; ?>"  >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient1Designation"  id="txtClient1Designation" value="<?php echo $EvalEmployee->ev_desg_client_1; ?>"  >
         
                          <select style="width: 70px;" name="cmbClient1" id="cmbClient1" >
                           <option value=""><?php echo __("--Select--") ?></option>
                           <option value="1" <?php if($EvalEmployee->ev_level_client_1 == "1" ){ echo " selected=selected"; }  ?> ><?php echo __("Top Manager") ?></option>
                           <option value="2" <?php if($EvalEmployee->ev_level_client_1 == "2" ){ echo " selected=selected"; }  ?> ><?php echo __("Mid Manager") ?></option>
                          </select>
         
     </div> 
     
     
     <br class="clear"/>
     <div class="leftCol">
         <label for="txtLocationCode"><?php echo __("External Review 2") ?> </label>
     </div>
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient2"  id="txtClient2" value="<?php echo $EvalEmployee->ev_email_client_2; ?>" >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient2Name"  id="txtClient2Name" value="<?php echo $EvalEmployee->ev_name_client_2; ?>"  >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient2Designation"  id="txtClient2Designation" value="<?php echo $EvalEmployee->ev_desg_client_2; ?>"  >
         <select style="width: 70px;" name="cmbClient2" id="cmbClient2" >
                           <option value=""><?php echo __("--Select--") ?></option>
                           <option value="1" <?php if($EvalEmployee->ev_level_client_2 == "1" ){ echo " selected=selected"; }  ?> ><?php echo __("Top Manager") ?></option>
                           <option value="2" <?php if($EvalEmployee->ev_level_client_2 == "2" ){ echo " selected=selected"; }  ?> ><?php echo __("Mid Manager") ?></option>
                          </select>
     </div> 
     
     <br class="clear"/>
     <div class="leftCol">
         <label for="txtLocationCode"><?php echo __("Internal Review ") ?> </label>
     </div>
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient3"  id="txtClient3" value="<?php echo $EvalEmployee->ev_email_client_3; ?>" >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient3Name"  id="txtClient3Name" value="<?php echo $EvalEmployee->ev_name_client_3; ?>"  >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient3Designation"  id="txtClient3Designation" value="<?php echo $EvalEmployee->ev_desg_client_3; ?>"  >
         <select style="width: 70px;" name="cmbClient3" id="cmbClient3" >
                           <option value=""><?php echo __("--Select--") ?></option>
                           <option value="1" <?php if($EvalEmployee->ev_level_client_3 == "1" ){ echo " selected=selected"; }  ?> ><?php echo __("Top Manager") ?></option>
                           <option value="2" <?php if($EvalEmployee->ev_level_client_3 == "2" ){ echo " selected=selected"; }  ?> ><?php echo __("Mid Manager") ?></option>
                          </select>
     </div> 
     
<!--     <br class="clear"/>
     
     <div class="leftCol">
         <label for="txtLocationCode"><?php echo __("Client 4") ?> </label>
     </div>
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient4"  id="txtClient4" value="<?php echo $EvalEmployee->ev_email_client_4; ?>" >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient4Name"  id="txtClient4Name" value="<?php echo $EvalEmployee->ev_name_client_4; ?>"  >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient4Designation"  id="txtClient4Designation" value="<?php echo $EvalEmployee->ev_desg_client_4; ?>"  >
         <select style="width: 70px;" name="cmbClient4" id="cmbClient4" >
                           <option value=""><?php echo __("--Select--") ?></option>
                           <option value="1" <?php if($EvalEmployee->ev_level_client_4 == "1" ){ echo " selected=selected"; }  ?> ><?php echo __("Top Manager") ?></option>
                           <option value="2" <?php if($EvalEmployee->ev_level_client_4 == "2" ){ echo " selected=selected"; }  ?> ><?php echo __("Mid Manager") ?></option>
                          </select>
     </div> 
     
     <br class="clear"/>
     
     <div class="leftCol">
         <label for="txtLocationCode"><?php echo __("Client 5") ?> </label>
     </div>
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient5"  id="txtClient5" value="<?php echo $EvalEmployee->ev_email_client_5; ?>" >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient5Name"  id="txtClient5Name" value="<?php echo $EvalEmployee->ev_name_client_5; ?>"  >
     </div> 
     <div class="centerCol">
         <input style="width: 100px;" class="" type="text" name="txtClient5Designation"  id="txtClient5Designation" value="<?php echo $EvalEmployee->ev_desg_client_5; ?>"  >
         <select style="width: 70px;" name="cmbClient5" id="cmbClient5" >
                           <option value=""><?php echo __("--Select--") ?></option>
                           <option value="1" <?php if($EvalEmployee->ev_level_client_5 == "1" ){ echo " selected=selected"; }  ?> ><?php echo __("Top Manager") ?></option>
                           <option value="2" <?php if($EvalEmployee->ev_level_client_5 == "2" ){ echo " selected=selected"; }  ?> ><?php echo __("Mid Manager") ?></option>
                          </select>
     </div> -->
     
     <br class="clear"/>
 </div>            
            
 
 <br class="clear"/>
            <div class="leftCol" style="width: 200px;">
                    <label for="txtLocationCode"><?php echo __("Total Percentage") ?> </label>
                </div>
 <div class="centerCol"><input style="width: 50px;" class="" type="text" name="txtTotalPersentage"  id="txtTotalPersentage" maxlength="6" readonly="readonly" > % </div>
            
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
    var comeval= null;
    var a=0;
    var b=0;
    var c=0;
    var ajaxFT = 0;
    var ajaxMS = 0;
    var ajaxTS = 0;  
    var startdate ;
    var enddate ;
    
    function getyear(){
                <?php 
        if($EvalEmployee->emp_number){ ?>
            var en = "<?php echo $EvalEmployee->emp_number ?>";
            getEmplyeeEvaldetail(en);
        <?php
         }else{ ?>
            var en = "<?php echo $EmployeeNumber ?>"; 
            getEmplyeeEvaldetail(en);
        <?php } ?>
    }
    
    $(document).ready(function() {
        
        $("#cmbCompEval").change( function() {
            $.ajax({
            type: "POST",
            async:false,
            url: "<?php echo url_for('evaluation/Year') ?>",
            data: { id : this.value },
            dataType: "json",
            success: function(data){
                startdate = data+"-01-01";
                enddate = data+"-12-31";
            },
        });
        getyear();
        });
     calTOT();
        

         
     
        buttonSecurityCommon("null","null","editBtn","null");
        
        $("#FTDiv").hide();
        $("#MSDiv").hide();
        $("#360Div").hide();
        

        
<?php if ($editMode == true) { ?>
                              $('#frmSave :input').attr('disabled', true);
                              $('#editBtn').removeAttr('disabled');
                              $('#btnBack').removeAttr('disabled');
<?php } ?>
    
                          $('#empRepPopBtn').click(function() {
                              
                              if($("#cmbCompEval").val() == "all"){
                                  alert("please select Work/Professional Category");
                                  return false;
                              }

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
                txtFromDate: {noSpecialCharsOnly: true, required: true },
                txtToDate: {noSpecialCharsOnly: true , required: true},
                txtFTDesc: { required: true,noSpecialCharsOnly: true, maxlength:500 },
                txtEmpId:{required: true}
                
            },
            messages: {
                cmbCompEval:{required:"<?php echo __("This field is required") ?>"},               
                txtFTName: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 200 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
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

            location.href="<?php echo url_for('evaluation/UpdateEmployeeEvaluation?id=' . $encrypt->encrypt($EvalEmployee->ev_id) . '&lock=1') ?>";
                           }
                           else {
                               
                               var errors = 0;
                                if( document.getElementById("chkFTActive").checked ) { 
                                    
                               if($("#txtFNPersentage").val() == "" ){
                                   alert("Functional Review percentage invalid");
                                   errors++;
                               }
                               FTcalAVGTOT();
                               if($("#txtev_fn_tot").val() == "" ){
                                   alert("Functional Weight Total should be 100");
                                   errors++;
                               }
                              }
                                
                               if( document.getElementById("chkMSActive").checked ) {  
                               if($("#txtMSPersentage").val() == "" ){
                                   alert("Managerial Skills Review percentage invalid");
                                   errors++;
                               }
                               if($("#txtev_ms_tot").val() == "" ){
                                   alert("Managerial Skills Weight Total should be 100");
                                   errors++;
                               }
                               }
                               
                               
                               if( document.getElementById("chk360Active").checked ) { 
                               
                               if($("#txtTSPersentage").val() == "" ){
                                   alert("360 Degree Review percentage invalid");
                                   errors++;
                               }


                               if($("#txtev_ts_tot").val() == "" ){
                                   alert("360 Degree Weight Total should be 100");
                                   errors++;
                               }
                               
                               }
                               
                               if( $("#txtTotalPersentage").val() == "" ){
                                     alert("Total Percentage should be equal to 100");
                                      errors++;
                                 }
                                 
                                 if($("#chk360Active").is(':checked')){ 
                                    if( $("#txtClient1").val() == "" ){
                                     alert("Client1 email required");
                                      errors++;
                                    }
                                    if( $("#txtClient2").val() == "" ){
                                     alert("Client2 email required");
                                      errors++;
                                    }
                                    if( $("#txtClient3").val() == "" ){
                                     alert("Client3 email required");
                                      errors++;
                                    }
                                }
                               
                               if(errors == 0){
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
                           <?php if($EvalEmployee->ev_id){ ?>
                           location.href="<?php echo url_for('evaluation/UpdateEmployeeEvaluation?id=' . $encrypt->encrypt($EvalEmployee->ev_id) . '&lock=0') ?>";
                           <?php }else{?>
                           location.href="<?php echo url_for('evaluation/UpdateEmployeeEvaluation') ?>";
                           <?php } ?>
                        });
                        
                        
                        
                        $("#chkFTActive").change(function() {
                            if(this.checked) {
                                $("#FTDiv").show();
                            }else{
                                 $("#FTDiv").hide();
                            }
                        });
                        $("#chkMSActive").change(function() {
                            if(this.checked) {
                                $("#MSDiv").show();
                            }else{
                                 $("#MSDiv").hide();
                            }
                        });
                         $("#chk360Active").change(function() {
                            if(this.checked) {
                                $("#360Div").show();
                            }else{
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


     <?php  if ($EvalEmployee->ev_fn_rv_active_flg == "1") { ?>  

               getEmplyeeEvaldetail("<?php echo $EvalEmployee->emp_number ?>");

               $("#FTDiv").show();
              
               
       <?php     }  ?>
      <?php  if ($EvalEmployee->ev_ms_rv_active_flg == "1") { ?>  
          getEmplyeeEvaldetail("<?php echo $EvalEmployee->emp_number ?>");

               $("#MSDiv").show();
               
       <?php     }  ?>
      <?php  if ($EvalEmployee->ev_ts_rv_active_flg == "1") { ?>  

          getEmplyeeEvaldetail("<?php echo $EvalEmployee->emp_number ?>");

               $("#360Div").show();
               
       <?php     }  ?>          
                     
                     
                     
                   });
                   
                   
                        function SelectEmployee(data){

                            myArr = data.split('|');
                            $("#txtEmpId").val("");
                            $("#txtEmpId").val(myArr[0]);
                            $("#txtEmployee").val(myArr[1]);
                            getEmplyeeEvaldetail(myArr[0]);
                        }
                        
                         function getEmplyeeEvaldetail(eno){
                             //alert(eno);
                             comeval = $("#cmbCompEval").val();
                             if(ajaxFT == 0 ){
                             FTDetaiils(eno,comeval);  
                             //ajaxFT++;
                             }
                             if(ajaxMS==0){
                             SMDetaiils(eno,comeval);
                             ajaxMS++;
                             }
                             if(ajaxTS==0){
                             threesixtyDetaiils(eno,comeval);
                             ajaxTS++;
                             }
                             
                             
                         }
                         
                         function FTDetaiils(eno,comeval){
                             var html="";
                             var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";
                             
                              $.ajax({
                                type: "POST",
                                async:false,
                                url: "<?php echo url_for('evaluation/AjaxGetFTData') ?>",
                                data: { comeval: comeval, eno : eno , ev_id: ev_id },
                                dataType: "json",
                                success: function(data){
                                   html+="<div><br>";
                                    $.each(data, function(key, value) {
                                        html+="<table border='1'><tr>";
                                        html+="<th width='300px;'>Function/Task</th>";
                                        html+="<th width='60px;'>From</th>";
                                        html+="<th width='60px;'>To</th>";
                                        html+="<th width='75px;'>Target / Indicator</th>";
                                        html+="<th width='50px;'>Weight</th>";
                                        html+="<th width='450px;' class='thTopRaw' >";
                                        html+="<table border='1' class='thTopCnt' width='450px;'><caption></caption><col /><col /><col /><col /><col /><col /><tbody>";
                                        html+="<tr><td class='thSupervisor' colspan='4' style='text-align:center;margin-left:auto;margin-right:auto; width:300px;'>Supervisor</td>";
                                        html+="<td colspan='2' rowspan='2' class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Moderator</td>";
                                        html+="</tr><tr>";
                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Mid Year</td>";
                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>End Year</td>";
                                        html+="</tr><tr>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto;  width:75px;'>Archive</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Archive</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="<td  class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Archive</td>";
                                        html+="<td class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="</tr></tbody></table>";
                                        html+="</th><th width='1px;'>Active</th><th width='1px;'>Approve</th></tr>";
                                        $.each(value, function(key1, value1) {
                                        //console.log(value1.emp_number);
                                         html+="<tr><td><input style='width:60px;' type='hidden' id='txtfnid_"+a+"' name='txtfnid_"+a+"' value='"+value1.ft_id+"'>";
                                         html+="<input style='width:60px;' type='hidden' id='txtftid[]' name='txtftid[]' value='"+a+"'>";
                                         if(value1.ft_title!= null){ 
                                         html+=value1.ft_title; 
                                         }
                                         html+="</td>";
                                         html+="<td><input style='width:60px;' type='text' id='txtfnfromdate_"+a+"' name='txtfnfromdate_"+a+"' value='";
                                         if(value1.ft_from_date!= null){
                                         html+=value1.ft_from_date;
                                         }
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' type='text' id='txtfttodate_"+a+"' name='txtfttodate_"+a+"' value='";
                                         if(value1.ft_to_date!= null){
                                         html+=value1.ft_to_date;
                                         }
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' type='text' id='txtfttargetindicater_"+a+"' name='txtfttargetindicater_"+a+"' value='";
                                         if(value1.ft_target_indicater!= null){
                                         html+=value1.ft_target_indicater;
                                         }
                                         html+="' title='";
                                         if(value1.ft_target_indicater!= null){
                                         html+=value1.ft_target_indicater;
                                         }
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' class='fnweight' type='text' id='txtftweight_"+a+"' name='txtftweight_"+a+"' maxlength='6' onkeypress='return validationFNAVGTot(event,this.id);'  value='";
                                         if(value1.ft_weight!= null){
                                         html+=value1.ft_weight;
                                         }
                                         html+="'></td>";
                                         html+="<td class='tdRaw' ><table border='0'><tr><td  class='trSupervisor' width='75px;' ><input class='trSupervisor'style='width:60px;' type='text' id='txtftsupmidachive_"+a+"' name='txtftsupmidachive_"+a+"' value='";
                                         if(value1.ft_sup_mid_achive!= null){
                                         html+=value1.ft_sup_mid_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td><td  class='trSupervisor' width='75px;'><input   style='width:60px;' type='text' id='txtftsupmidmarks_"+a+"' name='txtftsupmidmarks_"+a+"' value='";
                                         if(value1.ft_sup_mid_marks!= null){
                                         html+=value1.ft_sup_mid_marks;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td  class='trSupervisor' width='75px;'><input   style='width:60px;' type='text' id='txtftsupendachive_"+a+"' name='txtftsupendachive_"+a+"' value='";
                                         if(value1.ft_sup_end_achive!= null){
                                         html+=value1.ft_sup_end_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td  class='trSupervisor' width='75px;'><input   style='width:60px;' type='text' id='txtftsupendmarks_"+a+"' name='txtftsupendmarks_"+a+"' value='";
                                         if(value1.ft_sup_end_marks!= null){
                                         html+=value1.ft_sup_end_marks;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td  class='trModerator' width='75px;'><input style='width:60px;' type='text' id='txtftmodendachive_"+a+"' name='txtftmodendachive_"+a+"' value='";
                                         if(value1.ft_mod_end_achive!= null){
                                         html+=value1.ft_mod_end_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td  class='trModerator' width='75px;'><input style='width:60px;' type='text' id='txtftmodendmarks_"+a+"' name='txtftmodendmarks_"+a+"' value='";
                                         if(value1.ft_mod_end_marks!= null){
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></tr></table></td>";
                                         html+="<td   width='10px;'><input type='checkbox' name='chkftactiveflg_"+a+"' id='chkftactiveflg_"+a+"' value='1' ";
                                         if(value1.ft_active_flg!= null){
                                         html+="checked";
                                         }else{
                                         html+="";    
                                         }
                                         html+=">";
                                         html+="<td   width='10px;'><input type='checkbox' name='chkftapproveflg_"+a+"' id='chkftapproveflg_"+a+"' value='1' ";
                                         if(value1.ft_approve_flg == "2"){
                                         html+="checked";
                                         }else{
                                         html+="";    
                                         }
                                         html+=">";
                                         html+="</td></tr>";
                                         a++;
                                    });
                                        html+="<tr>";
                                        html+="<td colspan='4'>Average</td><td><input style='width:60px;' type='text' id='txtev_fn_avg' name='txtev_fn_avg'></td>";
                                        html+="<td><table  border='0'><tr><td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_avg' name='txtev_fn_sup_mid_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_mid_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_mid_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;' ><input style='width:60px;' type='text' id='txtev_fn_sup_mid_mark_avg' name='txtev_fn_sup_mid_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_mid_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_mid_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_end_ach_avg' name='txtev_fn_sup_end_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_end_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_end_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_end_mark_avg' name='txtev_fn_sup_end_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_end_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_end_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_mod_end_ach_avg' name='txtev_fn_mod_end_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_fn_mod_end_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_mod_end_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                         html+="<td class='trModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_mod_end_mark_avg' name='txtev_fn_mod_end_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_fn_mod_end_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_mod_end_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td></tr></table></td>";    
                                        html+="<td class='trModerator'></td><td></td></tr>";
                                        html+="<td colspan='4'>Total</td><td><input style='width:60px;' type='text' id='txtev_fn_tot' name='txtev_fn_tot'>";
                                        html+="</td>";
                                        html+="<td><table  border='0'><tr><td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_tot' name='txtev_fn_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_mid_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_mid_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_tot' name='txtev_fn_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_mid_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_mid_mark_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_tot' name='txtev_fn_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_end_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_end_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_tot' name='txtev_fn_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_fn_sup_end_mark_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_sup_end_mark_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_tot' name='txtev_fn_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_fn_mod_end_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_mod_end_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                         html+="<td class='trModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_fn_sup_mid_ach_tot' name='txtev_fn_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_fn_mod_end_mark_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_fn_mod_end_mark_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td></tr></table></td>";  
                                        html+="<td class='trModerator'></td>"; 
                                        html+="<td class=''></td>";
                                        html+="</tr></table>";
                                    });
                                    
                                    html+="</div>";
                                    
                                }
                        
                            });   
                            $("#FTDiv").empty();
                            $("#FTDiv").append(html);
                            if(ev_id!= ""){
                                 FTcalAVGTOT();
                            }
                         }
                         
                         
                         function SMDetaiils(eno,comeval){
                             var html="";
                             var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";
                              $.ajax({
                                type: "POST",
                                async:false,
                                url: "<?php echo url_for('evaluation/AjaxGetSMData') ?>",
                                data: { comeval: comeval, eno : eno , ev_id: ev_id },
                                dataType: "json",
                                success: function(data){
                                   html+="<div><br>";
                                    $.each(data, function(key, value) {
                                        html+="<table border='1'><tr>";
                                        html+="<th width='300px;'>Function/Task</th>";
                                        html+="<th width='60px;'>From</th>";
                                        html+="<th width='60px;'>To</th>";
                                        html+="<th width='75px;'>Target / Indicator</th>";
                                        html+="<th width='50px;'>Weight</th>";
                                        html+="<th width='450px;' class='thTopRaw' >";
                                        html+="<table border='1' class='thTopCnt' width='450px;'><caption></caption><col /><col /><col /><col /><col /><col /><tbody>";
                                        html+="<tr><td class='thSupervisor' colspan='4' style='text-align:center;margin-left:auto;margin-right:auto; width:300px;'>Supervisor</td>";
                                        html+="<td colspan='2' rowspan='2' class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Moderator</td>";
                                        html+="</tr><tr>";
                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Mid Year</td>";
                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>End Year</td>";
                                        html+="</tr><tr>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto;  width:75px;'>Archive</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Archive</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="<td  class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Archive</td>";
                                        html+="<td class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="</tr></tbody></table>";
                                        html+="</th><th width='1px;'>Active</th></tr>";
                                        $.each(value, function(key1, value1) {
                                        //console.log(value1.EvaluationSkill.skill_title);
                                         html+="<tr><td><input style='width:60px;' type='hidden' id='txtmsid_"+b+"' name='txtmsid_"+b+"' value='"+value1.skill_id+"'>";
                                         html+="<input style='width:60px;' type='hidden' id='txtmsid[]' name='txtmsid[]' value='"+b+"'>";
                                         if(ev_id == ""){
                                         if(value1.skill_title!= null){ 
                                         html+=value1.skill_title; 
                                         }
                                         }else{
                                         if(value1.EvaluationSkill.skill_title != null){ 
                                         html+=value1.EvaluationSkill.skill_title; 
                                         }
                                         }
                                         html+="</td>";
                                         html+="<td><input style='width:60px;' type='text' id='txtmsfromdate_"+b+"' name='txtmsfromdate_"+b+"' value='";
                                         if(value1.emp_skill_from_date!= null){
                                         html+=value1.emp_skill_from_date;
                                         }else{
                                         html+=startdate;
                                         }    
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' type='text' id='txtmstodate_"+b+"' name='txtmstodate_"+b+"' value='";
                                         if(value1.emp_skill_to_date!= null){
                                         html+=value1.emp_skill_to_date;
                                         }else{
                                         html+=enddate;
                                         }
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' type='text' id='txtmstargetindicater_"+b+"' name='txtmstargetindicater_"+b+"' value='";
                                         if(value1.emp_skill_target_indicater!= null){
                                         html+=value1.emp_skill_target_indicater;
                                         }
                                         html+="' title='";
                                         if(value1.emp_skill_target_indicater!= null){
                                         html+=value1.emp_skill_target_indicater;
                                         }
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' class='msweight' type='text' id='txtmsweight_"+b+"' name='txtmsweight_"+b+"' maxlength='6' onkeypress='return validationFNAVGTot(event,this.id);'  value='";
                                         if(value1.emp_skill_weight!= null){
                                         html+=value1.emp_skill_weight;
                                         }
                                         html+="'></td>";
                                         html+="<td class='tdRaw' ><table border='0'><tr ><td class='trSupervisor' width='75px;' ><input style='width:60px;' type='text' id='txtemp_skillsupmidachive_"+b+"' name='txtemp_skillsupmidachive_"+b+"' value='";
                                         if(value1.emp_skill_sup_mid_achive!= null){
                                         html+=value1.emp_skill_sup_mid_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td><td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtemp_skillsupmidmarks_"+b+"' name='txtemp_skillsupmidmarks_"+b+"' value='";
                                         if(value1.emp_skill_sup_mid_marks!= null){
                                         html+=value1.emp_skill_sup_mid_marks;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtemp_skillsupendachive_"+b+"' name='txtemp_skillsupendachive_"+b+"' value='";
                                         if(value1.emp_skill_sup_end_achive!= null){
                                         html+=value1.emp_skill_sup_end_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtemp_skillsupendmarks_"+b+"' name='txtemp_skillsupendmarks_"+b+"' value='";
                                         if(value1.emp_skill_sup_end_marks!= null){
                                         html+=value1.emp_skill_sup_end_marks;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtemp_skillmodendachive_"+b+"' name='txtemp_skillmodendachive_"+b+"' value='";
                                         if(value1.emp_skill_mod_end_achive!= null){
                                         html+=value1.emp_skill_mod_end_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtemp_skillmodendmarks_"+b+"' name='txtemp_skillmodendmarks_"+b+"' value='";
                                         if(value1.emp_skill_mod_end_marks!= null){
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></tr></table></td>";
                                         html+="<td width='10px;'><input type='checkbox' name='chkemp_skillactiveflg_"+b+"' id='chkemp_skillactiveflg_"+b+"' value='1' ";
                                         if(value1.emp_skill_active_flg!= null){
                                         html+="checked";
                                         }else{
                                         html+="";    
                                         }
                                         html+="></td></tr>";
                                         b++;
                                    });
                                        html+="<tr>";
                                        html+="<td colspan='4'>Average</td><td><input style='width:60px;' type='text' id='txtev_ms_avg' name='txtev_ms_avg'></td>";
                                        html+="<td><table  border='0'><tr><td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_avg' name='txtev_ms_sup_mid_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_mid_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_mid_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_mark_avg' name='txtev_ms_sup_mid_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_mid_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_mid_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_end_ach_avg' name='txtev_ms_sup_end_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_end_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_end_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_end_mark_avg' name='txtev_ms_sup_end_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_end_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_end_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_mod_end_ach_avg' name='txtev_ms_mod_end_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_ms_mod_end_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_mod_end_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_mod_end_mark_avg' name='txtev_ms_mod_end_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_ms_mod_end_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_mod_end_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td></tr></table></td>";    
                                        html+="<td class='tdRaw' ></td></tr>";
                                        html+="<tr>";
                                        html+="<td colspan='4'>Total</td><td><input style='width:60px;' type='text' id='txtev_ms_tot' name='txtev_ms_tot'>";
                                        html+="</td>";
                                        html+="<td><table  border='0'><tr><td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_tot' name='txtev_ms_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_mid_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_mid_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_tot' name='txtev_ms_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_mid_mark_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_mid_mark_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_tot' name='txtev_ms_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_end_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_end_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_tot' name='txtev_ms_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ms_sup_end_mark_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_sup_end_mark_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_tot' name='txtev_ms_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ms_mod_end_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_mod_end_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ms_sup_mid_ach_tot' name='txtev_ms_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ms_mod_end_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ms_mod_end_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td></tr></table></td>";    
                                        html+="<td class='tdRaw'></td>"; 
                                        
                                        html+="</tr></table>";
                                    });
                                    
                                    html+="</div>";
                                    
                                }
                        
                            });   
                            $("#MSDiv").empty();
                            $("#MSDiv").append(html);
                            if(ev_id!= ""){
                                 MScalAVGTOT();
                            }
                            
                         }
                         
                         function threesixtyDetaiils(eno,comeval){
                             var html="";
                             var ev_id = "<?php echo $EvalEmployee->ev_id; ?>";
                             
                              $.ajax({
                                type: "POST",
                                async:false,
                                url: "<?php echo url_for('evaluation/AjaxGet360Data') ?>",
                                data: { comeval: comeval, eno : eno , ev_id : ev_id},
                                dataType: "json",
                                success: function(data){
                                   html+="<div><br>";
                                    $.each(data, function(key, value) {
                                        html+="<table border='1'><tr>";
                                        html+="<th width='300px;'>Function/Task</th>";
                                        html+="<th width='60px;'>From</th>";
                                        html+="<th width='60px;'>To</th>";
                                        html+="<th width='75px;'>Target / Indicator</th>";
                                        html+="<th width='50px;'>Weight</th>";
                                        html+="<th width='450px;' class='thTopRaw' >";
                                        html+="<table border='1' class='thTopCnt' width='450px;'><caption></caption><col /><col /><col /><col /><col /><col /><tbody>";
                                        html+="<tr><td class='thSupervisor' colspan='4' style='text-align:center;margin-left:auto;margin-right:auto; width:300px;'>Supervisor</td>";
                                        html+="<td colspan='2' rowspan='2' class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Moderator</td>";
                                        html+="</tr><tr>";
                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>Mid Year</td>";
                                        html+="<td class='thSupervisor' colspan='2' style='text-align:center;margin-left:auto;margin-right:auto; width:150px;'>End Year</td>";
                                        html+="</tr><tr>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto;  width:75px;'>Archive</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Archive</td>";
                                        html+="<td class='thSupervisor' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="<td  class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Archive</td>";
                                        html+="<td class='thModerator' style='text-align:center;margin-left:auto;margin-right:auto; width:75px;'>Marks</td>";
                                        html+="</tr></tbody></table>";
                                        html+="</th><th width='1px;'>Active</th></tr>";
                                        $.each(value, function(key1, value1) {
                                        //console.log(value1.EvaluationSkill.skill_title);
                                         html+="<tr><td><input style='width:60px;' type='hidden' id='txttsid_"+c+"' name='txttsid_"+c+"' value='"+value1.ts_id+"'>";
                                         html+="<input style='width:60px;' type='hidden' id='txttsid[]' name='txttsid[]' value='"+c+"'>";

                                         if(ev_id == ""){
                                         if(value1.ts_title!= null){ 
                                         html+=value1.ts_title; 
                                         }
                                         }else{
                                         if(value1.EvaluationTSMaster.ts_title != null){ 
                                         html+=value1.EvaluationTSMaster.ts_title; 
                                         }
                                         }
                                         html+="</td>";
                                         html+="<td><input style='width:60px;' type='text' id='txttsfromdate_"+c+"' name='txttsfromdate_"+c+"' value='";
                                         if(value1.emp_ts_from_date!= null){
                                         html+=value1.emp_ts_from_date;
                                         }else{
                                         html+=startdate;    
                                         }    
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' type='text' id='txttstodate_"+c+"' name='txttstodate_"+c+"' value='";
                                         if(value1.emp_ts_to_date!= null){
                                         html+=value1.emp_ts_to_date;
                                         }else{
                                         html+=enddate;        
                                         }    
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' type='text' id='txttstargetindicater_"+c+"' name='txttstargetindicater_"+c+"' value='";
                                         if(value1.emp_ts_target_indicater!= null){
                                         html+=value1.emp_ts_target_indicater;
                                         }
                                         html+="' title='";
                                         if(value1.emp_ts_target_indicater!= null){
                                         html+=value1.emp_ts_target_indicater;
                                         }
                                         html+="'></td>";
                                         html+="<td><input style='width:60px;' class='tsweight' type='text' id='txttsweight_"+c+"'' name='txttsweight_"+c+"' maxlength='6' onkeypress='return validationFNAVGTot(event,this.id);'  value='";
                                         if(value1.emp_ts_weight!= null){
                                         html+=value1.emp_ts_weight;
                                         }
                                         html+="'></td>";
                                         html+="<td class='tdRaw'><table border='0'><tr class='trSupervisor'><td width='75px;' ><input style='width:60px;' type='text' id='txtemp_tssupmidachive_"+c+"' name='txtemp_tssupmidachive_"+c+"' value='";
                                         if(value1.emp_ts_sup_mid_achive!= null){
                                         html+=value1.emp_ts_sup_mid_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td><td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtemp_tssupmidmarks_"+c+"' name='txtemp_tssupmidmarks_"+c+"' value='";
                                         if(value1.emp_ts_sup_mid_marks!= null){
                                         html+=value1.emp_ts_sup_mid_marks;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtemp_tssupendachive_"+c+"' name='txtemp_tssupendachive_"+c+"' value='";
                                         if(value1.emp_ts_sup_end_achive!= null){
                                         html+=value1.emp_ts_sup_end_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtemp_tssupendmarks_"+c+"' name='txtemp_tssupendmarks_"+c+"' value='";
                                         if(value1.emp_ts_sup_end_marks!= null){
                                         html+=value1.emp_ts_sup_end_marks;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtemp_tsmodendachive_"+c+"' name='txtemp_tsmodendachive_"+c+"' value='";
                                         if(value1.emp_ts_mod_end_achive!= null){
                                         html+=value1.emp_ts_mod_end_achive;
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtemp_tsmodendmarks_"+c+"' name='txtemp_tsmodendmarks_"+c+"' value='";
                                         if(value1.emp_ts_mod_end_marks!= null){
                                         }else{
                                         html+="";    
                                         }
                                         html+="'></tr></table></td>";
                                         html+="<td width='10px;'><input type='checkbox' name='chkemp_tsactiveflg_"+c+"' id='chkemp_tsactiveflg_"+c+"' value='1' ";
                                         if(value1.emp_ts_active_flg!= null){
                                         html+="checked";
                                         }else{
                                         html+="";    
                                         }
                                         html+="></td></tr>";
                                         c++;
                                    });
                                        html+="<tr>";
                                        html+="<td colspan='4'>Average</td><td><input style='width:60px;' type='text' id='txtev_ts_avg' name='txtev_ts_avg'></td>";
                                        html+="<td><table  border='0'><tr class='trSupervisor'><td width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_ach_avg' name='txtev_ts_sup_mid_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_mid_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_mid_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_mark_avg' name='txtev_ts_sup_mid_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_mid_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_mid_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_end_ach_avg' name='txtev_ts_sup_end_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_end_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_end_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_end_mark_avg' name='txtev_ts_sup_end_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_end_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_end_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_mod_end_ach_avg' name='txtev_ts_mod_end_ach_avg' value='";
                                        <?php if($EvalEmployee->ev_ts_mod_end_ach_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_mod_end_ach_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_mod_end_mark_avg' name='txtev_ts_mod_end_mark_avg' value='";
                                        <?php if($EvalEmployee->ev_ts_mod_end_mark_avg!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_mod_end_mark_avg; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td></tr></table></td>";    
                                        html+="<td class='tdRaw'></td></tr>";
                                        html+="<tr>";
                                        html+="<td colspan='4'>Total</td><td><input style='width:60px;' type='text' id='txtev_ts_tot' name='txtev_ts_tot'>";
                                        html+="</td>";
                                        html+="<td><table  border='0'><tr class='trSupervisor' ><td width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_ach_tot' name='txtev_ts_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_mid_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_mid_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor'  width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_ach_tot' name='txtev_ts_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_mid_mark_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_mid_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_ach_tot' name='txtev_ts_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_end_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_mid_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='trSupervisor'  width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_ach_tot' name='txtev_ts_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ts_sup_end_mark_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_sup_end_mark_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                        html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_ach_tot' name='txtev_ts_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ts_mod_end_ach_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_mod_end_ach_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td>";
                                         html+="<td class='thModerator' width='75px;'><input style='width:60px;' type='text' id='txtev_ts_sup_mid_ach_tot' name='txtev_ts_sup_mid_ach_tot' value='";
                                        <?php if($EvalEmployee->ev_ts_mod_end_mark_tot!= null){ ?>
                                            html+= "<?php echo $EvalEmployee->ev_ts_mod_end_mark_tot; ?>";
                                         <?php }else{ ?>
                                         html+="";    
                                         <?php } ?> 
                                        html+="'></td></tr></table></td>";    
                                        html+="<td class='tdRaw'></td>"; 
                                        
                                        html+="</tr></table>";
                                    });
                                    
                                    html+="</div>";
                                    
                                }
                        
                            });   
                            //$("#360Div").empty();
                            $("#360Div").append(html);
                            if(ev_id!= ""){
                                 theresixtycalAVGTOT();
                            }
                            
                         }
                             function validationFNAVGTot(event,id){ //alert(event.keyCode);
                                     var code = event.which || event.keyCode;

                                // 65 - 90 for A-Z and 97 - 122 for a-z 95 for _ 45 for - 46 for .
                                if (!(code >= 48 && code <= 57 || code == 46 || code == 11 || code == 10 || code == 13 || code == 177) )
                                {
                                            $('#'+id).val("");
                                            return false;
                                }
                                
                                if($('#'+id).val() > 100){
                                    alert("<?php echo __('Maximum Amount is 100.00') ?>");
                                    $('#'+id).val("");
                                    return false;
                                 }

                            }
                            
                            function FTcalAVGTOT(){
                            
                                 var Avg = 0;
                                 var Tot = 0;  
                                 var error = 0;

                            $(".fnweight").each( function(s) {
                                //alert($(this).val());
                                if($(this).val() == ''){  
                                     error = 1;
                                     Tot +=  0;
                                 }else{
                                     Tot += parseFloat($(this).val());
                                 }
                            });
                            
                            
                                 Tot = parseFloat(Tot).toFixed(2)
                                 
                                 Avg = Tot / a;
                                 Avg = parseFloat(Avg).toFixed(2)
                                 
                                 if(Tot != 100 && error == 0 && $("#txtFNPersentage").val() != ""){
                                     alert("Functional Tasks Weight total should be equal to 100");
                                     
                                 }    
                                 
                                 $("#txtev_fn_tot").val(Tot);
                                 $("#txtev_fn_avg").val(Avg);                          
                            }
                            
                            function MScalAVGTOT(){
                            
                                 var Avg = 0;
                                 var Tot = 0;  
                                 var error = 0;

                            $(".msweight").each( function(s) {
                                //alert($(this).val());
                                if($(this).val() == ''){  
                                     error = 1;
                                     Tot +=  0;
                                 }else{
                                     Tot += parseFloat($(this).val());
                                 }
                            });
                            
                            
                                 Tot = parseFloat(Tot).toFixed(2)

                                 Avg = Tot / b; 
                                 Avg = parseFloat(Avg).toFixed(2)
                                 
                                 if(Tot != 100 && error == 0 && $("#txtMSPersentage").val() != "" ){
                                     alert("Managerial Skills Weight total should be equal to 100");
                                     
                                 }    
                                 
                                 $("#txtev_ms_tot").val(Tot);
                                 $("#txtev_ms_avg").val(Avg);                          
                            }

                            function theresixtycalAVGTOT(){
                            
                                 var Avg = 0;
                                 var Tot = 0;  
                                 var error = 0;

                            $(".tsweight").each( function(s) {
                                //alert($(this).val());
                                if($(this).val() == ''){  
                                     error = 1;
                                     Tot +=  0;
                                 }else{
                                     Tot += parseFloat($(this).val());
                                 }
                            });
                            
                            
                                 Tot = parseFloat(Tot).toFixed(2)

                                 Avg = Tot / c; 
                                 Avg = parseFloat(Avg).toFixed(2)
                                 
                                 if(Tot != 100 && error == 0 && $("#txtTSPersentage").val() != ""){
                                     alert("360 evaluation Weight total should be equal to 100");
                                     
                                 }    
                                 
                                 $("#txtev_ts_tot").val(Tot);
                                 $("#txtev_ts_avg").val(Avg);                          
                            }
                   
                   function calTOT(){
                            
                                 var Avg = 0;
                                 var Tot = 0;  
                                 var error = 0;

                            $(".total").each( function(s) {
                                //alert($(this).val());
                                if($(this).val() == ''){  
                                     error = 1;
                                     Tot +=  0;
                                 }else{
                                     Tot += parseFloat($(this).val());
                                 }
                            });
                            
                            
                                 Tot = parseFloat(Tot).toFixed(2)

                                 if(Tot != 100 && error == 0 ){
                                     alert("Total Percentage should be equal to 100");
                                     
                                 }    
                                 
                                 $("#txtTotalPersentage").val(Tot);
                        
                            }
                            
                            
                            

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}


                            
</script>
