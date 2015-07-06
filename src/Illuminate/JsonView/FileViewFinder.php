<?php

namespace ChickenCoder\Illuminate\JsonView;

use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\FileViewFinder as BaseFileViewFinder;

class FileViewFinder extends BaseFileViewFinder
{
    /**
     * Register a view extension with the finder.
     *
     * @var array
     */
    protected $extensions = ['json.php', 'php'];
}
