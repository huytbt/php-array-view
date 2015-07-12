<?php

if (!function_exists('app')) {
    function app() {
        return array(
            'config' => array(
                'view.paths' => array(
                    dirname(__FILE__) . '/views',
                ),
            )
        );
    }
}