<?php
namespace konduto\models;
require_once "validation.php";

interface Entity {
    public function set();
    public function get_errors();
    public function is_valid();
}