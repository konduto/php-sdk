## Intro

Welcome! This document will explain how to integrate with Konduto's anti-fraud service so you can begin to spot fraud on your e-commerce website.

Our service uses the visitor's behavior to analyze browsing patterns and detect fraud. You will need to add a **JavaScript** snippet to your website and tag your pages, so we can see your visitors, and call our **REST API** to send purchases, so we can analyze them.

This document refers to the **PHP SDK** used for our API.

## Requirements

* PHP 5.3.10+
* cURL extension

## Installation with Composer

```json
{
  "require": {
    "konduto/sdk": "v1.4.0"
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

Sending an order for analysis is as easy as calling the `analyze` method from Konduto core class for an `Order` object, as the snippet below shows. The recommendention is returned inside the order object sent for analyzis.

```php
try {
    Konduto::analyze($order);

    // Let's check the recommendation returned by Konduto
    echo "\n Konduto recommendation is to " . $order->recommendation() . " this order.";
}
catch (Exception $e) {
    echo "\n Konduto wasn't able to return a recommendation: " . $e->getMessage();
}
```

The request consists of a root object containing information related to the order as well as a `customer`, `billing` and `shipping` objects and the `payment` and `shopping_cart` arrays.

### Order information

```php
$order = new KondutoModels\Order(array(
  "id"                 => uniqid(),
  "visitor"            => "4738d516f09cab3a2c1ee973bec88a5a367a59e4",
  "total_amount"       => 100.00,
  "shipping_amount"    => 20.00,
  "tax_amount"         => 3.45,
  "currency"           => "USD",
  "installments"       => 1,
  "ip"                 => "170.149.100.10",
  "purchased_at"       => "2015-04-25T22:29:14Z",
  "first_message"      => "2015-04-22T18:01:02Z",
  "messages_exchanged" => 2,
  "customer"           => $customer,
  "payment"            => array($credit_card, $boleto),
  "billing"            => $billing,
  "shipping"           => $shipping,
  "shopping_cart"      => array($item1, $item2),
  "travel"             => $travel,
  "seller"             => $seller
));
```


Parameter | Description
--- | ---
id | _(required)_ Unique identifier for each order.
visitor | _(required)_ Visitor identifier obtained from our JavaScript snippet.
total_amount | _(required)_ Total order amount.
shipping_amount | _(optional)_ Shipping and handling amount.
tax_amount | _(optional)_ Taxes amount.
ip | _(optional)_ Customer's IPv4 address.
currency | _(optional)_ Currency code with 3 letters (ISO-4712).
installments | _(optional)_ Number of installments in the payment plan.
first_message | _(optional)_  In Marketplaces, it’s the date and time of the first message exchanged between buyer and seller. YYYY-MM-DDTHH:mm:ssZ format (ISO 8601).
purchased_at | _(optional)_ In Marketplaces, it’s the date and time the order was closed. YYYY-MM-DDTHH:mm:ssZ format (ISO 8601).
messages_exchanged | _(optional)_ In Marketplaces, should contain the number of messages exchanged between buyer and seller up until the order was placed.
customer | _(required)_ [Customer object](#Customer) containing the customer details.
payment | _(optional)_ Array containing [Payment objects](#Payment).
billing | _(optional)_ [Address object](#Billing) containing billing information.
shipping | _(optional)_ [Address object](#Shipping) containing shipping information.
shopping_cart | _(optional)_ Array containing the [Item objects](#Item).
travel | _(optional)_ [Travel object](#Travel) containing travel information
seller | _(optional)_ [Seller object](#Seller) Used by Marketplaces to indicate information about the seller.


### <a name="Customer"></a>Customer information

```php
$customer = new KondutoModels\Customer(array(
    "id"         => "28372",
    "name"       => "Mary Jane",
    "tax_id"     => "6253407",
    "phone1"     => "212-555-1234",
    "phone2"     => "202-555-6789",
    "email"      => "mary.jane@example.com",
    "is_new"     => true,
    "vip"        => false,
    "dob"        => "1988-10-02",
    "created_at" => "2015-03-29"
));
```
* OBS: Differently from API's naming, here we use `is_new` instead of simply `new` because `new` is a reserved word in PHP.


Parameter | Description
--- | ---
id | _(required)_ **Unique** identifier for each customer. Can be anything you like (counter, id, e-mail address) as long as it's consistent in future orders.
name | _(required)_ Customer's full name.
email | _(required)_ Customer's e-mail address
tax_id | _(optional)_ Customer's tax id.
phone1 | _(optional)_ Customer's primary phone number
phone2 | _(optional)_ Customer's secondary phone number
is_new | _(optional)_ Boolean indicating if the customer is using a newly created account for this purchase.
vip | _(optional)_ Boolean indicating if the customer is a VIP or frequent buyer.
dob | _(optional)_ Date of birth (YYYY-MM-DD).
created_at | _(optional)_ Date of creation of customer account (YYYY-MM-DD).


### <a name="Payment"></a>Payment information

Order's payment field receives an array of objects whose class is children of `Payment` class. For now, the two possible options are `CreditCard` and `Boleto`.

```php
$credit_card = new KondutoModels\CreditCard(array(
  "bin"             => "490172",
  "last4"           => "0012",
  "expiration_date" => "072015",
  "status"          => "approved"
));
```

Parameter | Description
--- | ---
status | _(required)_ The status of the transaction returned by the payment processor. Accepts `approved`, `declined` or `pending` if the payment wasn't been processed yet.
bin | _(optional)_ First six digits of the customer's credit card. Used to identify the type of card being sent.
last4 | _(optional)_ Four last digits of the customer's credit card number.
expiration_date | _(optional)_ Card's expiration date under MMYYYY format.

```php
$boleto = new KondutoModels\Boleto(array(
  "expiration_date" => "2014-12-11"  // Here it needs to be a full date
));
```

Parameter | Description
--- | ---
expiration_date | _(optional)_ Boleto's expiration date under YYYY-MM-DD format.

Alternatively, you can create a payment object by using the static method `Payment::instantiante` of the `Payment` class. Additionally, you have to provide `type` to indicate the type of the payment you are creating:

```php
$credit_card = KondutoModels\Payment::instantiate(array(
  "type"            => "credit",    // Required
  "bin"             => "490172",
  "last4"           => "0012",
  "expiration_date" => "072015",
  "status"          => "approved"
));

$boleto = KondutoModels\Payment::instantiate(array(
  "type"            => "boleto",    // Required
  "expiration_date" => "2014-12-11"
));
```


### <a name="Billing"></a>Billing address

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


### <a name="Shipping"></a>Shipping address

```php
$shipping = new KondutoModels\Address(array(
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


### <a name="Item"></a>Shopping cart

```php
$item1 = new KondutoModels\Item(array(
    "sku"           => "9919023",
    "product_code"  => "123456789999",
    "category"      => 201,
    "name"          => "Green T-Shirt",
    "description"   => "Male Green T-Shirt V Neck",
    "unit_cost"     => 1999.99,
    "quantity"      => 1,
    "created_at"    => "2015-02-28"
));

$item2 = new KondutoModels\Item(array(
    "sku"         => "0017273",
    "category"    => 202,
    "name"        => "Yellow Socks",
    "description" => "Pair of Yellow Socks",
    "unit_cost"   => 29.90,
    "quantity"    => 2,
    "discount"    => 5.00,
    "created_at"  => "2015-02-28"
));
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
created_at | _(optional)_ Date of creation of item in the system (YYYY-MM-DD).


### <a name="Travel"></a>Travel

The object `travel` can be populated with 2 different types of object: `Flight`
or `BusTravel`. Both classes inherit from a common `Travel` class.

```php
$flight = new KondutoModels\Flight(array(
    "departure" => array(
        "origin_city" => "São Paulo",
        "origin_airport" => "GRU",
        "destination_city" => "São Francisco",
        "destination_airport" => "SFO",
        "date" => "2018-12-25T18:00Z",
        "number_of_connections" => 1,
        "class" => "economy",
        "fare_basis" => "Y"
    ),
    "return_leg" => array(
        "origin_city" => "São Paulo",
        "origin_airport" => "GRU",
        "destination_city" => "São Francisco",
        "destination_airport" => "SFO",
        "date" => "2018-12-30T18:00Z",
        "number_of_connections" => 1,
        "class" => "business"
    ),
    "passengers" => array(
        array(
            "name" => "Júlia da Silva",
            "document" => "A1B2C3D4",
            "document_type" => "id",
            "dob" => "1986-03-22",
            "nationality" => "US",
            "frequent_flyer" => true,
            "special_needs" => false,
            "loyalty" => array(
                "program" => "advantage",
                "category" => "gold"
            )
        ),
        array(
            "name" => "Carlos Siqueira",
            "document" => "AB11223344",
            "document_type" => "passport",
            "dob" => "1970-12-01",
            "nationality" => "US",
            "frequent_flyer" => false,
            "special_needs" => true,
            "loyalty" => array(
                "program" => "skymiles",
                "category" => "silver"
            )
        )
    )
));

$bus_travel = new KondutoModels\BusTravel(array(
    "departure" => array(
        "origin_city" => "Campinas",
        "destination_city" => "Ponta Grossa",
        "date" => "2015-06-01T08:30Z"
    ),
    "return" => array(
        "origin_city" => "Ponta Grossa",
        "destination_city" => "Campinas",
        "date" => "2015-06-08T11:15Z"
    ),
    "passengers" => array(
        array(
            "name" => "Amilton de Oliveira",
            "document" => "191123872-1",
            "document_type" => "id"
        )
    )
));
```

Optionally, you may instead use the *static* method `Travel::instantiate` to build
a `BusTravel` or `Flight` object. Additionally you must provide the property `type`, that can assume the values `flight` or `bus`.

```php
$bus_travel = KondutoModels\Travel::instantiate(array(
    "type" => "bus",   // or "flight"
    "departure" => array(
        "origin_city" => "Campinas",
        "destination_city" => "Ponta Grossa",
        "date" => "2015-06-01T08:30Z"
    ),
    "passengers" => array(
        array(
            "name" => "Amilton de Oliveira",
            "document" => "191123872-1",
            "document_type" => "id"
        )
    )
));
```

#### Travel

Parameter | Description
--- | ---
type | _(required)_ `bus` or `flight`. It is automatically provided if you are building from `BusTravel` or `Flight` classes.
departure | _(required)_ A [`TravelLeg` object](#TravelLeg) containing departure trip information.
return_leg * | _(optional)_ A [`TravelLeg` object](#TravelLeg) containing return trip information.
passengers | _(optional)_ Array of [`Passenger` objects](#Passenger).

* The field `return_leg` is referred in the API documentation as solely `return`. Since *return* is a reserved word in PHP, we use *return_leg* as the name of the property.

#### <a name="TravelLeg"></a>TravelLeg

Parameter | Description
--- | ---
origin_city | _(required)_ for `BusTravel`, _(optional)_ for `Flight`
origin_airport | _(required)_ for `Flight`, leave absent for `BusTravel`
destination_city | _(required)_ for `BusTravel`, _(optional)_ for `Flight`
destination_airport | _(required)_ for `Flight`, leave absent for `BusTravel`
date | _(required)_ string with UTC date and time of departure in YYYY-MM-DDThh:mmZ format (ISO 8601)
number_of_connections | _(optional)_ Number of connections
class | _(optional)_ One of the strings: `economy`, `business` or `travel`
fare_basis | _(optional)_ (Fare basis code)[https://en.wikipedia.org/wiki/Fare_basis_code]

#### <a name="Passenger"></a>Passenger

Parameter | Description
--- | ---
name | _(required)_ Passenger's full name
document | _(required)_ Document number or identification
document_type | _(required)_ One of the strings: `id` or `passport`
dob | _(optional)_ Passenger's country of birth in YYYY-MM-DD (ISO 3166-2)
nationality | _(optional)_ Passenger's country of birth (ISO 3166-2)
loyalty | _(optional)_ [`Loyalty` object](#Loyalty)
frequent_traveler | _(optional)_ Boolean
special_needs | _(optional)_ Boolean

#### <a name="Loyalty"></a>Loyalty

Parameter | Description
--- | ---
program | _(optional)_ Name of loyalty program.
category | _(optional)_ Category of loyalty program.


#### <a name="Seller"></a>Seller

Parameter | Description
--- | ---
id | _(required)_ Id of the seller.
name | _(optional)_ Name of the seller.
created_at | _(optional)_ Seller creation date within the Marketplace, in YYYY-MM-DD format (ISO 8601).

```php
$seller = new KondutoModels\Seller(array(
    "id" => "A000003",
    "name" => "Daniel Marinus Kan",
    "created_at" => "2015-03-25"
));
```

### Creating an order with all fields at once

```php
$order = new KondutoModels\Order(array(
  "id"                 => uniqid(),
  "visitor"            => "4738d516f09cab3a2c1ee973bec88a5a367a59e4",
  "total_amount"       => 100.00,
  "shipping_amount"    => 20.00,
  "tax_amount"         => 3.45,
  "currency"           => "USD",
  "installments"       => 1,
  "ip"                 => "170.149.100.10",
  "purchased_at"       => "2015-04-25T22:29:14Z",
  "first_message"      => "2015-04-22T18:01:02Z",
  "messages_exchanged" => 2,
  "customer"           => array(
    "id"         => "28372",
    "name"       => "Mary Jane",
    "tax_id"     => "6253407",
    "phone1"     => "212-555-1234",
    "phone2"     => "202-555-6789",
    "email"      => "mary.jane@example.com",
    "is_new"     => true,
    "vip"        => false,
    "dob"        => "1988-10-02",
    "created_at" => "2015-03-29"
  ),
  "payment" => array(
    array(
      "type"            => "credit",  // Payment 'type' required
      "bin"             => "490172",
      "last4"           => "0012",
      "expiration_date" => "072015",
      "status"          => "approved"
    ),
    array(
      "type"            => "boleto",  // Payment 'type' required
      "expiration_date" => "2014-12-09"
    )
  ),
  "billing" => array(
    "name"     => "Mary Jane",
    "address1" => "123 Main St.",
    "address2" => "Apartment 4",
    "city"     => "New York City",
    "state"    => "NY",
    "zip"      => "10460",
    "country"  => "US"
  ),
  "shipping" => array(
    "name"     => "Mary Jane",
    "address1" => "123 Main St.",
    "address2" => "Apartment 4",
    "city"     => "New York City",
    "state"    => "NY",
    "zip"      => "10460",
    "country"  => "US"
  ),
  "shopping_cart" => array(
    array(
      "sku"          => "9919023",
      "product_code" => "123456789999",
      "category"     => 201,
      "name"         => "Green T-Shirt",
      "description"  => "Male Green T-Shirt V Neck",
      "unit_cost"    => 1999.99,
      "quantity"     => 1,
      "created_at"  => "2015-02-28"
    ),
    array(
      "sku"         => "0017273",
      "category"    => 202,
      "name"        => "Yellow Socks",
      "description" => "Pair of Yellow Socks",
      "unit_cost"   => 29.90,
      "quantity"    => 2,
      "discount"    => 5.00,
      "created_at"  => "2015-02-28"
    )
  )
));
```


## Update an order

This method returns `true` if the update was successful. Otherwise it throws an Exception:

```php
$update = Konduto::updateOrderStatus("ORD1237163", "approved", "Comments about this order");
```


Parameter | Description
--- | ---
status | _(required)_ New status for this transaction. Either `approved`, `declined`, `fraud`, `canceled` or `not_authorized`, when you have identified a fraud or chargeback.
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
