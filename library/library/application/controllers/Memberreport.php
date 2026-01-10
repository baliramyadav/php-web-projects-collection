<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Memberreport extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('member_m');
        $this->load->library('pdf');

        $lang = $this->session->userdata('language');
        $this->lang->load('memberreport', $lang);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/memberreport.js',
            ),
        );
        $this->data['flag']         = 0;
        $this->data['roleID']       = 0;
        $this->data['memberID']     = 0;
        $this->data['bloodgroupID'] = 0;
        $this->data['status']       = 0;

        $this->data['roles']   = pluck($this->role_m->get_role(), 'obj', 'roleID');
        $this->data['members'] = [];
        unset($_SESSION['error']);
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $message = implode('<br/>', $this->form_validation->error_array());
                $this->session->set_flashdata('error', $message);
                $this->data["subview"] = "report/member/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $roleID       = $this->input->post('roleID');
                $memberID     = $this->input->post('memberID');
                $bloodgroupID = $this->input->post('bloodgroupID');
                $status       = $this->input->post('status');

                $queryArray = [];
                if ((int) $roleID) {
                    $queryArray['roleID'] = $roleID;
                }
                if ((int) $memberID) {
                    $queryArray['memberID'] = $memberID;
                }
                if ((int) $bloodgroupID) {
                    $queryArray['bloodgroup'] = $bloodgroupID;
                }
                if ((int) $status) {
                    $queryArray['status'] = $status;
                }
                $queryArray['deleted_at'] = 0;
                $members                  = $this->member_m->get_order_by_member($queryArray);

                $this->data['flag']         = 1;
                $this->data['roleID']       = 0;
                $this->data['memberID']     = 0;
                $this->data['bloodgroupID'] = 0;
                $this->data['status']       = 0;
                $this->data['members']      = $members;

                $this->data["subview"] = "report/member/index";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "report/member/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function pdf()
    {
        $roleID       = htmlentities(escapeString($this->uri->segment(3)));
        $memberID     = htmlentities(escapeString($this->uri->segment(4)));
        $bloodgroupID = htmlentities(escapeString($this->uri->segment(5)));
        $status       = htmlentities(escapeString($this->uri->segment(6)));

        if (((int) $roleID || $roleID == 0) && ((int) $memberID || $memberID == 0) && ((int) $bloodgroupID || $bloodgroupID == 0) && ((int) $status || $status == 0)) {

            $queryArray = [];
            if ((int) $roleID) {
                $queryArray['roleID'] = $roleID;
            }
            if ((int) $memberID) {
                $queryArray['memberID'] = $memberID;
            }
            if ((int) $bloodgroupID) {
                $queryArray['bloodgroup'] = $bloodgroupID;
            }
            if ((int) $status) {
                $queryArray['status'] = $status;
            }
            $queryArray['deleted_at'] = 0;
            $members                  = $this->member_m->get_order_by_member($queryArray);

            $this->data['flag']         = 1;
            $this->data['roleID']       = 0;
            $this->data['memberID']     = 0;
            $this->data['bloodgroupID'] = 0;
            $this->data['status']       = 0;
            $this->data['members']      = $members;
            $this->data['roles']        = pluck($this->role_m->get_role(), 'obj', 'roleID');

            $this->pdf->create(['stylesheet' => 'memberreport.css', 'view' => 'report/member/pdf.php', 'data' => $this->data]);
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function get_member()
    {
        echo "<option value='0'>" . $this->lang->line('memberreport_please_select') . "</option>";
        if ($_POST && permissionChecker('memberreport')) {
            $roleID = $this->input->post('roleID');
            if ((int) $roleID) {
                $members = $this->member_m->get_order_by_member(array('roleID' => $roleID, 'status' => 1, 'deleted_at' => 0), array('memberID', 'name'));
                if (calculate($members)) {
                    foreach ($members as $member) {
                        echo "<option value='" . $member->memberID . "'>" . $member->name . "</option>";
                    }
                }
            }
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'roleID',
                'label' => $this->lang->line('memberreport_role'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'memberID',
                'label' => $this->lang->line('memberreport_member'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'bloodgroupID',
                'label' => $this->lang->line('memberreport_blood_group'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line('memberreport_status'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
        );
        return $rules;
    }

}
