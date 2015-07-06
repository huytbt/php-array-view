<?php

if (!function_exists('jsonView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \ChickenCoder\Illuminate\View\JsonFactory
     */
    function jsonView($view = null, $data = [], $mergeData = [])
    {
        $factory = app('ChickenCoder\Illuminate\JsonView\Contract');

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}
