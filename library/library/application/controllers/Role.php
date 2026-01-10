<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role extends Admin_Controller
{
    public $notdeleteArray = [1, 2, 3];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('role', $lang);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'role',
                'label' => $this->lang->line('role_role'),
                'rules' => 'trim|xss_clean|required|max_length[30]|callback_check_unique_role',
            ),
        );
        return $rules;
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
        $this->data['roles'] = $this->role_m->get_role(array('roleID', 'role', 'create_date'));

        $this->data["subview"] = "role/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "role/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array                    = [];
                $array['role']            = $this->input->post('role');
                $array['create_date']     = date('Y-m-d H:i:s');
                $array['create_memberID'] = $this->session->userdata('loginmemberID');
                $array['create_roleID']   = $this->session->userdata('roleID');
                $array['modify_date']     = date('Y-m-d H:i:s');
                $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                $array['modify_roleID']   = $this->session->userdata('roleID');

                $this->role_m->insert_role($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('role/index'));
            }
        } else {
            $this->data["subview"] = "role/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $roleID = escapeString($this->uri->segment('3'));
        if ((int) $roleID) {
            $this->data['role'] = $this->role_m->get_single_role($roleID);
            if (calculate($this->data['role'])) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "role/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array                    = [];
                        $array['role']            = $this->input->post('role');
                        $array['modify_date']     = date('Y-m-d H:i:s');
                        $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                        $array['modify_roleID']   = $this->session->userdata('roleID');

                        $this->role_m->update_role($array, $roleID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('role/index'));
                    }
                } else {
                    $this->data["subview"] = "role/edit";
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

    public function delete()
    {
        $roleID = escapeString($this->uri->segment('3'));
        if ((int) $roleID) {
            $role = $this->role_m->get_single_role(array('roleID' => $roleID));
            if (calculate($role)) {
                if (!in_array($roleID, $this->notdeleteArray)) {
                    $this->role_m->delete_role($roleID);
                    $this->session->set_flashdata('success', 'Success');
                } else {
                    $this->session->set_flashdata('error', 'The Role Can\'t delete.');
                }
                redirect(base_url('role/index'));
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function check_unique_role($role)
    {
        $roleID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $roleID) {
            $role = $this->role_m->get_single_role(array('role' => $role, 'roleID !=' => $roleID));
            if (calculate($role)) {
                $this->form_validation->set_message("check_unique_role", "The %s is already exits.");
                return false;
            }
            return true;
        } else {
            $role = $this->role_m->get_single_role(array('role' => $role));
            if (calculate($role)) {
                $this->form_validation->set_message("check_unique_role", "The %s is already exits.");
                return false;
            }
            return true;
        }
    }

}
