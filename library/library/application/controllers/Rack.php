<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rack extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('rack_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('rack', $lang);
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

        $this->data['racks']   = $this->rack_m->get_rack();
        $this->data["subview"] = "rack/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "rack/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array                    = [];
                $array['name']            = $this->input->post('name');
                $array['description']     = $this->input->post('description');
                $array['create_date']     = date('Y-m-d H:i:s');
                $array['create_memberID'] = $this->session->userdata('loginmemberID');
                $array['create_roleID']   = $this->session->userdata('roleID');
                $array['modify_date']     = date('Y-m-d H:i:s');
                $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                $array['modify_roleID']   = $this->session->userdata('roleID');

                $this->rack_m->insert_rack($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('rack/index'));
            }
        } else {
            $this->data["subview"] = "rack/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $rackID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $rackID) {
            $this->data['rack'] = $this->rack_m->get_single_rack(array('rackID' => $rackID));
            if (calculate($this->data['rack'])) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "rack/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array                    = [];
                        $array['name']            = $this->input->post('name');
                        $array['description']     = $this->input->post('description');
                        $array['modify_date']     = date('Y-m-d H:i:s');
                        $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                        $array['modify_roleID']   = $this->session->userdata('roleID');

                        $this->rack_m->update_rack($array, $rackID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('rack/index'));
                    }
                } else {
                    $this->data["subview"] = "rack/edit";
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
        $rackID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $rackID) {
            $rack = $this->rack_m->get_single_rack(array('rackID' => $rackID));
            if (calculate($rack)) {
                $this->rack_m->delete_rack($rackID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('rack/index'));
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line('rack_name'),
                'rules' => 'trim|xss_clean|required|max_length[100]|callback_check_unique_rack',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line('rack_description'),
                'rules' => 'trim|xss_clean|required',
            ),
        );
        return $rules;
    }

    public function check_unique_rack($name)
    {
        $rackID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $rackID) {
            $rack = $this->rack_m->get_single_rack(array('name' => $name, 'rackID !=' => $rackID));
            if (calculate($rack)) {
                $this->form_validation->set_message("check_unique_rack", "The %s is already exits.");
                return false;
            }
            return true;
        } else {
            $rack = $this->rack_m->get_single_rack(array('name' => $name));
            if (calculate($rack)) {
                $this->form_validation->set_message("check_unique_rack", "The %s is already exits.");
                return false;
            }
            return true;
        }
    }

}
