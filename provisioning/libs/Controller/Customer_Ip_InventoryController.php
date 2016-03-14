<?php

/** @package    Customer IP inventory::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Customer_Ip_Inventory.php");

/**
 * Customer_Ip_InventoryController is the controller class for the Customer_Ip_Inventory object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Customer IP inventory::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class Customer_Ip_InventoryController extends AppBaseController {

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
     * Displays a list view of Customer_Ip_Inventory objects
     */
    public function ListView() {
        $this->Render();
    }

    public function Export() {
        // get the data from the database
        $ips = $this->Phreezer->Query('Customer_Ip_Inventory');

        // convert the DataSet into an standard Array
        $objects = $ips->ToObjectArray();

        // OutputAsExcel will create the spreadsheet and stream to the browser
        require_once 'verysimple/Phreeze/ExportUtility.php';
        ExportUtility::OutputAsExcel($objects, $this->Phreezer, "Customer IP Inventory", "IP_Inventory.xls", "SPOT");
 
    }

    /**
     * API Method queries for Customer_Ip_Inventory records and render as JSON
     */
    public function Query() {
        try {
            $criteria = new Customer_Ip_InventoryCriteria();
            $criteria->SetOrder('Custipid', true);
            // TODO: this will limit results based on all properties included in the filter list 
            $filter = RequestUtil::Get('filter');
            if ($filter)
                $criteria->AddFilter(
                        new CriteriaFilter('Custipid,Subnet,Netmask,Account,Location,SystemName,Entt,RemoteAccess,Comments,Valdate,ValidatedBy,Lsmod,Status'
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

                $adresses = $this->Phreezer->Query('Customer_Ip_Inventory', $criteria)->GetDataPage($page, $pagesize);
                $output->rows = $adresses->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = $adresses->TotalResults;
                $output->totalPages = $adresses->TotalPages;
                $output->pageSize = $adresses->PageSize;
                $output->currentPage = $adresses->CurrentPage;
            } else {
                // return all results
                $adresses = $this->Phreezer->Query('Customer_Ip_Inventory', $criteria);
                $output->rows = $adresses->ToObjectArray(true, $this->SimpleObjectParams());
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
     * API Method retrieves a single Customer_Ip_Inventory record and render as JSON
     */
    public function Read() {
        try {
            $pk = $this->GetRouter()->GetUrlParam('custipid');
            $customer_ip_inventory = $this->Phreezer->Get('Customer_Ip_Inventory', $pk);
            $this->RenderJSON($customer_ip_inventory, $this->JSONPCallback(), true, $this->SimpleObjectParams());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method inserts a new Customer_Ip_Inventory record and render response as JSON
     */
    public function Create() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $customer_ip_inventory = new Customer_Ip_Inventory($this->Phreezer);

            // TODO: any fields that should not be inserted by the user should be commented out
            // this is an auto-increment.  uncomment if updating is allowed
            // $customer_ip_inventory->Custipid = $this->SafeGetVal($json, 'custipid');

            $customer_ip_inventory->Subnet = $this->SafeGetVal($json, 'subnet');
            $customer_ip_inventory->Netmask = $this->SafeGetVal($json, 'netmask');
            $customer_ip_inventory->Account = $this->SafeGetVal($json, 'account');
            $customer_ip_inventory->Location = $this->SafeGetVal($json, 'location');
            $customer_ip_inventory->SystemName = $this->SafeGetVal($json, 'systemName');
            $customer_ip_inventory->Entt = $this->SafeGetVal($json, 'entt');
            $customer_ip_inventory->RemoteAccess = $this->SafeGetVal($json, 'remoteAccess');
            $customer_ip_inventory->Comments = $this->SafeGetVal($json, 'comments');
            $customer_ip_inventory->Valdate = $this->SafeGetVal($json, 'valdate');
            $customer_ip_inventory->ValidatedBy = $this->SafeGetVal($json, 'validatedBy');
            $customer_ip_inventory->Lsmod = date('Y-m-d H:i:s', strtotime($this->SafeGetVal($json, 'lsmod')));
            $customer_ip_inventory->Status = $this->SafeGetVal($json, 'status');

            $customer_ip_inventory->Validate();
            $errors = $customer_ip_inventory->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                $customer_ip_inventory->Save();
                $this->RenderJSON($customer_ip_inventory, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method updates an existing Customer_Ip_Inventory record and render response as JSON
     */
    public function Update() {
        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $pk = $this->GetRouter()->GetUrlParam('custipid');
            $customer_ip_inventory = $this->Phreezer->Get('Customer_Ip_Inventory', $pk);

            // TODO: any fields that should not be updated by the user should be commented out
            // this is a primary key.  uncomment if updating is allowed
            // $customer_ip_inventory->Custipid = $this->SafeGetVal($json, 'custipid', $customer_ip_inventory->Custipid);

            $customer_ip_inventory->Subnet = $this->SafeGetVal($json, 'subnet', $customer_ip_inventory->Subnet);
            $customer_ip_inventory->Netmask = $this->SafeGetVal($json, 'netmask', $customer_ip_inventory->Netmask);
            $customer_ip_inventory->Account = $this->SafeGetVal($json, 'account', $customer_ip_inventory->Account);
            $customer_ip_inventory->Location = $this->SafeGetVal($json, 'location', $customer_ip_inventory->Location);
            $customer_ip_inventory->SystemName = $this->SafeGetVal($json, 'systemName', $customer_ip_inventory->SystemName);
            $customer_ip_inventory->Entt = $this->SafeGetVal($json, 'entt', $customer_ip_inventory->Entt);
            $customer_ip_inventory->RemoteAccess = $this->SafeGetVal($json, 'remoteAccess', $customer_ip_inventory->RemoteAccess);
            $customer_ip_inventory->Comments = $this->SafeGetVal($json, 'comments', $customer_ip_inventory->Comments);
            //$customer_ip_inventory->Valdate = $this->SafeGetVal($json, 'valdate', $customer_ip_inventory->Valdate);
            $customer_ip_inventory->ValidatedBy = $this->SafeGetVal($json, 'validatedBy', $customer_ip_inventory->ValidatedBy);
            $customer_ip_inventory->Lsmod = date('Y-m-d H:i:s', strtotime($this->SafeGetVal($json, 'lsmod', $customer_ip_inventory->Lsmod)));
            $customer_ip_inventory->Status = $this->SafeGetVal($json, 'status', $customer_ip_inventory->Status);




            $customer_ip_inventory->Validate();
            $errors = $customer_ip_inventory->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Please check the form for errors', $errors);
            } else {
                $customer_ip_inventory->Save();
                $this->RenderJSON($customer_ip_inventory, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {


            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method deletes an existing Customer_Ip_Inventory record and render response as JSON
     */
    public function Delete() {
        try {

            // TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

            $pk = $this->GetRouter()->GetUrlParam('custipid');
            $customer_ip_inventory = $this->Phreezer->Get('Customer_Ip_Inventory', $pk);

            $customer_ip_inventory->Delete();

            $output = new stdClass();

            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

}

?>
