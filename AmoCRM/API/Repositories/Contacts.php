<?php
namespace AmoCRM\API\Repositories;

use AmoCRM\API\Repositories\Base;

class Contacts extends Base {
    /**
     * Contacts repository class.
     * Implements interaction with the "/contacts" API endpoint
     */

    /**
     * Class constructor
     * 
     * @param API API instance to make requests with
     */
    function __construct($api) {
        parent::__construct("contacts", $api);
    }

    /**
     * Filter contacts list by phone and email fields
     * 
     * @param array $contacts Contacts array
     * @param string $phone Phone number
     * @param string $mail Email address
     * 
     * @return array Filtered array
     */
    private function filter_contacts($contacts, $phone = null, $email = null) {
        if($phone === null && $email === null) {
            return $contacts;
        }

        $out = [];
    
        foreach($contacts as $contact) {
            $cfv = $contact["custom_fields_values"];
            if($email != null && !$this->verify_custom_field($cfv, "EMAIL", $email)) {
                continue;
            }
            if($phone != null && !$this->verify_custom_field($cfv, "PHONE", $phone)) {
                continue;
            }
            array_push($out, $contact);
        }

        return $out;
    }

    /**
     * Verify if "custom_fields_values" field value is $value
     * 
     * @param array $customFieldsValues "custom_fields_values" array
     * @param string $fieldCode Custom field code
     * @param string $value Value to match with
     * 
     * @return bool Result if field value matches with $value
     */
    private function verify_custom_field($customFieldsValues, $fieldCode, $value) {
        foreach($customFieldsValues as $field) {
            $fieldValue = $field["values"][0]["value"];
            
            if($field["field_code"] == $fieldCode) {
                return $fieldValue == $value;
            }
        } 
        return false;
    }

    /**
     * Get all contacts from the AmoCRM
     *
     * @return array Array of contacts
     */ 
    function getAll($phone = null, $email = null) {
        $response = $this->api->get($this->endpoint);
        if($response === null) {
            return [];
        }
        return $this->filter_contacts($response['_embedded']['contacts'], $phone, $email);
    }

    /**
     * Update contact info
     *
     * @param int $contactId AmoCRM contact Id
     * @param array $data Data array to update
     * 
     * @return array Contact change info
     */ 
    function update($contactId, $data) {
        return $this->api->patch(
            $this->endpoint . "/" . $contactId,
            $data
        );
    }

    /**
     * Add new contact
     * 
     * @param array $data Contact data
     * 
     * @return array Added contact
     */
    function add($data) {
        $response = $this->api->post($this->endpoint, [$data]);
        if($response === null) {
            return [];
        }
        return $response['_embedded']['contacts'][0];
    }

    /**
     * Add new contacts
     * 
     * @param array $data Contacts data
     * 
     * @return array Added contacts list
     */
    function addMany($data) {
        $response = $this->api->post($this->endpoint, $data);
        if($response === null) {
            return [];
        }
        return $response['_embedded']['contacts'];
    }
}