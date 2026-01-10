<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{

    protected $_table_name  = '';
    protected $_primary_key = '';
    protected $_order_by    = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($array = null, $single = false)
    {
        if (calculate($array) && is_array($array)) {
            $this->db->select($array);
        } else {
            $this->db->select('*');
        }
        if ($single) {
            $method = "row";
        } else {
            $method = "result";
        }
        if (!empty($this->_order_by)) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->$method();
    }

    public function get_order_by($wherearray = null, $array = null, $single = false)
    {
        if (calculate($array) && is_array($array)) {
            $this->db->select($array);
        } else {
            $this->db->select('*');
        }
        if ($single) {
            $method = "row";
        } else {
            $method = "result";
        }
        if (calculate($wherearray) && is_array($wherearray)) {
            $this->db->where($wherearray);
        }
        if (!empty($this->_order_by)) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->$method();
    }

    public function get_order_by_limit($limit1, $limit2 = null, $wherearray = null, $array = null, $single = false)
    {
        if (calculate($array) && is_array($array)) {
            $this->db->select($array);
        } else {
            $this->db->select('*');
        }
        if ($single) {
            $method = "row";
        } else {
            $method = "result";
        }
        if (calculate($wherearray) && is_array($wherearray)) {
            $this->db->where($wherearray);
        }
        if (!empty($this->_order_by)) {
            $this->db->order_by($this->_order_by);
        }
        $this->db->limit($limit1, $limit2);
        return $this->db->get($this->_table_name)->$method();
    }

    public function get_single($wherearray = null, $array = null, $single = true)
    {
        if (calculate($array) && is_array($array)) {
            $this->db->select($array);
        } else {
            $this->db->select('*');
        }
        if ($single) {
            $method = "row";
        } else {
            $method = "result";
        }
        if (calculate($wherearray) && is_array($wherearray)) {
            $this->db->where($wherearray);
        } elseif ((int) $wherearray) {
            $this->db->where($this->_primary_key, $wherearray);
        }

        if (!empty($this->_order_by)) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->$method();
    }

    public function insert($array)
    {
        $this->db->insert($this->_table_name, $array);
        return $this->db->insert_id();
    }

    public function update($array, $id)
    {
        if ((int) $id) {
            $this->db->set($array);
            $this->db->where($this->_primary_key, $id);
            return $this->db->update($this->_table_name);
        }
        return false;
    }

    public function delete($id)
    {
        if ((int) $id) {
            $this->db->where($this->_primary_key, $id);
            $this->db->limit(1);
            return $this->db->delete($this->_table_name);
        }
        return false;
    }

    public function delete_where($array)
    {
        if (calculate($array)) {
            $this->db->where($array);
            return $this->db->delete($this->_table_name);
        }
        return false;
    }

    public function insert_batch($array)
    {
        if (calculate($array)) {
            $this->db->insert_batch($this->_table_name, $array);
            return $this->db->insert_id();
        }
        return false;
    }

    public function update_batch($array, $column)
    {
        if (calculate($array)) {
            return $this->db->update_batch($this->_table_name, $array, $column);
        }
        return false;
    }

    public function get_where_in($column, $whereinarray, $wherearray = null, $array = null)
    {
        if (calculate($array) && is_array($array)) {
            $this->db->select($array);
        } else {
            $this->db->select('*');
        }
        $this->db->where_in($column, $whereinarray);
        if (calculate($wherearray) && is_array($wherearray)) {
            $this->db->where($wherearray);
        }
        if (!empty($this->_order_by)) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->result();
    }

}
