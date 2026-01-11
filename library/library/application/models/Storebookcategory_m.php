<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Storebookcategory_m extends MY_Model
{

    protected $_table_name  = 'storebookcategory';
    protected $_primary_key = 'storebookcategoryID';
    protected $_order_by    = "storebookcategoryID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_storebookcategory($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_storebookcategory($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_storebookcategory($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_storebookcategory($array)
    {
        return parent::insert($array);
    }

    public function update_storebookcategory($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_storebookcategory($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_storebookcategory($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_storebookcategory_limit($limit, $offset = null, $wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by_limit($limit, $offset, $wherearray, $array, $single);
    }

}
