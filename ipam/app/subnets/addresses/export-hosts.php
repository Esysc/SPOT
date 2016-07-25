<?php

/**
 *	Generate hostfile dump for /etc/hosts
 *********************************/


/* functions */
require( dirname(__FILE__) . '/../../../functions/functions.php');

# initialize user object
$Database 	= new Database_PDO;
$User 		= new User ($Database);
$Sections	= new Sections ($Database);
$Subnets	= new Subnets ($Database);
$Addresses	= new Addresses ($Database);
$Tools		= new Tools ($Database);
$Result 	= new Result ();

# verify that user is logged in
$User->check_user_session();


//set filename
$filename = "phpipam_hosts_". date("Y-m-d");
$subnetid = $_GET['subnetId'];
//fetch all addresses with hostname set
$hosts = $Tools->fetch_addresses_subnetid_for_export($subnetid);

//loop
if(sizeof($hosts)>0) {
	//details
	$m=0;
	foreach($hosts as $host) {
		//fetch subnet and section details on change!
		if(@$hosts[$m-1]->subnetId!=$hosts[$m]->subnetId) {
			$subnet  = (array) $Subnets->fetch_subnet(null, $host->subnetId);
			$section = (array) $Sections->fetch_section(null, $subnet['sectionId']);

			//first print subnet and section details
			$res[] = "# $subnet[description] (".$Subnets->transform_to_dotted($subnet['subnet'])."/$subnet[mask]) - $section[description]";
		}

		//than address details
		$diff = 17 - strlen($Subnets->transform_to_dotted($host->ip_addr));	//for print offset
		$diff>0 ? : $diff = 3;												//IPv6 print offset

		$res[] = $Subnets->transform_to_dotted($host->ip_addr).str_repeat(" ", $diff)."$host->dns_name";

		//break
		if($hosts[$m]->subnetId!=@$hosts[$m+1]->subnetId) {
		$res[] = "";
		}

		$m++;		//next index
	}
}

# join content
$content = implode("\n", $res);


# headers
header("Cache-Control: private");
header("Content-Description: File Transfer");
header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename="'. $filename .'"');

print($content);
?>