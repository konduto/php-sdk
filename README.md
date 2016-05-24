## Intro

Welcome! This document will explain how to integrate with Konduto's anti-fraud service so you can begin to spot fraud on your e-commerce website.

Our service uses the visitor's behavior to analyze browsing patterns and detect fraud. You will need to add a **JavaScript** snippet to your website and tag your pages, so we can see your visitors, and call our **REST API** to send purchases, so we can analyze them.

This document refers to the **PHP SDK** used for our API.

Check it the available branches for older verions of this library.

## Requirements

* PHP 5.3.10+
* cURL extension

## Installation with Composer

```json
{
    "require": {
        "konduto/sdk": "v2.0.0"
    }
}
```

## Getting started

When a customer makes a purchase on your e-commerce you must send to Konduto the order information so it can be analyzed. We perform a real-time analysis and return you a **recommendation** of what to do next and a **score**, a numeric confidence level about that order.

While most of the parameters that can be sent are optional we recommend you send all you can, because every data point matters for the analysis. The **billing address** and **credit card information** are specially important, though we understand there are cases where you don't have that information.

### Use the Namespaces

Import the following Namespaces:

```php
use Konduto\Core\Konduto;
use Konduto\Models;
```

`Konduto` provides methods for using Konduto services, such analyzing an order, querying or updating an existent order:

```
// Sends an order for analysis
$analyzedOrder = Konduto::analyze($order);
```
```
// Queries a previously analyzed order
$order = Konduto::getOrder($orderId);
```
```
// Updates the status of a previously analyzed order
Konduto::updateOrderStatus($orderId, $status, $comments);
```

### Set your API key

Before using `Konduto` methods you need first to set your Konduto API key using the `setApiKey()` method. Check the official [Konduto docs](https://docs.konduto.com/) for how to obtain your API key:

```php
Konduto::setApiKey("T738D516F09CAB3A2C1EE");
```

## Send an order for analysis

Sending an order for analysis is as easy as calling the `analyze` method from Konduto core class for an `Order` object, as the snippet below shows. Use `$order->getRecommendention()` to see the recommendation for this order and `$order->getScore()` to know the score representing the order's risk that Konduto calculated for it.

```php
try {
    $order = Konduto::analyze($order);
    echo "\nKonduto recommends you to {$order->getRecommendation()} this order.\n";
}
catch (Exception $e) {
    echo "\nKonduto wasn't able to return a recommendation: {$e->getMessage()}";
}
```
Every call performed with `Konduto` can throw an exception in case something goes wrong. Check the exception's message to see what went wrong. An example of what can go wrong is that a mandatory field wasn't provided, or a field was provided in the wrong format:


### Building an Order

You can create an `Order` object (or any other model from Konduto SDK) in two ways: by providing an associative array with the allowed fields to the model's constructor or using the methods such as setters and getters.

Check the official [Konduto documentation](https://docs.konduto.com/) for reference to all fields accepted in the Konduto Orders API. Some fields are mandatory, like the order id and total amount for example.

#### Using associative array

```php
$order = new Models\Order(array(
    "id" => uniqid(),
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
)));
```

#### Using models methods

```php
$order = new Models\Order();
$order->setId(uniqid());
$order->setVisitor("4738d516f09cab3a2c1ee973bec88a5a367a59e4");
$order->setTotalAmount(100.10);
$order->setShippingAmount(20.00);
$order->setCurrency("USD");

$customer = new Models\Customer();
$customer->setName("Júlia da Silva");
$customer->setTaxId("12345678909");
$customer->setEmail("jsilva@exemplo.com.br");

$order->setCustomer($customer);
```

#### Using dates and DateTime

This library automatically converts dates to the required API format. If it is convenient for you, you can directly provide a `DateTime` object to the fields that require dates.

```
$now = new \DateTime();
$customer->setCreatedAt($now);
```

## Update an order status

After you decide what to do with the order you asked for analysis (e.g. approve, decline, fraud, cancel, not authorized) it is very important that you inform Konduto service about it. So the machine learning algorithm can learn better about your orders and improve itself. For this, you have to use the `Konduto::updateOrderStatus()` method.

```php
Konduto::updateOrderStatus("ORD1237163", "approved", "Comments about this order");
```
```
Konduto::updateOrderStatus($orderId, $status, $comments);
```
Parameter | Description
--- | ---
orderId | _(required)_ The id for the order
status | _(required)_ String of one of the possible order status, check the [available status](http://docs.konduto.com/en/#update-order-status).
comments | _(required)_ Reason or comments about the status update.

## Query an order

```php
$orderId = "ORD1237163";
$order = Konduto::getOrder($orderId);
```

## Reference Tables

Please [click here](http://docs.konduto.com/#n-tables) for the Currency and Category reference tables.

## Support

Feel free to contact our [support team](mailto:support@konduto.com) if you have any questions or suggestions!
