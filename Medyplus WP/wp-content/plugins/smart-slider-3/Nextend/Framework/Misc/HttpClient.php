<?php

namespace Nextend\Framework\Misc;

use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;

class HttpClient {

    public static function getCacertPath() {
        return dirname(__FILE__) . '/cacert.pem';
    }

    public static function get($url, $options = array()) {
        $request = wp_remote_get($url);
        if (is_wp_error($request)) {
            foreach ($request->get_error_messages() as $errorMessage) {
                Notification::error($errorMessage);
            }

            return false;
        } else {
            $data = wp_remote_retrieve_body($request);
        }

        return $data;
    }

    private static function parseHeaders(array $headers, $header = null) {
    }
}