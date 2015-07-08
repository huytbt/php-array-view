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
        static $factory;

        if ($factory == null) {
            $app = app();
            $viewPaths = $app['config']['view.paths'];
            $factory = new \ChickenCoder\Illuminate\JsonView\Factory($viewPaths);
        }

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->render($view, $data, $mergeData);
    }
}
