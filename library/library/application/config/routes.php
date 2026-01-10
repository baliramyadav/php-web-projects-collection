<?php
defined('BASEPATH') or exit('No direct script access allowed');

spl_autoload_register(function ($classname) {
    if (strpos($classname, 'CI_') !== 0) {
        $file = APPPATH . 'libraries/' . $classname . '.php';

        if (file_exists($file) && is_file($file)) {
            @include_once ($file);
        }
    }
});

$route['404_override']         = '';
$route['translate_uri_dashes'] = false;

if (config_item('installed') == 'NO') {
    $route["default_controller"] = "install";
} else if ($this->config->item('frontend') == 'NO') {
	$route['default_controller']   = 'dashboard';
} else {
	$route['default_controller']   = 'frontend';
}