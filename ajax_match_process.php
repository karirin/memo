<?php
require_once('config_1.php');

if (isset($_POST)) {

  $current_user_id = $_POST['current_user_id'];
  $user_id = $_POST['user_id'];
  $flash_type = 'sucsess';
  $sql = "INSERT INTO `match`(user_id,match_user_id)
  VALUES(:user_id,:matched_user_id)";
  try {
    $dbh = db_connect();
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user_id, ':matched_user_id' => $user_id));
    if (count(check_matchs($user_id, $current_user_id)) == 2) {
      setcookie('username', 1, time() + 60 * 60 * 24 * 7);
    } else {
      setcookie('username', "", time() - 60);
    }
  } catch (\Exception $e) {
    error_log($e, 3, "../php/error.log");
    _debug('マッチ失敗');
    echo json_encode("error");
  }
}

?>

<?php
require_once('footer.php');