<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissions_m extends MY_Model
{

    protected $_table_name = 'permissions';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_permissions($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_permissions($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_permissions($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_permissions($array)
    {
        return parent::insert($array);
    }

    public function update_permissions($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_permissions($id)
    {
        return parent::delete($id);
    }

    public function delete_permissions_by_roleID($roleID)
    {
        if ((int) $roleID) {
            $this->db->where('roleID', $roleID);
            return $this->db->delete($this->_table_name);
        }
        return false;
    }

    public function insert_batch_permissions($array)
    {
        return parent::insert_batch($array);
    }

    public function get_permissions_with_permissionlog_by_roleID($roleID)
    {
        $this->db->select('*');
        $this->db->from('permissions');
        $this->db->join('permissionlog', 'permissions.permissionlogID = permissionlog.permissionlogID');
        $this->db->where('permissions.roleID', $roleID);
        return $this->db->get()->result();
    }

}
