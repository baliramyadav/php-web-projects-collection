<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Expense_m extends MY_Model
{

    protected $_table_name  = 'expense';
    protected $_primary_key = 'expenseID';
    protected $_order_by    = "expenseID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_expense($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_expense($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_expense($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_expense($array)
    {
        return parent::insert($array);
    }

    public function update_expense($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_expense($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_expense($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_expense_for_report($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if (isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('date >=', $array['fromdate']);
            $this->db->where('date <=', $array['todate']);
        }
        return $this->db->get()->result();
    }

}
