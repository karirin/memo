<?php
session_start();
@session_regenerate_id(true);

require('db_connect.php');
require('function.php');
require('class.php');
require('head.php');
require('memo_process.php');
require('withdraw.php');

if (isset($_SESSION['flash'])) {
  $flash_messages = $_SESSION['flash']['message'];
  $flash_type = $_SESSION['flash']['type'];
}
unset($_SESSION['flash']);

$error_messages = array();

require('header.php');
//グローバル変数として定義 
//_debug('', true);
//_debug($flash_messages);

global $n;
global $o;
if (empty($_POST['block'])) {
  $_SESSION[$n] = 0;
}
if (isset($_POST['block'])) {
  switch ($_POST['block']) {
    case '«':
      $_SESSION[$n]--;
      break;
    case '»':
      $_SESSION[$n]++;
      break;
    default:
      $_SESSION[$n] = $_POST['block'] - 1;
      break;
  }
}

// すべてのメモ２ページ目以降を開いていても、正常にメモグループを選択できるよう調整
if (isset($_SESSION['group_select'])) {
  if ($_SESSION['group_select'] == 1) {
    $_SESSION[$o] = 0;
    $_SESSION['group_select'] = 0;
    _debug("config_1.php  :");
    _debug($_SESSION['group_select']);
  }
}