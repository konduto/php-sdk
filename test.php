<?php

require_once "vendor/autoload.php";

use \Konduto\Core\Konduto;
use \Konduto\Models;

Konduto::setApiKey("T738D516F09CAB3A2C1EE");

$orderId = uniqid();

$order = new Models\Order(array(
    "id" => $orderId,
    "visitor" => "4738d516f09cab3a2c1ee973bec88a5a367a59e4",
    "total_amount" => 100.10,
    "shipping_amount" => 20.00,
    "tax_amount" => 3.45,
    "currency" => "USD",
    "installments" => 1,
    "ip" => "170.149.100.10",
    "purchased_at" => "2015-04-25T22:29:14Z",
    "customer" => array(
        "id" => "28372",
        "name" => "Júlia da Silva",
        "tax_id" => "12345678909",
        "dob" => "1970-12-25",
        "phone1" => "11-1234-5678",
        "phone2" => "21-2143-6578",
        "email" => "jsilva@exemplo.com.br",
        "created_at" => "2010-12-25",
        "new" => false,
        "vip" => false
    )
));

try {
    $order = Konduto::analyze($order);

    echo "\nRecommendation: {$order->getRecommendation()}\n";

    $order = Konduto::getOrder($orderId);
    var_dump($order->getProperties());

    $order = Konduto::updateOrderStatus($orderId, "fraud", "DAMN FRAUDSTER");
}
catch (Exception $e) {
    echo "\nException: {$e->getMessage()}\n";
}
