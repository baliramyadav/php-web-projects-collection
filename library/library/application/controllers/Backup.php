<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backup extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();


        $lang = $this->session->userdata('language');
        $this->lang->load('backup', $lang);
    }

    public function index()
    {
        $this->data["subview"] = "backup/index";
        $this->load->view('_main_layout', $this->data);
    }

    public function backup()
    {
        if (config_item('demo')) {
            $this->session->set_flashdata('error', 'This backup module disable for demo version');
            redirect(base_url('dashboard/index'));
        }
        if(permissionChecker('backup')) {
            $backup_name = 'backup-on-' . date("Y-m-d-H-i-s-A");

            $this->load->dbutil();
            $prefs = array(
                'format'   => 'zip',
                'filename' => $backup_name . '.sql',
            );
            $backup = $this->dbutil->backup($prefs);
            $this->load->helper('download');
            force_download($backup_name . '.zip', $backup);
        } else {
            redirect(base_url('dashboard/index'));
        }
    }

}
