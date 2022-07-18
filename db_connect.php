<?php
function db_connect()
{
  if ($_SERVER["HTTP_HOST"] == "localhost") {
    $dsn = 'mysql:dbname=memo;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
  } else {
    $dsn = 'mysql:dbname=heroku_9791d250e1037e;host=us-cdbr-east-06.cleardb.net;charset=utf8';
    $user = 'bf902765a36179';
    $password = '52827b7c';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
  }
}