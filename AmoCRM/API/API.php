<?php
namespace AmoCRM\API;

use AmoCRM\API\Request;
use AmoCRM\API\Repositories\Contacts;
use AmoCRM\API\Repositories\Leads;

class API {
    /**
     * API class
     */

    /**
     * AmoCRM subdomain
     * @var string $subdomain
     */
    public $subdomain;

    /**
     * API version (number only)
     * @var string $version
     */
    public $version;

    /**
     * API access token
     * @var string $access_token
     */
    public $access_token;

    /**
     * API refresh token
     * @var string $refresh_token
     */
    public $refresh_token;

    /**
     * Contacts repository
     * @var Contacts $contacts
     */
    public $contacts;

    /**
     * Leads repository
     * @var Leads $leads
     */
    public $leads;

    /**
     * Request maker instance
     * @var Request $request;
     */
    private $request;

    /**
     * Class constructor
     * 
     * @param string $subdomain AmoCRM subdomain
     * @param string $access_token API access token
     * @param string $refresh_token API refresh token
     * @param string $version API version (number only)
     */
    function __construct($subdomain, $access_token, $refresh_token, $version="4") {
        $this->subdomain = $subdomain;
        $this->version = $version;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;

        // Repositories
        $this->contacts = new Contacts($this);
        $this->leads = new Leads($this);

        $this->request = new Request();
    }

    /**
     * Get API url schema
     * 
     * @param string $endpoint (Optional) endpoint to add to the URI
     * @return string URI
     */
    function getUriSchema($endpoint = null) {
        $uri = sprintf(
            "https://%s.amocrm.ru/api/v%s/",
            $this->subdomain,
            $this->version
        );
        if($endpoint !== null) {
            $uri .= $endpoint;
        }
        return $uri;
    }

    /**
     * Make GET request
     * @param string $endpoint API endpoint
     */
    function get($endpoint) {
        return $this->request->get(
            $this->getUriSchema($endpoint), [
                sprintf('Authorization: Bearer %s', $this->access_token)
            ]
        );
    }

    /**
     * Make POST request
     * @param string $endpoint API endpoint
     */
    function post($endpoint, $data) {
        return $this->request->post(
            $this->getUriSchema($endpoint), $data, [
                sprintf('Authorization: Bearer %s', $this->access_token)
            ]
        );
    }

    /**
     * Make PATCH request
     * @param string $endpoint API endpoint
     */
    function patch($endpoint, $data) {
        return $this->request->patch(
            $this->getUriSchema($endpoint), $data, [
                sprintf('Authorization: Bearer %s', $this->access_token)
            ]
        );
    }
}