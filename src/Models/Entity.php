<?php namespace Konduto\Models;

interface Entity {
    public function set();
    public function get_errors();
    public function is_valid();
}