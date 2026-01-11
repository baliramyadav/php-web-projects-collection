<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }

    public function required_no_zero($data)
    {
        if ($data != '') {
            if ($data == '0') {
                $this->CI->form_validation->set_message('required_no_zero', 'The {field} field is required.');
                return false;
            }
        }
        return true;
    }

    public function valid_date($date)
    {
        if ($date) {
            if (strlen($date) != 10) {
                $this->CI->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                return false;
            } else {
                $arr = explode("-", $date);
                if (calculate($arr)) {
                    $dd   = $arr[0];
                    $mm   = $arr[1];
                    $yyyy = $arr[2];
                    if (checkdate($mm, $dd, $yyyy)) {
                        return true;
                    } else {
                        $this->CI->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                        return false;
                    }
                } else {
                    $this->CI->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                    return false;
                }
            }
        }
        return true;
    }

    public function valid_username($username)
    {
        if ($username) {
            $username_pattern = "/^[a-z\d]{4,60}$/";
            $check            = calculate(explode(' ', $username));
            if ($check > 1) {
                $this->CI->form_validation->set_message("valid_username", 'Please remove white space and provide valid username.');
                return false;
            } elseif (preg_match($username_pattern, $username)) {
                return true;
            } else {
                $this->CI->form_validation->set_message("valid_username", 'Please Provide valid username.');
                return false;
            }
        }
        return true;
    }

}
