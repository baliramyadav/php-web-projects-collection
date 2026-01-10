<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookissuereport extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('book_m');
        $this->load->model('member_m');
        $this->load->model('bookissue_m');
        $this->load->model('bookcategory_m');
        $this->load->library('pdf');

        $lang = $this->session->userdata('language');
        $this->lang->load('bookissuereport', $lang);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css'      => array(
                'assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
            ),
            'headerjs' => array(
                'assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
            ),
            'js'       => array(
                'assets/custom/js/bookissuereport.js',
            ),
        );
        $this->data['flag']           = 0;
        $this->data['bookcategoryID'] = 0;
        $this->data['bookID']         = 0;
        $this->data['roleID']         = 0;
        $this->data['memberID']       = 0;
        $this->data['status']         = 0;
        $this->data['fromdate']       = 0;
        $this->data['todate']         = 0;

        $this->data['roles']         = pluck($this->role_m->get_role(), 'obj', 'roleID');
        $this->data['bookcategorys'] = $this->bookcategory_m->get_bookcategory();
        unset($_SESSION['error']);
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $message = implode('<br/>', $this->form_validation->error_array());
                $this->session->set_flashdata('error', $message);
                $this->data["subview"] = "report/bookissue/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $bookcategoryID = $this->input->post('bookcategoryID');
                $bookID         = $this->input->post('bookID');
                $roleID         = $this->input->post('roleID');
                $memberID       = $this->input->post('memberID');
                $status         = $this->input->post('status');
                $fromdate       = $this->input->post('fromdate');
                $todate         = $this->input->post('todate');

                $this->_queryArray(['bookcategoryID' => $bookcategoryID, 'bookID' => $bookID, 'roleID' => $roleID, 'memberID' => $memberID, 'status' => $status, 'fromdate' => $fromdate, 'todate' => $todate]);

                $this->data["subview"] = "report/bookissue/index";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "report/bookissue/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function pdf()
    {
        $bookcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        $bookID         = htmlentities(escapeString($this->uri->segment(4)));
        $roleID         = htmlentities(escapeString($this->uri->segment(5)));
        $memberID       = htmlentities(escapeString($this->uri->segment(6)));
        $status         = htmlentities(escapeString($this->uri->segment(7)));
        $fromdate       = htmlentities(escapeString($this->uri->segment(8)));
        $todate         = htmlentities(escapeString($this->uri->segment(9)));

        if (((int) $bookcategoryID || $bookcategoryID == 0) && ((int) $bookID || $bookID == 0) && ((int) $roleID || $roleID == 0) && ((int) $memberID || $memberID == 0) && ((int) $status || $status == 0) && ((int) $fromdate || $fromdate == 0) && ((int) $todate || $todate == 0)) {

            $this->_queryArray(['bookcategoryID' => $bookcategoryID, 'bookID' => $bookID, 'roleID' => $roleID, 'memberID' => $memberID, 'status' => $status, 'fromdate' => $fromdate, 'todate' => $todate]);

            $this->pdf->create(['stylesheet' => 'bookissuereport.css', 'view' => 'report/bookissue/pdf.php', 'data' => $this->data]);
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function _queryArray($qArray)
    {
        extract($qArray);

        $userArray  = [];
        $queryArray = [];
        if ((int) $roleID) {
            $queryArray['bookcategoryID'] = $bookcategoryID;
            if ((int) $memberID) {
                $queryArray['bookID'] = $bookID;
            }
        }
        if ((int) $roleID) {
            $userArray['roleID']  = $roleID;
            $queryArray['roleID'] = $roleID;
            if ((int) $memberID) {
                $userArray['memberID']  = $memberID;
                $queryArray['memberID'] = $memberID;
            }
        }
        $userArray['status']     = 1;
        $userArray['deleted_at'] = 0;

        if ((int) $status) {
            $queryArray['status'] = $status - 1;
        }
        if (!empty($fromdate) && !empty($todate)) {
            $queryArray['fromdate'] = date('Y-m-d', strtotime($fromdate));
            $queryArray['todate']   = date('Y-m-d', strtotime($todate));
        }

        $members = [];
        if (calculate($userArray)) {
            $members = $this->member_m->get_order_by_member($userArray);
        }
        $bookissues = [];
        if (calculate($userArray)) {
            $bookissues = $this->bookissue_m->get_order_by_bookissue_for_bookissuereport($queryArray);
        }

        $this->data['flag']           = 1;
        $this->data['bookcategoryID'] = $bookcategoryID;
        $this->data['bookID']         = $bookID;
        $this->data['roleID']         = $roleID;
        $this->data['memberID']       = $memberID;
        $this->data['status']         = $status;
        $this->data['fromdate']       = empty($fromdate) ? 0 : strtotime($fromdate);
        $this->data['todate']         = empty($todate) ? 0 : strtotime($todate);
        $this->data['members']        = pluck($members, 'obj', 'memberID');
        $this->data['books']          = pluck($this->book_m->get_book(), 'name', 'bookID');
        $this->data['bookissues']     = $bookissues;
    }

    public function get_book()
    {
        echo "<option value='0'>" . $this->lang->line('bookissuereport_please_select') . "</option>";
        if ($_POST && permissionChecker('bookissuereport')) {
            $bookcategoryID = $this->input->post('bookcategoryID');
            if ((int) $bookcategoryID) {
                $array['deleted_at']     = 0;
                $array['bookcategoryID'] = $bookcategoryID;
                $books                   = $this->book_m->get_order_by_book($array, array('bookID', 'name', 'codeno'));
                if (calculate($books)) {
                    foreach ($books as $book) {
                        echo "<option value='" . $book->bookID . "'>" . $book->name . ' - ' . $book->codeno . "</option>";
                    }
                }
            }
        }
    }

    public function get_member()
    {
        echo "<option value='0'>" . $this->lang->line('bookissuereport_please_select') . "</option>";
        if ($_POST && permissionChecker('bookissuereport')) {
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
                'field' => 'bookcategoryID',
                'label' => $this->lang->line('bookissuereport_book_category'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'bookID',
                'label' => $this->lang->line('bookissuereport_book'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'roleID',
                'label' => $this->lang->line('bookissuereport_role'),
                'rules' => 'trim|xss_clean|numeric',
            ),
            array(
                'field' => 'memberID',
                'label' => $this->lang->line('bookissuereport_member'),
                'rules' => 'trim|xss_clean|numeric',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line('bookissuereport_status'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'fromdate',
                'label' => $this->lang->line('bookissuereport_from_date'),
                'rules' => 'trim|xss_clean|valid_date',
            ),
            array(
                'field' => 'todate',
                'label' => $this->lang->line('bookissuereport_to_date'),
                'rules' => 'trim|xss_clean|valid_date|callback_date_check',
            ),
        );
        return $rules;
    }

    public function date_check()
    {
        $fromdate = $this->input->post('fromdate');
        $todate   = $this->input->post('todate');

        if ($fromdate != '' && $todate != '') {
            if (strtotime($fromdate) > strtotime($todate)) {
                $this->form_validation->set_message("date_check", "The to date can not be lowwer than fromdate.");
                return false;
            }
        } elseif ($fromdate == '' && $todate != '') {
            $this->form_validation->set_message("date_check", "The from date can not be empty.");
            return false;
        }
        return true;
    }

}
