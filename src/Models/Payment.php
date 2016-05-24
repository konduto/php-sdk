<?php namespace Konduto\Models;

class Payment extends BaseModel {

    const TYPE_CREDIT = "credit";
    const TYPE_BOLETO = "boleto";
    const TYPE_DEBIT = "debit";
    const TYPE_TRANSFER = "transfer";
    const TYPE_VOUCHER = "voucher";

    const STATUS_APPROVED = "approved";
    const STATUS_DECLINED = "declined";
    const STATUS_PENDING  = "pending";

    public static $availableTypes = array(self::TYPE_CREDIT, self::TYPE_BOLETO,
        self::TYPE_DEBIT, self::TYPE_TRANSFER, self::TYPE_VOUCHER);

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("type", "status");
    }

    /**
     * Given an array, instantiates a payment among the possible
     * types of payments. The decision of what Model to use is made
     * by field 'type'
     * @param $array: array containing fields of the Payment
     * @return Payment CreditCard or Boleto object
     */
    public static function build(array $array) {
        if (array_key_exists("type", $array) && in_array($array["type"], self::$availableTypes)) {
            switch ($array["type"]) {
                case Payment::TYPE_CREDIT:
                    return new CreditCard($array);
                    break;

                case Payment::TYPE_BOLETO:
                    return new Boleto($array);
                    break;

                case Payment::TYPE_DEBIT:
                case Payment::TYPE_TRANSFER:
                case Payment::TYPE_VOUCHER:
                    return new Payment($array);
                    break;

                default:  // Exception
            }
        }
        throw new \InvalidArgumentException("Array must contain a valid 'type' field");
    }

    public function getType() {
        return $this->get("type");
    }

    public function getStatus() {
        return $this->get("status");
    }

    public function setType($value) {
        $this->set("type", $value);
        return $this;
    }

    public function setStatus($value) {
        $this->set("status", $value);
        return $this;
    }
}
