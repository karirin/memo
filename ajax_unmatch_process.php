<?php
require_once('config_1.php');
if (isset($_POST)) {

  $current_user_id = $_POST['current_user_id'];
  $user_id = $_POST['user_id'];
  $flash_type = 'sucsess';
  $sql = "INSERT INTO `match`(user_id,match_user_id,unmatch_flg)
  VALUES(:user_id,:match_user_id,1)";
  try {
    $dbh = db_connect();
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user_id, ':match_user_id' => $user_id));
  } catch (\Exception $e) {
    error_log($e, 3, "../php/error.log");
    _debug('アンマッチ失敗');
    echo json_encode("error");
  }
}

?>

<?php
require_once('footer.php');