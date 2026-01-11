<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Smstemplate_m extends MY_Model
{

    protected $_table_name  = 'smstemplate';
    protected $_primary_key = 'smstemplateID';
    protected $_order_by    = "smstemplateID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_smstemplate($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_smstemplate($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_smstemplate($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_smstemplate($array)
    {
        return parent::insert($array);
    }

    public function update_smstemplate($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_smstemplate($id)
    {
        return parent::delete($id);
    }

}
