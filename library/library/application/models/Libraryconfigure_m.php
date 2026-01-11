<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Libraryconfigure_m extends MY_Model
{

    protected $_table_name  = 'libraryconfigure';
    protected $_primary_key = 'libraryconfigureID';
    protected $_order_by    = "libraryconfigureID desc";

    public $libraryconfigure = ['roleID' => 0, 'max_issue_book' => 0, 'max_renewed_limit' => 0, 'per_renew_limit_day' => 0, 'book_fine_per_day' => 0, 'issue_off_limit_amount' => 0];

    public function __construct()
    {
        parent::__construct();
    }

    public function get_libraryconfigure($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_libraryconfigure($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_libraryconfigure($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_libraryconfigure($array)
    {
        return parent::insert($array);
    }

    public function update_libraryconfigure($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_libraryconfigure($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_libraryconfigure($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

}
