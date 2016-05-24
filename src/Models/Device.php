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
        $this->set("user_id", $user_id);
        return $this;
    }

    public function getFingerprint() {
        return $this->get("fingerprint");
    }

    public function setFingerprint($fingerprint) {
        $this->set("fingerprint", $fingerprint);
        return $this;
    }

    public function getPlatform() {
        return $this->get("platform");
    }

    public function setPlatform($platform) {
        $this->set("platform", $platform);
        return $this;
    }

    public function getBrowser() {
        return $this->get("browser");
    }

    public function setBrowser($browser) {
        $this->set("browser", $browser);
        return $this;
    }

    public function getLanguage() {
        return $this->get("language");
    }

    public function setLanguage($language) {
        $this->set("language", $language);
        return $this;
    }

    public function getTimezone() {
        return $this->get("timezone");
    }

    public function setTimezone($timezone) {
        $this->set("timezone", $timezone);
        return $this;
    }

    public function getCookie() {
        return $this->get("cookie");
    }

    public function setCookie($cookie) {
        $this->set("cookie", $cookie);
        return $this;
    }

    public function getJavascript() {
        return $this->get("javascript");
    }

    public function setJavascript($javascript) {
        $this->set("javascript", $javascript);
        return $this;
    }

    public function getFlash() {
        return $this->get("flash");
    }

    public function setFlash($flash) {
        $this->set("flash", $flash);
        return $this;
    }
}
