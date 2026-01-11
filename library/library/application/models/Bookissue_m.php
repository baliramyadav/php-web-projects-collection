<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookissue_m extends MY_Model
{

    protected $_table_name  = 'bookissue';
    protected $_primary_key = 'bookissueID';
    protected $_order_by    = "bookissueID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_bookissue($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_bookissue($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_bookissue($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_bookissue($array)
    {
        return parent::insert($array);
    }

    public function insert_bookissue_batch($array)
    {
        return parent::insert_batch($array);
    }

    public function update_bookissue($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_bookissue($id)
    {
        return parent::delete($id);
    }

    public function get_where_in_bookissue($column, $whereinarray, $wherearray = null, $array = null)
    {
        return parent::get_where_in($column, $whereinarray, $wherearray, $array);
    }

    public function get_order_by_bookissue_for_report($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if (isset($array['roleID'])) {
            $this->db->where('roleID', $array['roleID']);
            if (isset($array['memberID'])) {
                $this->db->where('memberID', $array['memberID']);
            }
        }
        if (isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('issue_date >=', $array['fromdate']);
            $this->db->where('issue_date <=', $array['todate']);
        }
        $this->db->where('deleted_at', 0);
        $this->db->where('paidstatus !=', 2);
        return $this->db->get()->result();
    }

    public function get_order_by_bookissue_for_bookissuereport($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if (isset($array['bookcategoryID'])) {
            $this->db->where('bookcategoryID', $array['bookcategoryID']);
            if (isset($array['bookID'])) {
                $this->db->where('bookID', $array['bookID']);
            }
        }
        if (isset($array['roleID'])) {
            $this->db->where('roleID', $array['roleID']);
            if (isset($array['memberID'])) {
                $this->db->where('memberID', $array['memberID']);
            }
        }
        if (isset($array['status'])) {
            $this->db->where('status', $array['status']);
        }
        if (isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('issue_date >=', $array['fromdate']);
            $this->db->where('issue_date <=', $array['todate']);
        }
        $this->db->where('deleted_at', 0);
        return $this->db->get()->result();
    }

}
