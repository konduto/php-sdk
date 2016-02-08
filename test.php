<?php

require_once "vendor/autoload.php";

use \Konduto\Core\HttpRequest as HttpRequest;

$orderId = "Pedido0000331";
$request = new HttpRequest("post", Konduto\Params::ENDPOINT . "/orders", true);
$request->setBasicAuthorization("T738D516F09CAB3A2C1EE");
$request->setBodyAsJson(array("id"=>"id0182312"));

print_r($request->getHeaders());
print_r($request->getUri());

$response = $request->send();
echo "\nBody responded: ";
print_r($response->getBody());

echo "\nHTTP status: ";
print_r($response->getHttpStatus());

echo "\n";

