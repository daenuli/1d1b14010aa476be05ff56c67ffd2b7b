<?php
require_once __DIR__.'/server.php';

if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
    die;
}