<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Storebook_m extends MY_Model
{

    protected $_table_name  = 'storebook';
    protected $_primary_key = 'storebookID';
    protected $_order_by    = "storebookID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_storebook($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_storebook($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_storebook($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_storebook($array)
    {
        return parent::insert($array);
    }

    public function insert_storebook_batch($array)
    {
        return parent::insert_batch($array);
    }

    public function update_storebook($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_storebook($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_storebook($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_storebook_for_report($array)
    {
        return parent::get_order_by($array);
    }

    public function get_order_by_storebook_limit($limit, $offset = null, $wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by_limit($limit, $offset, $wherearray, $array, $single);
    }

    public function get_order_by_storebook_limit_search($limit, $offset = null, $queryArray = [])
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->group_start();
        if (isset($queryArray['search'])) {
            $this->db->like('name', $queryArray['search'], 'both');
            $this->db->or_like('author', $queryArray['search'], 'both');
            $this->db->or_like('description', $queryArray['search'], 'both');
            $this->db->or_like('notes', $queryArray['search'], 'both');
        }
        if (isset($queryArray['category'])) {
            $this->db->or_like('storebookcategoryID', $queryArray['category'], 'both');
        }
        $this->db->group_end();
        if (!empty($this->_order_by)) {
            $this->db->order_by($this->_order_by);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_storebook_search($queryArray = [])
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->group_start();
        if (isset($queryArray['search'])) {
            $this->db->like('name', $queryArray['search'], 'both');
            $this->db->or_like('author', $queryArray['search'], 'both');
            $this->db->or_like('description', $queryArray['search'], 'both');
            $this->db->or_like('notes', $queryArray['search'], 'both');
        }
        if (isset($queryArray['category'])) {
            $this->db->or_like('storebookcategoryID', $queryArray['category'], 'both');
        }
        $this->db->group_end();
        return $this->db->get()->result();
    }

}
