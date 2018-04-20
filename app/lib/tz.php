<?php
class tz {

    static function get_map() {
        /**
         * Nice, succinct yet complete timezone list, ported from
         * http://www.unicode.org/cldr/data/docs/design/formatting/zone_log.html#windows_ids
         */
        return array(
            '-12'	=> array(
                '(STD) International Date Line West'		=> 'Etc/GMT+12'
            ),
            '-11'	=> array(
                '(STD) Midway Island, Samoa'				=> 'Pacific/Apia'
            ),
            '-10'	=> array(
                '(STD) Hawaii'								=> 'Pacific/Honolulu'
            ),
            '-9'	=> array(
                '(DST) Alaska'								=> 'America/Anchorage'
            ),
            '-8'	=> array(
                '(DST) Pacific Time (US & Canada); Tijuana'	=> 'America/Los_Angeles'
            ),
            '-7'	=> array(
                '(STD) Arizona'								=> 'America/Phoenix',
                '(DST) Mountain Time (US & Canada)'			=> 'America/Denver',
                '(DST) Chihuahua, La Paz, Mazatlan'			=> 'America/Chihuahua'
            ),
            '-6'	=> array(
                '(STD) Saskatchewan'						=> 'America/Regina',
                '(STD) Central America'						=> 'America/Managua',
                '(DST) Central Time (US & Canada)'			=> 'America/Chicago',
                '(DST) Guadalajara, Mexico City, Monterrey'	=> 'America/Mexico_City',
            ),
            '-5'	=> array(
                '(STD) Indiana (East)'						=> 'America/Indianapolis',
                '(STD) Bogota, Lima, Quito'					=> 'America/Bogota',
                '(DST) Eastern Time (US & Canada)'			=> 'America/New_York'
            ),
            '-4'	=> array(
                '(STD) Caracas, La Paz'						=> 'America/Caracas',
                '(DST) Atlantic Time (Canada)'				=> 'America/Halifax',
                '(DST) Santiago'							=> 'America/Santiago',
            ),
            '-3.5'	=> array(
                '(DST) Newfoundland'						=> 'America/St_Johns'
            ),
            '-3'	=> array(
                '(STD) Buenos Aires, Georgetown'			=> 'America/Buenos_Aires',
                '(DST) Greenland'							=> 'America/Godthab',
                '(DST) Brasilia'							=> 'America/Sao_Paulo'
            ),
            '-2'	=> array(
                '(DST) Mid-Atlantic'						=> 'America/Noronha'
            ),
            '-1'	=> array(
                '(STD) Cape Verde Is.'						=> 'Atlantic/Cape_Verde',
                '(DST) Azores'								=> 'Atlantic/Azores'
            ),
            '0'	=> array(
                '(STD) Casablanca, Monrovia'				=> 'Africa/Casablanca',
                '(DST) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London'	=> 'Europe/London'
            ),
            '1'	=> array(
                '(STD) West Central Africa'					=> 'Africa/Lagos',
                '(DST) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'	=> 'Europe/Berlin',
                '(DST) Brussels, Copenhagen, Madrid, Paris'	=> 'Europe/Paris',
                '(DST) Sarajevo, Skopje, Warsaw, Zagreb'	=> 'Europe/Sarajevo',
                '(DST) Belgrade, Bratislava, Budapest, Ljubljana, Prague'	=> 'Europe/Belgrade'
            ),
            '2'	=> array(
                '(STD) Harare, Pretoria'					=> 'Africa/Johannesburg',
                '(STD) Jerusalem'							=> 'Asia/Jerusalem',
                '(DST) Athens, Istanbul, Minsk'				=> 'Europe/Istanbul',
                '(DST) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'	=> 'Europe/Helsinki',
                '(DST) Cairo'								=> 'Africa/Cairo',
                '(DST) Bucharest'							=> 'Europe/Bucharest'
            ),
            '3'	=> array(
                '(STD) Nairobi'								=> 'Africa/Nairobi',
                '(STD) Kuwait, Riyadh'						=> 'Asia/Riyadh',
                '(DST) Moscow, St. Petersburg, Volgograd'	=> 'Europe/Moscow',
                '(DST) Baghdad'								=> 'Asia/Baghdad'
            ),
            '3.5'	=> array(
                '(DST) Tehran'								=> 'Asia/Tehran'
            ),
            '4'	=> array(
                '(STD) Abu Dhabi, Muscat'					=> 'Asia/Muscat',
                '(DST) Baku, Tbilisi, Yerevan'				=> 'Asia/Tbilisi',
            ),
            '4.5'	=> array(
                '(STD) Kabul'								=> 'Asia/Kabul'
            ),
            '5'	=> array(
                '(STD) Islamabad, Karachi, Tashkent'		=> 'Asia/Karachi',
                '(DST) Ekaterinburg'						=> 'Asia/Yekaterinburg'
            ),
            '5.5'	=> array(
                '(STD) Chennai, Kolkata, Mumbai, New Delhi'	=> 'Asia/Calcutta'
            ),
            '5.75'	=> array(
                '(STD) Kathmandu'							=> 'Asia/Katmandu'
            ),
            '6'	=> array(
                '(STD) Sri Jayawardenepura'					=> 'Asia/Colombo',
                '(STD) Astana, Dhaka'						=> 'Asia/Dhaka',
                '(DST) Almaty, Novosibirsk'					=> 'Asia/Novosibirsk'
            ),
            '6.5'	=> array(
                '(STD) Rangoon'								=> 'Asia/Rangoon'
            ),
            '7'	=> array(
                '(STD) Bangkok, Hanoi, Jakarta'				=> 'Asia/Bangkok',
                '(DST) Krasnoyarsk'							=> 'Asia/Krasnoyarsk'
            ),
            '8'	=> array(
                '(STD) Perth'								=> 'Australia/Perth',
                '(STD) Taipei'								=> 'Asia/Taipei',
                '(STD) Kuala Lumpur, Singapore'				=> 'Asia/Singapore',
                '(STD) Beijing, Chongqing, Hong Kong, Urumqi'	=> 'Asia/Hong_Kong',
                '(DST) Irkutsk, Ulaan Bataar'				=> 'Asia/Irkutsk'
            ),
            '9'	=> array(
                '(STD) Osaka, Sapporo, Tokyo'				=> 'Asia/Tokyo',
                '(STD) Seoul'								=> 'Asia/Seoul',
                '(DST) Yakutsk'								=> 'Asia/Yakutsk'
            ),
            '9.5'	=> array(
                '(STD) Darwin'								=> 'Australia/Darwin',
                '(DST) Adelaide'							=> 'Australia/Adelaide'
            ),
            '10'	=> array(
                '(STD) Guam, Port Moresby'					=> 'Pacific/Guam',
                '(STD) Brisbane'							=> 'Australia/Brisbane',
                '(DST) Vladivostok'							=> 'Asia/Vladivostok',
                '(DST) Hobart'								=> 'Australia/Hobart',
                '(DST) Canberra, Melbourne, Sydney'			=> 'Australia/Sydney'
            ),
            '11'	=> array(
                '(STD) Magadan, Solomon Is., New Caledonia'	=> 'Asia/Magadan'
            ),
            '12'	=> array(
                '(STD) Fiji, Kamchatka, Marshall Is.'		=> 'Pacific/Fiji',
                '(DST) Auckland, Wellington'				=> 'Pacific/Auckland'
            ),
            '13'	=> array(
                '(STD) Nuku\'alofa'							=> 'Pacific/Tongatapu'
            )
        );
    }
}