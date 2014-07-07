<?php
require_once "konduto.php";
require_once "vendor/autoload.php";
use konduto\models as KondutoModels;
use konduto\exceptions as KondutoExceptions;

const LOCAL_EXISTING_KEY = "T01234567890123456789";

class ApiTest extends PHPUnit_Framework_TestCase
{
    public static $testOrder_1 = "";

    /**
     * This test passes when the following exception is thrown.
     * @expectedException        konduto\exceptions\InvalidAPIKeyException
     */
    public function testOperationWithNoKeySet()
    {
        // No API key was set
        Konduto::getOrder("Pedido00001");
    }

    /**
     * This test passes when the following exception is thrown.
     * @expectedException        konduto\exceptions\InvalidAPIKeyException
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
     * @expectedException        konduto\exceptions\InvalidVersionException
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
            "total_amount" => 100.50,
            "customer"     => $c
        ]);

        // Save this order for later...
        self::$testOrder_1 = $o;

        try {
            $success = Konduto::analyze($o);
            $this->assertTrue($success, "Order posted successfully.");

            $this->assertNotNull($o->geolocation(), 'Geolocation');
            $this->assertInstanceOf('konduto\models\Geolocation', $o->geolocation());
        }
        catch (Exception $e) {
            echo "\n-- Exception message: " . $e->getMessage();
            $this->fail("No exception should be thrown.");
        }
    }

    /**
     * @depends             testSimplePost
     * @expectedException   konduto\exceptions\DuplicateOrderException
     */
    public function testDuplicateOrder() {
        // Try to post the same order again.
        $o = self::$testOrder_1;
        Konduto::analyze($o);
    }

    /**
     * @depends             testSimplePost
     */
    public function testSimpleGet() {
        // Try to post the same order again.
        $o = self::$testOrder_1;
        $o2 = Konduto::getOrder($o->id());

        // o2 needs to be an Order
        $this->assertInstanceOf('konduto\models\Order', $o2);

        // Assert the objects for geolocation, status, recommendation and device are populated
        $this->assertNotNull($o2->device(), 'Device');
        $this->assertNotNull($o2->status(), 'Status');
        $this->assertNotNull($o2->recommendation(), 'recommendation');
        $this->assertInstanceOf('konduto\models\Device', $o2->device());
        $this->assertNotNull($o2->geolocation(), 'Geolocation');
        $this->assertInstanceOf('konduto\models\Geolocation', $o2->geolocation());
    }


    /**
     * @depends             testSimplePost
     */
    public function testSimplePut() {
        // Try to post the same order again.
        $o = self::$testOrder_1;
        try {
            $success = Konduto::updateOrderStatus($o->id(), KondutoModels\STATUS_APPROVED, "I will approve this freaking order despite the recommendation! mmmhuahuaha");
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

    private function generateUniqueID() {
        $id = str_replace(" ", "", "TestOrder" . microtime());
        $id = str_replace(".", "", $id);
        $id = str_replace(",", "", $id);
        return $id;
    }

}
