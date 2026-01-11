<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookitem_m extends MY_Model
{

    protected $_table_name  = 'bookitem';
    protected $_primary_key = 'bookitemID';
    protected $_order_by    = "bookitemID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_bookitem($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_bookitem($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_bookitem($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_bookitem($array)
    {
        return parent::insert($array);
    }

    public function insert_bookitem_batch($array)
    {
        return parent::insert_batch($array);
    }

    public function update_bookitem($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function update_bookitem_batch($array, $column)
    {
        return parent::update_batch($array, $column);
    }

    public function delete_bookitem($id)
    {
        return parent::delete($id);
    }

    public function update_bookitem_by_bookID($array, $bookID)
    {
        return $this->db->update($this->_table_name, $array, array('bookID' => $bookID));
    }

    public function get_where_in_bookitem($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

}
