<?php

namespace ChickenCoder\ArrayView;

use Closure;
use InvalidArgumentException;

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
    public function __construct($viewPaths)
    {
        $this->viewPaths = $viewPaths;
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
            throw new InvalidArgumentException("View [{$view}] not found");
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
     * Set value to results
     * 
     * @param  string $key   Key
     * @param  mix    $value Value
     * @return ChickenCoder\Illuminate\ArrayView\Factory $this
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function set($key, $value = null)
    {
        if (func_num_args() === 1) {
            $this->results = $key;
            return;
        }

        if ($value instanceof Closure) {
            $factory = new Factory($this->viewPaths);
            $value($factory);
            $this->results[$key] = $factory->getResults();
            return $this;
        }

        $value === null && $value = json_decode("{}");
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
     * @return ChickenCoder\Illuminate\ArrayView\Factory $partial
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function partial($partialView, $data = [], $mergeData = [])
    {
        $factory = new Factory($this->viewPaths);

        return $factory->render($partialView, $data, $mergeData);
    }
}
