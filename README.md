## Intro

Welcome! This document will explain how to integrate with Konduto's anti-fraud service so you can begin to spot fraud on your e-commerce website. 

This document covers Konduto PHP SDK integration library that facilitates the integration with the Orders API in your PHP application. For more information about the service, other APIs and other integration details check out [Konduto documentation](https://docs.konduto.com/).

## Minimum requirements

* PHP 5.4 or later
* cURL extension

## Local development without host PHP

If your machine does not have `php` installed, you can run everything with Docker.

```bash
docker run --rm -v "$PWD:/app" -w /app composer:2 install --ignore-platform-reqs --no-security-blocking
docker run --rm -v "$PWD:/app" -w /app php:7.4-cli php vendor/bin/phpunit tests/unit
```

Notes:

- This SDK still depends on `phpunit/phpunit:4.8.*`.
- Packagist no longer supports Composer 1, so use Composer 2 with `--no-security-blocking` for this legacy dependency set.

## Installation with Composer

```json
{
    "require": {
        "konduto/sdk": "v2.4.1"
    }
}
```

For older versions of this library check the releases section. We strongly recommend using the latest version possible. 

## Getting started

When a customer makes a purchase on your e-commerce you should input the order information into Konduto's Orders API so it can be analyzed for fraud risk. The analysis happens in real-time and will return you a **recommendation** of what to do next and a **score**, a numeric confidence level about that order's risk.

While most of the parameters are optional we recommend you send most you can, because every data point matters for the analysis. The **billing address** and **credit card information** are specially important, though we understand there are cases where you don't have that information.

### Import Namespaces

Import the following Namespaces:

```php
use Konduto\Core\Konduto;
use Konduto\Models;
```

`Konduto` provides methods for using Konduto services, such as sending an order for analysis (POST), querying (GET) or updating an existent order (PUT):

```
// Send order for analysis
$analyzedOrder = Konduto::analyze($order);
```
```
// Query a previously analyzed order
$order = Konduto::getOrder($orderId);
```
```
// Update the status of a previously analyzed order
Konduto::updateOrderStatus($orderId, $status, $comments);
```

### Set your API key

Before using `Konduto` methods you need first to set your Konduto API key using the `setApiKey()` method. Check the official [Konduto docs](https://docs.konduto.com/) for how to obtain your API key:

```php
Konduto::setApiKey("...YOUR_KONDUTO_PRIVATE_API_KEY...");
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

Every call performed with `Konduto` can throw an exception in case something goes wrong. Check the exception's message to see what went wrong. An example of what can go wrong is that a mandatory field wasn't provided, or a field was provided in the wrong format. Example:

```json
{
    "status": "error",
    "message": {
        "where": "\/",
        "why": {
            "expected": "Authorized credentials",
            "found": "Missing or unauthorized credentials"
        }
    }
}
```


### Building an Order

You can create an `Order` object (or any other model from Konduto SDK) in two ways: by providing an associative array with the allowed fields to the model's constructor or using the methods such as setters and getters.

Check the official [Konduto documentation](https://docs.konduto.com/) for reference to all fields accepted in the Konduto Orders API. Some fields are mandatory, like the order id and total amount, but most are optional.

You can provide order informatino using an associative array, like this:

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

Or using the methods provided by each model, like this:

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

You can check all the possible models in `src/Models/` folder.

#### Using dates and DateTime

This library automatically converts dates to the required API format. If it is convenient for you, you can directly provide a `DateTime` object to the fields that require dates.

```
$now = new \DateTime();
$customer->setCreatedAt($now);
```

## Updating order status

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

## Querying orders

```php
$orderId = "ORD1237163";
$order = Konduto::getOrder($orderId);
```

## Reference Tables

Please [click here](http://docs.konduto.com/#n-tables) for the Currency and Category reference tables.

## Payload field alignment (docs compatibility)

Recent updates aligned model fields with the documentation at `https://docs.konduto.com/reference/enviar-um-pedido` and its child pages.

### Breaking naming changes

The SDK now serializes only documented names for these objects:

| Object | Legacy field(s) | Official field(s) |
| --- | --- | --- |
| Order | `agentSeller` | `agent` |
| Order | `pointOfSale` | `point_of_sale` |
| Order | `bankOriginAccount` | `origin_account` |
| Order | `bankDestinationAccount` | `destination_accounts` |
| Delivery | `deliveryCompany` | `delivery_company` |
| Delivery | `deliveryMethod` | `delivery_method` |
| Delivery | `estimatedShippingDate` | `estimated_shipping_date` |
| Delivery | `estimatedDeliveryDate` | `estimated_delivery_date` |
| Agent | `taxId` | `tax_id` |

If your integration still sends legacy names, update your payload builder to the official fields above.

## Order payload fields by object

Reference source: `https://docs.konduto.com/reference/enviar-um-pedido` and child pages. Tables follow the same format as the official docs: each row's Description starts with an italic tag — _(required)_, _(recommended)_ or _(optional)_ — followed by the field's format/pattern (data type, enum values or date format) and its purpose.

> Obligation for `Order parameters` was confirmed against the official docs page. Obligation for nested objects (Customer, Payment, Address, Travel, Hotel, etc.) was not shown on the page yet, so it was estimated conservatively as _(optional)_ except where the SDK enforces a field to work at all (e.g. `payment.type`, `travel.type`, used to pick the right subclass). Please confirm with Konduto's docs/support for any nested field before relying on this SDK for validation.

### Order parameters

Parameter | Description
--- | ---
id | _(required)_ string. Unique identifier for each order.
visitor | _(optional)_ string. Visitor identifier obtained from our JavaScript snippet.
total_amount | _(required)_ decimal (e.g. `100.10`). Total order amount.
shipping_amount | _(optional)_ decimal. Shipping and handling amount.
tax_amount | _(optional)_ decimal. Taxes amount.
currency | _(optional)_ string, 3 letters (ISO-4217, e.g. `USD`, `BRL`). Currency code.
installments | _(required)_ integer (min: 1, max: 999). Number of installments in the payment plan.
ip | _(recommended)_ string, IPv4 or IPv6. Customer's IP address.
customer | _(required)_ `Customer` object. Object containing the customer details.
payment | _(optional)_ array of `Payment` objects. Array containing the payment methods.
billing | _(optional)_ `Address` object. Object containing the billing information.
shipping | _(optional)_ `Address` object. Object containing the shipping information.
shopping_cart | _(optional)_ array of `Item` objects. Shopping cart items.
first_message | _(optional)_ `YYYY-MM-DDThh:mmZ`. Marketplace first message datetime.
messages_exchanged | _(optional)_ integer. Marketplace messages count.
purchased_at | _(optional)_ `YYYY-MM-DDTHH:mm:ssZ`. Order purchase datetime.
recurring | _(optional)_ boolean. Recurring transaction flag.
risk_level | _(optional)_ string. Order risk level.
analyze | _(optional)_ boolean. Analyze flag.
sales_channel | _(optional)_ string. Sales channel.
hotel | _(optional)_ `Hotel` object. Hotel object.
travel | _(optional)_ `Travel` object. Travel object.
seller | _(optional)_ `Seller` object. Seller object.
events | _(optional)_ array of `Event` objects. Event list.
scheduled | _(optional)_ boolean. Scheduled transaction flag.
origin_account | _(optional)_ `BankOriginAccount` object. Origin account object.
destination_accounts | _(optional)_ array of `BankDestinationAccount` objects. Destination account list.
tenant | _(optional)_ `Tenant` object. Tenant object.
point_of_sale | _(optional)_ `PointOfSale` object. Point-of-sale object.
agent | _(optional)_ `AgentSeller` object. Agent object.

### Customer information

Parameter | Description
--- | ---
id | _(optional)_ string. Customer unique identifier.
name | _(optional)_ string. Customer full name.
email | _(optional)_ string, email format. Customer email.
dob | _(optional)_ `YYYY-MM-DD`. Date of birth.
tax_id | _(optional)_ string (CPF/CNPJ/SSN, etc.). Customer tax document.
phone1 | _(optional)_ string. Primary phone number.
phone2 | _(optional)_ string. Secondary phone number.
created_at | _(optional)_ `YYYY-MM-DD`. Customer creation date.
new | _(optional)_ boolean. New customer flag.
vip | _(optional)_ boolean. VIP customer flag.
type | _(optional)_ string. Customer type.
risk_level | _(optional)_ string. Customer risk level.
risk_score | _(optional)_ numeric. Customer risk score.
mother_name | _(optional)_ string. Customer mother name.

### Payment information

Parameter | Description
--- | ---
type | _(required)_ enum: `credit`, `boleto`, `debit`, `transfer`, `voucher`, `balance`, `pix`. Payment method type (defines the SDK subclass: `CreditCard`/`Boleto`/`Payment`).
status | _(optional)_ enum: `approved`, `declined`, `pending`. Payment status.
bin | _(optional)_ string, 6 digits (`credit` only). Card BIN.
last4 | _(optional)_ string, 4 digits (`credit` only). Last 4 card digits.
amount | _(optional)_ decimal. Amount paid in this method.
expiration_date | _(optional)_ `MMYYYY` for credit card or `YYYY-MM-DD` for boleto. Card expiration or boleto expiration date.
description | _(optional)_ string. Payment description.
tax_id | _(optional)_ string. Cardholder tax document.
cvv_result | _(optional)_ string. CVV verification result.
avs_result | _(optional)_ string. AVS verification result.
sha1 | _(optional)_ string, SHA1 hash. Encrypted card hash.
name | _(optional)_ string. Buyer name.
holder | _(optional)_ string. Card holder name.
mcc | _(optional)_ string. Merchant category code.
mid | _(optional)_ string. Merchant identifier.
3ds_id | _(optional)_ string. 3DS transaction identifier.
merchant_tax_id | _(optional)_ string. Merchant tax document.
voucher_type | _(optional)_ string (`voucher` only). Voucher type.

### Billing address

Parameter | Description
--- | ---
name | _(optional)_ string. Billing recipient name.
address1 | _(optional)_ string. Billing address line 1.
address2 | _(optional)_ string. Billing address line 2.
city | _(optional)_ string. Billing city.
state | _(optional)_ string. Billing state.
zip | _(optional)_ string. Billing ZIP code.
country | _(optional)_ string, ISO-3166 alpha-2 (e.g. `BR`, `US`). Billing country code.

### Shipping address

Parameter | Description
--- | ---
name | _(optional)_ string. Shipping recipient name.
address1 | _(optional)_ string. Shipping address line 1.
address2 | _(optional)_ string. Shipping address line 2.
city | _(optional)_ string. Shipping city.
state | _(optional)_ string. Shipping state.
zip | _(optional)_ string. Shipping ZIP code.
country | _(optional)_ string, ISO-3166 alpha-2. Shipping country code.
estimatedDate | _(optional)_ date. Estimated delivery date.
value | _(optional)_ decimal. Shipping value.
lat | _(optional)_ float. Destination latitude.
lon | _(optional)_ float. Destination longitude.

### Delivery and logistics

Parameter | Description
--- | ---
delivery_company | _(optional)_ string. Delivery company.
delivery_method | _(optional)_ string. Delivery method.
estimated_shipping_date | _(optional)_ date/string. Estimated shipping date.
estimated_delivery_date | _(optional)_ date/string. Estimated delivery date.

### Device information

Parameter | Description
--- | ---
fingerprint | _(optional)_ string. Device fingerprint.
provider | _(optional)_ string. Device provider.
category | _(optional)_ string. Device category.
model | _(optional)_ string. Device model.
platform | _(optional)_ string. Device platform.
manufacturer | _(optional)_ string. Device manufacturer.
os | _(optional)_ string. Device operating system.
browser | _(optional)_ string. Device browser.
language | _(optional)_ string. Device language.
flash | _(optional)_ boolean. Flash enabled flag.
cookie | _(optional)_ boolean. Cookie enabled flag.
javascript | _(optional)_ boolean. JavaScript enabled flag.
timezone | _(optional)_ string/integer. Device timezone.
user_id | _(optional)_ string. Device user identifier.

### Shopping cart

Parameter | Description
--- | ---
sku | _(optional)_ string. Product SKU.
product_code | _(optional)_ string. Product code.
category | _(optional)_ string. Product category.
name | _(optional)_ string. Product name.
description | _(optional)_ string. Product description.
unit_cost | _(optional)_ decimal. Item unit cost.
quantity | _(optional)_ integer. Item quantity.
discount | _(optional)_ decimal. Item discount.
created_at | _(optional)_ `YYYY-MM-DD`. Item creation date.
deliveryType | _(optional)_ string. Item delivery type.
deliverySlaInMinutes | _(optional)_ integer. Delivery SLA in minutes.
sellerId | _(optional)_ string. Marketplace seller identifier.
image | _(optional)_ string, URL. Product image URL.

### Travel

Parameter | Description
--- | ---
type | _(required)_ enum: `flight`, `bus`. Travel type. Decides whether `departure`/`return` are parsed as `FlightLeg` or `BusTravelLeg`.
expiration_date | _(optional)_ `YYYY-MM-DDTHH:mm:ssZ`. Travel expiration date.
departure | _(optional)_ `TravelLeg` object (`FlightLeg`/`BusTravelLeg`). Outbound segment object.
return | _(optional)_ `TravelLeg` object (`FlightLeg`/`BusTravelLeg`). Return segment object.
passengers | _(optional)_ array of `Passenger` objects. Passenger list.

### Travel leg (`departure` and `return`)

Parameter | Description
--- | ---
origin_city | _(optional)_ string (only when `travel.type = bus`). Origin city.
destination_city | _(optional)_ string (only when `travel.type = bus`). Destination city.
origin_airport | _(optional)_ string, 3-letter IATA code (only when `travel.type = flight`). Origin airport code.
destination_airport | _(optional)_ string, 3-letter IATA code (only when `travel.type = flight`). Destination airport code.
date | _(optional)_ `YYYY-MM-DDTHH:mmZ` (no seconds). Departure datetime.
number_of_connections | _(optional)_ integer. Number of connections.
class | _(optional)_ string. Travel class.
fare_basis | _(optional)_ string. Fare basis code.
company | _(optional)_ string. Travel company.

### Passenger

Parameter | Description
--- | ---
name | _(optional)_ string. Passenger name.
document | _(optional)_ string. Passenger document.
document_type | _(optional)_ string. Passenger document type.
dob | _(optional)_ `YYYY-MM-DD`. Passenger date of birth.
nationality | _(optional)_ string, ISO-3166 alpha-2. Passenger nationality.
frequent_traveler | _(optional)_ boolean. Frequent traveler flag.
special_needs | _(optional)_ boolean. Special needs flag.
loyalty | _(optional)_ `Loyalty` object. Loyalty object.

### Loyalty

Parameter | Description
--- | ---
program | _(optional)_ string. Loyalty program.
category | _(optional)_ string. Loyalty category.

### Hotel

Parameter | Description
--- | ---
name | _(optional)_ string. Hotel name.
address1 | _(optional)_ string. Hotel address line 1.
address2 | _(optional)_ string. Hotel address line 2.
city | _(optional)_ string. Hotel city.
state | _(optional)_ string. Hotel state.
zip | _(optional)_ string. Hotel ZIP code.
country | _(optional)_ string, ISO-3166 alpha-2. Hotel country code.
category | _(optional)_ string. Hotel category.
rooms | _(optional)_ array of `HotelRoom` objects. Room list.

### Hotel room

Parameter | Description
--- | ---
number | _(optional)_ string. Room number.
code | _(optional)_ string. Room code.
type | _(optional)_ string. Room type.
check_in_date | _(optional)_ `YYYY-MM-DD`. Check-in date.
check_out_date | _(optional)_ `YYYY-MM-DD`. Check-out date.
number_of_guests | _(optional)_ integer. Number of guests.
board_basis | _(optional)_ string. Board basis.
guests | _(optional)_ array of `HotelRoomGuest` objects. Guest list.

### Hotel room guest

Parameter | Description
--- | ---
name | _(optional)_ string. Guest name.
document | _(optional)_ string. Guest document.
document_type | _(optional)_ string. Guest document type.
dob | _(optional)_ `YYYY-MM-DD`. Guest date of birth.
nationality | _(optional)_ string, ISO-3166 alpha-2. Guest nationality.

### Event

Parameter | Description
--- | ---
name | _(optional)_ string. Event name.
date | _(optional)_ `YYYY-MM-DDTHH:mm:ssZ`. Event datetime.
type | _(optional)_ string. Event type.
subtype | _(optional)_ string. Event subtype.
venue | _(optional)_ `Venue` object. Venue object.
tickets | _(optional)_ array of `Ticket` objects. Ticket list.

### Venue

Parameter | Description
--- | ---
name | _(optional)_ string. Venue name.
address | _(optional)_ string. Venue address.
city | _(optional)_ string. Venue city.
state | _(optional)_ string. Venue state.
country | _(optional)_ string, ISO-3166 alpha-2. Venue country.
capacity | _(optional)_ integer. Venue capacity.

### Ticket

Parameter | Description
--- | ---
id | _(optional)_ string. Ticket identifier.
category | _(optional)_ string. Ticket category.
section | _(optional)_ string. Ticket section.
premium | _(optional)_ boolean. Premium ticket flag.
attendee | _(optional)_ `Attendee` object. Attendee object.

### Attendee

Parameter | Description
--- | ---
name | _(optional)_ string. Attendee name.
document | _(optional)_ string. Attendee document.
document_type | _(optional)_ string. Attendee document type.
dob | _(optional)_ `YYYY-MM-DD`. Attendee date of birth.

### Seller

Parameter | Description
--- | ---
id | _(optional)_ string. Seller identifier.
name | _(optional)_ string. Seller name.
created_at | _(optional)_ `YYYY-MM-DD`. Seller creation date.

### Agent

Parameter | Description
--- | ---
id | _(optional)_ string. Agent identifier.
login | _(optional)_ string. Agent login.
name | _(optional)_ string. Agent name.
tax_id | _(optional)_ string. Agent tax document.
dob | _(optional)_ `YYYY-MM-DD`. Agent date of birth.
category | _(optional)_ string. Agent category.
created_at | _(optional)_ `YYYY-MM-DD`. Agent creation date.

### Point of sale

Parameter | Description
--- | ---
id | _(optional)_ string. Point-of-sale identifier.
name | _(optional)_ string. Point-of-sale name.
lat | _(optional)_ float. Latitude.
lon | _(optional)_ float. Longitude.
address | _(optional)_ string. Address.
city | _(optional)_ string. City.
state | _(optional)_ string. State.
zip | _(optional)_ string. ZIP code.
country | _(optional)_ string, ISO-3166 alpha-2. Country code.

### Tenant

Parameter | Description
--- | ---
id | _(optional)_ string. Tenant identifier.
name | _(optional)_ string. Tenant name.
created_at | _(optional)_ `YYYY-MM-DD`. Tenant creation date.

### Origin account

Parameter | Description
--- | ---
id | _(optional)_ string. Origin account identifier.
key_type | _(optional)_ string. Origin account key type.
key_value | _(optional)_ string. Origin account key value.
holder_name | _(optional)_ string. Origin account holder name.
holder_tax_id | _(optional)_ string. Origin account holder tax document.
bank_code | _(optional)_ string. Origin account bank code.
bank_name | _(optional)_ string. Origin account bank name.
bank_branch | _(optional)_ string. Origin account bank branch.
bank_account | _(optional)_ string. Origin account number.
balance | _(optional)_ decimal. Origin account balance.

### Destination account

Parameter | Description
--- | ---
id | _(optional)_ string. Destination account identifier.
key_type | _(optional)_ string. Destination account key type.
key_value | _(optional)_ string. Destination account key value.
holder_name | _(optional)_ string. Destination account holder name.
holder_tax_id | _(optional)_ string. Destination account holder tax document.
bank_code | _(optional)_ string. Destination account bank code.
bank_name | _(optional)_ string. Destination account bank name.
bank_branch | _(optional)_ string. Destination account bank branch.
bank_account | _(optional)_ string. Destination account number.
amount | _(optional)_ decimal. Destination transfer amount.

## Support

Feel free to contact our [support team](mailto:support@konduto.com) if you have any questions or suggestions!


## Contributing

Found a bug or missing feature? This is an open-source project, so a Pull Request will be more than welcome. Just make sure following the guidelines:

- Respect the established naming conventions.
- Don't introduce external dependencies.
- Always add tests for covering new pieces of code.
- Respect the minimum requirements. I.e. avoid using PHP libs and features that might require changing them. We want to provide this library to the broadest audience possible.

### Testing

This project uses [PHPUnit](https://phpunit.de/) as its testing framework. Before running any test, make sure you install it. To install all project's dependencies using [Composer](https://getcomposer.org/) run a composer install first:

```
// This command might change depending on your Composer installation.
composer install
```

With Docker (no host PHP required):

```bash
docker run --rm -v "$PWD:/app" -w /app composer:2 install --ignore-platform-reqs --no-security-blocking
```

There are two types of test:

- Unit tests: Just test the logic of the code. They are located at `tests/unit/`.

You can run the unit tests with the command:

```
vendor/bin/phpunit tests/unit
```

Docker equivalent:

```bash
docker run --rm -v "$PWD:/app" -w /app php:7.4-cli php vendor/bin/phpunit tests/unit
```

- Integration tests: Make actual calls to Konduto's sandbox API to check the integration. They are located at `tests/integration/`. 

Before running the integration tests you will need to provide a working sandbox API key as an environment variable `KONDUTO_SANDBOX_API_KEY`. If you don't do this **all integration tests will fail**.

```
export KONDUTO_SANDBOX_API_KEY=your_api_key
```

Now you can run the integration tests:

```
vendor/bin/phpunit tests/integration
```

Docker equivalent:

```bash
docker run --rm -e KONDUTO_SANDBOX_API_KEY="$KONDUTO_SANDBOX_API_KEY" -v "$PWD:/app" -w /app php:7.4-cli php vendor/bin/phpunit tests/integration
```

