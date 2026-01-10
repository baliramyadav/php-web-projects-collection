<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Book extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('book_m');
        $this->load->model('bookitem_m');
        $this->load->model('rack_m');
        $this->load->model('bookcategory_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('book', $lang);
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
        $this->data['books']   = $this->book_m->get_order_by_book(['deleted_at'=> 0]);
        $this->data["subview"] = "book/index";
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
                'assets/custom/js/fileupload.js',
            ),
        );
        $this->data['racks']         = $this->rack_m->get_rack();
        $this->data['bookcategorys'] = $this->bookcategory_m->get_order_by_bookcategory(array('status' => 1));
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "book/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array                    = [];
                $array['name']            = $this->input->post('name');
                $array['author']          = $this->input->post('author');
                $array['bookcategoryID']  = $this->input->post('bookcategoryID');
                $array['quantity']        = $this->input->post('quantity');
                $array['price']           = $this->input->post('price');
                $array['codeno']          = $this->input->post('codeno');
                $array['coverphoto']      = $this->upload_data['coverphoto']['file_name'];
                $array['isbnno']          = $this->input->post('isbnno');
                $array['rackID']          = ($this->input->post('rackID')) ? $this->input->post('rackID') : null;
                $array['editionnumber']   = $this->input->post('editionnumber');
                $array['editiondate']     = (($this->input->post('editiondate')) ? date('Y-m-d', strtotime($this->input->post('editiondate'))) : null);
                $array['publisher']       = $this->input->post('publisher');
                $array['publisheddate']   = (($this->input->post('publisheddate')) ? date('Y-m-d', strtotime($this->input->post('publisheddate'))) : null);
                $array['notes']           = $this->input->post('notes');
                $array['create_date']     = date('Y-m-d H:i:s');
                $array['create_memberID'] = $this->session->userdata('loginmemberID');
                $array['create_roleID']   = $this->session->userdata('roleID');
                $array['modify_date']     = date('Y-m-d H:i:s');
                $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                $array['modify_roleID']   = $this->session->userdata('roleID');
                $this->book_m->insert_book($array);
                $bookID = $this->db->insert_id();

                $bookitemArray = [];
                for ($i = 1; $i <= $array['quantity']; $i++) {
                    $bookitemArray[$i]['bookID']     = $bookID;
                    $bookitemArray[$i]['bookno']     = $i;
                    $bookitemArray[$i]['status']     = 0;
                    $bookitemArray[$i]['deleted_at'] = 0;
                }
                $this->bookitem_m->insert_bookitem_batch($bookitemArray);

                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('book/index'));
            }
        } else {
            $this->data["subview"] = "book/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $bookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookID) {
            $book = $this->book_m->get_single_book(array('bookID' => $bookID, 'deleted_at' => 0));
            if (calculate($book)) {
                $this->data['headerassets'] = array(
                    'css'      => array(
                        'assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
                    ),
                    'headerjs' => array(
                        'assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
                    ),
                    'js'       => array(
                        'assets/custom/js/fileupload.js',
                    ),
                );
                $this->data['book']          = $book;
                $this->data['racks']         = $this->rack_m->get_rack();
                $this->data['bookcategorys'] = $this->bookcategory_m->get_order_by_bookcategory(array('status' => 1));
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "book/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array                    = [];
                        $array['name']            = $this->input->post('name');
                        $array['author']          = $this->input->post('author');
                        $array['bookcategoryID']  = $this->input->post('bookcategoryID');
                        $array['quantity']        = $this->input->post('quantity');
                        $array['price']           = $this->input->post('price');
                        $array['codeno']          = $this->input->post('codeno');
                        $array['coverphoto']      = $this->upload_data['coverphoto']['file_name'];
                        $array['isbnno']          = $this->input->post('isbnno');
                        $array['rackID']          = ($this->input->post('rackID')) ? $this->input->post('rackID') : null;
                        $array['editionnumber']   = $this->input->post('editionnumber');
                        $array['editiondate']     = (($this->input->post('editiondate')) ? date('Y-m-d', strtotime($this->input->post('editiondate'))) : null);
                        $array['publisher']       = $this->input->post('publisher');
                        $array['publisheddate']   = (($this->input->post('publisheddate')) ? date('Y-m-d', strtotime($this->input->post('publisheddate'))) : null);
                        $array['notes']           = $this->input->post('notes');
                        $array['modify_date']     = date('Y-m-d H:i:s');
                        $array['modify_memberID'] = $this->session->userdata('loginmemberID');
                        $array['modify_roleID']   = $this->session->userdata('roleID');

                        if ($array['quantity'] >= $book->quantity) {
                            $bookitems           = pluck($this->bookitem_m->get_order_by_bookitem(['bookID' => $bookID]), 'bookitemID', 'bookno');
                            $insertbookitemArray = [];
                            $updatebookitemArray = [];
                            for ($i = ($book->quantity + 1); $i <= $array['quantity']; $i++) {
                                if (isset($bookitems[$i])) {
                                    $updatebookitemArray[$i]['bookID']     = $bookID;
                                    $updatebookitemArray[$i]['bookitemID'] = $bookitems[$i];
                                    $updatebookitemArray[$i]['bookno']     = $i;
                                    $updatebookitemArray[$i]['status']     = 0;
                                    $updatebookitemArray[$i]['deleted_at'] = 0;
                                } else {
                                    $insertbookitemArray[$i]['bookID']     = $bookID;
                                    $insertbookitemArray[$i]['bookno']     = $i;
                                    $insertbookitemArray[$i]['status']     = 0;
                                    $insertbookitemArray[$i]['deleted_at'] = 0;
                                }
                            }

                            if (calculate($insertbookitemArray)) {
                                $this->bookitem_m->insert_bookitem_batch($insertbookitemArray);
                            }
                            if (calculate($updatebookitemArray)) {
                                $this->bookitem_m->update_bookitem_batch($updatebookitemArray, 'bookitemID');
                            }
                        } else {
                            $bookitems = pluck($this->bookitem_m->get_order_by_bookitem(array('bookID' => $bookID)), 'bookitemID', 'bookno');

                            $bookno              = $array['quantity'];
                            $deletebookitemArray = [];
                            for ($i = ($bookno + 1); $i <= $book->quantity; $i++) {
                                $deletebookitemArray[$i]['bookitemID'] = isset($bookitems[$i]) ? $bookitems[$i] : '';
                                $deletebookitemArray[$i]['bookID']     = $bookID;
                                $deletebookitemArray[$i]['bookno']     = $i;
                                $deletebookitemArray[$i]['deleted_at'] = 1;
                            }

                            $this->bookitem_m->update_bookitem_batch($deletebookitemArray, 'bookitemID');
                        }
                        $this->book_m->update_book($array, $bookID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('book/index'));
                    }
                } else {
                    $this->data["subview"] = "book/edit";
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
        $bookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookID) {
            $book = $this->book_m->get_single_book(array('bookID' => $bookID));
            if (calculate($book)) {
                $this->data['book'] = $book;

                $this->data['bookcategory'] = [];
                if ((int) $book->bookcategoryID) {
                    $this->data['bookcategory'] = $this->bookcategory_m->get_single_bookcategory(['bookcategoryID' => $book->bookcategoryID]);
                }
                $this->data['rack'] = [];
                if ((int) $book->rackID) {
                    $this->data['rack'] = $this->rack_m->get_single_rack(['rackID' => $book->rackID]);
                }

                $this->data['racks']         = $this->rack_m->get_rack();
                $this->data['bookcategorys'] = $this->bookcategory_m->get_order_by_bookcategory(array('status' => 1));

                $this->data["subview"] = "book/view";
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
        $bookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $bookID) {
            $book = $this->book_m->get_single_book(array('bookID' => $bookID, 'deleted_at !=' => 1));
            if (calculate($book)) {
                $this->book_m->update_book(['deleted_at' => 1], $bookID);
                $this->bookitem_m->update_bookitem_by_bookID(['deleted_at' => 1], $bookID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('book/index'));
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
                'label' => $this->lang->line('book_name'),
                'rules' => 'trim|xss_clean|required|max_length[100]',
            ),
            array(
                'field' => 'author',
                'label' => $this->lang->line('book_author'),
                'rules' => 'trim|xss_clean|required|max_length[100]',
            ),
            array(
                'field' => 'quantity',
                'label' => $this->lang->line('book_quantity'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'price',
                'label' => $this->lang->line('book_price'),
                'rules' => 'trim|xss_clean|required|max_length[100]|numeric',
            ),
            array(
                'field' => 'codeno',
                'label' => $this->lang->line('book_code_no'),
                'rules' => 'trim|xss_clean|required|max_length[100]',
            ),
            array(
                'field' => 'coverphoto',
                'label' => $this->lang->line('book_cover_photo'),
                'rules' => 'trim|xss_clean|callback_coverphoto_upload',
            ),
            array(
                'field' => 'bookcategoryID',
                'label' => $this->lang->line('book_book_category'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
            array(
                'field' => 'isbnno',
                'label' => $this->lang->line('book_isbn_no'),
                'rules' => 'trim|xss_clean|max_length[100]',
            ),
            array(
                'field' => 'rackID',
                'label' => $this->lang->line('book_rack'),
                'rules' => 'trim|xss_clean|numeric',
            ),
            array(
                'field' => 'editionnumber',
                'label' => $this->lang->line('book_edition_number'),
                'rules' => 'trim|xss_clean|max_length[100]',
            ),
            array(
                'field' => 'editiondate',
                'label' => $this->lang->line('book_edition_date'),
                'rules' => 'trim|xss_clean|valid_date',
            ),
            array(
                'field' => 'publisher',
                'label' => $this->lang->line('book_publisher'),
                'rules' => 'trim|xss_clean|max_length[200]',
            ),
            array(
                'field' => 'publisheddate',
                'label' => $this->lang->line('book_published_date'),
                'rules' => 'trim|xss_clean|valid_date',
            ),
            array(
                'field' => 'notes',
                'label' => $this->lang->line('book_notes'),
                'rules' => 'trim|xss_clean|max_length[1000]',
            ),
        );
        return $rules;
    }

    public function coverphoto_upload()
    {
        $bookID = htmlentities(escapeString($this->uri->segment(3)));
        $book   = array();
        if ((int) $bookID) {
            $book = $this->book_m->get_single_book(array('bookID' => $bookID));
        }

        $new_file = "";
        if ($_FILES["coverphoto"]['name'] != "") {
            $file_name        = $_FILES["coverphoto"]['name'];
            $random           = rand(1, 10000000000000000);
            $file_name_rename = hash('sha512', $random . config_item("encryption_key"));
            $explode          = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/book";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $config['max_size']      = "2048";
                $config['max_width']     = "2000";
                $config['max_height']    = "2000";
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
            if (calculate($book)) {
                $this->upload_data['coverphoto'] = array('file_name' => $book->coverphoto);
                return true;
            } else {
                $this->form_validation->set_message("coverphoto_upload", "The %s field is required.");
                return false;
            }
        }
    }

}
