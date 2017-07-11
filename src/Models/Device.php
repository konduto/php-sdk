<?php namespace Konduto\Models;

class Device extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("user_id", "fingerprint", "platform", "browser",
            "language", "timezone", "cookie", "javascript", "flash");
    }

    public function getUserId() {
        return $this->get("user_id");
    }

    public function setUserId($user_id) {
        return $this->set("user_id", $user_id);
    }

    public function getFingerprint() {
        return $this->get("fingerprint");
    }

    public function setFingerprint($fingerprint) {
        return $this->set("fingerprint", $fingerprint);
    }

    public function getPlatform() {
        return $this->get("platform");
    }

    public function setPlatform($platform) {
        return $this->set("platform", $platform);
    }

    public function getBrowser() {
        return $this->get("browser");
    }

    public function setBrowser($browser) {
        return $this->set("browser", $browser);
    }

    public function getLanguage() {
        return $this->get("language");
    }

    public function setLanguage($language) {
        return $this->set("language", $language);
    }

    public function getTimezone() {
        return $this->get("timezone");
    }

    public function setTimezone($timezone) {
        return $this->set("timezone", $timezone);
    }

    public function getCookie() {
        return $this->get("cookie");
    }

    public function setCookie($cookie) {
        return $this->set("cookie", $cookie);
    }

    public function getJavascript() {
        return $this->get("javascript");
    }

    public function setJavascript($javascript) {
        return $this->set("javascript", $javascript);
    }

    public function getFlash() {
        return $this->get("flash");
    }

    public function setFlash($flash) {
        return $this->set("flash", $flash);
    }
}
