<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_m extends MY_Model
{

    protected $_table_name  = 'member';
    protected $_primary_key = 'memberID';
    protected $_order_by    = "memberID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_member($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_member($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_member($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_member($array)
    {
        return parent::insert($array);
    }

    public function update_member($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_member($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_member($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

}
