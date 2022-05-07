<?php
session_start();
@session_regenerate_id(true);

require('../db_connect.php');
require_once('../function.php');

?>

<body>
    <?php
    try {

        $memo_id = $_POST['id'];
        $memo_image_name = $_POST['image_name'];
        $comment = get_memo($memo_id);

        $dbh = dbConnect();

        if (check_comment($memo_id)) {
            $sql = 'DELETE memo,comment FROM memo INNER JOIN comment ON memo.id = comment.memo_id WHERE memo.id=?';
        } else {
            $sql = 'DELETE memo FROM memo WHERE memo.id=?';
        }
        $stmt = $dbh->prepare($sql);
        $data[] = $memo_id;
        $stmt->execute($data);

        $dbh = null;

        if ($memo_image_name != '') {
            unlink('./image/' . $memo_image_name);
        }
        set_flash('sucsess', '投稿を削除しました');
        reload();
    } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }
    ?>
</body>
<?php require_once('../footer.php'); ?>

</html>