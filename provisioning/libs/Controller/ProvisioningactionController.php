<?php

/** @package    SPOT::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Provisioningaction.php");

/**
 * ProvisioningactionController is the controller class for the Provisioningaction object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ProvisioningactionController extends AppBaseController {

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
     * Displays a list view of Provisioningaction objects
     */
    public function ListView() {
        $this->Render();
    }

    public function Phase2View() {
        $this->Render('ProvisioningPhase2');
    }

    public function GeneralView() {
        $this->Render('ProvisioningGeneral');
    }

    public function ReloadGen() {
        $this->Render('ProvisioningGen2');
    }

    public function DiagMaint() {
        $this->Render('BootDiagMaint');
    }

    /**
     * API Method queries for Provisioningaction records and render as JSON
     */
    public function Query() {
        try {
            $criteria = new ProvisioningactionCriteria();
            $criteria->SetOrder('Actionid', true);
            // TODO: this will limit results based on all properties included in the filter list 
            $filter = RequestUtil::Get('filter');
            if ($filter)
                $criteria->AddFilter(
                        new CriteriaFilter('Actionid,Salesorder,Codeapc,Rack,Shelf,Hostname,Timezone,Posixtz,Wintz,Dststartday,Dststopday,Dststarth,Dststoph,Os,Image,Boot,Ip,Netmask,Gateway,Iloip,Ilonm,Ilogw,Workgroup,Productkey,Creationdate'
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

                $provisioningactions = $this->Phreezer->Query('Provisioningaction', $criteria)->GetDataPage($page, $pagesize);
                $output->rows = $provisioningactions->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = $provisioningactions->TotalResults;
                $output->totalPages = $provisioningactions->TotalPages;
                $output->pageSize = $provisioningactions->PageSize;
                $output->currentPage = $provisioningactions->CurrentPage;
            } else {
                // return all results
                $provisioningactions = $this->Phreezer->Query('Provisioningaction', $criteria);
                $output->rows = $provisioningactions->ToObjectArray(true, $this->SimpleObjectParams());
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
     * API Method retrieves a single Provisioningaction record and render as JSON
     */
    public function Read() {
        try {
            $pk = $this->GetRouter()->GetUrlParam('actionid');
            $provisioningaction = $this->Phreezer->Get('Provisioningaction', $pk);
            $this->RenderJSON($provisioningaction, $this->JSONPCallback(), true, $this->SimpleObjectParams());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method inserts a new Provisioningaction record and render response as JSON
     */
    public function Create() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $provisioningaction = new Provisioningaction($this->Phreezer);

            // TODO: any fields that should not be inserted by the user should be commented out

            $provisioningaction->Actionid = $this->SafeGetVal($json, 'actionid');
            $provisioningaction->Salesorder = $this->SafeGetVal($json, 'salesorder');
            $provisioningaction->Codeapc = $this->SafeGetVal($json, 'codeapc');
            $provisioningaction->Rack = $this->SafeGetVal($json, 'rack');
            $provisioningaction->Shelf = $this->SafeGetVal($json, 'shelf');
            $provisioningaction->Hostname = $this->SafeGetVal($json, 'hostname');
            $provisioningaction->Timezone = $this->SafeGetVal($json, 'timezone');
            $provisioningaction->Posixtz = $this->SafeGetVal($json, 'posixtz');
            $provisioningaction->Wintz = $this->SafeGetVal($json, 'wintz');
            $provisioningaction->Dststartday = $this->SafeGetVal($json, 'dststartday');
            $provisioningaction->Dststopday = $this->SafeGetVal($json, 'dststopday');
            $provisioningaction->Dststarth = $this->SafeGetVal($json, 'dststarth');
            $provisioningaction->Dststoph = $this->SafeGetVal($json, 'dststoph');
            $provisioningaction->Os = $this->SafeGetVal($json, 'os');
            $provisioningaction->Image = $this->SafeGetVal($json, 'image');
            $provisioningaction->Boot = $this->SafeGetVal($json, 'boot');
            $provisioningaction->Ip = $this->SafeGetVal($json, 'ip');
            $provisioningaction->Netmask = $this->SafeGetVal($json, 'netmask');
            $provisioningaction->Gateway = $this->SafeGetVal($json, 'gateway');
            $provisioningaction->Iloip = $this->SafeGetVal($json, 'iloip');
            $provisioningaction->Ilonm = $this->SafeGetVal($json, 'ilonm');
            $provisioningaction->Ilogw = $this->SafeGetVal($json, 'ilogw');
            $provisioningaction->Workgroup = $this->SafeGetVal($json, 'workgroup');
            $provisioningaction->Productkey = $this->SafeGetVal($json, 'productkey');

            $provisioningaction->Validate();
            $errors = $provisioningaction->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                // since the primary key is not auto-increment we must force the insert here
                $provisioningaction->Save(true);
                $this->RenderJSON($provisioningaction, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method updates an existing Provisioningaction record and render response as JSON
     */
    public function Update() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $pk = $this->GetRouter()->GetUrlParam('actionid');
            $provisioningaction = $this->Phreezer->Get('Provisioningaction', $pk);

            // TODO: any fields that should not be updated by the user should be commented out
            // this is a primary key.  uncomment if updating is allowed
            // $provisioningaction->Actionid = $this->SafeGetVal($json, 'actionid', $provisioningaction->Actionid);

            $provisioningaction->Salesorder = $this->SafeGetVal($json, 'salesorder', $provisioningaction->Salesorder);
            $provisioningaction->Codeapc = $this->SafeGetVal($json, 'codeapc', $provisioningaction->Codeapc);
            $provisioningaction->Rack = $this->SafeGetVal($json, 'rack', $provisioningaction->Rack);
            $provisioningaction->Shelf = $this->SafeGetVal($json, 'shelf', $provisioningaction->Shelf);
            $provisioningaction->Hostname = $this->SafeGetVal($json, 'hostname', $provisioningaction->Hostname);
            $provisioningaction->Timezone = $this->SafeGetVal($json, 'timezone', $provisioningaction->Timezone);
            $provisioningaction->Posixtz = $this->SafeGetVal($json, 'posixtz', $provisioningaction->Posixtz);
            $provisioningaction->Wintz = $this->SafeGetVal($json, 'wintz', $provisioningaction->Wintz);
            $provisioningaction->Dststartday = $this->SafeGetVal($json, 'dststartday', $provisioningaction->Dststartday);
            $provisioningaction->Dststopday = $this->SafeGetVal($json, 'dststopday', $provisioningaction->Dststopday);
            $provisioningaction->Dststarth = $this->SafeGetVal($json, 'dststarth', $provisioningaction->Dststarth);
            $provisioningaction->Dststoph = $this->SafeGetVal($json, 'dststoph', $provisioningaction->Dststoph);
            $provisioningaction->Os = $this->SafeGetVal($json, 'os', $provisioningaction->Os);
            $provisioningaction->Image = $this->SafeGetVal($json, 'image', $provisioningaction->Image);
            $provisioningaction->Boot = $this->SafeGetVal($json, 'boot', $provisioningaction->Boot);
            $provisioningaction->Ip = $this->SafeGetVal($json, 'ip', $provisioningaction->Ip);
            $provisioningaction->Netmask = $this->SafeGetVal($json, 'netmask', $provisioningaction->Netmask);
            $provisioningaction->Gateway = $this->SafeGetVal($json, 'gateway', $provisioningaction->Gateway);
            $provisioningaction->Iloip = $this->SafeGetVal($json, 'iloip', $provisioningaction->Iloip);
            $provisioningaction->Ilonm = $this->SafeGetVal($json, 'ilonm', $provisioningaction->Ilonm);
            $provisioningaction->Ilogw = $this->SafeGetVal($json, 'ilogw', $provisioningaction->Ilogw);
            $provisioningaction->Workgroup = $this->SafeGetVal($json, 'workgroup', $provisioningaction->Workgroup);
            $provisioningaction->Productkey = $this->SafeGetVal($json, 'productkey', $provisioningaction->Productkey);

            $provisioningaction->Validate();
            $errors = $provisioningaction->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                $provisioningaction->Save();
                $this->RenderJSON($provisioningaction, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
     * API Method deletes an existing Provisioningaction record and render response as JSON
     */
    public function Delete() {
        try {

            // TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

            $pk = $this->GetRouter()->GetUrlParam('actionid');
            $provisioningaction = $this->Phreezer->Get('Provisioningaction', $pk);

            $provisioningaction->Delete();

            $output = new stdClass();

            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

}

?>
