<?php

use Predis\Client;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/middleware.php';
require_once __DIR__.'/config/app.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('success' => false, 'message' => 'Method not allowed!'));
    exit;
}

try {
    $redis = new Client([
        'scheme' => 'tcp',
        'host' => $_ENV['REDIS_HOST'],
        'port' => 6379,
    ]);

    $data = [ 
            'email' => $_POST['email'],
            'title' => $_POST['title'],
            'text' => $_POST['text']
    ];
            
    $redis->rpush("jobs", json_encode($data));

    echo json_encode(array('success' => true, 'message' => 'Message sent'));
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => json_encode($e->getMessage())));
}