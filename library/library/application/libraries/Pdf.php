<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'mpdf/vendor/autoload.php';

use Mpdf\Mpdf;

class Pdf
{
    protected $CI;
    public $data;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function create($params)
    {
        $stylesheet = "";
        if (file_exists(FCPATH . 'assets/pdf/' . $params['stylesheet'])) {
            $stylesheet = file_get_contents('assets/pdf/' . $params['stylesheet']);
        }

        $view = "";
        if (file_exists(APPPATH . 'views/' . $params['view'])) {
            $view = $this->CI->load->view($params['view'], $params['data'], true);
        }

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }

}
