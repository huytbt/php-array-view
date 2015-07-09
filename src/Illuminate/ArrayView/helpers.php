<?php

if (!function_exists('arrayView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \ChickenCoder\Illuminate\View\JsonFactory
     */
    function arrayView($view = null, $data = [], $mergeData = [])
    {
        static $factory;

        if ($factory == null) {
            $app = app();
            $viewPaths = $app['config']['view.paths'];
            $factory = new \ChickenCoder\Illuminate\ArrayView\Factory($viewPaths);
        }

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->render($view, $data, $mergeData);
    }
}
