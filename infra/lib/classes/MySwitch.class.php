<?php

/*
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

class MySwitch {

    private $id = -1;
    private $vlans = null;
    private $name = "";
    private $realName = "";
    private $serialNumber = "";
    private $serieType = "";
    private $description = "";
    private $cpuUtilization = -1;
    private $totalMemory = -1;
    private $freeMemory = -1;
    private $usedMemory = -1;
    private $sysUpTime = "";
    private $ip = "";
    private $nbPorts = 0;
    private $ports = array();
    private $portsmap = array();
    private $community = COMMUNITY_DEFAULT;
    private $dashboard = 1;
    private $untagged_ports = array();
    private $tagged_ports = array();
    private $egress_ports = array();
    private $untagged_ports_hexa = array();
    private $egress_ports_hexa = array();
    private $snmpVersion = SNMP_DEFAULT_VERSION;
    private $snmpV3User = SNMP_V3_DEFAULT_USER;
    private $snmpV3Passphrase = SNMP_V3_DEFAULT_PASSPHRASE;
    private $snmpV3PrivPassphrase = SNMP_V3_DEFAULT_PRIV_PASSPHRASE;
    private $snmpV3AuthProtocol = SNMP_V3_DEFAULT_AUTH_PROTOCOL;
    private $snmpV3PrivProtocol = SNMP_V3_DEFAULT_PRIV_PROTOCOL;
    private $snmpV3SecLevel = SNMP_V3_DEFAULT_SEC_LEVEL;
    private $sshUser = SSH_DEFAULT_USER;
    private $sshPassword = SSH_DEFAULT_PASSWORD;
    private $plannedForBackup = DEFAULT_PLANNED_FOR_BACKUP_VALUE;
    private $hp3ComCompat = 0;
    private $oid3ComDeviceId = 0;
    private $groupId = 0;
    private static $switchs = array();

    public function __construct() {
        snmp_set_oid_numeric_print(TRUE);
        snmp_set_quick_print(TRUE);
        snmp_set_enum_print(TRUE);
    }

    public function get3ComOidDeviceId() {
        if ($this->hp3ComCompat) {
            if ($this->oid3ComDeviceId == 0) {
                $this->set3ComOidDeviceId();
            }
            return $this->oid3ComDeviceId;
        } else {
            return 0;
        }
    }

    public function set3ComOidDeviceId() {
        if ($this->hp3ComCompat) {
            $this->oid3ComDeviceId = Fonctions::simpleSnmpGet($this, OID_3COM_board_id);
            self::$switchs[$this->id] = $this;
        }
    }

    static function retrieveById($id_switch) {

        if (!isset(self::$switchs[$id_switch])) {

            $p = new MyXMLParser();
            $mySwitchs = $p->getMySwitchs();
            foreach ($mySwitchs as $s) {
                if ($s->getId() == $id_switch) {
                    return $s;
                }
            }
        } else {
            return(self::$switchs[$id_switch]);
        }
        return false;
    }

    public function setId($id_switch = -1) {
        $this->id = $id_switch;
        self::$switchs[$this->id] = $this;
    }

    public function setName($name = "") {
        $this->name = $name;
        self::$switchs[$this->id] = $this;
    }

    public function setIp($ip = "") {
        $this->ip = $ip;
        self::$switchs[$this->id] = $this;
    }

    public function setCommunity($community = COMMUNITY_DEFAULT) {
        if ($community == "") {
            $community = COMMUNITY_DEFAULT;
        }
        $this->community = $community;
        self::$switchs[$this->id] = $this;
    }

    public function setSshPassword($sshPassword = SSH_DEFAULT_PASSWORD) {
        if ($sshPassword == "") {
            $sshPassword = SSH_DEFAULT_PASSWORD;
        }
        $this->sshPassword = $sshPassword;
        self::$switchs[$this->id] = $this;
    }

    public function setSshUser($sshUser = SSH_DEFAULT_USER) {
        if ($sshUser == "") {
            $sshUser = SSH_DEFAULT_USER;
        }
        $this->sshUser = $sshUser;
        self::$switchs[$this->id] = $this;
    }

    public function setPlannedForBackup($plannedForBackup = DEFAULT_PLANNED_FOR_BACKUP_VALUE) {
        if ($plannedForBackup == "") {
            $plannedForBackup = DEFAULT_PLANNED_FOR_BACKUP_VALUE;
        }
        $this->plannedForBackup = $plannedForBackup;
        self::$switchs[$this->id] = $this;
    }

    public function setHp3ComCompat($hp3ComCompat = 0) {
        $this->hp3ComCompat = $hp3ComCompat;
        self::$switchs[$this->id] = $this;
    }

    public function setGroupId($group_id = 0) {
        $this->groupId = $group_id;
        self::$switchs[$this->id] = $this;
    }

    public function setDashboard($dashboard = 1) {
        $this->dashboard = $dashboard;
        self::$switchs[$this->id] = $this;
    }

    public function setSnmpVersion($version = SNMP_DEFAULT_VERSION) {
        if ($version == "") {
            $version = SNMP_DEFAULT_VERSION;
        }
        $this->snmpVersion = $version;
        self::$switchs[$this->id] = $this;
    }

    public function setSnmpV3User($user = SNMP_V3_DEFAULT_USER) {
        if ($user == "") {
            $user = SNMP_V3_DEFAULT_USER;
        }
        $this->snmpV3User = $user;
        self::$switchs[$this->id] = $this;
    }

    public function setSnmpV3Passphrase($passphrase = SNMP_V3_DEFAULT_PASSPHRASE) {
        if ($passphrase == "") {
            $passphrase = SNMP_V3_DEFAULT_PASSPHRASE;
        }
        $this->snmpV3Passphrase = $passphrase;
        self::$switchs[$this->id] = $this;
    }

    public function setSnmpV3PrivPassphrase($passphrase = SNMP_V3_DEFAULT_PRIV_PASSPHRASE) {
        if ($passphrase == "") {
            $passphrase = SNMP_V3_DEFAULT_PRIV_PASSPHRASE;
        }
        $this->snmpV3PrivPassphrase = $passphrase;
        self::$switchs[$this->id] = $this;
    }

    public function setSnmpV3AuthProtocol($authProtocol = SNMP_V3_DEFAULT_AUTH_PROTOCOL) {
        if ($authProtocol == "") {
            $authProtocol = SNMP_V3_DEFAULT_AUTH_PROTOCOL;
        }
        $this->snmpV3AuthProtocol = $authProtocol;
        self::$switchs[$this->id] = $this;
    }

    public function setSnmpV3PrivProtocol($privProtocol = SNMP_V3_DEFAULT_PRIV_PROTOCOL) {

        if ($privProtocol == "") {
            $privProtocol = SNMP_V3_DEFAULT_PRIV_PROTOCOL;
        }
        $this->snmpV3PrivProtocol = $privProtocol;
        self::$switchs[$this->id] = $this;
    }

    public function setSnmpV3SecLevel($secLevel = SNMP_V3_DEFAULT_SEC_LEVEL) {
        if ($secLevel == "") {
            $secLevel = SNMP_V3_DEFAULT_SEC_LEVEL;
        }
        $this->snmpV3SecLevel = $secLevel;
        self::$switchs[$this->id] = $this;
    }

    public function setVlans() {

        $vlans = Fonctions::simpleSnmpWalk($this, OID_dot1qVlanStaticName);

        $vlans_oids = Fonctions::simpleSnmpRealWalk($this, OID_dot1qVlanStaticName);

        if ($vlans_oids === false) {
            throw new Exception("ERROR snmp : OID = " . OID_dot1qPvid . " on the switch " . $this->ip . " (community : " . $this->community . ")");
        }

        foreach ($vlans_oids AS $oid => $valeur) {
            // Supression des guillemets
            if ($valeur[0] == "\"" && $valeur[strlen($valeur) - 1] == "\"") {
                $valeur = substr($valeur, 1);
                $valeur = substr($valeur, 0, strlen($valeur) - 1);
            }

            $vlans_objects[] = new Vlan($this->getId(), substr($oid, strrpos($oid, '.') + 1, strlen($oid)), $valeur);
        }

        $this->vlans = $vlans_objects;

        self::$switchs[$this->id] = $this;
    }

    public function addVlan($vlan_id, $vlan_name) {
        /*         * **
         * Creates a vlan  on the switch
         */
        if (ALLOW_VLAN_CREATION) {
            if (strlen($vlan_name) > 12) {
                die(MSG_VLAN_NAME_TOO_LONG);
            }
            if (!is_int($vlan_id)) {
                die(MSG_VLAN_ID_BAD_VALUE);
            }
            // non existant vlan ?
            if ($this->vlanExistsForName($vlan_name)) {
                die(MSG_VLAN_NAME_ALREADY_EXISTS);
            }
            if (is_numeric($vlan_name)) {
                die(MSG_VLAN_BAD_NAME);
            }
            if ($this->vlanExists($vlan_id)) {
                die(MSG_VLAN_ID_ALREADY_EXISTS);
            }

            $oids[] = OID_dot1qVlanStaticRowStatus . "." . $vlan_id;
            $oids[] = OID_dot1qVlanStaticName . "." . $vlan_id;
            /* $oids[] = OID_dot1qVlanStaticEgressPorts.".".$vlan_id;
              $oids[] = OID_dot1qVlanForbiddenEgressPorts.".".$vlan_id;
              $oids[] = OID_dot1qVlanStaticUntaggedPorts.".".$vlan_id; */

            $types[] = "i";
            $types[] = "s";
            /* $types[] = "x";
              $types[] = "x";
              $types[] = "x"; */

            /* $nbPorts = $this->getNbPorts();
              $size = $nbPorts *2;
              $emptyExString = Fonctions::getEmptyBitMap($size); */

            $values[] = (int) 5;
            $values[] = (string) $vlan_name;
            /* $values[] = (string)$emptyExString;
              $values[] = (string)$emptyExString;
              $values[] = (string)$emptyExString; */

            $result = Fonctions::multiSnmpSet($this, $oids, $types, $values);
        } else {
            return false;
        }
    }

    public function deleteVlan($id) {
        if (ALLOW_VLAN_DELETION) {
            if (!$this->vlanExists($id)) {
                die(MSG_NON_EXISTENT_VLAN);
            }
            $egressPorts = $this->getEgressPorts($id);
            if (isset($egressPorts[0])) { // At least one tagged or untagged port.
                die(MSG_VLAN_HAS_PORTS_TAGGED_OR_UNTAGGED);
            }
            if ($id == 1) {
                die(MSG_DEFAULT_VLAN_CANNOT_BE_DELETED);
            }
            $result = Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticRowStatus . "." . $id, 'i', (int) 6);
        } else {
            return false;
        }
    }

    public function vlanExistsForName($name) {
        $myVlans = $this->getVlans();
        foreach ($myVlans as $vlan) {
            if ($vlan->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    public function vlanExists($id) {
        $myVlans = $this->getVlans();
        foreach ($myVlans as $vlan) {
            if ($vlan->getId() == $id) {
                return true;
            }
        }
        return false;
    }

    public function setNbPorts() {
        $ports = Fonctions::simpleSnmpRealWalk($this, OID_dot1qPvid);
        $portsmap = array();
        $i = 1;
        foreach ($ports as $oid => $val) {
            $oid = preg_replace("/.*\.(\d+)$/", "$1", $oid);
            $this->ports[$oid] = $val;
            $portsmap[$i] = $oid;
            $i++;
        }
        $this->portsmap = $portsmap;

        $this->nbPorts = count($this->portsmap);
        self::$switchs[$this->id] = $this;
    }

    public function setRealName() {
        $real_name = Fonctions::simpleSnmpGet($this, OID_sysName);
        $this->realName = $real_name;
        self::$switchs[$this->id] = $this;
    }

    public function setSerialNumber() {
        if (!$this->hp3ComCompat) {
            $serialNumber = Fonctions::simpleSnmpGet($this, OID_hpSerialNumber);
        } else {
            $serialNumber = Fonctions::simpleSnmpGet($this, OID_3COM_entPhysicalSerialNum);
        }
        $serialNumber = str_replace(" ", "", $serialNumber);
        $serialNumber = str_replace("\"", "", $serialNumber);
        $this->serialNumber = $serialNumber;
        self::$switchs[$this->id] = $this;
    }

    public function setSerieType() {
        if (!$this->hp3ComCompat) {
            $serieType = Fonctions::simpleSnmpGet($this, OID_hpSerieType);
            $serieType = str_replace("\"", "", $serieType);
            $this->serieType = $serieType;
        } else {
            $this->serieType = -1;
        }
        self::$switchs[$this->id] = $this;
    }

    public function setDescription() {
        $description = Fonctions::simpleSnmpGet($this, OID_sysDescr);
        $this->description = $description;
        self::$switchs[$this->id] = $this;
    }

    public function setCpuUtilization() {
        if (!$this->hp3ComCompat) {
            $cpu = Fonctions::simpleSnmpGet($this, OID_hpSwitchCpuStat);
            $this->cpuUtilization = $cpu;
        } else {
            $cpu = Fonctions::simpleSnmpGet($this, OID_3COM_SwitchCpuStat . "." . $this->get3ComOidDeviceId());
            $this->cpuUtilization = $cpu;
        }
        self::$switchs[$this->id] = $this;
    }

    public function setFreeMemory() {
        if (!$this->hp3ComCompat) {
            $fm = (int) Fonctions::simpleSnmpGet($this, OID_hpLocalMemFreeBytes);
        } else {
            $fm = $this->getTotalMemory() - $this->getUsedMemory();
        }
        $this->freeMemory = $fm;
        self::$switchs[$this->id] = $this;
    }

    public function setTotalMemory() {
        if (!$this->hp3ComCompat) {
            $tm = (int) Fonctions::simpleSnmpGet($this, OID_hpLocalMemTotalBytes);
        } else {
            $tm = (int) Fonctions::simpleSnmpGet($this, OID_3COM_LocalMemTotalBytes . "." . $this->get3ComOidDeviceId());
        }
        $this->totalMemory = $tm;
        self::$switchs[$this->id] = $this;
    }

    public function setUsedMemory() {
        if (!$this->hp3ComCompat) {
            $um = (int) Fonctions::simpleSnmpGet($this, OID_hpLocalMemAllocBytes);
        } else {
            // OID_3COM_LocalMemAllocBytes returns memory utilization in percentage
            $um = (int) Fonctions::simpleSnmpGet($this, OID_3COM_LocalMemAllocBytes . "." . $this->get3ComOidDeviceId(), true);
            $um = $this->getTotalMemory() * $um / 100;
        }
        $this->usedMemory = $um;
        self::$switchs[$this->id] = $this;
    }

    public function setSysUpTime() {
        $sysUpTime = Fonctions::simpleSnmpWalk($this, OID_hpSysUpTimeInstance);
        $exploaded = explode(":", $sysUpTime[0]);
        $days = $exploaded[0];
        $hours = $exploaded[1];
        $minutes = $exploaded[2];
        $seconds = $exploaded[3];
        if (LANGUAGE == "fr") {
            $sysUpTime = $days . " jours, " . $hours . " heures, " . $minutes . " minutes et " . $seconds . " secondes.";
        } else {
            $sysUpTime = $days . " days, " . $hours . " hours, " . $minutes . " minutes and " . $seconds . " seconds.";
        }
        $this->sysUpTime = $sysUpTime;
        self::$switchs[$this->id] = $this;
    }

    public function untagPort($port_id = null, $dest_vlan = null) {
        if (!isset($port_id) || !isset($dest_vlan) || $port_id <= 0) {
            return false;
        } else {
            $port_id = (int) $port_id;
            $dest_vlan = (int) $dest_vlan;
            if (!$this->hp3ComCompat == 1) {
                Fonctions::simpleSnmpSet($this, OID_dot1qPvid . '.' . $port_id, "u", $dest_vlan); //"u" means "unsigned int"
            } else {
                $this->tagPort($port_id, $dest_vlan);
                $this->addUntaggedFlagOfPortInVlan($dest_vlan, $port_id);
            }
        }
        return true;
    }

    public function tagPort($port_id = null, $dest_vlan = null) {

        if (!isset($port_id) || !isset($dest_vlan) || $port_id <= 0) {
            return false;
        } else {

            //bool snmpset  ( string $hostname  , string $community  , string $object_id  , string $type  , mixed $value  [, int $timeout  [, int $retries  ]] )
            $port_id = (integer) $port_id;

            $ports_hexa_egress = $this->getEgressPortsInHexa($dest_vlan, true);
            $ports_bin = Fonctions::hex2bin($ports_hexa_egress);
            $ports_bin[$port_id - 1] = '1';
            $ports_hexa_egress = Fonctions::bin2Hex($ports_bin);
            return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticEgressPorts . '.' . $dest_vlan, 'x', $ports_hexa_egress);
        }
    }

    public function tagPorts($port_ids = array(), $dest_vlan = null) {

        if (!isset($port_ids) || !isset($dest_vlan)) {
            return false;
        } else {
            $ports_hexa_egress = $this->getEgressPortsInHexa($dest_vlan, true);
            $ports_bin = Fonctions::hex2bin($ports_hexa_egress);
            foreach ($port_ids as $port_id) {
                //bool snmpset  ( string $hostname  , string $community  , string $object_id  , string $type  , mixed $value  [, int $timeout  [, int $retries  ]] )
                $port_id = (integer) $port_id;
                $ports_bin[$port_id - 1] = '1';
            }
            $ports_hexa_egress = Fonctions::bin2Hex($ports_bin);
            return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticEgressPorts . '.' . $dest_vlan, 'x', $ports_hexa_egress);
        }
    }

    public function untagPorts($port_ids = array(), $dest_vlan = null) {
        if (!isset($port_ids) || !isset($dest_vlan) || count($port_ids) == 0) {
            return false;
        } else {
            foreach ($port_ids as $port_id) {
                //bool snmpset  ( string $hostname  , string $community  , string $object_id  , string $type  , mixed $value  [, int $timeout  [, int $retries  ]] )           
                Fonctions::simpleSnmpSet($this, OID_dot1qPvid . '.' . $port_id, "u", $dest_vlan);
            }
            if ($this->hp3ComCompat == 1) {
                $this->tagPorts($port_ids, $vlan_id);
                $this->addUntaggedFlagOfPortsInVlan($vlan_id, $port_ids);
            }
            return true;
        }
    }

    public function getUntaggedPorts($vlan_id, $force_snmp_request = false) {

        if (!isset($this->untagged_ports[$vlan_id]) or $force_snmp_request) {
            if (!isset($this->untagged_ports_hexa[$vlan_id])) {
                $ports_hexa = Fonctions::simpleSnmpGet($this, OID_dot1qVlanStaticUntaggedPorts . "." . $vlan_id);
                $this->untagged_ports_hexa[$vlan_id] = $ports_hexa;
            } else {
                $ports_hexa = $this->untagged_ports_hexa[$vlan_id];
            }
            $ports = Fonctions::parsePortsListInHexa($ports_hexa, $this->id, $vlan_id, "UNTAGGED");

            $this->untagged_ports[$vlan_id] = $ports;
            self::$switchs[$this->id] = $this;
        }
        return $this->untagged_ports[$vlan_id];
    }

    public function getUntaggedPortsInHexa($vlan_id, $force_snmp_request = false) {
        if (!isset($this->untagged_ports_hexa[$vlan_id]) or $force_snmp_request) {
            $this->untagged_ports_hexa[$vlan_id] = Fonctions::simpleSnmpGet($this, OID_dot1qVlanStaticUntaggedPorts . "." . $vlan_id);
            self::$switchs[$this->id] = $this;
        }
        return $this->untagged_ports_hexa[$vlan_id];
    }

    public function getEgressPorts($vlan_id, $force_snmp_request = false) { // Tagged + Untagged ports i guess :)
        if (!isset($this->egress_ports[$vlan_id]) or $force_snmp_request) {

            if (!isset($this->egress_ports_hexa[$vlan_id])) {
                $this->egress_ports_hexa[$vlan_id] = Fonctions::simpleSnmpGet($this, OID_dot1qVlanStaticEgressPorts . "." . $vlan_id);
            }

            $ports_convert = Fonctions::hex2bin($this->egress_ports_hexa[$vlan_id]);

            $this->egress_ports[$vlan_id] = Fonctions::parsePortsListInHexa($this->egress_ports_hexa[$vlan_id], $this->id, $vlan_id);
            self::$switchs[$this->id] = $this;
        }

        return $this->egress_ports[$vlan_id];
    }

    public function getEgressPortsInHexa($vlan_id, $force_snmp_request = false) {// Tagged + Untagged ports i guess :)
        if (!isset($this->egress_ports_hexa[$vlan_id]) or $force_snmp_request) {
            $this->egress_ports_hexa[$vlan_id] = Fonctions::simpleSnmpGet($this, OID_dot1qVlanStaticEgressPorts . "." . $vlan_id);
            self::$switchs[$this->id] = $this;
        }
        return $this->egress_ports_hexa[$vlan_id];
    }

    public function removeTaggedFlagOfPortInVlan($vlan_id, $port_id) {

        /*         * ******
          This function is used because when a port is untagged in another VLAN with snmpset function, the port is not set to NO in the original vlan but set to TAGGED
          And we don't want that...
          Hexa string from Fonctions::simpleSnmpGet converted in binary : each '1' position (first, second...) in the binary string represent a port number (first->port 1, second ->port 2 etc.).
         */

        $ports_hexa = $this->getEgressPortsInHexa($vlan_id, true);
        $ports_bin = Fonctions::hex2bin($ports_hexa);
        $ports_bin[$port_id - 1] = '0';
        $ports_hexa = Fonctions::bin2Hex($ports_bin);
        return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticEgressPorts . '.' . $vlan_id, "x", $ports_hexa);
    }

    public function removeTaggedFlagOfPortsInVlan($vlan_id, $port_ids, $debug = false) {

        /*         * ******
          This function is used because when a port is untagged in another VLAN with snmpset function, the port is not set to NO in the original vlan but set to TAGGED
          And we don't want that...
          Hexa string from Fonctions::simpleSnmpGet converted in binary : each '1' position (first, second...) in the binary string represent a port number (first->port 1, second ->port 2 etc.).
         */
        $ports_hexa = $this->getEgressPortsInHexa($vlan_id, true);
        $ports_bin = Fonctions::hex2bin($ports_hexa);

        if ($debug) {
            echo("Function : remove taggedFlagOfPortsInVlan<br />");
            echo("Liste des ports : <br />");
            echo("<ul>");
            foreach ($port_ids as $port_id) {
                echo("<li>$port_id</li>");
            }
            echo("</ul>");
            echo("Vlan : " . $vlan_id . "<br /><hr />");
        }

        foreach ($port_ids as $port_id) {
            $ports_bin[$port_id - 1] = '0';
        }

        $ports_hexa = Fonctions::bin2Hex($ports_bin);
        return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticEgressPorts . '.' . $vlan_id, "x", $ports_hexa);
    }

    public function addUntaggedFlagOfPortInVlan($vlan_id, $port_id) {

        /*         * ******
          This function is used to untag a port that has been tagged in a vlan (haven't found another way to do this).
          Hexa string from Fonctions::simpleSnmpGet converted in binary : each '1' position (first, second...) in the binary string represent a port number (first->port 1, second ->port 2 etc.).
         */

        $ports_hexa = $this->getUntaggedPortsInHexa($vlan_id, true);
        $ports_bin = Fonctions::hex2bin($ports_hexa);
        $ports_bin[$port_id - 1] = '1';
        $ports_hexa = Fonctions::bin2Hex($ports_bin);
        return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticUntaggedPorts . '.' . $vlan_id, "x", $ports_hexa);
    }

    public function addUntaggedFlagOfPortsInVlan($vlan_id, $port_ids, $debug = false) {


        if ($debug) {
            echo("Function : addUntaggedFlagOfPortsInVlan<br />");
            echo("Liste des ports : <br />");
            echo("<ul>");
            foreach ($port_ids as $port_id) {
                echo("<li>$port_id</li>");
            }
            echo("</ul>");
            echo("Vlan : " . $vlan_id . "<br /><hr />");
        }

        $ports_hexa = $this->getUntaggedPortsInHexa($vlan_id, true);
        $ports_bin = Fonctions::hex2bin($ports_hexa);
        foreach ($port_ids as $port_id) {
            $ports_bin[$port_id - 1] = '1';
        }
        $ports_hexa = Fonctions::bin2Hex($ports_bin);
        return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticUntaggedPorts . '.' . $vlan_id, "x", $ports_hexa);
    }

    public function removeUntaggedFlagOfPortInVlan($vlan_id, $port_id) {

        $ports_hexa = $this->getUntaggedPortsInHexa($vlan_id, true);
        $ports_bin = Fonctions::hex2bin($ports_hexa);
        $ports_bin[$port_id - 1] = '0';
        $ports_hexa = Fonctions::bin2Hex($ports_bin);
        return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticUntaggedPorts . '.' . $vlan_id, "x", $ports_hexa);
    }

    public function removeUntaggedFlagOfPortsInVlan($vlan_id, $port_ids) {

        $ports_hexa = $this->getUntaggedPortsInHexa($vlan_id, true);
        $ports_bin = Fonctions::hex2bin($ports_hexa);
        foreach ($port_ids as $port_id) {
            $ports_bin[$port_id - 1] = '0';
        }
        $ports_hexa = Fonctions::bin2Hex($ports_bin);
        return Fonctions::simpleSnmpSet($this, OID_dot1qVlanStaticUntaggedPorts . '.' . $vlan_id, "x", $ports_hexa);
    }

    public function getTaggedPorts($vlan_id, $force_snmp_request = false) {

        /*         * ****
         *  Tagged ports = EGRESS ports - UNTAGGED ports
         */
        if (!isset($this->tagged_ports[$vlan_id]) or $force_snmp_request) {
            $egress_ports = $this->getEgressPorts($vlan_id, $force_snmp_request);
            $untagged_ports = $this->getUntaggedPorts($vlan_id, $force_snmp_request);

            $tagged_ports = array();
            if (isset($egress_ports)) {
                foreach ($egress_ports as $egress_port) {
                    $exist = false;
                    if (isset($untagged_ports)) {
                        $i = 0;
                        while ($i < count($untagged_ports) && !$exist) {
                            if ($untagged_ports[$i]->getId() == $egress_port->getId()) {
                                $exist = true;
                            }
                            $i++;
                        }
                        if (!$exist) {
                            $egress_port->setTagged(true); // We know now it's a tagged port.
                            $egress_port->setVlan($vlan_id);
                            $tagged_ports[] = $egress_port;
                        }
                    }
                }
            }
            $this->tagged_ports[$vlan_id] = $tagged_ports;
            self::$switchs[$this->id] = $this;
        }
        return $this->tagged_ports[$vlan_id];
    }

    public function getAllPorts($vlan_id = null, $force_snmp_request = false) {


        if (isset($vlan_id)) {
            /**
             * 	Returns used ports (tagged and untagged) depends on conf.php SHOW_TAGGED_PORTS and SHOW_UNTAGGED_PORTS parameters
             */
           
            if (SHOW_TAGGED_PORTS && SHOW_UNTAGGED_PORTS) {
                $result = array_merge($this->getUntaggedPorts($vlan_id, $force_snmp_request), $this->getTaggedPorts($vlan_id, $force_snmp_request));
            } elseif (SHOW_TAGGED_PORTS && !SHOW_UNTAGGED_PORTS) {
                $result = $this->getTaggedPorts($vlan_id);
            } elseif (!SHOW_TAGGED_PORTS && SHOW_UNTAGGED_PORTS) {
                $result = $this->getUntaggedPorts($vlan_id);
            } else {
                return array();
            }
            return $result;
        } else {
            return($this->portsmap);
        }
    }

    public function countNbTags($port_id, $force_snmp_request = false) {
        $vlans = $this->getVlans();
        $i = 0;
        $nbTags = 0;
        while ($i < count($vlans) && $nbTags <= 1) {
            $tagged_ports = $this->getTaggedPorts($vlans[$i]->getId(), $force_snmp_request);
            $j = 0;
            while ($j < count($tagged_ports) && $nbTags <= 1) {
                if ($tagged_ports[$j]->getId() == $port_id) {
                    $nbTags++;
                }
                $j++;
            }
            $i++;
        }
        return $nbTags;
    }

    public function portIsTaggedInOnlyOneVlan($port_id, $force_snmp_request = false) {

        /**
         * 	Returns true if a port is tagged in only one vlan.
         */
        $nbTags = $this->countNbTags($port_id, $force_snmp_request);

        if ($nbTags == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function portIsTaggedInAtLeastOneVlan($port_id, $force_snmp_request = false) {

        /**
         * 	Returns true if a port is tagged in at least one vlan.
         */
        $nbTags = $this->countNbTags($port_id, $force_snmp_request);

        if ($nbTags >= 1) {
            return true;
        } else {
            return false;
        }
    }

    public function portIsNeverTagged($port_id, $force_snmp_request) {

        /**
         * 	Returns true if a port is never tagged.
         */
        $nbTags = $this->countNbTags($port_id, $force_snmp_request = false);

        if ($nbTags == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function portIsTaggedInMoreThanOneVlan($port_id, $force_snmp_request = false) {

        /**
         * 	Returns true if a port is tagged in more than one vlan.
         */
        /**
         * 	Returns true if a port is tagged in only one vlan.
         */
        $nbTags = $this->countNbTags($port_id, $force_snmp_request);

        if ($nbTags > 1) {
            return true;
        } else {
            return false;
        }
    }

    public function portIsUntagged($port_id, $force_snmp_request = false) {

        /**
         * 	Returns true if a port is untagged somewhere. Note that a port can be untagged in only one vlan.
         */
        $vlans = $this->getVlans();
        $i = 0;
        $nbTags = 0;
        while ($i < count($vlans) && $nbTags <= 1) {
            $untagged_ports = $this->getUntaggedPorts($vlans[$i]->getId(), $force_snmp_request);
            $j = 0;
            while ($j < count($untagged_ports) && $nbTags <= 1) {
                if ($untagged_ports[$j]->getId() == $port_id) {
                    $nbTags++;
                }
                $j++;
            }
            $i++;
        }
        if ($nbTags == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function vlanWhereThePortIsUntagged($port_id, $force_snmp_request = false) {
        $vlans = $this->getVlans();
        $i = 0;
        $vlan = null;
        $nbTags = 0;
        while ($i < count($vlans) && $nbTags <= 1) {
            $untagged_ports = $this->getUntaggedPorts($vlans[$i]->getId(), $force_snmp_request);
            $j = 0;
            while ($j < count($untagged_ports) && $nbTags <= 1) {
                if ($untagged_ports[$j]->getId() == $port_id) {
                    $vlan = $vlans[$i];
                    $nbTags += 1;
                }
                $j++;
            }
            $i++;
        }
        return $vlan;
    }

    public function portIsUntaggedInVlan($port_id, $vlan_id, $force_snmp_request = false) {

        /**
         * 	Returns true if a port is untagged in specified vlan. Note that a port can be untagged in only one vlan.
         */
        $vlans = $this->getVlans();
        $i = 0;
        $nbTags = 0;
        while ($i < count($vlans) && $nbTags <= 1) {
            if ($vlans[$i]->getId() == $vlan_id) {
                $untagged_ports = $this->getUntaggedPorts($vlans[$i]->getId(), $force_snmp_request);
                $j = 0;
                while ($j < count($untagged_ports) && $nbTags <= 1) {
                    if ($untagged_ports[$j]->getId() == $port_id) {
                        $nbTags++;
                    }
                    $j++;
                }
            }
            $i++;
        }
        if ($nbTags == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function __toString() {

        $color_cpu = "Green";
        if ($this->getCpuUtilization() > 50 && $this->getCpuUtilization() < 75) {
            $color_cpu = "Orange";
        } elseif ($this->getCpuUtilization() > 75) {
            $color_cpu = "Red";
        }

        $color_memory = "Green";
        if ($this->getMemoryUtilization() > 50 && $this->getMemoryUtilization() > 75) {
            $color_memory = "Orange";
        } elseif ($this->getMemoryUtilization() > 75) {
            $color_memory = "Red";
        }

        if (HIDE_DETAILS_BOX) { /* details are not displayed but can be by 'hovering' details link */
            $display = "style=\"display: none;\"";
            $link_show_details = "<a href=\"#\" class=\"switchDetails-link btn btn-default\">details</a>\n";
        } else {
            $display = "";
            $link_show_details = "";
        }

        if (ENABLE_SSH_CONSOLE) {
            $console_ssh_link = "<a href=\"display_console.php?switch_id=$this->id&amp;protocol=ssh\">ssh</a>";
        } else {
            $console_ssh_link = "";
        }

        if (ENABLE_TELNET_CONSOLE) {
            $console_telnet_link = "<a href=\"display_console.php?switch_id=$this->id&amp;protocol=telnet\">telnet</a>";
        } else {
            $console_telnet_link = "";
        }

        if (ENABLE_SWITH_CONFIGURATION_VIEW) {
           
            $swith_configuration_link = "<a class='btn btn-default' id='displayconf' href=\"display_config.php?switch_id=$this->id\">config</a>";
        } else {
            $swith_configuration_link = "";
        }


        if (DISABLE_DETAILS_BOX) {
            return "<h1>\n$this->name<a href=\"http://$this->ip\">web</a>" . $console_ssh_link . "</h1>\n";
        } else {

            if ($this->getSerialNumber() == "") {
                $serialNumber = "Unavailable";
            } else {
                $serialNumber = $this->getSerialNumber();
            }
            if ($this->getTotalMemory() == -1) {
                $totalMemory = "Unavailable";
            } else {
                $totalMemory = round($this->totalMemory / 1024 / 1024, 0) . " Mo";
            }
            if ($this->getFreeMemory() == -1) {
                $freeMemory = "Unavailable";
            } else {
                $freeMemory = round($this->freeMemory / 1024 / 1024, 0) . " Mo";
            }
            if ($this->getUsedMemory() == -1) {
                $usedMemory = "Unavailable";
            } else {
                $usedMemory = round($this->usedMemory / $this->totalMemory * 100) . "%";
            }
            if ($this->getCpuUtilization() == -1) {
                $cpuUtilization = "Unavailable";
            } else {
                $cpuUtilization = round($this->cpuUtilization, 0) . "%";
            }

            if (LANGUAGE == 'fr') {
                return("<h1>\n$this->name<a href=\"http://$this->ip\" target='_blank'>Switch web page</a>" . $console_ssh_link . $console_telnet_link . $swith_configuration_link . $link_show_details . "</h1>" .
                        "<div id=\"toolTipsSwitchDetails\"" . $display . " class=\"infoBox\">\n" .
                        "<p><u>Description d&eacute;taill&eacute;e :</u> " . $this->getDescription() . "</p>\n" .
                        "<ul><li><b>Adresse IP</b> : " . $this->ip . "</li>\n" .
                        "<li><b>Nom r&eacute;el</b> : " . $this->getRealName() . "</li>\n" .
                        "<li><b>Num&eacute;ro de s&eacute;rie :</b> " . $serialNumber . "</li>\n" .
                        "<li><b>M&eacute;moire totale : </b>" . $totalMemory . "</li>\n " .
                        "<li><b>M&eacute;moire disponible : </b>" . $freeMemory . "</li>\n" .
                        "<li style='color:" . $color_memory . "'><b>Utilisation de la m&eacute;moire :</b> " . $usedMemory . "</li>\n" .
                        "<li style='color:" . $color_cpu . "'><b>Utilisation du mirco-processeur :</b> " . $cpuUtilization . "</li>\n" .
                        "<li><b>Uptime :</b> " . $this->getSysUpTime() . "</li>\n" .
                        "<li><b>Version snmp utilis&eacute;e</b> : " . $this->snmpVersion . "</li>\n" .
                        "</ul>\n" .
                        "</div>\n"
                        );
            } else {
                return("<table "
                        . "class='table table-responsive'><tr>"
                        . "<th class='well' colspan='5'><center>"
                        . "<h2 ><span class='label label-default'>$this->name - $this->ip</span></h2>"
                        . "</center></th></tr><tr class='bg-info'>"
                        . "<td><a  href=\"http://$this->ip\" target='_blank' class='btn btn-default'>Switch web page</a></td>"
                        . "<td>$console_ssh_link</td>"
                        . "<td>$console_telnet_link</td>"
                        . "<td>$swith_configuration_link</td>"
                        . "<td>$link_show_details"
                        . "</td></tr></table>" .
                        "<div id=\"toolTipsSwitchDetails\"" . $display . " class=\"infoBox\">\n" .
                        "<p><u>Detailed description :</u> " . $this->getDescription() . "</p>\n" .
                        "<ul><li><b>IP Address</b> : " . $this->ip . "</li>\n" .
                        "<li><b>Real name</b> : " . $this->getRealName() . "</li>\n" .
                        "<li><b>Serial Number :</b> " . $serialNumber . "</li>\n" .
                        "<li><b>Total Memory : </b>" . $totalMemory . "</li>\n " .
                        "<li><b>Free Memory : </b>" . $freeMemory . "</li>\n" .
                        "<li style='color:" . $color_memory . "'><b>Memory usage :</b> " . $usedMemory . "</li>\n" .
                        "<li style='color:" . $color_cpu . "'><b>CPU usage :</b> " . $cpuUtilization . "</li>\n" .
                        "<li><b>Uptime :</b> " . $this->getSysUpTime() . "</li>\n" .
                        "<li><b>Snmp version in use</b> : " . $this->snmpVersion . "</li>\n" .
                        "</ul>\n" .
                        "</div>\n"
                        );
            }
        }
    }

    public function getVlans() {
        if (!isset($this->vlans)) {
            $this->setVlans();
        }
        return $this->vlans;
    }

    public function getNbPorts() {
        if ($this->nbPorts == 0) {
            $this->setNbPorts();
        }
        return $this->nbPorts;
    }

    public function getPortsmap() {
        return $this->portsmap;
    }

    public function getRealName() {
        if ($this->realName == "") {
            $this->setRealName();
        }
        return $this->realName;
    }

    public function getSerialNumber() {
        if ($this->serialNumber == "") {
            $this->setSerialNumber();
        }
        return $this->serialNumber;
    }

    public function getSerieType() {
        if ($this->serieType == "") {
            $this->setSerieType();
        }
        return $this->serieType;
    }

    public function getDescription() {
        if ($this->description == "") {
            $this->setDescription();
        }
        return $this->description;
    }

    public function getCpuUtilization() {
        if ($this->cpuUtilization == -1) {
            $this->setCpuUtilization();
        }
        return $this->cpuUtilization;
    }

    public function getFreeMemory() {
        if ($this->freeMemory == -1) {
            $this->setFreeMemory();
        }
        return $this->freeMemory;
    }

    public function getTotalMemory() {
        if ($this->totalMemory == -1) {
            $this->setTotalMemory();
        }
        return $this->totalMemory;
    }

    public function getUsedMemory() {
        if ($this->usedMemory == -1) {
            $this->setUsedMemory();
        }
        return $this->usedMemory;
    }

    public function getMemoryUtilization() {
        if (!$this->hp3ComCompat) {
            /**
             * returns memory utilization as a percentage
             * */
            return round($this->getUsedMemory() / $this->getTotalMemory() * 100, 0);
        } else {
            return -1;
        }
    }

    public function getSysUpTime() {
        if ($this->sysUpTime == "") {
            $this->setSysUpTime();
        }
        return $this->sysUpTime;
    }

    public function getCommunity() {
        return $this->community;
    }

    public function hasToBeDiplayedInDashboard() {
        if ($this->dashboard == 1) {
            return true;
        } else {
            return false;
        }
    }

    /** Retrieves switch configuration with sftp * */
    public function getConf($html = true) {
        if (!$this->hp3ComCompat) {
            if (OS_TYPE == "UNIX") {
                $connection = ssh2_connect($this->ip, 22);
                if (!$connection) {
                    throw new Exception("[switch $this->name - $this->ip ] " . SSH_CONNECTION_ERROR);
                }
                $auth = ssh2_auth_password($connection, $this->sshUser, $this->sshPassword);
                if (!$auth) {
                    throw new Exception("[switch $this->name - $this->ip ] " . SSH_CONNECTION_ERROR);
                }
                $ssh2 = ssh2_sftp($connection);
                if (!$ssh2) {
                    throw new Exception("[switch $this->name - $this->ip ] " . SSH_CONNECTION_ERROR);
                }
                $data = file_get_contents("ssh2.sftp://$ssh2/cfg/startup-config");
                if (!$data) {
                    throw new Exception("[switch $this->name - $this->ip ] " . SSH_RECV_ERROR);
                }
            } else { // For windows libssh2 doesn't exist natively so we use phpseclib
                include_once('Net/SSH2.php');
                include_once('Net/SFTP.php');

                $sftp = new Net_SFTP($this->ip, 22);
                $result = $sftp->login("$this->sshUser", "$this->sshPassword");
                if (!$result) {
                    throw new Exception("[switch $this->name - $this->ip ] " . SSH_CONNECTION_ERROR);
                }
                $data = $sftp->get("/cfg/startup-config");
                if (!$data) {
                    throw new Exception("[switch $this->name - $this->ip ] " . SSH_RECV_ERROR);
                }
            }
        } else {
            $data = "Not yet working for hp products originally designed 3com products";
        }

        if ($html) {
            $data = nl2br(htmlspecialchars($data));
        }
        return $data;
    }

    /** Retrieves switch configuration with rancid * */
    public function getConfRancid() {
        if (!$this->hp3ComCompat) {

            $host = $this->ip;
            return "<textarea class='form-control' rows='30' id='conf'>".shell_exec("sudo su - rancid /var/lib/rancid/rancidGet $host")."</textarea>";
           
        } else {
            return "Not yet working for hp products originally designed 3com products";
        }
    }

    /** Saves switch configuration with sftp * */
    public function setConf($conf) {

        $connection = ssh2_connect($this->ip, 22);
        if (!$connection) {
            throw new Exception("[switch $this->name - $this->ip ] " . SSH_CONNECTION_ERROR);
        }
        $auth = ssh2_auth_password($connection, $this->sshUser, $this->sshPassword);
        if (!$auth) {
            throw new Exception("[switch $this->name - $this->ip ] " . SSH_CONNECTION_ERROR);
        }
        $ssh2 = ssh2_sftp($connection);
        if (!$ssh2) {
            throw new Exception("[switch $this->name - $this->ip ] " . SSH_CONNECTION_ERROR);
        }
        $stream = @fopen("ssh2.sftp://$ssh2/cfg/startup-config", 'w');
        if (!$stream) {
            throw new Exception("[switch $this->name - $this->ip ] " . SSH_FOPEN_W_ERROR);
        }
        if (@fwrite($stream, $conf) === false) {
            throw new Exception("[switch $this->name - $this->ip ] " . SSH_FWRITE_ERROR);
        }


        return $conf;
    }

    public function getEncryptedConf($html = false) {
        $conf = $this->getConf($html);
        $iv = substr(md5(mt_rand(), true), 0, 8);
        $key = hash("SHA256", CONFIGURATION_FILES_ENCRYPT_KEY, true);

        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $conf = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $conf, MCRYPT_MODE_ECB, $iv);

        return $conf;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getSshPassword() {
        return $this->sshPassword;
    }

    public function getSshUser() {
        return $this->sshUser;
    }

    public function isPlannedForBackup() {
        return $this->plannedForBackup;
    }

    public function getSnmpVersion() {
        return $this->snmpVersion;
    }

    public function getSnmpV3User() {
        return $this->snmpV3User;
    }

    public function getSnmpV3Passphrase() {
        return $this->snmpV3Passphrase;
    }

    public function getSnmpV3PrivPassphrase() {
        return $this->snmpV3PrivPassphrase;
    }

    public function getSnmpV3AuthProtocol() {
        return $this->snmpV3AuthProtocol;
    }

    public function getSnmpV3PrivProtocol() {
        return $this->snmpV3PrivProtocol;
    }

    public function getSnmpV3SecLevel() {
        return $this->snmpV3SecLevel;
    }

    public function getGroupId() {
        return $this->groupId;
    }

    public function getHp3ComCompat() {
        return $this->hp3ComCompat;
    }

}

?>
