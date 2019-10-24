<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/paginator.js') ?>"></script>
<div class="formpage4col" style="width: auto">
    <div class="navigation">
<style type="text/css">
.active
{
color:#FFF8C6;
background-color:#FFE87C;
border: solid 1px #AF7817;
padding:1px 1px;
margin:1px;
text-decoration:none;
}
.inactive
{
color:#000000;
cursor:default;
text-decoration:none;
border: solid 1px #FFF8C6;
padding:1px 1px;
margin:1px;

}
div.formpage4col select{
width: 50px;
}
.paginator{

    padding-left: 50px;

}

</style>

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Assign Employee/Supervisor/Moderator") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <div class="leftCol">
                &nbsp;
            </div>
            <br class="clear"/>
            
            <div style="float: left; width: 350px;">
                <div class="leftCol" >
                    <label class=""><?php echo __("Work/Professional Category") ?><span class="required">*</span></label>
                </div>
                <div class="centerCol" style="padding-top: 8px;" id="btype">

                    <select name="cmbbtype" id="cmbbtype" onchange="getYear(this.value);" style="width: 150px;">
                        <option value=""><?php echo __("--Select--") ?></option>
                        <?php foreach ($EvaluationList as $Evaluation) {
                        ?>
                            <option value="<?php echo $Evaluation->eval_id; ?>" <?php if ($EVID == $Evaluation->eval_id){ echo "selected";  } ?>> <?php
                                    if ($Culture == 'en') {
                                        echo $Evaluation->eval_name;
                                    } elseif ($Culture == 'si') {
                                        if (($Evaluation->eval_name_si) == null) {
                                            echo $Evaluation->eval_name;
                                        } else {
                                            echo $Evaluation->eval_name_si;
                                        }
                                    } elseif ($Culture == 'ta') {
                                        if (($Evaluation->eval_name_ta) == null) {
                                            echo $Evaluation->eval_name;
                                        } else {
                                            echo $Evaluation->eval_name_ta;
                                        }
                                    }
                        ?></option>
<?php } ?>
                            </select>
                        </div>
                        <br class="clear"/>
                        <div class="leftCol" >
                            <label for="txtLocationCode" ><?php echo __("Year") ?><span class="required">*</span></label>
                        </div>
                       <div class="centerCol" style="padding-top: 8px; ">
                            <input id="txtYear" type="text" name="txtYear" value="<?php echo date("Y"); ?>" maxlength="4" style="width: 140px;" readonly="readonly">
                        </div>
                        <br class="clear"/>
 <!--                        <div class="leftCol" >
                    <label class=""><?php echo __("Evaluation Type") ?><span class="required">*</span></label>
                </div>
                <div class="centerCol" style="padding-top: 8px;" id="btype">

                    <select name="cmbEtype" id="cmbEtype" onchange="getData(this.value);" style="width: 150px;">
                        <option value=""><?php echo __("--Select--") ?></option>
                        <?php foreach ($EvaluationTypeList as $EvaluationType) {
                        ?>
                            <option value="<?php echo $EvaluationType->eval_type_id; ?>" <?php if ($ETID == $EvaluationType->eval_type_id){ echo "selected"; } ?>> <?php
                                    if ($Culture == 'en') {
                                        echo $EvaluationType->eval_type_name;
                                    } elseif ($Culture == 'si') {
                                        if (($EvaluationType->eval_type_name_si) == null) {
                                            echo $EvaluationType->eval_type_name;
                                        } else {
                                            echo $EvaluationType->eval_type_name_si;
                                        }
                                    } elseif ($Culture == 'ta') {
                                        if (($EvaluationType->eval_type_name_ta) == null) {
                                            echo $EvaluationType->eval_type_name;
                                        } else {
                                            echo $EvaluationType->eval_type_name_ta;
                                        }
                                    }
                        ?></option>
<?php } ?>
                            </select>
                        </div>-->
<!--                        <br class="clear"/>-->
 
                    </div>
                    <br class="clear"/>

            
            
<!--            <div id="bulkemp" style="float: right;">-->


                <div class="leftCol">
                    <label id="lblemp" class="controlLabel" for="txtLocationCode"><?php echo __("Add Employee") ?> <span class="required">*</span></label>
                </div>
                            
                <div class="centerCol" style="padding-top: 8px;">
                    <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> /><br>
                    <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $etid; ?>"/>
                </div>
                <br class="clear"/>
                <div id="employeeGrid1" class="centerCol" style="margin-left:10px; margin-top: 8px; width: 780px; border-style:  solid; border-color: #FAD163; ">
                    <div style="">
                        <div class="centerCol" style='width:100px; background-color:#FAD163;'>
                            <label class="languageBar" style="padding-left:2px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px;  color:#444444;"><?php echo __("Emp Id") ?></label>
                        </div>
                        <div class="centerCol" style='width:220px;  background-color:#FAD163;'>
                            <label class="languageBar" style="padding-left:2px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px; color:#444444; text-align:inherit"><?php echo __("Employee Name") ?></label>
                        </div>
                        <div class="centerCol" style='width:200px;   background-color:#FAD163;'>
                            <label class="languageBar" style="width:100px; padding-left: 0px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px; color:#444444; text-align:inherit"><?php echo __("Supervisor") ?></label>
                        </div>
                        <div class="centerCol" style='width:200px;   background-color:#FAD163;'>
                            <label class="languageBar" style="width:100px; padding-left: 0px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px; color:#444444; text-align:inherit"><?php echo __("Moderator") ?></label>
                        </div>
                        <div class="centerCol" style='width:60px;   background-color:#FAD163;'>
                            <label class="languageBar" style="width:50px; padding-left: 0px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px; color:#444444; text-align:inherit"><?php echo __("Remove") ?></label>
                        </div>

                    </div>
                    <div id="tohide" >
                    

                    </div>
                    <br class="clear"/>
                   
                </div>
<!--            </div>-->
            

                    <br class="clear"/>
                    <div class="formbuttons">
                        <input type="button" class="savebutton" id="editBtn"

                               value="<?php echo __("Save") ?>" tabindex="8" />
                        <input type="button" class="clearbutton"  id="resetBtn"
                               value="<?php echo __("Reset") ?>" tabindex="9" />
<!--                        <input type="button" class="backbutton" id="btnBack"
                               value="<?php echo __("Back") ?>" tabindex="10" />-->
                    </div>
                </form>
            </div>
            <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>

            <br class="clear" />
        </div>

        <script type="text/javascript">
            //ajax start to load to the grid ///
            var courseId="";
            var empIDMaster
            var myArray2= new Array();
            var empstatArray= new Array();
            var k;
            var pagination = 0;
            var existemp= new Array();

            //Pagination variable
            itemsPerPage = 20;
            paginatorStyle = 2;
            paginatorPosition = 'both';
            enableGoToPage = true;
            currentPage = 1;

            var ajaxED = 0;
//            function LoadCurrentDep(id){
//                $.post(
//
//                "<?php //echo url_for('Leave/AjaxEmpType') ?>", //Ajax file
//
//                { id: id },  // create an object will all values
//                function(data){
//                    var i = 0;
//                    empstatArray=data;
//                    $.each(data, function(key,value) {
//                        $.each(value, function(key,value) {
//
//                        });
//                    });
//                    i++;
//                },
//                "json"
//
//            );
//            }


    function SelectEmployee1(data){
        supArray = data.split('|');
        if(supArray[0]!=null){
            $("#hiddneSupID_"+tempEmpId).val(supArray[0]);
            $("#supname_"+tempEmpId).html("<b>"+supArray[1]+"</b>");
            //$("#type_"+tempEmpId).html("<b><?php echo __('Individual supervisor'); ?></b>");
            //                    $("#options").show();
        }
        }
        
    function LoadNomineeEmpSerachBox(empId){
        tempEmpId=empId;
        var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee2'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');

        if(!popup.opener) popup.opener=self;
        popup.focus();
    }

    function SelectEmployee2(data){
        supArray = data.split('|');
        if(supArray[0]!=null){
            $("#hiddnenominee_"+tempEmpId).val(supArray[0]);
            $("#nomineename_"+tempEmpId).html("<b>"+supArray[1]+"</b>");
            //$("#type_"+tempEmpId).html("<b><?php echo __('Individual supervisor'); ?></b>");
            //                    $("#options").show();
        }
        }
        
    function LoadEmpSerachBox(empId){
        tempEmpId=empId;
        var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee1'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');

        if(!popup.opener) popup.opener=self;
        popup.focus();
    }
            function getYear(id){
                $('#bulkemp').hide();
                $('#tohide').empty();
                $('#cmbEtype').val("");
                $.post(
                "<?php echo url_for('evaluation/Year') ?>", //Ajax file

                { id: id },  // create an object will all values
                function(data){
                    ajaxED=data;
                    $('#txtYear').val(data)
                   // LoadCurrentDep(id);

                    for(var t=0; t<=k; t++){
                        $("#row_"+t).remove();
                    }
                    myArray2=new Array();

                },
                "json"

            );
                getData();
            }

        function getData(){

       if($('#cmbbtype').val()!=''){
///        myArray2=new Array();   
        $.ajax({
            type: "POST",
            async:false,
            url: "<?php echo url_for('evaluation/CurrentEmployee') ?>",
            data: { EVid: $('#cmbbtype').val() },
            dataType: "json",
            success: function(data){ 
                
//                for(var t=0; t<=k; t++){
//                    $("#row_"+t).remove();
//                    }

//                    if(pagination >= 1){
//                       $("#tohide").depagination();
//                   }
                    if(data!=null){

                    var childdiv="";
                    var i=0;
                    $.each(data, function(key, value) {
                        var word=value.split("|");
                            
                            childdiv="<div class='pagin' id='row_"+word[2]+"' style='padding-top:5px; '>";
                                    childdiv+="<div class='centerCol' id='master' style='width:100px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>"+word[0]+"</div>";
                                    childdiv+="</div>";

                                    childdiv+="<div class='centerCol' id='master' style='width:220px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>"+word[1]+"</div>";
                                    childdiv+="</div>";
                                    
                                    childdiv+="<div class='centerCol' id='master' style='width:175px;'>";
                                    childdiv+="<div id='supname_"+word[2]+"' name='supname_"+word[2]+"'  style='height:30px; padding-left:3px;'>"+word[4]+"</div>";
                                    childdiv+="</div>";

                                    childdiv+="<input type='hidden'  id='hiddneSupID_"+word[2]+"' class='hiddenSupId'  name='hiddneSupID_"+word[2]+"' value='"+word[5]+"' />";

                    
                                    childdiv+="<div class='centerCol' id='master' style='width:25px; height:30px;'>";
                                    childdiv+="<input class='button' type='button' value='...' id='empRepPopBtn_"+word[2]+"' name='empRepPopBtn_"+word[2]+"' onclick='LoadEmpSerachBox("+word[2]+")' />";
                                    childdiv+="</div>";


                                    //nominee    
                                    childdiv+="<div class='centerCol' id='master' style='width:175px;'>";
                                    childdiv+="<div id='nomineename_"+word[2]+"' name='nomineename_"+word[2]+"' style='height:30px; padding-left:3px;'>"+word[7]+"</div>";
                                    childdiv+="</div>";

                                    childdiv+="<input type='hidden'  id='hiddnenominee_"+word[2]+"' class='hiddenSupId'  name='hiddnenominee_"+word[2]+"' value='"+word[8]+"' />";
                                    childdiv+="<input type='hidden'  id='empno_"+word[2]+"' class='hiddenSupId'  name='empno_"+word[2]+"' value='"+word[2]+"' />";

                    
                                    childdiv+="<div class='centerCol' id='master' style='width:25px; height:30px;'>";
                                    childdiv+="<input class='button' type='button' value='...' id='empnomineeRepPopBtn_"+word[2]+"' name='empnomineeRepPopBtn_"+word[2]+"' onclick='LoadNomineeEmpSerachBox("+word[2]+")' />";
                                    childdiv+="</div>";

                                    childdiv+="<div class='centerCol' id='master' style='width:60px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>";
                                    childdiv+="<input  class='' type='button' onclick='deleteCRow("+i+","+word[2]+")' value='<?php echo __('Remove') ?>'></input>";
                                    
                                    childdiv+="<input type='hidden' name='hiddenEmpNumber[]' value="+word[2]+" ></div>";
                                    childdiv+="</div>";
                                    childdiv+="</div>";
                                    //
                                    //alert(word);
                                    $('#tohide').append(childdiv);


//                        k=i;
//                        i++;
                        
                        
                        });
//                        pagination++;
                          

                    $(function () {
                        //alert(pagination);
                       if(pagination >= 1){ 
                       $("#tohide").depagination();
                       $("#tohide").pagination();
                       }else{ 
                           $("#tohide").pagination();
                       }   
                        
                    });
                    
                    pagination = pagination + 1;
                    
                    
                         
                    }else{
                    for(var t=0; t<=k; t++){
                        $("#row_"+t).remove();
                    }
                    
                    if(pagination >= 1){
                       $("#tohide").depagination();
                       }
                    //myArray2=new Array();
                    }
                    
                }
                
        });
            //alert(myArray2);
            //addtoGrid(myArray2);
            $('#bulkemp').show();
            }else{
               $('#bulkemp').hide();
            }

                                         

            }


            function SelectEmployee(data){

                myArr=new Array();
                lol=new Array();
                myArr = data.split('|');

                addtoGrid(myArr);
                if(myArr != null){
                }
            }

            function addtoGrid(empid){

                var arraycp=new Array();

                var arraycp = $.merge([], myArray2);

                var items= new Array();
                for(i=0;i<empid.length;i++){

                    items[i]=empid[i];
                }

                var u=1;
                $.each(items,function(key, value){

                    if(jQuery.inArray(value, arraycp)!=-1)
                    {

                        // ie of array index find bug sloved here//
                        if(!Array.indexOf){
                            Array.prototype.indexOf = function(obj){
                                for(var i=0; i<this.length; i++){
                                    if(this[i]==obj){
                                        return i;
                                    }
                                }
                                return -1;
                            }
                        }

                        var idx = arraycp.indexOf(value);
                        if(idx!=-1) arraycp.splice(idx, 1); // Remove it if really found!
                        u=0;

                    }
                    else{

                        arraycp.push(value);

                    }


                }


            );

                $.each(myArray2,function(key, value){
                    if(jQuery.inArray(value, arraycp)!=-1)
                    {

                        // ie of array index find bug sloved here//
                        if(!Array.indexOf){
                            Array.prototype.indexOf = function(obj){
                                for(var i=0; i<this.length; i++){
                                    if(this[i]==obj){
                                        return i;
                                    }
                                }
                                return -1;
                            }
                        }

                        var idx = arraycp.indexOf(value); // Find the index
                        if(idx!=-1) arraycp.splice(idx, 1); // Remove it if really found!
                        u=0;

                    }
                    else{


                    }


                }


            );
                $.each(arraycp,function(key, value){
                    myArray2.push(value);
                }


            );if(u==0){

                }
                var courseId1=$('#courseid').val();
                $.post(

                "<?php echo url_for('evaluation/LoadGrid') ?>", //Ajax file



                { 'empid[]' : arraycp },  // create an object will all values

                //function that is c    alled when server returns a value.
                function(data){
                    //alert(data);

                    //var childDiv;
                    var childdiv="";
                    var i=0;

                    $.each(data, function(key, value) {
                        var word=value.split("|");

                                    childdiv="<div class='pagin' id='row_"+word[4]+"' style='padding-top:5px; '>";
                                    childdiv+="<div class='centerCol' id='master' style='width:100px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>"+word[0]+"</div>";
                                    childdiv+="</div>";

                                    childdiv+="<div class='centerCol' id='master' style='width:220px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>"+word[1]+"</div>";
                                    childdiv+="</div>";
                                    
                                    childdiv+="<div class='centerCol' id='master' style='width:175px;'>";
                                    childdiv+="<div id='supname_"+word[4]+"' name='supname_"+word[4]+"'  style='height:30px; padding-left:3px;'>"+word[5]+"</div>";
                                    childdiv+="</div>";

                                    childdiv+="<input type='hidden'  id='hiddneSupID_"+word[4]+"' class='hiddenSupId'  name='hiddneSupID_"+word[4]+"' value='"+word[6]+"' />";

                    
                                    childdiv+="<div class='centerCol' id='master' style='width:25px; height:30px;'>";
                                    childdiv+="<input class='button' type='button' value='...' id='empRepPopBtn_"+word[4]+"' name='empRepPopBtn_"+word[4]+"' onclick='LoadEmpSerachBox("+word[4]+")' />";
                                    childdiv+="</div>";
                                    
    
                                    //alert(word[7]);
                                    //nominee 
//                                    if(word[4] === ""){ 
//                                       word[7]= 'Reshan Dewapura';
//                                       word[6]= '1';
//                                       alert(word[7]);
//                                    } 
                                    
                                    //nominee 
                                    childdiv+="<div class='centerCol' id='master' style='width:175px;'>";
                                    childdiv+="<div id='nomineename_"+word[4]+"' name='nomineename_"+word[4]+"'  style='height:30px; padding-left:3px;'>Reshan Dewapura</div>";
                                    childdiv+="</div>";

                                    childdiv+="<input type='hidden'  id='hiddnenominee_"+word[4]+"' class='hiddenSupId'  name='hiddnenominee_"+word[4]+"' value='1' />";
                                    childdiv+="<input type='hidden'  id='empno_"+word[4]+"' class='hiddenSupId'  name='empno_"+word[4]+"' value='"+word[4]+"' />";

                    
                                    childdiv+="<div class='centerCol' id='master' style='width:25px; height:30px;'>";
                                    childdiv+="<input class='button' type='button' value='...' id='empnomineeRepPopBtn_"+word[4]+"' name='empnomineeRepPopBtn_"+word[4]+"' onclick='LoadNomineeEmpSerachBox("+word[4]+")' />";
                                    childdiv+="</div>";

                                    childdiv+="<div class='centerCol' id='master' style='width:60px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>";
                                    childdiv+="<input  class='' type='button' onclick='deleteCRow("+i+","+word[4]+")' value='<?php echo __('Remove') ?>'></input>";
                                    
                                    childdiv+="<input type='hidden' name='hiddenEmpNumber[]' value="+word[4]+" ></div>";
                                    childdiv+="</div>";
                                    childdiv+="</div>";
                                    //

                                    $('#tohide').append(childdiv);


                        k=i;
                        i++;
                    });
                    pagination++;


$('.paginator').remove();

                    $(function () { 

                       if(pagination > 1){
                       $("#tohide").depagination();
                       }
                        $("#tohide").pagination();
                    });

                },

                //How you want the data formated when it is returned from the server.
                "json"

            );


            }
            function removeByValue(arr, val) {
                for(var i=0; i<arr.length; i++) {
                    if(arr[i] == val) {

                        arr.splice(i, 1);

                        break;

                    }
                }
            }
            function deleteCRow(id,value){

                answer = confirm("<?php echo __("Do you really want to Delete?") ?>");

                if (answer !=0)
                {

                    $("#row_"+value).remove();
                    removeByValue(myArray2, value);

                    $('#hiddeni').val(Number($('#hiddeni').val())-1);
                    

            $.ajax({
            type: "POST",
            async:false,
            url: "<?php echo url_for('performance/AjaxDeleteAssignEmployee') ?>",
            data: { EVid: $('#cmbbtype').val() , ETid: $('#cmbEtype').val() , Empno:value },
            dataType: "json",
            success: function(data){
            }
            });
                    $(function () { 
                        $("#tohide").depagination();
                        $("#tohide").pagination();
                    });

                }
                else{
                    return false;
                }

            }



            $(document).ready(function() {
                buttonSecurityCommon("null","editBtn","null","null");
                $('#bulkemp').hide();
                $('#empRepPopBtn').click(function() {
                    var EVid=$('#cmbEtype').val();
                    //var ETid=$('#cmbbtype').val();
                   // var popup=window.open('<?php //echo public_path('../../symfony/web/index.php/performance/searchEmployee?EVid='+EVid+'&ETid='+ETid+'&type=multiple&method=SelectEmployee'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');
                
                var popup=window.open("<?php echo public_path('../../symfony/web/index.php/evaluation/searchEmployee?EVid=') ?>" + EVid +"&type=multiple&method=SelectEmployee",'Locations','height=450,width=800,resizable=1,scrollbars=1');
                
                if(!popup.opener) popup.opener=self;
                    popup.focus();
                });
//                if("<?php echo $EVID ?>"!= null) {
//                getYear("<?php echo $EVID ?>");
//                
//                }
                //Validate the form
                $("#frmSave").validate({

                    rules: {

                        cmbbtype: { required: true},
                        txtYear: { required: true,number: true }


                    },
                    messages: {
                        cmbbtype: { required:"<?php echo __("Leave Type is required ") ?>"},
                        txtYear:{ required:"<?php echo __("This is required") ?>",number:"<?php echo __("Please Enter Digit") ?>"}

                    }
                });

                // When click edit button
                $("#editBtn").click(function() {
                    var entdate=parseInt($('#txtEntitleDays').val());
                    var enttdate=parseInt($('#txtEnTakenDays').val());
                    var entrem=entdate < enttdate;
                    
                    if($('#cmbbtype').val()==""){
                        alert("<?php echo __("Please Select an Evaluation.") ?>");
                        return false;
                    }
//                    if($('#cmbEtype').val()==""){
//                        alert("<?php echo __("Please Select an Evaluation Type.") ?>");
//                        return false;
//                    }

//console.log(myArray2);
//                    if($('#txtEmpId').val()==null){
//                        alert("<?php echo __("Please Select an Employee.") ?>");
//                        return false;
//                    }else{
//                            
//                            //$("#txtEmpId").val(myArray2);
//                            //alert($('#txtEmpId').val());
//                            if($('#txtEmpId').val()==""){
//                                alert("<?php echo __("Please Select an Employee.") ?>");
//                                return false;
//                            }else{
//                                //$('#frmSave').submit();
//                            }
//                        }
                        $('#frmSave').submit();
                    
                });

                //When click reset buton
                $("#resetBtn").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/SaveAssingEmployee')) ?>";
                });

                //When Click back button
                $("#btnBack").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/AssingEmployee')) ?>";
        });

    });
</script>
