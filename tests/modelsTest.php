<?php
require_once "konduto.php";
require_once "vendor/autoload.php";
use Konduto\Models as KondutoModels;
use Konduto\Exceptions as KondutoExceptions;

class ModelsTest extends \PHPUnit_Framework_TestCase
{
    public static $testOrder_2 = null;

    /**
     * Tests for the creation of an order object using a big array
     */
    public function testFullOrderWithArray()
    {
        $o = new KondutoModels\Order([
            "id"           => "Order-90125",
            "totalAmount"  => 312.71,
            "ip"           => "221.102.39.19",
            "customer"     => 
            [
                "id"    => "Customer n03",
                "name"  => "Hiroyuki Endo",
                "email" => "endo.hiroyuki@yahoo.jp"
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
                    "status" => "declined",
                    "bin" => "490231",
                    "last4"=> "0231",
                    "expiration_date" => "082016"
                ]
            ],
            "billing"      => 
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
                "name" => "Mary Jane",
                "address1" => "123 Main St.",
                "address2" => "Apartment 4",
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

        $isValid = $o->isValid();        


        if (!$isValid) {
            ob_start();
            var_dump($o->getErrors());
            $errors = ob_get_contents();
            ob_end_clean();
            $this->fail("The test failed because there were errors in the order: \n" . $errors);
        }
        else {
            $this->assertTrue($isValid);
        }
        // var_dump($o);
    }


    /**
     * Tests for an Order containing an error for an illegal ID property.
     */
    public function testPatternValidationOrderID() {
        $o = new KondutoModels\Order();
        // Set an ID that don't respect pattern (contains space);
        $o->id("Pedido 00001");
        $this->assertTrue(array_key_exists('id', $o->getErrors()), "The 'id' key should be present in errors.");

        // Now set an ID that respect pattern, and check if the error's gone.
        $o->id("Pedido00001");
        $this->assertFalse(array_key_exists('id', $o->getErrors()), "Now the 'id' key shouldn't be present.");
    }

    /**
     * Test pattern for Order ip.
     * @depends         testPatternValidationOrderID
     */
    public function testValidationOrderIP() {
        $o = new KondutoModels\Order();
        // Invalid IP.
        $o->ip("192.168.0.256");
        $this->assertTrue(array_key_exists('ip', $o->getErrors()), "The 'ip' key should be present in errors.");

        // Now there should be no error.
        $o->ip("192.168.0.255");
        $this->assertFalse(array_key_exists('ip', $o->getErrors()), "Now the 'ip' key shouldn't be present.");
    }

    public function testCustomer() {
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

    public function testAddress() {
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

    public function testShoppingCart() {
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

    public function testNavigation() {
        $navInfo = [
            "referrer"              => "http://www.google.com?q=buy+shirt",
            "session_time"          => 12,
            "time_site_1d"          => 13,
            "new_accounts_1d"       => 0,
            "password_resets_1d"    => 1,
            "sales_declined_1d"     => 2,
            "sessions_1d"           => 3,
            "time_site_7d"          => 4,
            "time_per_page_7d"      => 5,
            "new_accounts_7d"       => 6,
            "password_resets_7d"    => 7,
            "checkout_count_7d"     => 8,
            "sales_declined_7d"     => 9,
            "sessions_7d"           => 10,
            "time_since_last_sale"  => 11
        ];

        $navModel = new KondutoModels\Navigation($navInfo);
        
        $this->assertContains($navModel->sessionTime(), $navInfo);
        $this->assertContains($navModel->referrer(), $navInfo);
        $this->assertContains($navModel->timeSite1d(), $navInfo);
        $this->assertContains($navModel->newAccounts1d(), $navInfo);
        $this->assertContains($navModel->passwordResets1d(), $navInfo);
        $this->assertContains($navModel->salesDeclined1d(), $navInfo);
        $this->assertContains($navModel->timeSite7d(), $navInfo);
        $this->assertContains($navModel->timePerPage7d(), $navInfo);
        $this->assertContains($navModel->newAccounts7d(), $navInfo);
        $this->assertContains($navModel->passwordResets7d(), $navInfo);
        $this->assertContains($navModel->checkoutCount7d(), $navInfo);
        $this->assertContains($navModel->salesDeclined7d(), $navInfo);
        $this->assertContains($navModel->sessions7d(), $navInfo);
        $this->assertContains($navModel->timeSinceLastSale(), $navInfo);
    }

    public function testGetStatus1() {
        $order = [
            "id"          => "Pedido100001834",
            "visitor"     => "da39a3ee5e6b4b0d3255bfef95601890afd80709",
            "totalAmount" => 312.71,
            "currency"    => "BRL",
            "customer"    => [
                "id"     => "Customer n03",
                "name"   => "Hiroyuki Endo",
                "email"  => "endo.hiroyuki@yahoo.jp"
            ],
            "recommendation" => KondutoModels\RECOMMENDATION_APPROVE
        ];

        $orderObj = new KondutoModels\Order($order);
        
        // 
        $this->assertContains($orderObj->status(),
                KondutoModels\STATUS_APPROVED, 
                "Status should be 'approved': ".
                " We have a recommendation 'approve' ".
                "and no available payment.");
    }

    public function testGetStatus2() {
        $order = [
            "id"          => "Pedido100001834",
            "visitor"     => "da39a3ee5e6b4b0d3255bfef95601890afd80709",
            "totalAmount" => 312.71,
            "currency"    => "BRL",
            "customer"    => [
                "id"     => "Customer n03",
                "name"   => "Hiroyuki Endo",
                "email"  => "endo.hiroyuki@yahoo.jp"
            ],
            "payment" => [
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
            ],
            "recommendation" => KondutoModels\RECOMMENDATION_APPROVE
        ];

        $orderObj = new KondutoModels\Order($order);

        $this->assertContains($orderObj->status(),
                KondutoModels\STATUS_NOT_AUTHORIZED, 
                "Here status should be 'not_authorized': ".
                "We have a recommendation 'approve' and but a payment ".
                "was declined.");
    }
}
