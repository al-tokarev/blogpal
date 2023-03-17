<?php

    require_once 'Storage.php';

    class FileStorage extends Storage{
        use LinkFiles;

        public $object_slug = null;

        public function create($object) {
            $i = 1;

            if (file_exists($this->file_link . $object->slug . date('y-m-d') . '.txt')) {
                while (file_exists($this->file_link . $object->slug . date('y-m-d') . '.txt')) {
                    if (file_exists($this->file_link . $object->slug . date('y-m-d') . '_' . $i . '.txt')) {
                        $i++;
                    } else {
                        $object->slug = $object->slug . date('y-m-d') . '_' . $i . '.txt';
                    }
                }
            } else {
                $object->slug = $object->slug . date('y-m-d') . '.txt';
            }

            $this->object_slug = $object->slug;
            $article = ['text'=>$object->getText(), 'title'=>$object->title, 'author'=>$object->author, 'published'=>$object->published];
            file_put_contents($this->file_link . $object->slug, serialize($article));
            return $object->slug;
        }

        public function getSlug() {
            return $this->object_slug;
        }

        public function read($slug) {
            if (!file_exists($this->file_link . $slug)) {
                return 'Запрашиваемого файла не существует';
            }

            return unserialize(file_get_contents($this->file_link . $slug));
        }

        public function update($slug, $object) {
            if (file_exists($this->file_link . $slug)) {
                $article = unserialize(file_get_contents($this->file_link . $slug));
                $article['title'] = $object->title;
                $article['text'] = $object->getText();
                $article['author'] = $object->author;
                if (file_put_contents($this->file_link . $slug, serialize($article))) return $article;
            } else return 'Запрашиваемого файла не существует';
        }

        public function delete($slug) {
            if (file_exists($this->file_link . $slug)) {
                unlink($slug);
            } else return 'Запрашиваемого файла не существует';
        }

        public function list() {
            $list = [];
            foreach (array_diff(scandir('./' . $this->file_link), array('..', '.')) as $file) {
                $list[] = unserialize(file_get_contents($this->file_link . $file));
            }
            return $list;
        }
    }