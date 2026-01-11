<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_m extends MY_Model
{

    protected $_table_name  = 'menu';
    protected $_primary_key = 'menuID';
    protected $_order_by    = "menuID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_menu($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_menu($warray = null, $array = null, $single = false)
    {
        return parent::get_order_by($warray, $array, $single);
    }

    public function get_single_menu($warray = null, $array = null, $single = true)
    {
        return parent::get_single($warray, $array, $single);
    }

    public function insert_menu($array)
    {
        return parent::insert($array);
    }

    public function update_menu($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_menu($id)
    {
        return parent::delete($id);
    }

}
