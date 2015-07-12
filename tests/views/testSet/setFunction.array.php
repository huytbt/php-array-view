<?php

$this->set('author', function ($section) use ($author) {

    $section->set('location', 'en');
    $section->set('name', $author);
});
