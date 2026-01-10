<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Storebook extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('storebook_m');
        $this->load->model('storebookcategory_m');
        $this->load->model('storebookimage_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('storebook', $lang);
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
        $this->data['storebooks'] = $this->storebook_m->get_order_by_storebook(['deleted_at' => 0]);
        $this->data["subview"]    = "storebook/index";
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
                'assets/custom/js/storebook.js',
            ),
        );
        $this->data['storebookcategorys'] = $this->storebookcategory_m->get_order_by_storebookcategory(array('status' => 1));
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "storebook/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array['name']                = $this->input->post('name');
                $array['author']              = $this->input->post('author');
                $array['storebookcategoryID'] = $this->input->post('storebookcategoryID');
                $array['quantity']            = $this->input->post('quantity');
                $array['price']               = $this->input->post('price');
                $array['codeno']              = $this->input->post('codeno');
                $array['coverphoto']          = $this->upload_data['coverphoto']['file_name'];
                $array['isbnno']              = $this->input->post('isbnno');
                $array['editionnumber']       = $this->input->post('editionnumber');
                $array['editiondate']         = (($this->input->post('editiondate')) ? date('Y-m-d', strtotime($this->input->post('editiondate'))) : null);
                $array['publisher']           = $this->input->post('publisher');
                $array['publisheddate']       = (($this->input->post('publisheddate')) ? date('Y-m-d', strtotime($this->input->post('publisheddate'))) : null);
                $array['notes']               = $this->input->post('notes');
                $array['description']         = $this->input->post('description');
                $array['create_date']         = date('Y-m-d H:i:s');
                $array['create_memberID']     = $this->session->userdata('loginmemberID');
                $array['modify_date']         = date('Y-m-d H:i:s');
                $array['modify_memberID']     = $this->session->userdata('loginmemberID');

                $this->storebook_m->insert_storebook($array);

                if ($_FILES['images']['name'] != '') {
                    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {

                        $_FILES['image']['name']     = $_FILES['images']['name'][$i];
                        $_FILES['image']['type']     = $_FILES['images']['type'][$i];
                        $_FILES['image']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
                        $_FILES['image']['error']    = $_FILES['images']['error'][$i];
                        $_FILES['image']['size']     = $_FILES['images']['size'][$i];

                        $file_name        = $_FILES["image"]['name'];
                        $random           = rand(1, 10000000000000000);
                        $file_name_rename = hash('sha512', $random . config_item("encryption_key"));
                        $explode          = explode('.', $file_name);
                        if (calculate($explode) >= 2) {
                            $new_file                = $file_name_rename . '.' . end($explode);
                            $config['upload_path']   = "./uploads/storebook";
                            $config['allowed_types'] = "gif|jpg|png";
                            $config['file_name']     = $new_file;
                            $config['max_size']      = "2048";
                            $config['max_width']     = "2000";
                            $config['max_height']    = "2000";

                            $this->load->library('upload', $config);
                            if ($this->upload->do_upload("image")) {
                                $image = $this->upload->data();

                            }
                        }
                    }
                }

                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('storebook/index'));
            }
        } else {
            $this->data["subview"] = "storebook/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $storebookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $storebookID) {
            $storebook = $this->storebook_m->get_single_storebook(array('storebookID' => $storebookID, 'deleted_at' => 0));
            if (calculate($storebook)) {
                $this->data['headerassets'] = array(
                    'css'      => array(
                        'assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
                    ),
                    'headerjs' => array(
                        'assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
                    ),
                    'js'       => array(
                        'assets/custom/js/storebook.js',
                        'assets/custom/js/fileupload.js',
                    ),
                );
                $this->data['book']               = $storebook;
                $this->data['storebookcategorys'] = $this->storebookcategory_m->get_order_by_storebookcategory(array('status' => 1));
                $this->data['storebookimages']    = $this->storebookimage_m->get_order_by_storebookimage(['storebookID' => $storebookID]);
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "storebook/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array['name']                = $this->input->post('name');
                        $array['author']              = $this->input->post('author');
                        $array['storebookcategoryID'] = $this->input->post('storebookcategoryID');
                        $array['quantity']            = $this->input->post('quantity');
                        $array['price']               = $this->input->post('price');
                        $array['codeno']              = $this->input->post('codeno');
                        $array['coverphoto']          = $this->upload_data['coverphoto']['file_name'];
                        $array['isbnno']              = $this->input->post('isbnno');
                        $array['editionnumber']       = $this->input->post('editionnumber');
                        $array['editiondate']         = (($this->input->post('editiondate')) ? date('Y-m-d', strtotime($this->input->post('editiondate'))) : null);
                        $array['publisher']           = $this->input->post('publisher');
                        $array['publisheddate']       = (($this->input->post('publisheddate')) ? date('Y-m-d', strtotime($this->input->post('publisheddate'))) : null);
                        $array['notes']               = $this->input->post('notes');
                        $array['description']         = $this->input->post('description');
                        $array['modify_date']         = date('Y-m-d H:i:s');
                        $array['modify_memberID']     = $this->session->userdata('loginmemberID');

                        $this->storebook_m->update_storebook($array, $storebookID);

                        if ($_FILES['images']['name'][0] != '') {
                            $this->storebookimage_m->delete_where(['storebookID' => $storebookID]);

                            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {

                                $_FILES['image']['name']     = $_FILES['images']['name'][$i];
                                $_FILES['image']['type']     = $_FILES['images']['type'][$i];
                                $_FILES['image']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
                                $_FILES['image']['error']    = $_FILES['images']['error'][$i];
                                $_FILES['image']['size']     = $_FILES['images']['size'][$i];

                                $file_name        = $_FILES["image"]['name'];
                                $random           = rand(1, 10000000000000000);
                                $file_name_rename = hash('sha512', $random . config_item("encryption_key"));
                                $explode          = explode('.', $file_name);
                                if (calculate($explode) >= 2) {
                                    $new_file                = $file_name_rename . '.' . end($explode);
                                    $config['upload_path']   = "./uploads/storebook";
                                    $config['allowed_types'] = "gif|jpg|png";
                                    $config['file_name']     = $new_file;
                                    $config['max_size']      = "2048";
                                    $config['max_width']     = "2000";
                                    $config['max_height']    = "2000";

                                    $this->load->library('upload', $config);
                                    if ($this->upload->do_upload("image")) {
                                        $image = $this->upload->data();

                                        $retArray['storebookID'] = $storebookID;
                                        $retArray['file_name']   = $image['file_name'];
                                        $retArray['client_name'] = $image['client_name'];
                                        $retArray['meta']        = json_encode($image);

                                        $this->storebookimage_m->insert_storebookimage($retArray);
                                    }
                                }
                            }
                        }
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('storebook/index'));
                    }
                } else {
                    $this->data["subview"] = "storebook/edit";
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
        $storebookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $storebookID) {
            $storebook = $this->storebook_m->get_single_storebook(array('storebookID' => $storebookID));
            if (calculate($storebook)) {
                $this->data['storebook'] = $storebook;

                $this->data['storebookcategory'] = [];
                if ((int) $storebook->storebookcategoryID) {
                    $this->data['storebookcategory'] = $this->storebookcategory_m->get_single_storebookcategory(['storebookcategoryID' => $storebook->storebookcategoryID]);
                }
                $this->data["subview"] = "storebook/view";
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
        $storebookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $storebookID) {
            $storebook = $this->storebook_m->get_single_storebook(array('storebookID' => $storebookID, 'deleted_at !=' => 1));
            if (calculate($storebook)) {
                $this->storebook_m->update_storebook(['deleted_at' => 1], $storebookID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('storebook/index'));
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
                'label' => $this->lang->line('storebook_name'),
                'rules' => 'trim|xss_clean|required|max_length[100]',
            ),
            array(
                'field' => 'author',
                'label' => $this->lang->line('storebook_author'),
                'rules' => 'trim|xss_clean|required|max_length[100]',
            ),
            array(
                'field' => 'quantity',
                'label' => $this->lang->line('storebook_quantity'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'price',
                'label' => $this->lang->line('storebook_price'),
                'rules' => 'trim|xss_clean|required|max_length[100]|numeric',
            ),
            array(
                'field' => 'codeno',
                'label' => $this->lang->line('storebook_code_no'),
                'rules' => 'trim|xss_clean|required|max_length[100]',
            ),
            array(
                'field' => 'coverphoto',
                'label' => $this->lang->line('storebook_cover_photo'),
                'rules' => 'trim|xss_clean|callback_coverphoto_upload',
            ),
            array(
                'field' => 'storebookcategoryID',
                'label' => $this->lang->line('storebook_storebook_category'),
                'rules' => 'trim|xss_clean|required|numeric|required_no_zero',
            ),
            array(
                'field' => 'isbnno',
                'label' => $this->lang->line('storebook_isbn_no'),
                'rules' => 'trim|xss_clean|max_length[100]',
            ),
            array(
                'field' => 'rackID',
                'label' => $this->lang->line('storebook_rack'),
                'rules' => 'trim|xss_clean|numeric',
            ),
            array(
                'field' => 'editionnumber',
                'label' => $this->lang->line('storebook_edition_number'),
                'rules' => 'trim|xss_clean|max_length[100]',
            ),
            array(
                'field' => 'editiondate',
                'label' => $this->lang->line('storebook_edition_date'),
                'rules' => 'trim|xss_clean|valid_date',
            ),
            array(
                'field' => 'publisher',
                'label' => $this->lang->line('storebook_publisher'),
                'rules' => 'trim|xss_clean|max_length[200]',
            ),
            array(
                'field' => 'publisheddate',
                'label' => $this->lang->line('storebook_published_date'),
                'rules' => 'trim|xss_clean|valid_date',
            ),
            array(
                'field' => 'notes',
                'label' => $this->lang->line('storebook_notes'),
                'rules' => 'trim|xss_clean|max_length[1000]',
            ),
        );
        return $rules;
    }

    public function coverphoto_upload()
    {
        $storebookID = htmlentities(escapeString($this->uri->segment(3)));
        $storebook   = array();
        if ((int) $storebookID) {
            $storebook = $this->storebook_m->get_single_storebook(array('storebookID' => $storebookID));
        }

        $new_file = "";
        if ($_FILES["coverphoto"]['name'] != "") {
            $file_name        = $_FILES["coverphoto"]['name'];
            $random           = rand(1, 10000000000000000);
            $file_name_rename = hash('sha512', $random . config_item("encryption_key"));
            $explode          = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/storebook";
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
            if (calculate($storebook)) {
                $this->upload_data['coverphoto'] = array('file_name' => $storebook->coverphoto);
                return true;
            } else {
                $this->form_validation->set_message("coverphoto_upload", "The %s field is required.");
                return false;
            }
        }
    }

}
