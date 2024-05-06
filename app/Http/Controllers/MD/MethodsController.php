<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MethodsController extends Controller
{
    private $token;
    private $apiUrl;
    private $headers;

    function __construct()
    {
        $this->token = config('app.md_token');
        $this->apiUrl = config('app.md_apiurl');
        $this->headers = ['Content-Type: application/json', 'Authorization: ' . $this->token];
    }

    public function apiCallMD($query)
    {
        $data = @file_get_contents($this->apiUrl, false, stream_context_create([
            'http' => [
             'method' => 'POST',
             'header' => $this->headers,
             'content' => json_encode(['query' => $query]),
            ]
           ]));
           $responseContent = json_decode($data, true);
           
           return json_encode($responseContent);
    }


}