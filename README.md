## Intro

Welcome! This document will explain how to integrate with Konduto's anti-fraud service so you can begin to spot fraud on your e-commerce website.

Our service uses the visitor's behavior to analyze browsing patterns and detect fraud. You will need to add a **JavaScript** snippet to your website and tag your pages, so we can see your visitors, and call our **REST API** to send purchases, so we can analyze them. 

This document refers to the **PHP SDK** used for our API.

## Requirements

* PHP 5.3.2+
* cURL extension

## Installation with Composer

```json
{
  "require": {
    "konduto/sdk": "dev-master"
  }
}
```

## Manuall installation

```php
require_once "konduto/sdk/konduto.php";
```

```
/
|- konduto/
|--|- sdk/
|--|--|- src/
|--|--|- tests/
|--|--|- konduto.php
```

## Getting started

When a customer makes a purchase you must send the order information to us so we can analyze it. We perform a real-time analysis and return you a **recommendation** of what to do next and a **score**, a numeric confidence level about that order. 

While many of the parameters we accept are optional we recommend you send all you can, because every data point matters for the analysis. The **billing address** and **credit card information** are specially important, though we understand there are cases where you don't have that information.

### Use the Namespaces

Import the following Namespaces to make it easier to reference the methods in the future:

```php
use Konduto\Core\Konduto as Konduto;
use Konduto\Models as KondutoModels;
```

### Set your API key

You will need an API key to authenticate the requests. Luckily for you the examples below have been populated with a working key, so you can just copy and paste to see how it works. 

The `setApiKey()` method returns `true` if the API key is valid. Otherwise it throws an Exception:

```php
Konduto::setApiKey("T738D516F09CAB3A2C1EE");
```

## Send an order for analysis

The request consists of a root object containing information related to the order as well as a `customer`, `billing` and `shipping` objects and the `payment` and `shopping_cart` arrays.

### Order information

```php
$order = new KondutoModels\Order(array(
  "id"                => uniqid(),
  "visitor"           => "4738d516f09cab3a2c1ee973bec88a5a367a59e4",
  "total_amount"      => 100.00,
  "shipping_amount"   => 20.00,
  "tax_amount"        => 3.45,
  "currency"          => "USD",
  "installments"      => 0,
  "ip"                => "170.149.100.10",
  "customer"          => $customer,
  "payment_array"     => array($creditCard),
  "billing_address"   => $billing,
  "shipping_address"  => $shipping,
  "shopping_cart"     => array($item1, $item2)
));
```


Parameter | Description 
--- | ---
id | _(required)_ Unique identifier for each order.
visitor | _(required)_ Visitor identifier obtained from our JavaScript snippet.
total_amount | _(required)_ Total order amount.
shipping_amount | _(optional)_ Shipping and handling amount.
tax_amount | _(optional)_ Taxes amount.
currency | _(optional)_ Currency code with 3 letters (ISO-4712).
installments | _(optional)_ Number of installments in the payment plan.
ip | _(optional)_ Customer's IPv4 address.
customer | _(required)_ Object containing the customer details.
payment | _(optional)_ Array containing the payment methods.
billing | _(optional)_ Object containing the billing information.
shipping | _(optional)_ Object containing the shipping information.
shopping_cart | _(optional)_ Array containing the items purchased.


### Customer information

```php
$customer = new KondutoModels\Customer(array(
  "id"      => "28372",
  "name"    => "Mary Jane",
  "tax_id"  => "6253407",
  "phone1"  => "212-555-1234",
  "phone2"  => "202-555-6789",
  "email"   => "mary.jane@example.com",
  "new"     => true,
  "vip"     => false
));
```


Parameter | Description 
--- | ---
id | _(required)_ **Unique** identifier for each customer. Can be anything you like (counter, id, e-mail address) as long as it's consistent in future orders.
name | _(required)_ Customer's full name.
email | _(required)_ Customer's e-mail address
tax_id | _(optional)_ Customer's tax id.
phone1 | _(optional)_ Customer's primary phone number
phone 2 | _(optional)_ Customer's secondary phone number
new | _(optional)_ Boolean indicating if the customer is using a newly created account for this purchase.
vip | _(optional)_ Boolean indicating if the customer is a VIP or frequent buyer.


### Payment information

```php
$creditCard = new KondutoModels\CreditCard(array(
  "sha1"            => "68bfb396f35af3876fc509665b3dc23a0930aab1",
  "bin"             => "490172",
  "last4"           => "0012",
  "expiration_date" => "072015",
  "status"          => "approved"
));
```


Parameter | Description 
--- | ---
status | _(required)_ The status of the transaction returned by the payment processor. Accepts `approved`, `declined` or `pending` if the payment wasn't been processed yet.
sha1 | _(optional)_ Hash (SHA1) of the customer's full credit card number.
bin | _(optional)_ First six digits of the customer's credit card. Used to identify the type of card being sent.
last4 | _(optional)_ Four last digits of the customer's credit card number.
expiration_date | _(optional)_ Card's expiration date under MMYYYY format.


### Billing address

```php
$billing = new KondutoModels\Address(array(
  "name"      => "Mary Jane",
  "address1"  => "123 Main St.",
  "address2"  => "Apartment 4",
  "city"      => "New York City",
  "state"     => "NY",
  "zip"       => "10460",
  "country"   => "US"
));
```


Parameter | Description 
--- | ---
name | _(optional)_ Cardholder's full name.
address1 | _(optional)_ Cardholder's billing address on file with the bank.
address2 | _(optional)_ Additional cardholder address information.
city | _(optional)_ Cardholder's city.
state | _(optional)_ Cardholder's state.
zip | _(optional)_ Cardholder's ZIP code.
country | _(optional)_ Cardholder's country code (ISO 3166-2)


### Shipping address

```php
$billing = new KondutoModels\Address(array(
  "name"      => "Mary Jane",
  "address1"  => "123 Main St.",
  "address2"  => "Apartment 4",
  "city"      => "New York City",
  "state"     => "NY",
  "zip"       => "10460",
  "country"   => "US"
));
```

Parameter | Description 
--- | ---
name | _(optional)_ Recipient's full name.
address1 | _(optional)_ Recipient's shipping address.
address2 | _(optional)_ Additional recipient address information.
city | _(optional)_ Recipient's city.
state | _(optional)_ Recipient's state.
zip | _(optional)_ Recipient's ZIP code.
country | _(optional)_ Recipient's country code (ISO 3166-2)


### Shopping cart

```php
$item1 = new KondutoModels\Item(array(
  "sku"           => "9919023",
  "product_code"  => "123456789999",
  "category"      => 201,
  "name"          => "Green T-Shirt",
  "description"   => "Male Green T-Shirt V Neck",
  "unit_cost"     => 1999.99,
  "quantity"      => 1
);

$item2 = new KondutoModels\Item(array(
  "sku"         => "0017273",
  "category"    => 202,
  "name"        => "Yellow Socks",
  "description" => "Pair of Yellow Socks",
  "unit_cost"   => 29.90,
  "quantity"    => 2,
  "discount"    => 5.00
);
```


Parameter | Description 
--- | ---
sku | _(optional)_ Product or service's SKU or inventory id.
product_code | _(optional)_ Product or service's UPC, barcode or secondary id.
category | _(optional)_ Category code for the item purchased. [See here](http://docs.konduto.com/#n-tables) for the list.
name | _(optional)_ Name of the product or service.
description | _(optional)_ Detailed description of the item.
unit_cost | _(optional)_ Cost of a single unit of this item.
quantity | _(optional)_ Number of units purchased.
discount | _(optional)_ Discounted amount for this item.


## Update an order

This method returns `true` if the update was successful. Otherwise it throws an Exception:

```php
$update = Konduto::updateOrderStatus("ORD1237163", "approved", "Comments about this order");
```


Parameter | Description 
--- | ---
status | _(required)_ New status for this transaction. Either `approved`, `declined` or `fraud`, when you have identified a fraud or chargeback.
comments | _(required)_ Reason or comments about the status update.

## Query an order

```php
$order = Konduto::getOrder("ORD1237163");
```

## Reference Tables

Please [click here](http://docs.konduto.com/#n-tables) for the Currency and Category reference tables.

## Support

Feel free to contact our [support team](mailto:support@konduto.com) if you have any questions or suggestions!