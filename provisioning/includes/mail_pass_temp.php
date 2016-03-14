<?php
session_start();
require_once 'mail_function.php';
$domain = '@mycompany.com';
$email_addressess = array('ACS' => 'andrea.cristalli', 'DCL' => 'david.cignez', 'DDC' => 'diego.delcoco');
$options = Array('base_dn'            => 'DC=hq,DC=k,DC=grp',
		'account_suffix'     => '@my.compnay.com',
		'domain_controllers' => Array('auriga.my.compnay.com'),
		'use_ad'             => true,
		);



$my_file = $_SESSION['filename'].'.xlsx';
		$my_path = $_SERVER['DOCUMENT_ROOT']."/SPOT/log/";
		$my_name = 'SPOT System Production';
		//$my_name = 'sysprod_sw_delivery'.$domain;
		$my_mail = 'SPOT'.$domain;
		$my_replyto = 'SPOT'.$domain;
		$my_subject = "[".$_SESSION['SALESORDER']."] Access File";
		if(isset($_SESSION['ad_email'])){
		$my_to = $_SESSION['ad_email'];
		}
		else
		{
			$my_to = $email_addressess[strtoupper($_SESSION['login'])].$domain;
		}
		$my_mail = $my_to;
		$my_replyto = $my_to;
		$my_message = '
		<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv=Content-Type content="text/html; charset=iso-8859-1"><meta name=Generator content="Microsoft Word 14 (filtered medium)"><style><!--
		/* Font Definitions */
		@font-face
		{font-family:Calibri;
		panose-1:2 15 5 2 2 2 4 3 2 4;}
		/* Style Definitions */
		p.MsoNormal, li.MsoNormal, div.MsoNormal
		{margin:0cm;
		margin-bottom:.0001pt;
		font-size:11.0pt;
		font-family:"Calibri","sans-serif";
		mso-fareast-language:EN-US;}
a:link, span.MsoHyperlink
{mso-style-priority:99;
color:blue;
      text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
{mso-style-priority:99;
color:purple;
      text-decoration:underline;}
      span.EmailStyle17
{mso-style-type:personal-compose;
	font-family:"Calibri","sans-serif";
color:windowtext;}
.MsoChpDefault
{mso-style-type:export-only;
	font-family:"Calibri","sans-serif";
	mso-fareast-language:EN-US;}
	@page WordSection1
{size:612.0pt 792.0pt;
margin:70.85pt 70.85pt 70.85pt 70.85pt;}
div.WordSection1
{page:WordSection1;}
--></style>
<!--[if gte mso 9]><xml>
<o:shapedefaults v:ext="edit" spidmax="1026" />
</xml><![endif]--><!--[if gte mso 9]><xml>
<o:shapelayout v:ext="edit">
<o:idmap v:ext="edit" data="1" />
</o:shapelayout></xml><![endif]-->
</head>
<body lang=FR link=blue vlink=purple>
<div class=WordSection1>
<p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'>Dear Program Manager,<o:p></o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'>Please find attached table of passwords that have been set for each machine in this order. For security reasons, this data is sent only to you. It is not archived by SysProd. You need to verify the content and possibly correct it (e.g. customer name or &#8220;account&#8221;). You also must complete the missing fields in particular the System ID and the phone number. If the passwords are changed on site, please reflect it. You must then send the table to <a href="mailto:hotline@mycompany.com">hotline@mycompany.com</a> in [PGP] so they can enter the data in the password management system (PMP). The filename should be set to &#8220;PO_number-Account_name-System_ID.xls&#8221;<o:p></o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'>Until the data is entered in the password management system, you are the owner and are in charge of distributing the file to whomever may need it (e.g. system engineer). <o:p></o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'>Best regards,<o:p></o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span lang=EN-US style=\'color:#1F497D\'>Your SysProd team<o:p></o:p></span></p><p class=MsoNormal><o:p>&nbsp;</o:p></p></div></body></html>';
$title = $_SESSION['title'];
$filename = $_SESSION['filename'];
$usermail = $my_replyto;

mail_attachment($my_file, $my_path, $my_to, $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
?>
