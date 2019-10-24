<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<link href="../../themes/orange/css/style.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery.placeholder.js') ?>"></script>
<link href="<?php echo public_path('../../images/jquerybubblepopup-theme/jquery.bubblepopup.v2.3.1.css') ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo public_path('../../scripts/jquery/jquery.bubblepopup.v2.3.1.min.js') ?>" type="text/javascript"></script>
<?php

                    $sysConf = OrangeConfig::getInstance()->getSysConf();
                    $inputDate = $sysConf->getDateInputHint();
                    $dateDisplayHint = $sysConf->dateDisplayHint;
                    $format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());
                    $encrypt = new EncryptionHandler();
?>
<?php //print_r($chkAll); ?>
<div class="formpage4col">
    <div class="navigation">

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Functions \ Task Approve Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSave" id="frmSave" method="post" action="">
            <br class="clear"/>
            <div class="leftCol" >
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name") ?> </label>
            </div>
            <div class="centerCol" style="width: 160px;padding-top: 8px;">
                <input type="text" name="txtEmployeeName"
                       id="txtEmployeeName" value="<?php echo $EmployeeName; ?>" readonly="readonly" style="color: #222222" />
            </div>
            <div class="centerCol" style="padding-left: 10px;padding-top: 8px;">
                <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
            </div>

            <div class="leftCol">&nbsp;</div>
            <div class="centerCol">
                <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $emp; ?>"/>
                <input  type="hidden" name="txtempatnno" id="txtempatnno" value=""/>
            </div>
            <br class="clear"/>
            <input type="hidden" name="abc" id="abc" value="0" >
            <br class="clear"/>
            <div class="formbuttons">
                <input type="button" class="clearbutton"  id="resetBtn"
                       value="<?php echo __("Reset") ?>"/>
                <input type="button" class="savebutton" id="viewall"
                       value="<?php echo __("View") ?>"  />
                <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?>  </div> </div>
        </form>
        <div class="noresultsbar"></div>

        <input type="hidden" name="mode" id="mode" value=""/>

        <form name="gridsave" id="gridsave" method="post" action="<?php //echo url_for('training/Adminapp')   ?>">
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>

                        <td scope="col" style="width: 200px;">

                            <?php echo __('Employee Name') ?>
                        </td>

                       <td scope="col" style="width: 100px;">
                            <?php echo __('From Date') ?>
                        </td>
                        <td scope="col" style="width: 100px;">

                            <?php echo __('To Date') ?>
                        </td>
                        <td scope="col" style="width: 70px;">
                            <?php //echo __('Work/Professional ') ?>
                        </td>
                        <td scope="col" style="width: 140px;">

                            <?php echo __('Function/Task Title') ?>
                        </td>
                        <td scope="col" style="width: 70px;">
                            <?php echo __('Indicator ') ?>
                        </td>
                        <td scope="col">

                            <?php echo __('Status') ?>
                        </td>
<!--                        <td scope="col" style="width: 50px;" >

                            <?php echo __('Attachment') ?>
                        </td>-->
<!--                        <td scope="col" style="width: 100px;  text-align: center">

                            <?php echo __('Comment') ?>
                        </td>-->
                        <td scope="col">


                    </tr>
                </thead>
                <tbody>
                    <?php
                            $row = 0;
                            foreach ($view as $leave) {
                                $cssClass = ($row % 2) ? 'even' : 'odd';
                                $row = $row + 1;
                    ?>
                                <tr class="<?php echo $cssClass ?>">
                        <?php //} ?>
                                <td class="">
                                    <!--<a class="add linkHint" onMouseOver="LoadEmployeeLeaveSummary('<?php //echo $leave->emp_number ?>','<?php //$arr=str_split($leave->leave_app_start_date); echo $arr[0].$arr[1].$arr[2].$arr[3];  ?>')" id="<?php //echo $leave->leave_app_id; ?>"  >-->    
                                    <a href="<?php echo url_for('evaluation/UpdateEvalFunctionTask?id=' . $encrypt->encrypt($leave->ft_id)) ?>">
                            <?php
                                if ($Culture == 'en') {
                                    $abcd = "getEmp_display_name";
                                } else {
                                    $abcd = "getEmp_display_name_" . $Culture;
                                }

                                $dd = $leave->Employee->$abcd();
                                $rest = substr($leave->Employee->$abcd(), 0, 100);

                                if ($leave->Employee->$abcd() == null) {
                                    $dd = $leave->Employee->getEmp_display_name();
                                    $rest = substr($leave->Employee->getEmp_display_name(), 0, 100);
                                    if (strlen($dd) > 100) {
                                        echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $dd;
                                    }
                                } else {

                                    if (strlen($dd) > 100) {
                                        echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $dd;
                                    }
                                }
                            ?>
<!--                             </a>           -->
</a> 
                            </td>
                            <td class="">
                                <div id="Outtime_<?php //echo $row   ?>"><?php //echo $promotion->getAtn_outtime();   ?></div>
                            <?php echo $leave->ft_from_date; ?>
                            </td>
                            <td class="">
                                <div id="Outtime_<?php //echo $row   ?>"><?php //echo $promotion->getAtn_outtime();   ?></div>
                            <?php echo $leave->ft_to_date; ?>
                            </td>
                            <td class="">
                                <div id="Outtime_<?php //echo $row   ?>"><?php //echo $promotion->getAtn_outtime();   ?></div><div id="editableOuttimeHH_<?php echo $row ?>"></div><div id="editableOuttimeMM_<?php echo $row ?>"></div>
                            <?php //echo $leave->EvaluationCompany->eval_name; ?>
                            </td>
                            <td class="">
                            <?php

                                     echo $leave->ft_title;
                            ?>
                            </td>
                            <td class="">
                            <?php

                                     echo $leave->ft_target_indicater;
                            ?>
                            </td>
                            <td  >
                                <div id="cmbLeaveSatus_<?php echo $row ?>"> <select name="editablecmbLeaveSatus_<?php echo $row ?>" id="editablecmbLeaveSatus_<?php echo $row ?>"  style="width: 80px;">
<!--                                        <option value="0" <?php //if ($leave->getLeave_app_status() == 0)
                                    echo "selected"; ?>><?php echo __("Cancel"); ?></option>-->
                                    <option value="1" <?php if ($leave->ft_approve_flg == 1)
                                                echo "selected"; ?>><?php echo __("Pending"); ?></option>
                                    <option value="2" <?php if ($leave->ft_approve_flg == 2)
                                                echo "selected"; ?>><?php echo __("Approved"); ?></option>
                                    <option value="3" <?php if ($leave->ft_approve_flg == 3)
                                                echo "selected"; ?>><?php echo __("Rejected"); ?></option>
                                </select></div>


                        </td>

<!--                        <td class=""><div id="txtcomment_<?php echo $row ?>">
                                <?php
                                            //$dd = $leave->getLeave_app_comment();
                                            if ($dd == "null") {
                                                $dd = "";
                                            }

                                            $rest = substr($dd, 0, 100);

                                            if (strlen($dd) > 100) {
                                ?>
                                                <input id="editabletxtcomment_<?php echo $row ?>" type="text" name="editabletxtcomment_<?php echo $row ?>" value="<?php echo $rest; ?>" title="<?php echo $dd ?>" >
                                <?php
                                            } else {
                                ?>
                                                <input id="editabletxtcomment_<?php echo $row ?>" type="text" name="editabletxtcomment_<?php echo $row ?>" value="<?php echo $rest; ?>" title="<?php echo $dd; ?>" >
                                <?php } ?></div>
                                    </td>-->
                                    <td class="">
                                        <input type="button" class="plainbtn"
                                               name="editBtn_<?php echo $row ?>"
                                               id="editBtn_<?php echo $row ?>"
                                               value="<?php echo __("Edit") ?>"
                                               onclick='save1(this.id,this.value,<?php echo $leave->ft_id; ?>,<?php echo $row; ?>,$("#editablecmbLeaveSatus_<?php echo $row; ?>").val(),$("#editabletxtcomment_<?php echo $row; ?>").val())'
                                               />
                                    </td>
                                </tr>
                    <?php } ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
                        <br class="clear" />
                    </div>

<?php
//                                        require_once '../../lib/common/LocaleUtil.php';
//                                        $sysConf = OrangeConfig::getInstance()->getSysConf();
//                                        $sysConf = new sysConf();
//                                        $inputDate = $sysConf->dateInputHint;
//                                        $format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());
?>

                                        <script type="text/javascript">
                                            var i=0;
                                            
//                                            function LoadEmployeeLeaveSummary(Emp,Year){
//                                              $.post(
//
//                                                        "<?php /* echo url_for('Leave/DefaultLeavedetails') ?>", //Ajax file
//
//                                                        { eid: Emp, year: Year},
//
//                                                        function(data){
//                                                            
//                                                            
//                                                            var tabel="";
//                                                            tabel+="<table class='data-table' >";
//                                                            tabel+="<thead><tr><td scope='co'><?php echo __("Leave Type"); ?></td>";
//                                                            tabel+="<td scope='col'><?php echo __("Total Days"); ?></td>";
//                                                            tabel+="<td scope='col'><?php echo __("Taken"); ?></td>";
//                                                            tabel+="<td scope='col'><?php echo __("Pending"); ?></td>";
//                                                            tabel+="<td scope='col'><?php echo __("Remaining"); ?></td>";
//                                                            tabel+="</tr></thead>";
//                                                            var row=0;
//                                                            var cssClass;
//                                                            $.each(data.entitle, function(key,value) {
//                                                                cssClass = (row % 2) ? 'even' : 'odd';
//                                                                row = row + 1;
//
//                                                                tabel+="<tr class="+cssClass+" >";
//                                                                $.each(value, function(key,value) {
//
//
//                                                                    if(key=='leave_type_id'){
//                                                                        tabel+="<td class='' style='width: 100px; '>";
//                                                                        "<?php foreach ($loadbtype as $btype) { //die(print_r($btype->leave_type_name));
                                                 ?>//"
//                                                                            
//                                                                            var btid="<?php echo $btype->leave_type_id; ?>";
//                                                                            //alert(btid);
//                                                                            if(value == btid){
//                                                                                var btname="<?php
                                                        if ($Culture == 'en') {
                                                            //echo $btype->leave_type_name;
                                                        }
                                                ?>//";
//                                                                                var btnamesi="<?php
                                                        if ($Culture == 'si') {
                                                            if (($btype->leave_type_name_si) == null) {
                                                                echo $btype->leave_type_name;
                                                            } else {
                                                                echo $btype->leave_type_name_si;
                                                            }
                                                        }
                                                ?>//";
//                                                                                var btnameta="<?php
                                                        if ($Culture == 'ta') {
                                                            if (($btype->leave_type_name_ta) == null) {
                                                                echo $btype->leave_type_name;
                                                            } else {
                                                                echo $btype->leave_type_name_ta;
                                                            }
                                                        }
                                                ?>//";
//                                                                            } "<?php } ?>"
//                                                                        var cul="<?php echo $Culture; */ ?>";
//                                                                        tabel+=btname;
//                                                                        tabel+="</td>";
//                                                                    }
//
//                                                                    if(key=='leave_ent_day'){
//                                                                        tabel+="<td class='' style='text-align: center'>"+value+"</td>";
//                                                                    }
//                                                                    if(key=='leave_ent_taken'){
//                                                                        tabel+="<td class='' style='text-align: center'>"+value+"</td>";
//                                                                    }
//                                                                    if(key=='leave_ent_sheduled'){
//                                                                        tabel+="<td class='' style='text-align: center'>"+value+"</td>";
//                                                                    }
//                                                                    if(key=='leave_ent_remain'){
//                                                                        tabel+="<td class='' style='text-align: center'>"+value+"</td>";
//                                                                    }
//
//
//
//                                                                });
//                                                                tabel+="</tr>";
//
//                                                            });
//                                                            
//                                                            tabel+="</table>";
//                                                            
//                                                                    if( $('.linkHint').HasBubblePopup() ){
//                                                                     $('.linkHint').RemoveBubblePopup();
//                                                        }
//                                                                 $('.linkHint').CreateBubblePopup({
//                                                                 innerHtml: tabel,
//                                                                 innerHtmlStyle: {
//                                                                            color:'#333333',
//                                                                            'text-align':'center'                       
//                                                                            },
//
//                                                                themeName: 	'orange',
//                                                                themePath: 	'<?php echo public_path('../../images/jquerybubblepopup-theme') ?>',
//                                                                position : 'right'
//                                                                });
//                                                                
////                                                            $('#tabel2').html(tabel);
////                                                            
////                                                            $('#tabel2').show();
////                                                            $('#tabel1').show();
////                                                            $('#divbtn').hide();
//                                                            $('#editBtn').show();
//                                                            buttonSecurityCommon(null,"editBtn",null,null);
//                                                        },
//                                                        "json"
//
//                                                    );
//                                                    }

                                            
 

                                            function popuimage(link){
                                                window.open(link, "myWindow",
                                                "status = 1, height = 300, width = 300, resizable = 0" )
                                            }
                                            function checkall(id){ //alert("dsd");
                                                if((i%2) == 1){
                                                    $('#chkPending').attr('checked', 'checked');
                                                    $('#chkApproved').attr('checked', 'checked');
                                                    $('#chkRejected').attr('checked', 'checked');
                                                    $('#chkCanceled').attr('checked', 'checked');
                                                    $('#chkTaken').attr('checked', 'checked');
                                                }
                                                else{
                                                    //document.frmSave.chkPending.checked= false;
                                                    $('#chkPending').attr('checked');
                                                    $('#chkApproved').attr('checked');
                                                    $('#chkRejected').attr('checked');
                                                    $('#chkCanceled').attr('checked');
                                                    $('#chkTaken').attr('checked');
                                                }
                                                i++;
                                            }


                                            function disableAnchor(obj, disable){

                                                if(disable){
                                                    var href = obj.getAttribute("href");
                                                    if(href && href != "" && href != null){
                                                        obj.setAttribute('href_bak', href);
                                                    }
                                                    obj.removeAttribute('href');
                                                    obj.style.color="gray";
                                                }
                                                else{
                                                    obj.setAttribute('href', obj.attributes
                                                    ['href_bak'].nodeValue);
                                                    obj.style.color="blue";
                                                }
                                            }
                                            function displaymessage()
                                            {
                                                $("#abc").val(0);

                                            }
                                            var mode;
                                            function SelectEmployee(data){ 


                                                myArr = data.split('|');
                                                $("#txtEmpId").val(myArr[0]);
                                                $("#txtEmployeeName").val(myArr[1]);
                                                //alert($("#txtEmpId").val());
                                                displaymessage();

                                            }

                                            //edit
                                            var mode='edit';

                                            $('#gridsave :input').attr('disabled', true);
                                            $('#gridsave :button').removeAttr('disabled');
                                            function save1(id,value,ft_id,row,LeaveStatus,comment){
//                                                if(comment.length > 200){
//                                                    alert("<?php echo __("Comment Length Invalid ") ?>");
//                                                    return false;
//                                                }
                                                $('#gridsave :input').attr('disabled', true);

                                                $('#'+id).removeAttr('disabled');
                                                $('#editBtn_'+row).removeAttr('disabled');

                                                if(mode=='edit'){


                                                    $.post(

                                                    "<?php echo url_for('evaluation/ajaxTableLock') ?>", //Ajax file

                                                    { lock : 1 , ft_id:ft_id },  // create an object will all values

                                                    //function that is called when server returns a value.
                                                    function(data){

                                                        if(data.lockMode==0){
                                                            alert("<?php echo __("Can not Update Record Lock") ?>");
                                                            $('#gridsave :button').removeAttr('disabled');
                                                            $('#editBtn_'+row).attr('disabled', true);
                                                        }
                                                        else{


                                                            mode='save';
                                                            var cmbstatus="";


                                                            var cstatus=data.status;
                                                            var tcomment;
                                                            if(data.comment == "null"){
                                                                tcomment=" ";
                                                            }else{
                                                                tcomment=data.comment;
                                                            }
                                                            var txtcomment="";

                                                            $('#'+id).attr('value', '<?php echo __("Save") ?>');
                                                            cmbstatus+="<select name='editablecmbLeaveSatus_"+row+"' id='editablecmbLeaveSatus_"+row+"'  style='width: 80px;'>";
                                                            var Canceled="";
                                                            var Pending="";
                                                            var Approved="";
                                                            var Rejected="";
                                                            if(cstatus==0){
                                                                Canceled="selected=selected";
                                                            }
                                                            else if(cstatus==1){
                                                                Pending="selected=selected";
                                                            }else if(cstatus==2){
                                                                Approved="selected=selected";
                                                            }else{
                                                                Rejected="selected=selected";
                                                            }
//                                                            cmbstatus+="<option value='0' "+Canceled+"><?php echo __("Cancel"); ?></option>";
                                                            cmbstatus+="<option value='1' "+Pending+"><?php echo __("Pending"); ?></option>";
                                                            cmbstatus+="<option value='2' "+Approved+"><?php echo __("Approved"); ?></option>";
                                                            cmbstatus+="<option value='3' "+Rejected+"><?php echo __("Rejected"); ?></option>";
                                                            cmbstatus+="</select>";


                                                            txtcomment+="<input id='editabletxtcomment_"+row+"' type='text' name='editabletxtcomment_"+row+"' value='";
                                                            if(tcomment==null){
                                                                txtcomment+="";
                                                            }else{
                                                                txtcomment+=tcomment;
                                                            }
                                                            txtcomment+="' title='"+tcomment+"'>";

                                                            $('#editablecmbLeaveSatus_'+row).remove();
                                                            $('#cmbLeaveSatus_'+row).html(cmbstatus);

                                                            $('#editabletxtcomment_'+row).remove();
                                                            $('#txtcomment_'+row).append(txtcomment);

                                                        }

                                                    },


                                                    "json"

                                                );


                                                }
                                                else{

                                                    $.post(
                                                    "<?php echo url_for('evaluation/SaveApprove') ?>", //Ajax file

                                                    { lock : 0 , ft_id : ft_id , status : LeaveStatus, comment : comment   },  // create an object will all values

                                                    //function that is called when server returns a value.
                                                    function(data){

                                                        if(data.isupdated=="true"){

                                                            mode='edit';
                                                            $('#'+id).attr('value', '<?php echo __("Edit") ?>');
                                                            alert("<?php echo __("Successfully Updated") ?>");

                                                            $('#gridsave :button').removeAttr('disabled');
                                                            $.post(

                                                            "<?php echo url_for('evaluation/ajaxTableLock') ?>", //Ajax file

                                                            { lock : 0 , ft_id:ft_id },  // create an object will all values

                                                            //function that is called when server returns a value.
                                                            function(data){

                                                                if(data.lockMode==0){
                                                                    mode='edit';

                                                                    //location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/LeaveSearch')) ?>";

                                                                }

                                                                else{
                                                                    mode='edit';
                                                                    $('#'+id).attr('value', '<?php echo __("Save") ?>');
                                                                }
                                                            },

                                                            "json"
                                                        );

                                                        }
                                                        else{
                                                            alert("<?php echo __("Error") ?>");
                                                        }

                                                    },


                                                    "json"

                                                );

                                                }
                                            }


                                            $(document).ready(function() {
                                               for (var i = 0 ; i <= <?php echo $row; ?>;  i++){
                                                buttonSecurityCommon(null,null,"editBtn_"+i,null);
                                                }
                                                
                                               $("#empRepPopBtn:visible:first").focus();
                                               $("#fdate").placeholder();
                                                $("#tdate").placeholder();
                                                if($("#fdate").val()==""){
                                                    $("#fdate").val("<?php echo LocaleUtil::getInstance()->formatDate(date("Y-m-d")) ?>");
                                                }
                                                if($("#tdate").val()==""){
                                                   "<?php         $e = getdate();
                                                                    $today = date("Y-m-d", $e[0]);

                                                                    $fdate = new DateTime($today);
                                                                    $fdate->add(new DateInterval('P6D'));
                                                                    $nextweek = $fdate->format("Y-m-d");


                                                   ?>"
                                                    $("#tdate").val("<?php echo LocaleUtil::getInstance()->formatDate($nextweek) ?>");
                                                    //$('#chkAll').attr('checked', 'checked');
                                                }




                                                jQuery.validator.addMethod("orange_date",
                                                function(value, element, params) {

                                                    var format = params[0];

                                                    if (value == '') {

                                                        return true;
                                                    }
                                                    var d = strToDate(value, "<?php echo $format ?>");


                                                    return (d != false);

                                                }, ""
                                            );

                                                $('#empRepPopBtn').click(function() {

                                                    var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee&reason=atte'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');
                                                    if(!popup.opener) popup.opener=self;
                                                    popup.focus();
                                                });

                                                //Validate the form
                                                $("#frmSave").validate({
//                                                    rules: {
//                                                        txtfdate: { required: true,orange_date:true },
//                                                        txttdate: { required: true,orange_date:true }
//
//                                                    },
//                                                    messages: {
//
//                                                        txtfdate: { required:"<?php echo __("Please Enter From Date") ?>",orange_date: "<?php echo __("Please specify valid date") ?>"},
//                                                        txttdate: { required:"<?php echo __("Please Enter To Date") ?>",orange_date: "<?php echo __("Please specify valid date") ?>"}
//
//                                                    }
                                                });

                                                // When click process button
                                                $("#viewall").click(function() {

                                                        $('#frmSave').submit();
                                                        return true;

                                                });

                                                //When click reset buton
                                                $("#resetBtn").click(function() { 
                                                    //document.forms[0].reset('');
                                                    location.href="<?php echo url_for('evaluation/FTApproveSearch') ?>";
                                                });

                                                $("#fdate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });
                                                $("#tdate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });

    <?php if($chkAll == "All" ){ ?>
        $('#chkAll').attr('checked', 'checked');
    <?php } ?>
        <?php if($chkPending == "Pending" ){ ?>
        $('#chkPending').attr('checked', 'checked');
    <?php } ?>
       <?php if($chkApproved == "Approved" ){ ?>
        $('#chkApproved').attr('checked', 'checked');
    <?php } ?>
       <?php if($chkRejected == "Rejected" ){ ?>
        $('#chkRejected').attr('checked', 'checked');
    <?php } ?>
        <?php if($chkCanceled == "Canceled" ){ ?>
        $('#chkCanceled').attr('checked', 'checked');
    <?php } ?>
        <?php if($chkTaken == "Taken" ){ ?>
        $('#chkTaken').attr('checked', 'checked');
    <?php } ?>    

    });
</script>
