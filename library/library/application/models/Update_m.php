<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Update_m extends MY_Model
{

    protected $_table_name  = 'updates';
    protected $_primary_key = 'updateID';
    protected $_order_by    = "updateID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_update($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_update($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_update($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_update($array)
    {
        return parent::insert($array);
    }

    public function update_update($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_update($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_update($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

}
