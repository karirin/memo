<?php
require_once('../config_1.php');

if (isset($_SESSION['login']) == false) :
?>

<body class="top">
    <div class="description">
        ペアコードはエンジニアとマッチできるサイトです。<br>
        気になるエンジニアとマッチングしてメッセージなどで情報共有することができます。
    </div>
    <form method="post" action="user_login_done.php">
        <div class="flex_btn margin_top">
            <input type="hidden" name="name" class="user_name_input" value="test_user">
            <input type="hidden" name="pass" class="user_pass_input" value="pass">
            <input class="test_login btn btn-outline-dark" type="submit" name="test_login" value="test login">
        </div>
    </form>

    <?php else : ?>

    <body>
        <?php require_once("../user/user_disp.php"); ?>
        <?php endif; ?>

    </body>
    <?php require_once('../footer.php'); ?>

    </html>