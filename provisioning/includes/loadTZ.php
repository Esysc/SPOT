
<?php

/* GPL Public license 
 * This script load a complete tz information 
 * The argument is a given TZ, it return POSIX,alias windows and if DST is true
 * 
 * Sysprod - Mycompany */
/**
 * 
 */
require_once "class.xmlresponse.php";
require_once "../libs/Controller/ClassTimeZone.php";
if (isset($_POST['TZ'])) {
$value = urldecode($_POST['TZ']);

$timezones = new timezone();
$results = $timezones->getTimeZones($value);

//  $xml->start();
$xml = new xmlResponse();
$xml->start();


// generate commands in XML format
echo "results";

$xml->command('setstyle', array('target' => "tz_table", 'property' => 'display', 'value' => 'block')
);

$timezone = $value;
$aix_tz = $results['aix_tz'];
$win_tz = $results['win_tz'];
$hoffset = $results['Hoffset'];
$hasdst = $results['dst'];
$xml->command("setcontent", array("target" => "timezone"), array("content" => $timezone)
);
$xml->command("setvalue", array("target" => "aixtz"), array("value" => $aix_tz)
);
$xml->command("setvalue", array("target" => "wintz"), array("value" => $win_tz)
);
$xml->command("setcontent", array("target" => "hoffset"), array("content" => $hoffset)
);
$xml->command("setcontent", array("target" => "hasdst"), array("content" => $hasdst)
);
$xml->command("triggerchange", array("target" => "wintz"), array("value" => $win_tz));

$xml->command("triggerchange", array("target" => "aixtz"), array("value" => $aix_tz));


$xml->end();
}
