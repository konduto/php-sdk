<?php namespace Konduto;

abstract class Params {
    /*
     * Current version of this library
     */
    const SDK_VERSION = "v2.0.0";

    /*
     * Defines what URL to communicate with.
     */
    const ENDPOINT = "https://api.konduto.com/v1";

    /*
     * Timeout in seconds for every request performed
     */
    const API_TIMEOUT = 30;
}
