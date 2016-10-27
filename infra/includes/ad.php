
<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */


	require_once("lib".SYSTEM_PATH_SEPARATOR."adLDAP".SYSTEM_PATH_SEPARATOR."adLDAP.php");
	require_once("lib".SYSTEM_PATH_SEPARATOR."classes".SYSTEM_PATH_SEPARATOR."User.class.php");
	
	if($utilisateur = User::userConnection()){
			if(!$utilisateur->isAdmin()){
				die(AD_ERROR_MESSAGE);
			}
	} else {
		die(AD_ERROR_MESSAGE);
	}

?>
