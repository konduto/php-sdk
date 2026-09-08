<?php namespace Konduto\Tests;

use Konduto\Models\Delivery;
use Konduto\Models\Device;
use Konduto\Models\Item;
use Konduto\Models\Tenant;

class PayloadFieldAlignmentTest extends \PHPUnit_Framework_TestCase {

    function test_deliverySnakeCaseFields() {
        $delivery = new Delivery();
        $delivery->setDeliveryCompany("FastShip")
            ->setDeliveryMethod("express")
            ->setEstimatedShippingDate("2026-09-08")
            ->setEstimatedDeliveryDate("2026-09-09");

        $arr = $delivery->toJsonArray();
        $this->assertArrayHasKey("delivery_company", $arr);
        $this->assertArrayHasKey("delivery_method", $arr);
        $this->assertArrayHasKey("estimated_shipping_date", $arr);
        $this->assertArrayHasKey("estimated_delivery_date", $arr);
        $this->assertArrayNotHasKey("deliveryCompany", $arr);
        $this->assertArrayNotHasKey("deliveryMethod", $arr);
        $this->assertArrayNotHasKey("estimatedShippingDate", $arr);
        $this->assertArrayNotHasKey("estimatedDeliveryDate", $arr);
    }

    function test_deviceExtraFields() {
        $device = new Device(array(
            "fingerprint" => "abc",
            "provider" => "provider-x",
            "category" => "mobile",
            "model" => "xpto",
            "manufacturer" => "vendor",
            "os" => "android"
        ));

        $arr = $device->toJsonArray();
        $this->assertEquals("provider-x", $arr["provider"]);
        $this->assertEquals("mobile", $arr["category"]);
        $this->assertEquals("xpto", $arr["model"]);
        $this->assertEquals("vendor", $arr["manufacturer"]);
        $this->assertEquals("android", $arr["os"]);
    }

    function test_itemExtraFields() {
        $item = new Item(array(
            "sku" => "sku1",
            "deliveryType" => "instant",
            "deliverySlaInMinutes" => 30,
            "sellerId" => "seller-1",
            "image" => "https://example.com/item.png"
        ));

        $arr = $item->toJsonArray();
        $this->assertEquals("instant", $arr["deliveryType"]);
        $this->assertEquals(30, $arr["deliverySlaInMinutes"]);
        $this->assertEquals("seller-1", $arr["sellerId"]);
        $this->assertEquals("https://example.com/item.png", $arr["image"]);
    }

    function test_tenantModel() {
        $tenant = new Tenant(array(
            "id" => "tenant-1",
            "name" => "Tenant Name",
            "created_at" => "2026-09-08"
        ));

        $arr = $tenant->toJsonArray();
        $this->assertEquals("tenant-1", $arr["id"]);
        $this->assertEquals("Tenant Name", $arr["name"]);
        $this->assertEquals("2026-09-08", $arr["created_at"]);
    }
}

