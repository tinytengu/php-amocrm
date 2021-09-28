<?php
// Application configuration
require_once "config.php";

// Logging utilities
require_once "logging.php";

// AmoCRM library
require_once "AmoCRM/AmoCRM.php";

use AmoCRM\AmoCRM;

// Check for "code" parameter in GET request
if(!array_key_exists("code", $_GET)) {
    json_decode(["error" => "No authentication code provided"]);
    http_response_code(401);
}

$authCode = $_GET["code"];
$clientId = $_GET["client_id"];

// Create AmoCRM instance with predefined constants from the config.php file.
// CLIENT_ID is not actually required at this point so "client_id" from the GET request is being used.
$acrm = new AmoCRM(AMOCRM_SUBDOMAIN, REDIRECT_URI, $clientId, CLIENT_SECRET);

// Get access_token and refresh_token pair using authorization code from the GET request.
$tokens = $acrm->getTokens($authCode);

// Prepare output authentication.json output
$output = [
    "authentication_code" => $authCode,
    "client_id" => $clientId,
    "access_token" => $tokens["access_token"],
    "refresh_token" => $tokens["refresh_token"]
];

// Write it to the authentication.json file.
log_file("./authentication.json", json_encode($output, JSON_PRETTY_PRINT), null);
