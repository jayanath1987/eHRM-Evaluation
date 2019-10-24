<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>
<?php
$encrypt = new EncryptionHandler();
?>
<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Employee Evaluation Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>


                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <option value="ft_title" <?php if($searchMode=="ft_title"){ echo "selected=selected"; }  ?> ><?php echo __("Function/Task Title") ?></option>
                    <!--<option value="Branch_name" <?php if($searchMode=="Branch_name"){ echo "selected=selected"; }  ?> ><?php //echo __("Branch Name") ?></option>-->
                </select>

                <label for="searchValue"><?php echo __("Search For") ?></label>
                <input type="text" size="20" name="searchValue" id="searchValue" value="<?php echo $searchValue ?>" />
                <input type="submit" class="plainbtn"
                       value="<?php echo __("Search") ?>" />
                <input type="reset" class="plainbtn"
                       value="<?php echo __("Reset") ?>" id="resetBtn"/>
                <br class="clear"/>
            </div>
        </form>
        <div class="actionbar">
<!--            <div class="actionbuttons">

                <input type="button" class="plainbtn" id="buttonAdd"
                       value="<?php echo __("Add") ?>" />


                <input type="button" class="plainbtn" id="buttonRemove"
                       value="<?php echo __("Delete") ?>" />

            </div>-->
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?> </div>
            <br class="clear" />
        </div>
        <br class="clear" />
        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('evaluation/DeleteEmployeeEvaluation') ?>">
            <input type="hidden" name="mode" id="mode" value=""/>
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>
                        <td width="50">

                            <input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" />

                        </td>

                        <td scope="col">
                            <?php echo  __('Work/Professional Category'); ?>
                        </td>
                        <td scope="col">
                            <?php //echo $sorter->sortLink("b.emp_number", __('Employee Name'), '@DefineEmployeeEvaluation', ESC_RAW); ?>
                            <?php echo __('Employee Name'); ?>
                        </td>
                        <td scope="col">
                            <?php echo __('Status'); ?>
                        </td>
                        <td scope="col">

                        </td>
                        <td scope="col">

                        </td>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                            $row = 0;
                            if($EvaluationEmployeeList){
                            foreach ($EvaluationEmployeeList as $EvaluationEmployee) { 
                                if($_SESSION['empNumber']== $EvaluationEmployee->emp_number){
                                $cssClass = ($row % 2) ? 'even' : 'odd';
                                $row = $row + 1;
                    ?>
                                <tr class="<?php echo $cssClass ?>">
                                    <td >
                                        <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $EvaluationEmployee->eval_id.'_'.$EvaluationEmployee->emp_number ?>' />
                                    </td>

                                    <td class="">
                                        <?php echo $EvaluationEmployee->EvaluationCompany->eval_name; ?>
                                    </td>

                                    <td class="">
                                      
                                          <?php 
                                if ($Culture == 'en') {
                                    echo $EvaluationEmployee->Employee->emp_display_name;
                                } 

                    ?>
                        </td>
                        <td class="">
                                        <?php if($EvaluationEmployee->ev_complete_flg == "0"){                                              
                                            echo "Not Started ";
                                        }else if($EvaluationEmployee->ev_complete_flg == "1"){
                                            echo "Superviser Completed ";
                                            if($EvaluationEmployee->ev_employee_agree == "1"){ 
                                                echo "- Finished";
                                            }
                                        }else if($EvaluationEmployee->ev_complete_flg == "2"){    
                                            echo "Moderator Completed "; 
                                            if($EvaluationEmployee->ev_employee_agree == "1"){ 
                                                echo "- Finished";
                                            }
                                         }
                                         ?>
                                    </td>
                        <td class="">
                         <?php if($type == null){ ?>   
                        <a href="<?php echo url_for('evaluation/EmployeeEvaluation?id=' . $encrypt->encrypt($EvaluationEmployee->ev_id).'&type=2') ?>"><?php
                                if ($Culture == 'en') {
                                    echo "View";
                                } 
                                ?> </a> <?php  } ?>  
                        </td>
                        <td class="">
                        </td>

                    </tr>
                                <?php } } } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function validateform(){

                if($("#searchValue").val()=="")
                {

                    alert("<?php echo __('Please enter search value') ?>");
                    return false;

                }
                if($("#searchMode").val()=="all"){
                    alert("<?php echo __('Please select the search mode') ?>");
                    return false;
                }
                else{
                    $("#frmSearchBox").submit();
                }

            }
            $(document).ready(function() {
                buttonSecurityCommon("buttonAdd","null","null","buttonRemove");
                //When click add button
                $("#buttonAdd").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/UpdateEmployeeEvaluation')) ?>";

                });

                // When Click Main Tick box
                $("#allCheck").click(function() {
                    if ($('#allCheck').attr('checked')){

                        $('.innercheckbox').attr('checked','checked');
                    }else{
                        $('.innercheckbox').removeAttr('checked');
                    }
                });

                $(".innercheckbox").click(function() {
                    if($(this).attr('checked'))
                    {

                    }else
                    {
                        $('#allCheck').removeAttr('checked');
                    }
                });


                //When click reset buton
                $("#resetBtn").click(function() {
                    if($emp!= null && $type!= null){
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/DefineEmployeeEvaluation?emp='.$emp.'&type='.$type)) ?>";    
                    }else{
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/evaluation/DefineEmployeeEvaluation')) ?>";
                    }
               });

                $("#buttonRemove").click(function() {
                    $("#mode").attr('value', 'delete');
                    if($('input[name=chkLocID[]]').is(':checked')){
                        answer = confirm("<?php echo __("Do you really want to Delete?") ?>");
                    }


                    else{
                        alert("<?php echo __("select at least one check box to delete") ?>");

                    }

            if (answer !=0)
            {

                $("#standardView").submit();

            }
            else{
                return false;
            }

        });

        //When click Save Button
        $("#buttonAdd").click(function() {
            $("#mode").attr('value', 'save');
        });



    });


</script>
