<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Finehistory_m extends MY_Model
{

    protected $_table_name  = 'finehistory';
    protected $_primary_key = 'finehistoryID';
    protected $_order_by    = "finehistoryID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_finehistory($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_finehistory($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_finehistory_by_limit($limit1, $limit2 = null)
    {
        return parent::get_order_by_limit($limit1, $limit2);
    }

    public function get_single_finehistory($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_finehistory($array)
    {
        return parent::insert($array);
    }

    public function update_finehistory($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_finehistory($id)
    {
        return parent::delete($id);
    }

    public function get_order_by_finehistory_for_report($array)
    {
        $this->db->select('*, finehistory.fineamount as famount');
        $this->db->from($this->_table_name);
        $this->db->join('bookissue', 'bookissue.bookissueID=finehistory.bookissueID');
        if (isset($array['roleID'])) {
            $this->db->where('bookissue.roleID', $array['roleID']);
            if (isset($array['memberID'])) {
                $this->db->where('bookissue.memberID', $array['memberID']);
            }
        }
        if (isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('finehistory.create_date >=', $array['fromdate']);
            $this->db->where('finehistory.create_date <=', $array['todate']);
        }
        $this->db->where('finehistory.fineamount >', 0);
        return $this->db->get()->result();
    }

}
