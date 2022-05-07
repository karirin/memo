<?php
require_once('../config_2.php');
require('../head.php');
require('../header.php');

if (!empty($_POST)) {
    require_once('user_login_done.php');
}
?>

<body>
    <div class="row">
        <div class="col-6 offset-3 center">
            <h2>ログイン</h2>
            <form method="post" action="#">
                <input type="text" name="name" class="user_name_input" placeholder="ユーザー名">
                <input type="password" name="pass" class="user_pass_input" placeholder="パスワード">
                <div class="flex_btn margin_top">
                    <input class="btn btn-outline-dark" type="submit" value="ログイン">
                    <input class="btn btn-outline-info" type="button" onclick="history.back()" value="戻る">
                </div>
            </form>
        </div>
    </div>
</body>
<?php require_once('../footer.php'); ?>

</html>