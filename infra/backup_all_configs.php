<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
	<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
			<title>Switchs de la C2A - Administration</title>
		<link rel=\"shortcut icon\" href=\"/favicon.ico\" />
		
		<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"web/css/bootstrap/css/bootstrap.min.css\" />
		<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"web/css/style.css\" />
		<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"web/css/toolTip.css\" />
		<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"web/css/errors.css\" />

		<script type=\"text/javascript\" src=\"web/js/jquery-1.11.3.min.js\"></script>
		
	     <style type=\"text/css\">
				.progress {
					width: 500px;
					position: absolute;
				}
		</style>
	</head>
	<body>
	<div>
		<div class=\"page-container\">
		<!-- top navbar -->
		<div class=\"navbar navbar-default\">
			<div class=\"container\">
				<div class=\"navbar-header\">
					<a class=\"navbar-brand\" href=\"index.php\">Switchs de la C2A - Administration</a>
				</div>
				<div id=\"navbar\" class=\"navbar-collapse collapse\">
					<ul class=\"nav navbar-nav navbar-right\">
						<li><a href=\"http://sourceforge.net/projects/procurve-admin/forums/forum/1233992\">Aide</a></li>
					</ul>
				</div>
			</div>
		</div>
			<div class=\"container\">
				<div class=\"row row-offcanvas row-offcanvas-left\">
					<p>Backup in progress...</p>";
					
/**
 * Output span with progress.
 *
 * @param $current integer Current progress out of total
 * @param $total   integer Total steps required to complete
 */
function outputProgress($current, $total) {

	echo("
       	<div class=\"progress\">
			<div class=\"progress-bar\" style=\"width:".round($current / $total * 100)."%;\">
				<span class=\"sr-only\">".round($current / $total * 100)."% Complete</span>
			</div>
		</div>
    ");
    myFlush();
    sleep(1);
}

/**
 * Flush output buffer
 */
function myFlush() {
    echo(str_repeat(' ', 256));
    if (@ob_get_contents()) {
        @ob_end_flush();
    }
    flush();
}

include_once("includes.php");
require_once('include_path.php');
require_once('include_smarty.php');
require_once('include_classes.php');

if (ENABLE_CONFIGURATION_BACKUP_MANAGEMENT) {//  backing up switches configuration is authorized
	foreach ($mySwitchs as $switch) {
		if ($switch->isPlannedForBackup() == 1) {
			$switches[] = $switch;
		}
	}
	
    $date = date("Ymd");
    $links = array();
    $current = 0;
    $nbok = 0;
    if (count($switches) > 0) {
		if(SHOW_PROGRESS_BAR_DURING_BACKUP){
			outputProgress($current, count($switches));
		}
        foreach ($switches as $switch) {
            $header_error_message = "<span style=\"color:red\">[Backup ERR.]</span> -- " . $switch->getIp() . "(" . $switch->getName() . ") : ";
            $erreur = false;
            try {
                if (ENCRYPT_SAVED_CONFIGURATION_FILES) {
                    $conf = $switch->getEncryptedConf(false);
                } else {
                    $conf = $switch->getConf(false);
                }
            } catch (Exception $e) {
                $erreur = true;
                $messages[] = $header_error_message . $e->getMessage() . "<br />";
            }

            if (!$erreur) {
				$rep1 = "configs". SYSTEM_PATH_SEPARATOR .substr($date,0,strlen($date)-4);
				if($switch->getGroupId() != 0){ // the switch isMember of a group
					$group = Group::retrieveById($switch->getGroupId());
					$rep2 = $rep1 . SYSTEM_PATH_SEPARATOR . substr($date,4,strlen($date)-6). SYSTEM_PATH_SEPARATOR .$group->getName() . '-gp' . SYSTEM_PATH_SEPARATOR . $switch->getName();
				} else {
					$rep2 = $rep1 . SYSTEM_PATH_SEPARATOR . substr($date,4,strlen($date)-6). SYSTEM_PATH_SEPARATOR . $switch->getName();
				}
				if (!is_dir($rep2)){
					$dir = @mkdir($rep2,0777,1);
				}
               if (is_dir($rep2)){
                    try {
	
                        if (ENCRYPT_SAVED_CONFIGURATION_FILES) {
                            $fp = fopen($rep2. SYSTEM_PATH_SEPARATOR ."conf_" . $switch->getIp() . "-" . $date . "-encrypted", "w");
                        } else {
                            $fp = fopen($rep2. SYSTEM_PATH_SEPARATOR ."conf_" . $switch->getIp() . "-" . $date . "-clear_text", "w");
                        }
                    } catch (Exception $e) {
                        $erreur = true;
                        $messages[] = $header_error_message . $e->getMessage() . "<br />";
                    }
               } else {
                   $erreur = true;
                   $messages[] = $header_error_message . "Error creating $rep2 directory !<br />";
               }
            }

            if (!$erreur) {
                $link = $rep2. SYSTEM_PATH_SEPARATOR ."conf_" . $switch->getIp() . "-" . date("Ymd");
                try {
                    if (ENCRYPT_SAVED_CONFIGURATION_FILES) {
                        $link = $link . "-encrypted";
                    } else {
                        $link = $link . "-clear_text";
                    }
                    fputs($fp, $conf);
                    fclose($fp);
                } catch (Exception $e) {
                    $erreur = true;
                    $messages[] = $header_error_message . $e->getMessage() . "<br />";
                }
            }

            if (!$erreur) {
                $nbok+=1;
				if (SHOW_SWITCHS_IPS_IN_CONF_BACKUP_RESULTS){
					$messages[] = "<span style=\"color:green\">[Backup OK] </span>" . $switch->getIp() . " (" . $switch->getName() . ")<br />";
				} else {
					$messages[] = "<span style=\"color:green\">[Backup OK] </span>" . $switch->getName() . "<br />";
				}
            } else {
                $links[] = "error";
            }
            $current++;
			if(SHOW_PROGRESS_BAR_DURING_BACKUP){
				outputProgress($current, count($switches));
			}
        }
    }

	
    $log=date('l jS \of F Y h:i:s A')."\r\n";
	$log_dir ="logs";
	if(!is_dir($log_dir)){
		$log_dir= @mkdir("logs",0777,1);
	}
	if (!is_dir($log_dir)){
		 $erreur = true;
		 $messages[] = $header_error_message . "Error creating $log_dir directory !<br />";
	}
	if(is_file("logs/backup.log.5")){
		@unlink("logs/backup.log.5");
	}
	if(is_file("logs/backup.log.4")){
		rename("logs/backup.log.4","logs/backup.log.5");
	}
	if(is_file("logs/backup.log.3")){
		rename("logs/backup.log.3","logs/backup.log.4");
	}
	if(is_file("logs/backup.log.2")){
		rename("logs/backup.log.2","logs/backup.log.3");
	}
	if(is_file("logs/backup.log.1")){
		rename("logs/backup.log.1","logs/backup.log.2");
	}
	if(is_file("logs/backup.log")){
		rename("logs/backup.log","logs/backup.log.1");
	}
    
    foreach($messages as $message){
		 $fp = fopen("logs/backup.log", "w");
		 $log .= strip_tags($message)."\r\n";
    }
	fputs($fp,$log);
	fclose($fp);

	if(EMAIL_NOTIFICATION){
		if (USE_PHP_MAILER_LIBRARY){
            notify(EMAIL_RECIPIENT,EMAIL_SUBJECT,$log); // see lib/notify.php
		} else {
			notify_basic(EMAIL_RECIPIENT,EMAIL_SUBJECT,$log); // see lib/notify.php
		}
    }
	
    $smarty->assign("messages", $messages);
    $smarty->assign("date", $date);
    $smarty->assign("nbok", $nbok);
    $smarty->display('backup_all_configs.tpl');

} else {
    die(ACCESS_DENIED);
}
?>