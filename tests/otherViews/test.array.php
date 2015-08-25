<?php

$this->set('title', $article->title);
$this->set('author', function ($section) use ($article) {

    $section->set('name', $article->author->name);
});
