<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emailtemplate_m extends MY_Model
{

    protected $_table_name  = 'emailtemplate';
    protected $_primary_key = 'emailtemplateID';
    protected $_order_by    = "emailtemplateID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_emailtemplate($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_emailtemplate($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_emailtemplate($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_emailtemplate($array)
    {
        return parent::insert($array);
    }

    public function update_emailtemplate($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_emailtemplate($id)
    {
        return parent::delete($id);
    }

}
