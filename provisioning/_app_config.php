<?php

/**
 * @package SPOT
 *
 * APPLICATION-WIDE CONFIGURATION SETTINGS
 *
 * This file contains application-wide configuration settings.  The settings
 * here will be the same regardless of the machine on which the app is running.
 *
 * This configuration should be added to version control.
 *
 * No settings should be added to this file that would need to be changed
 * on a per-machine basic (ie local, staging or production).  Any
 * machine-specific settings should be added to _machine_config.php
 */
/**
 * APPLICATION ROOT DIRECTORY
 * If the application doesn't detect this correctly then it can be set explicitly
 */
if (!GlobalConfig::$APP_ROOT)
    GlobalConfig::$APP_ROOT = realpath("./");

/**
 * check is needed to ensure asp_tags is not enabled
 */
if (ini_get('asp_tags'))
    die('<h3>Server Configuration Problem: asp_tags is enabled, but is not compatible with Savant.</h3>'
            . '<p>You can disable asp_tags in .htaccess, php.ini or generate your app with another template engine such as Smarty.</p>');

/**
 * INCLUDE PATH
 * Adjust the include path as necessary so PHP can locate required libraries
 */
set_include_path(
        GlobalConfig::$APP_ROOT . '/libs/' . PATH_SEPARATOR .
        GlobalConfig::$APP_ROOT . '/phreeze/libs' . PATH_SEPARATOR .
        GlobalConfig::$APP_ROOT . '/vendor/phreeze/phreeze/libs/' . PATH_SEPARATOR .
        GlobalConfig::$APP_ROOT . '/includes/' . PATH_SEPARATOR .
        GlobalConfig::$APP_ROOT . '/libs/Controller/inc' . PATH_SEPARATOR .
        get_include_path()
);


/**
 * COMPOSER AUTOLOADER
 * Uncomment if Composer is being used to manage dependencies
 */
// $loader = require 'vendor/autoload.php';
// $loader->setUseIncludePath(true);

/**
 * SESSION CLASSES
 * Any classes that will be stored in the session can be added here
 * and will be pre-loaded on every page
 */
require_once "App/ExampleUser.php";
require_once GlobalConfig::$APP_ROOT . "/includes/share.php";

/**
 * RENDER ENGINE
 * You can use any template system that implements
 * IRenderEngine for the view layer.  Phreeze provides pre-built
 * implementations for Smarty, Savant and plain PHP.
 */
require_once 'verysimple/Phreeze/SavantRenderEngine.php';
GlobalConfig::$SYSPROD_SERVER = new stdClass();
GlobalConfig::$IMAGES = new stdClass();
GlobalConfig::$TEMPLATE_ENGINE = 'SavantRenderEngine';
GlobalConfig::$TEMPLATE_PATH = GlobalConfig::$APP_ROOT . '/templates/';
GlobalConfig::$SYSPROD_SERVER->DRBL = 'x.x.x.203';
GlobalConfig::$SYSPROD_SERVER->NIM = 'x.x.x.25'; //default nim server
GlobalConfig::$SYSPROD_SERVER->NIMA = 'x.x.x.25'; //Node A
GlobalConfig::$SYSPROD_SERVER->NIMB = 'x.x.x.26'; //Node B
//GlobalConfig::$SYSPROD_SERVER->NIMB = 'x.x.x.25'; //Node B Temporarely set to defalt one ( if problem )
GlobalConfig::$SYSPROD_SERVER->MDT = 'x.x.x.228';
GlobalConfig::$SYSPROD_SERVER->MGT = 'x.x.x.204';
GlobalConfig::$IMAGES->LYSIS = '/packager/products/shos_HP';
GlobalConfig::$APP_VERSION = '2.0';
/**
 * ROUTE MAP
 * The route map connects URLs to Controller+Method and additionally maps the
 * wildcards to a named parameter so that they are accessible inside the
 * Controller without having to parse the URL for parameters such as IDs
 */
GlobalConfig::$ROUTE_MAP = array(
    // default controller when no route specified
    // 'GET:' => array('route' => 'Provisioningnotifications.ListView'),
    'GET:' => array('route' => 'Charts.ChartsView'),
    //Change username or password route:
    'GET:change' => array('route' => 'Change.View'),
    'POST:change' => array('route' => 'Change.View'),
    // example authentication routes
    'GET:loginform' => array('route' => 'SecureExample.LoginForm'),
    'POST:login' => array('route' => 'SecureExample.Login'),
    'GET:secureuser' => array('route' => 'SecureExample.UserPage'),
    'GET:secureadmin' => array('route' => 'SecureExample.AdminPage'),
    'GET:logout' => array('route' => 'SecureExample.Logout'),
    // Provisionningos
    'GET:provisionningoss' => array('route' => 'Provisionningos.ListView'),
    'GET:provisionningos/(:any)' => array('route' => 'Provisionningos.SingleView', 'params' => array('sname' => 1)),
    'GET:api/provisionningoss' => array('route' => 'Provisionningos.Query'),
    'POST:api/provisionningos' => array('route' => 'Provisionningos.Create'),
    'GET:api/provisionningos/(:any)' => array('route' => 'Provisionningos.Read', 'params' => array('sname' => 2)),
    'PUT:api/provisionningos/(:any)' => array('route' => 'Provisionningos.Update', 'params' => array('sname' => 2)),
    'DELETE:api/provisionningos/(:any)' => array('route' => 'Provisionningos.Delete', 'params' => array('sname' => 2)),
    // Configtemplate
    'GET:configtemplates' => array('route' => 'Configtemplate.ListView'),
    'GET:configtemplate/(:any)' => array('route' => 'Configtemplate.SingleView', 'params' => array('versionId' => 1)),
    'GET:api/configtemplates' => array('route' => 'Configtemplate.Query'),
    'POST:api/configtemplate' => array('route' => 'Configtemplate.Create'),
    'GET:api/configtemplate/(:any)' => array('route' => 'Configtemplate.Read', 'params' => array('versionId' => 2)),
    'PUT:api/configtemplate/(:any)' => array('route' => 'Configtemplate.Update', 'params' => array('versionId' => 2)),
    'DELETE:api/configtemplate/(:any)' => array('route' => 'Configtemplate.Delete', 'params' => array('versionId' => 2)),
    // Customconfig
    'GET:customconfigsbuilder' => array('route' => 'Customconfig.FormView'),
    'GET:customconfigs' => array('route' => 'Customconfig.ListView'),
    'GET:customconfig/(:num)' => array('route' => 'Customconfig.SingleView', 'params' => array('configId' => 1)),
    'GET:api/customconfigs' => array('route' => 'Customconfig.Query'),
    'POST:api/customconfig' => array('route' => 'Customconfig.Create'),
    'GET:api/customconfig/(:num)' => array('route' => 'Customconfig.Read', 'params' => array('configId' => 2)),
    'PUT:api/customconfig/(:num)' => array('route' => 'Customconfig.Update', 'params' => array('configId' => 2)),
    'DELETE:api/customconfig/(:num)' => array('route' => 'Customconfig.Delete', 'params' => array('configId' => 2)),
    // Create CVS page (for old production DB)
    'GET:createcsv' => array('route' => 'Default.CreateCsv'),
    // Set specifications (generic one, free data to insert no DB related)
    'GET:setspecs' => array('route' => 'Default.setSpecsDb'), // SEt Assembled on all items globally
    'GET:setassgly' => array('route' => 'Default.setAssemblyGly'),
    //Run commander
    'GET:commander' => array('route' => 'Default.runCommander'),
    //Give the route for the log wrapper
    
    'POST:api/logWrapper/(:num)/(:any)' => array('route' => 'Default.logWrapper', 'params' => array('remotecommandid' => 2, 'copy' => 3)),
    
// Networkequipment
    'GET:networkequipments' => array('route' => 'Networkequipment.ListView'),
    'GET:networkequipment/(:num)' => array('route' => 'Networkequipment.SingleView', 'params' => array('equipId' => 1)),
    'GET:api/networkequipments' => array('route' => 'Networkequipment.Query'),
    'POST:api/networkequipment' => array('route' => 'Networkequipment.Create'),
    'GET:api/networkequipment/(:num)' => array('route' => 'Networkequipment.Read', 'params' => array('equipId' => 2)),
    'PUT:api/networkequipment/(:num)' => array('route' => 'Networkequipment.Update', 'params' => array('equipId' => 2)),
    'DELETE:api/networkequipment/(:num)' => array('route' => 'Networkequipment.Delete', 'params' => array('equipId' => 2)),
    // Events
    'GET:eventses' => array('route' => 'Events.ListView'),
    'GET:events/(:num)' => array('route' => 'Events.SingleView', 'params' => array('id' => 1)),
    'GET:api/eventses' => array('route' => 'Events.Query'),
    'POST:api/events' => array('route' => 'Events.Create'),
    'GET:api/events/(:num)' => array('route' => 'Events.Read', 'params' => array('id' => 2)),
    'PUT:api/events/(:num)' => array('route' => 'Events.Update', 'params' => array('id' => 2)),
    'DELETE:api/events/(:num)' => array('route' => 'Events.Delete', 'params' => array('id' => 2)),
    // Eventcategory
    'GET:eventcategories' => array('route' => 'Eventcategory.ListView'),
    'GET:eventcategory/(:any)' => array('route' => 'Eventcategory.SingleView', 'params' => array('category' => 1)),
    'GET:api/eventcategories' => array('route' => 'Eventcategory.Query'),
    'POST:api/eventcategory' => array('route' => 'Eventcategory.Create'),
    'GET:api/eventcategory/(:any)' => array('route' => 'Eventcategory.Read', 'params' => array('category' => 2)),
    'PUT:api/eventcategory/(:any)' => array('route' => 'Eventcategory.Update', 'params' => array('category' => 2)),
    'DELETE:api/eventcategory/(:any)' => array('route' => 'Eventcategory.Delete', 'params' => array('category' => 2)),
    // Executionflagcodes
    'GET:executionflagcodeses' => array('route' => 'Executionflagcodes.ListView'),
    'GET:executionflagcodes/(:any)' => array('route' => 'Executionflagcodes.SingleView', 'params' => array('executionflag' => 1)),
    'GET:api/executionflagcodeses' => array('route' => 'Executionflagcodes.Query'),
    'POST:api/executionflagcodes' => array('route' => 'Executionflagcodes.Create'),
    'GET:api/executionflagcodes/(:any)' => array('route' => 'Executionflagcodes.Read', 'params' => array('executionflag' => 2)),
    'PUT:api/executionflagcodes/(:any)' => array('route' => 'Executionflagcodes.Update', 'params' => array('executionflag' => 2)),
    'DELETE:api/executionflagcodes/(:any)' => array('route' => 'Executionflagcodes.Delete', 'params' => array('executionflag' => 2)),
    // MonOrderscurrent
    'GET:monorderscurrents' => array('route' => 'MonOrderscurrent.ListView'),
    'GET:monorderscurrent/(:num)' => array('route' => 'MonOrderscurrent.SingleView', 'params' => array('id' => 1)),
    'GET:api/monorderscurrents' => array('route' => 'MonOrderscurrent.Query'),
    'POST:api/monorderscurrent' => array('route' => 'MonOrderscurrent.Create'),
    'GET:api/monorderscurrent/(:num)' => array('route' => 'MonOrderscurrent.Read', 'params' => array('id' => 2)),
    'PUT:api/monorderscurrent/(:num)' => array('route' => 'MonOrderscurrent.Update', 'params' => array('id' => 2)),
    'DELETE:api/monorderscurrent/(:num)' => array('route' => 'MonOrderscurrent.Delete', 'params' => array('id' => 2)),
    // MonOrdersstatus
    'GET:monordersstatuses' => array('route' => 'MonOrdersstatus.ListView'),
    'GET:monordersstatus/(:num)' => array('route' => 'MonOrdersstatus.SingleView', 'params' => array('id' => 1)),
    'GET:api/monordersstatuses' => array('route' => 'MonOrdersstatus.Query'),
    'POST:api/monordersstatus' => array('route' => 'MonOrdersstatus.Create'),
    'GET:api/monordersstatus/(:num)' => array('route' => 'MonOrdersstatus.Read', 'params' => array('id' => 2)),
    'PUT:api/monordersstatus/(:num)' => array('route' => 'MonOrdersstatus.Update', 'params' => array('id' => 2)),
    'DELETE:api/monordersstatus/(:num)' => array('route' => 'MonOrdersstatus.Delete', 'params' => array('id' => 2)),
    // Jobtostart
    'GET:jobtostarts' => array('route' => 'Jobtostart.ListView'),
    'GET:jobtostart/(:any)' => array('route' => 'Jobtostart.SingleView', 'params' => array('scriptid' => 1)),
    'GET:api/jobtostarts' => array('route' => 'Jobtostart.Query'),
    'POST:api/jobtostart' => array('route' => 'Jobtostart.Create'),
    'GET:api/jobtostart/(:any)' => array('route' => 'Jobtostart.Read', 'params' => array('scriptid' => 2)),
    'PUT:api/jobtostart/(:any)' => array('route' => 'Jobtostart.Update', 'params' => array('scriptid' => 2)),
    'DELETE:api/jobtostart/(:any)' => array('route' => 'Jobtostart.Delete', 'params' => array('scriptid' => 2)),
    // Mediatype
    'GET:mediatypes' => array('route' => 'Mediatype.ListView'),
    'GET:mediatype/(:num)' => array('route' => 'Mediatype.SingleView', 'params' => array('id' => 1)),
    'GET:api/mediatypes' => array('route' => 'Mediatype.Query'),
    'POST:api/mediatype' => array('route' => 'Mediatype.Create'),
    'GET:api/mediatype/(:num)' => array('route' => 'Mediatype.Read', 'params' => array('id' => 2)),
    'PUT:api/mediatype/(:num)' => array('route' => 'Mediatype.Update', 'params' => array('id' => 2)),
    'DELETE:api/mediatype/(:num)' => array('route' => 'Mediatype.Delete', 'params' => array('id' => 2)),
    // Networks
    'GET:networkses' => array('route' => 'Networks.ListView'),
    'GET:networks/(:any)' => array('route' => 'Networks.SingleView', 'params' => array('salesorder' => 1)),
    'GET:api/networkses' => array('route' => 'Networks.Query'),
    'POST:api/networks' => array('route' => 'Networks.Create'),
    'GET:api/networks/(:any)' => array('route' => 'Networks.Read', 'params' => array('salesorder' => 2)),
    'PUT:api/networks/(:any)' => array('route' => 'Networks.Update', 'params' => array('salesorder' => 2)),
    'DELETE:api/networks/(:any)' => array('route' => 'Networks.Delete', 'params' => array('salesorder' => 2)),
    // Pendings
    // 'GET:pendings' => array('route' => 'Pendings.View'),
    'GET:pendings' => array('route' => 'Pendings.SharepointView'),
    // Scheduled
    'GET:scheduled' => array('route' => 'Pendings.SharepointScheduled'),
    // Production monitoring
    'GET:pmon' => array('route' => 'Default.PMon'),
    // Provisioning
    'GET:provisionings' => array('route' => 'Provisioning.ListView'),
    'GET:provisioning/(:any)' => array('route' => 'Provisioning.SingleView', 'params' => array('provisioningid' => 1)),
    'GET:api/provisionings' => array('route' => 'Provisioning.Query'),
    'POST:api/provisioning' => array('route' => 'Provisioning.Create'),
    'GET:api/provisioning/(:any)' => array('route' => 'Provisioning.Read', 'params' => array('provisioningid' => 2)),
    'PUT:api/provisioning/(:any)' => array('route' => 'Provisioning.Update', 'params' => array('provisioningid' => 2)),
    'DELETE:api/provisioning/(:any)' => array('route' => 'Provisioning.Delete', 'params' => array('provisioningid' => 2)),
    // Provisioningaction
    'GET:provisioning1' => array('route' => 'Provisioningaction.GeneralView'),
    'GET:provisioning2' => array('route' => 'Provisioningaction.Phase2View'),
    'GET:reload' => array('route' => 'Provisioningaction.ReloadGen'),
    'GET:maintdiag' => array('route' => 'Provisioningaction.DiagMaint'),
    'GET:provisioningactions' => array('route' => 'Provisioningaction.ListView'),
    'GET:provisioningaction/(:num)' => array('route' => 'Provisioningaction.SingleView', 'params' => array('actionid' => 1)),
    'GET:api/provisioningactions' => array('route' => 'Provisioningaction.Query'),
    'POST:api/provisioningaction' => array('route' => 'Provisioningaction.Create'),
    'GET:api/provisioningaction/(:num)' => array('route' => 'Provisioningaction.Read', 'params' => array('actionid' => 2)),
    'PUT:api/provisioningaction/(:num)' => array('route' => 'Provisioningaction.Update', 'params' => array('actionid' => 2)),
    'DELETE:api/provisioningaction/(:num)' => array('route' => 'Provisioningaction.Delete', 'params' => array('actionid' => 2)),
    // Provisioningimages
    'GET:provisioningimageses' => array('route' => 'Provisioningimages.ListView'),
    'GET:provisioningimages/(:any)' => array('route' => 'Provisioningimages.SingleView', 'params' => array('imagename' => 1), 'ostarget' => 1, 'imagetarget' => 1),
    'GET:api/provisioningimageses' => array('route' => 'Provisioningimages.Query'),
    'POST:api/provisioningimages' => array('route' => 'Provisioningimages.Create'),
    'GET:api/provisioningimages/(:any)' => array('route' => 'Provisioningimages.Read', 'params' => array('imagename' => 2)),
    'PUT:api/provisioningimages/(:any)' => array('route' => 'Provisioningimages.Update', 'params' => array('imagename' => 2)),
    'DELETE:api/provisioningimages/(:any)' => array('route' => 'Provisioningimages.Delete', 'params' => array('imagename' => 2)),
    // Provisioningnotifications
    'GET:provisioningnotificationses' => array('route' => 'Provisioningnotifications.ListView'),
    'GET:provisioningnotifications/(:any)' => array('route' => 'Provisioningnotifications.Update', 'params' => array('notifid' => 2)),
    'GET:api/provisioningnotificationses' => array('route' => 'Provisioningnotifications.Query'),
    'POST:api/provisioningnotifications' => array('route' => 'Provisioningnotifications.Create'),
    'GET:api/provisioningnotifications/(:any)' => array('route' => 'Provisioningnotifications.Read', 'params' => array('notifid' => 2)),
    'PUT:api/provisioningnotifications/(:any)' => array('route' => 'Provisioningnotifications.Update', 'params' => array('notifid' => 2)),
    'DELETE:api/provisioningnotifications/(:any)' => array('route' => 'Provisioningnotifications.Delete', 'params' => array('notifid' => 2)),
    // Set IP ALIAS
    'GET:setipalias' => array('route' => 'Setipalias.ListView'),
    // // TblPassword
    'GET:tblpasswords' => array('route' => 'TblPassword.ListView'),
    'GET:tblpassword/(:any)' => array('route' => 'TblPassword.SingleView', 'params' => array('salesorder' => 1)),
    'GET:api/tblpasswords' => array('route' => 'TblPassword.Query'),
    'POST:api/tblpassword' => array('route' => 'TblPassword.Create'),
    'GET:api/tblpassword/(:any)' => array('route' => 'TblPassword.Read', 'params' => array('salesorder' => 2)),
    'PUT:api/tblpassword/(:any)' => array('route' => 'TblPassword.Update', 'params' => array('salesorder' => 2)),
    'DELETE:api/tblpassword/(:any)' => array('route' => 'TblPassword.Delete', 'params' => array('salesorder' => 2)),
    // Customize passwords
    'GET:setpasswords' => array('route' => 'TblPassword.SetForm'),
    // Provisioningscripts
    'GET:provisioningscriptses' => array('route' => 'Provisioningscripts.ListView'),
    'GET:provisioningscripts/(:any)' => array('route' => 'Provisioningscripts.SingleView', 'params' => array('scriptid' => 1)),
    'GET:api/provisioningscriptses' => array('route' => 'Provisioningscripts.Query'),
    'POST:api/provisioningscripts' => array('route' => 'Provisioningscripts.Create'),
    'GET:api/provisioningscripts/(:any)' => array('route' => 'Provisioningscripts.Read', 'params' => array('scriptid' => 2)),
    'PUT:api/provisioningscripts/(:any)' => array('route' => 'Provisioningscripts.Update', 'params' => array('scriptid' => 2)),
    'DELETE:api/provisioningscripts/(:any)' => array('route' => 'Provisioningscripts.Delete', 'params' => array('scriptid' => 2)),
    // Remotecommands
    'GET:remotecommandses' => array('route' => 'Remotecommands.ListView'),
    'GET:remotecommands/(:num)' => array('route' => 'Remotecommands.SingleView', 'params' => array('remotecommandid' => 1)),
    'GET:api/remotecommandses' => array('route' => 'Remotecommands.Query'),
    'POST:api/remotecommands' => array('route' => 'Remotecommands.Create'),
    'GET:api/remotecommands/(:num)' => array('route' => 'Remotecommands.Read', 'params' => array('remotecommandid' => 2)),
    'PUT:api/remotecommands/(:num)' => array('route' => 'Remotecommands.Update', 'params' => array('remotecommandid' => 2)),
    'PUT:api/remotecommandses' => array('route' => 'Remotecommands.Update'),
    'DELETE:api/remotecommands/(:num)' => array('route' => 'Remotecommands.Delete', 'params' => array('remotecommandid' => 2)),
    // Sysprodracks
    'GET:sysprodrackses' => array('route' => 'Sysprodracks.ListView'),
    'GET:sysprodracks/(:any)' => array('route' => 'Sysprodracks.SingleView', 'params' => array('idracks' => 1)),
    'GET:api/sysprodrackses' => array('route' => 'Sysprodracks.Query'),
    'POST:api/sysprodracks' => array('route' => 'Sysprodracks.Create'),
    'GET:api/sysprodracks/(:any)' => array('route' => 'Sysprodracks.Read', 'params' => array('idracks' => 2)),
    'PUT:api/sysprodracks/(:any)' => array('route' => 'Sysprodracks.Update', 'params' => array('idracks' => 2)),
    'DELETE:api/sysprodracks/(:any)' => array('route' => 'Sysprodracks.Delete', 'params' => array('idracks' => 2)),
    // Sysprodracksmapping
    'GET:sysprodracksmappings' => array('route' => 'Sysprodracksmapping.ListView'),
    'GET:sysprodracksmapping/(:any)' => array('route' => 'Sysprodracksmapping.SingleView', 'params' => array('clientid' => 1)),
    'GET:api/sysprodracksmappings' => array('route' => 'Sysprodracksmapping.Query'),
    'POST:api/sysprodracksmapping' => array('route' => 'Sysprodracksmapping.Create'),
    'GET:api/sysprodracksmapping/(:any)' => array('route' => 'Sysprodracksmapping.Read', 'params' => array('clientid' => 2)),
    'PUT:api/sysprodracksmapping/(:any)' => array('route' => 'Sysprodracksmapping.Update', 'params' => array('clientid' => 2)),
    'DELETE:api/sysprodracksmapping/(:any)' => array('route' => 'Sysprodracksmapping.Delete', 'params' => array('clientid' => 2)),
    // Tblprogress
    'GET:tblprogresses' => array('route' => 'Tblprogress.ListView'),
    'GET:tblprogress/(:num)' => array('route' => 'Tblprogress.SingleView', 'params' => array('id' => 1)),
    'GET:api/tblprogresses' => array('route' => 'Tblprogress.Query'),
    'POST:api/tblprogress' => array('route' => 'Tblprogress.Create'),
    'GET:api/tblprogress/(:num)' => array('route' => 'Tblprogress.Read', 'params' => array('id' => 2)),
    'PUT:api/tblprogress/(:num)' => array('route' => 'Tblprogress.Update', 'params' => array('id' => 2)),
    //'PUT:api/tblprogress' => array('route' => 'Tblprogress.Update'),
    'DELETE:api/tblprogress/(:num)' => array('route' => 'Tblprogress.Delete', 'params' => array('id' => 2)),
    // TBL completed
    'GET:tblcompleted' => array('route' => 'Tblprogress.CompletedView'),
    // Tempdata
    'GET:tempdatas' => array('route' => 'Tempdata.ListView'),
    'GET:tempdata/(:any)' => array('route' => 'Tempdata.SingleView', 'params' => array('salesorder' => 1)),
    'GET:api/tempdatas' => array('route' => 'Tempdata.Query'),
    'POST:api/tempdata' => array('route' => 'Tempdata.Create'),
    'GET:api/tempdata/(:any)' => array('route' => 'Tempdata.Read', 'params' => array('salesorder' => 2)),
    'PUT:api/tempdata/(:any)' => array('route' => 'Tempdata.Update', 'params' => array('salesorder' => 2)),
    'DELETE:api/tempdata/(:any)' => array('route' => 'Tempdata.Delete', 'params' => array('salesorder' => 2)),
//DHCP/BOOTP list view
    'GET:dhcpmap' => array('route' => 'Dhcpbootpinv.ListView'),
    'GET:api/dhcpbootpinvs' => array('route' => 'Dhcpbootpinv.Query'),
    // Pxe clients inventory   
    'GET:pxeinv' => array('route' => 'Tempdata.PxelistView'),
    // Get MEMOS for releases
    'GET:memos' => array('route' => 'Tempdata.MemoView'),
// Users
    'GET:userses' => array('route' => 'Users.ListView'),
    'GET:users/(:num)' => array('route' => 'Users.SingleView', 'params' => array('uId' => 1)),
    'GET:api/userses' => array('route' => 'Users.Query'),
    'POST:api/users' => array('route' => 'Users.Create'),
    'GET:api/users/(:num)' => array('route' => 'Users.Read', 'params' => array('uId' => 2)),
    'PUT:api/users/(:num)' => array('route' => 'Users.Update', 'params' => array('uId' => 2)),
    'DELETE:api/users/(:num)' => array('route' => 'Users.Delete', 'params' => array('uId' => 2)),
    // Customer_Ip_Inventory
    'GET:adresses' => array('route' => 'Customer_Ip_Inventory.ListView'),
    'GET:customer_ip_inventory/(:num)' => array('route' => 'Customer_Ip_Inventory.SingleView', 'params' => array('custipid' => 1)),
    //this line is for exscel export
    'GET:api/exportIP' => array('route' => 'Customer_Ip_Inventory.Export'),
    'GET:api/adresses' => array('route' => 'Customer_Ip_Inventory.Query'),
    'POST:api/customer_ip_inventory' => array('route' => 'Customer_Ip_Inventory.Create'),
    'GET:api/customer_ip_inventory/(:num)' => array('route' => 'Customer_Ip_Inventory.Read', 'params' => array('custipid' => 2)),
    'PUT:api/customer_ip_inventory/(:num)' => array('route' => 'Customer_Ip_Inventory.Update', 'params' => array('custipid' => 2)),
    'DELETE:api/customer_ip_inventory/(:num)' => array('route' => 'Customer_Ip_Inventory.Delete', 'params' => array('custipid' => 2)),
    // HotlineSyncDate
    'GET:hotline' => array('route' => 'HotlineSyncDate.ListView'),
    'GET:hotlinesyncdate/(:num)' => array('route' => 'HotlineSyncDate.SingleView', 'params' => array('id' => 1)),
    'GET:api/hotline' => array('route' => 'HotlineSyncDate.Query'),
    'POST:api/hotlinesyncdate' => array('route' => 'HotlineSyncDate.Create'),
    'GET:api/hotlinesyncdate/(:num)' => array('route' => 'HotlineSyncDate.Read', 'params' => array('id' => 2)),
    'PUT:api/hotlinesyncdate/(:num)' => array('route' => 'HotlineSyncDate.Update', 'params' => array('id' => 2)),
    'DELETE:api/hotlinesyncdate/(:num)' => array('route' => 'HotlineSyncDate.Delete', 'params' => array('id' => 2)),
    // IP_valid_ranges
    'GET:ranges' => array('route' => 'IP_valid_ranges.ListView'),
    'GET:ip_valid_ranges/(:num)' => array('route' => 'IP_valid_ranges.SingleView', 'params' => array('id' => 1)),
    'GET:api/ranges' => array('route' => 'IP_valid_ranges.Query'),
    'POST:api/ip_valid_ranges' => array('route' => 'IP_valid_ranges.Create'),
    'GET:api/ip_valid_ranges/(:num)' => array('route' => 'IP_valid_ranges.Read', 'params' => array('id' => 2)),
    'PUT:api/ip_valid_ranges/(:num)' => array('route' => 'IP_valid_ranges.Update', 'params' => array('id' => 2)),
    'DELETE:api/ip_valid_ranges/(:num)' => array('route' => 'IP_valid_ranges.Delete', 'params' => array('id' => 2)),
    //Charts route
    'GET:charts' => array('route' => 'Charts.ChartsView'),
    'GET:stats' => array('route' => 'Charts.StatsView'),
    'POST:stats' => array('route' => 'Charts.StatsView'),
    'POST:stats/(:any)' => array('route' => 'Charts.StatsView'),
    'GET:stats/(:any)' => array('route' => 'Charts.StatsView', 'params' => array('YEAR' => 1)),
    //SubnetCalculator
    'GET:subnetcalculator' => array('route' => 'Charts.SubnetView'),
    'POST:subnetcalculator' => array('route' => 'Charts.SubnetView'),
    //RandomPassword generator
    'GET:randompass' => array('route' => 'Charts.RandomPass'),
    'POST:randompass' => array('route' => 'Charts.RandomPass'),
    //Set extended attr on production DB
    'GET:sysproddb' => array('route' => 'Sysproddb.FormView'),
    //Assemble childs and parents on orders stored in sysprodDB
    'GET:treebuilder' => array('route' => 'Sysproddb.TreeBuilder'),
    // Get cvsweb netwrok sysprod
    'GET:cvsweb' => array('route' => 'Default.Cvsweb'),
    // InstallModules, forst version
    'GET:instmod' => array('route' => 'Default.InstallModules'),
    // InstallPuppet, first version
    'GET:instpup' => array('route' => 'Default.InstallPuppet'),
    // Generate hosts file view
    'GET:hosts' => array('route' => 'Default.GenerateHostsFile'),
    //wrapper to connect to pld productionDB
    // Tblorders
    
    'GET:tblorderses' => array('route' => 'Tblorders.ListView'),
    'GET:tblorders/(:any)' => array('route' => 'Tblorders.SingleView', 'params' => array('salesorder' => 1)),
    'GET:api/tblorderses' => array('route' => 'Tblorders.Query'),
    'POST:api/tblorders' => array('route' => 'Tblorders.Create'),
    'GET:api/tblorders/(:any)' => array('route' => 'Tblorders.Read', 'params' => array('salesorder' => 2)),
    'PUT:api/tblorders/(:any)' => array('route' => 'Tblorders.Update', 'params' => array('salesorder' => 2)),
    'DELETE:api/tblorders/(:any)' => array('route' => 'Tblorders.Delete', 'params' => array('salesorder' => 2)),
    // catch any broken API urls
    'GET:api/(:any)' => array('route' => 'Default.ErrorApi404'),
    'PUT:api/(:any)' => array('route' => 'Default.ErrorApi404'),
    'POST:api/(:any)' => array('route' => 'Default.ErrorApi404'),
    'DELETE:api/(:any)' => array('route' => 'Default.ErrorApi404')
);

/**
 * FETCHING STRATEGY
 * You may uncomment any of the lines below to specify always eager fetching.
 * Alternatively, you can copy/paste to a specific page for one-time eager fetching
 * If you paste into a controller method, replace $G_PHREEZER with $this->Phreezer
 */
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Networks","so",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Notifications","category",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Notifications","userID",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Orders","fk_userid_order",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Orderslog","fk_userid",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Provisioningimages","fk_imageTarget",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
 //$GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Provisioningnotifications","userExist",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Provisioningscripts","scriptTarget",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Remotecommands","fk_execFlag",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Remotecommands","scriptID",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Tempdata","FK__users",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY