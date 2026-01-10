<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Generalsetting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $lang = $this->session->userdata('language');
        $this->lang->load('generalsetting', $lang);
    }

    public function index()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "generalsetting/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array = [];
                for ($i = 0; $i < calculate($rules); $i++) {
                    $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
                }

                if (isset($this->upload_data['logo']['file_name']) && ($this->upload_data['logo']['file_name'] != '')) {
                    $array['logo'] = $this->upload_data['logo']['file_name'];
                } else {
                    unset($array['logo']);
                }

                $configArray['frontend'] = 'YES';
                if ($array['frontend'] == 0) {
                    $configArray['frontend'] = 'NO';
                }
                $this->config->config_update($configArray);

                $this->generalsetting_m->insertorupdate_generalsetting($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('generalsetting/index'));
            }
        } else {
            $this->data["subview"] = "generalsetting/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'logo',
                'label' => $this->lang->line('generalsetting_logo'),
                'rules' => 'trim|xss_clean|callback_logo_upload',
            ),
            array(
                'field' => 'sitename',
                'label' => $this->lang->line('generalsetting_sitename'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line('generalsetting_email'),
                'rules' => 'trim|xss_clean|required|max_length[255]|valid_email',
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line('generalsetting_phone'),
                'rules' => 'trim|xss_clean|required|max_length[255]|numeric',
            ),
            array(
                'field' => 'web_address',
                'label' => $this->lang->line('generalsetting_web_address'),
                'rules' => 'trim|xss_clean|required|max_length[255]',
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line('generalsetting_address'),
                'rules' => 'trim|xss_clean|required|max_length[255]',
            ),
            array(
                'field' => 'copyright_by',
                'label' => $this->lang->line('generalsetting_copyright_by'),
                'rules' => 'trim|xss_clean|required|max_length[255]',
            ),
            array(
                'field' => 'ebook_download',
                'label' => $this->lang->line('generalsetting_ebook_download'),
                'rules' => 'trim|xss_clean|required|max_length[255]',
            ),
            array(
                'field' => 'registration',
                'label' => $this->lang->line('generalsetting_registration'),
                'rules' => 'trim|xss_clean|required|max_length[255]',
            ),
            array(
                'field' => 'frontend',
                'label' => $this->lang->line('generalsetting_frontend'),
                'rules' => 'trim|xss_clean|required|max_length[255]',
            ),
            array(
                'field' => 'delivery_charge',
                'label' => $this->lang->line('generalsetting_delivery_charge'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
        );
        return $rules;
    }

    public function logo_upload()
    {
        if ($_FILES["logo"]['name'] != "") {
            $file_name = $_FILES["logo"]['name'];
            $explode   = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $random   = rand(1, 10000000000000000);
                $logo     = hash('sha512', $random . config_item("encryption_key"));
                $new_file = $logo . '.' . end($explode);

                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $config['max_size']      = '2048';
                $config['max_width']     = '2000';
                $config['max_height']    = '2000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("logo")) {
                    $this->form_validation->set_message("logo_upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['logo'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("logo_upload", "Invalid file");
                return false;
            }
        }
        return true;
    }

}
