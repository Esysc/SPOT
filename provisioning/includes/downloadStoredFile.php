<?php
$attachment_location = $_GET['url'];
$filename = $_GET['filename'];
if (file_exists($attachment_location)) {

    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    header("Cache-Control: public"); // needed for internet explorer
    header("Content-Type: application/text");
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length:".filesize($attachment_location));
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    readfile($attachment_location);
    // Clean from file
    unlink($attachment_location);
    die();
} else {
    die("Error: File not found.");
}