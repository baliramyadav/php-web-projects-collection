<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Frontend_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generalsetting_m');
        $this->load->library('cart');

        if ($this->config->item('installed') == 'NO') {
            redirect(base_url('install/index'));
        }
        if ($this->config->item('frontend') == 'NO') {
            redirect(base_url('/'));
        }
        $this->load->database();

        $this->data["generalsetting"] = (object) pluck($this->generalsetting_m->get_generalsetting(), 'optionvalue', 'optionkey');

        $exception_uris = array(
            'myaccount/index',
            'myaccount/order',
            'frontend/checkout',
        );

        if (in_array(uri_string(), $exception_uris) == true) {
            $logged = $this->session->userdata('loggedin');
            if ($logged == false) {
                redirect(base_url('myaccount/login'));
            }
        }

        $this->data['activemenu'] = '';
        if ($this->uri->segment(1) != 'myaccount') {
            $this->data['activemenu'] = $this->uri->segment(2);
        }
        $this->data['get_title']     = $this->_get_title();
        $this->data["cart_contents"] = $this->cart->contents();
    }

    private function _get_title($title = null)
    {
        if (!$title) {
            $title = (empty($this->data['activemenu']) || $this->data['activemenu'] == 'index') ? 'Home Page' : $this->data['activemenu'];
        }
        return ucfirst($title) . " | " . $this->data["generalsetting"]->sitename;
    }

    public function loggedCheck()
    {
        $logged = $this->session->userdata('loggedin');
        if ($logged) {
            redirect(base_url('dashboard/index'));
        }
    }

    public function password_hash($password)
    {
        return hash('sha512', $password . $this->config->item('encryption_key'));
    }

}
