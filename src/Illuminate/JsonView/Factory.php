<?php

namespace ChickenCoder\Illuminate\JsonView;

use InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;

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
    protected $extension = 'json.php';

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
        $view = $this->normalizeName($view);

        $path = $this->getViewPath($view);

        if ($path === null) {
            throw new InvalidArgumentException("Unrecognized extension in file: {$path}/{$view}");
        }

        $data = array_merge($mergeData, $this->parseData($data));

        extract($data);

        include($path . '/' . $view . '.' . $this->extension);

        return $this->results;
    }

    /**
     * Normalize a view name.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function normalizeName($name)
    {
        return str_replace('.', '/', $name);
    }

    /**
     * Parse the given data into a raw array.
     *
     * @param  mixed  $data
     * @return array
     */
    protected function parseData($data)
    {
        return $data instanceof Arrayable ? $data->toArray() : $data;
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
                return $viewPath;
            }
        }

        return null;
    }

    /**
     * Set value to results
     * 
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function jsonSet($key, $value = null)
    {
        $value === null && $value = json_decode("{}");
        $this->results[$key] = $value;
    }

    /**
     * Render partial json view
     * 
     * @param  [type] $partialView [description]
     * @param  array  $data        [description]
     * @param  array  $mergeData   [description]
     * @return [type]              [description]
     * @author HuyTBT <huytbt@gmail.com>
     */
    protected function jsonPartial($partialView, $data = [], $mergeData = [])
    {
        $factory = new Factory($this->viewPaths);

        return $factory->render($partialView, $data, $mergeData);
    }
}
