<?php
require_once "konduto.php";
require_once "vendor/autoload.php";
use Konduto\Core\Konduto as Konduto;
use Konduto\Models as KondutoModels;
use Konduto\Exceptions as KondutoExceptions;

// Testing key from the docs.konduto.com
const LOCAL_EXISTING_KEY = "T738D516F09CAB3A2C1EE";

class ApiTest extends \PHPUnit_Framework_TestCase
{
    public static $testOrder_1 = null;
    public static $testOrder_2 = null;

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
            "totalAmount" => 100.50,
            "customer"     => $c
        ]);

        // Save this order for later...
        self::$testOrder_1 = $o;

        try {
            $success = Konduto::analyze($o);
            $this->assertTrue($success, "Order posted successfully.");

            $this->assertNotNull($o->geolocation(), 'Geolocation');
            $this->assertNotNull($o->device(), 'Device');
            $this->assertNotNull($o->status(), 'Status');
            $this->assertNotNull($o->recommendation(), 'recommendation');
            $this->assertInstanceOf('Konduto\models\Geolocation', $o->geolocation());
            $this->assertInstanceOf('Konduto\models\device', $o->device());
        }
        catch (Exception $e) {
            echo "\n-- Exception message: " . $e->getMessage();
            echo "\n-- Last response: " . Konduto::getLastResponse();
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
        $this->assertNotNull($o2->device(), 'Device');
        $this->assertNotNull($o2->status(), 'Status');
        $this->assertNotNull($o2->recommendation(), 'recommendation');
        $this->assertInstanceOf('Konduto\models\Device', $o2->device());
        $this->assertNotNull($o2->geolocation(), 'Geolocation');
        $this->assertInstanceOf('Konduto\models\Geolocation', $o2->geolocation());
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
            $this->assertTrue($success, "Success needs to be true!");
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
    ///// Since EngineSimulator ignores flag analyze=false, this test does not make sense in test environment.
    //
    // public function testPutNotAnalyzedOrder() {
    //     // When trying to update status of an order sent with analyze=false flag,
    //     // it should raise a OperationNotAllowedException
    //     Konduto::updateOrderStatus(999, KondutoModels\STATUS_APPROVED, "Trying to approve this order.");
    //     // Konduto::updateOrderStatus(self::$testOrder_2->id(), KondutoModels\STATUS_APPROVED, "Trying to approve this order.");
    // }

    private function generateUniqueID() {
        $id = str_replace(" ", "", "TestOrder" . microtime());
        $id = str_replace(".", "", $id);
        $id = str_replace(",", "", $id);
        return $id;
    }

}
