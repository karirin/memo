<?php
session_start();
@session_regenerate_id(true);

require('../db_connect.php');
require_once('../function.php');

?>

<body>
    <?php
    try {
        $id = $_POST['id'];
        $dbh = db_connect();
        $sql = "DELETE FROM memo
                WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':id' => $id
        ));
        set_flash('sucsess', '投稿を削除しました');
        reload();
    } catch (Exception $e) {
        error_log($e, 3, "../../php/error.log");
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }
    ?>
</body>
<?php require_once('../footer.php'); ?>

</html>