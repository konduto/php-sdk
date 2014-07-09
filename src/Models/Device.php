<?php namespace Konduto\Models;

class Device implements Entity {

    // Settable/gettable properties

    private $user_id_;
    private $fingerprint_;
    private $platform_;
    private $browser_;
    private $language_;
    private $timezone_;
    private $cookie_;
    private $javascript_;
    private $flash_;

    // Methods

    public function __construct() {
        // For understanding how this constructor works, take a look in Order constructor.
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    // Getters/setters

    public function set() {
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'user_id':
                    $this->user_id($arg);
                    break;
                case '1':
                case 'fingerprint':
                    $this->fingerprint($arg);
                    break;
                case '2':
                case 'platform':
                    $this->platform($arg);
                    break;
                case '3':
                case 'browser':
                    $this->browser($arg);
                    break;
                case '4':
                case 'language':
                    $this->language($arg);
                    break;
                case '5':
                case 'timezone':
                    $this->timezone($arg);
                    break;
                case '6':
                case 'cookie':
                    $this->cookie($arg);
                    break;
                case '7':
                case 'javascript':
                    $this->javascript($arg);
                    break;
                case '8':
                case 'flash':
                    $this->flash($arg);
                    break;
            }
        }
    }
    
    public function user_id($user_id = null) {
        return isset($user_id) ?
            // Set
            $this->set_property($this->user_id_, $user_id)
            // Get
            : $this->user_id_;
    }

    public function fingerprint($fingerprint = null) {
        return isset($fingerprint) ? 
            $this->set_property($this->fingerprint_, $fingerprint)
            : $this->fingerprint_;
    }

    public function platform($platform = null) {
        return isset($platform) ? 
            $this->set_property($this->platform_, $platform)
            : $this->platform_;
    }

    public function browser($browser = null) {
        return isset($browser) ? 
            $this->set_property($this->browser_, $browser)
            : $this->browser_;
    }

    public function language($language = null) {
        return isset($language) ? 
            $this->set_property($this->language_, $language)
            : $this->language_;
    }

    public function timezone($timezone = null) {
        return isset($timezone) ? 
            $this->set_property($this->timezone_, $timezone)
            : $this->timezone_;
    }

    public function cookie($cookie = null) {
        return isset($cookie) ? 
            $this->set_property($this->cookie_, $cookie)
            : $this->cookie_;
    }

    public function javascript($javascript = null) {
        return isset($javascript) ? 
            $this->set_property($this->javascript_, $javascript)
            : $this->javascript_;
    }

    public function flash($flash = null) {
        return isset($flash) ? 
            $this->set_property($this->flash_, $flash)
            : $this->flash_;
    }

    protected function set_property(&$field, $value) {
        $field = $value;
    }


    public function get_errors() {}
    public function is_valid() { return true; }
}