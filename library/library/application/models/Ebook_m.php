<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ebook_m extends MY_Model
{

    protected $_table_name  = 'ebook';
    protected $_primary_key = 'ebookID';
    protected $_order_by    = "ebookID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_ebook($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_ebook($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_ebook($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_ebook($array)
    {
        return parent::insert($array);
    }

    public function update_ebook($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_ebook($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_ebook($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_ebook_limit($limit, $offset = null, $wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by_limit($limit, $offset, $wherearray, $array, $single);
    }

    public function get_order_by_ebook_limit_search($limit, $offset = null, $search = null)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->group_start();
        $this->db->like('name', $search, 'both');
        $this->db->or_like('author', $search, 'both');
        $this->db->or_like('notes', $search, 'both');
        $this->db->group_end();
        if (!empty($this->_order_by)) {
            $this->db->order_by($this->_order_by);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_ebook_search($search)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->group_start();
        $this->db->like('name', $search, 'both');
        $this->db->or_like('author', $search, 'both');
        $this->db->or_like('notes', $search, 'both');
        $this->db->group_end();
        return $this->db->get()->result();
    }

}
