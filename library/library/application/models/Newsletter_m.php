<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Newsletter_m extends MY_Model
{

    protected $_table_name  = 'newsletter';
    protected $_primary_key = 'newsletterID';
    protected $_order_by    = "newsletterID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_newsletter($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_newsletter($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_newsletter($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_newsletter($array)
    {
        return parent::insert($array);
    }

    public function update_newsletter($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_newsletter($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_newsletter($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_newsletter_limit($limit, $offset = null, $wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by_limit($limit, $offset, $wherearray, $array, $single);
    }

}
