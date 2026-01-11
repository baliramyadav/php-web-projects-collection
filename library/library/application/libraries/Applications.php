<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applications
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();

        //Load email library
        $this->CI->load->library('parser');
        $this->CI->load->library('email');
        $this->CI->load->model('emailsetting_m');
    }

    public function sendemails($users, $message, $subject, $fromemail = 'no-reply@admin.com')
    {
        $this->_configure_email_setting();

        $sendemails       = $this->convert_tag($users, $message);
        $notsendemailuser = [];
        if (calculate($sendemails)) {
            foreach ($sendemails as $senduserID => $sendemail) {
                $viewload = $this->CI->load->view('_template/sendmail', $sendemail, true);
                $this->CI->email->to($sendemail['email']);
                $this->CI->email->from($fromemail, 'Portfolio');
                $this->CI->email->subject($subject);
                $this->CI->email->message($viewload);
                //Send email
                if (!$this->CI->email->send()) {
                    $notsendemailuser[$senduserID] = $senduserID;
                }
            }
        }
        echo $notsendemailuser;
    }

    // emailsend
    public function sendemail($email, $message, $subject, $name, $fromemail = 'no-reply@admin.com')
    {
        $this->_configure_email_setting();

        $passArray['message'] = $message;
        $viewload             = $this->CI->load->view('_template/sendmail', $passArray, true);
        $this->CI->email->to($email);
        $this->CI->email->from($fromemail, $name);
        $this->CI->email->subject($subject);
        $this->CI->email->message($viewload);
        //Send email
        return $this->CI->email->send();
    }

    public function sendmail($email, $message, $subject, $name, $fromemail = 'no-reply@admin.com')
    {
        $this->_configure_email_setting();

        $this->CI->email->to($email);
        $this->CI->email->from($fromemail, $name);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        //Send email
        return $this->CI->email->send();
    }

    private function convert_tag($memebers, $message)
    {
        $retArray = [];
        if (calculate($memebers)) {
            foreach ($memebers as $memeber) {
                $message = str_replace('[memberID]', "memeber ID " . $memeber->memberID, $message);
                $message = str_replace('[name]', $memeber->name, $message);
                $message = str_replace('[dateofbirth]', date('d m Y H:i:s', strtotime($memeber->dateofbirth)), $message);
                $message = str_replace('[gender]', $memeber->gender, $message);
                $message = str_replace('[religion]', $memeber->religion, $message);
                $message = str_replace('[email]', $memeber->email, $message);
                $message = str_replace('[phone]', $memeber->phone, $message);
                $message = str_replace('[address]', $memeber->address, $message);
                $message = str_replace('[joinningdate]', date('d m Y H:i:s', strtotime($memeber->joiningdate)), $message);
                if ($memeber->photo) {
                    $imageurl = "<img src='" . profile_img($memeber->photo) . "'/>";
                    $message  = str_replace('[photo]', $imageurl, $message);
                }
                $message                                 = str_replace('[username]', $memeber->username, $message);
                $message                                 = str_replace('[current_date]', date('d m Y H:i:s'), $message);
                $retArray[$memeber->memberID]['message'] = $message;
                $retArray[$memeber->memberID]['email']   = $memeber->email;
            }
        }
        return $retArray;
    }

    // SMTP & mail configuration
    private function _configure_email_setting()
    {
        $emailsetting = (object) pluck($this->CI->emailsetting_m->get_emailsetting(), 'optionvalue', 'optionkey');
        if (calculate($emailsetting)) {
            $config = array(
                'protocol'  => $emailsetting->mail_driver,
                'smtp_host' => $emailsetting->mail_host,
                'smtp_port' => $emailsetting->mail_port,
                'smtp_user' => $emailsetting->mail_username,
                'smtp_pass' => $emailsetting->mail_password,
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
            );
            $this->CI->email->initialize($config);
            $this->CI->email->set_mailtype("html");
            $this->CI->email->set_newline("\r\n");
        }
    }

}
