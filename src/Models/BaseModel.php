<?php namespace Konduto\Models;

use Konduto\Parsers\DefaultParser as DefaultParser;
use Konduto\Parsers\IParser;

abstract class BaseModel {

    // Internal properties
    private $_properties = array();
    protected $defaultParser;
    protected $fields;
    protected $parsers;
    protected static $customParsers = array();

    /**
     * Return an array with names of the default fields accepted
     * by the model.
     * @return array
     */
    abstract protected function fields();

    /**
     * The constructor allows for an associative array argument
     * in which each entry will be set using 'set'
     * @param $args array
     */
    public function __construct(array $args=array()) {
        $this->parsers = $this->initParsers();
        $this->fields = $this->fields();
        $this->defaultParser = new DefaultParser();
        foreach ($args as $key => $val)
            $this->set($key, $val);
    }

    /**
     * Parse and set a value in the correspondent field
     * in the $_properties array.
     * @param $field string
     * @param $value mixed
     */
    public function set($field, $value) {
        $parser = $this->getParser($field);
        $this->_properties[$field] = $parser->parse($value);
    }

    /**
     * Return the value of the field.
     * @param string $field
     * @return mixed
     */
    public function get($field) {
        return key_exists($field, $this->_properties) ? $this->_properties[$field] : null;
    }

    /**
     * Add a new field that can be enter in the json array representation
     * of the model.
     * @param string $field name of the field
     * @param mixed $value a value to be set
     */
    public function addField($field, $value=null) {
        $this->fields[] = $field;
        if ($value != null) $this->set($field, $value);
    }

    /**
     * Return an associative array with names of fields as
     * keys and parsers as values.
     * Example:
     *
     * array(
     *    "field_a" => new CustomParser(),
     *    "field_b" => new DateTimeParser()
     * )
     *
     * @see BaseModel::toJsonArray , BaseModel::set
     *
     * @return array
     */
    protected function initParsers() {
        return array();
    }

    public static function addCustomParser($field, IParser $parser) {
        self::$customParsers[$field] = $parser;
    }

    public static function removeCustomParser($field) {
        unset(self::$customParsers[$field]);
    }

    /**
     * Build an array representation of this object using $_properties
     * that can be encoded as json.
     * The parsers are responsible to unparse the values into
     * a correspondent value that is in a json type.
     * @return array
     */
    public function toJsonArray() {
        $array = $this->_properties;
        foreach ($array as $key => $value) {
            if (in_array($key, $this->fields) && !is_null($value)) {
                $parser = $this->getParser($key);
                $array[$key] = $parser->unparse($value);
            }
            else unset($array[$key]);
        }
        return $array;
    }

    public function getProperties() {
        return $this->_properties;
    }

    protected function getParser($field) {
        $parsers = array_replace($this->parsers, self::$customParsers);
        return (key_exists($field, $parsers)) ? $parsers[$field] : $this->defaultParser;
    }
}
