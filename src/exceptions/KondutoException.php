<?php
namespace konduto\exceptions;

class KondutoException extends \Exception {

    public function __toString() {
        return "Konduto exception: " . get_class($this) . "  [{$this->message}].";
    }
}