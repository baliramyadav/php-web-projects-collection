<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exceptionpage extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data["subview"] = "_permission";
        $this->load->view('_main_layout', $this->data);
    }

    public function error()
    {
        $this->data["subview"] = "_permission";
        $this->load->view('_main_layout', $this->data);
    }

}
