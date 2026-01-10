<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookbarcode extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('book_m');
        $this->load->model('bookitem_m');
        $this->load->model('bookcategory_m');
        $this->load->library('barcode');
        $this->load->library('pdf');

        $lang = $this->session->userdata('language');
        $this->lang->load('bookbarcode', $lang);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/bookbarcode.js',
            ),
        );

        $this->data['flag']           = 0;
        $this->data['bookcategoryID'] = 0;
        $this->data['bookID']         = 0;

        $this->data['books']         = [];
        $this->data['bookcategorys'] = pluck($this->bookcategory_m->get_bookcategory(), 'obj', 'bookcategoryID');
        unset($_SESSION['error']);
        if ($_POST) {
            $bookcategoryID = $this->input->post('bookcategoryID');
            $bookID         = $this->input->post('bookID');

            $array['status']         = 0;
            $array['deleted_at']     = 0;
            $array['bookcategoryID'] = $bookcategoryID;
            if ((int) $bookcategoryID) {
                $this->data['books'] = $this->book_m->get_order_by_book($array, array('bookID', 'name', 'codeno'));
            }

            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $message = implode('<br/>', $this->form_validation->error_array());
                $this->session->set_flashdata('error', $message);
                $this->data["subview"] = "bookbarcode/index";
            } else {

                $this->_queryArray(['bookcategoryID' => $bookcategoryID, 'bookID' => $bookID]);

                $this->data["subview"] = "bookbarcode/index";
            }
        } else {
            $this->data["subview"] = "bookbarcode/index";
        }
        $this->load->view('_main_layout', $this->data);
    }

    public function pdf()
    {
        $bookcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        $bookID         = htmlentities(escapeString($this->uri->segment(4)));

        if (((int) $bookcategoryID || $bookcategoryID == 0) && ((int) $bookID || $bookID == 0)) {

            $this->_queryArray(['bookcategoryID' => $bookcategoryID, 'bookID' => $bookID]);

            $this->pdf->create(['stylesheet' => 'bookbarcode.css', 'view' => 'bookbarcode/pdf.php', 'data' => $this->data]);
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function _queryArray($queryArr)
    {
        extract($queryArr);

        $queryArray = [];
        if ((int) $bookID) {
            $queryArray['bookID'] = $bookID;
        }
        $queryArray['status !=']  = 2;
        $queryArray['deleted_at'] = 0;

        $book      = $this->book_m->get_single_book(['bookID' => $bookID]);
        $bookitems = $this->bookitem_m->get_order_by_bookitem($queryArray);

        $this->generatebarcode($book, $bookitems);

        $this->data['flag']           = 1;
        $this->data['bookcategoryID'] = $bookcategoryID;
        $this->data['bookID']         = $bookID;
        $this->data['book']           = $book;
        $this->data['bookitems']      = $bookitems;
    }

    public function get_book()
    {
        echo "<option value='0'>" . $this->lang->line('bookbarcode_please_select') . "</option>";
        if ($_POST && permissionChecker('bookbarcode')) {
            $bookcategoryID          = $this->input->post('bookcategoryID');
            $array['status']         = 0;
            $array['deleted_at']     = 0;
            $array['bookcategoryID'] = $bookcategoryID;

            if ((int) $bookcategoryID) {
                $books = $this->book_m->get_order_by_book($array, array('bookID', 'name', 'codeno'));
                if (calculate($books)) {
                    foreach ($books as $book) {
                        echo "<option value='" . $book->bookID . "'>" . $book->name . ' - ' . $book->codeno . "</option>";
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
                'label' => $this->lang->line('bookbarcode_book_category'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
            array(
                'field' => 'bookID',
                'label' => $this->lang->line('bookbarcode_book'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
        );
        return $rules;
    }

    private function generatebarcode($book, $bookitems)
    {
        if(!calculate($book)) {
            $this->session->set_flashdata('error', 'Some Thing Wrong');
            redirect(base_url('bookbarcode/index'));
        }

        if (calculate($bookitems)) {
            foreach ($bookitems as $bookitem) {
                $bookitembarcode = $book->codeno.'-'.$bookitem->bookno;
                $this->barcode->generate($bookitembarcode, $bookitembarcode, 'uploads/bookbarcode/');
            }
        }
    }

}
