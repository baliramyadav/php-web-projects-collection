<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Update extends Admin_Controller
{

    protected $fileName   = '';
    protected $filePath   = '';
    protected $folderPath = '';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('login_m');
        $this->load->model('update_m');
        $this->load->helper('file');

        $lang = $this->session->userdata('language');
        $this->lang->load('update', $lang);
    }

    public function index()
    {
        if ($_FILES) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "update/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $this->fileName   = $this->upload_data['upload']['raw_name'];
                $this->folderPath = $this->upload_data['upload']['file_path'];
                $this->filePath   = $this->upload_data['upload']['full_path'];
                if (file_exists($this->filePath)) {
                    $zip = new ZipArchive;
                    if ($zip->open($this->filePath) === true) {
                        $zip->extractTo($this->folderPath);
                        $zip->close();

                        $destination = rtrim(FCPATH, '/');
                        $extractPath = $this->folderPath . $this->fileName . '/';

                        if ($this->fileUpdate($extractPath, $destination)) {
                            $updateFile = FCPATH . 'greensoftbd.json';
                            if (file_exists($updateFile)) {
                                $infos = file_get_contents($updateFile);
                                if ($infos != '') {
                                    $updateArr = json_decode($infos, true);

                                    if ((strtolower($updateArr['database']) == 'yes') && !empty($updateArr['itemname'])) {
                                        $file = FCPATH . 'mvc/' . $updateArr['itemname'] . '.php';
                                        if (file_exists($file) && is_file($file)) {
                                            @include_once $file;
                                        }
                                        if ($updateArr['version'] != 'none') {
                                            $array = [
                                                'version'     => $updateArr['version'],
                                                'date'        => date('Y-m-d H:i:s'),
                                                'memberID'    => $this->session->userdata('loginmemberID'),
                                                'status'      => 0,
                                                'description' => trim($updateArr['description'])
                                            ];
                                            $this->update_m->insert_update($array);
                                        }
                                    }

                                    $this->deleteFileUpdate();
                                    $this->session->sess_destroy();
                                    $this->session->set_userdata(array('modulepermission_set' => []));
                                    $this->session->set_flashdata('success', 'Success');
                                    redirect(base_url("login/index"));
                                } else {
                                    $this->session->set_flashdata('error', 'The update json info not found');
                                }
                            } else {
                                $this->session->set_flashdata('error', 'The update json file not found');
                            }
                        } else {
                            $this->session->set_flashdata('error', 'The update file extract failed');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'The update zip are Invalid');
                    }
                } else {
                    $this->session->set_flashdata('error', 'The update file not found');
                }
                redirect(base_url('update/index'));
            }
        } else {
            $this->data["subview"] = "update/index";
            $this->load->view('_main_layout', $this->data);
        }
    }

    private function deleteFileUpdate()
    {
        $updateFile = FCPATH . 'greensoftbd.json';
        if (file_exists($updateFile)) {
            $infos = file_get_contents($updateFile);
            if ($infos != '') {
                $updateArr = json_decode($infos, true);
                $file      = FCPATH . 'mvc/' . $updateArr['itemname'] . '.php';
                if (file_exists($file)) {
                    unlink($file);
                }
                if (file_exists($this->upload_data['upload']['full_path'])) {
                    unlink($this->upload_data['upload']['full_path']);
                }
                $folderPath = str_replace('.zip', '', $this->upload_data['upload']['full_path']);
                deleteAll($folderPath);
            }
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'upload',
                'label' => $this->lang->line('update_upload'),
                'rules' => 'trim|xss_clean|max_length[200]|callback_upload',
            ),
        );
        return $rules;
    }

    public function upload()
    {
        if ($_FILES["upload"]['name'] != "") {
            $file_name = $_FILES["upload"]['name'];
            $explode   = explode('.', $file_name);
            if (calculate($explode) >= 2) {
                $config['upload_path']   = "./uploads/update";
                $config['allowed_types'] = "zip";
                $config['file_name']     = $file_name;
                $config['overwrite']     = true;
                $config['max_size']      = '512000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload("upload")) {
                    $this->form_validation->set_message("upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['upload'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("upload", "Invalid file");
                return false;
            }
        } else {
            $this->form_validation->set_message("upload", "The %s field is required.");
            return false;
        }
    }

    private function fileUpdate($source, $dest, $options = array('folderPermission' => 0777, 'filePermission' => 0777))
    {
        $result = false;
        if (is_file($source)) {
            if ($dest[strlen($dest) - 1] == '/') {
                if (!file_exists($dest)) {
                    cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
                }
                $__dest = $dest . "/" . basename($source);
            } else {
                $__dest = $dest;
            }
            $result = copy($source, $__dest);
            @chmod($__dest, $options['filePermission']);
        } elseif (is_dir($source)) {
            if ($dest[strlen($dest) - 1] == '/') {
                if ($source[strlen($source) - 1] == '/') {
                } else {
                    $dest = $dest . basename($source);
                    @mkdir($dest);
                    @chmod($dest, $options['filePermission']);
                }
            } else {
                if ($source[strlen($source) - 1] == '/') {
                    @mkdir($dest, $options['folderPermission']);
                    @chmod($dest, $options['filePermission']);
                } else {
                    @mkdir($dest, $options['folderPermission']);
                    @chmod($dest, $options['filePermission']);
                }
            }
            $dirHandle = opendir($source);
            while ($file = readdir($dirHandle)) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($source . "/" . $file)) {
                        $__dest = $dest . "/" . $file;
                    } else {
                        $__dest = $dest . "/" . $file;
                    }
                    $result = $this->fileUpdate($source . "/" . $file, $__dest, $options);
                }
            }
            closedir($dirHandle);
        } else {
            $result = false;
        }
        return $result;
    }

}
