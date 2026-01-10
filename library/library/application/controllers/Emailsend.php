<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emailsend extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('member_m');
        $this->load->model('emailsend_m');
        $this->load->model('emailtemplate_m');
        $this->load->library('applications');

        $lang = $this->session->userdata('language');
        $this->lang->load('emailsend', $lang);
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
        $this->data['roles']      = pluck($this->role_m->get_role(), 'role', 'roleID');
        $this->data['emailsends'] = $this->emailsend_m->get_order_by_emailsend(array('on_deleted' => 0));
        $this->data["subview"]    = "emailsend/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/plugins/summernote/summernote.css',
                'assets/plugins/select2/dist/css/select2.min.css',
            ),
            'js'  => array(
                'assets/plugins/summernote/summernote.min.js',
                'assets/plugins/select2/dist/js/select2.min.js',
                'assets/custom/js/emailsend.js',
            ),
        );
        $this->data['active_tab']     = 1;
        $this->data['roles']          = $this->role_m->get_role();
        $this->data['emailtemplates'] = $this->emailtemplate_m->get_order_by_emailtemplate(array('status' => 1));
        if ($_POST) {
            $emailtype = $this->input->post('emailtype');
            if ($emailtype == 1) {
                $this->data['active_tab'] = 1;
                $rules                    = $this->rules_email();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = "emailsend/add";
                    $this->load->view('_main_layout', $this->data);
                } else {
                    $members                  = $this->get_member_to_email_send($_POST);
                    $array                    = [];
                    $array['subject']         = $this->input->post('subject');
                    $array['message']         = trim($this->input->post('message'));
                    $array['sender_name']     = $this->get_sender_name($members);
                    $array['sender_memberID'] = json_encode($this->input->post('sender_memberID'));
                    $array['sender_roleID']   = $this->input->post('sender_roleID');
                    $array['emailtemplateID'] = $this->input->post('emailtemplateID');
                    $array['create_date']     = date('Y-m-d H:i:s');
                    $array['create_memberID'] = $this->session->userdata('loginmemberID');
                    $array['create_roleID']   = $this->session->userdata('roleID');

                    if (calculate($members)) {
                        $this->applications->sendemails($members, $this->input->post('message'), $this->input->post('subject'));
                        $this->emailsend_m->insert_emailsend($array);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('emailsend/index'));
                    } else {
                        $this->session->set_flashdata('error', 'Error');
                        redirect(base_url('emailsend/index'));
                    }
                }
            } elseif ($emailtype == 2) {
                $this->data['active_tab'] = 2;
                $rules                    = $this->rules_otheremail();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = "emailsend/add";
                    $this->load->view('_main_layout', $this->data);
                } else {
                    $array                    = [];
                    $array['subject']         = $this->input->post('othersubject');
                    $array['message']         = trim($this->input->post('othermessage'));
                    $array['email']           = $this->input->post('email');
                    $array['sender_name']     = $this->input->post('name');
                    $array['sender_memberID'] = 0;
                    $array['sender_roleID']   = 0;
                    $array['emailtemplateID'] = 0;
                    $array['create_date']     = date('Y-m-d H:i:s');
                    $array['create_memberID'] = $this->session->userdata('loginmemberID');
                    $array['create_roleID']   = $this->session->userdata('roleID');

                    $result = $this->applications->sendemail($this->input->post('email'), $this->input->post('othermessage'), $this->input->post('othersubject'), $this->input->post('name'));
                    if ($result) {
                        $this->emailsend_m->insert_emailsend($array);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('emailsend/index'));
                    } else {
                        $this->session->set_flashdata('error', 'Error');
                        redirect(base_url('emailsend/index'));
                    }
                }
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "emailsend/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function get_member_to_email_send($post)
    {
        $roleID          = $post['sender_roleID'];
        $sender_memberID = isset($post['sender_memberID']) ? $post['sender_memberID'] : [];

        if (calculate($sender_memberID)) {
            $members = $this->member_m->get_where_in_member('memberID', $sender_memberID, array('roleID' => $roleID, 'deleted_at' => 0));
        } else {
            $members = $this->member_m->get_order_by_member(array('roleID' => $roleID, 'deleted_at' => 0));
        }
        return $members;
    }

    private function get_sender_name($members)
    {
        $ret = "";
        $i   = 1;
        if (calculate($members)) {
            foreach ($members as $member) {
                if ($i == 1) {
                    $ret .= $member->name;
                } else {
                    $ret .= "," . $member->name;
                }
                $i++;
            }
        }
        return $ret;
    }

    public function view()
    {
        $emailsendID = htmlentities(escapeString($this->uri->segment('3')));
        if ((int) $emailsendID) {
            $this->data['emailsend'] = $this->emailsend_m->get_single_emailsend(array('emailsendID' => $emailsendID, 'on_deleted' => 0));
            if (calculate($this->data['emailsend'])) {
                $this->data['emailtemplates'] = pluck($this->emailtemplate_m->get_emailtemplate(), 'name', 'emailtemplateID');
                $this->data["subview"]        = "emailsend/view";
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
        $emailsendID = htmlentities(escapeString($this->uri->segment('3')));
        if ((int) $emailsendID) {
            $array               = [];
            $array['on_deleted'] = 1;
            $this->emailsend_m->update_emailsend($array, $emailsendID);
            $this->session->set_flashdata('success', 'Success');
            redirect(base_url('emailsend/index'));
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
            $file_name   = $_FILES["photo"]['name'];
            $random      = rand(1, 10000000000000000);
            $file_rename = hash('sha512', $random . config_item("encryption_key"));
            $explode     = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $new_file                = $file_rename . '.' . end($explode);
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

    public function get_member()
    {
        echo "<option value='0'>" . $this->lang->line('emailsend_all_member') . "</option>";
        if ($_POST && permissionChecker('emailsend_add')) {
            $roleID = $this->input->post('roleID');
            if ((int) $roleID) {
                $members = $this->member_m->get_order_by_member(array('roleID' => $roleID), array('memberID', 'name'));
                if (calculate($members)) {
                    foreach ($members as $member) {
                        echo "<option value='" . $member->memberID . "'>" . $member->name . "</option>";
                    }
                }
            }
        }
    }

    public function emailtemplate()
    {
        if ($_POST) {
            $emailtemplateID = $this->input->post('emailtemplateID');
            if ((int) $emailtemplateID) {
                $emialtemplate = $this->emailtemplate_m->get_single_emailtemplate($emailtemplateID);
                if (calculate($emialtemplate)) {
                    echo $emialtemplate->template;
                } else {
                    echo "error";
                }
            } else {
                echo "error";
            }
        } else {
            echo "error";
        }
    }

    protected function rules_email()
    {
        $rules = array(
            array(
                'field' => 'subject',
                'label' => $this->lang->line('emailsend_subject'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[60]',
            ),
            array(
                'field' => 'sender_roleID',
                'label' => $this->lang->line('emailsend_sender_role'),
                'rules' => 'trim|xss_clean|required|required_no_zero',
            ),
            array(
                'field' => 'sender_memberID[]',
                'label' => $this->lang->line('emailsend_sender_name'),
                'rules' => 'trim',
            ),
            array(
                'field' => 'emailtemplateID',
                'label' => $this->lang->line('emailsend_emailtemplate'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line('emailsend_message'),
                'rules' => 'trim|xss_clean|required',
            ),
        );
        return $rules;
    }

    protected function rules_otheremail()
    {
        $rules = array(
            array(
                'field' => 'othersubject',
                'label' => $this->lang->line('emailsend_othersubject'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[60]',
            ),
            array(
                'field' => 'name',
                'label' => $this->lang->line('emailsend_name'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[60]',
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line('emailsend_email'),
                'rules' => 'trim|xss_clean|required|valid_email',
            ),
            array(
                'field' => 'othermessage',
                'label' => $this->lang->line('emailsend_othermessage'),
                'rules' => 'trim|xss_clean|required',
            ),
        );
        return $rules;
    }

}
