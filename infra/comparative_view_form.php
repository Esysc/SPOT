<?php
/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	$smarty->assign("mySwitchs",$mySwitchs);
	
	$smarty->display('comparative_view_form.tpl');
	
?>