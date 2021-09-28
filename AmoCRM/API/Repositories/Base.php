<?php
namespace AmoCRM\API\Repositories;

class Base {
    /**
     * Base API repository class
     */

    /**
     * API endpoint (after /api/v4/)
     * @var string $endpoint
     */
    public $endpoint;

    /**
     * Parent API instance
     * @var API api
     */
    protected $api;

    function __construct($endpoint, $api) {
        $this->endpoint = $endpoint;
        $this->api = $api;
    }
}