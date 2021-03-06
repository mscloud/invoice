<?php

/**
 * Description of invoicebase
 *
 * @author anuj
 */
class Controller_Invoicebase extends Controller_Base {

    protected function find_code($str) {
        $ct = 0;
        $code = 0;
        $states = Model_State::find('all');
        foreach ($states as $state):
            if ($state->name == $str) {
                $code = $state->code;
                break;
            }
        endforeach;
        echo 'State Code form Db:' . $code;
        $monthly_customers = Model_Monthlycustomer::find('all', array(
                    'related' => array('customer'),
        ));
        $org_codes[] = NULL;
        foreach ($monthly_customers as $monthly_customer):
            $org_codes[] = $monthly_customer->org_code;
        endforeach;
        if ($org_codes == NULL) {
            return $code . '001';
        } else {
            print_r($org_codes);

            foreach ($org_codes as $org_code):
                $string = $org_code[0] . $org_code[1];
                if ($string == $code) {
                    $ct++;
                }
            endforeach;
        }

        $ct++;
        echo $code;
        $org_code = 0;
        $org_code = $code;
/*        if ($ct < 9)
            return $org_code . '00' . $ct;
        if ($ct < 99)
            return $org_code . '0' . $ct;
        else
            return $org_code . '0' . $ct;*/
        return $org_code . ($ct < 9 ? '00' : '0') . $ct;
    }

    protected function submit_customer_details($data, $customer) {
        $customer->title = $data['title'];
        $customer->first_name = strtoupper($data['f_name']);
        $customer->last_name = strtoupper($data['l_name']);
        $customer->address_line_1 = $data['addr_1'];
        $customer->address_line_2 = $data['addr_2'];
        $customer->address_line_3 = $data['addr_3'];
        $customer->city = $data['city'];
        $customer->state = $data['state'];
        $customer->country = $data['country'];
        $customer->pincode = $data['pincode'];
        $customer->phone = $data['tele'];
        $customer->email = $data['email'];

        return $customer;
    }

    protected function submit_single_details($data) {
        $customer = new Model_Customer();
        $customer->type = 'single';
        $customer = $this->submit_customer_details($data, $customer);
        $customer->save();
        return $customer;
    }

    protected function submit_monthly_details($data,$inovice_no) {
        $customer_id = $data['customer_id'];
        $customer = Model_Customer::find($customer_id);
        print_r($customer);
        $customer = $this->submit_customer_details($data, $customer);
        $customer->monthlycustomer = Model_Monthlycustomer::find('first', array(
                    'where' => array('customer_id' => $customer_id)
        ));
//        print_r($monthly_customer);
        $customer->monthlycustomer->org_name = $data['org_name'];
        $customer->monthlycustomer->org_print_name = $data['org_print_name'];
        $customer->monthlycustomer->outstanding = $data['amount'] - $data['amount_paid'] + $customer->monthlycustomer->outstanding;
        $customer->save();
        $invoice = $this->submit_invoice_details($data, $customer_id,$inovice_no);
        $this->submit_panel_details($data, $invoice->id);
        return $invoice->id;
    }

    protected function submit_panel_pricing($data, $monthly_customer_id) {
        $i = 0;
        foreach ($data['panel'] as $row):
            for ($j = 0; $j < sizeof($row); $j++) {
                $panel_price = new Model_local_Panel_Price();
                $panel_price->monthly_customer_id = $monthly_customer_id;
                $panel_price->vol_low = Input::post('vol_low.' . $i);
                $panel_price->vol_high = Input::post('vol_high.' . $i);
                $panel_price->price = $row[$j];
                $panel_price->panel_id = $j + 1;
                $panel_price->save();
            }
            $i++;
        endforeach;
    }


    protected function submit_invoice_details($data, $customer_id, $invoice_no) {
        $invoice = new Model_Invoice();
        $invoice->invoice_no = $invoice_no;
        $invoice->baby_of = $data['baby_of'];
        $invoice->fp_number = $data['fp_number'];
        $invoice->date_of_service = $data['date_of_service'];
        $invoice->comment = $data['comment'];
        $invoice->customer_id = $customer_id;
        $invoice->user_id = Session::get('user')->id;
        $invoice->amount = $data['amount'];
        $invoice->currency = $data['currency'];
        $invoice->payment_mode = $data['payment_mode'];
        $invoice->amount_paid = Input::post('amount_paid');
        if ($data['payment_mode'] == "Cheque") {
            $invoice->bank_name = Input::post('bank_name');
            $invoice->cheque_number = Input::post('cheque_number');
            $invoice->bank_branch = Input::post('bank_branch');
            $invoice->bank_city = Input::post('bank_city');
        }
        $invoice->save();
        return $invoice;
    }

    protected function submit_panel_details($data, $invoice_id) {
        $panels = $data['panel_name'];
        $quantity = $data['panel_qty'];
        $price = $data['panel_price'];
//        print_r($quantity);
        for ($i = 0; $i < sizeof($panels); $i++) {
            $invoice = Model_Invoices_Panel::forge(
                            array(
                                'invoice_id' => $invoice_id,
                                'panel_id' => $panels[$i],
                                'panel_quantity' => $quantity[$i],
                                'panel_price' => $price[$i]
            ));
            $invoice->save();
        }
    }

    protected function update_panel_details($data, $invoice_id) {
        $query = DB::delete('local_panel_prices');
        $query->where('monthly_customer_id', $monthly_customer_id);
        $query->execute();
        $this->submit_panel_details($data, $invoice_id);
    }

}

?>
