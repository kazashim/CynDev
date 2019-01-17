<?php
$headers = apache_request_headers();
//$headers = getallheaders(); // Try using this instead if apache_request_headers() does not work.

$header_api_key = "";
if (isset($headers["Bd-Api-Session"])) {
	$header_api_key = $headers["Bd-Api-Session"];
} else if (isset($headers["BD-API-SESSION"])) {
	$header_api_key = $headers["BD-API-SESSION"];
}
?>