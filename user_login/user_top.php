<?php
require_once('../config_1.php');

if (isset($_SESSION['login']) == false) :
?>

<body class="top">
    <div class="description">
        自由にメモを書いたり、管理できるサイトです。<br>
        お気に入りのメモをグループ毎に仕分けすることなどができます。
    </div>
    <form method="post" action="user_login_done.php">
        <div class="flex_btn margin_top">
            <input type="hidden" name="name" class="user_name_input" value="test_user">
            <input type="hidden" name="pass" class="user_pass_input" value="pass">
            <input class="test_login btn btn-outline-dark" type="submit" name="test_login" value="test login">
        </div>
    </form>

    <?php else : ?>

    <body class="memo_top">
        <?php require_once("../memo/memo_index.php"); ?>
        <?php endif; ?>

    </body>
    <?php require_once('../footer.php'); ?>

    </html>