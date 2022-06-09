<?php
require_once('config_2.php');
if (isset($_POST)) {
  // $memo_id = $_POST["memo_id"];
  // $memo_text = $_POST["memo_text"];
  // $ball_id = $_POST['ball_id'];
  // try {
  //   $dbh = db_connect();
  //   $sql = "UPDATE memo
  //           SET text = :memo_text
  //           WHERE id = :memo_id";
  //   $stmt = $dbh->prepare($sql);
  //   $stmt->execute(array(
  //     ':memo_id' => $memo_id,
  //     ':memo_text' => $memo_text
  //   ));
  // } catch (\Exception $e) {
  //   error_log($e, 3, "../../php/error.log");
  //   _debug('メモ更新失敗');
  // }

  // if ($_POST["delete_flg"]) {
  //   try {
  //     $dbh = db_connect();
  //     $sql = "DELETE FROM memo
  //             WHERE id = :memo_id";
  //     $stmt = $dbh->prepare($sql);
  //     $stmt->execute(array(
  //       ':memo_id' => $ball_id
  //     ));
  //   } catch (\Exception $e) {
  //     error_log($e, 3, "../../php/error.log");
  //     _debug('メモ更新失敗');
  //   }
  // }
  if ($_POST["memo_group_list"]) {
    try {
      _debug("===================================");
      $id = $_POST["group_id"];
      $memo_id = $_POST["memo_group_id"];
      _debug("group_id");
      _debug($id);
      $dbh = db_connect();
      $sql = "select memo_id
            from memo_group
            WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(
        ':id' => $id
      ));
      _debug("memo_id");
      _debug($memo_id);
      $before_memo_id = $stmt->fetch();
      _debug("before_memo_id");
      _debug($before_memo_id);
      $memo_id .= $before_memo_id["memo_id"];
      _debug("memo_id");
      _debug($memo_id);
      _debug("===================================");
      $dbh = db_connect();
      $sql = "UPDATE memo_group
            SET memo_id = :memo_id
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
}
require_once('footer.php');