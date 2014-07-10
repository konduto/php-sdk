<?php namespace Konduto\Models;

interface Entity {
    public function set();
    public function getErrors();
    public function isValid();
}