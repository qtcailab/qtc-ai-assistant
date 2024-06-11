<?php
class Sample {
    const API_KEY = "";
    const SECRET_KEY = "";

    public function run() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://qianfan.baidubce.com/v2/app/conversation",
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            
            CURLOPT_POSTFIELDS =>'{"app_id":"865694a0-cd4c-4eba-8bdc-a8211a5a97e0"}',
    
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-Appbuilder-Authorization: Bearer bce-v3/ALTAK-ziDv0mIAvQorrW0JBgOpr/e7916cec5c4b58de23c6e462b6b2f2bd0ba9b36b'
            ),

        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    
    /**
     * 使用 AK，SK 生成鉴权签名（Access Token）
     * @return string 鉴权签名信息（Access Token）
     */
    private function getAccessToken(){
        $curl = curl_init();
        $postData = array(
            'grant_type' => 'client_credentials',
            'client_id' => self::API_KEY,
            'client_secret' => self::SECRET_KEY
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://aip.baidubce.com/oauth/2.0/token',
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query($postData)
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $rtn = json_decode($response);
        return $rtn->access_token;
    }
}

$rtn = (new Sample())->run();
print_r($rtn);