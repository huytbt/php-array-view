# Laravel Array View

An array view engine for the Laravel PHP framework.

> Reference from https://github.com/gergoerdosi/hapi-json-view

## Installation

```sh
$ composer require huytbt/laravel-array-view
```

## Usage

views/article.array.php
```php
<?php

$this->set('title', $article->title);
$this->set('author', function ($view) {

    $view->set('name', $article->author->name);
});
```

## Functions

### set()

### each()

### extract()
`Coming soon`

### helper()
`Coming soon`

### partial()
