<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rack_m extends MY_Model
{

    protected $_table_name  = 'rack';
    protected $_primary_key = 'rackID';
    protected $_order_by    = "rackID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_rack($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_rack($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_rack($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_rack($array)
    {
        return parent::insert($array);
    }

    public function update_rack($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_rack($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_rack($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

}
