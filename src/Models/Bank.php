<?php namespace Konduto\Models;

class Bank extends BaseModel {

    const PIX_CPF = "pix_cpf";
    const PIX_CNPJ = "pix_cnpj";
    const PIX_PHONE = "pix_phone";
    const PIX_EMAIL = "pix_email";
    const PIX_EVP = "pix_evp";
    const P2P = "p2p";
    const NONE = "none";


    public static $availableKeyTypes = array(self::PIX_CPF, self::PIX_CNPJ,
        self::PIX_PHONE, self::PIX_EMAIL, self::PIX_EVP, self::P2P, self::NONE);

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("id", "key_type", "key_value", "holder_name", "holder_tax_id",
            "bank_code", "bank_name", "bank_branch", "bank_account");
    }

    /**
     * @return Bank
     */
    public static function build(array $array) {
        if (array_key_exists("key_type", $array) && in_array($array["key_type"], self::$availableKeyTypes)) {
            switch ($array["key_type"]) {
                case Bank:: PIX_CPF:
                case Bank:: PIX_CNPJ:
                case Bank:: PIX_PHONE:
                case Bank:: PIX_EMAIL:
                case Bank:: PIX_EVP:
                case Bank:: P2P:
                case Bank:: NONE:
                    return new Bank($array);
                    break;

                default:  // Exception
            }
        }
        throw new \InvalidArgumentException("Array must contain a valid 'key_type' field");
    }

    public function getId() {
        return $this->get("id");
    }

    public function setId($value) {
        return $this->set("id", $value);
    }

    public function getKeyType() {
        return $this->get("key_type");
    }

    public function setKeyType($value) {
        return $this->set("Key_type", $value);
    }

    public function getKeyValue() {
        return $this->get("key_value");
    }

    public function setKeyValue($value) {
        return $this->set("Key_value", $value);
    }

    public function getHolderName() {
        return $this->get("holder_name");
    }

    public function setHolderName($value) {
        return $this->set("holder_name", $value);
    }

    public function getHolderTaxId() {
        return $this->get("holder_tax_id");
    }

    public function setHolderTaxId($value) {
        return $this->set("holder_tax_id", $value);
    }

    public function getBankCode() {
        return $this->get("bank_code");
    }

    public function setBankCode($value) {
        return $this->set("bank_code", $value);
    }

    public function getBankName() {
        return $this->get("bank_name");
    }

    public function setBankName($value) {
        return $this->set("bank_name", $value);
    }

    public function getBankBranch() {
        return $this->get("bank_branch");
    }

    public function setBankBranch($value) {
        return $this->set("bank_branch", $value);
    }

    public function getBankAccount() {
        return $this->get("bank_account");
    }

    public function setBankAccount($value) {
        return $this->set("bank_account", $value);
    }

}