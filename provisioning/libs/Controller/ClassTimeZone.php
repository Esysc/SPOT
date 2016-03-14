<?php

/* GPL Public License 
 * 
 * Sysprod - Mycompany */

/**
 * Description of timezone
 *
 * @author ACS
 */

/**
 * 
 * 
 * 
 * 
 */
class timezones {

    public $tz;
    public $dst_start;
    public $time_start;
    public $dst_stop;
    public $time_stop;
    public $nowTime;
    public $m4Time;
    public $m8Time;
    public $year;
    public $WindowsTimeZones = array();
    public $offset;
    public $Hoffset;
    public $form = array();
    public $transition = array();
    public $timeanddate;
    public $city;

}

/**
 * 
 * 
 */
class timezone {

    public function __construct($dst_start = '30-03', $time_start = '02:00', $dst_stop = '28-10', $time_stop = '02:00', $tz = 'UTC', $WindowsTimeZones = array(), $offset = 0, $Hoffset = 0, $form = array(), $transition = array()) {
        $WindowsTimeZones = array(
            'Africa/Abidjan' => 'Greenwich Standard Time',
            'Africa/Accra' => 'Greenwich Standard Time',
            'Africa/Addis_Ababa' => 'E. Africa Standard Time',
            'Africa/Algiers' => 'Central Europe Standard Time',
            'Africa/Asmara' => 'E. Africa Standard Time',
            'Africa/Bamako' => 'Greenwich Standard Time',
            'Africa/Bangui' => 'W. Central Africa Standard Time',
            'Africa/Banjul' => 'Greenwich Standard Time',
            'Africa/Bissau' => 'Greenwich Standard Time',
            'Africa/Blantyre' => 'South Africa Standard Time',
            'Africa/Brazzaville' => 'W. Central Africa Standard Time',
            'Africa/Bujumbura' => 'South Africa Standard Time',
            'Africa/Cairo' => 'Egypt Standard Time',
            'Africa/Casablanca' => 'Greenwich Standard Time',
            'Africa/Ceuta' => 'Central Europe Standard Time',
            'Africa/Conakry' => 'Greenwich Standard Time',
            'Africa/Dakar' => 'Greenwich Standard Time',
            'Africa/Dar_es_Salaam' => 'E. Africa Standard Time',
            'Africa/Djibouti' => 'E. Africa Standard Time',
            'Africa/Douala' => 'W. Central Africa Standard Time',
            'Africa/El_Aaiun' => 'Greenwich Standard Time',
            'Africa/Freetown' => 'Greenwich Standard Time',
            'Africa/Gaborone' => 'South Africa Standard Time',
            'Africa/Harare' => 'South Africa Standard Time',
            'Africa/Johannesburg' => 'South Africa Standard Time',
            'Africa/Kampala' => 'E. Africa Standard Time',
            'Africa/Khartoum' => 'E. Africa Standard Time',
            'Africa/Kigali' => 'South Africa Standard Time',
            'Africa/Kinshasa' => 'W. Central Africa Standard Time',
            'Africa/Lagos' => 'W. Central Africa Standard Time',
            'Africa/Libreville' => 'W. Central Africa Standard Time',
            'Africa/Lome' => 'Greenwich Standard Time',
            'Africa/Luanda' => 'W. Central Africa Standard Time',
            'Africa/Lubumbashi' => 'South Africa Standard Time',
            'Africa/Lusaka' => 'South Africa Standard Time',
            'Africa/Malabo' => 'W. Central Africa Standard Time',
            'Africa/Maputo' => 'South Africa Standard Time',
            'Africa/Maseru' => 'South Africa Standard Time',
            'Africa/Mbabane' => 'South Africa Standard Time',
            'Africa/Mogadishu' => 'E. Africa Standard Time',
            'Africa/Monrovia' => 'Greenwich Standard Time',
            'Africa/Nairobi' => 'E. Africa Standard Time',
            'Africa/Ndjamena' => 'W. Central Africa Standard Time',
            'Africa/Niamey' => 'W. Central Africa Standard Time',
            'Africa/Nouakchott' => 'Greenwich Standard Time',
            'Africa/Ouagadougou' => 'Greenwich Standard Time',
            'Africa/Porto-Novo' => 'W. Central Africa Standard Time',
            'Africa/Sao_Tome' => 'Greenwich Standard Time',
            'Africa/Tripoli' => 'South Africa Standard Time',
            'Africa/Tunis' => 'Central Europe Standard Time',
            'Africa/Windhoek' => 'W. Central Africa Standard Time',
            'America/Adak' => 'Hawaiian Standard Time',
            'America/Anchorage' => 'Alaskan Standard Time',
            'America/Anguilla' => 'Atlantic Standard Time',
            'America/Antigua' => 'Atlantic Standard Time',
            'America/Araguaina' => 'E. South America Standard Time',
            'America/Argentina/Buenos_Aires' => 'Argentina Standard Time',
            'America/Argentina/Catamarca' => 'Argentina Standard Time',
            'America/Argentina/Cordoba' => 'Argentina Standard Time',
            'America/Argentina/Jujuy' => 'Argentina Standard Time',
            'America/Argentina/La_Rioja' => 'Argentina Standard Time',
            'America/Argentina/Mendoza' => 'Argentina Standard Time',
            'America/Argentina/Rio_Gallegos' => 'Argentina Standard Time',
            'America/Argentina/San_Juan' => 'Argentina Standard Time',
            'America/Argentina/San_Luis' => 'Argentina Standard Time',
            'America/Argentina/Tucuman' => 'Argentina Standard Time',
            'America/Argentina/Ushuaia' => 'Argentina Standard Time',
            'America/Aruba' => 'Atlantic Standard Time',
            'America/Asuncion' => 'Central Brazilian Standard Time',
            'America/Atikokan' => 'Eastern Standard Time',
            'America/Bahia' => 'E. South America Standard Time',
            'America/Barbados' => 'Atlantic Standard Time',
            'America/Belem' => 'E. South America Standard Time',
            'America/Belize' => 'Central Standard Time (Mexico)',
            'America/Blanc-Sablon' => 'Atlantic Standard Time',
            'America/Boa_Vista' => 'Central Brazilian Standard Time',
            'America/Bogota' => 'SA Pacific Standard Time',
            'America/Boise' => 'Mountain Standard Time',
            'America/Cambridge_Bay' => 'Mountain Standard Time',
            'America/Campo_Grande' => 'SA Western Standard Time',
            'America/Cancun' => 'Central Standard Time (Mexico)',
            'America/Caracas' => 'Newfoundland Standard Time',
            'America/Cayenne' => 'SA Eastern Standard Time',
            'America/Cayman' => 'Eastern Standard Time',
            'America/Chicago' => 'Central Standard Time',
            'America/Chihuahua' => 'Mountain Standard Time (Mexico)',
            'America/Costa_Rica' => 'Central Standard Time (Mexico)',
            'America/Cuiaba' => 'SA Western Standard Time',
            'America/Curacao' => 'Atlantic Standard Time',
            'America/Danmarkshavn' => 'Greenwich Standard Time',
            'America/Dawson' => 'Pacific Standard Time',
            'America/Dawson_Creek' => 'Mountain Standard Time',
            'America/Denver' => 'Mountain Standard Time',
            'America/Detroit' => 'Eastern Standard Time',
            'America/Dominica' => 'Atlantic Standard Time',
            'America/Edmonton' => 'Mountain Standard Time',
            'America/Eirunepe' => 'SA Pacific Standard Time',
            'America/El_Salvador' => 'Central Standard Time (Mexico)',
            'America/Fortaleza' => 'E. South America Standard Time',
            'America/Glace_Bay' => 'Atlantic Standard Time',
            'America/Godthab' => 'Greenland Standard Time',
            'America/Goose_Bay' => 'Atlantic Standard Time',
            'America/Grand_Turk' => 'Eastern Standard Time',
            'America/Grenada' => 'Atlantic Standard Time',
            'America/Guadeloupe' => 'Atlantic Standard Time',
            'America/Guatemala' => 'Central Standard Time (Mexico)',
            'America/Guayaquil' => 'SA Pacific Standard Time',
            'America/Guyana' => 'SA Western Standard Time',
            'America/Halifax' => 'Atlantic Standard Time',
            'America/Havana' => 'SA Pacific Standard Time',
            'America/Hermosillo' => 'Mountain Standard Time',
            'America/Indiana/Indianapolis' => 'Eastern Standard Time',
            'America/Indiana/Knox' => 'Central Standard Time',
            'America/Indiana/Marengo' => 'Eastern Standard Time',
            'America/Indiana/Petersburg' => 'Eastern Standard Time',
            'America/Indiana/Tell_City' => 'Central Standard Time',
            'America/Indiana/Vevay' => 'Eastern Standard Time',
            'America/Indiana/Vincennes' => 'Eastern Standard Time',
            'America/Indiana/Winamac' => 'Eastern Standard Time',
            'America/Inuvik' => 'Mountain Standard Time',
            'America/Iqaluit' => 'Eastern Standard Time',
            'America/Jamaica' => 'Eastern Standard Time',
            'America/Juneau' => 'Alaskan Standard Time',
            'America/Kentucky/Louisville' => 'Eastern Standard Time',
            'America/Kentucky/Monticello' => 'Eastern Standard Time',
            'America/La_Paz' => 'SA Western Standard Time',
            'America/Lima' => 'SA Pacific Standard Time',
            'America/Los_Angeles' => 'Pacific Standard Time',
            'America/Maceio' => 'E. South America Standard Time',
            'America/Managua' => 'Central Standard Time (Mexico)',
            'America/Manaus' => 'Central Brazilian Standard Time',
            'America/Martinique' => 'Atlantic Standard Time',
            'America/Mazatlan' => 'Mountain Standard Time (Mexico)',
            'America/Menominee' => 'Central Standard Time (Mexico)',
            'America/Merida' => 'Central Standard Time (Mexico)',
            'America/Mexico_City' => 'Central Standard Time (Mexico)',
            'America/Miquelon' => 'Greenland Standard Time',
            'America/Moncton' => 'Atlantic Standard Time',
            'America/Monterrey' => 'Central Standard Time (Mexico)',
            'America/Montevideo' => 'Montevideo Standard Time',
            'America/Montreal' => 'Eastern Standard Time',
            'America/Montserrat' => 'Atlantic Standard Time',
            'America/Nassau' => 'Eastern Standard Time',
            'America/New_York' => 'Eastern Standard Time',
            'America/Nipigon' => 'Eastern Standard Time',
            'America/Nome' => 'Alaskan Standard Time',
            'America/Noronha' => 'Mid-Atlantic Standard Time',
            'America/North_Dakota/Center' => 'Central Standard Time',
            'America/North_Dakota/New_Salem' => 'Central Standard Time',
            'America/Panama' => 'Eastern Standard Time',
            'America/Pangnirtung' => 'Eastern Standard Time',
            'America/Paramaribo' => 'SA Eastern Standard Time',
            'America/Phoenix' => 'US Mountain Standard Time',
            'America/Port_of_Spain' => 'Atlantic Standard Time',
            'America/Port-au-Prince' => 'Eastern Standard Time',
            'America/Porto_Velho' => 'Central Brazilian Standard Time',
            'America/Puerto_Rico' => 'Atlantic Standard Time',
            'America/Rainy_River' => 'Central Standard Time',
            'America/Rankin_Inlet' => 'Central Standard Time',
            'America/Recife' => 'E. South America Standard Time',
            'America/Regina' => 'Central Standard Time',
            'America/Resolute' => 'Eastern Standard Time',
            'America/Rio_Branco' => 'SA Pacific Standard Time',
            'America/Santiago' => 'SA Western Standard Time',
            'America/Santo_Domingo' => 'Atlantic Standard Time',
            'America/Sao_Paulo' => 'E. South America Standard Time',
            'America/Scoresbysund' => 'Azores Standard Time',
            'America/St_Johns' => 'Newfoundland Standard Time',
            'America/St_Kitts' => 'Atlantic Standard Time',
            'America/St_Lucia' => 'Atlantic Standard Time',
            'America/St_Thomas' => 'Atlantic Standard Time',
            'America/St_Vincent' => 'Atlantic Standard Time',
            'America/Swift_Current' => 'Central Standard Time (Mexico)',
            'America/Tegucigalpa' => 'Central Standard Time (Mexico)',
            'America/Thule' => 'Atlantic Standard Time',
            'America/Thunder_Bay' => 'Eastern Standard Time',
            'America/Tijuana' => 'Pacific Standard Time (Mexico)',
            'America/Toronto' => 'Eastern Standard Time',
            'America/Tortola' => 'Atlantic Standard Time',
            'America/Vancouver' => 'Pacific Standard Time',
            'America/Whitehorse' => 'Pacific Standard Time',
            'America/Winnipeg' => 'Central Standard Time',
            'America/Yakutat' => 'Alaskan Standard Time',
            'America/Yellowknife' => 'Mountain Standard Time',
            'Antarctica/Casey' => 'W. Australia Standard Time',
            'Antarctica/Davis' => 'SE Asia Standard Time',
            'Antarctica/DumontDUrville' => 'E. Australia Standard Time',
            'Antarctica/Mawson' => 'Central Asia Standard Time',
            'Antarctica/McMurdo' => 'New Zealand Standard Time',
            'Antarctica/Palmer' => 'SA Western Standard Time',
            'Antarctica/Rothera' => 'SA Eastern Standard Time',
            'Antarctica/Syowa' => 'E. Africa Standard Time',
            'Antarctica/Vostok' => 'Central Asia Standard Time',
            'Asia/Aden' => 'Arabic Standard Time',
            'Asia/Almaty' => 'Central Asia Standard Time',
            'Asia/Amman' => 'Jordan Standard Time',
            'Asia/Anadyr' => 'New Zealand Standard Time',
            'Asia/Aqtau' => 'West Asia Standard Time',
            'Asia/Aqtobe' => 'West Asia Standard Time',
            'Asia/Ashgabat' => 'West Asia Standard Time',
            'Asia/Baghdad' => 'Arabic Standard Time',
            'Asia/Bahrain' => 'Arabic Standard Time',
            'Asia/Baku' => 'Azerbaijan Standard Time',
            'Asia/Bangkok' => 'SE Asia Standard Time',
            'Asia/Beirut' => 'Middle East Standard Time',
            'Asia/Bishkek' => 'Central Asia Standard Time',
            'Asia/Brunei' => 'North Asia East Standard Time',
            'Asia/Choibalsan' => 'Yakutsk Standard Time',
            'Asia/Chongqing' => 'China Standard Time',
            'Asia/Colombo' => 'Sri Lanka Standard Time',
            'Asia/Damascus' => 'Middle East Standard Time',
            'Asia/Dhaka' => 'N. Central Asia Standard Time',
            'Asia/Dili' => 'Korea Standard Time',
            'Asia/Dubai' => 'Arabian Standard Time',
            'Asia/Dushanbe' => 'West Asia Standard Time',
            'Asia/Gaza' => 'Middle East Standard Time',
            'Asia/Harbin' => 'China Standard Time',
            'Asia/Ho_Chi_Minh' => 'SE Asia Standard Time',
            'Asia/Hong_Kong' => 'Malay Peninsula Standard Time',
            'Asia/Hovd' => 'SE Asia Standard Time',
            'Asia/Irkutsk' => 'North Asia East Standard Time',
            'Asia/Jakarta' => 'SE Asia Standard Time',
            'Asia/Jayapura' => 'Korea Standard Time',
            'Asia/Jerusalem' => 'Middle East Standard Time',
            'Asia/Kabul' => 'Afghanistan Standard Time',
            'Asia/Kamchatka' => 'Fiji Standard Time',
            'Asia/Karachi' => 'West Asia Standard Time',
            'Asia/Kashgar' => 'China Standard Time',
            'Asia/Katmandu' => 'Nepal Standard Time',
            'Asia/Kolkata' => 'India Standard Time',
            'Asia/Krasnoyarsk' => 'North Asia Standard Time',
            'Asia/Kuala_Lumpur' => 'Malay Peninsula Standard Time',
            'Asia/Kuching' => 'Malay Peninsula Standard Time',
            'Asia/Kuwait' => 'Arabic Standard Time',
            'Asia/Macau' => 'China Standard Time',
            'Asia/Magadan' => 'Central Pacific Standard Time',
            'Asia/Makassar' => 'Malay Peninsula Standard Time',
            'Asia/Manila' => 'Malay Peninsula Standard Time',
            'Asia/Muscat' => 'Arabian Standard Time',
            'Asia/Nicosia' => 'Middle East Standard Time',
            'Asia/Novosibirsk' => 'N. Central Asia Standard Time',
            'Asia/Omsk' => 'N. Central Asia Standard Time',
            'Asia/Oral' => 'Ekaterinburg Standard Time',
            'Asia/Phnom_Penh' => 'SE Asia Standard Time',
            'Asia/Pontianak' => 'SE Asia Standard Time',
            'Asia/Pyongyang' => 'Korea Standard Time',
            'Asia/Qatar' => 'Arabic Standard Time',
            'Asia/Qyzylorda' => 'N. Central Asia Standard Time',
            'Asia/Rangoon' => 'Myanmar Standard Time',
            'Asia/Riyadh' => 'Arabic Standard Time',
            'Asia/Sakhalin' => 'Vladivostok Standard Time',
            'Asia/Samarkand' => 'West Asia Standard Time',
            'Asia/Seoul' => 'Korea Standard Time',
            'Asia/Shanghai' => 'China Standard Time',
            'Asia/Singapore' => 'Malay Peninsula Standard Time',
            'Asia/Taipei' => 'China Standard Time',
            'Asia/Tashkent' => 'West Asia Standard Time',
            'Asia/Tbilisi' => 'Arabian Standard Time',
            'Asia/Tehran' => 'Iran Standard Time',
            'Asia/Thimphu' => 'N. Central Asia Standard Time',
            'Asia/Tokyo' => 'Tokyo Standard Time',
            'Asia/Ulaanbaatar' => 'North Asia East Standard Time',
            'Asia/Urumqi' => 'China Standard Time',
            'Asia/Vientiane' => 'SE Asia Standard Time',
            'Asia/Vladivostok' => 'Vladivostok Standard Time',
            'Asia/Yakutsk' => 'Yakutsk Standard Time',
            'Asia/Yekaterinburg' => 'Ekaterinburg Standard Time',
            'Asia/Yerevan' => 'Caucasus Standard Time',
            'Atlantic/Azores' => 'Azores Standard Time',
            'Atlantic/Bermuda' => 'Atlantic Standard Time',
            'Atlantic/Canary' => 'W. Europe Standard Time',
            'Atlantic/Cape_Verde' => 'Cape Verde Standard Time',
            'Atlantic/Faroe' => 'W. Europe Standard Time',
            'Atlantic/Madeira' => 'W. Europe Standard Time',
            'Atlantic/Reykjavik' => 'Greenwich Standard Time',
            'Atlantic/South_Georgia' => 'Mid-Atlantic Standard Time',
            'Atlantic/St_Helena' => 'Greenwich Standard Time',
            'Atlantic/Stanley' => 'Pacific SA Standard Time',
            'Australia/Adelaide' => 'Cen. Australia Standard Time',
            'Australia/Brisbane' => 'E. Australia Standard Time',
            'Australia/Broken_Hill' => 'Cen. Australia Standard Time',
            'Australia/Currie' => 'Tasmania Standard Time',
            'Australia/Darwin' => 'AUS Central Standard Time',
            'Australia/Eucla' => 'Cen. Australia Standard Time',
            'Australia/Hobart' => 'Tasmania Standard Time',
            'Australia/Lindeman' => 'AUS Eastern Standard Time',
            'Australia/Lord_Howe' => 'Central Pacific Standard Time',
            'Australia/Melbourne' => 'AUS Eastern Standard Time',
            'Australia/Perth' => 'W. Australia Standard Time',
            'Australia/Sydney' => 'AUS Eastern Standard Time',
            'CET' => 'Central Europe Standard Time',
            'CST6CDT' => 'Central Standard Time',
            'EET' => 'E. Europe Standard Time',
            'EST' => 'Eastern Standard Time',
            'EST5EDT' => 'Eastern Standard Time',
            'Etc/GMT' => 'GMT Standard Time',
            'Etc/UCT' => 'GMT Standard Time',
            'Etc/UTC' => 'GMT Standard Time',
            'Europe/Amsterdam' => 'W. Europe Standard Time',
            'Europe/Andorra' => 'Central Europe Standard Time',
            'Europe/Athens' => 'E. Europe Standard Time',
            'Europe/Belgrade' => 'Central Europe Standard Time',
            'Europe/Berlin' => 'W. Europe Standard Time',
            'Europe/Brussels' => 'Central European Standard Time',
            'Europe/Bucharest' => 'E. Europe Standard Time',
            'Europe/Budapest' => 'Central European Standard Time',
            'Europe/Chisinau' => 'E. Europe Standard Time',
            'Europe/Copenhagen' => 'Central European Standard Time',
            'Europe/Dublin' => 'GMT Standard Time',
            'Europe/Gibraltar' => 'Central European Standard Time',
            'Europe/Helsinki' => 'E. Europe Standard Time',
            'Europe/Istanbul' => 'E. Europe Standard Time',
            'Europe/Kaliningrad' => 'E. Europe Standard Time',
            'Europe/Kiev' => 'E. Europe Standard Time',
            'Europe/Lisbon' => 'W. Europe Standard Time',
            'Europe/London' => 'GMT Standard Time',
            'Europe/Luxembourg' => 'Central European Standard Time',
            'Europe/Madrid' => 'Central European Standard Time',
            'Europe/Malta' => 'Central European Standard Time',
            'Europe/Minsk' => 'E. Europe Standard Time',
            'Europe/Monaco' => 'Central European Standard Time',
            'Europe/Moscow' => 'Russian Standard Time',
            'Europe/Oslo' => 'Central European Standard Time',
            'Europe/Paris' => 'Central European Standard Time',
            'Europe/Prague' => 'Central European Standard Time',
            'Europe/Riga' => 'E. Europe Standard Time',
            'Europe/Rome' => 'W. Europe Standard Time',
            'Europe/Samara' => 'Caucasus Standard Time',
            'Europe/Simferopol' => 'E. Europe Standard Time',
            'Europe/Sofia' => 'E. Europe Standard Time',
            'Europe/Stockholm' => 'W. Europe Standard Time',
            'Europe/Tallinn' => 'E. Europe Standard Time',
            'Europe/Tirane' => 'Central European Standard Time',
            'Europe/Uzhgorod' => 'E. Europe Standard Time',
            'Europe/Vaduz' => 'W. Europe Standard Time',
            'Europe/Vienna' => 'Central European Standard Time',
            'Europe/Vilnius' => 'E. Europe Standard Time',
            'Europe/Volgograd' => 'Russian Standard Time',
            'Europe/Warsaw' => 'Central European Standard Time',
            'Europe/Zaporozhye' => 'E. Europe Standard Time',
            'Europe/Zurich' => 'Central European Standard Time',
            'HST' => 'Hawaiian Standard Time',
            'Indian/Antananarivo' => 'E. Africa Standard Time',
            'Indian/Chagos' => 'Central Asia Standard Time',
            'Indian/Christmas' => 'SE Asia Standard Time',
            'Indian/Cocos' => 'Myanmar Standard Time',
            'Indian/Comoro' => 'E. Africa Standard Time',
            'Indian/Kerguelen' => 'West Asia Standard Time',
            'Indian/Mahe' => 'Arabian Standard Time',
            'Indian/Maldives' => 'West Asia Standard Time',
            'Indian/Mauritius' => 'Azerbaijan Standard Time',
            'Indian/Mayotte' => 'E. Africa Standard Time',
            'Indian/Reunion' => 'Arabian Standard Time',
            'MET' => 'Central European Standard Time',
            'MST' => 'Mountain Standard Time',
            'MST7MDT' => 'US Mountain Standard Time',
            'Pacific/Apia' => 'Samoa Standard Time',
            'Pacific/Auckland' => 'New Zealand Standard Time',
            'Pacific/Chatham' => 'Fiji Standard Time',
            'Pacific/Easter' => 'Central Standard Time (Mexico)',
            'Pacific/Efate' => 'Central Pacific Standard Time',
            'Pacific/Enderbury' => 'Tonga Standard Time',
            'Pacific/Fakaofo' => 'Hawaiian Standard Time',
            'Pacific/Fiji' => 'Fiji Standard Time',
            'Pacific/Funafuti' => 'Fiji Standard Time',
            'Pacific/Galapagos' => 'Central Standard Time',
            'Pacific/Gambier' => 'Alaskan Standard Time',
            'Pacific/Guadalcanal' => 'Central Pacific Standard Time',
            'Pacific/Guam' => 'West Pacific Standard Time',
            'Pacific/Honolulu' => 'Hawaiian Standard Time',
            'Pacific/Johnston' => 'Hawaiian Standard Time',
            'Pacific/Kiritimati' => 'Tonga Standard Time',
            'Pacific/Kosrae' => 'Central Pacific Standard Time',
            'Pacific/Kwajalein' => 'Fiji Standard Time',
            'Pacific/Majuro' => 'Fiji Standard Time',
            'Pacific/Marquesas' => 'Pacific Standard Time (Mexico)',
            'Pacific/Midway' => 'Samoa Standard Time',
            'Pacific/Nauru' => 'Fiji Standard Time',
            'Pacific/Niue' => 'Samoa Standard Time',
            'Pacific/Norfolk' => 'Central Pacific Standard Time',
            'Pacific/Noumea' => 'Central Pacific Standard Time',
            'Pacific/Pago_Pago' => 'Samoa Standard Time',
            'Pacific/Palau' => 'Tokyo Standard Time',
            'Pacific/Pitcairn' => 'Pacific Standard Time',
            'Pacific/Ponape' => 'Central Pacific Standard Time',
            'Pacific/Port_Moresby' => 'West Pacific Standard Time',
            'Pacific/Rarotonga' => 'Hawaiian Standard Time',
            'Pacific/Saipan' => 'West Pacific Standard Time',
            'Pacific/Tahiti' => 'Hawaiian Standard Time',
            'Pacific/Tarawa' => 'Fiji Standard Time',
            'Pacific/Tongatapu' => 'Tonga Standard Time',
            'Pacific/Truk' => 'West Pacific Standard Time',
            'Pacific/Wake' => 'Fiji Standard Time',
            'Pacific/Wallis' => 'Fiji Standard Time',
            'PST8PDT' => 'Pacific Standard Time',
            'WET' => 'W. Europe Standard Time',
            'Etc/GMT+9' => 'Alaskan Standard Time',
            'Asia/Riyadh87' => 'Arabic Standard Time',
            'Asia/Riyadh88' => 'Arabic Standard Time',
            'Asia/Riyadh89' => 'Arabic Standard Time',
            'Etc/GMT+4' => 'Atlantic Standard Time',
            'Etc/GMT-10' => 'AUS Eastern Standard Time',
            'Etc/GMT-4' => 'Azerbaijan Standard Time',
            'Etc/GMT+1' => 'Azores Standard Time',
            'Etc/GMT-1' => 'Central Europe Standard Time',
            'Etc/GMT-11' => 'Central Pacific Standard Time',
            'Etc/GMT+6' => 'Central Standard Time',
            'Etc/GMT+12' => 'Dateline Standard Time',
            'Etc/GMT-2' => 'E. Europe Standard Time',
            'Etc/GMT+3' => 'E. South America Standard Time',
            'Etc/GMT+5' => 'Eastern Standard Time',
            'Etc/GMT-5' => 'Ekaterinburg Standard Time',
            'Etc/GMT+10' => 'Hawaiian Standard Time',
            'Etc/GMT+2' => 'Mid-Atlantic Standard Time',
            'Etc/GMT+7' => 'Mountain Standard Time',
            'Etc/GMT-6' => 'N. Central Asia Standard Time',
            'Etc/GMT-12' => 'New Zealand Standard Time',
            'Etc/GMT-7' => 'North Asia Standard Time',
            'Etc/GMT+8' => 'Pacific Standard Time',
            'Etc/GMT-3' => 'Russian Standard Time',
            'Etc/GMT+11' => 'Samoa Standard Time',
            'Etc/GMT-13' => 'Tonga Standard Time',
            'Etc/GMT-14' => 'Tonga Standard Time',
            'Etc/GMT-8' => 'W. Australia Standard Time',
            'Etc/GMT-9' => 'Yakutsk Standard Time'
        );
        $this->dst_start = $dst_start;
        $this->time_start = $time_start;
        $this->dst_stop = $dst_stop;
        $this->time_stop = $dst_stop;
        $this->tz = $tz;
        $this->WindowsTimeZones = $WindowsTimeZones;
        $this->offset = $offset;
        $this->Hoffset = $Hoffset;
        $this->form = $form;
        $this->transition = $transition;
    }

    /**
     * 
     * @param type $array
     * @param type $key
     * Sort an array based on a given key->value
     */
    public function aasort(&$array = array(), $key = '') {

        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
    }

    /**
     * 
     * @param type $dst_start
     * @param type $time_start
     * @param type $dst_stop
     * @param type $time_stop
     * @param string $TZ
     * @return modified tz adding daylight data (POSIX)
     */
    public function get_time_value($dst_start, $time_start, $dst_stop, $time_stop, $TZ) {

        /* get time zonevalue and dst values and return the string for /etc/environment */

        /* prepare the variable */

        $dst_start_array = explode('-', $dst_start);
        $dst_stop_array = explode('-', $dst_stop);
        $time_start_array = explode(':', $time_start);
        $time_stop_array = explode(':', $time_stop);

        $dst_month_start = intval($dst_start_array[1]);
        $dst_day_start = intval($dst_start_array[0]);
        $dst_hour_start = $time_start_array[0];
        $dst_minute_start = $time_start_array[1];
        $dst_month_stop = intval($dst_stop_array[1]);
        $dst_day_stop = intval($dst_stop_array[0]);
        $dst_hour_stop = $time_stop_array[0];
        $dst_minute_stop = $time_stop_array[1];
        $year = date("Y");

        $dst_week_start = exec("/var/www/SPOT/web/current/site/SystemProvisioning/provisioning/scripts/timezone/week_no.sh " . $dst_day_start, $ret);

        $dst_day_start_week = jddayofweek(cal_to_jd(CAL_GREGORIAN, $dst_month_start, $dst_day_start, $year));

        $dst_week_stop = exec("/var/www/SPOT/web/current/site/SystemProvisioning/provisioning/scripts/timezone/week_no.sh " . $dst_day_stop, $ret);
        $dst_day_stop_week = jddayofweek(cal_to_jd(CAL_GREGORIAN, $dst_month_stop, $dst_day_stop, $year));
        $TZ_DST = ",M" . $dst_month_start . "." . $dst_week_start . "." . $dst_day_start_week . "/" . $dst_hour_start . ":" . $dst_minute_start . ":00,M" . $dst_month_stop . "." . $dst_week_stop . "." . $dst_day_stop_week . "/" . $dst_hour_stop . ":" . $dst_minute_stop . ":00";
        $dst_code = str_replace('ST', 'DT', exec("echo " . $TZ . " | tr -d  \"\-[:digit:]\"", $ret));
        $dst_code = str_replace('NFT', 'DFT', $dst_code);
        $dst_code = str_replace('EET', 'EETDT', $dst_code);
        $dst_code = str_replace('MET', 'METDT', $dst_code);
        $TZ .= $dst_code . $TZ_DST;
        $this->tz = $TZ;
        return $this->tz;
    }

    /**
     * 
     * @param type $tz
     * @return all timezones if arg NULL  in an array containing all information
     * if arg is given, the array will contain only information about the given timezone
     * this is a multidimesnional array where root key is the timezone
     * 
     * 
     */
    public function getTimeZones($tz = NULL) {
       
        if ($tz == NULL) {
             $this->nowTime = time(); # specific date/time we're checking, in epoch seconds. 
        $this->m4Time = time() + (17 * 7 * 24 * 60 * 60);
        $this->m8Time = time() + (34 * 7 * 24 * 60 * 60);


        $this->year = date("Y");
            foreach (DateTimeZone::listIdentifiers() as $this->tz) {
                $this->current_tz = new DateTimeZone($this->tz);


//Get the transition in 4 months
                $this->transition = $this->current_tz->getTransitions($this->m4Time);


                $this->form[$this->tz]['isdst_m4'] = $this->transition[0]['isdst'];
                !$this->form[$this->tz]['isdst_m4'] ? $this->form[$this->tz]['offset'] = $this->transition[0]['offset'] :
//Get the transition now
                                $this->transition = $this->current_tz->getTransitions($this->nowTime);

                $this->form[$this->tz]['isdst_now'] = $this->transition[0]['isdst'];
                !$this->form[$this->tz]['isdst_now'] ? $this->form[$this->tz]['offset'] = $this->transition[0]['offset'] :
//Get the transition in 8 months
                                $this->transition = $this->current_tz->getTransitions($this->m8Time);

                $this->form[$this->tz]['isdst_m8'] = $this->transition[0]['isdst'];
                !$this->form[$this->tz]['isdst_m8'] ? $this->form[$this->tz]['offset'] = $this->transition[0]['offset'] :
                                $this->form[$this->tz]['loc'] = $this->current_tz->getLocation();

                $this->form[$this->tz]['Hoffset'] = intval($this->form[$this->tz]['offset']) / 3600;
            }
        } else {
             $this->nowTime = time(); # specific date/time we're checking, in epoch seconds. 
        $this->m4Time = time() + (17 * 7 * 24 * 60 * 60);
        $this->m8Time = time() + (34 * 7 * 24 * 60 * 60);


        $this->year = date("Y");
            $this->tz = $tz;

            $this->current_tz = new DateTimeZone($this->tz);
            $this->city = explode('/', $this->tz);
            $this->timeanddate = "http://www.timeanddate.com/worldclock/results.html?query=" . end($this->city);
           


//Get the transition in 4 months
            $this->transition = $this->current_tz->getTransitions($this->m4Time);
            $this->form['abbr_m4'] = $this->transition[0]['abbr'];
            $this->form['isdst_m4'] = $this->transition[0]['isdst'];
            $this->form['isdst_m4'] == false ? $this->form['offset'] = $this->transition[0]['offset'] :
//Get the transition now
            $this->transition = $this->current_tz->getTransitions($this->nowTime);
            $this->form['abbr_now'] = $this->transition[0]['abbr'];
            $this->form['isdst_now'] = $this->transition[0]['isdst'];
            $this->form['isdst_now'] == false ? $this->form['offset'] = $this->transition[0]['offset'] :
//Get the transition in 8 months
            $this->transition = $this->current_tz->getTransitions($this->m8Time);
            $this->form['abbr_m8'] = $this->transition[0]['abbr'];
            $this->form['isdst_m8'] = $this->transition[0]['isdst'];
            $this->form['isdst_m8'] == false ? $this->form['offset'] = $this->transition[0]['offset'] :
            $this->form['isdst'] = false;
            $this->form['isdst_now'] == true ? $this->form['isdst'] = true :
            $this->form['isdst_m4'] == true ? $this->form['isdst'] = true :
            $this->form['isdst_m8'] == true ? $this->form['isdst'] = true :
                
            //$this->form['isdst_now'] == true || $this->form['isdst_m4'] == true || $this->form['isdst_m8'] == true ? $this->form['isdst'] = true : $this->form['isdst'] = false;
            $this->form['loc'] = $this->current_tz->getLocation();
            $this->form['isdst'] == true ? $this->form['dst'] = "<a target=\"_blank\" href=\"$this->timeanddate\"><img src='/css/crud/images/on.png' title='Click this link to check in timeanddate.com web site' /></a> The DST has been detected" : $this->form['dst'] = "<a target=\"_blank\" href=\"$this->timeanddate\"><img src='/css/crud/images/off.png' title='Click this link to check in timeanddate.com web site' /></a> The DST has NOT been detected";
            $this->form['Hoffset'] = intval($this->form['offset']) / 3600;
            $this->form['aix_offset'] = $this->form['Hoffset'] * -1;
            isset($this->WindowsTimeZones[$this->tz]) ? $this->form['win_tz'] = $this->WindowsTimeZones[$this->tz] : $this->form['win_tz'] = 'Not found in windows database';
            $this->form['aix_tz'] = $this->form['abbr_now'] . $this->form['aix_offset'];
        }

        return $this->form;
    }

}
