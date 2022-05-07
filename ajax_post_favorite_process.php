<?php
require_once('config.php');

if(isset($_POST)){

  $current_user = get_user($_SESSION['user_id']);
  $post_id = $_POST['post_id'];

  //既に登録されているか確認
  if(check_favolite_duplicate($current_user['id'],$post_id)){
    $action = '解除';
    $sql = "DELETE
            FROM favorite
            WHERE :user_id = user_id AND :post_id = post_id";
  }else{
    $action = '登録';
    $sql = "INSERT INTO favorite(user_id,post_id)
            VALUES(:user_id,:post_id)";
  }
  try{
    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
    echo json_encode("error");
  }
}
require_once('footer.php');
