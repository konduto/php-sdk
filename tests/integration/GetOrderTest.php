<?php namespace Konduto\Tests\Integration;

use Konduto\Core\Konduto;
use Konduto\Models\Address;
use Konduto\Models\Item;
use Konduto\Models\Order;
use Konduto\Models\Payment;

class GetOrderTest extends \PHPUnit_Framework_TestCase {

    public $uniqueId;

    public static function setUpBeforeClass() {
        Konduto::setApiKey("T738D516F09CAB3A2C1EE");
    }

    function test_getOrderSimple() {
        $order = $this->buildOrder();
        $order = $this->analyzeAndQuery($order);
        $this->assertOrderFields($order);
    }

    function test_getOrderWithShoppingCart() {
        $order = $this->buildOrderWithShoppingCart();
        $order = $this->analyzeAndQuery($order);
        $this->assertOrderWithShoppingCart($order);
    }

    function test_getOrderWithSeller() {
        $order = $this->buildOrderWithSeller();
        $order = $this->analyzeAndQuery($order);
        $this->assertOrderWithSeller($order);
    }

    function test_getFlightOrder() {
        $order = $this->buildFlightOrder();
        $order = $this->analyzeAndQuery($order);
        $this->assertFlightOrder($order);
    }

    function test_getBusOrder() {
        $order = $this->buildBusOrder();
        $order = $this->analyzeAndQuery($order);
        $this->assertBusOrder($order);
    }

    function analyzeAndQuery(Order $order) {
        Konduto::analyze($order);
        return Konduto::getOrder($order->getId());
    }

    /**
     * Builds a simple order with a Customer
     * @param array $info array to merge onto array passed to constructor
     * @return Order
     */
    function buildOrder(array $info = array()) {
        $this->uniqueId = "PhpSdkGetOrder-" . uniqid();
        $order = new Order(array_replace(array(
            "id" => $this->uniqueId,
            "visitor" => "da39a3ee5e6b4b0d3255bfef95601890afd80709",
            "total_amount" => 100.01,  // Result approve in test env
            "shipping_amount" => 20.00,
            "tax_amount" => 3.45,
            "currency" => "BRL",
            "installments" => 2,
            "ip" => "128.12.12.12",
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
            ),
            "payment" => array(
                array(
                    "type" => "credit",
                    "status" => "approved",
                    "bin" => "123456",
                    "last4" => "0987",
                    "expiration_date" => "122020"
                ),
                array("type" => "boleto", "expiration_date" => "2016-05-23"),
                array("type" => "voucher")
            ),
            "billing" => array(
              "name" => "Mary Jane",
              "address1" => "123 Main St.",
              "address2" => "Apartment 4",
              "city" => "New York City",
              "state" => "NY",
              "zip" => "10460",
              "country" => "US"
            ),
            "shipping" => array(
              "name" => "Mary Jane",
              "address1" => "89 Holly St.",
              "city" => "Springfield",
              "state" => "CO",
              "zip" => "02955",
              "country" => "US"
            ),
        ), $info));
        return $order;
    }

    function buildOrderWithShoppingCart() {
        return $this->buildOrder(array(
            "shopping_cart" => array(
                array(
                  "sku" => "9919023",
                  "product_code" => "123456789999",
                  "category" => 201,
                  "name" => "Green T-Shirt",
                  "description" => "Male Green T-Shirt V Neck",
                  "unit_cost" => 29.99,
                  "quantity" => 1,
                  "created_at" => "2015-12-10"
                ),
                array(
                  "sku" => "0017273",
                  "category" => 202,
                  "name" => "Yellow Socks",
                  "description" => "Pair of Yellow Socks",
                  "unit_cost" => 7.50,
                  "quantity" => 2,
                  "discount" => 1.00,
                  "created_at" => "2015-03-10"
                )
            )
        ));
    }

    function buildOrderWithSeller() {
        return $this->buildOrder(array(
            "first_message" => "2018-12-20T15:59:01Z",
            "messages_exchanged" => 9,
            "purchased_at" => "2018-12-25T12:00:25Z",
            "seller" => array(
                "id" => "012",
                "name" => "Vendedor",
                "created_at" => "2015-03-31"
            )
        ));
    }

    function buildFlightOrder() {
        return $this->buildOrder(array(
            "travel" => array(
                "type" => "flight",
                "departure" => array(
                    "origin_airport" => "GRU",
                    "destination_airport" => "SFO",
                    "date" => "2018-12-25T18:00Z",
                    "number_of_connections" => 1,
                    "class" => "economy",
                    "fare_basis" => "Y"
                ),
                "return" => array(
                    "origin_airport" => "SFO",
                    "destination_airport" => "GRU",
                    "date" => "2018-12-30T18:00Z",
                    "number_of_connections" => 1,
                    "class" => "business"
                ),
                "passengers" => array(
                    array(
                        "name" => "Júlia da Silva",
                        "document" => "12345678909",
                        "document_type" => "id",
                        "dob" => "1970-01-01",
                        "nationality" => "BR",
                        "loyalty" => array(
                            "program" => "smiles",
                            "category" => "gold"
                        ),
                        "frequent_traveler" => true,
                        "special_needs" => false
                    ),
                    array(
                        "name" => "Carlos Siqueira",
                        "document" => "XYZ1234",
                        "document_type" => "passport",
                        "dob" => "1970-12-01",
                        "nationality" => "US",
                        "loyalty" => array(
                            "program" => "multiplus",
                            "category" => "silver"
                        ),
                        "frequent_traveler" => false,
                        "special_needs" => true
                    )
                )
            )
        ));
    }

    function buildBusOrder() {
        return $this->buildOrder(array(
            "travel" => array(
                "type" => "bus",
                "departure" => array(
                    "origin_city" => "São Paulo",
                    "destination_city" => "Campinas",
                    "date" => "2018-12-25T18:00Z"
                ),
                "passengers" => array(
                    array(
                        "name" => "Júlia da Silva",
                        "document" => "12345678909",
                        "document_type" => "id",
                        "dob" => "1970-01-01",
                        "nationality" => "BR",
                        "loyalty" => array(
                            "program" => "smiles",
                            "category" => "gold"
                        )
                    )
                )
            )
        ));
    }

    function assertOrderFields(Order $order) {
        $this->assertEquals($this->uniqueId, $order->getId());
        $this->assertEquals("da39a3ee5e6b4b0d3255bfef95601890afd80709", $order->getVisitor());
        $this->assertEquals(Order::RECOMMENDATION_APPROVE, $order->getRecommendation(), "", 0, 0, false, true);
        $this->assertEquals(0.01, $order->getScore());
        $this->assertEquals(Order::STATUS_APPROVED, $order->getStatus(), "", 0, 0, false, true);
        $this->assertEquals(100.01, $order->getTotalAmount());
        $this->assertEquals(20.0, $order->getShippingAmount());
        $this->assertEquals(3.45, $order->getTaxAmount());
        $this->assertEquals("BRL", $order->getCurrency());
        $this->assertEquals(2, $order->getInstallments());
        $this->assertInstanceOf('DateTime', $order->getCreatedAt());
        $this->assertInstanceOf('DateTime', $order->getUpdatedAt());
        $this->assertInstanceOf('Konduto\Models\Customer', $order->getCustomer());
        $customer = $order->getCustomer();
        $this->assertEquals("28372", $customer->getId());
        $this->assertEquals("Júlia da Silva", $customer->getName());
        $this->assertEquals("12345678909", $customer->getTaxId());
        $this->assertDate("1970-12-25", $customer->getDob());
        $this->assertEquals("1112345678", $customer->getPhone1());
        $this->assertEquals("2121436578", $customer->getPhone2());
        $this->assertEquals("jsilva@exemplo.com.br", $customer->getEmail());
        $this->assertDate("2010-12-25", $customer->getCreatedAt());
        $this->assertFalse($customer->getNew());
        $this->assertFalse($customer->getVip());
        $this->assertOrderPayments($order);
        $billing = $order->getBilling();
        $shipping = $order->getShipping();
        $this->assertInstanceOf('Konduto\Models\Address', $billing);
        $this->assertInstanceOf('Konduto\Models\Address', $shipping);
        $this->assertAddress($billing, "Mary Jane", "123 Main St.", "Apartment 4",
                             "New York City", "NY", "10460", "US");
        $this->assertAddress($shipping, "Mary Jane", "89 Holly St.", null, "Springfield",
                             "CO", "02955", "US");
    }

    function assertOrderPayments(Order $order) {
        $payment = $order->getPayment();
        $this->assertNotNull($payment);
        $this->assertEquals(3, count($payment));
        $pay0 = $payment[0];
        $pay1 = $payment[1];
        $pay2 = $payment[2];
        $this->assertInstanceOf('Konduto\Models\CreditCard', $pay1);
        $this->assertEquals("123456", $pay1->getBin());
        $this->assertEquals("0987", $pay1->getLast4());
        $this->assertEquals("122020", $pay1->getExpirationDate());
        $this->assertEquals("approved", $pay1->getStatus());
        $this->assertInstanceOf('Konduto\Models\Boleto', $pay2);
        // There is a bug in the API on retrieving Boleto expiration date
        // $this->assertEquals($this->dateUTC("2016-05-23"), $pay2->getExpirationDate());
        $this->assertEquals("pending", $pay2->getStatus());
        $this->assertInstanceOf('Konduto\Models\Payment', $pay0);
        $this->assertEquals(Payment::TYPE_VOUCHER, $pay0->getType());
    }

    function assertOrderWithShoppingCart(Order $order) {
        $cart = $order->getShoppingCart();
        $this->assertNotNull($cart);
        $this->assertEquals(2, count($cart));
        $item0 = $cart[0];
        $item1 = $cart[1];
        $this->assertInstanceOf('Konduto\Models\Item', $item0);
        $this->assertInstanceOf('Konduto\Models\Item', $item1);
        $this->assertShoppingCartItem($item0, "2015-12-10", "9919023", "123456789999", 201,
                                      "Green T-Shirt", "Male Green T-Shirt V Neck", 29.99, 1, null);
        $this->assertShoppingCartItem($item1, "2015-03-10", "0017273", null, 202, "Yellow Socks",
                                      "Pair of Yellow Socks", 7.50, 2, 1.00);
    }

    function assertOrderWithSeller(Order $order) {
        $this->assertDateTime("2018-12-25T12:00:25Z", $order->getPurchasedAt());
        $this->assertDateTime("2018-12-20T15:59:01Z", $order->getFirstMessage());
        $this->assertEquals(9, $order->getMessagesExchanged());
        $seller = $order->getSeller();
        $this->assertInstanceOf('Konduto\Models\Seller', $seller);
        $this->assertEquals("012", $seller->getId());
        $this->assertEquals("Vendedor", $seller->getName());
        $this->assertDate("2015-03-31", $seller->getCreatedAt());
    }

    function assertFlightOrder(Order $order) {
        $travel = $order->getTravel();
        $this->assertInstanceOf('Konduto\Models\Flight', $travel);
        $this->assertInstanceOf('Konduto\Models\FlightLeg', $travel->getDeparture());
        $this->assertInstanceOf('Konduto\Models\FlightLeg', $travel->getReturn());
        $passengers = $travel->getPassengers();
        $this->assertEquals(2, count($passengers));
        $pass0 = $passengers[0];
        $pass1 = $passengers[1];
        $this->assertInstanceOf('Konduto\Models\Passenger', $pass0);
        $this->assertInstanceOf('Konduto\Models\Passenger', $pass1);
        $this->assertEquals("Carlos Siqueira", $pass1->getName());
        $this->assertEquals("XYZ1234", $pass1->getDocument());
        $this->assertEquals("passport", $pass1->getDocumentType());
        $this->assertDate("1970-12-01", $pass1->getDob());
        $this->assertEquals("US", $pass1->getNationality());
        $this->assertInstanceOf('\Konduto\Models\Loyalty', $pass1->getLoyalty());
        $this->assertEquals("multiplus", $pass1->getLoyalty()->getProgram());
        $this->assertFalse($pass1->getFrequentTraveler());
        $this->assertTrue($pass1->getSpecialNeeds());
    }

    function assertBusOrder(Order $order) {
        $travel = $order->getTravel();
        $this->assertInstanceOf('Konduto\Models\BusTravel', $travel);
        $this->assertInstanceOf('Konduto\Models\BusTravelLeg', $travel->getDeparture());
        $passengers = $travel->getPassengers();
        $this->assertEquals(1, count($passengers));
        $pass0 = $passengers[0];
        $this->assertInstanceOf('Konduto\Models\Passenger', $pass0);
        $this->assertEquals("Campinas", $travel->getDeparture()->getDestinationCity());
        $this->assertEquals("São Paulo", $travel->getDeparture()->getOriginCity());
    }

    function assertShoppingCartItem(Item $item, $createdAt, $sku, $productCode, $category,
                                    $name, $description, $unitCost, $quantity, $discount) {
        $this->assertDate($createdAt, $item->getCreatedAt());
        $this->assertEquals($sku, $item->getSku());
        $this->assertEquals($productCode, $item->getProductCode());
        $this->assertEquals($category, $item->getCategory());
        $this->assertEquals($name, $item->getName());
        $this->assertEquals($description, $item->getDescription());
        $this->assertEquals($unitCost, $item->getUnitCost());
        $this->assertEquals($quantity, $item->getQuantity());
        $this->assertEquals($discount, $item->getDiscount());
    }

    function assertAddress(Address $address, $name, $address1, $address2, $city,
                           $state, $zip, $country) {
        $this->assertEquals($name, $address->getName());
        $this->assertEquals($address1, $address->getAddress1());
        $this->assertEquals($address2, $address->getAddress2());
        $this->assertEquals($city, $address->getCity());
        $this->assertEquals($state, $address->getState());
        $this->assertEquals($zip, $address->getZip());
        $this->assertEquals($country, $address->getCountry());
    }

    function assertDate($date1, $date2) {
        $this->assertEquals($date1, date_format($date2, "Y-m-d"));
    }

    function assertDateTime($date1, $date2) {
        $this->assertEquals($date1, date_format($date2, 'Y-m-d\TH:i:s\Z'));
    }
}


