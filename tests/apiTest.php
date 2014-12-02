<?php
require_once "konduto.php";
require_once "vendor/autoload.php";
use Konduto\Core\Konduto as Konduto;
use Konduto\Models as KondutoModels;
use Konduto\Exceptions as KondutoExceptions;

const LOCAL_EXISTING_KEY = "T738D516F09CAB3A2C1EE";

class ApiTest extends \PHPUnit_Framework_TestCase
{
    public static $testOrder_1 = null;
    public static $testOrder_2 = null;
    public static $testOrder_3 = null;

    /**
     * This test passes when the following exception is thrown.
     * @expectedException        Konduto\exceptions\InvalidAPIKeyException
     */
    public function testOperationWithNoKeySet()
    {
        // No API key was set
        Konduto::getOrder("Pedido00001");
    }

    /**
     * This test passes when the following exception is thrown.
     * @expectedException        Konduto\exceptions\InvalidAPIKeyException
     */
    public function testInvalidAPIKey()
    {
        Konduto::setApiKey("for sure this is an invalid key");
    }

    public function testValidAPIKey()
    {
        // Now it is a valid API key
        $is_valid = Konduto::setApiKey(LOCAL_EXISTING_KEY);
        $this->assertTrue($is_valid, "If the API key is valid, setApiKey returns true.");
    }

    /**
     * This test passes when the following exception is thrown.
     * @expectedException        Konduto\exceptions\InvalidVersionException
     */
    public function testSetInvalidVersion()
    {
        $is_valid = Konduto::setVersion("v0.087");
    }

    /**
     * Uses a small number of fields to test Post.
     */
    public function testSimplePost()
    {
        Konduto::setApiKey(LOCAL_EXISTING_KEY);        

        $c = new KondutoModels\Customer([
            "id"    => "Customer n01",
            "name"  => "Homer Simpson",
            "email" => "h.simpson@gmail.com"
        ]);

        $o = new KondutoModels\Order([
            "id"           => self::generateUniqueID(),
            "totalAmount"  => 100.50,
            "customer"     => $c,
            "ip"           => "95.31.110.43",   // We need to provide an IP for having a geolocation returned
            "visitor"      => "1234567890123456789012345678901234567890" // We need to provide visitor for having navigation info returned
        ]);

        // Save this order for later...
        self::$testOrder_1 = $o;

        try {
            $success = Konduto::analyze($o);
            $this->assertTrue($success, "Order posted successfully.");

            $this->assertNotNull($o->geolocation(), 'Geolocation');
            // $this->assertNotNull($o->device(), 'Device');
            $this->assertNotNull($o->status(), 'Status');
            $this->assertNotNull($o->recommendation(), 'recommendation');
            // $this->assertNotNull($o->navigation(), 'navigation');
            $this->assertInstanceOf('Konduto\Models\Geolocation', $o->geolocation());
            $this->assertInstanceOf('Konduto\Models\Navigation', $o->navigation());
            // $this->assertInstanceOf('Konduto\models\device', $o->device());
        }
        catch (Exception $e) {
            echo "\n-- Exception message: " . $e->getMessage();
            echo "\n-- Last response: " . Konduto::getLastResponse();
            // echo "\n-- Var_dump(order): ";
            $this->fail("No exception should be thrown.");
        }
    }

    /**
     * @depends             testSimplePost
     * @expectedException   Konduto\exceptions\DuplicateOrderException
     */
    public function testDuplicateOrder() {
        // Try to post the same order again.
        $o = self::$testOrder_1;
        Konduto::analyze($o);
    }

    /**
     * @expectedException   Konduto\exceptions\OrderNotFoundException
     */
    public function testGetNonExistentOrder() {
        Konduto::getOrder("OrderD035N073X157");
    }

    /**
     * @expectedException   Konduto\exceptions\OrderNotFoundException
     */
    public function testPutNonExistentOrder() {
        Konduto::updateOrderStatus("OrderD035N073X157", KondutoModels\STATUS_FRAUD);
    }

    /**
     * @depends             testSimplePost
     * @expectedException   Konduto\exceptions\InvalidOrderException
     */
    public function testPutInvalidStatus() {
        Konduto::updateOrderStatus(self::$testOrder_1->id(), 'unknown');
    }

    /**
     * @depends             testSimplePost
     */
    public function testSimpleGet() {
        // Try to post the same order again.
        $o = self::$testOrder_1;
        $o2 = Konduto::getOrder($o->id());

        // o2 needs to be an Order
        $this->assertInstanceOf('Konduto\models\Order', $o2);

        // Assert the objects for geolocation, status, recommendation and device are populated
        $this->assertNotNull($o2->status(), 'Status');
        $this->assertNotNull($o2->recommendation(), 'recommendation');
        $this->assertNotNull($o2->geolocation(), 'Geolocation');
        $this->assertInstanceOf('Konduto\models\Geolocation', $o2->geolocation());
        // These 2 lines are commented because we cannot generate a device without providing a visitor id with behaviour
        // $this->assertNotNull($o2->device(), 'Device');  
        // $this->assertInstanceOf('Konduto\models\Device', $o2->device());
    }


    /**
     * @depends             testSimplePost
     */
    public function testSimplePut() {
        // Try to post the same order again.
        $o = self::$testOrder_1;
        try {
            $success = Konduto::updateOrderStatus($o->id(), KondutoModels\STATUS_APPROVED);
            $this->assertTrue($success, "Success needs to be true!");
            // Get the order, and check if the status is really updated
            $o2 = Konduto::getOrder($o->id());
            $this->assertEquals($o2->status(), KondutoModels\STATUS_APPROVED, "status property should be the same set.");
        }
        catch (Exception $e) {
            echo "\n-- Exception message: " . $e->getMessage();
            $this->fail("No exception should be thrown.");
        }
    }


    /**
     * Test sendOrder method. Just send an order with analyze=false flag.
     */
    public function testSendOrder() {
        $c = new KondutoModels\Customer([
            "id"    => "Customer n02",
            "name"  => "Bart Simpson",
            "email" => "bart.simpson@gmail.com"
        ]);

        $o = new KondutoModels\Order([
            "id"           => self::generateUniqueID(),
            "total_amount" => 139.22,
            "customer"     => $c
        ]);

        self::$testOrder_2 = $o;

        try {
            $success = Konduto::sendOrder($o);
            // There should be no geolocation, status, recommendation or device properties set.
            $this->assertNull($o->device());
            $this->assertNull($o->status());
            $this->assertNull($o->recommendation());
            $this->assertNull($o->geolocation());
        }
        catch (Exception $e) {
            echo "\n-- Exception message: " . $e->getMessage();
            $this->fail("No exception should be thrown.");
        }
    }

    /**
     * @depends                 testSendOrder
     * @expectedException       Konduto\exceptions\OperationNotAllowedException
     */
    /// Since EngineSimulator ignores flag analyze=false, this test does not make sense in test environment.    
    public function testPutNotAnalyzedOrder() {
        // When trying to update status of an order sent with analyze=false flag,
        // it should raise a OperationNotAllowedException
        // Konduto::updateOrderStatus(999, KondutoModels\STATUS_APPROVED, "Trying to approve this order.");
        Konduto::updateOrderStatus(self::$testOrder_2->id(), KondutoModels\STATUS_APPROVED, "Trying to approve this order.");
    }

    /**
     * @depends                 testSimplePost
     */
    public function testFullPost() {
        $o = new KondutoModels\Order([
            "id"           => self::generateUniqueID(),
            "visitor"      => "da39a3ee5e6b4b0d3255bfef95601890afd80709",
            "totalAmount"  => 312.71,
            "shippingAmount" => 20.00,
            "taxAmount"     => 9.50,
            "currency"     => "USD",
            "installments" => 2,
            "ip"           => "221.102.39.19",
            "customer"     => 
            [
                "id"     => "Customer n03",
                "name"   => "Hiroyuki Endo",
                "email"  => "endo.hiroyuki@yahoo.jp",
                "tax_id" => "XJ0000JX",
                "phone1" => "151520030",
                "phone2" => "151721295",
                "new"    => true,
                "vip"    => true
            ],
            "payment"      => 
            [
                [
                    "bin" => "490172",
                    "last4"=> "0012",
                    "expiration_date" => "072015",
                    "status" => "approved"
                ],
                [
                    "status" => "approved",
                    "bin" => "490231",
                    "last4"=> "0231",
                    "expiration_date" => "082016",
                    "status" => "declined"
                ]
            ]
,            "billing"      => 
            [
                "name" => "Mary Jane",
                "address1" => "123 Main St.",
                "address2" => "Apartment 4",
                "city" => "New York City",
                "state" => "NY",
                "zip" => "10460",
                "country" => "US"
            ],
            "shipping"     => 
            [
                "name" => "Charlotte Fitzroy",
                "address1" => "123 Main St.",
                "address2" => "Apartment 6",
                "city" => "New York City",
                "state" => "NY",
                "zip" => "10460",
                "country" => "US"
            ],
            "shopping_cart" => 
            [
                [
                    "sku" => "9919023",
                    "product_code" => 1231,
                    "category" => 201,
                    "name" => "Green T-Shirt",
                    "description" => "Male Green T-Shirt V Neck",
                    "unit_cost" => 1999.99,
                    "quantity" => 1
                ],
                [
                    "sku" => "0017273",
                    "category" => 1231,
                    "name" => "Yellow Socks",
                    "description" => "Pair of Yellow Socks",
                    "unit_cost" => 29.90,
                    "quantity" => 2,
                    "discount" => 5.00
                ]
            ]
        ]);

        $this->assertTrue($o->isValid());

        try {
            Konduto::analyze($o);

            $this->assertNotNull($o->geolocation(), 'geolocation');
            $this->assertNotNull($o->status(), 'status');
            $this->assertNotNull($o->recommendation(), 'recommendation');
            $this->assertNotNull($o->navigation(), 'navigation');
            $this->assertInstanceOf('Konduto\Models\Geolocation', $o->geolocation());
            $this->assertInstanceOf('Konduto\Models\Navigation', $o->navigation());

            self::$testOrder_3 = $o;
        }
        catch (Exception $e) {
            echo "\n-- Exception message: " . $e->getMessage();
            echo "\n-- Last response: " . Konduto::getLastResponse();
            // echo "\n-- Var_dump(order): ";
            $this->fail("No exception should be thrown.");
        }
    }

    /**
     * @depends                 testFullPost
     */
    public function testFullGet() {
        $o = self::$testOrder_3;
        $o2 = Konduto::getOrder($o->id());

        $this->assertEquals($o, $o2, "All the fields of both objects should contain the same values.");
    }


    private function generateUniqueID() {
        $id = str_replace(" ", "", "TestOrder" . microtime());
        $id = str_replace(".", "", $id);
        $id = str_replace(",", "", $id);
        print_r("Generated ID: " + $id);
        return $id;
    }

}
