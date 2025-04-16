<?php

// Get ConfigMap with group mapping located at ./mapping.json.
$json = file_get_contents( "mapping/mapping.json" );

// If ConfigMap is not provided, return a 500 error.
if ( $json === false ) {
    http_response_code(500);
    print "JSON Mapping is not found.";
    die();
}

// Decode the JSON mapping to an array.
$mappings = json_decode($json, true);

// Split returned group claims into an array.
$groups = explode(",", $_SERVER["HTTP_X_FORWARDED_GROUPS"]);

// Loop through each group in the group claim.
// Check if it exists in the JSON mapping, then
// redirect to the specified URL.
foreach ( $groups as $group ) {
    if ( array_key_exists( $group, $mappings ) ) {
        header( "Location: $mappings[$group]" );
        die();
    }
}

// If nothing matches, 
http_response_code(403);
print "Failed to match groups. Validate the group membership of the user and try again.<br>";
print "<a href='/oauth/sign_out'>Try again</a>";
die();
?>