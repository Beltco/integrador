<?php

namespace App\Http\Controllers\PD;

use Exception;
use GuzzleHttp\Client;
use Pipedrive\Api\DealsApi;
use Pipedrive\Configuration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;


require_once('../vendor/autoload.php');

session_start();

class MethodsController extends Controller
{

    private function config() {
        $access_token=(isset($_SESSION['token'])?$_SESSION['token']->access_token:false);

        if (!$access_token)
          die('<a href= "'.route('oauth').'">Autenticar</a>');

        $config=(new Configuration())->setAccessToken($access_token);

        return $config;
    }

// https://github.com/pipedrive/client-php/blob/master/docs/Api/DealsApi.md#getDealsSummary
    public function countDeals()
    {
        $apiInstance = new DealsApi(
            new Client(),
            $this->config()
        );

        try {
            return ($apiInstance->getDealsSummary()['data']['total_count']);
        } catch (Exception $e) {
            echo 'Exception when calling DealsApi->getDealsSummary: ', $e->getMessage(), PHP_EOL;
        }

    }



//  https://github.com/pipedrive/client-php/blob/master/docs/Api/DealsApi.md#getDeals
    public function getDeals ($id=false,$start=200,$limit=200) {

        $apiInstance = new DealsApi(
            new Client(),
            $this->config()
        );

        try {
            if ($id)
              return ($apiInstance->getDeal($id));
            else
              return ($apiInstance->getDeals(null,null,null,null,$start,$limit,'id ASC'));
        } catch (Exception $e) {
            echo 'Exception when calling DealsApi->getDeals: ', $e->getMessage(), PHP_EOL;
        }

    }

//  https://github.com/pipedrive/client-php/blob/master/docs/Api/DealsApi.md#getdealproducts
    public function getDealProducts($id){

        $apiInstance = new DealsApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new Client(),
            $this->config()
        );

        try{
            return($result = $apiInstance->getDealProducts($id));
        }catch (Exception $e) {
            echo 'Exception when calling DealsApi->getDealProducts: ', $e->getMessage(), PHP_EOL;
        }

    }

} //class

