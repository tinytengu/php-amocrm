<?php
namespace AmoCRM;

require_once __DIR__ . "/../vendor/autoload.php";

use AmoCRM\API\Api;
use AmoCRM\API\Request;

class AmoCRM {
    /**
     * AmoCRM class
     */

    /**
     * AmoCRM subdomain
     * @var string $subdomain
     */
    public $subdomain;

    /**
     * Widget redirect URI
     * @var string $redirectUri
     */
    public $redirectUri;

    /**
     * Client ID
     * @var string $clientId
     */
    public $clientId;

    /**
     * Client secret key
     * @var string $clientSecret
     */
    public $clientSecret;

    /**
     * Class constructor
     * 
     * @param string $subdomain AmoCRM subdomain
     * @param string $redirectUri Widget redirect URI
     * @param string $clientId Client ID
     * @param string $clientSecret Client secret key
     */ 
    function __construct($subdomain, $redirectUri, $clientId, $clientSecret) {
        $this->subdomain = $subdomain;
        $this->redirectUri = $redirectUri;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Get AmoCRM OAuth2 URI schema
     * 
     * @param string $endpoint Endpoint to add
     * 
     * @return string URI string
     */
    private function getOauthUri($endpoint=null) {
        $uri = "https://" . $this->subdomain . ".amocrm.ru/oauth2/";

        if($endpoint !== null) {
            $uri .= $endpoint;
        }

        return $uri;
    }

    /**
     * Get API tokens using authorization code
     * 
     * @param string $authCode Authrization code
     * 
     * @return array Array with access_token and refresh_token keys
     */
    function getTokens($authCode) {
        $url = $this->getOauthUri("access_token");
        return (new Request())->post($url, [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $authCode,
            'redirect_uri' => $this->redirectUri
        ]);
    }

    /**
     * Get API tokens using refresh token
     * 
     * @param string $refreshToken Valid refresh token
     * 
     * @return array Array with access_token and refresh_token keys
     */
    function refreshTokens($refreshToken) {
        $url = $this->getOauthUri("access_token");
        return (new Request())->post($url, [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'redirect_uri' => $this->redirectUri
        ]);
    }

    /**
     * Get API instance
     * 
     * @param string $access_token Access token to use for requests
     * @param string $version API version (4 by default)
     * 
     * @return API API Instance
     */
    function getApi($access_token, $version="4") {
        return new Api($this->subdomain, $access_token, $version);
    }
}