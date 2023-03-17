<?php

    class TelegraphText {
        private $title, $text, $author, $published, $slug, $storage;

        public function __construct(string $title, string $text, string $author, string $slug, FileStorage $storage)
        {
            $this->title = $title;
            $this->author = $author;
            $this->slug = $slug;
            $this->text = $text;
            $this->published = date('Y-m-d');
            $this->storage = $storage;
        }

        public function __set($name, $value) {
            switch ($name) {
                case 'title':
                    $this->title = $value;
                    break;
                case 'text':
                    $this->text = $value;
                    self::storeText();
                    break;
                case 'author':
                    if (mb_strlen($value) > 120) {
                        echo 'Имя не может превышать 120 символов';
                        break;
                    }
                    $this->author = $value;
                    break;
                case 'slug':
                    if (!preg_match("/^[a-zA-Z0-9\-_. ]+$/", $value)) {
                        echo 'Указаны недопустимые символы';
                        return;
                    }
                    $this->slug = $value;
                    break;
                case 'published':
                    $date = new DateTimeImmutable($value);
                    if ($date->format('Y-m-d') < date('Y-m-d')) {
                        echo 'Дата публикация не должна быть меньше сегодняшней';
                        return;
                    }

                    $this->published = $value;
                    break;
            }
        }

        public function __get($name) {
            switch ($name) {
                case 'title':
                    return $this->title;
                    break;
                case 'text':
                    return self::loadText();
                    break;
                case 'author':
                    return $this->author;
                    break;
                case 'slug':
                    return $this->slug;
                    break;
                case 'published':
                    return $this->published;
                    break;
            }
        }

        public function getText() {
            return $this->text;
        }

        private function storeText(): void
        {
            $this->storage->update($this->storage->getSlug(), $this);
        }

        private function loadText()
        {
            $text_article = $this->storage->read($this->storage->getSlug());
            return $text_article['text'];
        }
    }
