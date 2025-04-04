<?php 
require_once __DIR__.'/../config/config.php';

require_once __DIR__ . '/Webhook.php';
require_once __DIR__ . '/Member.php';

class Account{

    protected  $webhook ;
    protected  $member ;

    public function __construct(){
        $this->webhook =  new cURL();
        $this->member = new Member();
    }

    //--getting customer details from customer Email--//
    public function customer_details($email = Null){

        $member_details = $this->member->member_view($email);   
        $member_data = json_decode($member_details,true);
        $member_resp = $member_data['response_code'];

        if(isset($member_resp) && $member_resp == "100"){

            $cust_id = $member_data['data']['customer_id'] ;

            $cust_details = $this->member->customer_view($cust_id);
            $cust_data = json_decode($cust_details,true) ;
            $cust_resp = $cust_data['response_code'] ;

            if(isset($cust_resp) && $cust_resp == "100"){
                $json_resp = json_encode($cust_data);
                return $json_resp;
            }else{
                $response =[
                    'error' => "Something went wrong with customer_view API"
                ];
                $json_resp = json_encode($response);
                return $json_resp;    
            }

        }else{
            $response = [
                'error' => "Something went wrong with customer_view API"
            ];
            $json_resp = json_encode($response);
            return $json_resp;
        }

    }

    //--------------All Unfiltered  Orders--------------//
    public function order_details($order_id,$order_count){
        if($order_count == '1'){
            $order_details = $this->member->order_view([$order_id]);
        }else{
            $order_details = $this->member->order_view($order_id);
        }
        $order_data = json_decode($order_details,true);
        $order_resp = $order_data['response_code'] ;
    
        if(isset($order_resp) && $order_resp == "100"){  
            $json_resp = json_encode($order_data);
            return $json_resp;
     
        }else{
            $response = [
                'error' => "Something went wrong with order_view API"
            ];
            $json_resp = json_encode($response);
            return $json_resp;
        }
    }


    //--In PHP, when using switch, string numbers ("2") are treated as integers (2)--//
    public function order_status(int $order_status):string {
        switch ($order_status) {
            case 2:
                return "Approved";
            case 6:
                return "Void/Refunded";
            case 7:
                return "Declined";
            case 8:
                return "Shipped";
            case 11:
                return "Pending";
            default:
                return "Unknown Status";
        }
    }


    public function filtered_subscribed_orders($order_data){

        $orderRecords = [];

        if(isset($order_data['total_orders'])){

            foreach($order_data['order_id'] as $orderId){

                $order_status = $this->order_status($order_data['data'][$orderId]['order_status']);

                $product_name = $order_data['data'][$orderId]['products'][0]['name'];
                $recurring_date = $order_data['data'][$orderId]['products'][0]['recurring_date'] ;
                $order_price = $order_data['data'][$orderId]['order_total'];
                $recurring_freq = $order_data['data'][$orderId]['products'][0]['billing_model']['description'];
                $subscription_id = $order_data['data'][$orderId]['products'][0]['subscription_id'];
                $prod_id = $order_data['data'][$orderId]['products'][0]['product_id'];
                $hold = $order_data['data'][$orderId]['on_hold']; // 1 for subscription stop

                $orderRecords[] = [
                    "order_id" => $orderId,
                    "prod_id" => $prod_id ,
                    "product_name" => $product_name,
                    "total_price" => $order_price ,
                    "is_recur" => $order_data['data'][$orderId]['products'][0]['is_recurring'],
                    "recurring_date" => date("F j, Y", strtotime($recurring_date)),
                    "recurring_freq" => $recurring_freq,
                    "hold" => $hold ,
                    "status" => $order_status,
                    "subscription_id" => $subscription_id
                ];

            }

        }else{

                $order_status = $this->order_status($order_data['order_status']);
                $orderRecords[] = [
                    "order_id" => $order_data['order_id'],
                    "prod_id" => $order_data['products'][0]['product_id'],
                    "product_name" => $order_data['products'][0]['name'],
                    "total_price" => $order_data['order_total'],
                    "is_recur" => $order_data['products'][0]['is_recurring'],
                    "recurring_date" => date("F j, Y", strtotime($order_data['products'][0]['recurring_date'])),
                    "recurring_freq" => $order_data['products'][0]['billing_model']['description'],
                    "hold" => $order_data['on_hold'] ,
                    "status" => $order_status,
                    "subscription_id" => $order_data['products'][0]['subscription_id']
                ];
        }
        return $orderRecords;
    }

    //-----Fetching all(Subscription & One time Purchase) filtered orders------//
    public function filtered_all_orders($order_data){

        $orderRecords = [];
        if(isset($order_data['total_orders'])){

            foreach($order_data['order_id'] as $orderId){

                $order_date = $order_data['data'][$orderId]['time_stamp'] ;   
                $order_price = $order_data['data'][$orderId]['order_total'];
                $recurring_date = $order_data['data'][$orderId]['recurring_date']; 
                $email = $order_data['data'][$orderId]['email_address'];

                $status = $order_data['data'][$orderId]['order_status'];
                $order_status = $this->order_status($status) ;

                $allOrders = $order_data['data'][$orderId]['products'];
                
                foreach($allOrders as $key=>$order){

                    $orderRecords[] = [
                        "key" => $key+1 ,
                        "order_id" => $orderId,
                        "prod_id" => $order['product_id'],
                        "subscription_id" => $order['subscription_id'],
                        "product_name" => $order['name'],
                        "recurring_date" => $order['recurring_date'] ,
                        "recurring_freq" => $order['billing_model']['description'],
                        "sub_total" => $order_data['data'][$orderId]['totals_breakdown']['subtotal'],
                        "tax" => $order_data['data'][$orderId]['totals_breakdown']['tax'],
                        "discount" => $order_data['data'][$orderId]['coupon_discount_amount'],
                        "quantity" => $order['product_qty'],
                        "price" => $order['price'],
                        "order_date" =>date("F j, Y", strtotime($order_date)),
                        "ship_amount" => $order_data['data'][$orderId]['shipping_amount'],
                        "total_price" =>$order_data['data'][$orderId]['totals_breakdown']['total'] ,
                        "email" => $order_data['data'][$orderId]['email_address'],
                        "phone" => $order_data['data'][$orderId]['customers_telephone'],
                        "add1" => $order_data['data'][$orderId]['shipping_street_address'],
                        "add2" => $order_data['data'][$orderId]['shipping_street_address2'],
                        "pin" => $order_data['data'][$orderId]['billing_postcode'],
                        "state" => $order_data['data'][$orderId]['billing_state'],
                        "country" => $order_data['data'][$orderId]['billing_country'],
                        "status" => $order_status
                    ];
                }

            }

        }else{

            $order_status = $this->order_status($order_data['order_status']);
            $allOrders = $order_data['products'] ;

            foreach($allOrders as $key=>$order){

                $orderRecords[] = [
                    "key" => $key+1 ,
                    "order_id" => $order_data['order_id'],
                    "recurring_date" => $order['recurring_date'],
                    "subscription_id" => $order['subscription_id'],
                    "recurring_freq" => $order['billing_model']['description'],
                    "prod_id" => $order['product_id'],
                    "sub_total" => $order_data['totals_breakdown']['subtotal'],
                    "tax" => $order_data['totals_breakdown']['tax'],
                    "discount" => $order_data['coupon_discount_amount'],
                    "product_name" => $order['name'],
                    "quantity" => $order_data['main_product_quantity'],
                    "price" => $order['price'],
                    "order_date" => date("F j, Y", strtotime($order['time_stamp'])),
                    //"order_date" =>date("F j, Y", strtotime($order_data['acquisition_date'])), 
                    "ship_amount" => $order_data['shipping_amount'],
                    "email" => $order_data['email_address'],
                    "phone" => $order_data['customers_telephone'],
                    "add1" => $order_data['shipping_street_address'],
                    "add2" => $order_data['shipping_street_address2'],
                    "pin" => $order_data['billing_postcode'],
                    "state" => $order_data['billing_state'],
                    "country" => $order_data['billing_country'],
                    "status" => $order_status,
                    "total_price" => $order_data['totals_breakdown']['total'] ,
                    "status" => $order_status       
                ];

            }

        }
        return $orderRecords;
    }


    public function update_billing_info($data){

        return $this->member->order_details_update($data) ; 
    }

}
?>