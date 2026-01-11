<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_m extends MY_Model
{

    protected $_table_name  = 'dashboard';
    protected $_primary_key = 'dashboardID';
    protected $_order_by    = "dashboardID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_dashboard($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_dashboard($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_dashboard($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_dashboard($array)
    {
        return parent::insert($array);
    }

    public function update_dashboard($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_dashboard($id)
    {
        return parent::delete($id);
    }

}
