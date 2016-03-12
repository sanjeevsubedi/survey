<?php
header('Access-Control-Allow-Origin: *');
error_reporting(0);
define('ROOTPATH', dirname(__FILE__));
define('CERT_PATH', ROOTPATH . DIRECTORY_SEPARATOR . 'certs');

define('CERT_PATH', '/');
define('DS', '/');

$tokens = $_POST['tokens'];
$tokens = json_decode($tokens);
$message = $_POST['msg'];

// Put your private key's passphrase here:
	$passphrase = "Sanjeev@123";

	// Put your alert message here:
	//$message = "This is Anthony O'Connor from Bubblebridge. How are you guys doing in  the survey?";

$devices = $tokens;

if(!empty($devices)) {
//var_dump($devices);die;
	foreach($devices as $device) {
	
			$deviceToken = $device->token;	

			if($device->device_type == "ios"){
	
					// Put your device token here (without spaces):
					//$deviceToken = "79a07643046288de037b9b1eaaf6a90dab06f93cbac78be264f5ea29ce1e810c";
								
					$ctx = stream_context_create();
					stream_context_set_option($ctx, 'ssl', 'local_cert', CERT_PATH.DS.'PushFinalUni.pem');
					stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
					stream_context_set_option($ctx, 'ssl', 'cafile', CERT_PATH.DS.'entrust_2048_ca.cer');
			
					// Open a connection to the APNS server
					$fp = stream_socket_client(
					'ssl://gateway.push.apple.com:2195', $err,
					//'ssl://gateway.sandbox.push.apple.com:2195', $err,
					$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			
					if (!$fp)
					exit("Failed to connect: $err $errstr");
					//'ssl://gateway.sandbox.push.apple.com:2195', $err,
					//echo 'Connected to APNS' . PHP_EOL;
			
					// Create the payload body
					$body['aps'] = array(
					'alert' => $message,
					'sound' => 'default'
					);
			
					// Encode the payload as JSON
					$payload = json_encode($body);
			
					// Build the binary notification
					$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			
					// Send it to the server
					$result = fwrite($fp, $msg, strlen($msg));
			
					/*if (!$result)
					echo 'Message not delivered';
					else
					echo 'Message successfully delivered';
					*/
					// Close the connection to the server
					fclose($fp);

		} else {

				//for android

				$api_key = "AIzaSyC1Dj1Ve8sdx5hNiVxmDkXGgMwECB_nhHo";
				$msg = array("message" => $message,"msgcnt"=>"0");
				$registatoin_ids = array($deviceToken);


				// Set POST variables
				$url = 'https://android.googleapis.com/gcm/send';

				$fields = array(
				'registration_ids' => $registatoin_ids,
				'data' => $msg,
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
				//echo $result;




		}
	}


	$response = array(
		'success'=> true,
	);
	echo json_encode($response);




}