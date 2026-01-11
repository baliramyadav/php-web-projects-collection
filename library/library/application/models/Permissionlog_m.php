<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissionlog_m extends MY_Model
{

    protected $_table_name  = 'permissionlog';
    protected $_primary_key = 'permissionlogID';
    protected $_order_by    = "permissionlogID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_permissionlog($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_permissionlog($warray = null, $array = null, $single = false)
    {
        return parent::get_order_by($warray, $array, $single);
    }

    public function get_single_permissionlog($warray = null, $array = null, $single = true)
    {
        return parent::get_single($warray, $array, $single);
    }

    public function insert_permissionlog($array)
    {
        return parent::insert($array);
    }

    public function update_permissionlog($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_permissionlog($id)
    {
        return parent::delete($id);
    }

}
