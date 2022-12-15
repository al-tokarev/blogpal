<?php

$textStorage = [];

function add(array &$textStorage, string $title, string $text): void {
    $new_post = ["title" => $title, "text" => $text];
    $textStorage[] = $new_post;
}

function remove(array &$textStorage, int $id_post): bool {
    if (isset($textStorage[$id_post])) {
        unset($textStorage[$id_post]);
        return true;
    } else {
        return false;
    }
}

function edit(int $id_post, string $title, string $text, array &$textStorage): bool {
    if (isset($textStorage[$id_post])) {
        $textStorage[$id_post]['title'] = $title;
        $textStorage[$id_post]['text'] = $text;
        return true;
    } else {
        return false;
    }
}

add($textStorage, "Новая запись", "Текст новой записи");
add($textStorage, "Новая запись2", "Текст новой записи2");

echo remove($textStorage, 0);
echo remove($textStorage, 5) . PHP_EOL;

echo edit(1, "Измененный заголовок", "Измененный текст", $textStorage) . PHP_EOL;

print_r($textStorage);

echo edit(5, "Измененный заголовок", "Измененный текст", $textStorage) . PHP_EOL;
