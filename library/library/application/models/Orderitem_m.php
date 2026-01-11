<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orderitem_m extends MY_Model
{

    protected $_table_name   = 'orderitems';
    protected $_primary_key  = 'orderitemID';
    protected $_orderitem_by = "priority desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_orderitem($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_orderitem_by_orderitem($warray = null, $array = null, $single = false)
    {
        return parent::get_orderitem_by($warray, $array, $single);
    }

    public function get_single_orderitem($warray = null, $array = null, $single = true)
    {
        return parent::get_single($warray, $array, $single);
    }

    public function insert_orderitem($array)
    {
        return parent::insert($array);
    }

    public function update_orderitem($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_orderitem($id)
    {
        return parent::delete($id);
    }

    public function get_order_by_orderitem_with_storebook($array)
    {
        $this->db->select('orderitems.*, storebook.name, storebook.coverphoto');
        $this->db->from($this->_table_name);
        $this->db->join('storebook', 'orderitems.storebookID=storebook.storebookID');
        if (isset($array['orderID'])) {
            $this->db->where('orderitems.orderID', $array['orderID']);
        }
        return $this->db->get()->result();
    }

    public function get_order_by_orderitem_with_sum($warray = null)
    {
        $this->db->select_sum('quantity');
        $this->db->where($warray);
        return $this->db->get($this->_table_name)->row()->quantity;
    }

}
