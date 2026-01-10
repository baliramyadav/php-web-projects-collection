<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

class Install extends My_Controller
{

    public $_internet_connection = false;

    public function __construct()
    {
        parent::__construct();

        $this->data['get_url'] = htmlentities($this->uri->segment('2'));

        if ($this->config->item('installed') != 'NO' && $this->data['get_url'] != 'complate') {
            redirect(base_url('login/index'));
        }

        if ($this->check_internet_connection()) {
            $this->_internet_connection = true;
        }

        $this->load->library('session');
        $this->load->model('install_m');
        $this->load->model('member_m');
        $this->load->model('generalsetting_m');
        $this->load->helper('url');
    }

    public function index()
    {
        // Check PHP version
        if (version_compare(phpversion(), '5.6', '<')) {
            $this->data['errors'][] = 'You are running PHP old version!';
        } elseif (version_compare(phpversion(), '7.4', '>')) {
            $this->data['errors'][] = 'Our script not support geater than 7.3 version.';
        } else {
            $phpversion              = phpversion();
            $this->data['success'][] = ' You are running PHP version ' . $phpversion;
        }
        // Check Mysql PHP exention
        if (!extension_loaded('mysqli')) {
            $this->data['errors'][] = 'Mysqli PHP exention unloaded!';
        } else {
            $this->data['success'][] = 'Mysqli PHP exention loaded!';
        }
        // Check MBString PHP exention
        if (!extension_loaded('mbstring')) {
            $this->data['errors'][] = 'MBString PHP exention unloaded!';
        } else {
            $this->data['success'][] = 'MBString PHP exention loaded!';
        }
        // Check GD PHP exention
        if (!extension_loaded('gd')) {
            $this->data['errors'][] = 'GD PHP exention unloaded!';
        } else {
            $this->data['success'][] = 'GD PHP exention loaded!';
        }
        // Check Config Path
        if (@include ($this->config->config_path)) {
            $this->data['success'][] = 'Config file is loaded';
            @chmod($this->config->config_path, FILE_WRITE_MODE);
            if (is_really_writable($this->config->config_path) == true) {
                $this->data['success'][] = 'Config file is writable';
            } else {
                $this->data['errors'][] = 'Config file is unwritable';
            }
        } else {
            $this->data['errors'][] = 'Config file is unloaded';
        }

        // Check Database Path
        if (@include ($this->config->database_path)) {
            $this->data['success'][] = 'Database file is loaded';
            @chmod($this->config->database_path, FILE_WRITE_MODE);
            if (is_really_writable($this->config->database_path) === false) {
                $this->data['errors'][] = 'database file is unwritable';
            } else {
                $this->data['success'][] = 'Database file is writable';
            }
        } else {
            $this->data['errors'][] = 'Database file is unloaded';
        }

        if ($this->_internet_connection) {
            $this->data['success'][] = 'Internet connection OK';
        } else {
            $this->data['errors'][] = 'Internet connection problem!';
        }
        $this->load->view('install/index', $this->data);
    }

    public function purchase()
    {
        if ($_POST) {
            $rules = $this->purchase_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                //$this->load->view('install/purchase', $this->data);
                 redirect(base_url('install/database'));
            } else {
                redirect(base_url('install/database'));
            }
        } else {
            $this->load->view('install/purchase', $this->data);
        }
    }

    public function database()
    {
        if ($_POST) {
            $rules = $this->database_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->load->view('install/database', $this->data);
            } else {
                redirect(base_url('install/setting'));
            }
        } else {
            $this->load->view('install/database', $this->data);
        }
    }

    public function setting()
    {
        if ($_POST) {
            $this->load->database();
            $rules = $this->setting_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->load->view('install/setting', $this->data);
            } else {
                $array                    = [];
                $array['name']            = $this->input->post('adminname');
                $array['gender']          = 'Male';
                $array['religion']        = 'Unknown';
                $array['email']           = $this->input->post('adminemail');
                $array['phone']           = '';
                $array['address']         = '';
                $array['dateofbirth']     = date('Y-m-d');
                $array['joinningdate']    = date('Y-m-d');
                $array['photo']           = '';
                $array['roleID']          = 1;
                $array['status']          = 1;
                $array['username']        = $this->input->post('adminusername');
                $array['password']        = $this->password_hash($this->input->post('password'));
                $array['create_date']     = date('Y-m-d H:i:s');
                $array['create_memberID'] = 1;
                $array['create_roleID']   = 1;
                $array['modify_date']     = date('Y-m-d H:i:s');
                $array['modify_memberID'] = 1;
                $this->member_m->insert_member($array);

                $garray['sitename'] = $this->input->post('sitename');
                $this->generalsetting_m->insertorupdate_generalsetting($garray);

                $this->session->set_userdata('adminusername', $array['username']);
                $this->session->set_userdata('adminpassword', $this->input->post('password'));
                redirect(base_url('install/complate'));
            }
        } else {
            $this->load->view('install/setting', $this->data);
        }
    }

    public function complate()
    {
        $this->config->config_update(array('installed' => 'YES', 'demo' => false));
        $this->load->view('install/complate', $this->data);
    }

    public function test_mysql_connection()
    {
        if (strpos($this->input->post('database'), '.') === false) {
            ini_set('display_errors', 'Off');
            $config_db['hostname'] = trim($this->input->post('hostname'));
            $config_db['username'] = trim($this->input->post('username'));
            $config_db['password'] = trim($this->input->post('password'));
            $config_db['database'] = trim($this->input->post('database'));
            $config_db['dbdriver'] = 'mysqli';
            $this->config->db_config_update($config_db);
            $db_obj    = $this->load->database($config_db, true);
            $connected = $db_obj->initialize();
            if ($connected) {
                unset($this->db);
                $config_db['db_debug'] = false;
                $this->load->database($config_db);
                $this->load->dbutil();
                if ($this->dbutil->database_exists($this->db->database)) {
                    $this->install_m->use_sql_string();
                    $id             = uniqid();
                    $encryption_key = md5($id);
                    $this->config->config_update(array('encryption_key' => $encryption_key));
                    return true;
                } else {
                    $this->form_validation->set_message("test_mysql_connection", "Database Not Found.");
                    return false;
                }
            } else {
                $this->form_validation->set_message("test_mysql_connection", "Database Connection Failed.");
                return false;
            }
        } else {
            $this->form_validation->set_message("test_mysql_connection", "Database can not accept dot in DB name.");
            return false;
        }
    }

    private function check_internet_connection($sCheckHost = 'www.google.com')
    {
        return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
    }

    protected function database_rules()
    {
        $rules = array(
            array(
                'field' => 'hostname',
                'label' => 'Host Name',
                'rules' => 'trim|xss_clean|required|max_length[50]',
            ),
            array(
                'field' => 'username',
                'label' => 'User Name',
                'rules' => 'trim|xss_clean|required|max_length[50]|callback_test_mysql_connection',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|xss_clean|max_length[50]',
            ),
            array(
                'field' => 'database',
                'label' => 'Database',
                'rules' => 'trim|xss_clean|required|max_length[50]',
            ),
        );
        return $rules;
    }

    protected function purchase_rules()
    {
        $rules = array(
            array(
                'field' => 'username',
                'label' => 'Envato User Name',
                'rules' => 'trim|xss_clean|required|max_length[50]',
            ),
            array(
                'field' => 'purchasecode',
                'label' => 'Purchase Code',
                'rules' => 'trim|xss_clean|required|max_length[50]|callback_checkpurchasecoode',
            ),
        );
        return $rules;
    }

    protected function setting_rules()
    {
        $rules = array(
            array(
                'field' => 'sitename',
                'label' => 'Site Name',
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'adminname',
                'label' => 'Admin Name',
                'rules' => 'trim|xss_clean|required|max_length[60]',
            ),
            array(
                'field' => 'adminusername',
                'label' => 'Admin Username',
                'rules' => 'trim|xss_clean|max_length[60]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|xss_clean|required|max_length[128]',
            ),
            array(
                'field' => 'adminemail',
                'label' => 'Admin Email',
                'rules' => 'trim|xss_clean|required|valid_email|max_length[60]',
            ),
        );
        return $rules;
    }

    public function password_hash($password)
    {
        return hash('sha512', $password . $this->config->item('encryption_key'));
    }

    public function checkpurchasecoode()
    {
        if ($_POST['purchasecode']) {
            $username     = $this->input->post('username');
            $purchasecode = $this->input->post('purchasecode');
            $ipaddress    = $this->input->ip_address();
            $hostname     = base_url('/');

            $array['username']     = $username;
            $array['purchasecode'] = $purchasecode;
            $array['ipaddress']    = $ipaddress;
            $array['hostname']     = $hostname;
            $array['appname']      = config_item('appname');
            $array['appversion']   = config_item('appversion');
            $array['status']       = 1;


            $ch  = curl_init();
            $url = config_item('authorurl');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Submit the POST request
            $result = curl_exec($ch);

            // Close cURL session ch
            curl_close($ch);

            $result = json_decode($result, true);

            if ($result['status']) {
                return true;
            } else {
                $this->form_validation->set_message("checkpurchasecoode", "You provide wrong credential.");
                return false;
            }
        }
        return true;
    }

}
