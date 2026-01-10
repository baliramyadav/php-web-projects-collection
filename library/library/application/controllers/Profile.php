<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_m');
        $this->load->model('role_m');
        $this->load->model('bookcategory_m');
        $this->load->model('book_m');
        $this->load->model('bookissue_m');
        $lang = $this->session->userdata('language');
        $this->lang->load('profile', $lang);
    }

    public function index()
    {
        $memberID = $this->session->userdata('loginmemberID');
        if ((int) $memberID) {
            $this->data['member'] = $this->member_m->get_single_member(array('memberID' => $memberID));
            if (calculate($this->data['member'])) {
                $this->data['bookcategory'] = pluck($this->bookcategory_m->get_bookcategory(), 'name', 'bookcategoryID');
                $this->data['book']         = pluck($this->book_m->get_book(), 'name', 'bookID');
                $this->data['bookissues']   = $this->bookissue_m->get_order_by_bookissue(['deleted_at' => 0, 'memberID' => $memberID]);
                $this->data['role']         = $this->role_m->get_single_role(array('roleID' => $this->data['member']->roleID));
                $this->data["subview"]      = "profile/index";
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

}
