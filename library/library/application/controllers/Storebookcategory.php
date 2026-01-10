<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Storebookcategory extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('storebookcategory_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('storebookcategory', $lang);
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
        $this->data['storebookcategory'] = $this->storebookcategory_m->get_storebookcategory();
        $this->data["subview"]           = "storebookcategory/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/fileupload.js',
            ),
        );
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "storebookcategory/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array                    = [];
                $array['name']            = $this->input->post('name');
                $array['description']     = $this->input->post('description');
                $array['coverphoto']      = $this->upload_data['coverphoto']['file_name'];
                $array['status']          = $this->input->post('status');
                $array['create_date']     = date('Y-m-d H:i:s');
                $array['create_memberID'] = $this->session->userdata('loginmemberID');
                $array['modify_date']     = date('Y-m-d H:i:s');
                $array['modify_memberID'] = $this->session->userdata('loginmemberID');

                $this->storebookcategory_m->insert_storebookcategory($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('storebookcategory/index'));
            }
        } else {
            $this->data["subview"] = "storebookcategory/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/fileupload.js',
            ),
        );
        $storebookcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $storebookcategoryID) {
            $this->data['storebookcategory'] = $this->storebookcategory_m->get_single_storebookcategory(array('storebookcategoryID' => $storebookcategoryID));
            if (calculate($this->data['storebookcategory'])) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "storebookcategory/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array                    = [];
                        $array['name']            = $this->input->post('name');
                        $array['description']     = $this->input->post('description');
                        $array['coverphoto']      = $this->upload_data['coverphoto']['file_name'];
                        $array['status']          = $this->input->post('status');
                        $array['modify_date']     = date('Y-m-d H:i:s');
                        $array['modify_memberID'] = $this->session->userdata('loginmemberID');

                        $this->storebookcategory_m->update_storebookcategory($array, $storebookcategoryID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('storebookcategory/index'));
                    }
                } else {
                    $this->data["subview"] = "storebookcategory/edit";
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
        $storebookcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $storebookcategoryID) {
            $storebookcategory = $this->storebookcategory_m->get_single_storebookcategory(array('storebookcategoryID' => $storebookcategoryID));
            if (calculate($storebookcategory)) {
                if ($storebookcategory->coverphoto != 'storebookcategory.jpg') {
                    $deleleteimg = FCPATH . '/uploads/storebookcategory/' . $storebookcategory->coverphoto;
                    if (file_exists($deleleteimg)) {
                        unlink($deleleteimg);
                    }
                }

                $this->storebookcategory_m->delete_storebookcategory($storebookcategoryID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('storebookcategory/index'));
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
                'label' => $this->lang->line('storebookcategory_name'),
                'rules' => 'trim|xss_clean|required|max_length[100]|callback_check_unique_storebookcategory',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line('storebookcategory_description'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'coverphoto',
                'label' => $this->lang->line('storebookcategory_coverphoto'),
                'rules' => 'trim|xss_clean|max_length[200]|callback_coverphoto_upload',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line('storebookcategory_status'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
        );
        return $rules;
    }

    public function coverphoto_upload()
    {
        $storebookcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        $storebookcategory   = array();
        if ((int) $storebookcategoryID) {
            $storebookcategory = $this->storebookcategory_m->get_single_storebookcategory(array('storebookcategoryID' => $storebookcategoryID));
        }

        $new_file = "storebookcategory.jpg";
        if ($_FILES["coverphoto"]['name'] != "") {
            $file_name        = $_FILES["coverphoto"]['name'];
            $random           = rand(1, 10000000000000000);
            $file_name_rename = hash('sha512', $random . config_item("encryption_key"));
            $explode          = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/storebookcategory";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $config['max_size']      = '2048';
                $config['max_width']     = '2000';
                $config['max_height']    = '2000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("coverphoto")) {
                    $this->form_validation->set_message("coverphoto_upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['coverphoto'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("coverphoto_upload", "Invalid file");
                return false;
            }
        } else {
            if (calculate($storebookcategory)) {
                $this->upload_data['coverphoto'] = array('file_name' => $storebookcategory->coverphoto);
                return true;
            } else {
                $this->upload_data['coverphoto'] = array('file_name' => $new_file);
                return true;
            }
        }
    }

    public function check_unique_storebookcategory($name)
    {
        $storebookcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $storebookcategoryID) {
            $storebookcategory = $this->storebookcategory_m->get_single_storebookcategory(array('name' => $name, 'storebookcategoryID !=' => $storebookcategoryID));
        } else {
            $storebookcategory = $this->storebookcategory_m->get_single_storebookcategory(array('name' => $name));
        }
        if (calculate($storebookcategory)) {
            $this->form_validation->set_message("check_unique_storebookcategory", "The %s is already exits.");
            return false;
        }
        return true;
    }

}
