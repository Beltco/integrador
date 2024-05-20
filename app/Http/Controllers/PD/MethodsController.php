<?php

namespace App\Http\Controllers\PD;

use Exception;
use GuzzleHttp\Client;
use Pipedrive\Api\DealsApi;
use Pipedrive\Api\ProductsApi;
use Pipedrive\Configuration;
use Pipedrive\Model\UpdateDealProduct;
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

//  https://github.com/pipedrive/client-php/blob/master/docs/Api/ProductsApi.md#getProduct  
    public function getProducts($id=false,$start=200,$limit=200)
    {
        $apiInstance = new ProductsApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new Client(),
            $this->config()
        );

        try {
            if ($id)
                return($apiInstance->getProduct($id));
            else
                return($apiInstance->getProducts(null,null,null,null,false,$start,$limit));

        } catch (Exception $e) {
            echo 'Exception when calling ProductsApi->getProduct: ', $e->getMessage(), PHP_EOL;
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

//  https://github.com/pipedrive/client-php/blob/master/docs/Api/DealsApi.md#updateDealProduct
    public function updateDurationQuantity($deal_id,$id,$duration,$quantity,$enabled_flag)
    {
        $apiInstance = new DealsApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new Client(),
            $this->config()
        );
        
        $update_deal_product = new UpdateDealProduct();

        $update_deal_product->setQuantity($duration*$quantity);
        $update_deal_product->setDuration(1);
        $update_deal_product->setEnabledFlag($enabled_flag);
        
        try {
            $result = $apiInstance->updateDealProduct($deal_id, $id, $update_deal_product);
            return($result);
        } catch (Exception $e) {
            echo 'Exception when calling DealsApi->updateDealProduct: ', $e->getMessage(), PHP_EOL;
            exit();
        }
    }    

} //class

