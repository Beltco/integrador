<?php

namespace App\Http\Controllers\PD;

use Exception;
use Pipedrive\Configuration;
use App\Http\Controllers\Controller;

require_once('../vendor/autoload.php');

session_start();

class OauthController extends Controller
{
    public function index (){

        $config = (new Configuration());

        $config->setOauthRedirectUri(config('app.pd_callback'));
        $config->setClientSecret(config('app.pd_client_id_secret'));
        $config->setClientId(config('app.pd_client_id'));

        header('Location: ' . filter_var($config->getAuthorizationPageUrl(), FILTER_SANITIZE_URL));
        exit();
    }

    public function callback (){

        $config = (new Configuration());

        $config->setOauthRedirectUri(config('app.pd_callback'));

        $config->setClientSecret(config('app.pd_client_id_secret'));
        $config->setClientId(config('app.pd_client_id'));
        $config->setAuthorizationPageUrl('https://oauth.pipedrive.com/oauth/authorize?client_id='.config('app.pd_client_id').'&redirect_uri='.urlencode(config('app.pd_callback')));

        $config->setOAuthTokenUpdateCallback(function ($token) {
            $_SESSION['token'] = $token;
        });

        // if authorization code is absent, redirect to authorization page
        if (!isset($_GET['code'])) {
            header('Location: ' . filter_var($config->getAuthorizationPageUrl(), FILTER_SANITIZE_URL));
        } else {
            try {
                $config->authorize($_GET['code']);

                // resume user activity
                $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/PD/options';
                header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
                exit();
            } catch (Exception $ex) {
                print_r($ex);
            }
        }
    }
}
