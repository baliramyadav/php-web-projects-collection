<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('login_m');
        $this->load->model('member_m');
        $this->load->model('resetpassword_m');
        $this->load->library('applications');

        $lang = $this->session->userdata('language');
        $this->lang->load('login', $lang);
    }

    public function index()
    {
        $this->loggedCheck();
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['errors'] = $this->form_validation->error_array();
                $this->load->view('login/index', $this->data);
            } else {
                $array['username_or_email'] = $this->input->post('username_or_email');
                $array['password']          = $this->password_hash($this->input->post('password'));

                $member = $this->login_m->get_single_login_by_username_or_email_and_password($array);
                if (calculate($member) && $member->status == 1 && $member->roleID != 4) {
                    $role                          = $this->role_m->get_single_role(array('roleID' => $member->roleID));
                    $sessionArray                  = [];
                    $sessionArray['name']          = $member->name;
                    $sessionArray['username']      = $member->username;
                    $sessionArray['roleID']        = $member->roleID;
                    $sessionArray['role']          = calculate($role) ? $role->role : '';
                    $sessionArray['loginmemberID'] = $member->memberID;
                    $sessionArray['email']         = $member->email;
                    $sessionArray['photo']         = $member->photo;
                    $sessionArray['joinningdate']  = $member->joinningdate;
                    $sessionArray['language']      = 'english';
                    $sessionArray['loggedin']      = true;
                    $this->session->set_userdata($sessionArray);
                    redirect(base_url('dashboard/index'));
                } elseif (calculate($member) && $member->status == 2) {
                    $this->data['errors'] = ['message' => "You are now blocked. Please contact our admin. Thank You"];
                } elseif (calculate($member) && $member->status == 0) {
                    $this->data['errors'] = ['message' => "You are a new member. Please wait until to approved our admin. Thank You"];
                } elseif (calculate($member) && $member->roleID == 4) {
                    $this->data['errors'] = ['message' => "Customer cann't be login admin panel. Thank You"];
                } else {
                    $this->data['errors'] = ['message' => "You provide invalid username/email or password."];
                }
                $this->load->view('login/index', $this->data);
            }
        } else {
            $this->load->view('login/index', $this->data);
        }
    }

    public function resetpassword()
    {
        if ($_POST) {
            $rules = $this->rules_resetpassword();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['errors'] = $this->form_validation->error_array();
                $this->load->view('login/resetpassword', $this->data);
            } else {
                $username_or_email = $this->input->post('username_or_email');
                $member            = $this->login_m->get_single_login_check_by_username_or_email($username_or_email);
                if (calculate($member)) {
                    $resetArray['username']    = $member->username;
                    $resetArray['email']       = $member->email;
                    $resetArray['code']        = mt_rand(100000, 999999);
                    $resetArray['roleID']      = $member->roleID;
                    $resetArray['memberID']    = $member->memberID;
                    $resetArray['create_date'] = date('Y-m-d H:i:s');
                    $resetArray['modify_date'] = date('Y-m-d H:i:s');
                    $this->resetpassword_m->insert_resetpassword($resetArray);

                    $passArray                      = $resetArray;
                    $passArray['member']            = $member;
                    $passArray['username_or_email'] = $username_or_email;

                    $message = $this->load->view('_template/resetpassword', $passArray, true);
                    $this->applications->sendmail($member->email, $message, 'Reset Password', $member->name);
                    $this->session->set_flashdata('success', $this->lang->line('login_reset_your_password_checking'));
                    redirect(base_url('login/index'));
                } else {
                    $this->data['errors'] = array('message' => $this->lang->line('login_username_or_email_not_found'));
                    $this->load->view('login/resetpassword', $this->data);
                }
            }
        } else {
            $this->load->view('login/resetpassword', $this->data);
        }
    }

    public function registermember()
    {
        if(!$this->data["generalsetting"]->registration) {
            $this->session->set_flashdata('error', 'The new member registration is currently not allowed.');
            redirect('/');
        }
        if ($_POST) {
            $rules = $this->rules_registermember();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['errors'] = $this->form_validation->error_array();
                $this->load->view('login/registermember', $this->data);
            } else {
                $array                = [];
                $array['name']        = $this->input->post('name');
                $array['email']       = $this->input->post('email');
                $array['phone']       = $this->input->post('phone');
                $array['photo']       = $this->upload_data['file']['file_name'];
                $array['roleID']      = 3;
                $array['status']      = 0;
                $array['username']    = $this->input->post('username');
                $array['password']    = $this->password_hash($this->input->post('password'));
                $array['create_date'] = date('Y-m-d H:i:s');
                $array['modify_date'] = date('Y-m-d H:i:s');

                $this->login_m->insert_login($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('login/index'));
            }
        } else {
            $this->load->view('login/registermember', $this->data);
        }
    }

    public function resetpasswordconfirm()
    {
        $this->data['username_or_email'] = $_SERVER['QUERY_STRING'];
        if ($_POST) {
            $rules = $this->rules_resetpasswordconfirm();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['errors'] = $this->form_validation->error_array();
                $this->load->view('login/resetpasswordconfirm', $this->data);
            } else {
                $member = $this->login_m->get_single_login_check_by_username_or_email($this->input->post('username_or_email'));
                if (calculate($member)) {
                    $resetArray                      = [];
                    $resetArray['username_or_email'] = $this->input->post('username_or_email');
                    $resetArray['code']              = $this->input->post('verification_code');

                    $valid_members = $this->resetpassword_m->get_single_resetpassword_by_username_or_email_and_code($resetArray);
                    if (calculate($valid_members)) {
                        $password         = $this->input->post('password');
                        $confirm_password = $this->input->post('confirm_password');

                        if ($password == $confirm_password) {
                            $array['password']    = $this->password_hash($this->input->post('password'));
                            $array['modify_date'] = date('Y-m-d H:i:s');

                            $this->member_m->update_member($array, $member->memberID);
                            $this->session->set_flashdata('success', 'Your Password Successfully updated.');
                            redirect(base_url('login/index'));
                        } else {
                            $this->session->set_flashdata('error', 'The Password and confirm password not match.');
                            redirect(base_url('login/index'));
                        }
                    } else {
                        $this->session->set_flashdata('error', 'You gived wrong code.');
                        redirect(base_url('login/index'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'The Member not found.');
                    redirect(base_url('login/index'));
                }
            }
        } else {
            $this->load->view('login/resetpasswordconfirm', $this->data);
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_userdata(array('modulepermission_set' => []));
        redirect(base_url('login/index'));
    }

    public function rules()
    {
        $rules = array(
            array(
                'field' => 'username_or_email',
                'label' => $this->lang->line('login_username_or_email'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[60]|callback_valid_username_or_email_check',
            ),
            array(
                'field' => 'password',
                'label' => $this->lang->line('login_password'),
                'rules' => 'trim|xss_clean|required|min_length[6]|max_length[128]',
            ),
        );
        return $rules;
    }

    public function rules_resetpassword()
    {
        $rules = array(
            array(
                'field' => 'username_or_email',
                'label' => $this->lang->line('login_username_or_email'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[60]|callback_valid_username_or_email_check',
            ),
        );
        return $rules;
    }

    private function rules_registermember()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line('login_name'),
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line('login_email'),
                'rules' => 'trim|xss_clean|required|max_length[60]|valid_email|callback_check_unique_email',
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line('login_phone'),
                'rules' => 'trim|xss_clean|required|max_length[15]',
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line('login_photo'),
                'rules' => 'trim|xss_clean|max_length[200]|callback_photo_upload',
            ),
            array(
                'field' => 'username',
                'label' => $this->lang->line('login_username'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[60]',
            ),
            array(
                'field' => 'password',
                'label' => $this->lang->line('login_password'),
                'rules' => 'trim|xss_clean|required|min_length[6]|max_length[128]',
            ),
        );
        return $rules;
    }

    private function rules_resetpasswordconfirm()
    {
        $rules = array(
            array(
                'field' => 'username_or_email',
                'label' => $this->lang->line('login_username_or_email'),
                'rules' => 'trim|xss_clean|required|max_length[60]|callback_valid_username_or_email_check',
            ),
            array(
                'field' => 'verification_code',
                'label' => $this->lang->line('login_verification_code'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[11]',
            ),
            array(
                'field' => 'password',
                'label' => $this->lang->line('login_password'),
                'rules' => 'trim|xss_clean|required|min_length[6]|max_length[128]',
            ),
            array(
                'field' => 'confirm_password',
                'label' => $this->lang->line('login_confirm_password'),
                'rules' => 'trim|xss_clean|required|min_length[6]|max_length[128]|matches[password]',
            ),
        );
        return $rules;
    }

    private function loggedCheck()
    {
        $logged = $this->session->userdata('loggedin');
        if ($logged) {
            redirect(base_url('dashboard/index'));
        }
    }

    public function valid_username_or_email_check($username_or_email)
    {
        if ($username_or_email) {
            $email_pattern    = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
            $username_pattern = "/^[a-z\d]{4,20}$/";
            if (preg_match($email_pattern, $username_or_email)) {
                return true;
            } else {
                $check = calculate(explode(' ', $username_or_email));
                if ($check > 1) {
                    $this->form_validation->set_message("valid_username_or_email_check", $this->lang->line('login_remove_whitespace_username_email'));
                    return false;
                } elseif (preg_match($username_pattern, $username_or_email)) {
                    return true;
                } else {
                    $this->form_validation->set_message("valid_username_or_email_check", $this->lang->line('login_provide_username_email'));
                    return false;
                }
            }
        }
        return true;
    }

    public function photo_upload()
    {
        $new_file = "default.png";
        if ($_FILES["photo"]['name'] != "") {
            $file_name   = $_FILES["photo"]['name'];
            $random      = rand(1, 10000000000000000);
            $file_rename = hash('sha512', $random . $this->input->post('username') . config_item("encryption_key"));
            $explode     = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $new_file                = $file_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/member";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name']     = $new_file;
                $config['max_size']      = '2048';
                $config['max_width']     = '2000';
                $config['max_height']    = '2000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photo_upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photo_upload", "Please upload valid supported format file.");
                return false;
            }
        } else {
            $this->form_validation->set_message("photo_upload", "Please upload any supported format file.");
            return false;
        }
    }

    public function check_unique_email($email)
    {
        $member = $this->login_m->get_single_login(array('email' => $email));
        if (calculate($member)) {
            $this->form_validation->set_message("check_unique_email", $this->lang->line('login_unique_email_activate'));
            return false;
        }
        return true;
    }

}
