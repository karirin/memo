<?php
require_once('config_2.php');

if (isset($_POST)) {
  $user = new User($_SESSION['user_id']);
  $current_user = $user->get_user();
  $memo_id = $_POST["memo_id"];
  $memo_text = $_POST["memo_text"];
  _debug($_POST);

  try {
    $dbh = db_connect();
    $sql = "UPDATE memo
            SET text = :memo_text
            WHERE id = :memo_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(
      ':memo_id' => $memo_id,
      ':memo_text' => $memo_text
    ));
    set_flash('sucsess', 'プロフィールを更新しました');
    reload();
  } catch (\Exception $e) {
    error_log($e, 3, "../../php/error.log");
    _debug('メモ更新失敗');
  }
}
require_once('footer.php');