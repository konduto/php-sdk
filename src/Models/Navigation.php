<?php namespace Konduto\Models;

class Navigation extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("session_time", "referrer", "time_site_1d", "new_accounts_1d",
            "password_resets_1d", "sales_declined_1d", "sessions_1d", "time_site_7d",
            "time_per_page_7d", "new_accounts_7d", "password_resets_7d", "checkout_count_7d",
            "sales_declined_7d", "sessions_7d", "time_since_last_sale");
    }

    public function getSessionTime() {
        return $this->get("session_time");
    }

    public function setSessionTime($session_time) {
        $this->set("session_time", $session_time);
        return $this;
    }

    public function getReferrer() {
        return $this->get("referrer");
    }

    public function setReferrer($referrer) {
        $this->set("referrer", $referrer);
        return $this;
    }

    public function getTimeSite1d() {
        return $this->get("time_site_1d");
    }

    public function setTimeSite1d($time_site_1d) {
        $this->set("time_site_1d", $time_site_1d);
        return $this;
    }

    public function getNewAccounts1d() {
        return $this->get("new_accounts_1d");
    }

    public function setNewAccounts1d($new_accounts_1d) {
        $this->set("new_accounts_1d", $new_accounts_1d);
        return $this;
    }

    public function getPasswordResets1d() {
        return $this->get("password_resets_1d");
    }

    public function setPasswordResets1d($password_resets_1d) {
        $this->set("password_resets_1d", $password_resets_1d);
        return $this;
    }

    public function getSalesDeclined1d() {
        return $this->get("sales_declined_1d");
    }

    public function setSalesDeclined1d($sales_declined_1d) {
        $this->set("sales_declined_1d", $sales_declined_1d);
        return $this;
    }

    public function getSessions1d() {
        return $this->get("sessions_1d");
    }

    public function setSessions1d($sessions_1d) {
        $this->set("sessions_1d", $sessions_1d);
        return $this;
    }

    public function getTimeSite7d() {
        return $this->get("time_site_7d");
    }

    public function setTimeSite7d($time_site_7d) {
        $this->set("time_site_7d", $time_site_7d);
        return $this;
    }

    public function getTimePerPage7d() {
        return $this->get("time_per_page_7d");
    }

    public function setTimePerPage7d($time_per_page_7d) {
        $this->set("time_per_page_7d", $time_per_page_7d);
        return $this;
    }

    public function getNewAccounts7d() {
        return $this->get("new_accounts_7d");
    }

    public function setNewAccounts7d($new_accounts_7d) {
        $this->set("new_accounts_7d", $new_accounts_7d);
        return $this;
    }

    public function getPasswordResets7d() {
        return $this->get("password_resets_7d");
    }

    public function setPasswordResets7d($password_resets_7d) {
        $this->set("password_resets_7d", $password_resets_7d);
        return $this;
    }

    public function getCheckoutCount7d() {
        return $this->get("checkout_count_7d");
    }

    public function setCheckoutCount7d($checkout_count_7d) {
        $this->set("checkout_count_7d", $checkout_count_7d);
        return $this;
    }

    public function getSalesDeclined7d() {
        return $this->get("sales_declined_7d");
    }

    public function setSalesDeclined7d($sales_declined_7d) {
        $this->set("sales_declined_7d", $sales_declined_7d);
        return $this;
    }

    public function getSessions7d() {
        return $this->get("sessions_7d");
    }

    public function setSessions7d($sessions_7d) {
        $this->set("sessions_7d", $sessions_7d);
        return $this;
    }

    public function getTimeSinceLastSale() {
        return $this->get("time_since_last_sale");
    }

    public function setTimeSinceLastSale($time_since_last_sale) {
        $this->set("time_since_last_sale", $time_since_last_sale);
        return $this;
    }
}
