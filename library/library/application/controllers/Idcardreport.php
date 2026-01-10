<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Idcardreport extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('member_m');
        $this->load->library('barcode');
        $this->load->library('pdf');

        $lang = $this->session->userdata('language');
        $this->lang->load('idcardreport', $lang);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/idcardreport.js',
            ),
        );
        $this->data['flag']     = 0;
        $this->data['type']     = 0;
        $this->data['roleID']   = 0;
        $this->data['memberID'] = 0;
        $this->data['members']  = [];
        $this->data['roles']    = $this->role_m->get_role();
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $message = implode('<br/>', $this->form_validation->error_array());
                $this->session->set_flashdata('error', $message);
                $this->data["subview"] = "report/idcard/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $roleID   = $this->input->post('roleID');
                $memberID = $this->input->post('memberID');
                $type     = $this->input->post('type');

                $queryArray['roleID'] = $roleID;
                if ((int) $memberID) {
                    $queryArray['memberID'] = $memberID;
                }
                $queryArray['status']     = 1;
                $queryArray['deleted_at'] = 0;
                $members                  = $this->member_m->get_order_by_member($queryArray);
                if ($type == 2) {
                    $this->generatebarcode($members);
                }

                $this->data['flag']     = 1;
                $this->data['type']     = $type;
                $this->data['roleID']   = $roleID;
                $this->data['memberID'] = $memberID;
                $this->data['members']  = $members;
                $this->data["subview"]  = "report/idcard/index";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "report/idcard/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function pdf()
    {
        $roleID   = htmlentities(escapeString($this->uri->segment(3)));
        $memberID = htmlentities(escapeString($this->uri->segment(4)));
        $type     = htmlentities(escapeString($this->uri->segment(5)));

        if ((int) $roleID && ((int) $memberID || $memberID == 0) && (int) $type) {
            $queryArray['roleID'] = $roleID;
            if ((int) $memberID) {
                $queryArray['memberID'] = $memberID;
            }
            $queryArray['status']     = 1;
            $queryArray['deleted_at'] = 0;
            $members                  = $this->member_m->get_order_by_member($queryArray);
            if ($type == 2) {
                $this->generatebarcode($members);
            }

            $this->data['type']     = $type;
            $this->data['roleID']   = $roleID;
            $this->data['memberID'] = $memberID;
            $this->data['members']  = $members;

            $this->pdf->create(['stylesheet' => 'idcardreport.css', 'view' => 'report/idcard/pdf.php', 'data' => $this->data]);
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function generatebarcode($members)
    {
        if (calculate($members)) {
            foreach ($members as $member) {
                $memberID = generate_memberID($member->memberID);
                $this->barcode->generate($memberID, $memberID);
            }
        }
    }

    public function get_member()
    {
        echo "<option value='0'>" . $this->lang->line('idcardreport_please_select') . "</option>";
        if ($_POST && permissionChecker('idcardreport')) {
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
                'label' => $this->lang->line('idcardreport_role'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
            array(
                'field' => 'memberID',
                'label' => $this->lang->line('idcardreport_member'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line('idcardreport_type'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
        );
        return $rules;
    }

}
