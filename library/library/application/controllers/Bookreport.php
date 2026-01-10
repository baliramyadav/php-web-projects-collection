<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookreport extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('book_m');
        $this->load->model('bookitem_m');
        $this->load->model('bookcategory_m');
        $this->load->library('pdf');

        $lang = $this->session->userdata('language');
        $this->lang->load('bookreport', $lang);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/bookreport.js',
            ),
        );

        $this->data['flag']           = 0;
        $this->data['bookcategoryID'] = 0;
        $this->data['bookID']         = 0;
        $this->data['status']         = 0;

        $this->data['books']         = [];
        $this->data['bookcategorys'] = pluck($this->bookcategory_m->get_bookcategory(), 'obj', 'bookcategoryID');
        unset($_SESSION['error']);
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $message = implode('<br/>', $this->form_validation->error_array());
                $this->session->set_flashdata('error', $message);
                $this->data["subview"] = "report/book/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $bookcategoryID = $this->input->post('bookcategoryID');
                $bookID         = $this->input->post('bookID');
                $status         = $this->input->post('status');

                $this->_queryArray(['bookcategoryID' => $bookcategoryID, 'bookID' => $bookID, 'status' => $status]);

                $this->data["subview"] = "report/book/index";
                $this->load->view('_main_layout', $this->data);
            }
        } else {
            $this->data["subview"] = "report/book/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function pdf()
    {
        $bookcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        $bookID         = htmlentities(escapeString($this->uri->segment(4)));
        $status         = htmlentities(escapeString($this->uri->segment(5)));

        if (((int) $bookcategoryID || $bookcategoryID == 0) && ((int) $bookID || $bookID == 0) && ((int) $status || $status == 0)) {

            $this->_queryArray(['bookcategoryID' => $bookcategoryID, 'bookID' => $bookID, 'status' => $status]);
            $this->data['bookcategorys'] = pluck($this->bookcategory_m->get_bookcategory(), 'obj', 'bookcategoryID');

            $this->pdf->create(['stylesheet' => 'bookreport.css', 'view' => 'report/book/pdf.php', 'data' => $this->data]);
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function _queryArray($queryArr)
    {
        extract($queryArr);

        $queryArray = [];
        $itemArray  = [];
        if ((int) $bookcategoryID) {
            $queryArray['bookcategoryID'] = $bookcategoryID;
        }
        if ((int) $bookID) {
            $queryArray['bookID'] = $bookID;
            $itemArray['bookID']  = $bookID;
        }
        if ((int) $status) {
            $queryArray['status'] = $status - 1;
        }
        $itemArray['status']     = 0;
        $itemArray['deleted_at'] = 0;

        $books     = $this->book_m->get_order_by_book_for_report($queryArray);
        $bookitems = $this->bookitem_m->get_order_by_bookitem($itemArray);

        $bookQuantity = [];
        if (calculate($bookitems)) {
            foreach ($bookitems as $bookitem) {
                if (isset($bookQuantity[$bookitem->bookID])) {
                    $bookQuantity[$bookitem->bookID]++;
                } else {
                    $bookQuantity[$bookitem->bookID] = 1;
                }
            }
        }

        $this->data['flag']           = 1;
        $this->data['bookcategoryID'] = $bookcategoryID;
        $this->data['bookID']         = $bookID;
        $this->data['status']         = $status;
        $this->data['bookQuantity']   = $bookQuantity;
        $this->data['books']          = $books;
    }

    public function get_book()
    {
        echo "<option value='0'>" . $this->lang->line('bookreport_please_select') . "</option>";
        if ($_POST && permissionChecker('bookreport')) {
            $bookcategoryID          = $this->input->post('bookcategoryID');
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
                'label' => $this->lang->line('bookreport_book_category'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'bookID',
                'label' => $this->lang->line('bookreport_book'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line('bookreport_status'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
        );
        return $rules;
    }

}
