<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/middleware.php';
require_once __DIR__.'/config/app.php';

header('Content-Type: application/json; charset=utf-8');

$dsn = 'pgsql:dbname='.$_ENV['DB_DATABASE'].';host='.$_ENV['DB_HOST'].';port=5432';

$pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$data = $pdo->query('SELECT * FROM email_sent ORDER BY id DESC');

$item = [];
while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
    $item[] = [
        'id' => $row['id'],
        'email_destination' => $row['email'],
        'email_title' => $row['title'],
        'email_body' => $row['text'],
    ];
}
echo json_encode($item);