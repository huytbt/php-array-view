# PHP Array View

[![Build Status](https://travis-ci.org/huytbt/php-array-view.svg)](https://travis-ci.org/huytbt/php-array-view)

An array view engine for PHP.

> Reference from https://github.com/gergoerdosi/hapi-json-view

## Installation
```sh
$ composer require huytbt/php-array-view
```

## Usage
Define helper (example bootstrap.php)
```php
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
            $viewPaths = [ dirname(__FILE__) . '/views' ];    // array of view path
            $factory = new \ChickenCoder\ArrayView\Factory($viewPaths);
        }

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->render($view, $data, $mergeData);
    }
}
```
Code in controller (Example routes.php of Laravel)
```php
<?php

Route::get('/articles/{id}', function ($id) {

    $article = Article::find($id);
    return response()->json(arrayView('article', [ 'article' => $article ]));
});
```
views/article.array.php
```php
<?php

$this->set('title', $article->title);
$this->set('author', function ($section) use ($article) {

    $section->set('name', $article->author->name);
});
```
This template generates the following object:
```php
[
    'title' => 'Example Title',
    'author' => [
        'name' => 'John Doe'
    ]
]
```

## Functions

### set()
It assigns a value to a key.
```php
<?php

$this->set('title', 'Example Title');

// => [ 'title' => 'Example Title' ]
```
The value can be a function. If `$this->set()` is called with a key, it creates an array:
```php
<?php

$this->set('author', function ($section) {

    $section->set('name', 'John Doe');
});

// => [ 'author' => [ 'name' => 'John Doe' ] ]
```
If `$section->set()` is called without a key, it assign the value to the parent key:
```php
<?php

$this->set('title', function ($section) {

    $section->set('Example Title');
});

// => [ 'title' => 'Example Title' ]
```

### each()
It creates a new array by iterating through an existing array:
```php
<?php

$numbers = ['one', 'two'];

$this->set('numbers', $this->each($numbers, function ($section, $item) {

    $section->set('number', $item);
}));

// => [ 'numbers' => [[ 'number' => 'one' ], [ 'number' => 'two' ]] ]
```

### extract()
It extracts values from an object and assigns them to the result object:
```php
<?php

$article = [
    'title' => 'Example Title',
    'body' => 'Example Body',
    'created' => '2015-07-16'
];

$this->extract($article, ['title', 'created']);

// => [ 'title' => 'Example Title', 'created' => '2015-07-16' ]
```

### helper()
Helpers can be registered through the engine.
views/helpers/uppercase.helper.php
```php
<?php

return function ($text)
{
    return strtoupper($text);
};

```
views/atricle.array.php
```php
<?php

$this->set('title', $this->helper('uppercase', $title));

// [ 'title' => 'EXAMPLE TITLE' ]
```

### partial()
views/partials/author.array.php
```php
<?php

$this->set('name', $author->name);
$this->set('gender', $author->gender);
```
views/article.array.php
```php
<?php

$this->set('title', $article->title);
$this->set('author', $this->partial('partials/author', [ 'author' => $article->author ]));

// [ 'title' => 'Example Title', 'author' => [ 'name' => 'John Doe', 'gender' => 'female' ] ]
```
