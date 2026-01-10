<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissions extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('permissions_m');
        $this->load->model('permissionlog_m');

        $lang = $this->session->userdata('language');
        $this->lang->load('permissions', $lang);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'permissionsroleID',
                'label' => 'Permissions roleID',
                'rules' => 'trim|xss_clean|required|numeric',
            ),
        );
        return $rules;
    }

    public function index()
    {
        $roleID = escapeString($this->uri->segment('3'));
        if ((int) $roleID) {
            $this->data['urlroleID'] = $roleID;
        } else {
            $this->data['urlroleID'] = '0';
        }

        $this->data['headerassets'] = array(
            'css' => array(
                'assets/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css',
                'assets/custom/css/hidetable.css',
            ),
            'js'  => array(
                'assets/plugins/datatables.net/js/jquery.dataTables.min.js',
                'assets/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js',
                'assets/custom/js/permissions.js',
            ),
        );

        $permissionlogs = $this->permissionlog_m->get_order_by_permissionlog(array('active' => 'yes'));

        $permissionlogsArray    = [];
        $permissionsModuleArray = [];
        if (calculate($permissionlogs)) {
            foreach ($permissionlogs as $permissionlog) {
                if ((strpos($permissionlog->name, '_add') == false) && (strpos($permissionlog->name, '_edit') == false) && (strpos($permissionlog->name, '_view') == false) && (strpos($permissionlog->name, '_delete') == false)) {
                    $permissionsModuleArray[$permissionlog->permissionlogID] = $permissionlog;
                }
                $permissionlogsArray[$permissionlog->name] = $permissionlog->permissionlogID;
            }
        }

        $this->data['permissions'] = pluck_multi_array_key($this->permissions_m->get_permissions(), 'permissionlogID', 'roleID', 'permissionlogID');

        $this->data['permissionlogsArray']    = $permissionlogsArray;
        $this->data['permissionsModuleArray'] = $permissionsModuleArray;
        $this->data['roles']                  = $this->role_m->get_role();
        $this->data["subview"]                = "permissions/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function save()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', trim(validation_errors()));
                redirect(base_url("permissions/index"));
            } else {
                $permissionsroleID = $_POST['permissionsroleID'];
                unset($_POST['permissionsroleID']);

                $permissionArray = [];
                if (calculate($_POST)) {
                    $i = 0;
                    foreach ($_POST as $permissionname => $permissionlogID) {
                        $permissionArray[$i]['roleID']          = $permissionsroleID;
                        $permissionArray[$i]['permissionlogID'] = $permissionlogID;
                        $i++;
                    }
                }

                if (calculate($permissionArray)) {
                    $this->permissions_m->delete_permissions_by_roleID($permissionsroleID);
                    $this->permissions_m->insert_batch_permissions($permissionArray);
                }
                $this->session->set_flashdata('success', 'Success');
                redirect(base_url("permissions/index/$permissionsroleID"));
            }
        } else {
            redirect(base_url('permissions/index'));
        }
    }

}
