<?php
require_once "Konduto.php";
require_once "vendor/autoload.php";
/*use Konduto\core\api as KondutoAPI;
use Konduto\models\Order as KondutoOrder;
use Konduto\models\Customer as KondutoCustomer;
use Konduto\exceptions;


$order = new KondutoOrder([
    "id" => "1231411",
    "visitor" => "at78123681y23",
    "customer" => new Konduto\Models\Customer(['idsoeij', 123123, 'email' => 'idsoeij@jieosdi.com'])
]);

$order->id("Pedido000025ba");
$order->visitor("1234567890123456789012345678901234567890");
$order->ip("100.100.100.255");
$order->installments(1);
$order->total_amount(192.10);
$order->shipping_amount(14.00);
$order->currency('BRL');


// var_dump($billingAd);

// $order->billing_address($billingAd);

// var_dump($order);

// Konduto::setApiKey("T738D516F09CAB3A2C1EE");
Konduto::setApiKey("T01234567890123456789");
Konduto::setVersion("v1");


// echo "\n\n----- received from url: \n";
// $array = array(
//         'id'           => 'Pedido01293029',
//         'visitor'      => 'poi1234567890123456789012678901234567890',
//         'total_amount' => '7494.88',
//         'ip'           => "192.168.0.1"
// );

// $order = Konduto::getOrder("Pedido00ax0");

// var_dump($order);

// $cust = new KondutoModels\Customer([
//     'id' => 'cus123456',
//     'name' => 'Tyrion Lanister',
//     'email' => 'imp@lannister.com.kl',
//     'vip' => true
// ]);

// $bad = new KondutoModels\Address();
// $bad->address1("Via del campo");
// $bad->city("Torino");
// $bad->country("IT");

// $cc = new KondutoModels\CreditCard();
// $cc->sha1("1234567890123456789012345678901234567890");
// $cc->status("approved");

// $order = new KondutoModels\Order($array);
// $order->customer($cust);
// $order->billing_address($bad);
// $order->add_payment($cc);
// $order->add_item(new KondutoModels\Item('ItemPhod', '1234', '1224', 'vishable'));
// // // $custow = $order->customer();

// $order->id("PedidoTESTEENGINE");
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

echo "\n--- chegou ao fim.\n";
*/
// In the beginning of the program, rename classes and namespaces for avoiding conflicts and decrease verbosity of the code
use Konduto\core\Konduto as Konduto;
use Konduto\models\Order as KondutoOrder;
use Konduto\models\Customer as KondutoCustomer;

// Set you API key
Konduto::setApiKey("T01234567890123456789");

// Instantiate a KondutoOrder object and populate it with information about the order to be analyzed
$order = new KondutoOrder();
$order->id("Order00002");
$order->total_amount(125.30);
$order->currency("USD");

// Instantiate a KondutoCustomer object and populate it with customer data
$customer = new KondutoCustomer();
$customer->id("Customer00001");
$customer->name("Jon Snow");
$customer->email("snow@starks.co.wf");

// Include this customer to the previously created order
$order->customer($customer);

// Analyze order using Konduto
try {
    Konduto::analyze($order);
    
    // Check the recommendation that Konduto gave about this order
    echo "\n Konduto recommends us to '" . $order->recommendation() . "' '" . $order->id() . "'";
}
catch (Exception $e) {
    echo "\n Konduto wasn't able to return a recommendation: " . $e->getMessage();
}
