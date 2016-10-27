<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */


/**************************************************/
/* DO NOT MODIFY THIS FILE !!! MODIFY CONFIG.CONF */
/* UNLESS YOU ARE A VERY KIND DEVELOPPER :)	  */
/**************************************************/

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

$fp = @fopen($config_conf_path."/config.conf", "r");

if($fp){
	while (!feof($fp)) {
	  $line = trim(fgets($fp)); 
	  if ($line && !preg_match("@$comment@", $line)) {
	  	$equal_pos=strpos($line,"=");
	  	$option=substr($line,0,$equal_pos);
	  	$value=substr($line,$equal_pos+1,strlen($line));
		
	/*
	    echo("Option : ".$option);
	    echo("<br />");
	    echo("Value : ".$value);
	    echo("<br />");
	*/
	    
	    $config_values[$option] = $value;
	  }
	}
	fclose($fp);
} else {
	trigger_error("FATAL ERROR : Cannot open configuration file $config_file !, make sure the good location is set in params/conf_path.php file.");
}

if ((!isset($config_values['DISABLE_FIRST_RUN_WARNINGS'])) || ($config_values['DISABLE_FIRST_RUN_WARNINGS'] == "0")){
	die(	"<p>
			
      
                        <b><u>SECURITY WARNING</b></u><br />
                        <b>Please, don't forget to move params/switches.xml and params/config.conf to a new location (non WWW accessible) and to set the new path in params/conf_path.php !
                        <br />
                        N'oubliez pas de deplacer params/switchs.xml et params/config.conf vers un repertoire non accessible par le web et de modifier params/conf_path.php en consequence !
                        </b>
                        
                        
                        <br /><br />
			<b>Disable me</b> : move and modify params/config.conf file, switches.xml before using this software. <br />(add or set DISABLE_FIRST_RUN_WARNINGS=1 in config.conf file)
                        <br />
                        <b>Desactivez moi</b> : deplacer et modifier le fichier params/config.conf, switches.xml, avant toute chose. <br/>(definissez DISABLE_FIRST_RUN_WARNINGS=1 dans config.conf file)
		
                        <hr>
     		</p>"); 
}

if (!isset($config_values['TIME_LIMIT'])){
	set_time_limit(60);
} else {
	set_time_limit((int) $config_values['TIME_LIMIT']);
}

function setStringConfigParameter($name,$value=NULL,$default_value="",$show_warning=1){
	/** 	defines a constant $name with string value $value
		default value is set if not found in config.conf file
	*/

	if(!isset($name)){
		die("ERROR : a name must be provided !");
	}

	if (!isset($value) || $value == ""){
		if(isset($show_warning) && $show_warning==1){
			echo("warning : default value set for $name --> $default_value <br />");
		}
		define($name,$default_value);
	}
	else {
		define($name,(string) $value);
	}
	return 1;
}

function setIntConfigParameter($name,$value=NULL,$default_value="",$show_warning=1){
	/** 	defines a constant $name with int value $value
		default value is set if not found in config.conf file
	*/

	if(!isset($name)){
		die("ERROR : a name must be provided !");
	}
	if (!isset($value) || $value == ""){
		if(isset($show_warning) && $show_warning==1){
			echo("warning : default value set for $name --> $default_value<br />");
		}
		define($name,$default_value);
	}
	else {
		define($name,(int) $value);
	}
	return 1;
}

$show_warning = (int) $config_values['SHOW_WARNING_WHEN_DEFAULT_VALUE_IS_USED'];

setStringConfigParameter('APPLI_FOLDER_NAME',$config_values['APPLI_FOLDER_NAME'],"hp_vlan_admin",$show_warning);
setStringConfigParameter('SUPPORT_ADDRESS',$config_values['SUPPORT_ADDRESS'],"http://sourceforge.net/projects/procurve-admin/forums/forum/1233992",$show_warning);
setStringConfigParameter('OS_TYPE',$config_values['OS_TYPE'],"UNIX",$show_warning);
setStringConfigParameter('LANGUAGE',$config_values['LANGUAGE'],"en",$show_warning);
setIntConfigParameter('ENABLE_COPYRIGHT_BOX',$config_values['ENABLE_COPYRIGHT_BOX'],1,$show_warning);
setStringConfigParameter('TITLE',$config_values['TITLE'],"HP Vlan Simple Administration",$show_warning);
setStringConfigParameter('FOOTER',$config_values['FOOTER'],"YOUR COMPAGNY",$show_warning);
setIntConfigParameter('ENABLE_FOOTER_SUPPORT_LINK',$config_values['ENABLE_FOOTER_SUPPORT_LINK'],1,$show_warning);
setIntConfigParameter('HIDE_DETAILS_BOX',$config_values['HIDE_DETAILS_BOX'],1,$show_warning);
setIntConfigParameter('DISABLE_DETAILS_BOX',$config_values['DISABLE_DETAILS_BOX'],0,$show_warning);
setStringConfigParameter('UP_TAGGED_PORT_COLOR',$config_values['UP_TAGGED_PORT_COLOR'],"blue",$show_warning);
setStringConfigParameter('DOWN_TAGGED_PORT_COLOR',$config_values['DOWN_TAGGED_PORT_COLOR'],"black",$show_warning);
setStringConfigParameter('UP_UNTAGGED_PORT_COLOR',$config_values['UP_UNTAGGED_PORT_COLOR'],"green",$show_warning);
setStringConfigParameter('DOWN_UNTAGGED_PORT_COLOR',$config_values['DOWN_UNTAGGED_PORT_COLOR'],"red",$show_warning);
setStringConfigParameter('UP_TAGGED_PORT_BTN',$config_values['UP_TAGGED_PORT_BTN'],"btn-primary",$show_warning);
setStringConfigParameter('DOWN_TAGGED_PORT_BTN',$config_values['DOWN_TAGGED_PORT_BTN'],"btn-secondary",$show_warning);
setStringConfigParameter('UP_UNTAGGED_PORT_BTN',$config_values['UP_UNTAGGED_PORT_BTN'],"btn-success",$show_warning);
setStringConfigParameter('DOWN_UNTAGGED_PORT_BTN',$config_values['DOWN_UNTAGGED_PORT_BTN'],"btn-danger",$show_warning);	
setIntConfigParameter('DISPLAY_DASHBOARD',$config_values['DISPLAY_DASHBOARD'],1,$show_warning);	
setIntConfigParameter('SET_DASHBOARD_AS_MAIN_PAGE',$config_values['SET_DASHBOARD_AS_MAIN_PAGE'],1,$show_warning);
setIntConfigParameter('DISPLAY_PHP_WARNINGS',$config_values['DISPLAY_PHP_WARNINGS'],0,$show_warning);
setIntConfigParameter('SNMP_DEFAULT_VERSION',$config_values['SNMP_DEFAULT_VERSION'],1,$show_warning);
setStringConfigParameter('SNMP_V3_DEFAULT_USER',$config_values['SNMP_V3_DEFAULT_USER'],"initial",$show_warning);
setStringConfigParameter('SNMP_V3_DEFAULT_PASSPHRASE',$config_values['SNMP_V3_DEFAULT_PASSPHRASE'],"public_passphrase",$show_warning);
setStringConfigParameter('SNMP_V3_DEFAULT_SEC_LEVEL',$config_values['SNMP_V3_DEFAULT_SEC_LEVEL'],"authPriv",$show_warning);
setStringConfigParameter('SNMP_V3_DEFAULT_AUTH_PROTOCOL',$config_values['SNMP_V3_DEFAULT_AUTH_PROTOCOL'],"MD5",$show_warning);
setStringConfigParameter('SNMP_V3_DEFAULT_PRIV_PROTOCOL',$config_values['SNMP_V3_DEFAULT_PRIV_PROTOCOL'],"DES",$show_warning);
setStringConfigParameter('SNMP_V3_DEFAULT_PRIV_PASSPHRASE',$config_values['SNMP_V3_DEFAULT_PRIV_PASSPHRASE'],"private_passphrase",$show_warning);
setStringConfigParameter('COMMUNITY_DEFAULT',$config_values['COMMUNITY_DEFAULT'],"private",$show_warning);
setIntConfigParameter('LOCAL_AUTHENTICATION',$config_values['LOCAL_AUTHENTICATION'],0,$show_warning);
setStringConfigParameter('LOCAL_USER',$config_values['LOCAL_USER'],"admin",$show_warning);
setStringConfigParameter('LOCAL_PASSWORD',$config_values['LOCAL_PASSWORD'],"admin",$show_warning);
setIntConfigParameter('AD_ACTIVE',$config_values['AD_ACTIVE'],0,$show_warning);
setIntConfigParameter('SHOW_TAGGED_PORTS',$config_values['SHOW_TAGGED_PORTS'],1,$show_warning);
setIntConfigParameter('SHOW_SWITCH_IP_MAIN_MENU',$config_values['SHOW_SWITCH_IP_MAIN_MENU'],1,$show_warning);
setIntConfigParameter('SHOW_UNTAGGED_PORTS',$config_values['SHOW_UNTAGGED_PORTS'],1,$show_warning);
setIntConfigParameter('ALLOW_VLAN_EDITION',$config_values['ALLOW_VLAN_EDITION'],1,$show_warning);
setIntConfigParameter('ALLOW_VLAN_CREATION',$config_values['ALLOW_VLAN_CREATION'],1,$show_warning);
setIntConfigParameter('ALLOW_VLAN_DELETION',$config_values['ALLOW_VLAN_DELETION'],1,$show_warning);
setIntConfigParameter('ALLOW_PORT_TAGGING',$config_values['ALLOW_PORT_TAGGING'],1,$show_warning);
setStringConfigParameter('ACCOUNT_SUFFIX',$config_values['ACCOUNT_SUFFIX'],"@domaine.pri",$show_warning);
setStringConfigParameter('BASE_DN',$config_values['BASE_DN'],"DC=domaine,DC=pri",$show_warning);
setStringConfigParameter('DOMAIN_CONTROLLERS',$config_values['DOMAIN_CONTROLLERS'],"127.0.0.1",$show_warning);
setStringConfigParameter('AD_USERNAME',$config_values['AD_USERNAME'],"Administrator",$show_warning);
setStringConfigParameter('AD_PASSWORD',$config_values['AD_PASSWORD'],"Password",$show_warning);
setStringConfigParameter('AD_AUTHORIZED_GROUP',$config_values['AD_AUTHORIZED_GROUP'],"IT Service",$show_warning);
setIntConfigParameter('ENABLE_SSH_CONSOLE',$config_values['ENABLE_SSH_CONSOLE'],1,$show_warning);
setIntConfigParameter('ENABLE_TELNET_CONSOLE',$config_values['ENABLE_TELNET_CONSOLE'],1,$show_warning);
setIntConfigParameter('USE_MINDTERM_CONSOLE',$config_values['USE_MINDTERM_CONSOLE'],1,$show_warning);
setIntConfigParameter('USE_JTA_CONSOLE',$config_values['USE_JTA_CONSOLE'],1,$show_warning);
setStringConfigParameter('SSH_DEFAULT_PASSWORD',$config_values['SSH_DEFAULT_PASSWORD'],"myPassword",$show_warning);
setStringConfigParameter('SSH_DEFAULT_USER',$config_values['SSH_DEFAULT_USER'],"manager",$show_warning);
setIntConfigParameter('ENABLE_CONFIGURATION_BACKUP_MANAGEMENT',$config_values['ENABLE_CONFIGURATION_BACKUP_MANAGEMENT'],1,$show_warning);
setIntConfigParameter('SHOW_SWITCHS_IPS_IN_CONF_BACKUP_RESULTS',$config_values['SHOW_SWITCHS_IPS_IN_CONF_BACKUP_RESULTS'],1,$show_warning);
setIntConfigParameter('ENABLE_SWITH_CONFIGURATION_VIEW',$config_values['ENABLE_SWITH_CONFIGURATION_VIEW'],1,$show_warning);
setIntConfigParameter('ENABLE_SWITH_CONFIGURATION_EDITION',$config_values['ENABLE_SWITH_CONFIGURATION_EDITION'],1,$show_warning);
setIntConfigParameter('SHOW_PROGRESS_BAR_DURING_BACKUP',$config_values['SHOW_PROGRESS_BAR_DURING_BACKUP'],1,$show_warning);
setIntConfigParameter('DEFAULT_PLANNED_FOR_BACKUP_VALUE',$config_values['DEFAULT_PLANNED_FOR_BACKUP_VALUE'],0,$show_warning);
setIntConfigParameter('ENCRYPT_SAVED_CONFIGURATION_FILES',$config_values['ENCRYPT_SAVED_CONFIGURATION_FILES'],1,$show_warning);
setIntConfigParameter('CONFIGURATION_FILES_ENCRYPT_KEY',$config_values['CONFIGURATION_FILES_ENCRYPT_KEY'],1,$show_warning);
setIntConfigParameter('EMAIL_NOTIFICATION',$config_values['EMAIL_NOTIFICATION'],1,$show_warning);
setStringConfigParameter('EMAIL_RECIPIENT',$config_values['EMAIL_RECIPIENT'],"my_email@mydomain.dom",$show_warning);
setStringConfigParameter('EMAIL_FROM',$config_values['EMAIL_FROM'],"hp_vlan_admin@mydomain.dom",$show_warning);
setStringConfigParameter('EMAIL_SUBJECT',$config_values['EMAIL_SUBJECT'],"hp vlan admin notification",$show_warning);
setIntConfigParameter('USE_SMTP_SERVER',$config_values['USE_SMTP_SERVER'],1,$show_warning);
setStringConfigParameter('SMTP_SERVER',$config_values['SMTP_SERVER'],"127.0.0.1",$show_warning);
setIntConfigParameter('SMTP_PORT',$config_values['SMTP_PORT'],25,$show_warning);
setIntConfigParameter('USE_PHP_MAILER_LIBRARY',$config_values['USE_PHP_MAILER_LIBRARY'],1,$show_warning);
setIntConfigParameter('USE_SMTP_AUTH',$config_values['USE_SMTP_AUTH'],0,$show_warning);
setStringConfigParameter('SMTP_USERNAME',$config_values['SMTP_USERNAME'],"myuser@mydomain.dom",$show_warning);
setStringConfigParameter('SMTP_PASSWORD',$config_values['SMTP_PASSWORD'],"myPassword",$show_warning);
setIntConfigParameter('SHOW_PORT_ALIASES_INSTEAD_OF_IDS',$config_values['SHOW_PORT_ALIASES_INSTEAD_OF_IDS'],0,$show_warning);
setIntConfigParameter('SHOW_PORT_NAMES_INSTEAD_OF_IDS',$config_values['SHOW_PORT_NAMES_INSTEAD_OF_IDS'],0,$show_warning);
setIntConfigParameter('LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS',$config_values['LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS'],1,$show_warning);

?>
