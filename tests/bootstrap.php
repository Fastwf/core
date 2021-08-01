<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';


if (!function_exists('apache_request_headers')) {
    // Create fake apache_request_headers method
    function apache_request_headers() {
        return [];
    }
}
