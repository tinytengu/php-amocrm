<?php
namespace AmoCRM\API\Repositories;

use AmoCRM\API\Repositories\Base;

class Leads extends Base {
    /**
     * Leads repository class.
     * Implements interaction with the "/leaders" API endpoint
     */

    /**
     * Class constructor
     * 
     * @param API API instance to make requests with
     */
    function __construct($api) {
        parent::__construct("leads", $api);
    }

    /**
     * Add new lead
     * 
     * @param array $data Lead data
     * @param int $statuId Lead status
     * 
     * @return array Added lead info
     */
    function add($contactId, $statusId = 42958351) {
        $response = $this->api->post($this->endpoint, [[
            "status_id" => $statusId,
            "_embedded" => [
                "contacts" => [[
                    "id" => $contactId
                ]]
            ]
        ]]);
        if($response === null) {
            return [];
        }
        return $response['_embedded']['leads'][0];
    }
}