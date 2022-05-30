<?php
require_once('config_2.php');

if (isset($_POST)) {
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
      $dbh = db_connect();
      $sql = "SELECT max(id)
          FROM memo_group";
      try {
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $memo_id = $stmt->fetch();
        $dbh = db_connect();
        $ball_id = $_POST["ball_id"];
        _debug("a    ");
        _debug($memo_id["max(id)"]);
        _debug("a    ");
        _debug($ball_id);
        _debug("a    ");
        if (!is_null($memo_id["max(id)"])) {
          $max_memo_id = $memo_id["max(id)"];
          $dbh = db_connect();
          $sql = "SELECT *
          FROM memo_group
          where id=:id and memo_id =:memo_id";
          $stmt = $dbh->prepare($sql);
          $stmt->execute(array(
            ':id' => $max_memo_id,
            ':memo_id' => $ball_id
          ));
          $memo_flg = $stmt->fetch();
          if (is_null($memo_flg)) {
            $dbh = db_connect();
            $sql = "insert into memo_group( memo_id ) values(:ball_id)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(
              ':ball_id' => $_POST["ball_id"]
            ));
          } else {
            _debug("pppppppppppppppppppppppppppp");
          }
        } else {
          _debug("www");
          $dbh = db_connect();
          $sql = "insert into memo_group( memo_id ) values(:ball_id)";
          $stmt = $dbh->prepare($sql);
          $stmt->execute(array(
            ':ball_id' => $_POST["ball_id"]
          ));
        }
      } catch (\Exception $e) {
        error_log($e, 3, "../php/error.log");
        _debug('フォロー確認失敗');
      }
    }
  }
}
require_once('footer.php');