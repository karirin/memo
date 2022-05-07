<?php
require_once('../config_2.php');
require_once('../class.php');

try {

    $date = new DateTime();
    $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));

    $message_text = $_POST['text'];
    $message_image = "";
    _debug($_FILES['image']);
    if ($_FILES['image']['tmp_name'] != "") {
        $message_image = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
        image_check($_FILES['image']);
    }
    _debug($message_image); //空
    $user_id = $_SESSION['user_id'];
    $destination_user_id = $_POST['destination_user_id'];

    if ($message_text == '') {
        if ($_FILES['image']['name'] == '') {
            set_flash('danger', 'メッセージ内容が未記入です');
            reload();
        }
    }

    $message_text = htmlspecialchars($message_text, ENT_QUOTES, 'UTF-8');
    $user_id = htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8');

    $dbh = db_connect();
    $sql = 'INSERT INTO message(text,image,user_id,destination_user_id,created_at) VALUES (?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data[] = $message_text;
    $data[] = $message_image;
    $data[] = $user_id;
    $data[] = $destination_user_id;
    $data[] = $date->format('Y-m-d H:i:s');
    $stmt->execute($data);
    $dbh = null;
    $message = new Message();
    if (!$message->check_relation_message($user_id, $destination_user_id)) {
        $message->insert_message($user_id, $destination_user_id);
    } elseif (!$message->check_relation_user_message($user_id, $destination_user_id)) {
        $message->insert_user_message($user_id, $destination_user_id);
    }
    $message->insert_message_count($user_id, $destination_user_id);
    set_flash('sucsess', 'メッセージを送信しました');
    reload();
} catch (Exception $e) {
    error_log($e, 3, "../../php/error.log");
    _debug("メッセージ送信失敗");
    exit();
}

?>

<a href="post_index.php">戻る</a>