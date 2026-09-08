<?php namespace Konduto\Models;

/**
 * Payment model.
 * @see http://docs.konduto.com Konduto API Spec
 */
class Payment extends BaseModel {

    const TYPE_CREDIT = "credit";
    const TYPE_BOLETO = "boleto";
    const TYPE_DEBIT = "debit";
    const TYPE_TRANSFER = "transfer";
    const TYPE_VOUCHER = "voucher";
    const TYPE_BALANCE = "balance";
    const TYPE_PIX = "pix";

    const STATUS_APPROVED = "approved";
    const STATUS_DECLINED = "declined";
    const STATUS_PENDING  = "pending";

    public static $availableTypes = array(self::TYPE_CREDIT, self::TYPE_BOLETO,
        self::TYPE_DEBIT, self::TYPE_TRANSFER, self::TYPE_VOUCHER, self::TYPE_BALANCE, self::TYPE_PIX);

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("type", "status", "amount", "description", "tax_id", "cvv_result",
            "avs_result", "sha1", "name", "holder", "mcc", "mid", "3ds_id",
            "merchant_tax_id", "voucher_type");
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

                case Payment::TYPE_BOLETO:
                    return new Boleto($array);

                case Payment::TYPE_DEBIT:
                case Payment::TYPE_TRANSFER:
                case Payment::TYPE_VOUCHER:
                case Payment::TYPE_BALANCE:
                case Payment::TYPE_PIX:
                    return new Payment($array);

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
        return $this->set("type", $value);
    }

    public function setStatus($value) {
        return $this->set("status", $value);
    }

    public function setAmount($value) {
        return $this->set("amount", $value);
    }

    public function getAmount() {
        return $this->get("amount");
    }

    public function setDescription($value) {
        return $this->set("description", $value);
    }

    public function getDescription() {
        return $this->get("description");
    }

    public function getTaxId() {
        return $this->get("tax_id");
    }

    public function setTaxId($value) {
        return $this->set("tax_id", $value);
    }

    public function getCvvResult() {
        return $this->get("cvv_result");
    }

    public function setCvvResult($value) {
        return $this->set("cvv_result", $value);
    }

    public function getAvsResult() {
        return $this->get("avs_result");
    }

    public function setAvsResult($value) {
        return $this->set("avs_result", $value);
    }

    public function getSha1() {
        return $this->get("sha1");
    }

    public function setSha1($value) {
        return $this->set("sha1", $value);
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($value) {
        return $this->set("name", $value);
    }

    public function getHolder() {
        return $this->get("holder");
    }

    public function setHolder($value) {
        return $this->set("holder", $value);
    }

    public function getMcc() {
        return $this->get("mcc");
    }

    public function setMcc($value) {
        return $this->set("mcc", $value);
    }

    public function getMid() {
        return $this->get("mid");
    }

    public function setMid($value) {
        return $this->set("mid", $value);
    }

    public function get3dsId() {
        return $this->get("3ds_id");
    }

    public function set3dsId($value) {
        return $this->set("3ds_id", $value);
    }

    public function getMerchantTaxId() {
        return $this->get("merchant_tax_id");
    }

    public function setMerchantTaxId($value) {
        return $this->set("merchant_tax_id", $value);
    }

    public function getVoucherType() {
        return $this->get("voucher_type");
    }

    public function setVoucherType($value) {
        return $this->set("voucher_type", $value);
    }
}
