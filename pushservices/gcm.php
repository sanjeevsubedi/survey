<?php
$api_key = "AIzaSyC1Dj1Ve8sdx5hNiVxmDkXGgMwECB_nhHo";
$message = array("message" => "Hi Sanjeev !!!","msgcnt"=>"0");
$registatoin_ids = array('APA91bHzHJHSTc33qUSzFZ7H3vpA8FemY4SkUj6wRLtYxuhlPgzpDSfMoW533pz6pYzTo9sH-tyPXgfxRi9pFHjqNRfps1qaLbS-AXoX0xiblqF8ffPs6oZlT43ebHLVs6K8-uUxeSlt');


// Set POST variables
$url = 'https://android.googleapis.com/gcm/send';

$fields = array(
    'registration_ids' => $registatoin_ids,
    'data' => $message,
);

$headers = array(
    'Authorization: key=' . $api_key,
    'Content-Type: application/json'
);
// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Disabling SSL Certificate support temporarly
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Curl failed: ' . curl_error($ch));
}

// Close connection
curl_close($ch);
echo $result;

?>