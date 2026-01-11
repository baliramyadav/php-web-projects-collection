<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resetpassword_m extends MY_Model
{

    protected $_table_name  = 'resetpassword';
    protected $_primary_key = 'resetpasswordID';
    protected $_order_by    = "resetpasswordID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_resetpassword($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_resetpassword($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_resetpassword($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_resetpassword($array)
    {
        return parent::insert($array);
    }

    public function update_resetpassword($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_resetpassword($id)
    {
        return parent::delete($id);
    }

    public function get_single_resetpassword_by_username_or_email_and_code($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->group_start();
        $this->db->where('username', $array['username_or_email']);
        $this->db->or_where('email', $array['username_or_email']);
        $this->db->group_end();
        $this->db->where('code', $array['code']);
        $this->db->order_by('resetpasswordID', 'desc');
        $query = $this->db->get();
        return $query->row();
    }

}
