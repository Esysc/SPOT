<?php
/*
 *	config.php
 *	
 *
 *	site global configuration
 *
 *	This file is responsible of global vars 
 *
 *
 *	Fell Free to add global vars 
 */


define("SITE_DIR", "/var/www/SPOT");
define("SITE_URL", "http://x.x.x.204");
define("URL_REL", "http://ist.my.compnay.com/cgi-bin/WebObjects/ist.woa/wa/inspectRecord?entityName=Delivery&id=");
define("URL_MEMOS", "http://ist.my.compnay.com/cgi-bin/WebObjects/ISTWebServices.woa/ra/HECustomerReleaseMemos.xml?releaseName=");
define("URL_SHAREPOINT", "http://sharepoint.my.compnay.com/sites/salesandops/sysprod/_layouts/listfeed.aspx?List={F48F4228-AFE9-4DF1-8CEB-386D5712D0A0}");
define("IST_MEMO_DETAIL", "http://ist.my.compnay.com/cgi-bin/WebObjects/ist.woa/wa/inspectRecord?entityName=Memo&id=");
define("IST_MEMO_XML_DETAIL", "http://ist.my.compnay.com/cgi-bin/WebObjects/ISTWebServices.woa/ra/MemoCustomerReleases.xml?memoNumber=");
define("URL_SOL", 'http://ist.my.compnay.com/cgi-bin/WebObjects/ist.woa/wa/inspectRecord?entityName=SolutionRelease&id=');
define("LIB_DIR", "/var/www/SPOT/provisioning/libs/lib");
define("SYSPROD_USER", 'sysprod:***REMOVED***');
define('URL_SYSPRODDB', 'http://sysproddb.my.compnay.com/api/1.0');
define('URL_WEBSYSPRODDB', 'http://sysproddb.my.compnay.com/sales_order.php?page=salesOrderDetails&sales_order_ref=');
define('URL_POSTSYSPRODDB', 'http://sysproddb.my.compnay.com/sales_order.php?page=salesOrderDetails');
define('URL_WEBSYSPRODDB_ORDERS', 'http://sysproddb.my.compnay.com/sales_order.php?page=salesOrders');
define('URL_PACKAGER', 'http://spdrbl01.my.compnay.com/packager');
define("USER_NOT_ALLOWED", 0);
define("PUBLIC_USER_ID", 1);
define("HOTLINE_USERS_ID", 2);
define("SYSLOG_ROOT", 'http://sysproddb.my.compnay.com');



set_include_path(get_include_path() 
        . PATH_SEPARATOR . LIB_DIR.PATH_SEPARATOR . PATH_SEPARATOR . SITE_DIR);

error_reporting(E_ALL);

