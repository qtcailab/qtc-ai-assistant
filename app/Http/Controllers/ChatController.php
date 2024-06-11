<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    public function index() {
        return view('aibot');
    }

    private function get_baiduqf_token() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://qianfan.baidubce.com/v2/app/conversation",
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                'app_id' => env('BAIDU_QF_APPID')
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-Appbuilder-Authorization: Bearer '. env('BAIDU_QF_KEY'),
            ),

        ));
        $response = curl_exec($curl);
        curl_close($curl);

        // Decode the JSON response
        $responseArray = json_decode($response, true);

        // Print the response
        return($responseArray);
    }
    public function __invoke(Request $request): string
    {
//        dd($this->get_baiduqf_token()['conversation_id']);
        try {
            $conv_id = $this->get_baiduqf_token()['conversation_id'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://qianfan.baidubce.com/v2/app/conversation/runs",
                CURLOPT_TIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER  => false,
                CURLOPT_SSL_VERIFYHOST  => false,
                CURLOPT_CUSTOMREQUEST => 'POST',
                
                CURLOPT_POSTFIELDS => json_encode(array(
                    'app_id' => env('BAIDU_QF_APPID'),
                    'query' => $request->input('content'),
                    'stream' => true,
                    "conversation_id" => $conv_id
                )),
        
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'X-Appbuilder-Authorization: Bearer '. env('BAIDU_QF_KEY'),
                ),

            ));
            $response = curl_exec($curl);
            curl_close($curl);

            // Split the response by the "data:" delimiter
            $responseParts = explode("data:", $response);

            // Remove empty elements and trim whitespace
            $responseParts = array_filter(array_map('trim', $responseParts));

            // Decode each JSON string
            $res="";
            foreach ($responseParts as $responsePart) {
                $responseData = json_decode($responsePart, true);
                
                // Print out the decoded data
                $res = $res . $responseData['answer'];
            }
            return($res);
        } catch (\Exception $e) {
            return $e;
        }
    }
}