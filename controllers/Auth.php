<?php 
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/Webhook.php';

class Auth{

    private $webhook;

    function __construct(){
        $this->webhook = new cURL();
    }

    public function login($email,$password){
        
        $errors = [];
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Invalid email format.";
        }
        if (empty($password)){
            $errors[] = "Password is required.";
        }

        $url = "https://highline.sticky.io/api/v1/member_login";
        $data = [
            "email" => $email,
            "member_password" => $password
        ];

        if(empty($errors)){

            $login_details = $this->webhook->cURl_POST($url,$data);
            $login_data = json_decode($login_details,true);
            $login_resp = $login_data['response_code'];

            if(isset($login_resp) && $login_resp == "100"){

                $_SESSION['user'] = [
                    "cust_id" => $login_data['data']['customer_id'],
                    "email" => $login_data['data']['email'],
                    "token" => $login_data['data']['member_token'],
                ];
                header("location:account.php");  
                exit();
            }else{
                if(isset($login_data['response_message'])){
                    $errors[] = $login_data['response_message'];
                }else{
                    $errors[] = "Something went wrong.Please try again later";
                }
                return $errors ;
            }
        }else{
            return $errors ;
        }
    }

    public function isLoggedIn(){
        return isset($_SESSION['user']);
    }

    public function logout(){
        $url = 'https://highline.sticky.io/api/v1/member_logout';
        $token = $_SESSION['user']['token'];
        $data = [
            "token" => $token
        ];

        $logout_details = $this->webhook->cURl_POST($url,$data);
        $logout_data = json_encode($logout_details,true);

        $logout_resp = $logout_data['response_code'];
        $resp_msg = $logout['response_message'];

        if((isset($logout_resp) && $logout_resp = "100") || (isset($resp_msg) && $resp_msg != "100")){ 
            
            session_unset(); // Unset all session variables
            session_destroy(); // Destroy the session
            setcookie(session_name(), '', time() - 3600, '/'); // Delete session cookie

            header("Location: index.php");
            exit();
        }
    }


    //-----Update existing password----// 
    public function update_password($currentPass,$newPass,$confPass){

        $email = $_SESSION['user']['email'];
        if($newPass == $confPass){

            $resp = $this->update_password_API($email,$currentPass,$newPass);
            $decode_resp = json_decode($resp,true);

            if($decode_resp['response_code'] == "100"){
                return [
                    "message" => "Password successfully updated.."
                ];
            }else{
                return ["error" => "something went wrong,try again later.."];
            }

        }else{
            return ["error" => "New Password & Confirm Password mismatched...."];
        }
    }


    public function update_password_API($email,$currentPass,$newPass){

        $url = "https://highline.sticky.io/api/v1/member_update";
        $data = [
            "email"=>$email,
            "current_member_password"=>$currentPass,
            "new_member_password"=>$newPass
        ];
        return $this->webhook->cURL_POST($url,$data);
    }


    //----Send initial temp_password to Email-----//
    public function forgetPassword($email){
        $url = "https://highline.sticky.io/api/v1/member_forgot_password";
        $data = [
            "email" => $email,
            "event_id" => 1    
        ];
        return $this->webhook->cURL_POST($url,$data);
    }


    //---temp_password must be reset after forgetPassword----//
    public function reset_password($email,$tempPass,$newPass){

        $url = "https://highline.sticky.io/api/v1/member_reset_password";
        $data = [
            "email" => $email,
            "member_temp_password" => $tempPass, 
            "member_new_password" => $newPass
        ];
        return $this->webhook->cURL_POST($url,$data);
    }


}

?>