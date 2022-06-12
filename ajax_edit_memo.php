<?php
require_once('config_2.php');
if (isset($_POST)) {
  $memo_id = $_POST["memo_id"];
  $memo_text = $_POST["memo_text"];
  $ball_id = $_POST['ball_id'];
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
  if ($_POST["memo_group_list"]) {
    try {
      $id = $_POST["group_id"];
      _debug($id);
      $memo_id = $_POST["memo_group_id"];
      _debug($memo_id);
      $dbh = db_connect();
      $sql = "UPDATE memo_group
            SET memo_id = concat(memo_id, :memo_id) 
            WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(
        ':id' => $id,
        ':memo_id' => $memo_id
      ));
    } catch (\Exception $e) {
      error_log($e, 3, "../php/error.log");
      _debug('メモ更新失敗');
    }
  }
  if ($_POST["memo_group_create"]) {
    try {
      $memo_id = $_POST["memo_group_id"];
      $dbh = db_connect();
      $sql = "insert into memo_group(memo_id) values(:memo_id)";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(
        ':memo_id' => $memo_id
      ));
    } catch (\Exception $e) {
      error_log($e, 3, "../php/error.log");
      _debug('メモ更新失敗');
    }
  }
}
require_once('footer.php');