<?php
require "share.php";
require "config.php";
function release_find($rel_id) {
	$cookie_file_path = "/tmp/share_cookie.txt";
	$url_rel = 'http://ist.my.compnay.com/cgi-bin/WebObjects/ist.woa/wa/inspectRecord?entityName=Delivery&id=' . trim($rel_id);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url_rel);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_USERPWD, 'sysprod:***REMOVED***');
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);


	$chresult = curl_exec($ch);
	$chapierr = curl_errno($ch);
	$cherrmsg = curl_error($ch);

	curl_close($ch);

	$results = $chresult;

	$doc = new DomDocument;
	$doc->strictErrorChecking = FALSE;
	libxml_use_internal_errors(true);
	$doc->loadHTML($results);
	$div = $doc->getElementById('Delivery_inspect_deliverableRelease_container');
	if (!$div) {
		$url_rel = 'http://ist.my.compnay.com/cgi-bin/WebObjects/ist.woa/wa/inspectRecord?entityName=SolutionRelease&id=' . trim($rel_id);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_rel);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_USERPWD, 'sysprod:***REMOVED***');
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);


		$chresult = curl_exec($ch);
		$chapierr = curl_errno($ch);
		$cherrmsg = curl_error($ch);

		curl_close($ch);

		$results = $chresult;

		$doc = new DomDocument;
		$doc->strictErrorChecking = FALSE;
		libxml_use_internal_errors(true);
		$doc->loadHTML($results);

		$div = $doc->getElementById('SolutionRelease_inspect_pmsName_container');
		if (!$div) {
			return false;
		}
	}
	if ($div) {
		$ret = strip_tags($doc->saveXML($div));
		$ret_arr = explode(' ', $ret);
		$count_arr = count($ret_arr);
		$elem = $count_arr - 1;
		$release = preg_replace("/[^A-Za-z0-9_.]/", "", $ret_arr[$elem]);
		if ($release !== '') {
			return $release;
		} else {
			return false;
		}
	}
}







echo release_find("28383");
?>
