<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emailsetting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('emailsetting_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('emailsetting', $lang);

        if (config_item('demo')) {
            $this->session->set_flashdata('error', 'This email setting module disable for demo version');
            redirect(base_url('dashboard/index'));
        }
    }

    public function index()
    {
        $this->data['emailsetting'] = (object) pluck($this->emailsetting_m->get_emailsetting(), 'optionvalue', 'optionkey');
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "emailsetting/index";
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

                $this->emailsetting_m->insertorupdate_emailsetting($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('emailsetting/index'));
            }
        } else {
            $this->data["subview"] = "emailsetting/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'mail_driver',
                'label' => $this->lang->line('emailsetting_mail_driver'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'mail_host',
                'label' => $this->lang->line('emailsetting_mail_host'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'mail_port',
                'label' => $this->lang->line('emailsetting_mail_port'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'mail_username',
                'label' => $this->lang->line('emailsetting_mail_username'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'mail_password',
                'label' => $this->lang->line('emailsetting_mail_password'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'mail_encryption',
                'label' => $this->lang->line('emailsetting_mail_encryption'),
                'rules' => 'trim|xss_clean|max_length[60]',
            )
        );
        return $rules;
    }

}
