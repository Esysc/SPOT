<?php

require_once("config.php");

function print_r_V2($array) {
    $table = "<table class='table-condensed table-bordered'>";
    foreach ($array as $key => $val) {
        $table .= "<tr><td>" . $key . "</td><td>";
        if (is_array($array[$key])) {
            $table .= print_r_V2($array[$key]);
            $table .= "</td></tr>";
        } else
            $table .= $val . "</td></tr>";
    } $table .= "</table>";
    return $table;
}

function prettyprint($code, $id) {
    echo '<pre class="prettyprint linenums" id="' . $id . '">', str_replace("\t", str_repeat("&nbsp", 4), htmlspecialchars($code)), '</pre>';
}

function space($string) {
    $sPattern = '/\s*/m';
    $sReplace = '';

    $newstr = preg_replace($sPattern, $sReplace, $string);
    return $newstr;
}

/*
 * curlkGet
 * @Author: Andrea Cristalli
 * Scope: connect to Corporate REST API or WEB SITE interfaces
 * return: raw content (xml-json-html) 
 */

function curlGet($url, $sharepoint = false) {
    $cookie = "share_cookie.txt";
    $tmp = sys_get_temp_dir();
    $cookie_file_path = $tmp . "/" . $cookie;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    if ($sharepoint) {
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    } else {
        curl_setopt($ch, CURLOPT_HEADER, false);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERPWD, SYSPROD_USER);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds






    $chresult = curl_exec($ch);
    $chapierr = curl_errno($ch);
    $cherrmsg = curl_error($ch);



    curl_close($ch);

    $results = $chresult;

    return $results;
}

function curlPost($url, $postfields) {
    $cookie = "share_cookie.txt";
    $tmp = sys_get_temp_dir();
    $cookie_file_path = $tmp . "/" . $cookie;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERPWD, SYSPROD_USER);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
    $chresult = curl_exec($ch);
    $chapierr = curl_errno($ch);
    $cherrmsg = curl_error($ch);
    curl_close($ch);

    $results = $chresult;

    return $results;
}

/* POST to ipam api to get token */

function tokenGet($url, $header = "", $user = "admin", $pass = "***REMOVED***") {
    $cookie = "share_cookie.txt";
    $tmp = sys_get_temp_dir();
    $cookie_file_path = $tmp . "/" . $cookie;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");

    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
    $chresult = curl_exec($ch);
    $chapierr = curl_errno($ch);
    $cherrmsg = curl_error($ch);
    curl_close($ch);

    $results = $chresult;

    return $results;
}

function tokenCheck($url, $header = "", $user = "admin", $pass = "***REMOVED***") {
    $cookie = "share_cookie.txt";
    $tmp = sys_get_temp_dir();
    $cookie_file_path = $tmp . "/" . $cookie;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
    $chresult = curl_exec($ch);
    $chapierr = curl_errno($ch);
    $cherrmsg = curl_error($ch);
    curl_close($ch);

    $results = $chresult;

    return $results;
}



/*
 * Scope: get all memos ID for the given url and return a xml string
 * Arguments: Release name
 */

function getMemoIds($relname) {

    $xml = addslashes(trim(preg_replace('/\s+/', ' ', curlGet(URL_MEMOS . $relname))));



    return $xml;
}

function getMemoXmlDetail($memoNumber) {

    $xml = addslashes(trim(preg_replace('/\s+/', ' ', curlGet(IST_MEMO_XML_DETAIL . $memoNumber))));
    return $xml;
}

/**

 * This function will remove all the specified values from an array and return the final array.

 * Arguments :    The first argument is the array that should be edited

 *                The arguments after the first argument is a list of values that must be removed.

 * Example : array_remove_value($arr,"one","two","three");

 * Return : The function will return an array after deleting the said values

 */
function array_remove_value() {

    $args = func_get_args();

    $arr = $args[0];

    $values = array_slice($args, 1);



    foreach ($arr as $k => $v) {

        if (in_array($v, $values))
            unset($arr[$k]);
    }

    return $arr;
}

function mask2cidr($mask) {
    $long = ip2long($mask);
    $base = ip2long('255.255.255.255');
    return 32 - log(($long ^ $base) + 1, 2);
}

function subpicker() {

    $range_json = apiWrapper(SITE_URL . '/SPOT/provisioning/api/ranges');
    $table_json = apiWrapper(SITE_URL . '/SPOT/provisioning/api/adresses', 'admin', '***REMOVED***');
    $propose_sub = "";
    $allowed_sub = array();
    $jsonDecoded = json_decode($range_json, true);

    $values = $jsonDecoded['rows'];
    $jsonDecoded = json_decode($table_json, true);
    $IPvalues = $jsonDecoded['rows'];

    foreach ($values as $key => $row) {

        array_push($allowed_sub, $row['start'], $row['end']);
    }


    foreach ($allowed_sub as $allowed_sub_key => $allowed_sub_value) {
        if ($allowed_sub_key % 2 == 0 || $allowed_sub_key == 0)
            $start_sub[] = $allowed_sub_value;
        if ($allowed_sub_key % 2 == 1)
            $end_sub[] = $allowed_sub_value;
    }
    foreach ($start_sub as $k => $value) {
        $start_arr = explode(".", $value);
        $end_arr = explode(".", $end_sub[$k]);

        // works only for valid range

        while ($start_arr <= $end_arr) {
            $tmp_value = space($start_arr[0] . '.' . $start_arr[1] . '.' . $start_arr[2] . '.0');
            if (!$key = array_search($tmp_value, $IPvalues)) {
                $propose_sub = $tmp_value;
                break;
            } else {

                $start_arr[2] ++;
                if ($start_arr[2] == 256) {
                    $start_arr[2] = 0;
                    $start_arr[1] ++;
                    if ($start_arr[1] == 256) {
                        $start_arr[1] = 0;
                        $start_arr[0] ++;
                        if ($start_arr[0] == 256) {
                            $start_arr[1] = 0;
                            $start_arr[0] ++;
                        }
                    }
                }
            }
        }
        if (!$propose_sub == "")
            break;
    }

    return $propose_sub;
}

function subchecker($value, $json) {
    global $sub_string;
    $propose_sub = "";
    $allowed_sub = array();
    $jsonDecoded = json_decode($json, true);
    $values = $jsonDecoded['rows'];
    foreach ($values as $key => $row) {

        array_push($allowed_sub, $row['start'], $row['end']);
    }
    //$allowed_sub = array('3.3.3.0', '3.255.255.0', '10.0.0.0', '10.255.255.0'); // start is pair key , end is unpair
    $sub_string = "";
    foreach ($allowed_sub as $allowed_sub_key => $allowed_sub_value) {
        if ($allowed_sub_key % 2 == 0 || $allowed_sub_key == 0)
            $start_sub[] = $allowed_sub_value;
        if ($allowed_sub_key % 2 == 1)
            $end_sub[] = $allowed_sub_value;
    }
    foreach ($start_sub as $k => $val) {
        $sub_string .= " * " . $val . " - " . $end_sub[$k] . " * ";
        $lower_dec = ip2long($val);
        $upper_dec = ip2long($end_sub[$k]);
        $ip_dec = ip2long($value);
        if ($ip_dec >= $lower_dec && $ip_dec <= $upper_dec) {
            return 0;
            break;
        }
    }
    return 1;
}

function isDuplicate($table, $subnet_tmp) {
    $jsonDecoded = json_decode($table, true);
    $values = $jsonDecoded['rows'];

    foreach ($values as $key => $row) {
        if ($row['subnet'] === $subnet_tmp && $row['status'] === 'active') {
            return $row['subnet'];
        }
    }
}

function jsonToHtml($array) {
    $html = "<table class='table-condensed table-bordered'><thead>";

    foreach ($array as $key => $sub_array) {
        if ($key == 0) {
            $html .= "<tr bgcolor='cyan'>";
            $fields = array_keys($sub_array);
            // print headers
            foreach ($fields as $field) {
                $html .= "<th>$field</th>";
            }

            $html .= "</tr></thead>";
        }
        $html .= "<tr>";
        foreach ($sub_array as $value) {
            $html .= "<td>$value</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

function htmlentities2utf8($string) { // because of the html_entity_decode() bug with UTF-8 
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    foreach ($trans_tbl as $k => $v) {
        $ttr[$v] = utf8_encode($k);
    }

    $string = strtr($string, $ttr);
    return $string;
}

function ntc($search_arr, $string) {
    /*     *
     * Function ntc (name translation customer)                                                   *   
     * 												*
     * 												*                                                                                               
     *                                                                                   		*
     *                                                                                            *                                                                                       
     * This function checks for a string occurrence between array values.                         *   
     * It look for each chars of string in the exactly order they are                             *   
     * (for example, if string 'nvi' is given then the result will be all values in array         *
     * where this string is present. But if a value have niv the result will be false)            *
     * The search is performed in case insensitive way and only if the string and the value       *
     * has the same start char. It returns the array containing all matches                       *   
     * This array may be use to find all the concerning subnet                                    *   
     * 												*
     */

    $string = strtolower($string);
    $search_arr_no_case = array_map('strtolower', ($search_arr));
    $string_arr = str_split($string);
    $results = array();
    foreach ($search_arr_no_case as $key => $value) {
        $value_arr = str_split($value);
        $index = 0;
        if ($value_arr[0] === $string_arr[0]) {
            $pos[$index] = strpos($value, $string_arr[0]);

            foreach ($string_arr as $str_value) {

                if (strstr($value, $str_value)) {
                    if (strpos($value, $str_value) >= $pos[$index]) {
                        ++$index;
                        $pos[$index] = strpos($value, $str_value);
                    }
                }
            }
            if ($index == 3)
                $results[] = $search_arr[$key];
        }
    }
    return $results;
}

function loggit($priority, $message, $facility = "LOG_LOCAL0") {
    // write log to syslog
    openlog($priority . "_SPOT_LOG", LOG_PID, intval($facility));
    syslog(intval($priority), 'SPOTLOGS: ' . $message);
    closelog();
}

function apiWrapper($url, $user = 'hotline', $pass = 'hotline') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $pass);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function apiPOST($url, $content, $method = "POST") {


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'perl');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($content))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

    $result = curl_exec($ch);
    //echo var_dump($result);
    curl_close($ch);
    // return var_dump($content);
}

function removeLines($path, $remove) {
    $lines = file($path);

    foreach ($lines as $key => $line)
        if (stristr($line, $remove))
            unset($lines[$key]);

    // $data = implode('\n', array_values($lines));

    $file = fopen($path, 'w+') or die("Unable to open file! $path");


    foreach ($lines as $line)
        fwrite($file, $line);


    fclose($file);
    //Remove blank and new emty lines
    file_put_contents($path, implode(PHP_EOL, file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
}

function redirection($page) {
    echo "
		<script type='text/javascript'>
		window.location = '$page'
		</script>
		";
}
