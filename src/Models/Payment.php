<?php namespace Konduto\Models;

const PAYMENT_APPROVED = "approved";
const PAYMENT_DECLINED = "declined";
const PAYMENT_PENDING  = "pending";

abstract class Payment extends Model {

    const TYPE_CARD = "credit";
    const TYPE_BOLETO = "boleto";

    protected $available_types = array(self::TYPE_CARD, self::TYPE_BOLETO);

    /**
     * Given an array, instantiates a payment among the possible
     * types of payments. The decision of what Model to use is made
     * with field 'type'
     * @param $array_of_args: array containing fields of the Payment
     * @return a CreditCard or Boleto object
     */
    public static function instantiate($array_of_args) {
        if (is_array($array_of_args)
            && array_key_exists("type", $array_of_args)) {
            $type = $array_of_args["type"];
            unset($array_of_args["type"]);
            switch ($type) {
                case Payment::TYPE_CARD:
                    return new CreditCard($array_of_args);
                    break;

                case Payment::TYPE_BOLETO:
                    return new Boleto($array_of_args);
                    break;

                default:
                    return null;
            }
        }
        else {
            return null;
        }
    }
}
