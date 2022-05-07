<?php
require_once('../config_1.php');

$post_id = $_GET['post_id'];
$comment_id = $_GET['comment_id'];

$post = new Post($post_id);
$post = $post->get_post();
$user = new User($post['user_id']);
$post_user = $user->get_user();
$user = new User($_SESSION['user_id']);
$current_user = $user->get_user();
?>

<div class="col-8 offset-2">
    <div class="post">
        <div class="post_list">
            <div class="post_user">
                <object><a
                        href="/user/user_disp.php?user_id=<?= $current_user['id'] ?>&page_id=<?= $post_user['id'] ?>">
                        <img src="/user/image/<?= $post_user['image'] ?>">
                        <?= $post_user['name'] ?>
                    </a></object>
            </div>
            <div class="post_text">
                <?= $post['text'] ?>
            </div>

            <?php if (!empty($post['image'])) : ?>
            <img src="/post/image/<?= $post['image'] ?>" class="post_img">
            <?php endif; ?>

            <div class="post_info">
                <form class="favorite_count" action="#" method="post">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="button" name="favorite" class="btn favorite_btn">
                        <?php if (!check_favolite_duplicate($_SESSION['user_id'], $post['id'])) : ?>
                        <i class="far fa-star"></i>
                        <?php else : ?>
                        <i class="fas fa-star"></i>
                        <?php endif; ?>
                    </button>
                    <span class="post_count"><?= current($post->get_post_favorite_count()) ?></span>
                </form>
                <div class="post_comment_count">
                    <button class="btn modal_btn" data-target="#modal<?= $post['id'] ?>" type="button"><i
                            class="fas fa-comment-dots"></i></button>
                    <span class="post_comment_count"><?= current($post->get_post_favorite_count()) ?></span>
                </div>
                <div class="comment_confirmation" id="modal<?= $post['id'] ?>">
                    <p class="modal_title">この投稿にコメントしますか？</p>
                    <p class="post_content"><?= nl2br($post['text']) ?></p>
                    <form method="post" action="../comment/comment_add_done.php" enctype="multipart/form-data">
                        <p>コメント内容を入力ください。</p>
                        <input type="text" name="text">
                        <p>画像を選んでください。</p>
                        <input type="file" name="image_name">
                        <input type="hidden" name="id" value="<?= $post_id ?>">
                        <button class="btn btn-outline-danger" type="submit" name="comment"
                            value="comment">コメント</button>
                        <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                    </form>
                </div>
                <button class="btn modal_btn" data-target="#edit_modal<?= $post['id'] ?>" type="button"><i
                        class="fas fa-edit"></i></button>
                <div class="post_edit" id="edit_modal<?= $post['id'] ?>">
                    投稿内容更新
                    <form method="post" action="../post/post_edit_done.php" enctype="multipart/form-data">
                        投稿内容を編集する
                        <input type="text" name="text" value="<?php print $post['text']; ?>">
                        <?php
                        if (!empty($disp)) {
                            print $disp_image;
                        }
                        ?>
                        画像を選んでください<br />
                        <input type="file" name="image_name" style="width:400px">
                        <input type="hidden" name="id" value="<?php print $post['id']; ?>">
                        <input type="hidden" name="image_name_old" value="<?php print $post['image']; ?>">
                        <button class="btn btn-outline-danger" type="submit" name="edit" value="edit">更新</button>
                        <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                    </form>
                </div>
                <button class="btn modal_btn" data-target="#delete_modal<?= $post['id'] ?>" type="button"><i
                        class="far fa-trash-alt"></i></button>
                <div class="delete_confirmation" id="delete_modal<?= $post['id'] ?>">
                    <p class="modal_title">こちらの投稿を削除しますか？</p>
                    <p class="post_content"><?= nl2br($post['text']) ?></p>
                    <form action="post_delete_done.php" method="post">
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <input type="hidden" name="image_name" value="<?= $post['image'] ?>">
                        <button class="btn btn-outline-danger" type="submit" name="delete" value="delete">削除</button>
                        <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                    </form>
                </div>
            </div>
            <p class="post_created_at"><?php print '' . convert_to_fuzzy_time($post['created_at']) . ''; ?></p>
            <?php
            $reply_comments = $comment->get_reply_comments($post_id);
            foreach ($reply_comments as $reply_comment) :
                if ($reply_comment['comment_id'] == $comment_id) :
                    $user = new User($reply_comment['user_id']);
                    $reply_comment_user = $user->get_user();
            ?>
            <object><a
                    href="/user/user_disp.php?user_id=<?= $reply_comment_user['id'] ?>&page_id=<?= $reply_comment_user['id'] ?>">
                    <div class="user_info">
                        <img src="/user/image/<?= $reply_comment_user['image'] ?>">
                        <?= $reply_comment_user['name'] ?>
                    </div>
            </object></a>
            <span class="comment_text"><?= $reply_comment['text'] ?></span>
            <?php if (!empty($reply_comment['image'])) : ?>
            <p class="comment_image"><img src="../comment/image/<?= $reply_comment['image'] ?>"></p>
            <?php endif; ?>
            <div class="comment_info">
                <button class="btn modal_btn" data-target="#delete_modal<?= $reply_comment['id'] ?>" type="button"><i
                        class="far fa-trash-alt"></i></button>
                <div class="delete_confirmation" id="delete_modal<?= $reply_comment['id'] ?>">
                    <span class="modal_title">こちらのコメントを削除しますか？</span>
                    <span class="post_content">'.nl2br($reply_comment['text']).'</span>
                    <form action="../comment/comment_delete_done.php" method="post">
                        <input type="hidden" name="id" value="<?= $reply_comment['id'] ?>">
                        <input type="hidden" name="image_name" value="<?= $reply_comment['image'] ?>">
                        <input type="hidden" name="user_id" value="<?= $post_user['id'] ?>">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button class="btn btn-outline-danger" type="submit" name="delete" value="delete">削除</button>
                        <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                    </form>
                </div>
                <button class="btn modal_btn" data-target="#reply_modal<?= $reply_comment['id'] ?>" type="button"><i
                        class="fas fa-reply"></i></button>
                <span class="post_comment_count"><?= current(get_post_comment_count($reply_comment['id'])) ?></span>
                <div class="reply_comment_confirmation" id="reply_modal<?= $reply_comment['id'] ?>">
                    <p class="modal_title">このコメントに返信しますか？</p>
                    <p class="post_content"><?= nl2br($reply_comment['text']) ?></p>
                    <form method="post" action="../comment/comment_add_done.php" enctype="multipart/form-data">
                        <p>コメント内容を入力ください。</p>
                        <input type="text" name="text">
                        <p>画像を選んでください。</p>
                        <input type="file" name="image_name">
                        <input type="hidden" name="id" value="'.$post_id.'">
                        <input type="hidden" name="comment_id" value="'.$reply_comment['id'].'">
                        <button class="btn btn-outline-danger" type="submit" name="comment"
                            value="comment">コメント</button>
                        <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                    </form>
                </div>
            </div>
            <span class="comment_created_at"><?= convert_to_fuzzy_time($reply_comment['created_at']) ?></span>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>
<?php require_once('../footer.php'); ?>