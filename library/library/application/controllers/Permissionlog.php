<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissionlog extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('permissionlog_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('permissionlog', $lang);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line('permissionlog_name'),
                'rules' => 'trim|xss_clean|required|max_length[60]|callback_check_unique_name',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line('permissionlog_description'),
                'rules' => 'trim|xss_clean|required|max_length[255]',
            ),
            array(
                'field' => 'active',
                'label' => $this->lang->line('permissionlog_active'),
                'rules' => 'trim|xss_clean|required|callback_check_active_value',
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
        $this->data['permissionlogs'] = $this->permissionlog_m->get_permissionlog();

        $this->data["subview"] = "permissionlog/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "permissionlog/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array                = [];
                $array['name']        = $this->input->post('name');
                $array['description'] = $this->input->post('description');
                $array['active']      = $this->input->post('active');

                $this->permissionlog_m->insert_permissionlog($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('permissionlog/index'));
            }
        } else {
            $this->data["subview"] = "permissionlog/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $permissionlogID = escapeString($this->uri->segment('3'));
        if ((int) $permissionlogID) {
            $this->data['permissionlog'] = $this->permissionlog_m->get_single_permissionlog($permissionlogID);
            if (calculate($this->data['permissionlog'])) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "permissionlog/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array['name']        = $this->input->post('name');
                        $array['description'] = $this->input->post('description');
                        $array['active']      = $this->input->post('active');

                        $this->permissionlog_m->update_permissionlog($array, $permissionlogID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('permissionlog/index'));
                    }
                } else {
                    $this->data["subview"] = "permissionlog/edit";
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
        $permissionlogID = escapeString($this->uri->segment('3'));
        if ((int) $permissionlogID) {
            $permissionlog = $this->permissionlog_m->get_single_permissionlog($permissionlogID);
            if (calculate($permissionlog)) {
                $this->permissionlog_m->delete_permissionlog($permissionlogID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('permissionlog/index'));
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function check_unique_name($name)
    {
        $permissionlogID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $permissionlogID) {
            $member = $this->permissionlog_m->get_single_permissionlog(array('name' => $name, 'permissionlogID !=' => $permissionlogID));
            if (calculate($member)) {
                $this->form_validation->set_message("check_unique_name", "The %s is already exits.");
                return false;
            }
            return true;
        } else {
            $member = $this->permissionlog_m->get_single_permissionlog(array('name' => $name));
            if (calculate($member)) {
                $this->form_validation->set_message("check_unique_name", "The %s is already exits.");
                return false;
            }
            return true;
        }
    }

    public function check_active_value($data)
    {
        $array = array('yes', 'no');
        if (in_array($data, $array) == false) {
            $this->form_validation->set_message("check_active_value", "The %s fields only provide yes or no value.");
            return false;
        }
        return true;
    }

}
