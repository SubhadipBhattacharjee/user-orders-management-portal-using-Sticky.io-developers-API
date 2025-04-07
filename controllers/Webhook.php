<?php

class cURL{

    private $un_sticky;
    private $pw_sticky;

    function __construct(){
        //------Sticky Credentials------//
        $this->un_sticky = '';
        $this->pw_sticky = '';
    }

    public function cURL_POST($url,$data,$method="POST"){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json'
            ),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,  // Enable Basic Authentication
            CURLOPT_USERPWD => $this->un_sticky . ":" . $this->pw_sticky, // Set credentials
        
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        
        // Check for errors
        if (curl_errno($curl)) {
            return  curl_error($curl);
        } else {
            return  $response;
        }
    }

    public function cURL_GET($url){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,  // Enable Basic Authentication
            CURLOPT_USERPWD => $this->un_sticky . ":" . $this->pw_sticky, // Set credentials
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // Check for errors
        if (curl_errno($curl)) {
            return  curl_error($curl);
        } else {
            return  $response;
        }
    }


}

?>