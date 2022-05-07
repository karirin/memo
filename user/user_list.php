<?php
if (!empty($_POST['search_user'])) {
    $hoge = $_POST['search_input'];
    header("Location:user_list.php?type=search&query=${hoge}");
}
require_once('../config_1.php');
?>

<body>
    <?php if (basename($_SERVER['PHP_SELF']) === 'user_list.php') : ?>
        <div class="col-6 offset-3">
            <h2 class="center margin_top_bottom">マッチユーザー一覧</h2>
            <form method="post" action="#" class="search_container">
                <div class="input-group mb-2">
                    <input type="text" name="search_input" class="form-control" placeholder="ユーザー検索">
                    <div class="input-group-append">
                        <input type="submit" name="search_user" class="btn btn-outline-secondary">
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
    <div class="col-8 offset-2">
        <?php
        $page_type = $_GET['type'];
        if (basename($_SERVER['PHP_SELF']) === 'user_list.php') {
            $user_class = new User($_SESSION['user_id']);
            $current_user = $user_class->get_user();
        } else {
            $user_class = new User($_GET['page_id']);
            $current_user = $user_class->get_user();
        }

        $users = $user_class->get_users('match', '');

        $block = pagination_block($users);

        if (isset($block[0])) :

            foreach ($block[$_SESSION[$i]] as $user) :
        ?>
                <a href="../user_login/user_top.php?user_id=<?= $current_user['id'] ?>&page_id=<?= $user['id'] ?>&type=main" class="user_link">
                    <div class="user">
                        <?php if (basename($_SERVER['PHP_SELF']) === 'user_top.php') : ?>
                            <div class="user_info top">
                            <?php else : ?>
                                <div class="user_info" style="width: 25%;">
                                <?php endif; ?>
                                <?php if (!empty($user['image'])) : ?>
                                    <img src="data:image/jpeg;base64,<?= $user['image']; ?>">
                                <?php endif; ?>
                                <div class="user_name">
                                    <?= $user['name'] ?>
                                    <?php if ($current_user != $user) : ?>
                                        <object><a href="../message/message.php?user_id=<?= $user['id'] ?>">
                                                <i class="fas fa-envelope-square"></i>
                                            </a></object>
                                    <?php endif; ?>
                                </div>
                                </div>
                                <?php if (!empty($user['profile'])) : ?>
                                    <?php if (basename($_SERVER['PHP_SELF']) === 'user_top.php') : ?>
                                        <div class="user_profile" style="font-size: 1rem;margin-top:1rem;width: 100%;">
                                            <?= $user['profile'] ?></div>
                                    <?php else : ?>
                                        <div class="user_profile"><?= $user['profile'] ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                </a>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <?php require('../pagination.php'); ?>
</body>
<?php require_once('../footer.php'); ?>