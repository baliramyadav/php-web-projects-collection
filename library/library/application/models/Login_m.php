<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_m extends MY_Model
{

    protected $_table_name  = 'member';
    protected $_primary_key = 'memberID';
    protected $_order_by    = "memberID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_login($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_login($warray = null, $array = null, $single = false)
    {
        return parent::get_order_by($warray, $array, $single);
    }

    public function get_single_login($warray = null, $array = null, $single = true)
    {
        return parent::get_single($warray, $array, $single);
    }

    public function get_single_login_by_username_or_email_and_password($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->group_start();
        $this->db->where('username', $array['username_or_email']);
        $this->db->or_where('email', $array['username_or_email']);
        $this->db->group_end();
        $this->db->where('password', $array['password']);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_single_login_check_by_username_or_email($username_or_email)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('username', $username_or_email);
        $this->db->or_where('email', $username_or_email);
        $query = $this->db->get();
        return $query->row();
    }

    public function insert_login($array)
    {
        return parent::insert($array);
    }

    public function update_login($array, $memberID = null)
    {
        return parent::update($array, $memberID);
    }

    public function delete_login($memberID)
    {
        return parent::delete($memberID);
    }

}
