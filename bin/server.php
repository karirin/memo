<?php

use Ratchet\Server\IoServer;
use MyApp\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;


require_once 'C:\xampp\htdocs\vendor\autoload.php';

$server = IoServer::factory(new HttpServer(new WsServer(new Chat())), 8080);
$server->run();