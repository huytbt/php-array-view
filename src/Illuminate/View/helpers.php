<?php

use ChickenCoder\Illuminate\View\Json;

if (!function_exists('json_view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \ChickenCoder\Illuminate\View\JsonFactory
     */
    function json_view($view = null, $data = [], $mergeData = [])
    {
        $jsonFactory = app('ChickenCoder\Illuminate\View\JsonFactory');

        if (func_num_args() === 0) {
            return $jsonFactory;
        }

        return $jsonFactory->make($view, $data, $mergeData);
    }
}
