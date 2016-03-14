<?php

/** @package    SPOT::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Customconfig.php");

/**
 * CustomconfigController is the controller class for the Customconfig object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class CustomconfigController extends AppBaseController {

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
     * Displays a list view of Customconfig objects
     */
    public function ListView() {
        $this->Render();
    }

    public function FormView() {
        $this->Render('CustomconfigFormView.tpl');
    }

    /**
     * API Method queries for Customconfig records and render as JSON
     */
    public function Query() {
        try {
            $criteria = new CustomconfigCriteria();
             $criteria->SetOrder('ConfigId',true);

            // TODO: this will limit results based on all properties included in the filter list 
            $filter = RequestUtil::Get('filter');
            if ($filter)
                $criteria->AddFilter(
                        new CriteriaFilter('ConfigId,Salesorder,ConfigTarget,ConfigContent,TimeStamp'
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

                $customconfigs = $this->Phreezer->Query('CustomconfigReporter', $criteria)->GetDataPage($page, $pagesize);
                $output->rows = $customconfigs->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = $customconfigs->TotalResults;
                $output->totalPages = $customconfigs->TotalPages;
                $output->pageSize = $customconfigs->PageSize;
                $output->currentPage = $customconfigs->CurrentPage;
            } else {
                // return all results
                $customconfigs = $this->Phreezer->Query('CustomconfigReporter', $criteria);
                $output->rows = $customconfigs->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = count($output->rows);
                $output->totalPages = 1;
                $output->pageSize = $output->totalResults;
                $output->currentPage = 1;
            }


            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method retrieves a single Customconfig record and render as JSON
     */
    public function Read() {
        try {
            $pk = $this->GetRouter()->GetUrlParam('configId');
            $customconfig = $this->Phreezer->Get('Customconfig', $pk);
            $this->RenderJSON($customconfig, $this->JSONPCallback(), true, $this->SimpleObjectParams());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method inserts a new Customconfig record and render response as JSON
     */
    public function Create() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $customconfig = new Customconfig($this->Phreezer);

            // TODO: any fields that should not be inserted by the user should be commented out
            // this is an auto-increment.  uncomment if updating is allowed
            // $customconfig->ConfigId = $this->SafeGetVal($json, 'configId');

            $customconfig->Salesorder = $this->SafeGetVal($json, 'salesorder');
            $customconfig->ConfigTarget = $this->SafeGetVal($json, 'configTarget');
            $customconfig->ConfigContent = $this->SafeGetVal($json, 'configContent');
            $customconfig->TimeStamp = date('Y-m-d H:i:s');

            $customconfig->Validate();
            $errors = $customconfig->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                $customconfig->Save();
                $this->RenderJSON($customconfig, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method updates an existing Customconfig record and render response as JSON
     */
    public function Update() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $pk = $this->GetRouter()->GetUrlParam('configId');
            $customconfig = $this->Phreezer->Get('Customconfig', $pk);

            // TODO: any fields that should not be updated by the user should be commented out
            // this is a primary key.  uncomment if updating is allowed
            // $customconfig->ConfigId = $this->SafeGetVal($json, 'configId', $customconfig->ConfigId);

            $customconfig->Salesorder = $this->SafeGetVal($json, 'salesorder', $customconfig->Salesorder);
            $customconfig->ConfigTarget = $this->SafeGetVal($json, 'configTarget', $customconfig->ConfigTarget);
            $customconfig->ConfigContent = $this->SafeGetVal($json, 'configContent', $customconfig->ConfigContent);
            $customconfig->TimeStamp = date('Y-m-d H:i:s');

            $customconfig->Validate();
            $errors = $customconfig->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                $customconfig->Save();
                $this->RenderJSON($customconfig, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {


            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method deletes an existing Customconfig record and render response as JSON
     */
    public function Delete() {
        try {

            // TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

            $pk = $this->GetRouter()->GetUrlParam('configId');
            $customconfig = $this->Phreezer->Get('Customconfig', $pk);

            $customconfig->Delete();

            $output = new stdClass();

            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

}

?>
