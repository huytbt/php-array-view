<?php

if (!function_exists('arrayView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \ChickenCoder\ArrayView\Factory
     */
    function arrayView($view = null, $data = [], $mergeData = [])
    {
        static $factory;

        if ($factory == null) {
            $viewPaths = [ dirname(__FILE__) . '/views' ];
            $factory = new \ChickenCoder\ArrayView\Factory($viewPaths);
        }

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->render($view, $data, $mergeData);
    }
}
