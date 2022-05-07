<?php
require_once('config_2.php');

if (isset($_POST)) {
    $_SESSION['user_id'] = 7;
    $user_skill = $_POST['skills'];

    try {
        $dbh = db_connect();
        $sql = "UPDATE user
            SET skill = :skill
            WHERE id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':user_id' => $_SESSION['user_id'],
            ':skill' => $user_skill
        ));
        set_flash('sucsess', 'プロフィールを更新しました');
        reload();
    } catch (\Exception $e) {
        error_log($e, 3, "../../php/error.log");
        _debug('プロフィール更新失敗');
    }
}
require_once('footer.php');