<?php
session_start();
@session_regenerate_id(true);

require_once('../db_connect.php');
require_once('../function.php');

if (isset($_SESSION['flash'])) {
    $flash_messages = $_SESSION['flash']['message'];
    $flash_type = $_SESSION['flash']['type'];
}
unset($_SESSION['flash']);

$error_messages = array();

try {
    $memo_id = $_POST['id'];
    $memo_text = $_POST['text'];
    $memo_image_name_old = $_POST['image_name_old'];
    $memo_image_name = $_FILES['image_name'];

    if ($memo_text == '') {
        set_flash('danger', '投稿内容が未記入です');
        reload();
    }

    if ($memo_image_name['size'] > 0) {
        if ($memo_image_name['size'] > 1000000) {
            set_flash('danger', '画像が大きすぎます');
            reload();
        } else {
            move_uploaded_file($memo_image_name['tmp_name'], './image/' . $memo_image_name['name']);
        }
    }

    $memo_text = htmlspecialchars($memo_text, ENT_QUOTES, 'UTF-8');
    $memo_id = htmlspecialchars($memo_id, ENT_QUOTES, 'UTF-8');

    $dbh = dbConnect();
    $sql = 'UPDATE memo SET text=?,image=? WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $memo_text;
    $data[] = $memo_image_name['name'];
    $data[] = $memo_id;
    $stmt->execute($data);

    $dbh = null;

    if ($memo_image_name_old != '' && $memo_image_name_old != $memo_image_name['name']) {
        unlink('./image/' . $memo_image_name_old);
    }
} catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

set_flash('sucsess', '更新しました');
reload();

require_once('../footer.php');