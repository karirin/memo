<?php
require_once('config_1.php');
// post送信されていた場合
if (!empty($_POST['withdraw'])) {
    $user = new User($_SESSION['user_id']);
    $current_user = $user->get_user();
    $dbh = db_connect();
    $argument = ["post", "favorite", "comment", "follow", "follower", "message", "message_relation"];
    for ($i = 0; $i < 7; $i++) {
        $count[$i] = $user->get_user_count($argument[$i]);
    }
    $sql = 'DELETE FROM user where id = :user_id';
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $user_id));
    if ($count[0][0] == 1) {
        $sql = 'DELETE FROM post where user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
    }
    if ($count[1][0] == 1) {
        $sql = 'DELETE FROM favorite where user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
    }
    if ($count[2][0] == 1) {
        $sql = 'DELETE FROM comment where user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
    }
    if ($count[3][0] == 1) {
        $sql = 'DELETE FROM follow where user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
    }
    if ($count[4][0] == 1) {
        $sql = 'DELETE FROM follower where user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
    }
    if ($count[5][0] == 1) {
        $sql = 'DELETE FROM message where user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
    }
    if ($count[6][0] == 1) {
        $sql = 'DELETE FROM message_relation where user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
    }
    $dbh = null;

    // セッション削除
    session_destroy();
    $_SESSION = array();
    header("Location:/user_login/user_top.php");
    exit();
}
?>
<div class="modal_withdraw"></div>
<div class="withdraw_process">
    <h4 class="center">本当に退会しますか？</h4>

    <form action="#" method="post">
        <div class="withdraw_btn">
            <button class="btn btn-outline-danger" name="withdraw" value="withdraw" type="submit">退会する</button>
            <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
        </div>
    </form>

</div>