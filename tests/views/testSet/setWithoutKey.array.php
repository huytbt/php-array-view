<?php

$this->set('author', function ($section) use ($author) {

    $section->set($author);
});
