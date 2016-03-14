<?php
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8; name=\"".$filename."\"\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"; size=\"".$file_size."\";\n";
    $header .= "Content-Transfer-Encoding: base64\n\n".chunk_split(base64_encode($content))."\n";
    $header .= "--".$uid."--";
        if (mail($mailto, $subject, "", $header)) {
        	echo "<h1 id='listing'>mail sent ... OK</h1>"; // or use booleans here
		echo "<h2>Verify your inbox<h2>";
        } else {
        	echo "<h1 id='listing'>mail sent ... ERROR!</h1>";
        }
        }   
?>
