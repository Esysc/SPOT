<?php

/* 
*
* This program comes with ABSOLUTELY NO WARRANTY.
*
* This is free software, placed under the terms of the GNU
* General Public License, as published by the Free Software
* Foundation.  Please see the file COPYING for details.  */
      

include_once("includes.php");

if(isset($_GET["location"])){
	$location = $_GET["location"];
} else {
    $location = "configs";
}

if(ENABLE_CONFIGURATION_BACKUP_MANAGEMENT){//  backing up/restoration of switches configuration is authorized
    $conf_files_links=array();
  
    if ($handle = opendir($location)) {
		$i=0;
        while (false !== ($entry = readdir($handle))) {
			$values =array("link"=>"","group"=>"","name"=>"");
            if($entry != "." && $entry != '..' && $entry != '.htaccess'){
                if(strrpos($entry,"-clear_text")){
                     $values["link"]=$location."/".$entry;
                } elseif(strrpos($entry,"-encrypted")) {
					$values["link"]="decrypt_file.php?location=".$location.'/'.$entry;
                } else {
					$values["link"]="browse_config_files.php?location=".$location.'/'.$entry;
                }
				$group_links_type[$i]["group"] = false;
				if(strrpos($entry,"-gp")){
					$values["name"] = substr($entry,0,-3);
					$values["group"] = true;
				} else {
					$values["name"] = $entry;
				}
				$conf_files_links[] = $values;
				$i++;
            }
        }
    }
    $smarty->assign("conf_files_links",$conf_files_links);
    $smarty->display('browse_config_files.tpl');
} else {
    die(ACCESS_DENIED);
}

?>
