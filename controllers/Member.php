<?php 
require_once __DIR__ . '/Webhook.php';

class Member{

    private $webhook;

    function __construct(){
        $this->webhook =  new cURL();
    }

    /* Getting All orders placed by "Member" on Sticky.io by hitting these APIs successively

      'member_view' ($email),
      'customer_view' ($customer_id)  
      'order_view' ($order_id =[])    
    */

    //----member creation through Postback URL from Sticky.io CRM----//
    public function member_create($email,$event_id,$cust_id){
        
        $url = "https://highline.sticky.io/api/v1/member_create";
        $data = [
            "customer_id" => $cust_id,
	        "email" => $email,
	        "event_id"=> $event_id
        ];
        return $this->webhook->cURL_POST($url,$data);
    }

    public function update_member_profile(){

        $method = "PUT";
        $url = "https://highline.sticky.io/api/v2/customers/".$_SESSION['user']['cust_id'];
        $data = [
            'first_name'=>$_POST['fname'],
            'last_name'=>$_POST['lname'],
            'phone'=>$_POST['phone']
        ];
        if($_SESSION['user']['email'] != $_POST['email']){
           $data['email'] = $_POST['email'];
        }
        return $this->webhook->cURL_POST($url,$data,$method);
    }

    public function member_view($email = NULL){
        $url = "https://highline.sticky.io/api/v1/member_view";
        $data = [
            "email" => $email
        ];
        return $this->webhook->cURL_POST($url,$data);
    }

    public function customer_view($cust_id){
        $url = "https://highline.sticky.io/api/v1/customer_view";
        $data = [
            "customer_id" => $cust_id
        ];
        return $this->webhook->cURL_POST($url,$data);   
    }

    public function order_view( $order_id = [] ){
        $url = "https://highline.sticky.io/api/v1/order_view";
        $data = [
            "order_id" => $order_id
        ];
        return $this->webhook->cURL_POST($url,$data);
    }

    //------order-details(billing/delivery/payment)-----//
    public function order_details_update($data){
        $url = "https://highline.sticky.io/api/v1/order_update";
        return $this->webhook->cURL_POST($url,$data);
    }

}
?>