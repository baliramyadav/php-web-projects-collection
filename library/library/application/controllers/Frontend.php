<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Frontend extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ebook_m');
        $this->load->model('storebook_m');
        $this->load->model('newsletter_m');
        $this->load->model('bookcategory_m');
        $this->load->model('storebookcategory_m');
        $this->load->model('book_m');
        $this->load->model('bookitem_m');
        $this->load->model('order_m');
        $this->load->model('orderitem_m');
        $this->load->model('storebookimage_m');
        $this->load->library('applications');
        $this->load->library('pagination');
        $this->load->library('paypal_lib');

        $lang = $this->session->userdata('language');
        $this->lang->load('frontend', $lang);
    }

    public function index()
    {
        $this->data['bookcategorys']      = $this->bookcategory_m->get_bookcategory();
        $this->data['storebookcategorys'] = $this->storebookcategory_m->get_storebookcategory();
        $this->data["subview"]            = "frontend/index";
        $this->load->view('_frontend_layout', $this->data);
    }

    // Ebook
    public function ebook()
    {
        $ebookID                    = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/custom/css/ebook.css',
            ),
        );

        $search = $this->input->get('search');
        if (empty($search)) {
            $ebook = calculate($this->ebook_m->get_ebook());
        } else {
            $ebook = calculate($this->ebook_m->get_ebook_search($search));
        }

        $config['base_url']           = base_url('frontend/ebook');
        $config['total_rows']         = $ebook;
        $config['per_page']           = 12;
        $config['num_links']          = 5;
        $config['full_tag_open']      = '<ul class="pagination">';
        $config['full_tag_close']     = '</ul>';
        $config['attributes']         = ['class' => 'page-link'];
        $config['first_link']         = false;
        $config['last_link']          = false;
        $config['first_tag_open']     = '<li class="page-item">';
        $config['first_tag_close']    = '</li>';
        $config['prev_link']          = '&laquo Previous';
        $config['prev_tag_open']      = '<li class="page-item">';
        $config['prev_tag_close']     = '</li>';
        $config['next_link']          = 'Next &raquo';
        $config['next_tag_open']      = '<li class="page-item">';
        $config['next_tag_close']     = '</li>';
        $config['last_tag_open']      = '<li class="page-item">';
        $config['last_tag_close']     = '</li>';
        $config['cur_tag_open']       = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close']      = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open']       = '<li class="page-item">';
        $config['num_tag_close']      = '</li>';
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        if (empty($search)) {
            $this->data["ebooks"] = $this->ebook_m->get_order_by_ebook_limit($config['per_page'], $ebookID);
        } else {
            $this->data["ebooks"] = $this->ebook_m->get_order_by_ebook_limit_search($config['per_page'], $ebookID, $search);
        }
        $this->data['search'] = $search;

        $this->data["subview"] = "frontend/ebook";
        $this->load->view('_frontend_layout', $this->data);
    }

    public function ebookview()
    {
        $ebookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $ebookID) {
            $this->data['ebook'] = $this->ebook_m->get_single_ebook(array('ebookID' => $ebookID));
            if (calculate($this->data['ebook'])) {
                $fileimg = FCPATH . '/uploads/ebook/' . $this->data['ebook']->file;
                if (!file_exists($fileimg)) {
                    $this->session->set_flashdata('error', 'The Book file is not found');
                    redirect(base_url('frontend/ebook'));
                } else {
                    $this->data['headerassets'] = array(
                        'headerjs' => array(
                            'assets/custom/js/pdfobject.min.js',
                        ),
                    );
                    $this->data["subview"] = "frontend/ebookview";
                    $this->load->view('_frontend_layout', $this->data);
                }
            } else {
                $this->session->set_flashdata('error', 'The Book file is not found');
                redirect(base_url('frontend/ebook'));
            }
        } else {
            $this->session->set_flashdata('error', 'The Book file is not found');
            redirect(base_url('frontend/ebook'));
        }
    }

    public function ebookdownload()
    {
        if ($this->data["generalsetting"]->ebook_download != 1) {
            $this->session->set_flashdata('error', "You dont have permission to download this ebook.");
            redirect(base_url('frontend/ebook'));
        }
        $ebookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $ebookID) {
            $ebook = $this->ebook_m->get_single_ebook(array('ebookID' => $ebookID));
            if (calculate($ebook)) {
                $file = realpath('uploads/ebook/' . $ebook->file);
                if (file_exists($file)) {
                    $originalname = $ebook->file_original_name;
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    $this->session->set_flashdata('error', 'The Book file is not found');
                    redirect(base_url('frontend/ebook'));
                }
            } else {
                $this->session->set_flashdata('error', 'The Book file is not found');
                redirect(base_url('frontend/ebook'));
            }
        } else {
            $this->session->set_flashdata('error', 'The Book file is not found');
            redirect(base_url('frontend/ebook'));
        }
    }

    // Book
    public function book()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/custom/js/bookreport.js',
            ),
        );

        $this->data['flag']           = 0;
        $this->data['bookcategoryID'] = 0;
        $this->data['bookID']         = 0;
        $this->data['status']         = 0;

        $this->data['books']         = [];
        $this->data['bookcategorys'] = pluck($this->bookcategory_m->get_bookcategory(), 'obj', 'bookcategoryID');
        unset($_SESSION['error']);
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $message = implode('<br/>', $this->form_validation->error_array());
                $this->session->set_flashdata('error', $message);
                $this->data["subview"] = "report/book/index";
                $this->load->view('_main_layout', $this->data);
            } else {
                $bookcategoryID = $this->input->post('bookcategoryID');
                $bookID         = $this->input->post('bookID');
                $status         = $this->input->post('status');

                $this->_queryArray(['bookcategoryID' => $bookcategoryID, 'bookID' => $bookID, 'status' => $status]);
            }
        }
        $this->data["subview"] = "frontend/book";
        $this->load->view('_frontend_layout', $this->data);
    }

    private function _queryArray($queryArr)
    {
        extract($queryArr);

        $queryArray = [];
        $itemArray  = [];
        if ((int) $bookcategoryID) {
            $queryArray['bookcategoryID'] = $bookcategoryID;
        }
        if ((int) $bookID) {
            $queryArray['bookID'] = $bookID;
            $itemArray['bookID']  = $bookID;
        }
        if ((int) $status) {
            $queryArray['status'] = $status - 1;
        }
        $itemArray['status']     = 0;
        $itemArray['deleted_at'] = 0;

        $books     = $this->book_m->get_order_by_book_for_report($queryArray);
        $bookitems = $this->bookitem_m->get_order_by_bookitem($itemArray);

        $bookQuantity = [];
        if (calculate($bookitems)) {
            foreach ($bookitems as $bookitem) {
                if (isset($bookQuantity[$bookitem->bookID])) {
                    $bookQuantity[$bookitem->bookID]++;
                } else {
                    $bookQuantity[$bookitem->bookID] = 1;
                }
            }
        }

        $this->data['flag']           = 1;
        $this->data['bookcategoryID'] = $bookcategoryID;
        $this->data['bookID']         = $bookID;
        $this->data['status']         = $status;
        $this->data['bookQuantity']   = $bookQuantity;
        $this->data['books']          = $books;
    }

    public function get_book()
    {
        echo "<option value='0'>" . $this->lang->line('frontend_please_select') . "</option>";
        if ($_POST) {
            $bookcategoryID          = $this->input->post('bookcategoryID');
            $array['deleted_at']     = 0;
            $array['bookcategoryID'] = $bookcategoryID;

            if ((int) $bookcategoryID) {
                $books = $this->book_m->get_order_by_book($array, array('bookID', 'name', 'codeno'));
                if (calculate($books)) {
                    foreach ($books as $book) {
                        echo "<option value='" . $book->bookID . "'>" . $book->name . ' - ' . $book->codeno . "</option>";
                    }
                }
            }
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'bookcategoryID',
                'label' => $this->lang->line('frontend_book_category'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'bookID',
                'label' => $this->lang->line('frontend_book'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line('frontend_status'),
                'rules' => 'trim|xss_clean|required|numeric',
            ),
        );
        return $rules;
    }

    // Shop
    public function shop()
    {
        $storebookID = htmlentities(escapeString($this->uri->segment(3)));
        $search      = $this->input->get('search');
        $category    = $this->input->get('category');

        $queryArray = [];
        if (!empty($search)) {
            $queryArray['search'] = $search;
        } else if (!empty($category)) {
            $queryArray['category'] = $category;
        }

        if (calculate($queryArray)) {
            $storebook = calculate($this->storebook_m->get_storebook_search($queryArray));
        } else {
            $storebook = calculate($this->storebook_m->get_storebook());
        }

        $config['base_url']           = base_url('frontend/shop');
        $config['total_rows']         = $storebook;
        $config['per_page']           = 12;
        $config['num_links']          = 5;
        $config['full_tag_open']      = '<ul class="pagination">';
        $config['full_tag_close']     = '</ul>';
        $config['attributes']         = ['class' => 'page-link'];
        $config['first_link']         = false;
        $config['last_link']          = false;
        $config['first_tag_open']     = '<li class="page-item">';
        $config['first_tag_close']    = '</li>';
        $config['prev_link']          = '&laquo Previous';
        $config['prev_tag_open']      = '<li class="page-item">';
        $config['prev_tag_close']     = '</li>';
        $config['next_link']          = 'Next &raquo';
        $config['next_tag_open']      = '<li class="page-item">';
        $config['next_tag_close']     = '</li>';
        $config['last_tag_open']      = '<li class="page-item">';
        $config['last_tag_close']     = '</li>';
        $config['cur_tag_open']       = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close']      = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open']       = '<li class="page-item">';
        $config['num_tag_close']      = '</li>';
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        if (calculate($queryArray)) {
            $this->data["storebooks"] = $this->storebook_m->get_order_by_storebook_limit_search($config['per_page'], $storebookID, $queryArray);
        } else {
            $this->data["storebooks"] = $this->storebook_m->get_order_by_storebook_limit($config['per_page'], $storebookID);
        }
        $this->data['storebookcategorys'] = $this->storebookcategory_m->get_storebookcategory();

        $this->data['search']  = $search;
        $this->data["subview"] = "frontend/shop";
        $this->load->view('_frontend_layout', $this->data);
    }

    public function single($storebookID)
    {
        $this->data['storebook']       = $this->storebook_m->get_single_storebook($storebookID);
        $this->data['storebookimages'] = $this->storebookimage_m->get_order_by_storebookimage(['storebookID' => $storebookID]);
        $this->data["subview"]         = "frontend/single";
        $this->load->view('_frontend_layout', $this->data);
    }

    // Cart
    public function cart()
    {
        if (calculate($this->data["cart_contents"])) {
            $this->data["subview"] = "frontend/cart";
            $this->load->view('_frontend_layout', $this->data);
        } else {
            $this->session->set_flashdata('error', $this->lang->line('frontend_cart_empty'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function addcart()
    {
        $storebookID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $storebookID) {
            $storebook = $this->storebook_m->get_single_storebook($storebookID);
            if (calculate($storebook)) {
                $qty = $this->input->post('qty') ? $this->input->post('qty') : 1;

                if ($this->checkOrderQuantity($storebook, $qty)) {
                    $data = array(
                        'id'     => $storebook->storebookID,
                        'name'   => $storebook->name,
                        'images' => app_image_link($storebook->coverphoto, 'uploads/storebook/', 'storebook.jpg'),
                        'price'  => $storebook->price,
                        'qty'    => $qty,
                    );
                    $this->cart->insert($data);
                    $this->session->set_flashdata('success', 'This product added cart successfully.');
                } else {
                    $this->session->set_flashdata('error', 'This book are not out of stock.');
                }
            } else {
                $this->session->set_flashdata('error', 'This book is not found.');
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    private function checkOrderQuantity($storebook, $qty)
    {
        $orderQuantity = $this->orderitem_m->get_order_by_orderitem_with_sum(['storebookID' => $storebook->storebookID]) + $qty;
        if ($orderQuantity <= $storebook->quantity) {
            return true;
        }
        return false;
    }

    public function removecart()
    {

        $rowID = htmlentities(escapeString($this->uri->segment(3)));
        if (!empty($rowID)) {
            $this->cart->remove($rowID);
            $this->session->set_flashdata('success', 'This product removed from cart successfully.');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function checkout()
    {
        $cart_contents = $this->cart->contents();
        if (!calculate($cart_contents)) {
            $this->session->set_flashdata('error', $this->lang->line('frontend_cart_empty'));
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($_POST) {
            $rules = $this->rules_checkout();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "frontend/checkout";
                $this->load->view('_frontend_layout', $this->data);
            } else {
                $order['memberID']         = $this->session->userdata('loginmemberID');
                $order['name']             = $this->input->post('name');
                $order['mobile']           = $this->input->post('mobile');
                $order['email']            = $this->input->post('email');
                $order['address']          = $this->input->post('address');
                $order['delivery_charge']  = $this->data['generalsetting']->delivery_charge;
                $order['subtotal']         = $this->cart->total();
                $order['total']            = $this->cart->total() + $order['delivery_charge'];
                $order['payment_status']   = 5;
                $order['payment_method']   = $this->input->post('payment_method');
                $order['paid_amount']      = 0;
                $order['discounted_price'] = 0;
                $order['status']           = 5;
                $order['notes']            = $this->input->post('notes');
                $order['create_date']      = date('Y-m-d H:i:s');
                $order['modify_date']      = date('Y-m-d H:i:s');

                $this->order_m->insert_order($order);
                $orderID = $this->db->insert_id();

                foreach ($cart_contents as $cart_content) {
                    $orderitem['orderID']     = $orderID;
                    $orderitem['storebookID'] = $cart_content['id'];
                    $orderitem['quantity']    = $cart_content['qty'];
                    $orderitem['unit_price']  = $cart_content['price'];
                    $orderitem['subtotal']    = $cart_content['subtotal'];
                    $orderitem['create_date'] = date('Y-m-d H:i:s');
                    $orderitem['modify_date'] = date('Y-m-d H:i:s');

                    $this->orderitem_m->insert_orderitem($orderitem);
                }
                $this->cart->destroy();
                $this->session->set_userdata('stripeToken', $this->input->post('stripeToken'));
                redirect(base_url('frontend/payment/' . $orderID));
            }
        } else {
            $this->data["subview"] = "frontend/checkout";
            $this->load->view('_frontend_layout', $this->data);
        }
    }

    public function payment($orderID)
    {
        $order          = $this->order_m->get_single_order(['orderID' => $orderID]);
        $payment_method = $order->payment_method;

        if ($payment_method == 5) {
            $this->session->set_flashdata('success', 'Your order created successfully.');
            redirect(base_url('myaccount/orderview/' . $orderID));
        } else if ($payment_method == 10) {
            $this->paypal($order);
        } else if ($payment_method == 15) {
            // $this->paypal($order);
        } else if ($payment_method == 20) {
            $this->stripe($order);
        }
    }

    // Paypal payment
    private function paypal($order)
    {
        //Set variables for paypal form
        $returnURL = base_url('frontend/paypalsuccess'); //payment success url
        $failURL   = base_url('frontend/paypalfail'); //payment fail url
        $notifyURL = base_url('frontend/paypalipn'); //ipn url

        //get particular product data
        $this->session->set_userdata('orderID', $order->orderID);
        $userID = $this->session->userdata('loginmemberID'); //current user id
        $logo   = app_image_link($this->data['generalsetting']->logo, 'uploads/images/', 'logo.jpg');

        $this->paypal_lib->add_field('return', $returnURL);
        $this->paypal_lib->add_field('fail_return', $failURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL);

        $this->paypal_lib->add_field('item_name', 'Order Item');
        $this->paypal_lib->add_field('amount', $order->total);
        $this->paypal_lib->add_field('item_number', $order->orderID);
        $this->paypal_lib->add_field('custom', $userID);
        $this->paypal_lib->image($logo);

        $this->paypal_lib->paypal_auto_form();
    }

    public function paypalsuccess()
    {
        $paypalInfo = $this->input->get();
        if (empty($paypalInfo)) {
            $this->session->set_flashdata('error', 'Your try to access invalid url.');
            redirect(base_url('myaccount/order'));
        }

        $orderID = $this->session->userdata('orderID');

        $miscArray['item_number']   = $orderID;
        $miscArray['txn_id']        = $paypalInfo["tx"];
        $miscArray['payment_amt']   = $paypalInfo["amt"];
        $miscArray['currency_code'] = $paypalInfo["cc"];
        $miscArray['status']        = $paypalInfo["st"];

        $order = $this->order_m->get_single_order(['orderID' => $orderID]);
        if (calculate($order)) {
            $updateArray['misc']           = json_encode($miscArray);
            $updateArray['paid_amount']    = $paypalInfo["amt"];
            $updateArray['payment_status'] = 10;
            if ($order->total == $updateArray['paid_amount']) {
                $updateArray['payment_status'] = 15;
            }
            $this->order_m->update_order($updateArray, $orderID);
        }
        $this->session->set_flashdata('success', 'Your order payment successfully paid.');
        redirect(base_url('myaccount/orderview/' . $order->orderID));
    }

    public function paypalfail()
    {
        $this->session->set_flashdata('error', 'Your order payment fail.');
        redirect(base_url('myaccount/order'));
    }

    public function paypalipn()
    {
        $this->session->set_flashdata('error', 'Your order payment ipn.');
        redirect(base_url('myaccount/order'));
    }

    // Paypal payment
    private function stripe($order)
    {
        try {
            require_once 'vendor/stripe/stripe-php/init.php';

            $stripeSecret = $this->config->item('stripe_secret');

            \Stripe\Stripe::setApiKey($stripeSecret);
            $charge = \Stripe\Charge::create([
                "amount"      => (int) $order->total,
                "currency"    => "usd",
                "source"      => $this->session->userdata('stripeToken'),
                "description" => $order->notes,
            ]);

            if (!empty($charge) && $charge['amount_refunded'] == 0 && empty($charge['failure_code']) && $charge['paid'] == 1 && $charge['captured'] == 1) {
                $paidAmount                    = $charge['amount'];
                $updateArray['paid_amount']    = $paidAmount;
                $updateArray['payment_status'] = 10;
                if ($order->total == $updateArray['paid_amount']) {
                    $updateArray['payment_status'] = 15;
                }
                $this->order_m->update_order($updateArray, $order->orderID);
            }
            $this->session->set_flashdata('success', 'Your order payment successfully paid.');
            redirect(base_url('myaccount/orderview/' . $order->orderID));
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(base_url('myaccount/orderview/' . $order->orderID));
        }

    }

    private function rules_checkout()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line('frontend_name'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'mobile',
                'label' => $this->lang->line('frontend_mobile'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line('frontend_email'),
                'rules' => 'trim|xss_clean|required|valid_email',
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line('frontend_address'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'notes',
                'label' => $this->lang->line('frontend_notes'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'payment_method',
                'label' => $this->lang->line('frontend_payment_method'),
                'rules' => 'trim|xss_clean|required',
            ),
        );
        return $rules;
    }

    //// Contacnt Page
    public function contact()
    {
        if ($_POST) {
            $rules = $this->rules_contact();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "frontend/contact";
                $this->load->view('_frontend_layout', $this->data);
            } else {
                $name      = $this->input->post('name');
                $fromemail = $this->input->post('email');
                $subject   = $this->input->post('subject');
                $message   = $this->input->post('message');
                $toemail   = $this->session->userdata('email');

                $sendmail = $this->applications->sendmail($toemail, $message, $subject, $name, $fromemail);
                if ($sendmail) {
                    $this->session->set_flashdata('success', $this->lang->line('frontend_email_send'));
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('frontend_email_fail'));
                }
                redirect(base_url('frontend/contact'));
            }
        } else {
            $this->data["subview"] = "frontend/contact";
            $this->load->view('_frontend_layout', $this->data);
        }
    }

    private function rules_contact()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line('frontend_name'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line('frontend_email'),
                'rules' => 'trim|xss_clean|required|valid_email',
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line('frontend_subject'),
                'rules' => 'trim|xss_clean|required',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line('frontend_message'),
                'rules' => 'trim|xss_clean|required',
            ),
        );
        return $rules;
    }

    // Newsletter
    public function subscribe()
    {
        if ($_POST) {
            $rules = $this->rules_subscribe();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $message = implode('<br/>', $this->form_validation->error_array());
                $this->session->set_flashdata('error', $message);
            } else {
                $newsletter = $this->newsletter_m->get_single_newsletter(['email' => $this->input->post('email')]);
                if (!calculate($newsletter)) {
                    $this->newsletter_m->insert_newsletter(['email' => $this->input->post('email')]);
                }
                $this->session->set_flashdata('success', 'Success');
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    private function rules_subscribe()
    {
        $rules = array(
            array(
                'field' => 'email',
                'label' => $this->lang->line('frontend_subsciption'),
                'rules' => 'trim|xss_clean|required|valid_email',
            ),
        );
        return $rules;
    }

}
