<body>

    <?php
    require_once('../config_1.php');

    $user_name = $_POST['name'];
    $user_pass = $_POST['pass'];
    $user_pass2 = $_POST['pass2'];
    $user_image = base64_encode(file_get_contents($_FILES['image']['tmp_name']));

    $user_name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
    $user_pass = htmlspecialchars($user_pass, ENT_QUOTES, 'UTF-8');
    $user_pass2 = htmlspecialchars($user_pass2, ENT_QUOTES, 'UTF-8');
    $user = new User(0);

    if (empty($user_name) && empty($user_pass) && empty($user_pass2)) {
        $error_messages[] = "ユーザー名とパスワードを入力してください";
    } else if (empty($user_name)) {
        $error_messages[] = "ユーザー名を入力してください";
    } else if ($user->check_user($user_name)) {
        $error_messages[] = 'このユーザー名は既に使用されています';
    } else if (empty($user_pass)) {
        $error_messages[] = "パスワードを入力してください";
    } else if ($user_pass != $user_pass2) {
        $error_messages[] = 'パスワードが一致しません';
    }
    set_flash('error', $error_messages);

    if (!empty($error_messages)) {
        header("Location:/user/user_add.php");
    }

    $user_pass = md5($user_pass);
    set_flash('error', $error_messages);

    if (!empty($_FILES['image']['tmp_name'])) {
        $_SESSION['image']['data'] = file_get_contents($_FILES['image']['tmp_name']);
        $_SESSION['image']['type'] = exif_imagetype($_FILES['image']['tmp_name']);
    }
    ?>
    <div class="row center">
        <div class="col-8 offset-2">
            <h2 class="margin_top_bottom">こちらのユーザーを追加しますか</h2>
            <img src="user_add_check_image.php" class="user_newimage">
            <h3 class="margin_top_bottom"><?= $user_name ?></h3>
            <form method="post" action="user_add_done.php">
                <input type="hidden" name="name" value="<?= $user_name ?>">
                <input type="hidden" name="pass" value="<?= $user_pass ?>">
                <input type="hidden" name="image_name" value="<?= $user_image ?>">
                <div class="flex_btn">
                    <input type="submit" class="btn btn-outline-dark" value="登録">
                    <input type="button" class="btn btn-outline-info modal_close" onclick="history.back()" value="戻る">
                </div>
            </form>
        </div>
    </div>

</body>
<?php require_once('../footer.php'); ?>

</html>