<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Income_m extends MY_Model
{

    protected $_table_name  = 'income';
    protected $_primary_key = 'incomeID';
    protected $_order_by    = "incomeID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_income($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_income($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_income($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_income($array)
    {
        return parent::insert($array);
    }

    public function update_income($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_income($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_income($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_income_for_report($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if (isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('date >=', $array['fromdate']);
            $this->db->where('date <=', $array['todate']);
        }
        $this->db->order_by('date asc');
        return $this->db->get()->result();
    }

}
