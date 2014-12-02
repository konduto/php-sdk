<?php namespace Konduto\Models;

class Navigation extends Model {

    // Settable/gettable properties

    private $sessionTime_;
    private $referrer_;
    private $timeSite1d_;
    private $newAccounts1d_;
    private $passwordResets1d_;
    private $salesDeclined1d_;
    private $sessions1d_;
    private $timeSite7d_;
    private $timePerPage7d_;
    private $newAccounts7d_;
    private $passwordResets7d_;
    private $checkoutCount7d_;
    private $salesDeclined7d_;
    private $sessions7d_;
    private $timeSinceLastSale_;

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
                case 'sessionTime':
                case 'session_time':
                    $this->sessionTime($arg);
                    break;
                case '1':
                case 'referrer':
                    $this->referrer($arg);
                    break;
                case '2':
                case 'timeSite1d':
                case 'time_site_1d':
                    $this->timeSite1d($arg);
                    break;
                case '3':
                case 'newAccounts1d':
                case 'new_accounts_1d':
                    $this->newAccounts1d($arg);
                    break;
                case '4':
                case 'passwordResets1d':
                case 'password_resets_1d':
                    $this->passwordResets1d($arg);
                    break;
                case '5':
                case 'salesDeclined1d':
                case 'sales_declined_1d':
                    $this->salesDeclined1d($arg);
                    break;
                case '6':
                case 'sessions1d':
                case 'sessions_1d':
                    $this->sessions1d($arg);
                    break;
                case '7':
                case 'timeSite7d':
                case 'time_site_7d':
                    $this->timeSite7d($arg);
                    break;
                case '8':
                case 'timePerPage7d':
                case 'time_per_page_7d':
                    $this->timePerPage7d($arg);
                    break;
                case '9':
                case 'newAccounts7d':
                case 'new_accounts_7d':
                    $this->newAccounts7d($arg);
                    break;
                case '10':
                case 'passwordResets7d':
                case 'password_resets_7d':
                    $this->passwordResets7d($arg);
                    break;
                case '11':
                case 'checkoutCount7d':
                case 'checkout_count_7d':
                    $this->checkoutCount7d($arg);
                    break;
                case '12':
                case 'salesDeclined7d':
                case 'sales_declined_7d':
                    $this->salesDeclined7d($arg);
                    break;
                case '13':
                case 'sessions7d':
                case 'sessions_7d':
                    $this->sessions7d($arg);
                    break;
                case '14':
                case 'timeSinceLastSale':
                case 'time_since_last_sale':
                    $this->timeSinceLastSale($arg);
                    break;
            }
        }
    }
    
    /**
     * Sets or gets sessionTime of user navigation. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param sessionTime value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function sessionTime($sessionTime = null) {
        return isset($sessionTime) ?
            // Set
            $this->set_property($this->sessionTime_, "", $sessionTime)
            // Get
            : $this->sessionTime_;
    }

    /**
     * Alias for sessionTime()
     */
    public function session_time($sessionTime = null) {
        return $this->sessionTime($sessionTime);
    }
    
    /**
     * Sets or gets referrer of user navigation. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param referrer value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function referrer($referrer = null) {
        return isset($referrer) ?
            // Set
            $this->set_property($this->referrer_, "", $referrer)
            // Get
            : $this->referrer_;
    }
    
    /**
     * Sets or gets the number of minutes spent on the web site for the last 1 day by the customer. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param timeSite1d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function timeSite1d($timeSite1d = null) {
        return isset($timeSite1d) ?
            // Set
            $this->set_property($this->timeSite1d_, "", $timeSite1d)
            // Get
            : $this->timeSite1d_;
    }

    /**
     * Alias for timeSite1d()
     */
    public function time_site_1d($timeSite1d = null) {
        return $this->timeSite1d($timeSite1d);
    }
    
    /**
     * Sets or gets the number of accounts created by the user in the last 1 day. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param newAccounts1d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function newAccounts1d($newAccounts1d = null) {
        return isset($newAccounts1d) ?
            // Set
            $this->set_property($this->newAccounts1d_, "", $newAccounts1d)
            // Get
            : $this->newAccounts1d_;
    }

    /**
     * Alias for newAccounts1d()
     */
    public function new_accounts_1d($newAccounts1d = null) {
        return $this->newAccounts1d($newAccounts1d);
    }
    
    /**
     * Sets or gets the number of password resets done by the user in the last 1 day. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param passwordResets1d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function passwordResets1d($passwordResets1d = null) {
        return isset($passwordResets1d) ?
            // Set
            $this->set_property($this->passwordResets1d_, "", $passwordResets1d)
            // Get
            : $this->passwordResets1d_;
    }

    /**
     * Alias for passwordResets1d()
     */
    public function password_resets_1d($passwordResets1d = null) {
        return $this->passwordResets1d($passwordResets1d);
    }
    
    /**
     * Sets or gets the number of sales declined to the user in the last 1 day. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param salesDeclined1d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function salesDeclined1d($salesDeclined1d = null) {
        return isset($salesDeclined1d) ?
            // Set
            $this->set_property($this->salesDeclined1d_, "", $salesDeclined1d)
            // Get
            : $this->salesDeclined1d_;
    }

    /**
     * Alias for salesDeclined1d()
     */
    public function sales_declined_1d($salesDeclined1d = null) {
        return $this->salesDeclined1d($salesDeclined1d);
    }
    
    /**
     * Sets or gets the number of sessions used by the user in the last 1 day. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param sessions1d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function sessions1d($sessions1d = null) {
        return isset($sessions1d) ?
            // Set
            $this->set_property($this->sessions1d_, "", $sessions1d)
            // Get
            : $this->sessions1d_;
    }

    /**
     * Alias for sessions1d()
     */
    public function sessions_1d($sessions1d = null) {
        return $this->sessions1d($sessions1d);
    }
    
    /**
     * Sets or gets the number of minutes spent on the web site for the last 7 days by the customer. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param timeSite7d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function timeSite7d($timeSite7d = null) {
        return isset($timeSite7d) ?
            // Set
            $this->set_property($this->timeSite7d_, "", $timeSite7d)
            // Get
            : $this->timeSite7d_;
    }

    /**
     * Alias for timeSite7d()
     */
    public function time_site_7d($timeSite7d = null) {
        return $this->timeSite7d($timeSite7d);
    }
    
    /**
     * Sets or gets the average number of minutes spent on each page for the last 7 days by the customer. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param timePerPage7d_ value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function timePerPage7d($timePerPage7d = null) {
        return isset($timePerPage7d) ?
            // Set
            $this->set_property($this->timePerPage7d_, "", $timePerPage7d)
            // Get
            : $this->timePerPage7d_;
    }

    /**
     * Alias for timePerPage7d()
     */
    public function time_per_page_7d($timePerPage7d = null) {
        return $this->timePerPage7d($timePerPage7d);
    }
    
    /**
     * Sets or gets the number of accounts created by the user in the last 7 days. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param newAccounts7d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function newAccounts7d($newAccounts7d = null) {
        return isset($newAccounts7d) ?
            // Set
            $this->set_property($this->newAccounts7d_, "", $newAccounts7d)
            // Get
            : $this->newAccounts7d_;
    }

    /**
     * Alias for newAccounts7d()
     */
    public function new_accounts_7d($newAccounts7d = null) {
        return $this->newAccounts7d($newAccounts7d);
    }
    
    /**
     * Sets or gets the number of password resets done by the user in the last 7 days. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param passwordResets7d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function passwordResets7d($passwordResets7d = null) {
        return isset($passwordResets7d) ?
            // Set
            $this->set_property($this->passwordResets7d_, "", $passwordResets7d)
            // Get
            : $this->passwordResets7d_;
    }

    /**
     * Alias for passwordResets7d()
     */
    public function password_resets_7d($passwordResets7d = null) {
        return $this->passwordResets7d($passwordResets7d);
    }
    
    /**
     * Sets or gets the number of checkouts performed by the user in the last 7 days. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param checkoutCount7d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function checkoutCount7d($checkoutCount7d = null) {
        return isset($checkoutCount7d) ?
            // Set
            $this->set_property($this->checkoutCount7d_, "", $checkoutCount7d)
            // Get
            : $this->checkoutCount7d_;
    }

    /**
     * Alias for checkoutCount7d()
     */
    public function checkout_count_7d($checkoutCount7d = null) {
        return $this->checkoutCount7d($checkoutCount7d);
    }
    
    /**
     * Sets or gets the number of sales declined in the last 7 days by the user. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param salesDeclined7d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function salesDeclined7d($salesDeclined7d = null) {
        return isset($salesDeclined7d) ?
            // Set
            $this->set_property($this->salesDeclined7d_, "", $salesDeclined7d)
            // Get
            : $this->salesDeclined7d_;
    }

    /**
     * Alias for salesDeclined7d()
     */
    public function sales_declined_7d($salesDeclined7d = null) {
        return $this->salesDeclined7d($salesDeclined7d);
    }
    
    /**
     * Sets or gets the number of sessions initiated by the user in the last 7 days. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param sessions7d value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function sessions7d($sessions7d = null) {
        return isset($sessions7d) ?
            // Set
            $this->set_property($this->sessions7d_, "", $sessions7d)
            // Get
            : $this->sessions7d_;
    }

    /**
     * Alias for sessions7d()
     */
    public function sessions_7d($sessions7d = null) {
        return $this->sessions7d($sessions7d);
    }
    
    /**
     * Sets or gets the time since last sale for the user. 
     * If a parameter is passed, it tries to set it. Gets its value otherwise.
     *
     * @param timeSinceLastSale value to set it. Leave null for getting the current value.
     * 
     * @return value in case of get, or null in case of setting.
     */
    public function timeSinceLastSale($timeSinceLastSale = null) {
        return isset($timeSinceLastSale) ?
            // Set
            $this->set_property($this->timeSinceLastSale_, "", $timeSinceLastSale)
            // Get
            : $this->timeSinceLastSale_;
    }

    /**
     * Alias for timeSinceLastSale()
     */
    public function time_since_last_sale($timeSinceLastSale = null) {
        return $this->timeSinceLastSale($timeSinceLastSale);
    }

    /**
     * Returns validation errors for Navigation. Since there is currently no validation
     * for Navigation, it will always return null.
     */ 
    public function getErrors() {}

    /**
     * Returns true if Navigation is valid. Since there is currently no validation
     * for Navigation, it will always return true.
     */ 
    public function isValid() { return true; }
}