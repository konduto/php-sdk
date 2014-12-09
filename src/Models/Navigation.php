<?php namespace Konduto\Models;

class Navigation extends Model {

    protected $_properties = [
        "session_time" => null,
        "referrer" => null,
        "time_site_1d" => null,
        "new_accounts_1d" => null,
        "password_resets_1d" => null,
        "sales_declined_1d" => null,
        "sessions_1d" => null,
        "time_site_7d" => null,
        "time_per_page_7d" => null,
        "new_accounts_7d" => null,
        "password_resets_7d" => null,
        "checkout_count_7d" => null,
        "sales_declined_7d" => null,
        "sessions_7d" => null,
        "time_since_last_sale" => null
    ];
}