<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paymentsetting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $lang = $this->session->userdata('language');
        $this->lang->load('paymentsetting', $lang);
    }

    public function index()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "paymentsetting/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array = [];
                for ($i = 0; $i < calculate($rules); $i++) {
                    $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
                }

                $configArray['stripe_key']    = $array['stripe_key'];
                $configArray['stripe_secret'] = $array['stripe_secret'];
                $this->config->config_update($configArray);

                $this->generalsetting_m->insertorupdate_generalsetting($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('paymentsetting/index'));
            }
        } else {
            $this->data["subview"] = "paymentsetting/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'paypal_payment_method',
                'label' => $this->lang->line('paymentsetting_paypal_payment_method'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'stripe_payment_method',
                'label' => $this->lang->line('paymentsetting_stripe_payment_method'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'stripe_key',
                'label' => $this->lang->line('paymentsetting_stripe_key'),
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'stripe_secret',
                'label' => $this->lang->line('paymentsetting_stripe_secret'),
                'rules' => 'trim|xss_clean',
            ),
        );
        return $rules;
    }
}
