<?php 
require_once __DIR__.'/Account.php';

class Subscription extends Account{

    function __construct(){
        parent::__construct();
    }

    //--------Updating Recurring Date---------------//
    public function update_recurring($order_id,$rebill_date){
        
        $orderId = [$order_id] ; 
        $date = DateTime::createFromFormat('m/d/Y', $rebill_date)->format('Y-m-d');

        $order_resp = $this->member->order_view($orderId);
        $order_data = json_decode($order_resp,true);

        //-----------billing_model_id == 2 for one time purchase-------//
        if($order_data['response_code']== "100" ){

            if($order_data["products"][0]['billing_model']['id'] != "2"){

                $subscription_id = $order_data["products"][0]['subscription_id'];
                $subs_resp = $this->update_rebill_date($subscription_id,$date);
                $subs_data = json_decode($subs_resp,true);

                if($subs_data['status']=="SUCCESS"){
                    return ["message" => "Recurring date updated.."];
                }else{
                    return ["error" => $subs_data['data']['recur_at'][0] ];
                }
            }else{
                 return [ "error" => "Cannot update,it is one time purchase product"];
            }
        }else{
            return ["error" => $order_data['response_message'] ] ;
        }   
    }

    //----------Update rebill-date API-----------//
    public function update_rebill_date($subscription_id,$date){

        $method = "PUT";
        $url = "https://highline.sticky.io/api/v2/subscriptions/".$subscription_id."/recur_at";
        $data = [
            'recur_at' => $date
        ];
        return $this->webhook->cURL_POST($url,$data,$method);
    }

    //========Billing Models & Frequency Update(START)===========//
     
    //----------Fetching all Billing Models------------//
    public function all_billing_models($subscription_id){

        $resp = $this->available_billing_model_API($subscription_id);
        $decode_resp = json_decode($resp,true);

        $data = [];
        foreach($decode_resp['data'] as $resp){
            if($resp['type'] != null){
                $data[] = [
                    "id" => $resp['id'] ,
                    "period" => $resp['description']
                ];
            }
        }
        return $data ;
    }

    //-------------Available Billing Models API----------------//
    public function available_billing_model_API($subscription_id){
        $url = "https://highline.sticky.io/api/v2/subscriptions/".$subscription_id."/billing_models";
        return $this->webhook->cURL_GET($url);
    }


    //-------------Updating Specific Order's billing Frequncy---------//
    public function update_billing_model($billing_model_id,$subscription_id){

        if(!empty($billing_model_id)){

            $resp = $this->update_billing_model_API($billing_model_id,$subscription_id);
            $decode_resp = json_decode($resp,true);

            if($decode_resp['status']== 'SUCCESS'){
                return ["message" => "Billing Frequency Updated...."];
            }else{
                $error = $decode_resp['data']['billing_model_id'][0];
                if(isset($error)){
                    return ["error" => $error ];
                }else{
                    return ["error" => $decode_resp['message'] ];
                } 
            }

        }else{
            return ["error" => "Please select one Billing Frequency..." ];
        }

    }

    //----------------Billing Frequency Update API---------------------//
    public function update_billing_model_API($billing_model_id,$subscription_id){

        $method = "PUT";
        $url = "https://highline.sticky.io/api/v2/subscriptions/".$subscription_id."/billing_model";
        $data = [
            "billing_model_id" => $billing_model_id
        ];
        return $this->webhook->cURL_POST($url,$data,$method);
    }

    //======Update-Order(Delivery/Billing address,Pyament Info)(START)======//

    //-------Update Specific order's Shipping Address----------//
    public function update_delivery_address($order_id){

        $data = [
            //"shipping_first_name" => $_POST['sh_fname'],
            //"shipping_last_name" => $_POST['sh_lname'],
            "shipping_address1" => $_POST['sh_add1'],
            "shipping_address2" => $_POST['sh_add2'],
            "shipping_country" => $_POST['sh_country'],
            "shipping_state"=> $_POST['sh_state'],
            "shipping_city"=> $_POST['sh_city'],
            "shipping_zip" => $_POST['sh_zip'],
            "phone" => $_POST['phone'],
        ];

        $formattedData = ["order_id" => []];
        $formattedData["order_id"][$order_id] = $data;

        return $this->member->order_details_update($formattedData) ;
    }


    //-------Update Specific order's Billing Address---------//
    public function update_billing_address($order_id){

        $data = [
            //"billing_first_name" => $_POST['b_fname'],
            //"billing_last_name" => $_POST['b_lname'],
            "billing_address1" => $_POST['b_add1'],
            "billing_address2" => $_POST['b_add2'],
            "billing_country" => $_POST['b_country'],
            "billing_state" => $_POST['b_state'],
            "billing_city" => $_POST['b_city'],
            "billing_zip" => $_POST['b_zip']
        ];

        $formattedData = ["order_id" => []];
        $formattedData["order_id"][$order_id] = $data;

        return $this->member->order_details_update($formattedData) ;

    }

    //-------Update Specific order's Payment Info----------//
    public function update_payment_info($order_id){

        $exp = $_POST['cc_exp']; // format 09/2038
        if(!empty($exp)){ 
            $parts = explode("/", $exp); 
            $output = $parts[0] . substr($parts[1], -2); 
        }
        $data = [
            "cc_number" => $_POST['cc_number'],
            "cc_expiration_date" => $output,
            "cc_payment_type" => $_POST['cc_type'],
        ];
        if(!empty($_POST['cc_cvv'])){
            $data['cc_cvv'] = $_POST['cc_cvv'];
        }

        $formattedData = ["order_id" => []];
        $formattedData["order_id"][$order_id] = $data;

        return $this->member->order_details_update($formattedData) ;
    }

    //------------Pause a Subscription-----------//
    public function stop_subscription($subscription_id){

        $subs_resp = $this->cancel_subscription_API($subscription_id);
        $decode_resp = json_decode($subs_resp,true);

        if($decode_resp['status']=='SUCCESS'){
           return [ "message" => "Subscription successfully cancelled..." ];
        }else{
           return ["error" => $decode_resp['message'] ];
        }
    }

    public function cancel_subscription_API($subs_id){

        $data = '';
        $url = "https://highline.sticky.io/api/v2/subscriptions/".$subs_id."/stop?cancellation_id=&cancellation_reason=";
        return $this->webhook->cURL_POST($url,$data);
    }

    //-----Reset/Start a stopped Subscription-----//
    public function reset_subscription(){

    }


}

?>