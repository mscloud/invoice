<?php

class Controller_Invoice extends Controller_Base {

    public $template = 'template_invoice';

    public function action_index() {
        Response::redirect('/invoice/single');
    }

    public function action_single() {
        $data['panels'] = Model_Panel::find('all');
        $data["subnav"] = array('index' => 'active');
        $this->template->title = 'Invoice | Single';
        $this->template->content = View::forge('invoice/single', $data);
    }

    private function submit_customer_details($data, $type) {
        $customer = Model_Customer::forge(array(
                    'first_name' => $data['f_name'],
                    'last_name' => $data['l_name'],
                    'address_line_1' => $data['addr_1'],
                    'address_line_2' => $data['addr_2'],
                    'address_line_3' => $data['addr_3'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'pincode' => $data['pin'],
                    'phone' => $data['tele'],
                    'email' => $data['email'],
                    'type' => $type,
        ));
        $val = $customer->save();

        print_r($customer);

        if ($val == 1) {
            Response::redirect('/invoice/content');
        } else {
            Session::set_flash('error', 'Error in Form');
            return Response::forge(View::forge('invoice/single'));
        }
    }

    public function action_submit_single() {
        $this->submit_customer_details(Input::post);
        
    }

    public function action_monthly() {
        $data['monthly_customers'] = Model_Monthlycustomer::find('all', array(
                    'related' => array('customer'),
//            'where' => array('t1.type' => 'monthly')
        ));
        $this->template->title = 'Invoice | Monthly';
        $this->template->content = View::forge('invoice/monthly', $data);
    }

    public function action_submit_monthly() {
//        $this->submit_customer_details($_POST, 'monthly');
        print_r($_POST);

    }
    public function action_monthly_new() {
        $data["subnav"] = array('index' => 'active');
        $this->template->title = 'Invoice | Monthly';
        $this->template->content = View::forge('invoice/monthly_new', $data);
    }

    public function action_content() {
        $data['panels'] = Model_Panel::find('all');
        $this->template->title = 'Invoice | Main Content';
        $this->template->content = View::forge('invoice/invoice', $data);
    }

    public function action_payment() {
        $data['panels'] = Model_Panel::find('all');
        $this->template->title = 'Invoice | Payment';
        $this->template->content = View::forge('invoice/payment', $data);
    }

}