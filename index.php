<?php

date_default_timezone_set('Europe/Moscow');

require_once 'interfaces/LoggerInterface.php';
require_once 'interfaces/EventListenerInterface.php';
require_once 'traits/LinkFiles.php';
require_once 'autoload.php';

$article = new FileStorage();
$tt = new TelegraphText('New article', 'Text3', 'Alexandr', 'article3', $article);
$article->create($tt);
print_r($article->list());

