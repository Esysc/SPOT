<?php

/** @package    SPOT */
session_start();


/* GlobalConfig object contains all configuration information for the app */
include_once("_global_config.php");
include_once("_app_config.php");
include_once("_machine_config.php");

if (!GlobalConfig::$CONNECTION_SETTING) {
    throw new Exception('GlobalConfig::$CONNECTION_SETTING is not configured.  Are you missing _machine_config.php?');
}

if (!isset($_SERVER['HTTP_USER_AGENT']))
    $_SERVER['HTTP_USER_AGENT'] = 'unknown';

if (strpos($_SERVER['HTTP_USER_AGENT'], 'perl') === FALSE && strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla/4.0') === FALSE) {


    if (empty(array_intersect($custip, $URL)) && empty(array_intersect($productiondb, $URL)) && ! isset($_SESSION['right'])) {
        if (isset($_SESSION['right'])) {
            switch ($_SESSION['right']) {


                case 2:
                    // header('Location: ./adresses');
                    break;
                case 10:
                    //  header('Location: index.php');  
                    break;
                default:
                    header('Location: login.php');
                    break;
            }
        } else {
            header('Location: login.php');
        }
    } else {
        // Added the logic to enter public pages if rights are not defined already
        if (!isset($_SESSION['right'])) {
            $_SESSION['right'] = 99;
            $_SESSION['login'] = 'guest';
            
        }
    }
    
}


/* require framework libs */
require_once("verysimple/Phreeze/Dispatcher.php");

// the global config is used for all dependency injection
$gc = GlobalConfig::GetInstance();

try {
    Dispatcher::Dispatch(
            $gc->GetPhreezer(), $gc->GetRenderEngine(), '', $gc->GetContext(), $gc->GetRouter()
    );
} catch (exception $ex) {
    // This is the global error handler which will be called in the event of
    // uncaught errors.  If the endpoint appears to be an API request then
    // render it as JSON, otherwise attempt to render a friendly HTML page

    $url = RequestUtil::GetCurrentURL();
    $isApiRequest = (strpos($url, 'api/') !== false);

    if ($isApiRequest) {
        $result = new stdClass();
        $result->success = false;
        $result->message = $ex->getMessage();
        $result->data = $ex->getTraceAsString();

        @header('HTTP/1.1 401 Unauthorized');
        echo json_encode($result);
    } else {
        $gc->GetRenderEngine()->assign("message", $ex->getMessage());
        $gc->GetRenderEngine()->assign("stacktrace", $ex->getTraceAsString());
        $gc->GetRenderEngine()->assign("code", $ex->getCode());

        try {
            $gc->GetRenderEngine()->display("DefaultErrorFatal.tpl");
        } catch (Exception $ex2) {
            // this means there is an error with the template, in which case we can't display it nicely
            echo "<style>* { font-family: verdana, arial, helvetica, sans-serif; }</style>\n";
            echo "<h1>Fatal Error:</h1>\n";
            echo '<h3>' . htmlentities($ex->getMessage()) . "</h3>\n";
            echo "<h4>Original Stack Trace:</h4>\n";
            echo '<textarea wrap="off" style="height: 200px; width: 100%;">' . htmlentities($ex->getTraceAsString()) . '</textarea>';
            echo "<h4>In addition to the above error, the default error template could not be displayed:</h4>\n";
            echo '<textarea wrap="off" style="height: 200px; width: 100%;">' . htmlentities($ex2->getMessage()) . "\n\n" . htmlentities($ex2->getTraceAsString()) . '</textarea>';
        }
    }
}
?>