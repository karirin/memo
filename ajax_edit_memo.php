<?php
require_once('config_2.php');
_debug("www");
if (isset($_POST)) {
  _debug("POST");
  $user = new User($_SESSION['user_id']);
  $current_user = $user->get_user();
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
  if ($_POST["ball_id"]) {
    if ($_POST["create_flg"]) {
      try {
        // memo_groupの最大IDを取得
        $dbh = db_connect();
        $sql = "SELECT max(id)
          FROM memo_group";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $memo_id = $stmt->fetch();
        $dbh = db_connect();
        $ball_id = $_POST["ball_id"];
        // memo_groupが空の場合はデータを追加
        if ($memo_id["max(id)"] != "") {
          // 以下、重複追加を防ぐ処理
          $max_memo_id = $memo_id["max(id)"];
          $dbh = db_connect();
          $sql = "SELECT *
          FROM memo_group
          where id=:id and memo_id like '%':memo_id'%'"; //ここはlike句にする
          $stmt = $dbh->prepare($sql);
          $stmt->execute(array(
            ':id' => $max_memo_id,
            ':memo_id' => $ball_id  //ここがnullになっているのかも
          ));
          $memo_flg = $stmt->fetch();
          // memo_groupにmemo_idが無ければ追加
          if ($memo_flg == "") {
            $dbh = db_connect();
            $sql = "insert into memo_group(memo_id) values(:ball_id)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(
              ':ball_id' => $ball_id
            ));
          }
        } else {
          $dbh = db_connect();
          $sql = "insert into memo_group(memo_id) values(:ball_id)";
          $stmt = $dbh->prepare($sql);
          $stmt->execute(array(
            ':ball_id' => $ball_id
          ));
        }
      } catch (\Exception $e) {
        error_log($e, 3, "../php/error.log");
        _debug('メモ更新失敗');
      }
    }
  }
}
require_once('footer.php');