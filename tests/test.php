<?php
require_once "konduto.php";
require_once "vendor/autoload.php";
// use konduto\core\api as KondutoAPI;
use konduto\models as KondutoModels;
use konduto\exceptions as KondutoExceptions;


$order = new KondutoModels\Order([
    "id" => "123141",
    "visitor" => "at78123681y23",
    "customer" => new KondutoModels\Customer('idsoeij', 123123)
]);

$order->id("Pedido000025");
$order->visitor("1234567890123456789012345678901234567890");
$order->ip("100.100.100.1");
$order->installments(1);
$order->total_amount(192.10);
$order->shipping_amount(14.00);
$order->currency('BRL');


// var_dump($billingAd);

// $order->billing_address($billingAd);

// var_dump($order);

// Konduto::setApiKey("T738D516F09CAB3A2C1EE");
Konduto::setApiKey("T01234567890123456789");
// Konduto::setVersion("v1");


// echo "\n\n----- received from url: \n";
$array = array(
        'id'           => 'Pedido01293029',
        'visitor'      => 'poi1234567890123456789012678901234567890',
        'total_amount' => '7494.88',
        'ip'           => "192.168.0.1"
);

// $order = Konduto::getOrder("Pedido00ax0");

// var_dump($order);

$cust = new KondutoModels\Customer([
    'id' => 'cus123456',
    'name' => 'Tyrion Lanister',
    'email' => 'imp@lannister.com.kl',
    'vip' => true
]);

$bad = new KondutoModels\Address();
$bad->address1("Via del campo");
$bad->city("Torino");
$bad->country("IT");

$cc = new KondutoModels\CreditCard();
$cc->sha1("1234567890123456789012345678901234567890");
$cc->status("approved");

$order = new KondutoModels\Order($array);
$order->customer($cust);
$order->billing_address($bad);
$order->add_payment($cc);
$order->add_item(new KondutoModels\Item('ItemPhod', '1234', '1224', 'vishable'));
// // // $custow = $order->customer();

$order->id("PedidoTESTEENGINE");
// // $order->set(["ip" => "123.257.123.2", "total_amounta" => 199.00]);

try {
    Konduto::analyze($order);
    var_dump(Konduto::getLastResponse());
}
catch (KondutoExceptions\KondutoException $e) {
    echo "\nKonduto Exception!, lets see errors: ";
    var_dump($order->get_errors());
    echo "\nThis exceptions says ---> ";
    echo $e->getMessage();
}


// Konduto::updateOrderStatus("Pedido9999", "declinead");

// echo "\n--- chegou ao fim.\n";