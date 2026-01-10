<?php

function escapeString($val)
{
    $ci     = &get_instance();
    $driver = $ci->db->dbdriver;
    if ($driver == 'mysqli') {
        $db  = get_instance()->db->conn_id;
        $val = mysqli_real_escape_string($db, $val);
    }
    return $val;
}

if (!function_exists('dump')) {
    function dump($var, $label = 'Dump', $echo = true)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        if ($echo == true) {
            echo $output;
        } else {
            return $output;
        }
    }
}

if (!function_exists('dd')) {
    function dd($var = "", $label = 'Dump', $echo = true)
    {
        dump($var, $label, $echo);
        exit;
    }
}

function pluck($array, $value, $key = null)
{
    $returnArray = array();
    if (calculate($array)) {
        foreach ($array as $item) {
            if ($key != null) {
                $returnArray[$item->$key] = strtolower($value) == 'obj' ? $item : $item->$value;
            } else {
                $returnArray[] = $item->$value;
            }
        }
    }
    return $returnArray;
}

function pluck_bind($array, $value, $concatFirst, $concatLast, $key = null)
{
    $returnArray = array();
    if (calculate($array)) {
        foreach ($array as $item) {
            if ($key != null) {
                $returnArray[$item->$key] = $concatFirst . $item->$value . $concatLast;
            } else {
                if ($value != null) {
                    $returnArray[] = $concatFirst . $item->$value . $concatLast;
                } else {
                    $returnArray[] = $concatFirst . $item . $concatLast;
                }
            }
        }
    }

    return $returnArray;
}

function pluck_multi_array($arrays, $val, $key = null)
{
    $retArray = array();
    if (calculate($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if (!empty($key)) {
                if (strtolower($val) == 'obj') {
                    $retArray[$array->$key][] = $array;
                } else {
                    $retArray[$array->$key][] = $array->$val;
                }
            } else {
                if (strtolower($val) == 'obj') {
                    $retArray[$i][] = $array;
                } else {
                    $retArray[$i][] = $array->$val;
                }
                $i++;
            }
        }
    }
    return $retArray;
}

function pluck_multi_array_key($arrays, $val, $fstKey = null, $sndKey = null)
{
    $retArray = array();
    if (calculate($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if (!empty($fstKey)) {
                if (strtolower($val) == 'obj') {
                    if (!empty($sndKey)) {
                        $retArray[$array->$fstKey][$array->$sndKey] = $array;
                    } else {
                        $retArray[$array->$fstKey][] = $array;
                    }
                } else {
                    if (!empty($sndKey)) {
                        $retArray[$array->$fstKey][$array->$sndKey] = $array->$val;
                    } else {
                        $retArray[$array->$fstKey][] = $array->$val;
                    }

                }
            } else {
                if (strtolower($val) == 'obj') {
                    if (!empty($sndKey)) {
                        $retArray[$i][$array->$sndKey] = $array;
                    } else {
                        $retArray[$i][] = $array;
                    }
                } else {
                    if (!empty($sndKey)) {
                        $retArray[$i][$array->$sndKey] = $array->$val;
                    } else {
                        $retArray[$i][] = $array->$val;
                    }
                }
                $i++;
            }
        }
    }
    return $retArray;
}

function btn_download($uri, $name)
{
    return anchor($uri, "<i class='fa fa-download'></i>", "class='btn btn-success btn-xs' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'");
}

function namesorting($string, $len = 14)
{
    $return = $string;
    if (isset($string) && $len) {
        if (strlen($string) > $len) {
            $return = substr($string, 0, $len - 2) . '..';
        } else {
            $return = $string;
        }
    }

    return $return;
}

function btn_view($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-check-square-o'></i>", "class='btn btn-success btn-xs' data-placement='auto' data-toggle='tooltip' data-original-title='" . $name . "'");
    }
}

function btn_edit($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-edit'></i>", "class='btn btn-warning btn-xs' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'");
    }
}

function btn_delete($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-trash-o'></i>",
            array(
                'onclick'             => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
                'class'               => 'btn btn-danger btn-xs',
                'data-placement'      => 'top',
                'data-toggle'         => 'tooltip',
                'data-original-title' => $name,
            )
        );
    }
}

function visibleButton($uri)
{
    $explodeUri = explode('/', $uri);
    $permission = $explodeUri[0] . '_' . $explodeUri[1];
    if (permissionChecker($permission)) {
        return true;
    }
    return false;
}

function visibleButtonMenu($uri)
{
    $explodeUri = explode('/', $uri);
    if (calculate($explodeUri)) {
        if (isset($explodeUri[0]) && isset($explodeUri[1])) {
            $permission = $explodeUri[0] . '_' . $explodeUri[1];
        } else {
            $permission = $explodeUri[0];
        }
        if (permissionChecker($permission)) {
            return true;
        }
    }
    return false;
}

function permissionChecker($data)
{
    $CI         = &get_instance();
    $permission = $CI->session->userdata('modulepermission_set');
    if (isset($permission[$data]) && $permission[$data] == 'yes') {
        return true;
    }
    return false;
}

function btn_view_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-check-square-o'></i>", "class='btn btn-success btn-xs' data-placement='auto' data-toggle='tooltip' data-original-title='" . $name . "'");
}

function btn_payment_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-credit-card-alt'></i>", "class='btn btn-primary btn-xs' data-placement='auto' data-toggle='tooltip' data-original-title='" . $name . "'");
}

function btn_edit_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-edit'></i>", "class='btn btn-warning btn-xs' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'");
}

function btn_delete_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-trash-o'></i>",
        array(
            'onclick'             => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'class'               => 'btn btn-danger btn-xs',
            'data-placement'      => 'top',
            'data-toggle'         => 'tooltip',
            'data-original-title' => $name,
        )
    );
}

function menu_treeview_show($array, $menulink, $text1, $text2)
{
    if (calculate($array)) {
        $retArray = [];
        foreach ($array as $menu) {
            $retArray[] = $menu['menulink'];
        }
        if (in_array($menulink, $retArray)) {
            return $text1;
        } else {
            return $text2;
        }
    }
    return "";
}

function profile_img($imagename = 'default.png', $path = 'uploads/member/')
{
    if ($imagename) {
        $filepath = $path . $imagename;
    } else {
        $filepath = $path . 'default.png';
    }
    if (file_exists(FCPATH . $filepath)) {
        $imgurl = base_url($filepath);
    } else {
        $imgurl = base_url('uploads/default/user.png');
    }
    return $imgurl;
}

function app_image_link($imagename = 'default.png', $path, $defaultimage = 'default.png')
{
    if ($imagename) {
        $filepath = $path . $imagename;
    } else {
        $filepath = $path . $defaultimage;
    }
    if (file_exists($filepath)) {
        $imgurl = base_url($filepath);
    } else {
        $imgurl = base_url('uploads/default/' . $defaultimage);
    }
    return $imgurl;
}

function app_date($date)
{
    if ($date != '') {
        return date('d M Y', strtotime($date));
    }
    return '';
}

function country_list()
{
    return array(
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "BQ" => "British Antarctic Territory",
        "IO" => "British Indian Ocean Territory",
        "VG" => "British Virgin Islands",
        "BN" => "Brunei",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CT" => "Canton and Enderbury Islands",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos [Keeling] Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo - Brazzaville",
        "CD" => "Congo - Kinshasa",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "CI" => "Côte d’Ivoire",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "NQ" => "Dronning Maud Land",
        "DD" => "East Germany",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "FQ" => "French Southern and Antarctic Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GG" => "Guernsey",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and McDonald Islands",
        "HN" => "Honduras",
        "HK" => "Hong Kong SAR China",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IM" => "Isle of Man",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JE" => "Jersey",
        "JT" => "Johnston Island",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Laos",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macau SAR China",
        "MK" => "Macedonia",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "FX" => "Metropolitan France",
        "MX" => "Mexico",
        "FM" => "Micronesia",
        "MI" => "Midway Islands",
        "MD" => "Moldova",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "ME" => "Montenegro",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar [Burma]",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NT" => "Neutral Zone",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "KP" => "North Korea",
        "VD" => "North Vietnam",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PC" => "Pacific Islands Trust Territory",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territories",
        "PA" => "Panama",
        "PZ" => "Panama Canal Zone",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "YD" => "People's Democratic Republic of Yemen",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn Islands",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RO" => "Romania",
        "RU" => "Russia",
        "RW" => "Rwanda",
        "RE" => "Réunion",
        "BL" => "Saint Barthélemy",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "MF" => "Saint Martin",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "RS" => "Serbia",
        "CS" => "Serbia and Montenegro",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and the South Sandwich Islands",
        "KR" => "South Korea",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syria",
        "ST" => "São Tomé and Príncipe",
        "TW" => "Taiwan",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania",
        "TH" => "Thailand",
        "TL" => "Timor-Leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UM" => "U.S. Minor Outlying Islands",
        "PU" => "U.S. Miscellaneous Pacific Islands",
        "VI" => "U.S. Virgin Islands",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "SU" => "Union of Soviet Socialist Republics",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "ZZ" => "Unknown or Invalid Region",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VA" => "Vatican City",
        "VE" => "Venezuela",
        "VN" => "Vietnam",
        "WK" => "Wake Island",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe",
        "AX" => "Åland Islands",
    );
}

function status_button($status)
{
    $CI = &get_instance();
    if ($status == 1) {
        return '<span class="btn btn-success btn-xs">' . $CI->lang->line('active') . '</span>';
    } elseif ($status == 2) {
        return '<span class="btn btn-danger btn-xs">' . $CI->lang->line('disable') . '</span>';
    } else {
        return '<span class="btn btn-primary btn-xs">' . $CI->lang->line('new') . '</span>';
    }
}

function get_two_date_diff($fdate, $sdate = null)
{
    if ($sdate) {
        $current_date = strtotime($sdate);
    } else {
        $current_date = time();
    }
    $expire_date = strtotime($fdate);
    $datediff    = $current_date - $expire_date;

    return round($datediff / (60 * 60 * 24));
}

function get_increament_decrement_date($date, $day = '+1')
{
    $date = strtotime("$day day", strtotime($date));
    return date("Y-m-d", $date);
}

function app_amount_format($amount)
{
    // $CI = &get_instance();
    // dd($CI->data);
    return number_format($amount, 2);

}

function generate_memberID($id = 0)
{
    return sprintf('%06d', $id);
}

function deleteAll($str)
{
    if (is_file($str)) {
        @unlink($str);
    } elseif (is_dir($str)) {
        $scan = glob(rtrim($str, '/') . '/*');
        foreach ($scan as $path) {
            @deleteAll($path);
        }
        @rmdir($str);
    }
}

function site_address($generalsetting)
{
    $site_address = $generalsetting->sitename . "<br/>";
    if ($generalsetting->phone) {
        $site_address .= $generalsetting->phone . "<br/>";
    }
    if ($generalsetting->email) {
        $site_address .= $generalsetting->email . "<br/>";
    }
    if ($generalsetting->address) {
        $site_address .= $generalsetting->address . "<br/>";
    }
    return $site_address;
}

function order_delivery_to($order)
{
    $delivery = $order->name . "<br/>";
    if ($order->mobile) {
        $delivery .= $order->mobile . "<br/>";
    }
    if ($order->email) {
        $delivery .= $order->email . "<br/>";
    }
    if ($order->address) {
        $delivery .= $order->address . "<br/>";
    }
    return $delivery;
}

function order_invoice_to($member)
{
    $invoice = $member->name . "<br/>";
    if ($member->phone) {
        $invoice .= $member->phone . "<br/>";
    }
    if ($member->email) {
        $invoice .= $member->email . "<br/>";
    }
    if ($member->address) {
        $invoice .= $member->address . "<br/>";
    }
    return $invoice;
}

function orderStatusArray()
{
    return [
        '5'  => 'Pending',
        '10' => 'Cancel',
        '15' => 'Reject',
        '20' => 'Accept',
        '25' => 'Process',
        '30' => 'Completed',
    ];
}

function orderStatus($status)
{
    $orderStatusArray = orderStatusArray();
    return isset($orderStatusArray[$status]) ? $orderStatusArray[$status] : '';
}

function orderPamentStatus($paymentStatus)
{
    $paymentStatusArray = [
        '5'  => 'Unpaid',
        '10' => 'Partial',
        '15' => 'Paid',
    ];
    return isset($paymentStatusArray[$paymentStatus]) ? $paymentStatusArray[$paymentStatus] : '';
}

function orderPamentMethod($paymentPaymentMethod)
{
    $paymentMethodArray = orderPaymentMethodArray();
    return isset($paymentMethodArray[$paymentPaymentMethod]) ? $paymentMethodArray[$paymentPaymentMethod] : '';
}

function orderPaymentMethodArray()
{
    return [
        '5'  => 'Cash On Delivery',
        '10' => 'Paypal',
        '15' => '2Checkout',
        '20' => 'Stripe',
    ];
}
