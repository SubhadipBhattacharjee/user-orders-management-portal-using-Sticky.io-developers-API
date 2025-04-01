<?php 
require_once __DIR__ . '/controllers/Member.php';

    //-----member_creation through Postback URL from Sticky.io CRM-----//
    $member = new Member();

    //--------fetching all query-parameter values------------//
    $email =  isset($_REQUEST['email']) ? htmlspecialchars($_REQUEST['']) : Null ;
    $event_id =  $_REQUEST['event_id'] ?? Null ;
    $customer_id =  $_REQUEST['customer_id'] ?? Null ;

    //-----checking if member already exist-----//
    $member_check = $member->member_view($email);
    $decode_resp = json_decode($member_check,true);

    //-----creating member if not exist-----//
    if($decode_resp['response_code'] != "100"){
        $member_create = $member->member_create($email,$event_id,$customer_id);
    }else{
        exit("Member already exist!") ;
    }


?>