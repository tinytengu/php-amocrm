<?php
// Application configuration
require_once "config.php";

// Logging utilities
require_once "logging.php";

// AmoCRM library
require_once "AmoCRM/AmoCRM.php";

use AmoCRM\AmoCRM;

// Validate GET parameters
$params = ["name", "email", "phone"];

foreach ($params as $key) {
    if(!array_key_exists($key, $_GET)) {
        http_response_code(400);
        die(sprintf("'%s' parameter is required.", $key));
    }
}

$name = $_GET["name"];
$phone = $_GET["phone"];
$email = $_GET["email"];

// Create AmoCRM libary instance
$acrm = new AmoCRM(AMOCRM_SUBDOMAIN, REDIRECT_URI, CLIENT_ID, CLIENT_SECRET);

// Get API class instance
$api = $acrm->getApi(ACCESS_TOKEN, REFRESH_TOKEN);

// Get all the contacts
log_file(
    LOGS_PATH,
    sprintf("Fetching contact (name=%s, phone=%s, email=%s)\n", $name, $phone, $email)
);

$contacts = $api->contacts->getAll($phone, $email);
$contact = null;

// If contact not exist
if(count($contacts) == 0) {
    // Add new
    $contact = $api->contacts->add([
        "name" => $name,
        "custom_fields_values" => [[
            "field_id" => 270589,
            "field_name" => "Телефон",
            "values" => [[
                "value" => $phone,
                "enum_code" => "WORK"
            ]]
        ], [
            "field_id" => 270591,
            "field_name" => "Email",
            "values" => [[
                "value" => $email,
                "enum_code" => "WORK"
            ]]
        ]]
    ]);
    log_file(
        LOGS_PATH,
        sprintf("Contact not found. Created new contact with id %d\n", $contact['id'])
    );
}
else {
    // Update existing data
    $contact = $contacts[0];
    $id = $contact["id"];

    $api->contacts->update($id, [
        "name" => $name,
        "custom_fields_values" => [[
            "field_id" => 270589,
            "field_name" => "Телефон",
            "values" => [[
                "value" => $phone,
                "enum_code" => "WORK"
            ]]
        ], [
            "field_id" => 270591,
            "field_name" => "Email",
            "values" => [[
                "value" => $email,
                "enum_code" => "WORK"
            ]]
        ]]
    ]);

    log_file(
        LOGS_PATH,
        sprintf("Contact found (id %d). Properties updated\n", $id)
    );
}

$lead = $api->leads->add($contact["id"]);
log_file(
    LOGS_PATH,
    sprintf("Created new lead with id %d\n", $lead['id'])
);

echo("Ok");
