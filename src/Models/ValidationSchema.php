<?php namespace Konduto\Models;

const TYPE_BOOL   = "boolean";
const TYPE_INT    = "integer";
const TYPE_STRING = "string";
const TYPE_FLOAT  = "double";

const I_TYPE    = 0;
const I_MIN     = 1;
const I_MAX     = 2;
const I_PATTERN = 3;

const REGEX_LETTERS_DIGITS = "/^[a-zA-Z0-9-_]+\z/";
const REGEX_LETTERS        = "/^[a-zA-Z]+\z/";
const REGEX_UPPERCASE      = "/^[A-Z]+\z/";
const REGEX_DIGITS         = "/^[0-9]+\z/";
const REGEX_HEXA_DIGITS    = "/^[a-fA-F0-9]+\z/";
const REGEX_DATE           = "/^\d{4}-\d{2}-\d{2}\z/";
const REGEX_DATETIME       = "/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}Z\z/";
const REGEX_DATETIME_SECS  = "/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z\z/";
const REGEX_IPv4           = "/^(?:(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]?|[0-9])\z/";
const REGEX_CREDIT         = "/^credit\z/";
const REGEX_BOLETO         = "/^boleto\z/";
const REGEX_TRAVEL_TYPE    = "/^(flight|bus)\z/";
const REGEX_DOCUMENT_TYPE  = "/^(passport|id)\z/";
const REGEX_TRAVEL_CLASS   = "/^(economy|business|first)\z/";

abstract class ValidationSchema {

    /**
     * This structure $validation follows the pattern:
     * object => [
     *      field => [type, minimum, maximum, pattern],
     *      field => ...
     * ]
     *
     * 'type' accepts one of the constants defined above: TYPE_BOOL, TYPE_INT, TYPE_STRING and TYPE_FLOAT.
     * For type TYPE_STRING, the 2nd and 3rd positions, 'minimum' and 'maximum', refer to the number of character.
     * For types TYPE_INT and TYPE_FLOAT, the 2nd and 3rd positions, 'minimum' and 'maximum', refer to minimum and max numbers allowed.
     * For type TYPE_BOOL, positions 'minimum' and 'maximum' are not used.
     * Optionally, it could have a IDX_PATTERN key containing a regex expression to be applied.
     */
    private static $validation = array(
        "order" => array(
            "id"                 => array(TYPE_STRING,  1,     100, REGEX_LETTERS_DIGITS),
            "visitor"            => array(TYPE_STRING,  0,     100, REGEX_LETTERS_DIGITS),
            "total_amount"       => array( TYPE_FLOAT,  0, 9999999),
            "shipping_amount"    => array( TYPE_FLOAT,  0, 9999999),
            "tax_amount"         => array( TYPE_FLOAT,  0, 9999999),
            "currency"           => array(TYPE_STRING,  3,       3, REGEX_UPPERCASE),
            "installments"       => array(   TYPE_INT,  1,     999),
            "ip"                 => array(TYPE_STRING,  7,      15, REGEX_IPv4),
            "first_message"      => array(TYPE_STRING,  1,      20, REGEX_DATETIME_SECS),
            "purchased_at"       => array(TYPE_STRING,  1,      20, REGEX_DATETIME_SECS),
            "messages_exchanged" => array(   TYPE_INT,  0, 9999999)
        ),
        "customer" => array(
            "id"              => array(TYPE_STRING,  0,     100),
            "name"            => array(TYPE_STRING,  0,     100),
            "tax_id"          => array(TYPE_STRING,  0,     100),
            "phone1"          => array(TYPE_STRING,  0,     100),
            "phone2"          => array(TYPE_STRING,  0,     100),
            "email"           => array(TYPE_STRING,  0,     100),
            "new"             => array(TYPE_BOOL),
            "vip"             => array(TYPE_BOOL),
            "dob"             => array(TYPE_STRING,  8,      10, REGEX_DATE),
            "created_at"      => array(TYPE_STRING,  8,      10, REGEX_DATE)
        ),
        "address" => array(
            "name"            => array(TYPE_STRING,  0,     100),
            "address1"        => array(TYPE_STRING,  0,     255),
            "address2"        => array(TYPE_STRING,  0,     255),
            "city"            => array(TYPE_STRING,  0,     100),
            "state"           => array(TYPE_STRING,  0,     100),
            "zip"             => array(TYPE_STRING,  0,     100),
            "country"         => array(TYPE_STRING,  2,       2, REGEX_LETTERS)
        ),
        "credit_card" => array(
            "type"            => array(TYPE_STRING,  0,       8, REGEX_CREDIT),
            "status"          => array(TYPE_STRING,  0,       8),
            "sha1"            => array(TYPE_STRING, 40,      40, REGEX_HEXA_DIGITS),
            "bin"             => array(TYPE_STRING,  6,       6, REGEX_DIGITS),
            "last4"           => array(TYPE_STRING,  4,       4, REGEX_DIGITS),
            "expiration_date" => array(TYPE_STRING,  6,       6, REGEX_DIGITS)
        ),
        "boleto" => array(
            "type"            => array(TYPE_STRING,  0,       8, REGEX_BOLETO),
            "expiration_date" => array(TYPE_STRING, 10,      10, REGEX_DATE)
        ),
        "item" => array(
            "sku"             => array(TYPE_STRING,  0,     100),
            "product_code"    => array(TYPE_STRING,  0,     100),
            "category"        => array(   TYPE_INT,100,    9999),
            "name"            => array(TYPE_STRING,  0,     100),
            "description"     => array(TYPE_STRING,  0,     100),
            "unit_cost"       => array( TYPE_FLOAT,  0, 9999999),
            "quantity"        => array(   TYPE_INT,  0, 9999999),
            "discount"        => array( TYPE_FLOAT,  0, 9999999),
            "created_at"      => array(TYPE_STRING,  8,      10, REGEX_DATE)
        ),
        "travel" => array(
            "type"            => array(TYPE_STRING,  1,     10, REGEX_TRAVEL_TYPE)
        ),
        "passenger" => array(
            "name"            => array(TYPE_STRING,  0,    100),
            "document"        => array(TYPE_STRING,  0,    100),
            "document_type"   => array(TYPE_STRING,  0,      8, REGEX_DOCUMENT_TYPE),
            "dob"             => array(TYPE_STRING, 10,     10, REGEX_DATE),
            "nationality"     => array(TYPE_STRING,  2,      2, REGEX_LETTERS),
            "frequent_traveler" => array(TYPE_BOOL),
            "special_needs"   => array(TYPE_BOOL)
        ),
        "travel_info" => array(
            "origin_city"     => array(TYPE_STRING,  0,    100),
            "origin_airport"  => array(TYPE_STRING,  3,      3),
            "destination_city" => array(TYPE_STRING, 0,    100),
            "destination_airport" => array(TYPE_STRING,  3,  3, REGEX_LETTERS),
            "number_of_connections" => array(TYPE_INT,   0, 99),
            "date"            => array(TYPE_STRING, 17,     17, REGEX_DATETIME),
            "class"           => array(TYPE_STRING,  1,      8, REGEX_TRAVEL_CLASS),
            "fare_basis"      => array(TYPE_STRING,  0,     20)
        )
    );

    /**
     * Validates whether a field is valid according to $validation structure.
     * Converts $var to the correct type if possible.
     */
    public static function validateField($object, $field, $var) {

        $isValid = false;

        switch (self::$validation[$object][$field][I_TYPE]) {

            case TYPE_INT:
                $var = self::coerceToInt($var);
                $isValid = is_int($var) && self::validateNumberLength($object, $field, $var);
                break;

            case TYPE_STRING:
                $var = self::coerceToString($var);
                $isValid = is_string($var) && self::validateStringLength($object, $field, $var);
                break;

            case TYPE_FLOAT:
                $var = self::coerceToFloat($var);
                $isValid = is_float($var) && self::validateNumberLength($object, $field, $var);
                break;

            case TYPE_BOOL:
                $isValid = is_bool($var);
                break;
        }

        // Validate regex pattern, if present
        if ($isValid and array_key_exists(I_PATTERN, self::$validation[$object][$field])) {
            $isValid &= preg_match(self::$validation[$object][$field][I_PATTERN], $var) == 1;
        }

        return $isValid;
    }

    public static function validateNumberLength($object, $field, $number) {
        // Assumes $number is numeric!
        return $number >= self::$validation[$object][$field][I_MIN] && $number <= self::$validation[$object][$field][I_MAX];
    }

    public static function validateStringLength($object, $field, $string) {
        // Assumes $string is string!
        return strlen($string) >= self::$validation[$object][$field][I_MIN] && strlen($string) <= self::$validation[$object][$field][I_MAX];
    }

    public static function schemaHasField($schema_key, $field_name) {
        return array_key_exists($field_name, self::$validation[$schema_key]);
    }

    public static function coerceToInt($var) {
        return ((gettype($var) == TYPE_STRING and ctype_digit($var))
            or gettype($var) == TYPE_FLOAT) ? intval($var) : $var;
    }

    public static function coerceToString($var) {
        return (gettype($var) == TYPE_INT or gettype($var) == TYPE_FLOAT) ?
                strval($var) : $var;
    }

    public static function coerceToFloat($var) {
        return is_numeric($var) ? floatval($var) : $var;
    }

    public static function coerce($schema_key, $field, $var) {
        switch (self::$validation[$schema_key][$field][I_TYPE]) {
            case TYPE_INT: return self::coerceToInt($var);
            case TYPE_STRING: return self::coerceToString($var);
            case TYPE_FLOAT: return self::coerceToFloat($var);
            default: return $var;
        }
    }
}
