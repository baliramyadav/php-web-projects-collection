<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emailtemplate extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('emailtemplate_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('emailtemplate', $lang);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css',
                'assets/custom/css/hidetable.css',
            ),
            'js'  => array(
                'assets/plugins/datatables.net/js/jquery.dataTables.min.js',
                'assets/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js',
            ),
        );
        $this->data['emailtemplates'] = $this->emailtemplate_m->get_emailtemplate();
        $this->data["subview"]        = "emailtemplate/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/plugins/summernote/summernote.css',
            ),
            'js'  => array(
                'assets/plugins/summernote/summernote.min.js',
                'assets/custom/js/emailtemplate.js',
            ),
        );
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "emailtemplate/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array                    = [];
                $array['name']            = $this->input->post('name');
                $array['template']        = $this->input->post('template');
                $array['status']          = $this->input->post('status');
                $array['create_date']     = date('Y-m-d H:i:s');
                $array['create_memberID'] = $this->session->userdata('loginmemberID');
                $array['create_roleID']   = $this->session->userdata('roleID');
                $array['modify_date']     = date('Y-m-d H:i:s');
                $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                $array['modify_roleID']   = $this->session->userdata('roleID');

                $this->emailtemplate_m->insert_emailtemplate($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('emailtemplate/index'));
            }
        } else {
            $this->data["subview"] = "emailtemplate/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/plugins/summernote/summernote.css',
            ),
            'js'  => array(
                'assets/plugins/summernote/summernote.min.js',
                'assets/custom/js/emailtemplate.js',
            ),
        );
        $emailtemplateID = htmlentities(escapeString($this->uri->segment('3')));
        if ((int) $emailtemplateID) {
            $this->data['emailtemplate'] = $this->emailtemplate_m->get_single_emailtemplate($emailtemplateID);
            if (calculate($this->data['emailtemplate'])) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "emailtemplate/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array                    = [];
                        $array['name']            = $this->input->post('name');
                        $array['template']        = $this->input->post('template');
                        $array['status']          = $this->input->post('status');
                        $array['modify_date']     = date('Y-m-d H:i:s');
                        $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                        $array['modify_roleID']   = $this->session->userdata('roleID');

                        $this->emailtemplate_m->update_emailtemplate($array, $emailtemplateID);
                        $this->session->set_flashdata('msg', 'Success');
                        redirect(base_url('emailtemplate/index'));
                    }
                } else {
                    $this->data["subview"] = "emailtemplate/edit";
                    $this->load->view('_main_layout', $this->data);
                }
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function view()
    {
        $emailtemplateID = escapeString($this->uri->segment('3'));
        if ((int) $emailtemplateID) {
            $emailtemplate = $this->emailtemplate_m->get_single_emailtemplate($emailtemplateID);
            if (calculate($emailtemplate)) {
                $this->data['emailtemplate'] = $emailtemplate;
                $this->data["subview"]       = "emailtemplate/view";
                $this->load->view('_main_layout', $this->data);
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function delete()
    {
        $emailtemplateID = escapeString($this->uri->segment('3'));
        if ((int) $emailtemplateID) {
            $emailtemplate = $this->emailtemplate_m->get_single_emailtemplate($emailtemplateID);
            if (calculate($emailtemplate)) {
                $this->emailtemplate_m->delete_emailtemplate($emailtemplateID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('emailtemplate/index'));
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function uploads()
    {
        $retArray           = [];
        $retArray['status'] = false;
        if ($_FILES["photo"]['name'] != "") {
            $file_name        = $_FILES["photo"]['name'];
            $random           = rand(1, 10000000000000000);
            $file_name_rename = hash('sha512', $random . config_item("encryption_key"));
            $explode          = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/summernote";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $config['max_size']      = '2048';
                $config['max_width']     = '2000';
                $config['max_height']    = '2000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $retArray['message'] = $this->upload->display_errors();
                } else {
                    $imagename          = $this->upload->data()['file_name'];
                    $retArray['photo']  = base_url('uploads/summernote/' . $imagename);
                    $retArray['status'] = true;
                }
            } else {
                $retArray['message'] = "Invalid File";
            }
        } else {
            $retArray['message'] = "Please Select File";
        }
        echo json_encode($retArray);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line('emailtemplate_name'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[60]',
            ),
            array(
                'field' => 'template',
                'label' => $this->lang->line('emailtemplate_template'),
                'rules' => 'trim|xss_clean|required|min_length[10]',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line('emailtemplate_status'),
                'rules' => 'trim|xss_clean|required|required_no_zero',
            ),
        );
        return $rules;
    }

}
