<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookissue extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_m');
        $this->load->model('role_m');
        $this->load->model('bookissue_m');
        $this->load->model('bookcategory_m');
        $this->load->model('book_m');
        $this->load->model('bookitem_m');
        $this->load->model('finehistory_m');
        $this->load->model('libraryconfigure_m');
        $this->load->model('paymentanddiscount_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('bookissue', $lang);
    }

    public function index()
    {
        $loginmemberID = $this->session->userdata('loginmemberID');
        if ($_POST) {
            $memberID = $this->input->post('memberID');
        } else {
            $memberID = htmlentities(escapeString($this->uri->segment(3)));
        }
        $this->data['headerassets'] = array(
            'css'      => array(
                'assets/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css',
                'assets/custom/css/hidetable.css',
            ),
            'headerjs' => array(
                'assets/plugins/datatables.net/js/jquery.dataTables.min.js',
                'assets/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js',
            ),
            'js'       => array(
                'assets/custom/js/bookissue.js',
            ),
        );
        if ((int) $memberID) {
            $issueArray['memberID'] = $memberID;
        }
        if ($this->checkAdminLibrarianPermission()) {
            $issueArray['memberID'] = $loginmemberID;
        }
        $issueArray['deleted_at'] = 0;
        $this->data['bookissues'] = $this->bookissue_m->get_order_by_bookissue($issueArray);

        $this->data['memberID']     = $memberID;
        $this->data['roles']        = pluck($this->role_m->get_role(), 'role', 'roleID');
        $this->data['members']      = pluck($this->member_m->get_member(), 'name', 'memberID');
        $this->data['bookcategory'] = pluck($this->bookcategory_m->get_bookcategory(), 'name', 'bookcategoryID');
        $this->data['book']         = pluck($this->book_m->get_book(), 'name', 'bookID');
        $this->data["subview"]      = "bookissue/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        $this->data['headerassets'] = array(
            'css'      => array(
                'assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
            ),
            'headerjs' => array(
                'assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
            ),
            'js'       => array(
                'assets/custom/js/bookissue.js',
            ),
        );

        $this->data['members'] = $this->member_m->get_order_by_member(['status' => 1, 'deleted_at' => 0], array('memberID', 'name'));
        $this->data['books']   = $this->book_m->get_order_by_book(array('status' => 0, 'deleted_at' => 0));

        $this->data['bookitems'] = [];
        $bookID                  = $this->input->post('bookID');
        if ((int) $bookID) {
            $this->data['bookitems'] = $this->bookitem_m->get_order_by_bookitem(array('bookID' => $bookID, 'status' => 0, 'deleted_at' => 0), array('bookno'));
        }

        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "bookissue/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $memberID = $this->input->post('memberID');
                $member   = $this->member_m->get_single_member(['memberID' => $memberID]);
                $book     = $this->book_m->get_single_book(['bookID' => $bookID]);

                $roleID           = $member->roleID;
                $libraryconfigure = $this->libraryconfigure_m->get_single_libraryconfigure(array('roleID' => $roleID));
                if (!calculate($libraryconfigure)) {
                    $libraryconfigure = (object) $this->libraryconfigure_m->libraryconfigure;
                }

                $issue_date = $this->input->post('issue_date');

                $array['roleID']            = $member->roleID;
                $array['memberID']          = $memberID;
                $array['bookcategoryID']    = $book->bookcategoryID;
                $array['bookID']            = $this->input->post('bookID');
                $array['bookno']            = $this->input->post('bookno');
                $array['notes']             = $this->input->post('notes');
                $array['issue_date']        = date('Y-m-d', strtotime($issue_date));
                $array['expire_date']       = date('Y-m-d', strtotime($issue_date . "+ $libraryconfigure->per_renew_limit_day days"));
                $array['renewed']           = 1;
                $array['max_renewed_limit'] = $libraryconfigure->max_renewed_limit;
                $array['book_fine_per_day'] = $libraryconfigure->book_fine_per_day;
                $array['fineamount']        = 0;
                $array['status']            = 0;
                $array['deleted_at']        = 0;
                $array['create_date']       = date('Y-m-d H:i:s');
                $array['create_memberID']   = $this->session->userdata('loginmemberID');
                $array['create_roleID']     = $this->session->userdata('roleID');
                $array['modify_date']       = date('Y-m-d H:i:s');
                $array['modify_memberID']   = $this->session->userdata('loginmemberID');
                $array['modify_roleID']     = $this->session->userdata('roleID');
                $bookissueID                = $this->bookissue_m->insert_bookissue($array);

                $bookitem = $this->bookitem_m->get_single_bookitem(['bookID' => $array['bookID'], 'bookno' => $array['bookno'], 'status' => 0, 'deleted_at' => 0]);
                if (calculate($bookitem)) {
                    $this->bookitem_m->update_bookitem(['status' => 1], $bookitem->bookitemID);
                }
                $bookitem = $this->bookitem_m->get_order_by_bookitem(['bookID' => $array['bookID'], 'status' => 0, 'deleted_at' => 0]);
                if (!calculate($bookitem)) {
                    $this->book_m->update_book(['status' => 1], $array['bookID']);
                }

                $fineArray                    = [];
                $fineArray['bookissueID']     = $bookissueID;
                $fineArray['bookstatusID']    = 0;
                $fineArray['renewed']         = 1;
                $fineArray['from_date']       = null;
                $fineArray['to_date']         = null;
                $fineArray['fineamount']      = 0;
                $fineArray['notes']           = null;
                $fineArray['create_date']     = date('Y-m-d H:i:s');
                $fineArray['create_memberID'] = $this->session->userdata('loginmemberID');
                $fineArray['create_roleID']   = $this->session->userdata('roleID');
                $fineArray['modify_date']     = date('Y-m-d H:i:s');
                $fineArray['modify_memberID'] = $this->session->userdata('loginmemberID');
                $fineArray['modify_roleID']   = $this->session->userdata('roleID');
                $this->finehistory_m->insert_finehistory($fineArray);

                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('bookissue/index'));
            }
        } else {
            $this->data["subview"] = "bookissue/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $bookissueID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookissueID) {
            $bookissue = $this->bookissue_m->get_single_bookissue(array('bookissueID' => $bookissueID, 'deleted_at' => 0, 'status' => 0, 'renewed' => 1));
            if (calculate($bookissue)) {
                $this->data['bookissue']    = $bookissue;
                $this->data['headerassets'] = array(
                    'css'      => array(
                        'assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
                    ),
                    'headerjs' => array(
                        'assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
                    ),
                    'js'       => array(
                        'assets/custom/js/bookissue.js',
                    ),
                );

                $this->data['members'] = $this->member_m->get_order_by_member(['status' => 1, 'deleted_at' => 0], array('memberID', 'name'));
                $this->data['books']   = $this->book_m->get_order_by_book(array('status' => 0, 'deleted_at' => 0));

                if ((int) $bookissue->bookID) {
                    $bookID = $bookissue->bookID;
                } else {
                    $bookID = $this->input->post('bookID');
                }

                $this->data['bookitems'] = [];
                if ((int) $bookID) {
                    $this->data['bookitems'] = $this->bookitem_m->get_order_by_bookitem(array('bookID' => $bookID, 'status' => 0, 'deleted_at' => 0), array('bookno'));
                }

                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "bookissue/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $memberID = $this->input->post('memberID');
                        $member   = $this->member_m->get_single_member(['memberID' => $memberID]);
                        $book     = $this->book_m->get_single_book(['bookID' => $bookID]);

                        $roleID           = $member->roleID;
                        $libraryconfigure = $this->libraryconfigure_m->get_single_libraryconfigure(array('roleID' => $roleID));
                        if (!calculate($libraryconfigure)) {
                            $libraryconfigure = (object) $this->libraryconfigure_m->libraryconfigure;
                        }
                        $array['roleID']            = $member->roleID;
                        $array['memberID']          = $memberID;
                        $array['bookcategoryID']    = $book->bookcategoryID;
                        $array['bookID']            = $bookID;
                        $array['bookno']            = $this->input->post('bookno');
                        $array['notes']             = $this->input->post('notes');
                        $array['issue_date']        = date('Y-m-d', strtotime($this->input->post('issue_date')));
                        $array['expire_date']       = date('Y-m-d', strtotime($this->input->post('issue_date') . "+ $libraryconfigure->per_renew_limit_day days"));
                        $array['renewed']           = $bookissue->renewed;
                        $array['max_renewed_limit'] = $libraryconfigure->max_renewed_limit;
                        $array['book_fine_per_day'] = $libraryconfigure->book_fine_per_day;
                        $array['modify_date']       = date('Y-m-d H:i:s');
                        $array['modify_memberID']   = $this->session->userdata('loginmemberID');
                        $array['modify_roleID']     = $this->session->userdata('roleID');

                        if (($bookissue->bookID != $array['bookID']) || ($bookissue->bookno != $array['bookno'])) {
                            $bookitem = $this->bookitem_m->get_single_bookitem(['bookID' => $bookissue->bookID, 'bookno' => $bookissue->bookno, 'status' => 1, 'deleted_at' => 0]);
                            if (calculate($bookitem)) {
                                $this->bookitem_m->update_bookitem(['status' => 0], $bookitem->bookitemID);
                            }
                        }

                        $bookitem = $this->bookitem_m->get_single_bookitem(['bookID' => $array['bookID'], 'bookno' => $array['bookno'], 'status' => 0, 'deleted_at' => 0]);
                        if (calculate($bookitem)) {
                            $this->bookitem_m->update_bookitem(['status' => 1], $bookitem->bookitemID);
                        }

                        $bookitem = $this->bookitem_m->get_single_bookitem(['bookID' => $array['bookID'], 'status' => 0, 'deleted_at' => 0]);
                        if (!calculate($bookitem)) {
                            $this->book_m->update_book(['status' => 1], $array['bookID']);
                        }

                        $this->bookissue_m->update_bookissue($array, $bookissueID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('bookissue/index'));
                    }
                } else {
                    $this->data["subview"] = "bookissue/edit";
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
        $bookissueID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookissueID) {
            $loginmemberID = $this->session->userdata('loginmemberID');
            if ($this->checkAdminLibrarianPermission()) {
                $issueArray['memberID'] = $loginmemberID;
            }
            $issueArray['bookissueID'] = $bookissueID;
            $issueArray['deleted_at']  = 0;
            $bookissue                 = $this->bookissue_m->get_single_bookissue($issueArray);
            if (calculate($bookissue)) {
                $this->data['bookissue'] = $bookissue;
                $this->data['member']    = $this->member_m->get_single_member(array('memberID' => $bookissue->memberID));
                $this->data['role']      = $this->role_m->get_single_role(array('roleID' => $bookissue->roleID));
                $this->data['book']      = $this->book_m->get_single_book(array('bookID' => $bookissue->bookID));
                $this->db->order_by('finehistoryID desc');
                $this->data['finehistory']         = $this->finehistory_m->get_order_by_finehistory(array('bookissueID' => $bookissueID));
                $this->data['paymentanddiscounts'] = $this->paymentanddiscount_m->get_order_by_paymentanddiscount(array('bookissueID' => $bookissueID));
                $this->data["subview"]             = "bookissue/view";
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
        $bookissueID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookissueID) {
            $bookissue = $this->bookissue_m->get_single_bookissue(array('bookissueID' => $bookissueID, 'deleted_at' => 0, 'status' => 0, 'renewed' => 1));
            if (calculate($bookissue)) {
                $bookitem = $this->bookitem_m->get_single_bookitem(['bookID' => $bookissue->bookID, 'bookno' => $bookissue->bookno, 'status !=' => 2, 'deleted_at' => 0]);
                if (calculate($bookitem)) {
                    $this->bookitem_m->update_bookitem(['status' => 0], $bookitem->bookitemID);
                    $this->book_m->update_book(['status' => 0], $array->bookID);
                }
                $this->bookissue_m->update_bookissue(['deleted_at' => 1], $bookissueID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('bookissue/index'));
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function renewandreturn()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/bookissue.js',
            ),
        );
        $bookissueID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookissueID) {
            $loginmemberID = $this->session->userdata('loginmemberID');
            if ($this->checkAdminLibrarianPermission()) {
                $issueArray['memberID'] = $loginmemberID;
            }
            $issueArray['bookissueID'] = $bookissueID;
            $issueArray['deleted_at']  = 0;
            $issueArray['status']      = 0;
            $bookissue                 = $this->bookissue_m->get_single_bookissue($issueArray);
            if (calculate($bookissue)) {
                $this->data['bookissue'] = $bookissue;
                $this->data['member']    = $this->member_m->get_single_member(array('memberID' => $bookissue->memberID));
                $this->data['role']      = $this->role_m->get_single_role(array('roleID' => $bookissue->roleID));
                $this->data['book']      = $this->book_m->get_single_book(array('bookID' => $bookissue->bookID));

                $this->db->order_by('finehistoryID desc');
                $this->data['finehistory']         = $this->finehistory_m->get_order_by_finehistory(array('bookissueID' => $bookissueID));
                $this->data['paymentanddiscounts'] = $this->paymentanddiscount_m->get_order_by_paymentanddiscount(array('bookissueID' => $bookissueID));
                if ($_POST) {
                    $rules = $this->rules_renewandreturn();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "bookissue/renewandreturn";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $bookstatusID          = $this->input->post('bookstatusID');
                        $array                 = [];
                        $array['bookissueID']  = $bookissueID;
                        $array['bookstatusID'] = ($bookstatusID - 1);
                        if ($bookstatusID < 2) {
                            $array['renewed'] = calculate($bookissue) ? ($bookissue->renewed + 1) : 0;
                        } else {
                            $array['renewed'] = calculate($bookissue) ? $bookissue->renewed : 0;
                        }

                        if (($this->input->post('fineamount') > 0) && (strtotime($bookissue->expire_date) < strtotime(date('Y-m-d')))) {
                            $from_date = get_increament_decrement_date(date('d-m-Y', strtotime($bookissue->expire_date)));
                            $to_date   = get_increament_decrement_date(date('d-m-Y'), '-1');

                            $array['from_date'] = $from_date;
                            $array['to_date']   = $to_date;
                        } else {
                            $array['from_date'] = null;
                            $array['to_date']   = null;
                        }
                        $array['fineamount']      = $this->input->post('fineamount');
                        $array['notes']           = $this->input->post('notes');
                        $array['create_date']     = date('Y-m-d H:i:s');
                        $array['create_memberID'] = $this->session->userdata('loginmemberID');
                        $array['create_roleID']   = $this->session->userdata('roleID');
                        $array['modify_date']     = date('Y-m-d H:i:s');
                        $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                        $array['modify_roleID']   = $this->session->userdata('roleID');

                        $this->finehistory_m->insert_finehistory($array);

                        $roleID           = $bookissue->roleID;
                        $libraryconfigure = $this->libraryconfigure_m->get_single_libraryconfigure(array('roleID' => $roleID));
                        if (!calculate($libraryconfigure)) {
                            $libraryconfigure = $this->libraryconfigure_m->libraryconfigure;
                        }

                        if ($bookstatusID < 2) {
                            $issueArray['renewed'] = $bookissue->renewed + 1;
                        }
                        $issueArray['fineamount'] = $bookissue->fineamount + $this->input->post('fineamount');
                        $issueArray['status']     = ($bookstatusID - 1);

                        if ($bookissue->paymentamount > 0) {
                            $issueArray['paidstatus'] = 1;
                        } else {
                            $issueArray['paidstatus'] = 0;
                        }

                        if (strtotime(date('Y-m-d')) <= strtotime($bookissue->expire_date)) {
                            $issueArray['expire_date'] = date('Y-m-d', strtotime($bookissue->expire_date . "+ $libraryconfigure->per_renew_limit_day days"));
                        } else {
                            $issueArray['expire_date'] = date('Y-m-d', strtotime(date('d-m-Y') . "+ $libraryconfigure->per_renew_limit_day days"));
                        }
                        $this->bookissue_m->update_bookissue($issueArray, $bookissueID);

                        if (($bookstatusID == 2) || ($bookstatusID == 3)) {
                            $bookitem = $this->bookitem_m->get_single_bookitem(['bookID' => $bookissue->bookID, 'bookno' => $bookissue->bookno, 'deleted_at' => 0]);
                            if (calculate($bookitem)) {
                                $this->bookitem_m->update_bookitem(['status' => 0], $bookitem->bookitemID);
                                $this->book_m->update_book(['status' => 0], $bookissue->bookID);
                            }
                        }

                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('bookissue/view/' . $bookissueID));
                    }
                } else {
                    $this->data["subview"] = "bookissue/renewandreturn";
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

    public function get_fineamount()
    {
        $retArr['status']  = false;
        $retArr['amount']  = 0;
        $retArr['message'] = '';

        if ($_POST && permissionChecker('bookissue_view')) {
            $bookissueID  = $this->input->post('bookissueID');
            $bookstatusID = $this->input->post('bookstatusID');

            if ((int) $bookissueID) {
                $bookissue = $this->bookissue_m->get_single_bookissue(['bookissueID' => $bookissueID]);
                if (calculate($bookissue)) {

                    $renewed           = $bookissue->renewed;
                    $max_renewed_limit = $bookissue->max_renewed_limit;
                    $book_fine_per_day = $bookissue->book_fine_per_day;
                    $expire_date       = $bookissue->expire_date;
                    $current_date      = date('Y-m-d');

                    if ($max_renewed_limit > calculate($bookissue->renewed)) {
                        $days = get_two_date_diff($bookissue->expire_date);
                        if ($days >= 1) {
                            $fineamount = $days * $book_fine_per_day;
                        } else {
                            $fineamount = 0;
                        }
                        if ($bookstatusID == 3) {
                            $book       = $this->book_m->get_single_book(['bookID' => $bookissue->bookID]);
                            $fineamount = $fineamount + (calculate($book) ? $book->price : 0);
                        }
                        $retArr['amount'] = $fineamount;
                        $retArr['status'] = true;
                    } else {
                        $retArr['message'] = 'You already renew this book maximum time.';
                    }
                } else {
                    $retArr['message'] = 'The book not available at this moment.';
                }
            } else {
                $retArr['message'] = 'The book not available at this moment.';
            }
        } else {
            $retArr['message'] = 'You have not permission to access this page.';
        }
        echo json_encode($retArr);
    }

    public function set_paymentamount()
    {
        $retArray['status']  = false;
        $retArray['message'] = '';
        if ($_POST && permissionChecker('bookissue_add')) {
            $rules = $this->rules_paymentamount();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $retArray           = $this->form_validation->error_array();
                $retArray['status'] = false;
            } else {
                $array                    = [];
                $array['bookissueID']     = $this->input->post('bookissueID');
                $array['paymentamount']   = $this->input->post('paymentamount');
                $array['discountamount']  = ($this->input->post('discountamount')) ? $this->input->post('discountamount') : 0;
                $array['notes']           = $this->input->post('notes');
                $array['create_date']     = date('Y-m-d H:i:s');
                $array['create_memberID'] = $this->session->userdata('loginmemberID');
                $array['create_roleID']   = $this->session->userdata('roleID');
                $array['modify_date']     = date('Y-m-d H:i:s');
                $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                $array['modify_roleID']   = $this->session->userdata('roleID');

                $this->paymentanddiscount_m->insert_paymentanddiscount($array);

                $bookissueID = $this->input->post('bookissueID');
                $bookissue   = $this->bookissue_m->get_single_bookissue(['bookissueID' => $bookissueID]);
                if (calculate($bookissue)) {
                    $issueArray                   = [];
                    $issueArray['paymentamount']  = $bookissue->paymentamount + $array['paymentamount'];
                    $issueArray['discountamount'] = $bookissue->discountamount + $array['discountamount'];

                    $totalfineamount       = $bookissue->fineamount;
                    $paymentdiscountamount = $issueArray['paymentamount'] + $issueArray['discountamount'];

                    if ($paymentdiscountamount == $totalfineamount) {
                        $issueArray['paidstatus'] = 2;
                    } elseif ($paymentdiscountamount <= 0) {
                        $issueArray['paidstatus'] = 0;
                    } else {
                        $issueArray['paidstatus'] = 1;
                    }
                    $this->bookissue_m->update_bookissue($issueArray, $bookissueID);
                }
                $retArray['status'] = true;
                $this->session->set_flashdata('success', 'Success');
            }
        }
        echo json_encode($retArray);
    }

    public function get_paymentamount()
    {
        $retArray['message']        = '';
        $retArray['status']         = false;
        $retArray['paymentamount']  = 0;
        $retArray['discountamount'] = '';
        if ($_POST && permissionChecker('bookissue_add')) {
            $bookissueID = $this->input->post('bookissueID');
            if ((int) $bookissueID) {
                $bookissue = $this->bookissue_m->get_single_bookissue(['bookissueID' => $bookissueID], array('fineamount', 'discountamount', 'paymentamount'));
                if (calculate($bookissue)) {
                    $retArray['status']        = true;
                    $retArray['paymentamount'] = ($bookissue->fineamount - ($bookissue->paymentamount + $bookissue->discountamount));
                } else {
                    $retArray['message'] = 'The data not found.';
                }
            } else {
                $retArray['message'] = 'The data not found.';
            }
        } else {
            $retArray['message'] = 'You have not permission to access this page.';
        }
        echo json_encode($retArray);
    }

    public function get_member()
    {
        echo "<option value='0'>" . $this->lang->line('bookissue_please_select') . "</option>";
        if ($_POST && permissionChecker('bookissue_add')) {
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

    public function get_book()
    {
        echo "<option value='0'>" . $this->lang->line('bookissue_please_select') . "</option>";
        if ($_POST && permissionChecker('bookissue_add')) {
            $bookcategoryID      = $this->input->post('bookcategoryID');
            $array['status']     = 0;
            $array['deleted_at'] = 0;
            if ((int) $bookcategoryID || ($bookcategoryID == 0)) {
                $array['bookcategoryID'] = $bookcategoryID;
            }
            $books = $this->book_m->get_order_by_book($array, array('bookID', 'name', 'codeno'));
            if (calculate($books)) {
                foreach ($books as $book) {
                    echo "<option value='" . $book->bookID . "'>" . $book->name . ' - ' . $book->codeno . "</option>";
                }
            }
        }
    }

    public function get_book_item()
    {
        echo "<option value='0'>" . $this->lang->line('bookissue_please_select') . "</option>";
        if ($_POST && permissionChecker('bookissue_add')) {
            $bookID = $this->input->post('bookID');
            if ((int) $bookID) {
                $bookitems = $this->bookitem_m->get_order_by_bookitem(array('bookID' => $bookID, 'status' => 0, 'deleted_at' => 0), array('bookno'));
                if (calculate($bookitems)) {
                    foreach ($bookitems as $bookitem) {
                        echo "<option value='" . $bookitem->bookno . "'>" . $bookitem->bookno . "</option>";
                    }
                }
            }
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'memberID',
                'label' => $this->lang->line('bookissue_member'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero|callback_check_eligible_for_book',
            ),
            array(
                'field' => 'bookID',
                'label' => $this->lang->line('bookissue_book'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
            array(
                'field' => 'bookno',
                'label' => $this->lang->line('bookissue_book_no'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero|callback_check_available_book',
            ),
            array(
                'field' => 'issue_date',
                'label' => $this->lang->line('bookissue_issue_date'),
                'rules' => 'trim|xss_clean|required|valid_date',
            ),
            array(
                'field' => 'notes',
                'label' => $this->lang->line('bookissue_notes'),
                'rules' => 'trim',
            ),
        );
        return $rules;
    }

    private function rules_renewandreturn()
    {
        $rules = array(
            array(
                'field' => 'bookstatusID',
                'label' => $this->lang->line('bookissue_book_status'),
                'rules' => 'trim|xss_clean|required|required_no_zero|numeric|callback_check_renew_eligible',
            ),
            array(
                'field' => 'fineamount',
                'label' => $this->lang->line('bookissue_fine_amount'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'notes',
                'label' => $this->lang->line('bookissue_notes'),
                'rules' => 'trim',
            ),
        );
        return $rules;
    }

    private function rules_paymentamount()
    {
        $rules = array(
            array(
                'field' => 'bookissueID',
                'label' => $this->lang->line('bookissue_book_issue'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'paymentamount',
                'label' => $this->lang->line('bookissue_payment_amount'),
                'rules' => 'trim|xss_clean|required|numeric|callback_check_payment_amount',
            ),
            array(
                'field' => 'discountamount',
                'label' => $this->lang->line('bookissue_discount_amount'),
                'rules' => 'trim|xss_clean|numeric',
            ),
            array(
                'field' => 'notes',
                'label' => $this->lang->line('bookissue_notes'),
                'rules' => 'trim',
            ),
        );
        return $rules;
    }

    public function check_eligible_for_book($memberID)
    {
        $roleID = $this->input->post('roleID');
        if ((int) $memberID && (int) $roleID) {
            $libraryconfigure = $this->libraryconfigure_m->get_single_libraryconfigure(array('roleID' => $roleID));
            if (!calculate($libraryconfigure)) {
                $libraryconfigure = $this->libraryconfigure_m->libraryconfigure;
            }
            $max_issue_book    = $libraryconfigure->max_issue_book;
            $bookissue         = $this->bookissue_m->get_order_by_bookissue(['memberID' => $memberID, 'status' => 0, 'deleted_at' => 0]);
            $current_issuebook = calculate($bookissue);
            $bookissueID       = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $bookissueID) {
                $current_issuebook--;
            }

            if ($max_issue_book > $current_issuebook) {
                return true;
            } else {
                $this->form_validation->set_message("check_eligible_for_book", "You are not eligible to issue new book. You have already issued maximum book.");
                return false;
            }
        }
        return true;
    }

    public function check_available_book($bookno)
    {
        $bookID = $this->input->post('bookID');
        if ((int) $bookno && (int) $bookID) {
            $f           = false;
            $bookissueID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $bookissueID) {
                $bookissue = $this->bookissue_m->get_single_bookissue(array('bookissueID' => $bookissueID, 'deleted_at' => 0));
                if (($bookID == $bookissue->bookID) && ($bookno == $bookissue->bookno)) {
                    $f = true;
                } else {
                    $bookitem = $this->bookitem_m->get_order_by_bookitem(['bookID' => $bookID, 'bookno' => $bookno, 'status' => 0, 'deleted_at' => 0]);
                    if (calculate($bookitem)) {
                        $f = true;
                    }
                }
            } else {
                $bookitem = $this->bookitem_m->get_order_by_bookitem(['bookID' => $bookID, 'bookno' => $bookno, 'status' => 0, 'deleted_at' => 0]);
                if (calculate($bookitem)) {
                    $f = true;
                }
            }
            if ($f) {
                return true;
            } else {
                $this->form_validation->set_message("check_available_book", "The Book currently not available.");
                return false;
            }
        } else {
            $this->form_validation->set_message("check_available_book", "The %s field is required.");
            return false;
        }
    }

    public function check_payment_amount()
    {
        $paymentamount  = $this->input->post('paymentamount');
        $discountamount = $this->input->post('discountamount') ? $this->input->post('discountamount') : 0;
        $bookissueID    = $this->input->post('bookissueID');

        if ($_POST && permissionChecker('bookissue_add') && (int) $bookissueID) {
            if (empty($paymentamount)) {
                $this->form_validation->set_message("check_payment_amount", "The %s field is required.");
                return false;
            } else {
                $bookissue = $this->bookissue_m->get_single_bookissue(['bookissueID' => $bookissueID, 'paidstatus !=' => 2]);
                if (calculate($bookissue)) {
                    $totalfineamount       = $bookissue->fineamount;
                    $paymentdiscountamount = $paymentamount + $discountamount;
                    if ($paymentamount <= 0) {
                        $this->form_validation->set_message("check_payment_amount", "Your payment amount are invalid.");
                        return false;
                    } elseif ($paymentdiscountamount > $totalfineamount) {
                        $this->form_validation->set_message("check_payment_amount", "Your payment or discount amount are invalid.");
                        return false;
                    }
                } else {
                    $this->form_validation->set_message("check_payment_amount", "Your issue data not fount.");
                    return false;
                }
            }
        }
        return true;
    }

    public function check_renew_eligible()
    {
        $bookissueID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookissueID) {
            $bookissue = $this->bookissue_m->get_single_bookissue(['bookissueID' => $bookissueID, 'status' => 0, 'deleted_at' => 0]);
            if (calculate($bookissue) && ($bookissue->status == 0)) {
                $bookstatusID = $this->input->post('bookstatusID');
                if (($bookstatusID == 1) && ($bookissue->renewed == $bookissue->max_renewed_limit)) {
                    $this->form_validation->set_message("check_renew_eligible", "You have already maximum time renewed.Please return your book.");
                    return false;
                }
            } else {
                $this->form_validation->set_message("check_renew_eligible", "You have already return or lost this book.");
                return false;
            }
        }
        return true;
    }

}
