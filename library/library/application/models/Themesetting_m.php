<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Themesetting_m extends MY_Model
{

    protected $_table_name = 'generalsetting';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_themesetting($array = null, $single = false)
    {
        return parent::get($array, $single);
    }

    public function get_order_by_themesetting($wherearray = null, $array = null, $single = false)
    {
        return parent::get_order_by($wherearray, $array, $single);
    }

    public function get_single_themesetting($wherearray = null, $array = null, $single = true)
    {
        return parent::get_single($wherearray, $array, $single);
    }

    public function insert_themesetting($array)
    {
        return parent::insert($array);
    }

    public function update_themesetting($data, $id = null)
    {
        return parent::update($data, $id);
    }

    public function delete_themesetting($id)
    {
        return parent::delete($id);
    }

    public function insertorupdate_themesetting($arrays)
    {
        if (calculate($arrays)) {
            foreach ($arrays as $optionkey => $optionvalue) {
                $optionvalue = str_replace("'", "\'", $optionvalue);
                $this->db->query("INSERT INTO generalsetting (optionkey, optionvalue) VALUES ('" . $optionkey . "', '" . $optionvalue . "') ON DUPLICATE KEY UPDATE optionkey='" . $optionkey . "' , optionvalue='" . $optionvalue . "'");
            }
        }
        return true;
    }

}
