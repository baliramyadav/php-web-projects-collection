<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paymentanddiscount_m extends MY_Model
{

    protected $_table_name  = 'paymentanddiscount';
    protected $_primary_key = 'paymentanddiscountID';
    protected $_order_by    = "paymentanddiscountID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_paymentanddiscount($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_paymentanddiscount($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_paymentanddiscount($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_paymentanddiscount($array)
    {
        return parent::insert($array);
    }

    public function insert_paymentanddiscount_batch($array)
    {
        return parent::insert_batch($array);
    }

    public function update_paymentanddiscount($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_paymentanddiscount($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_paymentanddiscount($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_paymentanddiscount_for_report($array)
    {
        $this->db->select('*, paymentanddiscount.paymentamount as payamount, paymentanddiscount.discountamount as disamount');
        $this->db->from($this->_table_name);
        $this->db->join('bookissue', 'bookissue.bookissueID=paymentanddiscount.bookissueID');
        if (isset($array['roleID'])) {
            $this->db->where('bookissue.roleID', $array['roleID']);
            if (isset($array['memberID'])) {
                $this->db->where('bookissue.memberID', $array['memberID']);
            }
        }
        if (isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('paymentanddiscount.create_date >=', $array['fromdate']);
            $this->db->where('paymentanddiscount.create_date <=', $array['todate']);
        }
        return $this->db->get()->result();
    }

}
