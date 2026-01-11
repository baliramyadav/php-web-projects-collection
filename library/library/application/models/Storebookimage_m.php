<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Storebookimage_m extends MY_Model
{

    protected $_table_name  = 'storebookimage';
    protected $_primary_key = 'storebookimageID';
    protected $_order_by    = "storebookimageID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_storebookimage($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_storebookimage($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_storebookimage($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_storebookimage($array)
    {
        return parent::insert($array);
    }

    public function insert_storebookimage_batch($array)
    {
        return parent::insert_batch($array);
    }

    public function update_storebookimage($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_storebookimage($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_storebookimage($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_storebookimage_for_report($array)
    {
        return parent::get_order_by($array);
    }

}
