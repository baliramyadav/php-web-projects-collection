<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_m');
        $this->load->model('permissions_m');
        $this->load->model('permissionlog_m');
        $this->load->model('generalsetting_m');
        $this->load->helper('text');

        if ($this->config->item('installed') == 'NO') {
            redirect(base_url('install/index'));
        }
        $this->load->database();

        $this->data["generalsetting"] = (object) pluck($this->generalsetting_m->get_generalsetting(), 'optionvalue', 'optionkey');
        $this->data['bloodgroups']    = $this->_bloodgroup();

        $lang = $this->session->userdata('language');
        $this->lang->load('menubar', $lang);
        $this->lang->load('default', $lang);

        $this->data['activemenu'] = $this->uri->segment(1);
        $exception_uris           = array(
            'login/index',
            'login/logout',
            'login/resetpassword',
            'login/registermember',
            'login/resetpasswordconfirm',
        );

        if (in_array(uri_string(), $exception_uris) == false) {
            $logged = $this->session->userdata('loggedin');
            if ($logged == false) {
                redirect(base_url('login/index'));
            }
        }

        $this->_set_permission();
        $this->_url_protect();
        $this->data['sidebarmenus'] = $this->_sidebar_menu();
        $this->data['get_title']    = $this->_get_title();

    }

    private function _set_permission()
    {
        $modulepermission_set = $this->session->userdata('modulepermission_set');
        $loggedin             = $this->session->userdata('loggedin');
        if (!calculate($modulepermission_set) && $loggedin) {
            $roleID        = $this->session->userdata('roleID');
            $loginmemberID = $this->session->userdata('loginmemberID');

            $permissionlogs   = $this->permissionlog_m->get_permissionlog();
            $permissionsArray = [];
            if ($roleID == 1 && $loginmemberID == 1) {
                if (calculate($permissionlogs)) {
                    foreach ($permissionlogs as $permissionlog) {
                        $permissionsArray['modulepermission_set'][$permissionlog->name] = $permissionlog->active;
                    }
                }
            } else {
                $permissions = $this->permissions_m->get_permissions_with_permissionlog_by_roleID($roleID);
                if (calculate($permissions)) {
                    foreach ($permissions as $permission) {
                        $permissionsArray['modulepermission_set'][$permission->name] = $permission->active;
                    }
                }

                if (calculate($permissionlogs)) {
                    foreach ($permissionlogs as $permissionlog) {
                        if (!isset($permissionsArray['modulepermission_set'][$permissionlog->name])) {
                            $permissionsArray['modulepermission_set'][$permissionlog->name] = 'no';
                        }
                    }
                }
            }
            if (calculate($permissionsArray)) {
                $this->session->set_userdata($permissionsArray);
            }
        }
    }

    private function _url_protect()
    {
        $module = $this->uri->segment(1);
        $action = $this->uri->segment(2);

        $permission = '';
        if ($action == 'index' || $action == false) {
            $permission = $module;
        } else {
            $permission = $module . '_' . $action;
        }

        $modulepermission_set = $this->session->userdata('modulepermission_set');
        if ((isset($modulepermission_set[$permission]))) {
            if ($modulepermission_set[$permission] != "yes") {
                redirect(base_url('exceptionpage/error'));
            }
        }
    }

    private function _sidebar_menu()
    {
        $menus     = $this->menu_m->get_order_by_menu(array('status' => 1));
        $menuArray = [];
        if (calculate($menus)) {
            foreach ($menus as $menu) {
                if (visibleButtonMenu($menu->menulink) || ($menu->menulink == '#')) {
                    if ($menu->parentmenuID == 0) {
                        if (!isset($menuArray[$menu->menuID])) {
                            $menuArray[$menu->menuID] = (array) $menu;
                        }
                    } else {
                        if (!isset($menuArray[$menu->parentmenuID]['child'])) {
                            $menuArray[$menu->parentmenuID]['child'] = [];
                        }
                        $menuArray[$menu->parentmenuID]['child'][$menu->menuID] = (array) $menu;
                    }
                }
            }
        }
        return $menuArray;
    }

    private function _get_title($title = null)
    {
        $get_title = '';
        if ($title) {
            $get_title = ucfirst($title) . " | " . $this->data["generalsetting"]->sitename;
        } else {
            $title     = empty($this->data['activemenu']) ? 'Dashboard' : $this->data['activemenu'];
            $get_title = ucfirst($title) . " | " . $this->data["generalsetting"]->sitename;
        }
        return $get_title;
    }

    public function password_hash($password)
    {
        return hash('sha512', $password . $this->config->item('encryption_key'));
    }

    private function _bloodgroup()
    {
        return [
            'A+'  => 'A+',
            'A-'  => 'A-',
            'B+'  => 'B+',
            'B-'  => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
            'O+'  => 'O+',
            'O-'  => 'O-',
        ];
    }

    public function checkAdminLibrarianPermission()
    {
        $roleID = $this->session->userdata('roleID');
        if ($roleID == 1 || $roleID == 2) {
            return false;
        }
        return true;
    }

}
