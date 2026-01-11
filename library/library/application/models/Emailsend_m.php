<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emailsend_m extends MY_Model
{

    protected $_table_name  = 'emailsend';
    protected $_primary_key = 'emailsendID';
    protected $_order_by    = "emailsendID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_emailsend($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_emailsend($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_emailsend($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_emailsend($array)
    {
        return parent::insert($array);
    }

    public function update_emailsend($array, $emailsendID = null)
    {
        return parent::update($array, $emailsendID);
    }

    public function delete_emailsend($emailsendID)
    {
        return parent::delete($emailsendID);
    }

}
