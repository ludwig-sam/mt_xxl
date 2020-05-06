<?php namespace Tests\Api\HelperLib;

class Curl{

    static public function post($url, $data){
        $curl = curl_init();
        $options = array(
            CURLOPT_URL     => $url,
            CURLOPT_POST    => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query($data)
        );
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


}

