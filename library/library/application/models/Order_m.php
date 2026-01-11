<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_m extends MY_Model
{

    protected $_table_name  = 'orders';
    protected $_primary_key = 'orderID';
    protected $_order_by    = "orderID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_order($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_order($warray = null, $array = null, $single = false)
    {
        return parent::get_order_by($warray, $array, $single);
    }

    public function get_single_order($warray = null, $array = null, $single = true)
    {
        return parent::get_single($warray, $array, $single);
    }

    public function insert_order($array)
    {
        return parent::insert($array);
    }

    public function update_order($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_order($id)
    {
        return parent::delete($id);
    }

}
