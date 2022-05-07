<?php
require_once('../config_1.php');

try {

    $comment_id = $_GET['comment_id'];
    $user_id = $_GET['user_id'];
    $post_id = $_GET['post_id'];

    $comment = new Comment($comment_id);
    $comment = $comment->get_comment();

    $dbh = null;

    if ($comment['image'] == '') {
        $disp_image = '';
    } else {
        $disp_image = '<img src="./image/' . $comment['image'] . '">';
    }

    $dbh = db_connect();
    $sql = 'DELETE FROM comment WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $comment_id;
    $stmt->execute($data);

    $dbh = null;

    if ($comment['image'] != '') {
        unlink('./image/' . $comment['image']);
    }
} catch (Exception $e) {
    error_log($e, 3, "../../php/error.log");
    _debug('コメント削除確認失敗');
    exit();
}

?>


<p><?php $comment['text'] ?></p>
このコメントを削除してもよろしいでしょうか。

<form method="post" action="/comment/comment_delete_done.php">
    <input type="hidden" name="id" value="<?= $comment['id']; ?>">
    <input type="hidden" name="image_name" value="<?= $comment['image']; ?>">
    <input type="hidden" name="user_id" value="<?= $user_id; ?>">
    <input type="hidden" name="post_id" value="<?= $post_id; ?>">
    <input type="button" onclick="history.back()" value="戻る">
    <input type="submit" value="OK">
</form>
</body>
<?php require_once('../footer.php'); ?>

</html>