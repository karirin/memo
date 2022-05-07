<?php
if (isset($_SESSION['login']) == true) {
    if (isset($_GET['page_id']) && $_GET['page_id'] != 'current_user') {
        $user = new User($_GET['page_id']);
        $current_user = $user->get_user();
    } else {
        $user = new User($_SESSION['user_id']);
        $current_user = $user->get_user();
    }
}
?>
<div class="modal_prof"></div>
<div class="slide_prof">
    <a class="prof_close" href="#">
        <p><i class="fas fa-angle-right"></i></p>
    </a>
    <div class="profile">
        <form method="post" action="../ajax_edit_profile.php" enctype="multipart/form-data">
            <div class="edit_profile_img">
                <label>
                    <div class="fa-image_range">
                        <i class="far fa-image"></i>
                    </div>
                    <input type="file" name="image_name" id="edit_profile_img_narrower" accept="image/*" multiple>
                </label>
                <img name="profile_image" class="editing_profile_img" src="/user/image/<?= $current_user['image'] ?>">
                <label>
                    <i class="far fa-times-circle profile_clear"></i>
                    <input type="button" id="profile_clear">
                </label>
            </div>
            <img src="/user/image/<?= $current_user['image'] ?>" class="mypage">
            <h3 class="profile_name_narrower"><?= $current_user['name'] ?></h3>
            <p class="comment_narrower"><?= $current_user['profile'] ?></p>
            <input type="hidden" name="id" class="user_id" value="<?= $current_user['id'] ?>">
            <input type="file" name="image" class="image" value="<?= $current_user['image'] ?>" style="display:none;">
            <div class="btn_flex">
                <input type="submit" class="btn btn-outline-dark" value="編集完了">
                <button class="btn btn-outline-info modal_close" type="button">キャンセル</button>
            </div>
        </form>
    </div>
    <div class="row profile_count">
        <div class="col-4 offset-1">
            <a href="user_top.php?page_id=<?= $current_user['id'] ?>&type=main">投稿数<p>
                    <?= current($user->get_user_count('post', $current_user['id'])) ?></p></a>
        </div>
        <div class="col-4 offset-1">
            <a href="user_top.php?page_id=<?= $current_user['id'] ?>&type=favorites">お気に入り投稿<p>
                    <?= current($user->get_user_count('favorite', $current_user['id'])) ?></p></a>
        </div>
        <div class="col-4 offset-1">
            <a href="user_top.php?page_id=<?= $current_user['id'] ?>&type=follow">フォロー数<p>
                    <?= current($user->get_user_count('follow', $current_user['id'])) ?></p></a>
        </div>
        <div class="col-4 offset-1">
            <a href="user_top.php?page_id=<?= $current_user['id'] ?>&type=follower">フォロワー数<p>
                    <?= current($user->get_user_count('follower', $current_user['id'])) ?></p></a>
        </div>
    </div>
    <?php if ($current_user['id'] == $_SESSION['user_id']) : ?>
    <button class="btn btn btn-outline-dark edit_btn" type="button" name="follow">プロフィール編集</button>
    <?php else : ?>
    <form action="#" method="post">

        <input type="hidden" class="current_user_id" value="<?= $_SESSION['user_id'] ?>">
        <input type="hidden" name="follow_user_id" value="<?= $current_user['id'] ?>">
        <?php if ($user->check_follow($_SESSION['user_id'], $current_user['id'])) : ?>
        <button class="follow_btn border_white btn following" type="button" name="follow">フォロー中</button>
        <?php else : ?>
        <button class="follow_btn border_white btn" type="button" name="follow">フォロー</button>
        <?php endif; ?>
    </form>
    <?php endif; ?>

</div>