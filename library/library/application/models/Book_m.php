<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Book_m extends MY_Model
{

    protected $_table_name  = 'book';
    protected $_primary_key = 'bookID';
    protected $_order_by    = "bookID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_book($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_book($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_book($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_book($array)
    {
        return parent::insert($array);
    }

    public function insert_book_batch($array)
    {
        return parent::insert_batch($array);
    }

    public function update_book($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_book($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_book($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_book_for_report($array)
    {
        return parent::get_order_by($array);
    }

}
