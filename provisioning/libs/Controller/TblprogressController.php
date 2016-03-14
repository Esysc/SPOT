<?php

/** @package    SPOT::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Tblprogress.php");
require_once('includes/share.php');

/**
 * TblprogressController is the controller class for the Tblprogress object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */

/**
 * Get the property name of an object
 */
class Meta {

    public function __construct($obj) {
        $a = get_object_vars($obj);
        foreach ($a as $key => $value) {
            $this->$key = $key;   // <-- this can be enhanced to store an
            //     object with a whole bunch of meta-data, 
            //     but you get the idea.
        }
    }

}


    



class TblprogressController extends AppBaseController {

    /**
     * Override here for any controller-specific functionality
     *
     * @inheritdocs
     */
    protected function Init() {
        parent::Init();

        // TODO: add controller-wide bootstrap code
        // TODO: if authentiation is required for this entire controller, for example:
        // $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
    }
    
    /**
     * Displays a list view of Tblprogress objects
     */
    public function ListView() {
        $this->Render();
    }
    
    public function CompletedView () {
        $this->Render('TblcompletedListView.tpl');
    }


    /**
     * API Method queries for Tblprogress records and render as JSON
     */
    public function Query() {
        
        try {
            $criteria = new TblprogressCriteria();
            $criteria->SetOrder('Id', true);
            // TODO: this will limit results based on all properties included in the filter list 
            $filter = RequestUtil::Get('filter');
            if ($filter)
                $criteria->AddFilter(
                        new CriteriaFilter('Id,User,Data,Salesorder,Creationdate'
                        , '%' . $filter . '%')
                );

            // TODO: this is generic query filtering based only on criteria properties
            foreach (array_keys($_REQUEST) as $prop) {
                $prop_normal = ucfirst($prop);
                $prop_equals = $prop_normal . '_Equals';

                if (property_exists($criteria, $prop_normal)) {
                    $criteria->$prop_normal = RequestUtil::Get($prop);
                } elseif (property_exists($criteria, $prop_equals)) {
                    // this is a convenience so that the _Equals suffix is not needed
                    $criteria->$prop_equals = RequestUtil::Get($prop);
                }
            }

            $output = new stdClass();

            // if a sort order was specified then specify in the criteria
            $output->orderBy = RequestUtil::Get('orderBy');
            $output->orderDesc = RequestUtil::Get('orderDesc') != '';
            if ($output->orderBy)
                $criteria->SetOrder($output->orderBy, $output->orderDesc);

            $page = RequestUtil::Get('page');

            if ($page != '') {
                // if page is specified, use this instead (at the expense of one extra count query)
                $pagesize = $this->GetDefaultPageSize();

                $tblprogresses = $this->Phreezer->Query('Tblprogress', $criteria)->GetDataPage($page, $pagesize);

                $output->rows = $tblprogresses->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = $tblprogresses->TotalResults;
                $output->totalPages = $tblprogresses->TotalPages;
                $output->pageSize = $tblprogresses->PageSize;
                $output->currentPage = $tblprogresses->CurrentPage;
            } else {
                // return all results
                $tblprogresses = $this->Phreezer->Query('Tblprogress', $criteria);
                $output->rows = $tblprogresses->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = count($output->rows);
                $output->totalPages = 1;
                $output->pageSize = $output->totalResults;
                $output->currentPage = 1;
            }

         /*   foreach ($output->rows as $key => $props) {

                
                $data = print_r_V2(json_decode($output->rows[$key]->data, true));
               
                  
                   
                
           
               
                    $output->rows[$key]->data = $data;
                
            } */


            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method retrieves a single Tblprogress record and render as JSON
     */
    public function Read() {
        try {
            $pk = $this->GetRouter()->GetUrlParam('id');
            $tblprogress = $this->Phreezer->Get('Tblprogress', $pk);
            $this->RenderJSON($tblprogress, $this->JSONPCallback(), true, $this->SimpleObjectParams());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }
   

    /**
     * API Method inserts a new Tblprogress record and render response as JSON
     */
    public function Create() {
        try {
               
            $json = json_decode(RequestUtil::GetBody());
        
           if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }
 
            $tblprogress = new Tblprogress($this->Phreezer);

            // TODO: any fields that should not be inserted by the user should be commented out
            // this is an auto-increment.  uncomment if updating is allowed
            // $tblprogress->Id = $this->SafeGetVal($json, 'id');

            $tblprogress->User = $this->SafeGetVal($json, 'user');
            $tblprogress->Data = $this->SafeGetVal($json, 'data');
            $tblprogress->Salesorder = $this->SafeGetVal($json, 'salesorder');
            $tblprogress->Creationdate = $this->SafeGetVal($json, 'creationdate');
            
            $tblprogress->Validate();
            $errors = $tblprogress->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                $tblprogress->Save();
                $this->RenderJSON($tblprogress, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method updates an existing Tblprogress record and render response as JSON
     */
    public function Update() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $pk = $this->GetRouter()->GetUrlParam('id');
            $tblprogress = $this->Phreezer->Get('Tblprogress', $pk);

            // TODO: any fields that should not be updated by the user should be commented out
            // this is a primary key.  uncomment if updating is allowed
            // $tblprogress->Id = $this->SafeGetVal($json, 'id', $tblprogress->Id);

            $tblprogress->User = $this->SafeGetVal($json, 'user', $tblprogress->User);
            $tblprogress->Data = $this->SafeGetVal($json, 'data', $tblprogress->Data);
            $tblprogress->Salesorder = $this->SafeGetVal($json, 'salesorder', $tblprogress->Salesorder);
            $tblprogress->Creationdate = $this->SafeGetVal($json, 'creationdate', $tblprogress->Creationdate);

            $tblprogress->Validate();
            $errors = $tblprogress->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                $tblprogress->Save();
                $this->RenderJSON($tblprogress, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {


            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method deletes an existing Tblprogress record and render response as JSON
     */
    public function Delete() {
        try {

            // TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

            $pk = $this->GetRouter()->GetUrlParam('id');
            $tblprogress = $this->Phreezer->Get('Tblprogress', $pk);

            $tblprogress->Delete();

            $output = new stdClass();

            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

}

?>
