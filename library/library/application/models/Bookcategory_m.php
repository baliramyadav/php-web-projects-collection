<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookcategory_m extends MY_Model
{

    protected $_table_name  = 'bookcategory';
    protected $_primary_key = 'bookcategoryID';
    protected $_order_by    = "bookcategoryID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_bookcategory($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_bookcategory($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_bookcategory($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_bookcategory($array)
    {
        return parent::insert($array);
    }

    public function update_bookcategory($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_bookcategory($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_bookcategory($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_bookcategory_limit($limit, $offset = null, $wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by_limit($limit, $offset, $wherearray, $array, $single);
    }

}
