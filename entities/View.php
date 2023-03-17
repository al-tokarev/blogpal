<?php

    abstract class View {
        public $storage;

        public function __construct($article_array) {
            $this->storage = $article_array;
        }

        abstract public function displayTextById($id);
        abstract public function displayTextByUrl($url);
    }
