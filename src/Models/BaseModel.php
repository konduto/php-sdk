<?php namespace Konduto\Models;

use Konduto\Parsers\DefaultParser as DefaultParser;

abstract class BaseModel {

    // Internal properties
    private $_properties;
    protected $defaultParser;

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
        $this->initProperties();
        $this->defaultParser = new DefaultParser();
        foreach ($args as $key => $val)
            $this->set($key, $val);
    }

    /**
     * Return an associative array with names of fields as
     * keys and parsers as values.
     * Example:
     *
     * array(
     *    "field_a" => new CustomParser(),
     *    "field_b" => new DateParser()
     * )
     *
     * @return array
     */
    protected function parsers() {
        return array();
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
     * @param $field string
     * @return mixed
     */
    public function get($field) {
        return key_exists($field, $this->_properties) ? $this->_properties[$field] : null;
    }

    /**
     * Initialize _properties associative array according to the names
     * of fields indicated by an array returned by 'values' method.
     */
    private function initProperties() {
        foreach ($this->fields() as $fieldName)
            $this->_properties[$fieldName] = null;
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
            if (!empty($value)) {
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
        $parsers = $this->parsers();
        return (in_array($field, $parsers)) ? $parsers[$field] : $this->defaultParser;
    }
}
