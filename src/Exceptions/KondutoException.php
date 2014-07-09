<?php namespace Konduto\exceptions;

class KondutoException extends \Exception {

    public function __toString() {
        return "Konduto exception: " . get_class($this) . "  [{$this->message}].";
    }
}