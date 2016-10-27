<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */


class MyXMLParser
{
	private $mySwitchs = null;
	private $myGroups = null;
	private $oids = null;
	
	public function __construct(){
                
               $comment = "#";

                $path_file = fopen("params/conf_path.php","r") or die("FATAL ERROR : Cannot open conf_path.php in params directory");
                $path_option_found = false;
                while (!feof($path_file)) {
                    $line = trim(fgets($path_file)); 
                    if ($line && !preg_match("@$comment@", $line)) {
                        $equal_pos=strpos($line,"=");
                        $option=substr($line,0,$equal_pos);
                        if($option == "path"){
                            $config_conf_path = substr($line,$equal_pos+1,strlen($line));
                            $path_option_found = true;
                        }
                    }
                }
                if(!$path_option_found){
                    trigger_error("FATAL ERROR : The path to configuration files was not found in params/conf_path.php. Make sure the line path=path/to/conf is present in conf_path.php !");
                }
                fclose($path_file);

		$this->mySwitchs = array();
		$xml = simplexml_load_file("$config_conf_path/switches.xml");
		$i=0;
		$ids=array();
		foreach($xml->switch as $mySwitch){
			foreach($ids as $existant_id){
				if((int) $existant_id == (int) $mySwitch->id){
					die(MSG_2_SWITCHES_WITH_THE_SAME_ID." : ".$existant_id);
				}
			}
			$ids[] = $mySwitch->id;
		}
		
		$ips=array();
		foreach($xml->switch as $mySwitch){
			foreach($ips as $existant_ip){
				if((string) $existant_ip == (string) $mySwitch->ip){
					die(MSG_2_SWITCHES_WITH_THE_SAME_IP." : ".$existant_ip);
				}
			}
			$ips[] = $mySwitch->ip;
		}

		foreach($xml->switch as $mySwitch){
			$switchs[] = new MySwitch();
			$switchs[$i]->setId((string) $mySwitch->id);
			$switchs[$i]->setName((string) $mySwitch->name);
			$switchs[$i]->setIp((string) $mySwitch->ip);
			$switchs[$i]->setCommunity((string) $mySwitch->community);
			$switchs[$i]->setDashboard((string) $mySwitch->dashboard);
			$switchs[$i]->setSnmpVersion((string) $mySwitch->snmpVersion);
			$switchs[$i]->setSnmpV3User((string) $mySwitch->snmpV3User);
			$switchs[$i]->setSnmpV3Passphrase((string) $mySwitch->snmpV3Passphrase);
			$switchs[$i]->setSnmpV3PrivPassphrase((string) $mySwitch->snmpV3PrivPassphrase);
			$switchs[$i]->setSnmpV3AuthProtocol((string) $mySwitch->snmpV3AuthProtocol);
			$switchs[$i]->setSnmpV3PrivProtocol((string) $mySwitch->snmpV3PrivProtocol);
			$switchs[$i]->setSnmpV3SecLevel((string) $mySwitch->snmpV3SecLevel);
			$switchs[$i]->setSshPassword((string) $mySwitch->sshPassword);
			$switchs[$i]->setSshUser((string) $mySwitch->sshUser);
			$switchs[$i]->setPlannedForBackup((string) $mySwitch->plannedForBackup);
			$switchs[$i]->setHp3ComCompat((string) $mySwitch->en3comCompat);
			$switchs[$i]->setGroupId((string) $mySwitch->group);
			$i++;
		}
		$this->mySwitchs = $switchs; 
		
		$this->groups =array();
		foreach($xml->group as $myGroup){
			$groups[] = new Group($myGroup->id,$myGroup->name,$myGroup->color,$myGroup->description);
			$i++;
		}
		$this->myGroups = $groups;
		
		$this->oids = array();
		$xml = simplexml_load_file('params/OIDs.xml');
		$i=0;
		$ids=array();
		foreach($xml->oid as $oid){
			foreach($ids as $existant_id){
				if((int) $existant_id == (int) $oid->id){
					die(MSG_2_OIDS_WITH_THE_SAME_ID." : ".$existant_id);
				}
			}
			$ids[] = $oid->id;
		}
		foreach($xml->oid as $oid){
			$oids[] = new Oid();
			$oids[$i]->setId((string) $oid->id);
			$oids[$i]->setName((string) $oid->name);
			$oids[$i]->setValue((string) $oid->value);
			$oids[$i]->setDescription((string) $oid->description);
			$i++;
		}
		$this->oids = $oids; 
	}
	
	function getMySwitchs(){
		return $this->mySwitchs;
	}
	
	function getMyGroups(){
		return $this->myGroups;
	}
	
	function getOids(){
		return $this->oids;
	}
}

?>
