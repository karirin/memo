<?php
require_once('../config_2.php');

try {

    $date = new DateTime();
    $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));

    $comment_text = $_POST['text'];
    $comment_image_name = $_FILES['image_name'];
    if (!empty($_POST['comment_id'])) {
        $comment_id = $_POST['comment_id'];
    }
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['id'];

    if ($comment_text == '') {
        set_flash('danger', 'コメントが空です');
        reload();
    }

    image_check($comment_image_name);

    $comment_text = htmlspecialchars($comment_text, ENT_QUOTES, 'UTF-8');
    $user_id = htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8');

    $dbh = db_connect();
    $sql = 'INSERT INTO comment(text,image,user_id,created_at,post_id,comment_id) VALUES (?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data[] = $comment_text;
    $data[] = $comment_image_name['name'];
    $data[] = $user_id;
    $data[] = $date->format('Y-m-d H:i:s');
    $data[] = $post_id;
    if (!empty($comment_id)) {
        $data[] = $comment_id;
    } else {
        $data[] = '';
    }
    $stmt->execute($data);
    $dbh = null;

    set_flash('sucsess', 'コメントを追加しました');
    reload();
} catch (Exception $e) {
    error_log($e, 3, "../../php/error.log");
    _debug('コメント送信失敗');
    exit();
}