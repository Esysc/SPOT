<?php
function notify_basic($recipient,$subject,$message_txt){

	ini_set("SMTP",SMTP_SERVER );
	ini_set('sendmail_from', EMAIL_FROM); 
	
	$cr = "\r\n";

	$boundary = "-----=".md5(rand());
	$header = "From:".EMAIL_FROM.$cr;
	$header.= "Reply-to:".EMAIL_FROM.$cr;
	$header.= "MIME-Version: 1.0".$cr;
	$header.= "Content-Type: multipart/alternative;".$cr." boundary=\"$boundary\"".$cr;

	$message = $cr."--".$boundary.$cr;
	$message.= "Content-Type: text/plain; charset=\"utf-8\"".$cr;
	$message.= "Content-Transfer-Encoding: 8bit".$cr;
	$message.= $cr.$message_txt.$cr;
	$message.= $cr."--".$boundary.$cr;
	$message.= $cr."--".$boundary."--".$cr;

	mail($recipient,$subject,$message,$header);
}


function notify($recipient,$subject,$message_txt){
    
    $mail = new PHPMailer();
    $mail->IsHTML(false);
    $mail->CharSet = "utf-8";
    $mail->SetFrom(EMAIL_FROM, EMAIL_FROM);
    $mail->Subject = $subject;
    $mail->Body = $message_txt;
    $mail->AddAddress($recipient);
    $mail->IsSMTP();
    $mail->host=SMTP_SERVER;
    $mail->port=SMTP_PORT;
    if (USE_SMTP_AUTH){
		$mail->SMTPAuth = true;
		$$mail->Username = SMTP_USER;
		$mail->Password = SMTP_PASSWORD;
	}
    $mail->Send();

}

?>