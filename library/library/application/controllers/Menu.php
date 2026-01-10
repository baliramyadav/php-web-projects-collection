<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_m');
        $lang = $this->session->userdata('language');
        $this->lang->load('menu', $lang);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'menuname',
                'label' => $this->lang->line('menu_menuname'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[128]',
            ),
            array(
                'field' => 'menulink',
                'label' => $this->lang->line('menu_menulink'),
                'rules' => 'trim|xss_clean|required|max_length[128]',
            ),
            array(
                'field' => 'menuicon',
                'label' => $this->lang->line('menu_menuicon'),
                'rules' => 'trim|xss_clean|required|min_length[4]|max_length[128]',
            ),
            array(
                'field' => 'priority',
                'label' => $this->lang->line('menu_priority'),
                'rules' => 'trim|xss_clean|required|is_natural',
            ),
            array(
                'field' => 'parentmenuID',
                'label' => $this->lang->line('menu_parentmenuID'),
                'rules' => 'trim|xss_clean|required|is_natural',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line('menu_status'),
                'rules' => 'trim|xss_clean|required|required_no_zero',
            ),
        );
        return $rules;
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
        $this->data['menus']     = $this->menu_m->get_menu();
        $this->data['menusName'] = pluck($this->data['menus'], 'menuname', 'menuID');
        $this->data["subview"]   = "menu/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function add()
    {
        $this->data['parentmenus'] = $this->menu_m->get_order_by_menu(array('parentmenuID' => 0), array('menuID', 'menuname'));
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "menu/add";
                $this->load->view('_main_layout', $this->data);
            } else {
                $array                 = [];
                $array['menuname']     = $this->input->post('menuname');
                $array['menulink']     = $this->input->post('menulink');
                $array['menuicon']     = $this->input->post('menuicon');
                $array['priority']     = $this->input->post('priority');
                $array['parentmenuID'] = $this->input->post('parentmenuID');
                $array['status']       = $this->input->post('status');
                $this->menu_m->insert_menu($array);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('menu/index'));
            }
        } else {
            $this->data["subview"] = "menu/add";
            $this->load->view('_main_layout', $this->data);
        }
    }

    public function edit()
    {
        $menuID = escapeString($this->uri->segment('3'));
        if ((int) $menuID) {
            $this->data['menu'] = $this->menu_m->get_single_menu($menuID);
            if (calculate($this->data['menu'])) {
                $this->data['parentmenus'] = $this->menu_m->get_order_by_menu(array('parentmenuID' => 0), array('menuID', 'menuname'));
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "menu/edit";
                        $this->load->view('_main_layout', $this->data);
                    } else {
                        $array                 = [];
                        $array['menuname']     = $this->input->post('menuname');
                        $array['menulink']     = $this->input->post('menulink');
                        $array['menuicon']     = $this->input->post('menuicon');
                        $array['priority']     = $this->input->post('priority');
                        $array['parentmenuID'] = $this->input->post('parentmenuID');
                        $array['status']       = $this->input->post('status');
                        $this->menu_m->update_menu($array, $menuID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url('menu/index'));
                    }
                } else {
                    $this->data["subview"] = "menu/edit";
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

    public function delete()
    {
        $menuID = escapeString($this->uri->segment('3'));
        if ((int) $menuID) {
            $menu = $this->menu_m->get_single_menu(array('menuID' => $menuID));
            if (calculate($menu)) {
                $this->menu_m->delete_menu($menuID);
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url('menu/index'));
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
