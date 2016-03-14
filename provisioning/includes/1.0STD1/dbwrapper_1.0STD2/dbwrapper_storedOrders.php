<?php

ini_set('memory_limit', '64M');
require_once "dbwrapper_writer.php";

class DBWRAPPERSTO extends Exception {

    private $head = "[DBWStoredOrders]";

    public function __construct($message, $code = 0, Exception $previous = null) {
        $message = $this->head . $message;
        parent::__construct($message, $code, $previous);
    }

}

class DBWSTO extends DBWWriter {

    public function __construct($username, $password, $auth_method) {
        parent::__construct($username, $password, $auth_method);
    }

    public function orderAlreadyStored($so) {

        $count = $this->sql_count(TBLSTOREDORDERS, "salesOrder = '$so'", $columnName = '*');
        $count = intval($count[0]);



        return $count > 0 ? True : False;
    }

    public function hasPreviouslySavedOrder() {
        $user = $this->getInstancier();
        $count = $this->sql_count(TBLSTOREDORDERS, "USER = '" . $this->getInstancier() . "'", $columnName = '*');
        $count = intval($count[0]);



        return $count > 0;
    }

    public function getStoredOrderIDFromSo($salesOrder, $showOrigin = false) {
        $this->printdbg("SO issued :" . var_export($salesOrder, true));
        if ($showOrigin) {

            return $this->basic_select(TBLSTOREDORDERS, "salesOrder = '$salesOrder'", array("ID", "origin"));
        }
        return $this->basic_select(TBLSTOREDORDERS, "salesOrder = '$salesOrder'", array("ID"));
    }

    public function removeStoredOrder($id) {

        $origin = $this->getStoredOrderSoFromID($id);


        if ($origin[1] == "SP" or $origin[1] == "SPOT") {

            return $this->delete_row(TBLSTOREDORDERS, "ID='$id'");
        }

        parent::syslog_msg(LOG_INFO, "Removing stored order id=$id");
        return $this->delete_row(TBLSTOREDORDERS, "USER='" . $this->getInstancier() . "' AND ID='$id'");
    }

    public function getStoredOrders($filter = "") {
        if ($filter != "")
            return $this->basic_select(
                            TBLSTOREDORDERS, "origin='" . $filter . "'", array(
                        "ID",
                        "USER",
                        "salesOrder",
                        "creationDate",
                        "origin",
                        "status",
                        "message"
                            ), $orderBy = 'salesOrder', $asc = True
            );
        return $this->basic_select(TBLSTOREDORDERS, "", array("ID", "USER", "salesOrder", "creationDate", "origin", "status", "message"), $orderBy = 'origin', $asc = True);
    }

    private function getStoredOrderSoFromID($id) {

        $out = $this->basic_select(TBLSTOREDORDERS, "ID='" . $id . "'", array("salesOrder", "origin"));
        $this->printdbg($out);
        return $out[0];
    }

    protected function _propertyMerge($prop1, $prop2) {

        if ($prop1 != null)
            return $prop1;
        if ($prop1 == NULL and $prop2 != NULL)
            return $prop2;
        if ($prop1 == NULL and $prop2 == NULL)
            return NULL;
    }

    protected function _arrayMerge($Srcarray, $array) {
        if ($Srcarray == NULL AND $array != NULL)
            return $array;
        if ($array == NULL AND $Srcarray != NULL)
            return $Srcarray;
        if ($Srcarray == NULL AND $array == NULL)
            return NULL;
        return array_merge($Srcarray, $array);
    }

    public function mergeStoredOrder($IDarray, $header = "merged") {

        parent::syslog_msg(LOG_INFO, "Merging orders id(s)=" . implode(",", $IDarray));
        if (empty($IDarray) or count($IDarray) < 2 or ! is_array($IDarray))
            throw new DBWRAPPERSTO("INVALID_PARAMS");



        $orderBase = $this->getOrderObject($IDarray[0]);
        $this->removeStoredOrder($IDarray[0]);
        $genSo = $orderBase->getSalesOrder();

        $args = $IDarray;
        unset($args[0]);

        foreach ($args as $index => $value) {
            $id = $this->getOrderObject($value);
            $so = $id->getSalesOrder();


            unset($id);

            if ($so != $genSo)
                throw new DBWRAPPERSTO("ERROR : contains different sales order! CODE=1");
        }

        foreach ($args as $index => $idOfStoOrder) {
            try {
                $order = $this->getOrderObject($idOfStoOrder);
                if ($order->getSalesOrder() == $orderBase->getSalesOrder()) {


                    $this->printdbg("processing order " . $order->getSalesOrder());
                    $orderBase->setProgramManager($this->_propertyMerge($orderBase->getProgramManager(), $order->getProgramManager()));
                    $orderBase->setSiteEngineer($this->_propertyMerge($orderBase->getSiteEngineer(), $order->getSiteEngineer()));
                    $orderBase->setSysprodActor($this->_propertyMerge($orderBase->getSysprodActor(), $order->getSysprodActor()));
                    $orderBase->setRelease($this->_propertyMerge($orderBase->getRelease(), $order->getRelease()));
                    $orderBase->setComments($this->_propertyMerge($orderBase->getComments(), $order->getComments()));
                    $orderBase->setStartDate($this->_propertyMerge($orderBase->getStartDate(), $order->getStartDate()));
                    $orderBase->setEndDate($this->_propertyMerge($orderBase->getEndDate(), $order->getEndDate()));
                    $orderBase->setProdEndDate($this->_propertyMerge($orderBase->getProdEndDate(), $order->getProdEndDate()));
                    $orderBase->setProdEndDate($this->_propertyMerge($orderBase->getProdStartDate(), $order->getProdStartDate()));
                    $orderBase->setCustomer($this->_propertyMerge($orderBase->getCustomer(), $order->getCustomer()));

                    $orderBase->setCCTSnapshotPath($this->_propertyMerge($orderBase->getCCTSnaptshot(), $order->getCCTSnaptshot()));

                    $orderBase->merge_networks($order->getNetworks());


                    $count = $orderBase->merge_orders_items($order);
                } else
                    throw new DBWRAPPERSTO("ERROR : SO are different!", 2);


                $this->removeStoredOrder($idOfStoOrder);
                unset($order);
            } catch (Exception $e) {
                throw $e;
            }
        }
        return $this->storeOrder($orderBase, $header);
    }

    public function getSalesorderFromSerial($serial) {

        return $serial;
    }

    public function getProcessedPendingOrderIDBySo($so) {
        parent::toggleNoDoubleArrays();
        $out = $this->basic_select("sp_pending", "salesOrder='$so'", "ID");
        parent::toggleNoDoubleArrays();

        return $out == NULL ? False : $out;
    }

    public function getOrderObject($orderId) {

        $order = $this->basic_select(TBLSTOREDORDERS, "ID=" . $orderId, array("object"));
        if (!empty($order)) {

            $order = $order[0];


            $orderObj = unserialize(base64_decode($order));
            if ($orderObj === false) {
                $this->printdbg("Error while opening the stored order!");
                throw new DBWRAPPERSTO("Error while opening order");
            }


            $orderObj->setModelList($this->getModelsFromDB());
            $orderObj->setSysprodActor(parent::getInstancier());

            return $orderObj;
        }
        throw new DBWRAPPERSTO("NO_ORDER_FOUND");
    }

    public function storeOrder($order, $origin = "internal", $status = 0, $message = "") {
        if (!$order instanceof ORDER)
            throw new Exception("NOT_ORDER_OBJECT");

        if ($origin == "SP")
            $user = "SharePoint";
        else
            $user = $this->getInstancier();


        $content = base64_encode(serialize($order));


        $so = $order->getSalesOrder();

        $values = array("USER" => $user, "object" => $content, "salesOrder" => $so, "creationDate" => date("d-m-Y"), "origin" => $origin, "version" => "1.0STD1", "status" => $status, "message" => $message);



        $this->insert_into($values, TBLSTOREDORDERS, $REMOVE_TYPE_FLAG = False);
        parent::_commitTransaction();


        $prevOrder = $this->get_previous_sto($so);

        parent::syslog_msg(LOG_INFO, "Stored order $so");
        return true;
    }

    public function get_previous_sto($salesOrder) {


        $out = parent::basic_select(TBLSTOREDORDERS, "salesOrder=$salesOrder", "ID");

        return $out;
    }

}

?>
