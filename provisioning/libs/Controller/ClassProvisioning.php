<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_provisioning
 * This class 
 *
 * @author cristall
 */
#define("ADMIN_ID", 1);

class provisionings extends DataModel {

    private $_table = "provisioningImages";
    static $title = "Images";

    public function __construct() {
        parent::__construct();
    }

    public function count($category) {
        return parent::execq(Queries::SQLStmt("SELECT COUNT(*) FROM $this->_table"))->get();
    }

    public function view($target, $extrafiles = NULL) {
        return parent::execq(Queries::SQLStmt("SELECT *  FROM " . $this->_table . " WHERE imageTarget='$target' ORDER BY date DESC"));
    }

    public function __toString() {
        return parent::__list($this->_table);
    }

    /*   public function purge()
      {
      return parent::execq(Queries::SQLStmt("DELETE FROM $this->_table"));
      }

      public function acknowledgeAll()
      {
      return parent::execq(Queries::SQLStmt("UPDATE $this->_table SET acknowledged=1 "));
      }
      public function acknowledge($id)
      {
      return parent::execq(Queries::update(array("acknowledged" => 1), $this->_table, "ID='$id'"));

      }
      public function __destruct() {

      parent::__destruct();
      }
     */
}

class provisioning extends provisionings {

    public $provisioning = array();
    public $host;
    public $ip;
    public $mask;
    public $gw;
    public $rack;
    public $shelf;
    public $iloip;
    public $ilomask;
    public $ilogw;
    public $hostname;
    public $ilohostname;
    public $ilopassword = "***REMOVED***";
    public $windowslicense;
    public $radmin;
    public $release;
    public $aix_tz;
    public $win_tz;
    public $script_aix;
    public $image_data;
    public $images;
    public $serial;
    public $cpu;
    public $ram;
    public $aix_images;
    public $win_images;
    public $linux_images;
    public $img_table = "provisioningImages";
    public $itemName;
    public $itemNode;

    /**
     * 
     * @param type $img_table
     * @param type $imagetarget
     * @return array of images optionally filtered by target
     */
    public function getImages($img_table = "provisioningImages", $imagetarget = null) {
        isset($imagetarget) ? $images = parent::execq(Queries:: basic_select($img_table, "imageTarget='$imagetarget'"))->assoc_values() :
                        $images = parent::execq(Queries:: basic_select($img_table))->assoc_values();

        return $images;
    }

    function __construct($so = NULL, $rack = NULL, $shelf = NULL) {
        parent::__construct($this);


        // $this->map($this);
        $this->userID = 1;

        $this->salesOrder = $so;
        $this->rack = $rack;
        $this->shelf = $shelf;

        if (!( $so == NULL || $rack == NULL || $shelf == NULL)) {
            if ($this->notifExist()) {
                $this->_status = RECORD_EXIST;




                $data = parent::execq(
                                Queries:: basic_select(
                                        "provisioningNotifications", "salesOrder='$so' AND rack='$rack' AND shelf='$shelf'", "*"))->assoc_values();


                $this->setData($data);
            }
        }
    }

    function update() {
        qlog("UPDATE ; RECORD_EXIST ? ", (bool) $this->_status);
        if ($this->notifExist()) {
            qlog("user id : $this->userID");
            $saved = parent::execq(Queries::update(array(
                                "hostname" => $this->hostname,
                                "installationIP" => $this->installationIP,
                                "configuredIP" => $this->configuredIP,
                                "userID" => $this->userID,
                                "status" => $this->status,
                                "progress" => $this->progress,
                                "image" => $this->image,
                                "firmware" => $this->firmware,
                                "ram" => $this->ram,
                                "cpu" => $this->cpu,
                                "disksCount" => $this->disksCount,
                                "NetIntCount" => $this->NetIntCount,
                                "model" => $this->model,
                                "serial" => $this->serial,
                                "os" => $this->os), "provisioningNotifications", "salesOrder='$this->salesOrder' AND rack='$this->rack' AND shelf='$this->shelf'"));


            if ($saved === FALSE) {
                throw new Exception("Error while updating $this->so,$this->rack,$this->shelf", __LINE__);
            }
        } else {
            $id = parent::execq(Queries::insert_into(
                                    array(
                                "salesOrder" => $this->salesOrder,
                                "rack" => $this->rack,
                                "shelf" => $this->shelf,
                                "hostname" => $this->hostname,
                                "installationIP" => $this->installationIP,
                                "configuredIP" => $this->configuredIP,
                                "userID" => $this->userID,
                                "status" => $this->status,
                                "progress" => $this->progress,
                                "image" => $this->image,
                                "firmware" => $this->firmware,
                                "ram" => $this->ram,
                                "cpu" => $this->cpu,
                                "disksCount" => $this->disksCount,
                                "NetIntCount" => $this->NetIntCount,
                                "model" => $this->model,
                                "serial" => $this->serial,
                                "os" => $this->os
                                    ), "provisioningNotifications"));


            if ($id === false) {
                throw new Exception("Error while inserting $this->salesOrder,$this->rack,$this->shelf", __LINE__);
            } else {
                $this->_status = RECORD_EXIST;
            }
        }
    }
    
    
    /*
     * This function save all catchable form data in the current object
     */

    public function autosaveForm($data = NULL, $provisioning = NULL, $id = NULL) {

        /*
         * This fucntion autosave via ajax call the provisioning form value
         */
        if (!($provisioning = NULL && $data = NULL && $id = NULL )) {
            $data = $this->data;
            $id = $this->id;
            $provisioning = $this->provisioning;


            list($itemName, $itemNode) = explode('_', $id);
            try {


                $provisioning['provisioning']->$itemNode->$itemName = $data;




                return $provisioning;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception("Error: the values sales order and posted data cannot be null");
        }
    }

    /*
     * This function delete item from current object if we don't want to provision
     */
    public function deleteItem($provisioning = NULL, $id = NULL) {
        if (!($provisioning = NULL && $id = NULL )) {
            $provisioning = $this->provisioning;
            $id = $this->id;
            try {
                unset($provisioning['provisioning']->$id);
                return $provisioning;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception("Error: the values sales order and posted data cannot be null");
        }
    }

}
