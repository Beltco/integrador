<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MethodsController extends Controller
{
    private $token;
    private $apiUrl;
    private $headers;

    function __construct()
    {
        $this->token = config('app.bk_token');
        $this->apiUrl = config('app.bk_apiurl');
        $this->headers = ['Accept: application/json', 'auth_token: '.$this->token];
    }

    public function apiCallBK($endpoint,$params=[],$method=false)
    {
        $curl = curl_init($this->apiUrl."/$endpoint?".http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Para obtener la respuesta como una cadena
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);        
        curl_setopt($curl, CURLOPT_POST, $method); //false=GET true=POST

        $data=json_decode(curl_exec($curl),true);

        // Verificar si hubo algún error
        if (curl_errno($curl)) {
            echo 'Error en cURL: ' . curl_error($curl);
        } else {
            curl_close($curl);
            return $data;
        }
    }
}
