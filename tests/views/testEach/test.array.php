<?php

$this->set('numbers', $this->each($numbers, function ($section, $item) {

    $section->set('number', $item);
}));
