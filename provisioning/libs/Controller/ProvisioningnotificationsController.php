<?php

/** @package    SPOT::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Provisioningnotifications.php");

/**
 * ProvisioningnotificationsController is the controller class for the Provisioningnotifications object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ProvisioningnotificationsController extends AppBaseController {

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
     * Displays a list view of Provisioningnotifications objects
     */
    public function ListView() {
        $this->Render();
    }

    /**
     * API Method queries for Provisioningnotifications records and render as JSON
     */
    public function Query() {
        try {


            $criteria = new ProvisioningnotificationsCriteria();
            $criteria->SetOrder('Update', true);
            // TODO: this will limit results based on all properties included in the filter list 
            $filter = RequestUtil::Get('filter');
            if ($filter)
                $criteria->AddFilter(
                        new CriteriaFilter('Notifid,Hostname,Installationip,Configuredip,Startdate,Status,Progress,Image,Firmware,Ram,Cpu,Diskscount,Netintcount,Model,Serial,Os'
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

                $provisioningnotificationses = $this->Phreezer->Query('Provisioningnotifications', $criteria)->GetDataPage($page, $pagesize);
                $output->rows = $provisioningnotificationses->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = $provisioningnotificationses->TotalResults;
                $output->totalPages = $provisioningnotificationses->TotalPages;
                $output->pageSize = $provisioningnotificationses->PageSize;
                $output->currentPage = $provisioningnotificationses->CurrentPage;
            } else {
                // return all results
                $provisioningnotificationses = $this->Phreezer->Query('Provisioningnotifications', $criteria);
                $output->rows = $provisioningnotificationses->ToObjectArray(true, $this->SimpleObjectParams());
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
     * API Method retrieves a single Provisioningnotifications record and render as JSON
     */
    public function Read() {
        try {
            $pk = $this->GetRouter()->GetUrlParam('notifid');
            $provisioningnotifications = $this->Phreezer->Get('Provisioningnotifications', $pk);
            $this->RenderJSON($provisioningnotifications, $this->JSONPCallback(), true, $this->SimpleObjectParams());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method inserts a new Provisioningnotifications record and render response as JSON
     */
    public function Create() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $provisioningnotifications = new Provisioningnotifications($this->Phreezer);

            // TODO: any fields that should not be inserted by the user should be commented out

            $provisioningnotifications->Notifid = $this->SafeGetVal($json, 'notifid');
            $provisioningnotifications->Hostname = $this->SafeGetVal($json, 'hostname');
            $provisioningnotifications->Installationip = $this->SafeGetVal($json, 'installationip');
            $provisioningnotifications->Configuredip = $this->SafeGetVal($json, 'configuredip');
            $provisioningnotifications->Startdate = date('Y-m-d H:i:s');
            //$provisioningnotifications->Startdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'startdate')));
            $provisioningnotifications->Status = $this->SafeGetVal($json, 'status');
            $provisioningnotifications->Progress = $this->SafeGetVal($json, 'progress');
            $provisioningnotifications->Image = $this->SafeGetVal($json, 'image');
            $provisioningnotifications->Firmware = $this->SafeGetVal($json, 'firmware');
            $provisioningnotifications->Ram = $this->SafeGetVal($json, 'ram');
            $provisioningnotifications->Cpu = $this->SafeGetVal($json, 'cpu');
            $provisioningnotifications->Diskscount = $this->SafeGetVal($json, 'diskscount');
            $provisioningnotifications->Netintcount = $this->SafeGetVal($json, 'netintcount');
            $provisioningnotifications->Model = $this->SafeGetVal($json, 'model');
            $provisioningnotifications->Serial = $this->SafeGetVal($json, 'serial');
            $provisioningnotifications->Os = $this->SafeGetVal($json, 'os');
            //$provisioningnotifications->Update = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'update')));
            $provisioningnotifications->Update = date('Y-m-d H:i:s');

            $provisioningnotifications->Validate();
            $errors = $provisioningnotifications->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                // since the primary key is not auto-increment we must force the insert here
                if ($provisioningnotifications->Progress != 0) {
                    $provisioningnotifications->Save(True);
                }
              //  $provisioningnotifications->Save(true);
                $this->RenderJSON($provisioningnotifications, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method updates an existing Provisioningnotifications record and render response as JSON
     */
    public function Update() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $pk = $this->GetRouter()->GetUrlParam('notifid');
            $provisioningnotifications = $this->Phreezer->Get('Provisioningnotifications', $pk);

            // TODO: any fields that should not be updated by the user should be commented out
            // this is a primary key.  uncomment if updating is allowed
            $provisioningnotifications->Notifid = $this->SafeGetVal($json, 'notifid', $provisioningnotifications->Notifid);

            $provisioningnotifications->Hostname = $this->SafeGetVal($json, 'hostname', $provisioningnotifications->Hostname);
           // $provisioningnotifications->Installationip = $this->SafeGetVal($json, 'installationip', $provisioningnotifications->Installationip);
            $provisioningnotifications->Configuredip = $this->SafeGetVal($json, 'configured ip', $provisioningnotifications->Configuredip);
            //$provisioningnotifications->Startdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'startdate', $provisioningnotifications->Startdate)));
            $provisioningnotifications->Status = $this->SafeGetVal($json, 'status', $provisioningnotifications->Status);
            $provisioningnotifications->Progress = $this->SafeGetVal($json, 'progress', $provisioningnotifications->Progress);
            $provisioningnotifications->Image = $this->SafeGetVal($json, 'image', $provisioningnotifications->Image);
            $provisioningnotifications->Firmware = $this->SafeGetVal($json, 'firmware', $provisioningnotifications->Firmware);
            $provisioningnotifications->Ram = $this->SafeGetVal($json, 'ram', $provisioningnotifications->Ram);
            $provisioningnotifications->Cpu = $this->SafeGetVal($json, 'cpu', $provisioningnotifications->Cpu);
            $provisioningnotifications->Diskscount = $this->SafeGetVal($json, 'diskscount', $provisioningnotifications->Diskscount);
            $provisioningnotifications->Netintcount = $this->SafeGetVal($json, 'netintcount', $provisioningnotifications->Netintcount);
            $provisioningnotifications->Model = $this->SafeGetVal($json, 'model', $provisioningnotifications->Model);
            $provisioningnotifications->Serial = $this->SafeGetVal($json, 'serial', $provisioningnotifications->Serial);
            $provisioningnotifications->Os = $this->SafeGetVal($json, 'os', $provisioningnotifications->Os);
            // $provisioningnotifications->Update = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'update')));
            $provisioningnotifications->Update = date('Y-m-d H:i:s');

            $provisioningnotifications->Validate();
            $errors = $provisioningnotifications->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                if ($provisioningnotifications->Progress != 0) {
                    $provisioningnotifications->Save();
                }
                $this->RenderJSON($provisioningnotifications, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {

            // this table does not have an auto-increment primary key, so it is semantically correct to
            // issue a REST PUT request, however we have no way to know whether to insert or update.
            // if the record is not found, this exception will indicate that this is an insert request
            if (is_a($ex, 'NotFoundException')) {
                return $this->Create();
            }

            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method deletes an existing Provisioningnotifications record and render response as JSON
     */
    public function Delete() {
        try {

            // TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

            $pk = $this->GetRouter()->GetUrlParam('notifid');
            $provisioningnotifications = $this->Phreezer->Get('Provisioningnotifications', $pk);

            $provisioningnotifications->Delete();

            $output = new stdClass();

            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

}

?>
