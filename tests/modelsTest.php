<?php
require_once "konduto.php";
require_once "vendor/autoload.php";
use Konduto\Models as KondutoModels;
use Konduto\Exceptions as KondutoExceptions;

// const curr = "T01234567890123456789";

class ModelsTest extends PHPUnit_Framework_TestCase
{
    public static $testOrder_2 = null;

    /**
     * Tests for an Order containing an error for an illegal ID property.
     */
    public function testPatternValidationOrderID()
    {
        $o = new KondutoModels\Order();
        // Set an ID that don't respect pattern (contains space);
        $o->id("Pedido 00001");
        $errors = $o->getErrors();
        $this->assertTrue(array_key_exists('id', $errors), "The 'id' key should be present in errors.");

        // Now set an ID that respect pattern, and check if the error's gone.
        $o->id("Pedido00001");
        $errors = $o->getErrors();
        $this->assertFalse(array_key_exists('id', $errors), "Now the 'id' key shouldn't be present.");
    }

    /**
     * Test pattern for Order ip.
     * @depends         testPatternValidationOrderID
     */
    public function testValidationOrderIP()
    {
        $o = new KondutoModels\Order();
        // Invalid IP.
        $o->ip("192.168.0.256");
        $errors = $o->getErrors();
        $this->assertTrue(array_key_exists('ip', $errors), "The 'ip' key should be present in errors.");

        // Now there should be no error.
        $o->ip("192.168.0.255");
        $errors = $o->getErrors();
        $this->assertFalse(array_key_exists('ip', $errors), "Now the 'id' key shouldn't be present.");
    }

    public function testCustomer()
    {
        $c = new KondutoModels\Customer([
            'id'     => 'cus123456',
            'name'   => 'Tyrion Lanister',
            'email'  => 'imp@lannister.com.kl',
            'vip'    => true,
            'new'    => true,
            'phone1' => '(11) 98756789',
            'phone2' => '(11) 98756710',
            'taxId' => '192.102.021-12'
]       );
        $this->assertTrue($c->isValid(), "There should be no errors.");
        // All the fields should be not null.
        $this->assertNotNull($c->id());
        $this->assertNotNull($c->name());
        $this->assertNotNull($c->email());
        $this->assertNotNull($c->isNew());
        $this->assertNotNull($c->isVip());
        $this->assertNotNull($c->email());
        $this->assertNotNull($c->taxId());
        // Two phones were set
        $this->assertCount(2, $c->phones());
    }  

    public function testAddress()
    {
        $addr = new KondutoModels\Address();
        $addr->address1("Via Volvera, 14");
        $addr->address2("Appartamento 6");
        $addr->city("Torino");
        $addr->state("Piemonte");
        $addr->country("IT");
        $addr->zip("10141");

        $this->assertTrue($addr->isValid(), "There should be no errors.");
        // All the fields should be not null.
        $this->assertNotNull($addr->address1());
        $this->assertNotNull($addr->address2());
        $this->assertNotNull($addr->city());
        $this->assertNotNull($addr->state());
        $this->assertNotNull($addr->country());
        $this->assertNotNull($addr->zip());
    }

    public function testShoppingCart()
    {
        $item = new KondutoModels\Item([
            "sku"           => "9919023",
            "productCode"   => "123456789999",
            "category"      => 201,
            "name"          => "Green T-Shirt",
            "description"   => "Male Green T-Shirt V Neck",
            "unitCost"      => 199.99,
            "quantity"      => 1
        ]);

        $item2 = new KondutoModels\Item([
            "sku"           => "0017273",
            "category"      => 202,
            "name"          => "Yellow Socks",
            "description"   => "Pair of Yellow Socks",
            "unit_cost"     => 29.99,
            "discount"      => 5.0,
            "quantity"      => 2
        ]);

        $o = new KondutoModels\Order();
        $o->addItem($item);
        $o->addItem($item2);
        
        // All the fields should be not null.
        $this->assertNotNull($item->sku());
        $this->assertNotNull($item->product_code());
        $this->assertNotNull($item->category());
        $this->assertNotNull($item->name());
        $this->assertNotNull($item->description());
        $this->assertNotNull($item->quantity());
        $this->assertNotNull($item2->discount());
        $this->assertCount(2, $o->shopping_cart());
    }
}
