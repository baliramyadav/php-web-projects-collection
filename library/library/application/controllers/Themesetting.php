<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Themesetting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('themesetting_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('themesetting', $lang);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/themesetting.js',
            ),
        );
        $this->data["subview"] = "themesetting/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function settheme()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                $this->session->set_flashdata('error', 'Error');
            } else {
                $array['settheme'] = $this->input->post('theme');
                $this->themesetting_m->insertorupdate_themesetting($array);
                $this->session->set_flashdata('success', 'Success');
            }
        } else {
            redirect(base_url('themesetting/index'));
        }

    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'theme',
                'label' => $this->lang->line('themesetting_themesetting'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
        );
        return $rules;
    }

}
