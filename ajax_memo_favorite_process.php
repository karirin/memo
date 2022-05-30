<?php
require_once('config_1.php');

if (isset($_POST)) {
  $user = new User($_SESSION['user_id']);
  $current_user = $user->get_user();
  echo json_encode($_POST['memo_id']);
  $memo_id = $_POST['memo_id'];
  $memo = new Memo($memo_id);
  //既に登録されているか確認
  if (check_favolite_duplicate($current_user['id'], $memo_id)) {
    $action = '解除';
    $sql = "DELETE
            FROM favorite
            WHERE :user_id = user_id AND :memo_id = memo_id";
  } else {
    $action = '登録';
    $sql = "INSERT INTO favorite(user_id,memo_id)
            VALUES(:user_id,:memo_id)";
  }
  try {
    $dbh = db_connect();
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'], ':memo_id' => $memo_id));
    $return = array(
      'memo_count' => current($memo->get_memo_favorite_count())
    );
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($return);
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error', ERR_MSG1);
    echo json_encode("error");
  }
}
require_once('footer.php');