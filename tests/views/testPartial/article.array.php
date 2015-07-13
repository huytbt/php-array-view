<?php

$this->set('title', $article->title);
$this->set('author', $this->partial('testPartial/partials/author', [ 'author' => $article->author ]));
