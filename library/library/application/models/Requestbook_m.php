<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Requestbook_m extends MY_Model
{

    protected $_table_name  = 'requestbook';
    protected $_primary_key = 'requestbookID';
    protected $_order_by    = "requestbookID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_requestbook($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_requestbook($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_requestbook($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_requestbook($array)
    {
        return parent::insert($array);
    }

    public function update_requestbook($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_requestbook($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_requestbook($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

}
