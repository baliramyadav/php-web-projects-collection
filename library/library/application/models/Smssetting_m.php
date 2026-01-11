<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Smssetting_m extends MY_Model
{

    protected $_table_name = 'smssetting';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_smssetting($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_smssetting($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_smssetting($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_smssetting($array)
    {
        return parent::insert($array);
    }

    public function update_smssetting($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_smssetting($id)
    {
        return parent::delete($id);
    }

    public function insertorupdate_smssetting($arrays)
    {
        if (calculate($arrays)) {
            foreach ($arrays as $optionkey => $optionvalue) {
                $optionvalue = str_replace("'", "\'", $optionvalue);
                $this->db->query("INSERT INTO smssetting (optionkey, optionvalue) VALUES ('" . $optionkey . "', '" . $optionvalue . "') ON DUPLICATE KEY UPDATE optionkey='" . $optionkey . "' , optionvalue='" . $optionvalue . "'");
            }
        }
        return true;
    }

}
