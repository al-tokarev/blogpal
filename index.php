<?php

date_default_timezone_set('Europe/Moscow');

class TelegraphText {
    public $title, $text, $author, $published, $slug;

    public function __construct(string $author, string $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('Y-m-d H:i:s');
    }

    public function storeText(): void
    {
        $article = ['text'=>$this->text, 'title'=>$this->title, 'author'=>$this->author, 'published'=>$this->published];
        file_put_contents($this->slug, serialize($article));
    }

    public function loadText()
    {
        if (file_exists($this->slug)) {
            $article_array = unserialize(file_get_contents($this->slug));

            $this->title = $article_array['title'];
            $this->text = $article_array['text'];
            $this->author = $article_array['author'];
            $this->published = $article_array['published'];

            return $this->text;
        } else echo 'Запрашиваемого файла не существует';
    }

    public function editText(string $title, string $text)
    {
        $this->title = $title;
        $this->text = $text;
    }
}

$article = new TelegraphText('Alexandr', 'test_text_file.txt');
$article->editText('Title article','Text article');
$article->storeText();
echo $article->loadText();