<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role_m extends MY_Model
{

    protected $_table_name  = 'role';
    protected $_primary_key = 'roleID';
    protected $_order_by    = "roleID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_role($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_role($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_role($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_role($array)
    {
        return parent::insert($array);
    }

    public function update_role($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_role($id)
    {
        return parent::delete($id);
    }

}
