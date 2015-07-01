<?php
require_once "konduto.php";
require_once "vendor/autoload.php";
use Konduto\Models as KondutoModels;
use Konduto\Exceptions as KondutoExceptions;

class SellerTest extends \PHPUnit_Framework_TestCase {

    public function test_seller_okay() {
        $seller_arr = array(
            "id"         => "Loja-A003",
            "name"       => "Loja de especiarias do Japonês",
            "created_at" => "2014-12-01"
        );

        $seller = new KondutoModels\Seller($seller_arr);

        $this->assertTrue($seller->is_valid(), 'Not a valid');
        $this->assertEquals($seller->id(), $seller_arr['id'], 'id');
        $this->assertEquals($seller->name(), $seller_arr['name'], 'name');
        $this->assertEquals($seller->created_at(), $seller_arr['created_at'],
                'created_at');
    }

    public function test_seller_validation() {
        $seller_arr = array(
            "id"         => "Loja-A003",
            "name"       => "Loja de especiarias do Japonês",
            "created_at" => "12-01-2014"
        );

        $seller = new KondutoModels\Seller($seller_arr);

        $this->assertFalse($seller->is_valid(), 'Should not be valid');
        $this->assertArrayHasKey("created_at", $seller->get_errors());
    }

}
