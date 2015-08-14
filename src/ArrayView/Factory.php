<?php

namespace ChickenCoder\ArrayView;

use Closure;
use InvalidArgumentException;
use BadFunctionCallException;

class Factory
{
    /**
     * The view paths.
     */
    protected $viewPaths = [];

    /**
     * The extension to engine bindings.
     *
     * @var array
     */
    protected $extension = 'array.php';

    /**
     * Results.
     *
     * @var array
     */
    protected $results = [];

    /**
     * Create a new view factory instance.
     *
     * @param  array $viewPaths
     * @return void
     */
    public function __construct($viewPaths = [])
    {
        $this->viewPaths = $viewPaths;
    }

    /**
     * Set view paths
     * 
     * @param  string $view View
     * @return string       View Path
     * @author HuyTBT <huytbt@gmail.com>
     */
    public function setViewPaths($viewPaths = [])
    {
        $this->viewPaths = $viewPaths;
    }

    /**
     * Get view paths
     * 
     * @param  string $view View
     * @return string       View Path
     * @author HuyTBT <huytbt@gmail.com>
     */
    public function getViewPaths()
    {
        return $this->viewPaths;
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    public function render($view, $data = [], $mergeData = [])
    {
        $this->results = [];

        $viewPath = $this->getViewPath($view);

        if ($viewPath === null) {
            throw new InvalidArgumentException("View [{$view}] not found.");
        }

        $data = array_merge($mergeData, $data);

        extract($data);

        include($viewPath);

        return $this->results;
    }

    public function getResults()
    {
        return $this->results;
    }

    /**
     * Get path of view
     * 
     * @param  string $view View
     * @return string       View Path
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function getViewPath($view)
    {
        foreach ($this->viewPaths as $viewPath) {
            if (file_exists($viewPath . '/' . $view . '.' . $this->extension)) {
                return $viewPath . '/' . $view . '.' . $this->extension;
            }
        }

        return null;
    }

    /**
     * Get path of helper
     * 
     * @param  string $view View
     * @return string       View Path
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function getHelperPath($helper)
    {
        foreach ($this->viewPaths as $viewPath) {
            if (file_exists($viewPath . '/helpers/' . $helper . '.helper.php')) {
                return $viewPath . '/helpers/' . $helper . '.helper.php';
            }
        }

        return null;
    }

    /**
     * Set value to results
     * 
     * @param  string $key   Key
     * @param  mix    $value Value
     * @return ChickenCoder\ArrayView\Factory $this
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function set($key, $value = null)
    {
        if (func_num_args() === 1) {
            $key === '{}' && $key = json_decode('{}');
            $this->results = $key;
            return;
        }

        if ($value instanceof Closure) {
            $factory = new Factory($this->viewPaths);
            $value($factory);
            $this->results[$key] = $factory->getResults();
            return $this;
        }

        $value === '{}' && $value = json_decode('{}');
        $this->results[$key] = $value;

        return $this;
    }

    /**
     * Each item array
     * 
     * @param  array   $data     Data
     * @param  Closure $callback Callback
     * @return array
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function each($data = [], Closure $callback)
    {
        $factory = new Factory($this->viewPaths);
        $results = array();
        foreach ($data as $item) {
            $callback($factory, $item);
            $results[] = $factory->getResults();
        }

        return $results;
    }

    /**
     * Render partial json view
     * 
     * @param  string $partialView Partial view
     * @param  array  $data        Data
     * @param  array  $mergeData   Merge data
     * @return ChickenCoder\ArrayView\Factory
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function partial($partialView, $data = [], $mergeData = [])
    {
        $factory = new Factory($this->viewPaths);

        return $factory->render($partialView, $data, $mergeData);
    }

    /**
     * Extract data
     * 
     * @param  array $data
     * @param  array $fields
     * @return ChickenCoder\ArrayView\Factory
     */
    public function extract($data = [], $fields = [])
    {
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $this->set($field, $data[$field]);
            } else {
                $this->set($field, null);
            }
        }

        return $this;
    }

    /**
     * Helper method
     * 
     * @param  string $helper
     * @return mix
     */
    public function helper($helper)
    {
        $helperPath = $this->getHelperPath($helper);

        if ($helperPath === null) {
            throw new InvalidArgumentException("Helper [{$helper}] not found.");
        }

        $args = func_get_args();
        unset($args[0]);

        include_once $helperPath;

        if (!function_exists("helper_{$helper}")) {
            throw new BadFunctionCallException("Helper [{$helper}] does not contain function [helper_{$helper}()].");
        }

        return call_user_func_array("helper_{$helper}", $args);
    }
}
