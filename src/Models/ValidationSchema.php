<?php namespace Konduto\Models;

const BOOL   = "boolean";
const INT    = "integer";
const STRING = "string";
const FLOAT  = "double";

const I_TYPE    = 0;
const I_MIN     = 1;
const I_MAX     = 2;
const I_PATTERN = 3;

const REGEX_LETTERS_DIGITS = "/^[a-zA-Z0-9-_]+\z/";
const REGEX_LETTERS        = "/^[a-zA-Z]+\z/";
const REGEX_UPPERCASE      = "/^[A-Z]+\z/";
const REGEX_DIGITS         = "/^[0-9]+\z/";
const REGEX_HEXA_DIGITS    = "/^[a-fA-F0-9]+\z/";
const REGEX_FULL_DATE      = "/^\d{4}-\d{2}-\d{2}\z/";
const REGEX_DATETIME       = "/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}Z\z/";
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
     * 'type' accepts one of the constants defined above: BOOL, INT, STRING and FLOAT.
     * For type STRING, the 2nd and 3rd positions, 'minimum' and 'maximum', refer to the number of character.
     * For types INT and FLOAT, the 2nd and 3rd positions, 'minimum' and 'maximum', refer to minimum and max numbers allowed.
     * For type BOOL, positions 'minimum' and 'maximum' are not used.
     * Optionally, it could have a IDX_PATTERN key containing a regex expression to be applied.
     */
    private static $validation = [
        "order" => [
            "id"              => [STRING,  1,     100, REGEX_LETTERS_DIGITS],
            "visitor"         => [STRING,  0,     100, REGEX_LETTERS_DIGITS],
            "total_amount"    => [ FLOAT,  0, 9999999],
            "shipping_amount" => [ FLOAT,  0, 9999999],
            "tax_amount"      => [ FLOAT,  0, 9999999],
            "currency"        => [STRING,  3,       3, REGEX_UPPERCASE],
            "installments"    => [   INT,  1,     999],
            "ip"              => [STRING,  7,      15, REGEX_IPv4]
        ],
        "customer" => [
            "id"              => [STRING,  0,     100],
            "name"            => [STRING,  0,     100],
            "tax_id"          => [STRING,  0,     100],
            "phone1"          => [STRING,  0,     100],
            "phone2"          => [STRING,  0,     100],
            "email"           => [STRING,  0,     100],
            "new"             => [  BOOL],
            "vip"             => [  BOOL],
            "dob"             => [STRING,  8,      10, REGEX_FULL_DATE]
        ],
        "address" => [
            "name"            => [STRING,  0,     100],
            "address1"        => [STRING,  0,     255],
            "address2"        => [STRING,  0,     255],
            "city"            => [STRING,  0,     100],
            "state"           => [STRING,  0,     100],
            "zip"             => [STRING,  0,     100],
            "country"         => [STRING,  2,       2, REGEX_LETTERS]
        ],
        "credit_card" => [
            "type"            => [STRING,  0,       8, REGEX_CREDIT],
            "status"          => [STRING,  0,       8],
            "sha1"            => [STRING, 40,      40, REGEX_HEXA_DIGITS],
            "bin"             => [STRING,  6,       6, REGEX_DIGITS],
            "last4"           => [STRING,  4,       4, REGEX_DIGITS],
            "expiration_date" => [STRING,  6,       6, REGEX_DIGITS]
        ],
        "boleto" => [
            "type"            => [STRING,  0,       8, REGEX_BOLETO],
            "expiration_date" => [STRING, 10,      10, REGEX_FULL_DATE]
        ],
        "item" => [
            "sku"             => [STRING,  0,     100],
            "product_code"    => [STRING,  0,     100],
            "category"        => [   INT,100,    9999],
            "name"            => [STRING,  0,     100],
            "description"     => [STRING,  0,     100],
            "unit_cost"       => [ FLOAT,  0, 9999999],
            "quantity"        => [   INT,  0, 9999999],
            "discount"        => [ FLOAT,  0, 9999999]
        ],
        "travel" => [
            "type"            => [STRING,  1,     10, REGEX_TRAVEL_TYPE]
        ],
        "passenger" => [
            "name"            => [STRING,  0,    100],
            "document"        => [STRING,  0,    100],
            "document_type"   => [STRING,  0,      8, REGEX_DOCUMENT_TYPE],
            "dob"             => [STRING, 10,     10, REGEX_FULL_DATE],
            "nationality"     => [STRING,  2,      2, REGEX_LETTERS],
            "frequent_traveler" => [BOOL],
            "special_needs"   => [BOOL]
        ],
        "travel_info" => [
            "origin_city"     => [STRING,  0,    100],
            "origin_airport"  => [STRING,  3,      3],
            "destination_city" => [STRING, 0,    100],
            "destination_airport" => [STRING,  3,  3, REGEX_LETTERS],
            "number_of_connections" => [INT,   0, 99],
            "date"            => [STRING, 17,     17, REGEX_DATETIME],
            "class"           => [STRING,  1,      8, REGEX_TRAVEL_CLASS],
            "fare_basis"      => [STRING,  0,     20]
        ]
    ];

    /**
     * Validates whether a field is valid according to $validation structure.
     * Converts $var to the correct type if possible.
     */
    public static function validateField($object, $field, $var) {

        $isValid = false;

        switch (self::$validation[$object][$field][I_TYPE]) {

            case INT:
                $var = self::coerceToInt($var);
                $isValid = is_int($var) && self::validateNumberLength($object, $field, $var);
                break;

            case STRING:
                $var = self::coerceToString($var);
                $isValid = is_string($var) && self::validateStringLength($object, $field, $var);
                break;

            case FLOAT:
                $var = self::coerceToFloat($var);
                $isValid = is_float($var) && self::validateNumberLength($object, $field, $var);
                break;

            case BOOL:
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
        return ((gettype($var) == STRING and ctype_digit($var))
            or gettype($var) == FLOAT) ? intval($var) : $var;
    }

    public static function coerceToString($var) {
        return (gettype($var) == INT or gettype($var) == FLOAT) ?
                strval($var) : $var;
    }

    public static function coerceToFloat($var) {
        return is_numeric($var) ? floatval($var) : $var;
    }

    public static function coerce($schema_key, $field, $var) {
        switch (self::$validation[$schema_key][$field][I_TYPE]) {
            case INT: return self::coerceToInt($var);
            case STRING: return self::coerceToString($var);
            case FLOAT: return self::coerceToFloat($var);
            default: return $var;
        }
    }
}
