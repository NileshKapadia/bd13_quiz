<?php
function isValidPhoneNumber($phone_number, $customer_id, $api_key) {

	$api_url = "https://rest-ww.telesign.com/v1/phoneid/$phone_number";
	$headers = [
			"Authorization: Basic " . base64_encode("$customer_id:$api_key"),
			"Content-Type: application/x-www-form-urlencoded"
		];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Made change from 1 to true as it is standard coding practice to use true and false instead of 0 and 1
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, true); // added to post fieds as it requires as per telesign curl request 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");

	$response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return false;
    }
    $data = json_decode($response, true);
    if (!isset($data['phone_type']['description'])) {  //updated phone type extraction as per response array 
        return false;
    }
    $phone_type = strtoupper($data['phone_type']['description']); //updated phone type extraction as per response array 
    $valid_types = ["FIXED_LINE", "MOBILE", "VALID"];

    return in_array($phone_type, $valid_types);



}

// Usage example
$customer_id = "1F53DA41-200F-40CA-B21A-992DF856355E"; //updated api credentials 
$api_key = "EtfZykIwPzRfy1bRdD5kCT2q3SmsQkve7foF3OdJgryPV2+rEsf193wwQ+oVCraxpF6+RouvLKtDtyBQSv3uTw=="; //updated api credentials 
$phone_number = "1234567890	";
$result = isValidPhoneNumber($phone_number, $customer_id, $api_key);
var_dump($result);
