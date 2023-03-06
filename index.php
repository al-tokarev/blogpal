<?php

date_default_timezone_set('Europe/Moscow');

interface LoggerInterface {
    public function logMessage();
    public function lastMessages();
}

interface EventListenerInterface {
    public function attachEvent();
    public function detouchEvent();
}

class TelegraphText {
    public $title, $text, $author, $published, $slug;

    public function __construct(string $title, string $text, string $author, string $slug)
    {
        $this->title = $title;
        $this->text = $text;
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('Y-m-d H:i:s');
    }
}

abstract class Storage implements LoggerInterface, EventListenerInterface {
    public function logMessage(){}
    public function lastMessages(){}
    public function attachEvent(){}
    public function detouchEvent(){}

    abstract public function create($object);
    abstract public function read($slug);
    abstract public function update($id, $object);
    abstract public function delete($id);
    abstract public function list();
}

abstract class View {
    public $storage;

    public function __construct($article_array) {
        $this->storage = $article_array;
    }

    abstract public function displayTextById($id);
    abstract public function displayTextByUrl($url);
}

abstract class User implements EventListenerInterface
{
    public $id, $name, $role;

    abstract public function getTextToEdit();

    public function attachEvent(){}
    public function detouchEvent(){}
}

class FileStorage extends Storage{
    public $file_link = 'articles/';

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

        $article = ['text'=>$object->text, 'title'=>$object->title, 'author'=>$object->author, 'published'=>$object->published];
        file_put_contents($this->file_link . $object->slug, serialize($article));

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
            $article['text'] = $object->text;
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
        foreach (array_diff(scandir(__DIR__ . '/' . $this->file_link), array('..', '.')) as $file) {
            $list[] = unserialize(file_get_contents($this->file_link . $file));
        }
        return $list;
    }

}

$article = new FileStorage();
//$article->create(new TelegraphText('New article', 'Text', 'Alexandr', 'article'));