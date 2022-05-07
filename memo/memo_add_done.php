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

    $date = new DateTime();
    $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));

    $memo_text = $_POST['text'];
    $memo_image_name = $_FILES['image_name'];
    $user_id = $_SESSION['user_id'];


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
    $user_id = htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8');

    $dbh = db_connect();
    $sql = 'INSERT INTO memo(text,image,user_id,created_at) VALUES (?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data[] = $memo_text;
    $data[] = $memo_image_name['name'];
    $data[] = $user_id;
    $data[] = $date->format('Y-m-d H:i:s');
    $stmt->execute($data);
    $dbh = null;

    set_flash('sucsess', 'メモしました');
    header('Location:../user_login/user_top.php?type=main&page_id=current_user');
} catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

?>

<a href="memo_index.php">戻る</a>