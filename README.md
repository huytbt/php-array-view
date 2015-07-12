# Laravel Array View

An array view engine for the Laravel PHP framework.

> Reference from https://github.com/gergoerdosi/hapi-json-view

## Installation
```sh
$ composer require huytbt/laravel-array-view
```

## Usage
routes.php
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
$this->set('title', 'Example Title');

// => [ 'title' => 'Example Title' ]
```
The value can be a function. If `$this->set()` is called with a key, it creates an array:
```php
$this->set('author', function ($section) {

    $section->set('name', 'John Doe');
});

// => [ 'author' => [ 'name' => 'John Doe' ] ]
```
If `$section->set()` is called without a key, it assign the value to the parent key:
```php
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
`Coming soon`

### helper()
`Coming soon`

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
$this->set('author', $this->partial('views/partials/author', [ 'author' => $article->author ]));
```