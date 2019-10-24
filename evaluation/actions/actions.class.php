<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
/**
 * Actions class for Performance module
 *
 * -------------------------------------------------------------------------------------------------------
 *  Author    - Jayanath Liyanage
 *  On (Date) - 10 May 2013 
 *  Comments  - Employee Evaluation Functions 
 *  Version   - Version 1.0
 * -------------------------------------------------------------------------------------------------------
 * */
include ('../../lib/common/LocaleUtil.php');
include ('../../lib/common/Struct.php');

ini_set('memory_limit', '-1');
class evaluationActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */

    //Rate
    public function executeRate(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $EvaluationService = new EvaluationService();

            $this->sorter = new ListSorter('EvaluationRate', 'evaluation', $this->getUser(), array('r.rate_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('evaluation/Rate');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'r.rate_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $EvaluationService->searchRate($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->RateList = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeSaveRate(sfWebRequest $request) {
        $this->myCulture = $this->getUser()->getCulture();
        try {
            $EvaluationService = new EvaluationService();

            $Rate = new EvaluationRate();

            if ($request->isMethod('post')) {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();

               $rateObj=$EvaluationService->getRatesObj($request,$Rate);

              

                $EvaluationService->saveRate($rateObj);
                $RateCode = $EvaluationService->getLastRateID();


                $exploed = array();
                $count_rows = array();

                foreach ($_POST as $key => $value) {


                    $exploed = explode("_", $key);


                    if (strlen($exploed[1])) {

                        $count_rows[] = $exploed[1];

                        $arrname = "a_" . $exploed[1];

                        if (!is_array($$arrname)) {
                            $$arrname = Array();
                        }

                        ${$arrname}[$exploed[0]] = $value;
                    }
                }

                $uniqueRowIds = array_unique($count_rows);
                $uniqueRowIds = array_values($uniqueRowIds);

                for ($i = 0; $i < count($uniqueRowIds); $i++) {
                    $RateDetail = new EvaluationRateDetails();
                    $RateDetail->setRate_id($RateCode[0]['MAX']);



                    $v = "a_" . $uniqueRowIds[$i];



                    if (!strlen(${$v}[txtGrade])) {
                        $RateDetail->setRdt_grade(null);
                    } else {
                        $RateDetail->setRdt_grade(${$v}[txtGrade]);
                    }
                    if (!strlen(${$v}[txtMarks])) {

                        $RateDetail->setRdt_mark(null);
                    } else {
                        $RateDetail->setRdt_mark(${$v}[txtMarks]);
                    }
                    if (!strlen(${$v}[txtRIDesc])) {

                        $RateDetail->setRdt_description(null);
                    } else {
                        $RateDetail->setRdt_description(${$v}[txtRIDesc]);
                    }
                    $EvaluationService->saveRateDetail($RateDetail);
                }
                //---------------

                $conn->commit();
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Saved", $args, 'messages')));
                $this->redirect('evaluation/Rate');
            }
        } catch (sfStopException $sf) {
            
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('evaluation/Rate');
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('evaluation/Rate');
        }
    }

    public function executeUpdateRate(sfWebRequest $request) {
        //Table Lock code is Open

        $encrypt = new EncryptionHandler();
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $RateID = $encrypt->decrypt($request->getParameter('id'));
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_evl_rate', array($RateID), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {
                $conHandler = new ConcurrencyHandler();
                $conHandler->resetTableLock('hs_hr_evl_rate', array($RateID), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
        $this->myCulture = $this->getUser()->getCulture();
        $EvaluationService = new EvaluationService();
        $Rate = new EvaluationRate();

        $Rate = $EvaluationService->readRate($encrypt->decrypt($request->getParameter('id')));
        if (!$Rate) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
            $this->redirect('evaluation/Rate');
        }

        $this->Rate = $Rate;
        $this->RateDetails = $EvaluationService->readRateDetailList($RateID,$Rate->rate_option);

        try {
            if ($request->isMethod('post')) {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();

               $Rate=$EvaluationService->GetUpdateRateObj($request,$Rate);



                $EvaluationService->saveRate($Rate);
                $EvaluationService->deleteRateDetail($RateID);


                $exploed = array();
                $count_rows = array();

                foreach ($_POST as $key => $value) {


                    $exploed = explode("_", $key);


                    if (strlen($exploed[1])) {

                        $count_rows[] = $exploed[1];

                        $arrname = "a_" . $exploed[1];

                        if (!is_array($$arrname)) {
                            $$arrname = Array();
                        }

                        ${$arrname}[$exploed[0]] = $value;
                    }
                }

                $uniqueRowIds = array_unique($count_rows);
                $uniqueRowIds = array_values($uniqueRowIds);

                for ($i = 0; $i < count($uniqueRowIds); $i++) {
                    $RateDetail = new EvaluationRateDetails();
                    $RateDetail->setRate_id($RateID);



                    $v = "a_" . $uniqueRowIds[$i];



                    if (!strlen(${$v}[txtGrade])) {
                        $RateDetail->setRdt_grade(null);
                    } else {
                        $RateDetail->setRdt_grade(${$v}[txtGrade]);
                    }
                    if (!strlen(${$v}[txtMarks])) {

                        $RateDetail->setRdt_mark(null);
                    } else {
                        $RateDetail->setRdt_mark(${$v}[txtMarks]);
                    }
                    if (!strlen(${$v}[txtRIDesc])) {

                        $RateDetail->setRdt_description(null);
                    } else {
                        $RateDetail->setRdt_description(${$v}[txtRIDesc]);
                    }
                    $EvaluationService->saveRateDetail($RateDetail);
                }
                //---------------

                $conn->commit();
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
                $this->redirect('evaluation/UpdateRate?id=' . $encrypt->encrypt($Rate->rate_id) . '&lock=0');
            }
        } catch (sfStopException $sf) {
            
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('evaluation/UpdateRate?id=' . $encrypt->encrypt($Rate->rate_id) . '&lock=0');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('evaluation/UpdateRate?id=' . $encrypt->encrypt($Rate->rate_id) . '&lock=0');
        }
    }

    public function executeDeleteRate(sfWebRequest $request) {
        if (count($request->getParameter('chkLocID')) > 0) {
                $EvaluationService=new EvaluationService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_perf_rate', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $EvaluationService->deleteRateDetail($ids[$i]);
                        $EvaluationService->deleteRate($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_perf_rate', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/Rate');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/Rate');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('evaluation/Rate');
    }
    
    
    public function executeCompanyEvaluationInfo(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();
           
           $EvaluationService = new EvaluationService();
            $this->sorter = new ListSorter('EvaluationCompany', 'evaluation', $this->getUser(), array('e.eval_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));
            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('evaluation/CompanyEvaluationInfo');
                }
                $this->var = 1;
            }
            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');
            $this->sort = ($request->getParameter('sort') == '') ? 'e.rate_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $EvaluationService->searchEvaluationCompanyInfo($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->EvaluationList = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeSaveCompanyEvaluationInfo(sfWebRequest $request) {
        $this->myCulture = $this->getUser()->getCulture();
        
        $EvaluationService = new EvaluationService();
        $this->RateList = $EvaluationService->readRateList();
        $this->YearList = $EvaluationService->readYearList();
        $EvaluationComInfo = new EvaluationCompany();
        if ($request->isMethod('post')) {
        

            try {
                $EvaluationService->saveEvaluationCompanyInfo($EvaluationComInfo,$request);
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/CompanyEvaluationInfo');
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/CompanyEvaluationInfo');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Saved", $args, 'messages')));
            $this->redirect('evaluation/CompanyEvaluationInfo');
        }
    }

    /*
     *  Delete Company Evaluation Information
     */

    public function executeDeleteCompanyEvaluationInfo(sfWebRequest $request) {
        if (count($request->getParameter('chkLocID')) > 0) {
            $performanceSearchService = new PerformanceSearchService();
            $PerformanceSaveService = new PerformanceSaveService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_perf_evaluation', array($ids[$i]), 1);
                    if ($isRecordLocked) {
                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $PerformanceSaveService->deleteEvaluationCompanyInfo($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_perf_evaluation', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/CompanyEvaluationInfo');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/CompanyEvaluationInfo');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('evaluation/CompanyEvaluationInfo');
    }

    /*
     *  Update Company Evaluation Information
     */

    public function executeUpdateCompanyEvaluationInfo(sfWebRequest $request) {
        //Table Lock code is Open
        $encrypt = new EncryptionHandler();
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $PGID = $encrypt->decrypt($request->getParameter('id'));
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_perf_evaluation', array($PGID), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {
                $conHandler = new ConcurrencyHandler();
                $conHandler->resetTableLock('hs_hr_perf_evaluation', array($PGID), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
        $this->myCulture = $this->getUser()->getCulture();
        $EvaluationService = new EvaluationService();
        $EvaluationComInfo = new EvaluationCompany();

        $EvaluationComInfo = $EvaluationService->readEvaluationCompanyInfo($encrypt->decrypt($request->getParameter('id')));
        if (!$EvaluationComInfo) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
            $this->redirect('evaluation/CompanyEvaluationInfo');
        }

        $this->Evaluation = $EvaluationComInfo;
        $this->YearList = $EvaluationService->readYearList();
        $this->RateList = $EvaluationService->readRateList();
        if ($request->isMethod('post')) {
            

            try {
                $EvaluationService->saveEvaluationCompanyInfo($EvaluationComInfo,$request);
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/UpdateCompanyEvaluationInfo?id=' . $encrypt->encrypt($EvaluationComInfo->eval_id) . '&lock=0');
            } catch (sfStopException $e) {
                
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/UpdateCompanyEvaluationInfo?id=' . $encrypt->encrypt($EvaluationComInfo->eval_id) . '&lock=0');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('evaluation/UpdateCompanyEvaluationInfo?id=' . $encrypt->encrypt($EvaluationComInfo->eval_id) . '&lock=0');
        }
    }
    
     public function executeSaveAssingEmployee(sfWebRequest $request) {
        //$performanceService = new PerformanceService();
        $EvaluationService = new EvaluationService();
        $this->Culture = $this->getUser()->getCulture();
        $this->EvaluationList = $EvaluationService->getEvaluationList();

        $this->EVID = $request->getParameter('EVID');

        try {
            //die(print_r($_POST));
            if ($request->isMethod('post')) {

                $count = 0;
        $this->EVID = $request->getParameter('cmbbtype'); //die(print_r($_POST));

         $EvaluationService = new EvaluationService();

//---------
                    $exploed = array();
                    $count_rows = array();
                    foreach ($_POST as $key => $value) {


                        $exploed = explode("_", $key);

                        if (strlen($exploed[1])) {
                            $count_rows[] = $exploed[1];

                            $arrname = "a_" . $exploed[1];

                            if (!is_array($$arrname)) {
                                $$arrname = Array();
                            }

                            ${$arrname}[$exploed[0]] = $value;
                        }
                    }



                    $uniqueRowIds = array_unique($count_rows);
                    $uniqueRowIds = array_values($uniqueRowIds);

//
                    $conn = Doctrine_Manager::getInstance()->connection();
                    $conn->beginTransaction();
                 
                    
                    for ($i = 0; $i < count($uniqueRowIds); $i++) {

                        $supEvalObj= new PerformanceEvaluationSupervisor();
                        $v = "a_" . $uniqueRowIds[$i];

                        $supEvalObj->setEval_id($this->EVID);

                        $supEvalObj->setEmp_number(${$v}[hiddneEmpID]);
                        $supEvalObj->setEval_type_id($this->ETID);
                        
                                                
                        $EvaluationService->getDeleteEvaluationEmpList($request->getParameter('cmbbtype'),  ${$v}[empno]);
                        $EvalEmployee = new EvaluationSupervisorNominee();
                        $Max=$EvaluationService->getMaxEvaluationSupervisorNominee();
                        //die(print_r($Max));
                        $EvalEmployee->setEvl_id($Max[0]['MAX']+1);
                        $EvalEmployee->setEval_id($request->getParameter('cmbbtype'));
                        if(${$v}[empno] != null){
                        $EvalEmployee->setEmp_number(${$v}[empno]);
                        if(${$v}[hiddneSupID] != null){
                        $EvalEmployee->setSup_num(${$v}[hiddneSupID]);
                        }else{
                        $EvalEmployee->setSup_num(null);    
                        }
                        $EvalEmployee->setEval_sup_flag(null);
                        if(${$v}[hiddnenominee] != null){
                        $EvalEmployee->setEvl_nomine_emp_number(${$v}[hiddnenominee]);
                        }else{
                        $EvalEmployee->setEvl_nomine_emp_number(null); 
                        }
                        
                        $EvalEmployee->save();
                        }
                    
                    
//---------                    

                    
                }
                $conn->commit();
                if ($count == 0) {
                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Saved.", $args, 'messages')));
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some employee(s) were not added due to the company evaluation not defined for their designation,level,service and job role.", $args, 'messages')));
                }
                if($request->getParameter('cmbbtype')!= null){
                    $this->redirect('evaluation/SaveAssingEmployee?EVID='.$request->getParameter('cmbbtype'));
                }else{
                     $this->redirect('evaluation/SaveAssingEmployee');
                }
            }
        } catch (sfStopException $sf) {
            
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollback();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('evaluation/SaveAssingEmployee');
        } catch (Exception $e) {
            $conn->rollback();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('evaluation/SaveAssingEmployee');
        }
    }
        
    
     public function executeCurrentEmployee(sfWebRequest $request) {

        $EvaluationService = new EvaluationService();
        $EVid = $request->getParameter('EVid');

        $emplist = $EvaluationService->getEvaluationEmpList($EVid);
//die(print_r($emplist));
        foreach ($emplist as $emp) {
            
            $employee=$EvaluationService->getEmployeeDetail($emp['emp_number']);
            
            if($emp['sup_num']!= null){
            $employeeSupervisor=$EvaluationService->getEmployeeDetail($emp['sup_num']);
                $supervisorID=$employeeSupervisor->employeeId;
                $supervisorName=$employeeSupervisor->emp_display_name;
                $supervisorEmpNuber=$employeeSupervisor->empNumber;
            }else{
                $supervisorID=null;
                $supervisorName=null;
                $supervisorEmpNuber=null;
            }
            if($emp['evl_nomine_emp_number']!= null){
            $employeeNominee=$EvaluationService->getEmployeeDetail($emp['evl_nomine_emp_number']);
                $NomineeID=$employeeNominee->employeeId;
                $NomineeName=$employeeNominee->emp_display_name;
                $NomineeEmpNumber=$employeeNominee->empNumber;
            }else{
                $NomineeID=null;
                $NomineeName=null;
                $NomineeEmpNumber=null;
            }

            
            $arr[$emp['emp_number']] = $employee->employeeId . "|" . $employee->emp_display_name."|". $employee->empNumber."|". $supervisorID . "|" . $supervisorName."|". $supervisorEmpNuber."|".$NomineeID . "|" . $NomineeName."|". $NomineeEmpNumber;
            //$arr[$row['employeeId']] = $row['employeeId'] . "|" . $row[$abc] . "|" . $comTitle . "|" . $row['emp_status'] . "|" . $row['empNumber'] . "|" .$supername."|".$superno;
        }
        //die(print_r($arr));
        echo json_encode($arr);
        die;
    }
    
     public function executeYear(sfWebRequest $request) {

        $ID = $request->getParameter('id');
        $EvaluationService = new EvaluationService();
        $Year = $EvaluationService->getEvaluationYear($ID);
        echo json_encode($Year[0]['eval_year']);
        die;
    }

 public function executeLoadGrid(sfWebRequest $request) {
        $culture = $this->getUser()->getCulture();
        $EvaluationService = new EvaluationService();
        $empId = $request->getParameter('empid');

        $emplist = $EvaluationService->getEmployee($empId);
        $arr = Array();
//$n="td_course_name_".$culture;
$EvaluationDao = new EvaluationDao();

foreach ($emplist as $row) {

    if ($culture == "en") {
        $abc = "emp_display_name";
    } else {
        $abc = "emp_display_name_" . $culture;
    }
    if ($culture == "en") {
        $title = "title";
    } else {
        $title = "title_" . $culture;
    }
    $comStruture = $EvaluationDao->getCompnayStructure($row['work_station']);
    if ($culture == "en") {
        $title = "getTitle";
    } else {
        $title = "getTitle_" . $culture;
    }
    if ($comStruture) {
        $comTitle = $comStruture->$title();
    }
    $supervisor = $EvaluationDao->getDefaultSupervisor($row['empNumber']);
    if($supervisor->supervisor->emp_display_name != null && $supervisor->supervisorId != null){
        $supername= $supervisor->supervisor->emp_display_name;
        $superno=$supervisor->supervisorId ;
    }
    
    $arr[$row['employeeId']] = $row['employeeId'] . "|" . $row[$abc] . "|" . $comTitle . "|" . $row['emp_status'] . "|" . $row['empNumber'] . "|" .$supername."|".$superno;
}

//die(print_r($arr));

echo json_encode($arr);
        die;
    }
    
    
    public function executeSearchEmployee(sfWebRequest $request) {
        try {

            $this->userCulture = $this->getUser()->getCulture();
            
            
            $EvaluationService = new EvaluationService();
            $this->type = $request->getParameter('type', isset($_SESSION["type"]) ? $_SESSION["type"] : 'single');
            $this->method = $request->getParameter('method', isset($_SESSION["method"]) ? $_SESSION["method"] : '');
            $reason = $request->getParameter('reason');
            if (strlen($reason)) {
                $this->reason = $reason;
            } else {
                $this->reason = '';
            }

            $att = $request->getParameter('att');
            if (strlen($att)) {
                $this->att = $att;
            } else {
                $this->att = '';
            }
            $EVid = $request->getParameter('EVid');
            if (strlen($EVid)) {
                $this->EVid = $EVid;
            } else {
                $this->EVid = '';
            }
            $ETid = $request->getParameter('ETid');
            if (strlen($ETid)) {
                $this->ETid = $ETid;
            } else {
                $this->ETid = '';
            }


            //Store in session to support sorting
            $_SESSION["type"] = $this->type;
            $_SESSION["method"] = $this->method;

            $this->sorter = new ListSorter('propoerty.sort', 'pim_module', $this->getUser(), array('emp_number', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            $this->searchMode = ($request->getParameter('cmbSearchMode') == '') ? 'all' : $request->getParameter('cmbSearchMode');
            $this->searchValue = ($request->getParameter('txtSearchValue') == '') ? '' : $request->getParameter('txtSearchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'e.emp_number' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $result = $EvaluationService->searchEmployee($this->searchMode, $this->searchValue, $this->userCulture, $request->getParameter('page'), $this->sort, $this->order, $this->type, $this->method, $this->reason, $this->att, $this->ETid, $this->EVid);

            $this->listEmployee = $result['data'];
            $this->pglay = $result['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (sfStopException $sf) {
            $this->redirect('evaluation/searchEmployee');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('evaluation/searchEmployee');
        }
    }
    
        public function executeEvalFunctionTask(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $EvaluationService = new EvaluationService();

            $this->sorter = new ListSorter('EvalFunctionTask', 'evaluation', $this->getUser(), array('b.ft_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('evaluation/EvalFunctionTask');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.ft_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $EvaluationService->EvalFunctionTask($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->FTList = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }
    
public function executeUpdateEvalFunctionTask(sfWebRequest $request) {
        $EvaluationService = new EvaluationService();
        $this->myCulture = $this->getUser()->getCulture();
         
        if($_SESSION['empNumber']){
        $Employee=$EvaluationService->LoadEmpData($_SESSION['empNumber']);
        $this->EmployeeNumber= $Employee[0]['empNumber'];
        $this->EmpDisplayName= $Employee[0]['emp_display_name'];
        }
        
        //Table Lock code is Open
        if ($request->getParameter('id')) {
            $encrypt = new EncryptionHandler();
            if (!strlen($request->getParameter('lock'))) {
                $this->lockMode = 0;
            } else {
                $this->lockMode = $request->getParameter('lock');
            }
            $VTID = $encrypt->decrypt($request->getParameter('id'));
            if (isset($this->lockMode)) {
                if ($this->lockMode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock('hs_hr_evl_functions_tasks', array($VTID), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->lockMode = 1;
                    } else {
                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->lockMode = 0;
                    }
                } else if ($this->lockMode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock('hs_hr_evl_functions_tasks', array($VTID), 1);
                    $this->lockMode = 0;
                }
            }

            //Table lock code is closed


            $EvalFunctionTask = $EvaluationService->readEvalFunctionTask($encrypt->decrypt($request->getParameter('id')));
            if (!$EvalFunctionTask) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
                $this->redirect('evaluation/EvalFunctionTask');
            }
        } else {

            $this->lockMode = 1;
        }
        $this->EvalFunctionTask = $EvalFunctionTask;
        $this->EvaluationList = $EvaluationService->getEvaluationList();
        
        if ($request->isMethod('post')) { //die(print_r($_POST));
            
            if(strlen($request->getParameter('txtid'))){
                $EvalFunctionTask=$EvaluationService->readEvalFunctionTask($request->getParameter('txtid'));
            }else{
                 $EvalFunctionTask=new EvaluationFunctionsTask();
            }
            
            if (strlen($request->getParameter('cmbCompEval'))) {
                $EvalFunctionTask->setEval_id(trim($request->getParameter('cmbCompEval')));
            } else {
                $EvalFunctionTask->setEval_id(null);
            }
            if (strlen($request->getParameter('txtEmpId'))) {
                $EvalFunctionTask->setEmp_number(trim($request->getParameter('txtEmpId')));
            } else {
                $EvalFunctionTask->setEmp_number(null);
            }
            if (strlen($request->getParameter('txtFTName'))) {
                $EvalFunctionTask->setFt_title(trim($request->getParameter('txtFTName')));
            } else {
                $EvalFunctionTask->setFt_title(null);
            }
            if (strlen($request->getParameter('txtFTDesc'))) {
                $EvalFunctionTask->setFt_description(trim($request->getParameter('txtFTDesc')));
            } else {
                $EvalFunctionTask->setFt_description(null);
            }
            if (strlen($request->getParameter('txtIndicator'))) {
                $EvalFunctionTask->setFt_target_indicater(trim($request->getParameter('txtIndicator')));
            } else {
                $EvalFunctionTask->setFt_target_indicater(null);
            }
            if (strlen($request->getParameter('txtFromDate'))) {
                $EvalFunctionTask->setFt_from_date(trim($request->getParameter('txtFromDate')));
            } else {
                $EvalFunctionTask->setFt_from_date(null);
            }
            if (strlen($request->getParameter('txtToDate'))) {
                $EvalFunctionTask->setFt_to_date(trim($request->getParameter('txtToDate')));
            } else {
                $EvalFunctionTask->setFt_to_date(null);
            }
            if (strlen($request->getParameter('chkActive'))) {
                $EvalFunctionTask->setFt_active_flg(1);
            } else {
                $EvalFunctionTask->setFt_active_flg(0);
            }
                $EvalFunctionTask->setFt_approve_flg(1);

            try {
                $EvalFunctionTask->save();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());

                if ($request->getParameter('txtid') != null) {
                    $this->redirect('evaluation/UpdateEvalFunctionTask?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0');
                } else {
                    $this->redirect('evaluation/EvalFunctionTask');
                }
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());

                if ($request->getParameter('txtid') != null) {
                    $this->redirect('evaluation/UpdateEvalFunctionTask?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0');
                } else {
                    $this->redirect('evaluation/EvalFunctionTask');
                }
            }
            if ($request->getParameter('txtid') != null) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
                $this->redirect('evaluation/UpdateEvalFunctionTask?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0');
            } else {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Saved", $args, 'messages')));
                $this->redirect('evaluation/EvalFunctionTask');
            }
        }
    }

    
    public function executeDeleteEvalFunctionTask(sfWebRequest $request) {
        if (count($request->getParameter('chkLocID')) > 0) {
                $EvaluationService = new EvaluationService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_evl_functions_tasks', array($ids[$i]), 1);
                    if ($isRecordLocked) {
                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $EvaluationService->DeleteEvalFunctionTask($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_evl_functions_tasks', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/EvalFunctionTask');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/EvalFunctionTask');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('evaluation/EvalFunctionTask');
    }

    public function executeFTApproveSearch(sfWebRequest $request) { 
        
        

        
        $this->Culture = $this->getUser()->getCulture();
        $this->sorter = new ListSorter('Leave', 'LeaveSearch', $this->getUser(), array('a.ft_id', ListSorter::ASCENDING));
        $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

        $EvaluationService = new EvaluationService();

        $this->sort = ($request->getParameter('sort') == '') ? 'a.ft_id' : $request->getParameter('sort');
        $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
        $encrypt = new EncryptionHandler();
        if(strlen($request->getParameter('Employee'))){
        $this->EmployeeSub = (($encrypt->decrypt($request->getParameter('Employee')) == null)) ? $encrypt->decrypt($request->getParameter('Employee')) : $encrypt->decrypt($request->getParameter('Employee'));
        }else{
            $this->EmployeeSub = null;
        }
        $this->chkAll = ($request->getParameter('chkAll') == null) ? $request->getParameter('chkAll') : $_POST['chkAll'];
        $this->chkPending = ($request->getParameter('chkPending') == null) ? $request->getParameter('chkPending') : $_POST['chkPending'];
        $this->chkApproved = ($request->getParameter('chkApproved') == null) ? $request->getParameter('chkApproved') : $_POST['chkApproved'];
        $this->chkRejected = ($request->getParameter('chkRejected') == null) ? $request->getParameter('chkRejected') : $_POST['chkRejected'];
        $this->chkCanceled = ($request->getParameter('chkCanceled') == null) ? $request->getParameter('chkCanceled') : $_POST['chkCanceled'];
        $this->chkTaken = ($request->getParameter('chkTaken') == null) ? $request->getParameter('chkTaken') : $_POST['chkTaken'];
        
        $this->EmployeeName = ($request->getParameter('txtEmployeeName') == null) ? $request->getParameter('txtEmployeeName') : $_POST['txtEmployeeName'];
        $this->searchMode = ($request->getParameter('txttdate') == null) ? $request->getParameter('searchMode') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txttdate']);
        $this->searchValue = ($request->getParameter('txtfdate') == null) ? $request->getParameter('searchValue') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txtfdate']);
            $this->emp = ($request->getParameter('txtEmpId') == null) ? $request->getParameter('emp') : $_POST['txtEmpId'];
        $this->type = ($request->getParameter('abc') == null) ? $request->getParameter('type') : $_POST['abc'];
        $this->post = ($_POST == null) ? $request->getParameter('post') : $_POST;

        if($request->getParameter('emp')!= null){
            //$pieces = explode("_", $request->getParameter('emp'));
            $pieces=str_replace("_",",",$request->getParameter('emp'));
            $this->emp =$pieces;

        }
//        else{
//             $this->setMessage('NOTICE', array('Please Select an Employee'));
//              $this->redirect('evaluation/FTApproveSearch');
//        }
        //die(print_r($this->emp));

        $res = $EvaluationService->viewall($this->searchValue, $this->searchMode, $request->getParameter('page'), $this->emp, $this->type, $this->sort, $this->order, $this->EmployeeSub,$this->chkAll,$this->chkPending,$this->chkApproved,$this->chkRejected,$this->chkCanceled,$this->chkTaken);
        $this->view = $res['data'];
        $this->pglay = $res['pglay'];
        $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
        $this->pglay->setSelectedTemplate('{%page}');
    }
    
        public function executeAjaxTableLock(sfWebRequest $request) {


        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $ft_id = $request->getParameter('ft_id');

        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_evl_functions_tasks', array($ft_id), 1);

                if ($recordLocked) {
                    $this->lockMode = 1;
                    $EvaluationService = new EvaluationService();
                    $EvalFunctionTask = $EvaluationService->readEvalFunctionTask($ft_id);
                    $this->status = $EvalFunctionTask->ft_active_flg;
                    //$this->comment = $EvalFunctionTask->getLeave_app_comment();
                } else {

                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {

                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_evl_functions_tasks', array($ft_id), 1);
                $this->lockMode = 0;
            }
            echo json_encode(array("status" => $this->status, "comment" => $this->comment, "lockMode" => $this->lockMode));
            die;
        }
    }
    
        public function executeSaveApprove(sfWebRequest $request) {
            
        $EvaluationService = new EvaluationService();
        
        $leaveDao = new LeaveDao();
        $ft_id = $request->getParameter('ft_id');
        $status = $request->getParameter('status');
        $comment = $request->getParameter('comment');
        try {
           $EvalFunctionTask = $EvaluationService->readEvalFunctionTask($ft_id);
            
           $EvalFunctionTask->setFt_approve_flg($status);
           $EvalFunctionTask->save();
            //$conn->commit();
            $this->isupdated = "true";
            

            
        } catch (Exception $e) {
            //$conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->isupdated = "false";
            $this->redirect('Leave/LeaveSearch');
        }
        echo json_encode(array("isupdated" => $this->isupdated));
        die;
    }

    
    public function executeDefineEmployeeEvaluation(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $EvaluationService = new EvaluationService();

            $this->sorter = new ListSorter('DefineEmployeeEvaluation', 'evaluation', $this->getUser(), array('b.ev_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('evaluation/DefineEmployeeEvaluation');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.ev_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            
            $this->emp = ($request->getParameter('txtEmpId') == null) ? $request->getParameter('emp') : $_POST['txtEmpId'];
            $this->type = ($request->getParameter('txtType') == null) ? $request->getParameter('type') : $_POST['txtType'];
            
            $res = $EvaluationService->EmployeeEvaluationList($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'), $this->emp, $this->type);
            $this->EvaluationEmployeeList = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }
    
    public function executeUpdateEmployeeEvaluation(sfWebRequest $request) {
        $EvaluationService = new EvaluationService();
        $this->myCulture = $this->getUser()->getCulture();
         
        if($_SESSION['empNumber']){
        $Employee=$EvaluationService->LoadEmpData($_SESSION['empNumber']);
        $this->EmployeeNumber= $Employee[0]['empNumber'];
        $this->EmpDisplayName= $Employee[0]['emp_display_name'];
        }
        $this->type=$request->getParameter('type');
        //Table Lock code is Open
        if ($request->getParameter('id')) {
            $encrypt = new EncryptionHandler();
            if (!strlen($request->getParameter('lock'))) {
                $this->lockMode = 0;
            } else {
                $this->lockMode = $request->getParameter('lock');
            }
            $VTID = $encrypt->decrypt($request->getParameter('id'));
            if (isset($this->lockMode)) {
                if ($this->lockMode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock('hs_hr_evl_evaluation_employee', array($VTID), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->lockMode = 1;
                    } else {
                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->lockMode = 0;
                    }
                } else if ($this->lockMode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock('hs_hr_evl_evaluation_employee', array($VTID), 1);
                    $this->lockMode = 0;
                }
            }

            //Table lock code is closed


            $EvalEmployee = $EvaluationService->readEvalEmployee($encrypt->decrypt($request->getParameter('id')));
            if (!$EvalEmployee) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
                $this->redirect('evaluation/DefineEmployeeEvaluation');
            }
        } else {

            $this->lockMode = 1;
        }
        $this->EvalEmployee = $EvalEmployee;
//        if($EvalEmployee->ev_ts_rv_active_flg == 1 ){
//           $tsdetails = $EvaluationService->gettsdetail();
//        }
        $this->EvaluationList = $EvaluationService->getEvaluationList();
        
        if ($request->isMethod('post')) { //die(print_r($_POST));
            
             $conn = Doctrine_Manager::getInstance()->connection();
             $conn->beginTransaction();
          try {  
            if(strlen($request->getParameter('txtid'))){
                $EvalEmployee=$EvaluationService->readEvalEmployee($request->getParameter('txtid'));
                //$eid = $request->getParameter('txtid');
            }else{
                 $EvalEmployee=new EvaluationEmployee();
                 //$evalrcd = $EvaluationService->getLastEvaluationEmployee();
                      
            }
            
            if (strlen($request->getParameter('cmbCompEval'))) {
                $EvalEmployee->setEval_id(trim($request->getParameter('cmbCompEval')));
            } else {
                $EvalEmployee->setEval_id(null);
            }
            if (strlen($request->getParameter('txtEmpId'))) {
                $EvalEmployee->setEmp_number(trim($request->getParameter('txtEmpId')));
            } else {
                $EvalEmployee->setEmp_number(null);
            }
            if (strlen($request->getParameter('chkFTActive'))) {
                $EvalEmployee->setEv_fn_rv_active_flg(1);
            } else {
                $EvalEmployee->setEv_fn_rv_active_flg(0);
            }            
            if (strlen($request->getParameter('chkMSActive'))) {
                $EvalEmployee->setEv_ms_rv_active_flg(1);
            } else {
                $EvalEmployee->setEv_ms_rv_active_flg(0);
            }  
            if (strlen($request->getParameter('chk360Active'))) {
                $EvalEmployee->setEv_ts_rv_active_flg(1);
            } else {
                $EvalEmployee->setEv_ts_rv_active_flg(0);
            }              
            if (strlen($request->getParameter('txtFNPersentage'))) {
                $EvalEmployee->setEv_fn_rv_percentage(trim($request->getParameter('txtFNPersentage')));
            } else {
                $EvalEmployee->setEv_fn_rv_percentage(null);
            }      
            if (strlen($request->getParameter('txtMSPersentage'))) {
                $EvalEmployee->setEv_ms_rv_percentage(trim($request->getParameter('txtMSPersentage')));
            } else {
                $EvalEmployee->setEv_ms_rv_percentage(null);
            }
            if (strlen($request->getParameter('txtTSPersentage'))) {
                $EvalEmployee->setEv_ts_rv_percentage(trim($request->getParameter('txtTSPersentage')));
            } else {
                $EvalEmployee->setEv_ts_rv_percentage(null);
            }     
                            
            if($_POST['txtClient1']!= null){ 
            $EvalEmployee->setEv_email_client_1($_POST['txtClient1']);
            }  
            if($_POST['txtClient2']!= null){ 
            $EvalEmployee->setEv_email_client_2($_POST['txtClient2']);
            }
            if($_POST['txtClient3']!= null){ 
            $EvalEmployee->setEv_email_client_3($_POST['txtClient3']);
            }
            if($_POST['txtClient4']!= null){ 
            $EvalEmployee->setEv_email_client_4($_POST['txtClient4']);
            }
            if($_POST['txtClient5']!= null){ 
            $EvalEmployee->setEv_email_client_5($_POST['txtClient5']);
            }
            
               
            if($_POST['txtClient1Name']!= null){ 
            $EvalEmployee->setEv_name_client_1($_POST['txtClient1Name']);
            }  
            if($_POST['txtClient2Name']!= null){ 
            $EvalEmployee->setEv_name_client_2($_POST['txtClient2Name']);
            }
            if($_POST['txtClient3Name']!= null){ 
            $EvalEmployee->setEv_name_client_3($_POST['txtClient3Name']);
            }
            if($_POST['txtClient4Name']!= null){ 
            $EvalEmployee->setEv_name_client_4($_POST['txtClient4Name']);
            }
            if($_POST['txtClient5Name']!= null){ 
            $EvalEmployee->setEv_name_client_5($_POST['txtClient5Name']);
            }
            
            if($_POST['txtClient1Designation']!= null){ 
            $EvalEmployee->setEv_desg_client_1($_POST['txtClient1Designation']);
            }  
            if($_POST['txtClient2Designation']!= null){ 
            $EvalEmployee->setEv_desg_client_2($_POST['txtClient2Designation']);
            }
            if($_POST['txtClient3Designation']!= null){ 
            $EvalEmployee->setEv_desg_client_3($_POST['txtClient3Designation']);
            }
            if($_POST['txtClient4Designation']!= null){ 
            $EvalEmployee->setEv_desg_client_4($_POST['txtClient4Designation']);
            }
            if($_POST['txtClient5Designation']!= null){ 
            $EvalEmployee->setEv_desg_client_5($_POST['txtClient5Designation']);
            }
            
            if($_POST['cmbClient1']!= null){ 
            $EvalEmployee->setEv_level_client_1($_POST['cmbClient1']);
            }
            if($_POST['cmbClient2']!= null){ 
            $EvalEmployee->setEv_level_client_2($_POST['cmbClient3']);
            }
            if($_POST['cmbClient3']!= null){ 
            $EvalEmployee->setEv_level_client_3($_POST['cmbClient3']);
            }
            if($_POST['cmbClient4']!= null){ 
            $EvalEmployee->setEv_level_client_4($_POST['cmbClient4']);
            }
            if($_POST['cmbClient5']!= null){ 
            $EvalEmployee->setEv_level_client_5($_POST['cmbClient5']);
            }
            
            
                $EvalEmployee->setEv_complete_flg(0);
                
                
            //FT Save
            if($_POST['txtftid']){
            foreach($_POST['txtftid'] as $row){
                if($_POST['txtfnid_'.$row]!= null){
                $FuntionTask=$EvaluationService->readFuntionTask($_POST['txtfnid_'.$row]);
                
                if($_POST['txtfnfromdate_'.$row]!= null){
                $FuntionTask->setFt_from_date($_POST['txtfnfromdate_'.$row]);
                }
                if($_POST['txtfttodate_'.$row]!= null){
                $FuntionTask->setFt_to_date($_POST['txtfttodate_'.$row]);
                }
                if($_POST['txtfttargetindicater_'.$row]!= null){
                $FuntionTask->setFt_target_indicater($_POST['txtfttargetindicater_'.$row]);
                }
                if($_POST['txtftweight_'.$row]!= null){
                $FuntionTask->setFt_weight($_POST['txtftweight_'.$row]);
                }  
                if($_POST['chkftactiveflg_'.$row]!= null){
                $FuntionTask->setFt_active_flg(1);
                } 
                if($_POST['chkftapproveflg_'.$row]!= null){
                $FuntionTask->setFt_approve_flg(2);
                }
                $FuntionTask->save();    
                }
                
            }
            }
            
            //MS Save
            if($_POST['txtmsid']){
            foreach($_POST['txtmsid'] as $row){
                if($_POST['txtmsid_'.$row]!= null){
                $EvaluationSkillEmployee=$EvaluationService->readEvaluationSkillEmployee($_POST['txtmsid_'.$row],$_POST['cmbCompEval'],$_POST['txtEmpId']);
                
                if(!$EvaluationSkillEmployee){
                    $EvaluationSkillEmployee = new EvaluationSkillEmployee();
                     $SkillMax=$EvaluationService->getLastEvaluationSkillEmployeeID();
                     
                     $EvaluationSkillEmployee->setEmp_skill_id($SkillMax[0]['MAX']+1);
                }

                if($_POST['cmbCompEval']!= null){
                $EvaluationSkillEmployee->setEval_id($_POST['cmbCompEval']);
                }
                if($_POST['txtEmpId']!= null){
                $EvaluationSkillEmployee->setEmp_number($_POST['txtEmpId']);
                }
                if($_POST['txtmsid_'.$row]!= null){
                $EvaluationSkillEmployee->setSkill_id($_POST['txtmsid_'.$row]);
                }
                if($_POST['txtmsfromdate_'.$row]!= null){
                $EvaluationSkillEmployee->setEmp_skill_from_date($_POST['txtmsfromdate_'.$row]);
                }
                if($_POST['txtmstodate_'.$row]!= null){
                $EvaluationSkillEmployee->setEmp_skill_to_date($_POST['txtmstodate_'.$row]);
                }
                if($_POST['txtmstargetindicater_'.$row]!= null){
                $EvaluationSkillEmployee->setEmp_skill_target_indicater($_POST['txtmstargetindicater_'.$row]);
                }
                if($_POST['txtmsweight_'.$row]!= null){
                $EvaluationSkillEmployee->setEmp_skill_weight($_POST['txtmsweight_'.$row]);
                }  
                if($_POST['chkemp_skillactiveflg_'.$row]!= null){
                $EvaluationSkillEmployee->setEmp_skill_active_flg(1);
                }                 
                $EvaluationSkillEmployee->save();    
                }
                
            }
            }
            //die(print_r($_POST));
            //360 Save
            if($_POST['txttsid']){
            foreach($_POST['txttsid'] as $row){
                if($_POST['txttsid_'.$row]!= null){
                $EvaluationTSEmployee=$EvaluationService->readEvaluationTSEmployee($_POST['txttsid_'.$row],$_POST['cmbCompEval'],$_POST['txtEmpId']);
                if($_POST['chkemp_tsactiveflg_'.$row]== 1){
                if(!$EvaluationTSEmployee){
                    $EvaluationTSEmployee = new EvaluationTSEmployee();
                     $TSMax=$EvaluationService->getLastEvaluationTSEmployeeID();
                     
                     $EvaluationTSEmployee->setEmp_ts_id($TSMax[0]['MAX']+1);
                }

                if($_POST['cmbCompEval']!= null){
                $EvaluationTSEmployee->setEval_id($_POST['cmbCompEval']);
                }
                if($_POST['txtEmpId']!= null){
                $EvaluationTSEmployee->setEmp_number($_POST['txtEmpId']);
                }
                if($_POST['txttsid_'.$row] != null){
                $EvaluationTSEmployee->setTs_id($_POST['txttsid_'.$row]);
                }
                if($_POST['txttsfromdate_'.$row]!= null){
                $EvaluationTSEmployee->setEmp_ts_from_date($_POST['txttsfromdate_'.$row]);
                }
                if($_POST['txttstodate_'.$row]!= null){
                $EvaluationTSEmployee->setEmp_ts_to_date($_POST['txttstodate_'.$row]);
                }
                if($_POST['txttstargetindicater_'.$row]!= null){
                $EvaluationTSEmployee->setEmp_ts_target_indicater($_POST['txttstargetindicater_'.$row]);
                }
                if($_POST['txttsweight_'.$row]!= null){
                $EvaluationTSEmployee->setEmp_ts_weight($_POST['txttsweight_'.$row]);
                }  
                if($_POST['chkemp_tsactiveflg_'.$row]!= null){
                $EvaluationTSEmployee->setEmp_ts_active_flg(1);
                }
                
//                if($_POST['txtClient1']!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_email_client_1($_POST['txtClient1']);
//                }  
//                if($_POST['txtClient2']!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_email_client_2($_POST['txtClient2']);
//                }
//                if($_POST['txtClient3']!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_email_client_3($_POST['txtClient3']);
//                }
//                if($_POST['txtClient4']!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_email_client_4($_POST['txtClient4']);
//                }
//                if($_POST['txtClient5']!= null){ die(print_r($_POST['txtClient5']));
//                $EvaluationTSEmployee->setEmp_ts_email_client_5($_POST['txtClient5']);
//                }
                
                
                $EvaluationTSEmployee->save();    
                }
                }
                
            }
            
            for($i=1; $i <= 5; $i++  ){
                if(strlen($_POST['txtClient'.$i])){ 
                if($_POST['cmbClient'.$i]!= null){   
                    $tslist = $EvaluationService->gettslistbylevel($_POST['cmbClient1']);
                }else{
                    $tslist = $EvaluationService->gettslistbylevel(1);
                }
                 
                $client =$i;
                $eid=$request->getParameter('cmbCompEval');
                $defaultDao = new DefaultDao();
                $Subject="360 Performance Evaluation of ICTA";
                $TO = $_POST['txtClient'.$i];
                $CC = "commonhrm@icta.lk";
                
                $Message = "Dear ".$_POST['txtClient'.$i.'Name'].","."<br/>"."<br/>";
                $Message.= "We are pleased to let you know that you have been nominated as a 360  performance evaluator of ". (($this->EvalEmployee->Employee->gender_code == "1")?"Mr. ":"Ms. ").$this->EvalEmployee->Employee->emp_display_name.","."<br/>";
                $Message.= "an employee of ICTA for year ".$this->EvalEmployee->EvaluationCompany->eval_year."<br/>"."<br/>";
                $Message.= "Please be good enough to extend your support to us by filling the relevant sections shown in the link given below."."<br/>";
                $Message.= "<a href='https://hrm.icta.lk/clientEvaluation.php?id=".$eid."&emp=".$_POST['txtEmpId']."&cid=".$client."'>Here you go!</a>"."<br/>"."<br/>"; 
//                foreach ($tslist as $row){
//                $Message.= "<div><div>".$row->ts_title."</div><div> <input id='row_".$i."_".$row->ts_id."'  name='row_".$i."_".$row->ts_id."' type='text'  /></div></div>"."<br/>";
//                }
                $Message.= "Thanks"."<br/>";
                
                $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);

                //$EvaluationTSEmployee->setEmp_ts_client_email_1($_POST['txtClient1']);
                }  
                
            }
                
//                if($_POST['txtClient2']!= null){ 
//                $client =2;
//                $eid=$request->getParameter('cmbCompEval');
//                $defaultDao = new DefaultDao();
//                $Subject="Employee Evaluation ICTA";
//                $TO = $_POST['txtClient2'];
//                $CC = "commonhrm@icta.lk";
//                
//                $Message = "Hi,"."<br/>"."<br/>";
//                $Message.= "We are doing a employee Evaluation for increasing our employee performance, "."<br/>";
//                $Message.= "so this is a your turn to evaluate our guy "."<br/>";
//                $Message.= "<a href='http://localhost/ictalive/clientEvaluation.php?id=".$eid."&emp=".$_POST['txtEmpId']."&cid=".$client."'>Please Click Link</a>"."<br/>"; 
//                $Message.= "Thanks"."<br/>";
//                
//                $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);
//                }
//                if($_POST['txtClient3']!= null){ 
//                $client =3;
//                $eid=$request->getParameter('cmbCompEval');
//                $defaultDao = new DefaultDao();
//                $Subject="Employee Evaluation ICTA";
//                $TO = $_POST['txtClient3'];
//                $CC = "commonhrm@icta.lk";
//                
//                $Message = "Hi,"."<br/>"."<br/>";
//                $Message.= "We are doing a employee Evaluation for increasing our employee performance, "."<br/>";
//                $Message.= "so this is a your turn to evaluate our guy "."<br/>";
//                $Message.= "<a href='http://localhost/ictalive/clientEvaluation.php?id=".$eid."&emp=".$_POST['txtEmpId']."&cid=".$client."'>Please Click Link</a>"."<br/>"; 
//                $Message.= "Thanks"."<br/>";
//                
//                $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);
//                }
            }
            
            
            
            

                $EvalEmployee->save();
                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $conn->rollback();
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());

                if ($request->getParameter('txtid') != null) {
                    $this->redirect('evaluation/UpdateEmployeeEvaluation?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0&type='.$this->type);
                } else {
                    $this->redirect('evaluation/DefineEmployeeEvaluation');
                }
            } catch (Exception $e) {
                $conn->rollback();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());

                if ($request->getParameter('txtid') != null) {
                    $this->redirect('evaluation/UpdateEmployeeEvaluation?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0&type='.$this->type);
                } else {
                    $this->redirect('evaluation/DefineEmployeeEvaluation');
                }
            }
            if ($request->getParameter('txtid') != null) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
                $this->redirect('evaluation/UpdateEmployeeEvaluation?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0&type='.$this->type);
            } else {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Saved", $args, 'messages')));
                $this->redirect('evaluation/DefineEmployeeEvaluation');
            }
        }
    }

    public function executeAjaxGetFTData(sfWebRequest $request) {

        $comeval = $request->getParameter('comeval');
        $eno = $request->getParameter('eno');
        
        $EvaluationService = new EvaluationService();
        $FTData = $EvaluationService->getGetFTData($comeval,$eno);
        //die(print_r($FTData));
        foreach ($FTData as $row) {
            
            $array[] = $row;
        }
        
        echo json_encode(array($FTData));
        die;
    }
    
        public function executeAjaxGetSMData(sfWebRequest $request) {

        $comeval = $request->getParameter('comeval');
        $eno = $request->getParameter('eno');
        $ev_id = $request->getParameter('ev_id');

        $EvaluationService = new EvaluationService();
        $FTData = $EvaluationService->getGetSMData($comeval,$eno,$ev_id);
        //die(print_r($FTData));
        foreach ($FTData as $row) {
            
            $array[] = $row;
        }
        
        echo json_encode(array($FTData));
        die;
    }
    
        public function executeAjaxGet360Data(sfWebRequest $request) {

        $comeval = $request->getParameter('comeval');
        $eno = $request->getParameter('eno');
        $ev_id = $request->getParameter('ev_id');
        
        $EvaluationService = new EvaluationService();
        $FTData = $EvaluationService->getGet360Data($comeval,$eno,$ev_id);
        //die(print_r($FTData));
        foreach ($FTData as $row) {
            
            $array[] = $row;
        }
        
        echo json_encode(array($FTData));
        die;
    }    
    
    public function executeDeleteEmployeeEvaluation(sfWebRequest $request) {
        if (count($request->getParameter('chkLocID')) > 0) {
                $EvaluationService=new EvaluationService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $pieces = explode("_", $ids[$i]);
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_evl_evaluation_employee', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        //die(print_r($pieces));
                        $EvaluationService->deleteTS($id,$pieces[0],$pieces[1]);
                        $EvaluationService->deleteMS($id,$pieces[0],$pieces[1]);
                        $EvaluationService->deleteFT($id,$pieces[0],$pieces[1]);
                        $EvaluationService->deleteEvaluationEmployee($pieces[0],$pieces[1]);
                        $conHandler->resetTableLock('hs_hr_evl_evaluation_employee', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/DefineEmployeeEvaluation');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/DefineEmployeeEvaluation');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('evaluation/DefineEmployeeEvaluation');
    }
    
    public function setMessage($messageType, $message = array(), $persist=true) {
        $this->getUser()->setFlash('messageType', $messageType, $persist);
        $this->getUser()->setFlash('message', $message, $persist);
    }
    
     public function executeEmployeeEvaluation(sfWebRequest $request) {
        $EvaluationService = new EvaluationService();
        $this->myCulture = $this->getUser()->getCulture();
         
        if($_SESSION['empNumber']){
        $Employee=$EvaluationService->LoadEmpData($_SESSION['empNumber']);
        $this->EmployeeNumber= $Employee[0]['empNumber'];
        $this->EmpDisplayName= $Employee[0]['emp_display_name'];
        }
        
        $this->type=$request->getParameter('type');

        //Table Lock code is Open
        if ($request->getParameter('id')) {
            $encrypt = new EncryptionHandler();
            if (!strlen($request->getParameter('lock'))) {
                $this->lockMode = 0;
            } else {
                $this->lockMode = $request->getParameter('lock');
            }
            $VTID = $encrypt->decrypt($request->getParameter('id'));
            if (isset($this->lockMode)) {
                if ($this->lockMode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock('hs_hr_evl_evaluation_employee', array($VTID), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->lockMode = 1;
                    } else {
                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->lockMode = 0;
                    }
                } else if ($this->lockMode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock('hs_hr_evl_evaluation_employee', array($VTID), 1);
                    $this->lockMode = 0;
                }
            }

            //Table lock code is closed


            $EvalEmployee = $EvaluationService->readEvalEmployee($encrypt->decrypt($request->getParameter('id')));
            if (!$EvalEmployee) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
                $this->redirect('evaluation/DefineEmployeeEvaluation');
            }
//            if ($EvalEmployee->ev_complete_flg == 2) {
//                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Evaluation Completed.', $args, 'messages')));
//                $this->redirect('evaluation/EmployeeEvaluation?id=' . $encrypt->encrypt($VTID) . '&lock=0');
//            }
        } else {

            $this->lockMode = 1;
        }
        $this->EvalEmployee = $EvalEmployee;
        $this->EvaluationList = $EvaluationService->getEvaluationList();
        
        if ($request->isMethod('post')) {  //die(print_r($_POST));
            
             $conn = Doctrine_Manager::getInstance()->connection();
             $conn->beginTransaction();
          try { 
            
            if(strlen($request->getParameter('txtid'))){
                $EvalEmployee=$EvaluationService->readEvalEmployee($request->getParameter('txtid'));
            }else{
                 $EvalEmployee=new EvaluationEmployee();
            }
            
            if($request->getParameter('type') == '2'){
                $EvalEmployee->setEv_employee_comment(trim($request->getParameter('empcomment')));
                if (strlen($request->getParameter('chkEmpcomplete'))) {
                $EvalEmployee->setEv_employee_agree("1");
                }
            }else{
                
            
            
             //$conn = Doctrine_Manager::getInstance()->connection();
             //$conn->beginTransaction();
            
            if (strlen($request->getParameter('cmbCompEval'))) {
                $EvalEmployee->setEval_id(trim($request->getParameter('cmbCompEval')));
            } else {
                $EvalEmployee->setEval_id(null);
            }
            if (strlen($request->getParameter('txtEmpId'))) {
                $EvalEmployee->setEmp_number(trim($request->getParameter('txtEmpId')));
            } else {
                $EvalEmployee->setEmp_number(null);
            }
            if (strlen($request->getParameter('chkFTActive'))) {
                $EvalEmployee->setEv_fn_rv_active_flg(1);
            } else {
                $EvalEmployee->setEv_fn_rv_active_flg(0);
            }            
            if (strlen($request->getParameter('chkMSActive'))) {
                $EvalEmployee->setEv_ms_rv_active_flg(1);
            } else {
                $EvalEmployee->setEv_ms_rv_active_flg(0);
            }  
            if (strlen($request->getParameter('chk360Active'))) {
                $EvalEmployee->setEv_ts_rv_active_flg(1);
            } else {
                $EvalEmployee->setEv_ts_rv_active_flg(0);
            }              
            if (strlen($request->getParameter('txtFNPersentage'))) {
                $EvalEmployee->setEv_fn_rv_percentage(trim($request->getParameter('txtFNPersentage')));
            } else {
                $EvalEmployee->setEv_fn_rv_percentage(null);
            }      
            if (strlen($request->getParameter('txtMSPersentage'))) {
                $EvalEmployee->setEv_ms_rv_percentage(trim($request->getParameter('txtMSPersentage')));
            } else {
                $EvalEmployee->setEv_ms_rv_percentage(null);
            }
            if (strlen($request->getParameter('txtTSPersentage'))) {
                $EvalEmployee->setEv_ts_rv_percentage(trim($request->getParameter('txtTSPersentage')));
            } else {
                $EvalEmployee->setEv_ts_rv_percentage(null);
            }       
            
                        if (strlen($request->getParameter('supcomment'))) {
                $EvalEmployee->setEv_appraiser_comment(trim($request->getParameter('supcomment')));
            } else {
                $EvalEmployee->setEv_appraiser_comment(null);
            }  
            if (strlen($request->getParameter('modcomment'))) {
                $EvalEmployee->setEv_moderator_comment(trim($request->getParameter('modcomment')));
            } else {
                $EvalEmployee->setEv_moderator_comment(null);
            } 
            
            
            
            
            if (strlen($request->getParameter('chkSupcomplete'))) {
                $EvalEmployee->setEv_complete_flg(1);
            }
            
            if (strlen($request->getParameter('chkModcomplete'))) {
                $EvalEmployee->setEv_complete_flg(2);
            }
            
 
            //fn
            if (strlen($request->getParameter('txtev_fn_sup_mid_ach_avg'))) {
                $EvalEmployee->setEv_fn_sup_mid_ach_avg(trim($request->getParameter('txtev_fn_sup_mid_ach_avg')));
            } else {
                $EvalEmployee->setEv_fn_sup_mid_ach_avg(null);
            }
            if (strlen($request->getParameter('txtev_fn_sup_mid_ach_tot'))) {
                $EvalEmployee->setEv_fn_sup_mid_ach_tot(trim($request->getParameter('txtev_fn_sup_mid_ach_tot')));
            } else {
                $EvalEmployee->setEv_fn_sup_mid_ach_tot(null);
            }  
            if (strlen($request->getParameter('txtev_fn_sup_mid_mark_avg'))) {
                $EvalEmployee->setEv_fn_sup_mid_mark_avg(trim($request->getParameter('txtev_fn_sup_mid_mark_avg')));
            } else {
                $EvalEmployee->setEv_fn_sup_mid_mark_avg(null);
            }
            if (strlen($request->getParameter('txtev_fn_sup_mid_mark_tot'))) {
                $EvalEmployee->setEv_fn_sup_mid_mark_tot(trim($request->getParameter('txtev_fn_sup_mid_mark_tot')));
            } else {
                $EvalEmployee->setEv_fn_sup_mid_mark_tot(null);
            }
            
            if (strlen($request->getParameter('txtev_fn_sup_end_ach_avg'))) {
                $EvalEmployee->setEv_fn_sup_end_ach_avg(trim($request->getParameter('txtev_fn_sup_end_ach_avg')));
            } else {
                $EvalEmployee->setEv_fn_sup_end_ach_avg(null);
            }
            if (strlen($request->getParameter('ev_fn_sup_end_ach_tot'))) {
                $EvalEmployee->setEv_fn_sup_end_ach_tot(trim($request->getParameter('ev_fn_sup_end_ach_tot')));
            } else {
                $EvalEmployee->setEv_fn_sup_end_ach_tot(null);
            }  
            if (strlen($request->getParameter('txtev_fn_sup_end_mark_avg'))) {
                $EvalEmployee->setEv_fn_sup_end_mark_avg(trim($request->getParameter('txtev_fn_sup_end_mark_avg')));
            } else {
                $EvalEmployee->setEv_fn_sup_end_mark_avg(null);
            }
            if (strlen($request->getParameter('ev_fn_sup_end_mark_tot'))) {
                $EvalEmployee->setEv_fn_sup_end_mark_tot(trim($request->getParameter('ev_fn_sup_end_mark_tot')));
            } else {
                $EvalEmployee->setEv_fn_sup_end_mark_tot(null);
            }            

            if (strlen($request->getParameter('txtev_fn_mod_end_ach_avg'))) {
                $EvalEmployee->setEv_fn_mod_end_ach_avg(trim($request->getParameter('txtev_fn_mod_end_ach_avg')));
            } else {
                $EvalEmployee->setEv_fn_mod_end_ach_avg(null);
            }
            if (strlen($request->getParameter('txtev_fn_sup_end_mark_avg'))) {
                $EvalEmployee->setEv_fn_mod_end_ach_tot(trim($request->getParameter('txtev_fn_sup_end_mark_avg')));
            } else {
                $EvalEmployee->setEv_fn_mod_end_ach_tot(null);
            }  
            if (strlen($request->getParameter('txtev_fn_mod_end_mark_avg'))) {
                $EvalEmployee->setEv_fn_mod_end_mark_avg(trim($request->getParameter('txtev_fn_mod_end_mark_avg')));
            } else {
                $EvalEmployee->setEv_fn_mod_end_mark_avg(null);
            }
            if (strlen($request->getParameter('ev_fn_mod_end_mark_tot'))) {
                $EvalEmployee->setEv_fn_mod_end_mark_tot(trim($request->getParameter('ev_fn_mod_end_mark_tot')));
            } else {
                $EvalEmployee->setEv_fn_mod_end_mark_tot(null);
            }              

            //ms
            if (strlen($request->getParameter('txtev_ms_sup_mid_ach_avg'))) {
                $EvalEmployee->setEv_ms_sup_mid_ach_avg(trim($request->getParameter('txtev_ms_sup_mid_ach_avg')));
            } else {
                $EvalEmployee->setEv_ms_sup_mid_ach_avg(null);
            }
            if (strlen($request->getParameter('txtev_ms_sup_mid_ach_tot'))) {
                $EvalEmployee->setEv_ms_sup_mid_ach_tot(trim($request->getParameter('txtev_ms_sup_mid_ach_tot')));
            } else {
                $EvalEmployee->setEv_ms_sup_mid_ach_tot(null);
            }  
            if (strlen($request->getParameter('txtev_ms_sup_mid_mark_avg'))) {
                $EvalEmployee->setEv_ms_sup_mid_mark_avg(trim($request->getParameter('txtev_ms_sup_mid_mark_avg')));
            } else {
                $EvalEmployee->setEv_ms_sup_mid_mark_avg(null);
            }
            if (strlen($request->getParameter('txtev_ms_sup_mid_mark_tot'))) {
                $EvalEmployee->setEv_ms_sup_mid_mark_tot(trim($request->getParameter('txtev_ms_sup_mid_mark_tot')));
            } else {
                $EvalEmployee->setEv_ms_sup_mid_mark_tot(null);
            }
            
            if (strlen($request->getParameter('txtev_ms_sup_end_ach_avg'))) {
                $EvalEmployee->setEv_ms_sup_end_ach_avg(trim($request->getParameter('txtev_ms_sup_end_ach_avg')));
            } else {
                $EvalEmployee->setEv_ms_sup_end_ach_avg(null);
            }
            if (strlen($request->getParameter('txtev_ms_sup_end_ach_tot'))) {
                $EvalEmployee->setEv_ms_sup_end_ach_tot(trim($request->getParameter('txtev_ms_sup_end_ach_tot')));
            } else {
                $EvalEmployee->setEv_ms_sup_end_ach_tot(null);
            }  
            if (strlen($request->getParameter('txtev_ms_sup_end_mark_avg'))) {
                $EvalEmployee->setEv_ms_sup_end_mark_avg(trim($request->getParameter('txtev_ms_sup_end_mark_avg')));
            } else {
                $EvalEmployee->setEv_ms_sup_end_mark_avg(null);
            }
            if (strlen($request->getParameter('txtev_ms_sup_end_mark_tot'))) {
                $EvalEmployee->setEv_ms_sup_end_mark_tot(trim($request->getParameter('txtev_ms_sup_end_mark_tot')));
            } else {
                $EvalEmployee->setEv_ms_sup_end_mark_tot(null);
            }            

            if (strlen($request->getParameter('txtev_ms_mod_end_ach_avg'))) {
                $EvalEmployee->setEv_ms_mod_end_ach_avg(trim($request->getParameter('txtev_ms_mod_end_ach_avg')));
            } else {
                $EvalEmployee->setEv_ms_mod_end_ach_avg(null);
            }
            if (strlen($request->getParameter('txtev_ms_sup_end_mark_avg'))) {
                $EvalEmployee->setEv_ms_mod_end_ach_tot(trim($request->getParameter('txtev_ms_sup_end_mark_avg')));
            } else {
                $EvalEmployee->setEv_ms_mod_end_ach_tot(null);
            }  
            if (strlen($request->getParameter('txtev_ms_mod_end_mark_avg'))) {
                $EvalEmployee->setEv_ms_mod_end_mark_avg(trim($request->getParameter('txtev_ms_mod_end_mark_avg')));
            } else {
                $EvalEmployee->setEv_ms_mod_end_mark_avg(null);
            }
            if (strlen($request->getParameter('txtev_ms_mod_end_mark_tot'))) {
                $EvalEmployee->setEv_ms_mod_end_mark_tot(trim($request->getParameter('txtev_ms_mod_end_mark_tot')));
            } else {
                $EvalEmployee->setEv_ms_mod_end_mark_tot(null);
            }              


            //ts
//            if (strlen($request->getParameter('txtev_ts_sup_mid_ach_avg'))) {
//                $EvalEmployee->setEv_ts_sup_mid_ach_avg(trim($request->getParameter('txtev_ts_sup_mid_ach_avg')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_mid_ach_avg(null);
//            }
//            if (strlen($request->getParameter('txtev_ts_sup_mid_ach_tot'))) {
//                $EvalEmployee->setEv_ts_sup_mid_ach_tot(trim($request->getParameter('txtev_ts_sup_mid_ach_tot')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_mid_ach_tot(null);
//            }  
//            if (strlen($request->getParameter('txtev_ts_sup_mid_mark_avg'))) {
//                $EvalEmployee->setEv_ts_sup_mid_mark_avg(trim($request->getParameter('txtev_ts_sup_mid_mark_avg')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_mid_mark_avg(null);
//            }
//            if (strlen($request->getParameter('txtev_ts_sup_mid_mark_tot'))) {
//                $EvalEmployee->setEv_ts_sup_mid_mark_tot(trim($request->getParameter('txtev_ts_sup_mid_mark_tot')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_mid_mark_tot(null);
//            }
//            
//            if (strlen($request->getParameter('txtev_ts_sup_end_ach_avg'))) {
//                $EvalEmployee->setEv_ts_sup_end_ach_avg(trim($request->getParameter('txtev_ts_sup_end_ach_avg')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_end_ach_avg(null);
//            }
//            if (strlen($request->getParameter('txtev_ts_sup_end_ach_tot'))) {
//                $EvalEmployee->setEv_ts_sup_end_ach_tot(trim($request->getParameter('txtev_ts_sup_end_ach_tot')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_end_ach_tot(null);
//            }  
//            if (strlen($request->getParameter('txtev_ts_sup_end_mark_avg'))) {
//                $EvalEmployee->setEv_ts_sup_end_mark_avg(trim($request->getParameter('txtev_ts_sup_end_mark_avg')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_end_mark_avg(null);
//            }
//            if (strlen($request->getParameter('txtev_ts_sup_end_mark_tot'))) {
//                $EvalEmployee->setEv_ts_sup_end_mark_tot(trim($request->getParameter('txtev_ts_sup_end_mark_tot')));
//            } else {
//                $EvalEmployee->setEv_ts_sup_end_mark_tot(null);
//            }            
            
            // clients details 

            if (strlen($request->getParameter('ev_avg_client_1'))) {
                $EvalEmployee->setEv_avg_client_1(trim($request->getParameter('ev_avg_client_1')));
            } else {
                $EvalEmployee->setEv_avg_client_1(null);
            } 
            if (strlen($request->getParameter('ev_avg_client_2'))) {
                $EvalEmployee->setEv_avg_client_2(trim($request->getParameter('ev_avg_client_2')));
            } else {
                $EvalEmployee->setEv_avg_client_2(null);
            } 
            if (strlen($request->getParameter('ev_avg_client_3'))) {
                $EvalEmployee->setEv_avg_client_3(trim($request->getParameter('ev_avg_client_3')));
            } else {
                $EvalEmployee->setEv_avg_client_3(null);
            } 
            if (strlen($request->getParameter('ev_tot_client_1'))) {
                $EvalEmployee->setEv_tot_client_1(trim($request->getParameter('ev_tot_client_1')));
            } else {
                $EvalEmployee->setEv_tot_client_1(null);
            } 
            if (strlen($request->getParameter('ev_tot_client_2'))) {
                $EvalEmployee->setEv_tot_client_2(trim($request->getParameter('ev_tot_client_2')));
            } else {
                $EvalEmployee->setEv_tot_client_2(null);
            } 
            if (strlen($request->getParameter('ev_tot_client_3'))) {
                $EvalEmployee->setEv_tot_client_3(trim($request->getParameter('ev_tot_client_3')));
            } else {
                $EvalEmployee->setEv_tot_client_3(null);
            } 
            if (strlen($request->getParameter('emp_ts_marks_client_avg_summary'))) {
                $EvalEmployee->setEmp_ts_marks_client_avg_summary(trim($request->getParameter('emp_ts_marks_client_avg_summary')));
            } else {
                $EvalEmployee->setEmp_ts_marks_client_avg_summary(null);
            } 
            if (strlen($request->getParameter('emp_ts_marks_client_tot_summary'))) {
                $EvalEmployee->setEmp_ts_marks_client_tot_summary(trim($request->getParameter('emp_ts_marks_client_tot_summary')));
            } else {
                $EvalEmployee->setEmp_ts_marks_client_tot_summary(null);
            } 
            

            if (strlen($request->getParameter('txtev_ts_mod_end_ach_avg'))) {
                $EvalEmployee->setEv_ts_mod_end_ach_avg(trim($request->getParameter('txtev_ts_mod_end_ach_avg')));
            } else {
                $EvalEmployee->setEv_ts_mod_end_ach_avg(null);
            }
            if (strlen($request->getParameter('txtev_ts_mod_end_mark_tot'))) {
                $EvalEmployee->setEv_ts_mod_end_ach_tot(trim($request->getParameter('txtev_ts_mod_end_mark_tot')));
            } else {
                $EvalEmployee->setEv_ts_mod_end_ach_tot(null);
            }  
            if (strlen($request->getParameter('txtev_ts_mod_end_mark_avg'))) {
                $EvalEmployee->setEv_ts_mod_end_mark_avg(trim($request->getParameter('txtev_ts_mod_end_mark_avg')));
            } else {
                $EvalEmployee->setEv_ts_mod_end_mark_avg(null);
            }
            if (strlen($request->getParameter('txtev_ts_mod_end_mark_tot'))) {
                $EvalEmployee->setEv_ts_mod_end_mark_tot(trim($request->getParameter('txtev_ts_mod_end_mark_tot')));
            } else {
                $EvalEmployee->setEv_ts_mod_end_mark_tot(null);
            }              

            if (strlen($request->getParameter('txtSEMark'))) {
                $EvalEmployee->setEv_ts_sup_mark_tot(trim($request->getParameter('txtSEMark')));
            } else {
                $EvalEmployee->setEv_ts_sup_mark_tot(null);
            }
            if (strlen($request->getParameter('txtMEMark'))) {
                $EvalEmployee->setEv_ts_mod_mark_tot(trim($request->getParameter('txtMEMark')));
            } else {
                $EvalEmployee->setEv_ts_mod_mark_tot(null);
            }
            //die(print_r($EvalEmployee));
            $EvalEmployee->save();
            
            //FT Save
            if($_POST['txtftid']){ 
            foreach($_POST['txtftid'] as $row){ 
                if($_POST['txtfnid_'.$row]!= null){ 
                $FuntionTask=$EvaluationService->readFuntionTask($_POST['txtfnid_'.$row]);
                
                if($_POST['txtftsupmidachive_'.$row]!= null){ 
                $FuntionTask->setFt_sup_mid_achive($_POST['txtftsupmidachive_'.$row]);
                }
                if($_POST['txtftsupendachive_'.$row]!= null){ 
                $FuntionTask->setFt_sup_end_achive($_POST['txtftsupendachive_'.$row]);
                }         
                if($_POST['txtftsupmidmarks_'.$row]!= null){ 
                $FuntionTask->setFt_sup_mid_marks($_POST['txtftsupmidmarks_'.$row]);
                }
                if($_POST['txtftsupendmarks_'.$row]!= null){ 
                $FuntionTask->setFt_sup_end_marks($_POST['txtftsupendmarks_'.$row]);
                }
                if($_POST['txtftmodendachive_'.$row]!= null){ 
                $FuntionTask->setFt_mod_end_achive($_POST['txtftmodendachive_'.$row]);
                }
                if($_POST['txtftmodendmarks_'.$row]!= null){ 
                $FuntionTask->setFt_mod_end_marks($_POST['txtftmodendmarks_'.$row]);
                }                
                $FuntionTask->save();    
                //die(print_r($FuntionTask));
                }
                
            }
            }
            
            //MS Save
            if($_POST['txtmsid']){
            foreach($_POST['txtmsid'] as $row){
                if($_POST['txtmsid_'.$row]!= null){
                $EvaluationSkillEmployee=$EvaluationService->readEvaluationSkillEmployee($_POST['txtmsid_'.$row],$_POST['cmbCompEval'],$_POST['txtEmpId']);
                
//                if(!$EvaluationSkillEmployee){
//                    $EvaluationSkillEmployee = new EvaluationSkillEmployee();
//                     $SkillMax=$EvaluationService->getLastEvaluationSkillEmployeeID();
//                     
//                     $EvaluationSkillEmployee->setEmp_skill_id($SkillMax[0]['MAX']+1);
//                }

                if($_POST['txtemp_skillsupmidachive_'.$row]!= null){ 
                $EvaluationSkillEmployee->setEmp_skill_sup_mid_achive($_POST['txtemp_skillsupmidachive_'.$row]);
                }
                if($_POST['txtemp_skillsupendachive_'.$row]!= null){ 
                $EvaluationSkillEmployee->setEmp_skill_sup_end_achive($_POST['txtemp_skillsupendachive_'.$row]);
                }         
                if($_POST['txtemp_skillsupmidmarks_'.$row]!= null){ 
                $EvaluationSkillEmployee->setEmp_skill_sup_mid_marks($_POST['txtemp_skillsupmidmarks_'.$row]);
                }
                if($_POST['txtemp_skillsupendmarks_'.$row]!= null){ 
                $EvaluationSkillEmployee->setEmp_skill_sup_end_marks($_POST['txtemp_skillsupendmarks_'.$row]);
                }
                if($_POST['txtemp_skillmodendachive_'.$row]!= null){ 
                $EvaluationSkillEmployee->setEmp_skill_mod_end_achive($_POST['txtemp_skillmodendachive_'.$row]);
                }
                if($_POST['txtemp_skillmodendmarks_'.$row]!= null){ 
                $EvaluationSkillEmployee->setEmp_skill_mod_end_marks($_POST['txtemp_skillmodendmarks_'.$row]);
                }                                
                $EvaluationSkillEmployee->save();    
//                die(print_r($EvaluationSkillEmployee));
                }
                
            }
            }
            //360 Save
            if($_POST['txttsid']){
            foreach($_POST['txttsid'] as $row){
                if($_POST['txttsid_'.$row]!= null){
                $EvaluationTSEmployee=$EvaluationService->readEvaluationTSEmployee($_POST['txttsid_'.$row],$_POST['cmbCompEval'],$_POST['txtEmpId']);
                
//                if(!$EvaluationTSEmployee){
//                    $EvaluationTSEmployee = new EvaluationTSEmployee();
//                     $TSMax=$EvaluationService->getLastEvaluationTSEmployeeID();
//                     
//                     $EvaluationTSEmployee->setEmp_ts_id($TSMax[0]['MAX']+1);
//                }

//                if($_POST['txtemp_tssupmidachive_'.$row]!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_sup_mid_achive($_POST['txtemp_tssupmidachive_'.$row]);
//                }
//                if($_POST['txtemp_tssupendachive_'.$row]!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_sup_end_achive($_POST['txtemp_tssupendachive_'.$row]);
//                }         
//                if($_POST['txtemp_tssupmidmarks_'.$row]!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_sup_mid_marks($_POST['txtemp_tssupmidmarks_'.$row]);
//                }
//                if($_POST['txtemp_tssupendmarks_'.$row]!= null){ 
//                $EvaluationTSEmployee->setEmp_ts_sup_end_marks($_POST['txtemp_tssupendmarks_'.$row]);
//                }
                
                if($_POST['txtemp_ts_marks_client_1_'.$row]!= null){ 
                $EvaluationTSEmployee->setEmp_ts_marks_client_1($_POST['txtemp_ts_marks_client_1_'.$row]);
                }
                if($_POST['emp_ts_marks_client_2_'.$row]!= null){ 
                $EvaluationTSEmployee->setEmp_ts_marks_client_2($_POST['emp_ts_marks_client_2_'.$row]);
                }
                if($_POST['emp_ts_marks_client_3_'.$row]!= null){ 
                $EvaluationTSEmployee->setEmp_ts_marks_client_3($_POST['emp_ts_marks_client_3_'.$row]);
                }
                
                if($_POST['txtemp_tsmodendachive_'.$row]!= null){ 
                $EvaluationTSEmployee->setEmp_ts_mod_end_achive($_POST['txtemp_tsmodendachive_'.$row]);
                }
                if($_POST['txtemp_tsmodendmarks_'.$row]!= null){ 
                $EvaluationTSEmployee->setEmp_ts_mod_end_marks($_POST['txtemp_tsmodendmarks_'.$row]);
                }   
                if($_POST['txtemp_tsmodendmarks_'.$row]!= null){ 
                $EvaluationTSEmployee->setEmp_ts_marks_client_summary($_POST['txtemp_tssupendmarks_'.$row]);
                }  

                $EvaluationTSEmployee->save();    
                //die(print_r($EvaluationTSEmployee));
                }
                
            }
            

            }
          }

            //die(print_r($EvalEmployee));
                $EvalEmployee->save();
                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $conn->rollback();
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());

                if ($request->getParameter('txtid') != null) {
                    $this->redirect('evaluation/EmployeeEvaluation?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0');
                } else {
                    $this->redirect('evaluation/DefineEmployeeEvaluation');
                }
            } catch (Exception $e) {
                $conn->rollback();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());

                if ($request->getParameter('txtid') != null) {
                    $this->redirect('evaluation/EmployeeEvaluation?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0');
                } else {
                    $this->redirect('evaluation/DefineEmployeeEvaluation');
                }
            }
            if ($request->getParameter('txtid') != null) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
                $this->redirect('evaluation/EmployeeEvaluation?id=' . $encrypt->encrypt($request->getParameter('txtid')) . '&lock=0&type='.$this->type);
            } else {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Saved", $args, 'messages')));
                $this->redirect('evaluation/DefineEmployeeEvaluation');
            }
        }
    }
    
    
        public function executeAjaxGetFTDataEval(sfWebRequest $request) {

        $comeval = $request->getParameter('comeval');
        $eno = $request->getParameter('eno');
        
        $EvaluationService = new EvaluationService();
        $FTData = $EvaluationService->getGetFTDataEval($comeval,$eno);
        //die(print_r($FTData));
        foreach ($FTData as $row) {
            
            $array[] = $row;
        }
        
        echo json_encode(array($FTData));
        die;
    }
    
        public function executeAjaxGetSMDataEval(sfWebRequest $request) {

        $comeval = $request->getParameter('comeval');
        $eno = $request->getParameter('eno');
        $ev_id = $request->getParameter('ev_id');

        $EvaluationService = new EvaluationService();
        $FTData = $EvaluationService->getGetSMDataEval($comeval,$eno,$ev_id);
        //die(print_r($FTData));
        foreach ($FTData as $row) {
            
            $array[] = $row;
        }
        
        echo json_encode(array($FTData));
        die;
    }
    
        public function executeAjaxGet360DataEval(sfWebRequest $request) {

        $comeval = $request->getParameter('comeval');
        $eno = $request->getParameter('eno');
        $ev_id = $request->getParameter('ev_id');
        
        $EvaluationService = new EvaluationService();
        $FTData = $EvaluationService->getGet360DataEval($comeval,$eno,$ev_id);
        //die(print_r($FTData));
        foreach ($FTData as $row) {
            
            $array[] = $row;
        }
        
        echo json_encode(array($FTData));
        die;
    }    
    
    public function executeAjaxModeratorAppeal(sfWebRequest $request) {


        $eno = $request->getParameter('ev_id');
        
        $EvaluationService = new EvaluationService();
        $EvaluationEmployee = $EvaluationService->readEvalEmployee($eno);
        $EvaluationEmployee->setEv_complete_flg(1);
        $EvaluationEmployee->save();
        
        echo json_encode("successfully Appeal to Moderator");
        die;
    }
    
    public function executeUpdateClientEvaluation(sfWebRequest $request) {
        
        //$_SESSION['user'] = "USR001";
        die("admin can't proceed");
    }

     public function sendEmailClientEvaluation($id,$email) {
         die(print_r($id."|".$email));
     }
     
   public function executeAjaxFinalRate(sfWebRequest $request) {


        $eno = $request->getParameter('ev_id');
        $marks = $request->getParameter('marks');
        $minm = 0;
        
        $EvaluationService = new EvaluationService();
        $EvaluationEmployee = $EvaluationService->readEvalEmployee($eno);
        $Evaluation =  $EvaluationService->readEvaluationCompanyInfo($EvaluationEmployee->eval_id);
        $EvaluationRate =  $EvaluationService->readRateDetails($Evaluation->rate_id);
        foreach ($EvaluationRate as $row){
            if($minm <= $marks){
                $Grade = $row[rdt_grade];
                $GradeDesc = $row[rdt_description];
                $minm = $row[rdt_mark];
            }
        }


        echo json_encode(array($Grade));
        die;
    }
    
     public function executeViewEmployeeEvaluation(sfWebRequest $request) {
         if($_SESSION['empNumber'] == null){
             $this->setMessage('NOTICE', array('Please Contact the administrator'));
             $this->redirect('default/error');
         }
        try {
            $this->Culture = $this->getUser()->getCulture();
            $EvaluationService = new EvaluationService();

            $this->sorter = new ListSorter('ViewEmployeeEvaluation', 'evaluation', $this->getUser(), array('b.ev_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('evaluation/ViewEmployeeEvaluation');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.ev_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            
            $this->emp = ($request->getParameter('txtEmpId') == null) ? $request->getParameter('emp') : $_POST['txtEmpId'];
            $this->type = ($request->getParameter('txtType') == null) ? $request->getParameter('type') : $_POST['txtType'];
            
            $res = $EvaluationService->EmployeeEvaluationList($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'), $this->emp, $this->type);
            $this->EvaluationEmployeeList = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
         
    }
    
    public function executeAddComment(sfWebRequest $request) {

        $Type = $request->getParameter('type');
        $id= $request->getParameter('id');
        $this->Type = $Type;
        $this->id = $id;
        
        $EvaluationService = new EvaluationService();
        $EvaluationComment = $EvaluationService->readEvaluationComment($Type,$id);
        $this->EvaluationComment = $EvaluationComment;
        
        try {
        if ($request->isMethod('post')) {  
            $e = getdate();
            $today = date("Y-m-d", $e[0]);
            $empnumber = $_SESSION['empNumber'];
            if($Type == "1"){
                $EvaluationFunctionsTaskComments = new EvaluationFunctionsTaskComments();
                $EvaluationFunctionsTaskComments->setFt_id($id);
                $EvaluationFunctionsTaskComments->setFtc_comment($_POST['comment']);
                $EvaluationFunctionsTaskComments->setFtc_date($today);
                $EvaluationFunctionsTaskComments->setEmp_number($empnumber);
                $EvaluationFunctionsTaskComments->save();
            }else if($Type == "2"){ 
                $EvaluationSkillEmployeeComments = new EvaluationSkillEmployeeComments();
                $EvaluationSkillEmployeeComments->setEmp_skill_id($id);
                $EvaluationSkillEmployeeComments->setEsc_comment($_POST['comment']);
                $EvaluationSkillEmployeeComments->setEsc_date($today);
                $EvaluationSkillEmployeeComments->setEmp_number($empnumber);
                $EvaluationSkillEmployeeComments->save();


            }else if($Type == "3"){
                
                $EvaluationTSEmployeeComments = new EvaluationTSEmployeeComments();
                $EvaluationTSEmployeeComments->setEmp_ts_id($id);
                $EvaluationTSEmployeeComments->setEtc_comment($_POST['comment']);
                $EvaluationTSEmployeeComments->setEtc_date($today);
                $EvaluationTSEmployeeComments->setEmp_number($empnumber);
                $EvaluationTSEmployeeComments->save();
            }
           $this->redirect('evaluation/AddComment?type='.$Type.'&id='.$id);
        }
        } catch (Doctrine_Connection_Exception $e) {
                
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/AddComment?type='.$Type.'&id='.$id);
            } catch (Exception $e) {
                $conn->rollback();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('evaluation/AddComment?type='.$Type.'&id='.$id);
            }
    }
    
}
