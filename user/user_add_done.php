<?php
require_once('../config_1.php');

try {
    $user_name = $_POST['name'];
    $user_pass = $_POST['pass'];
    $user_image = $_POST['image_name'];

    $user_name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
    $user_pass = htmlspecialchars($user_pass, ENT_QUOTES, 'UTF-8');

    $dbh = db_connect();
    $sql = 'INSERT INTO user(name,password,image) VALUES (?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data[] = $user_name;
    $data[] = $user_pass;
    $data[] = $user_image;
    $stmt->execute($data);
    $dbh = null;

    $user = new User(0);
    $current_user = $user->get_newuser($user_name, $user_pass);
    $message = $user_name . 'さんを新規登録しました';
    set_flash('sucsess', $message);
    $_SESSION['login'] = 1;
    $_SESSION['user_id'] = $current_user['id'];
    $_SESSION['user_name'] = $current_user['name'];
    header('Location:../user_login/user_top.php?page_id=' . $current_user['id'] . '&type=main');
} catch (Exception $e) {
    error_log($e, 3, "../../../php/error.log");
    _debug('ユーザー追加失敗');
    exit();
}

require_once('../footer.php');
