<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Smssetting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('smssetting_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('smssetting', $lang);

        if (config_item('demo')) {
            $this->session->set_flashdata('error', 'This sms setting module disable for demo version');
            redirect(base_url('dashboard/index'));
        }
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/smssetting.js',
            ),
        );
        $this->data['smssetting'] = (object) pluck($this->smssetting_m->get_smssetting(), 'optionvalue', 'optionkey');
        if ($_POST) {
            $rules = $this->muthofun_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "smssetting/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array = [];
                for ($i = 0; $i < calculate($rules); $i++) {
                    if ($this->input->post($rules[$i]['field']) == false) {
                        $array[$rules[$i]['field']] = '';
                    } else {
                        $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
                    }
                }

                $this->smssetting_m->insertorupdate_smssetting($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('smssetting/index'));
            }
        } else {
            $this->data["subview"] = "smssetting/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function muthofun_rules()
    {
        $rules = array(
            array(
                'field' => 'muthofun_username',
                'label' => $this->lang->line('smssetting_username'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'muthofun_password',
                'label' => $this->lang->line('smssetting_password'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'muthofun_originator',
                'label' => $this->lang->line('smssetting_originator'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            )
        );
        return $rules;
    }

}
