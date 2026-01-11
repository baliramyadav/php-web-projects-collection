<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Install_m extends MY_Model
{

    protected $_table_name  = 'install';
    protected $_primary_key = 'installID';
    protected $_order_by    = "installID asc";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');

        $this->sql_path = SYSDIR . '/database/DB.txt';
    }

    public function get_install($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_install($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_install($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_install($array)
    {
        return parent::insert($array);
    }

    public function update_install($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_install($id)
    {
        return parent::delete($id);
    }

    public function use_sql_string()
    {
        $sqlstring = read_file($this->sql_path);
        $sqls      = explode(';', $sqlstring);

        if (calculate($sqls)) {
            foreach ($sqls as $sql) {
                $sql = trim($sql);
                $this->db->query($sql);
            }
        }
    }
}
