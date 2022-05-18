<?php
require_once('config_2.php');

if (isset($_POST)) {
  $user = new User($_SESSION['user_id']);
  $current_user = $user->get_user();
  $memo_id = $_POST["memo_id"];
  $memo_text = $_POST["memo_text"];
  $ball_id = $_POST['ball_id'];
  _debug($memo_text);

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
  } catch (\Exception $e) {
    error_log($e, 3, "../../php/error.log");
    _debug('メモ更新失敗');
  }

  if ($_POST["delete_flg"]) {
    try {
      $dbh = db_connect();
      $sql = "DELETE FROM memo
              WHERE id = :memo_id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(
        ':memo_id' => $ball_id
      ));
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('メモ更新失敗');
    }
  }
}
require_once('footer.php');