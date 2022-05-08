<?php
require_once('../config_1.php');
?>

<body>
    <?php
    $memo_id = $_GET['memo_id'];
    $memo_class = new Memo($memo_id);
    $memo = $memo_class->get_memo($memo_id);
    $user_class = new User($memo['user_id']);
    $memo_user = $user_class->get_user();
    $user_class = new User($_SESSION['user_id']);
    $current_user = $user_class->get_user();
    ?>
    <div class="col-8 offset-2">
        <div class="memo">
            <div class="memo_list">
                <div class="memo_user">
                    <object>
                        <a
                            href="/user/user_disp.php?user_id=<?= $current_user['id'] ?>&page_id=<?= $memo_user['id'] ?>&type=main">
                            <img src="data:image/jpeg;base64,<?= $memo_user['image'] ?>">
                            <?php print '' . $memo_user['name'] . ''; ?>
                        </a>
                    </object>
                </div>
                <div class="memo_text" id="memo_text"><?php print '' . $memo['text'] . ''; ?></div>
                <?php
                if (!empty($memo['image'])) {
                    print '<img src="data:image/jpeg;base64,"' . $memo['image'] . '" class="memo_img" >';
                }
                ?>
                <?php require('memo_info.php'); ?>
                　<p class="memo_created_at"><?php print '' . convert_to_fuzzy_time($memo['created_at']) . ''; ?></p>
                <?php
                $comment_class = new Comment($memo['id']);
                $comments = $comment_class->get_comments($memo['id']);
                foreach ($comments as $comment) :
                    $reply_comments = $comment_class->get_reply_comments($memo['id']);
                    if (empty($comment['comment_id'])) :
                ?>
                <div class="comment">
                    <?php
                            $user_class = new User($comment['user_id']);
                            $comment_user = $user_class->get_user();
                            ?>

                    <object><a
                            href="/user/user_disp.php?user_id=<?= $current_user['id'] ?>&page_id=<?= $comment_user['id'] ?>&type=all">
                            <div class="user_info">
                                <img src="data:image/jpeg;base64,<?= $comment_user['image'] ?>">
                                <?php print '' . $comment_user['name'] . ''; ?>
                            </div>
                        </a></object>
                    <span class="comment_text"><?= $comment['text'] ?></span>
                    <?php
                            if (!empty($comment['image'])) {
                                print '<p class="comment_image"><img src="data:image/jpeg;base64,' . $comment['image'] . '"></p>';
                            }
                            ?>

                    <div class="comment_info">
                        <?php if ($memo['user_id'] == $current_user['id']) : ?>
                        <button class="btn modal_btn" data-target="#delete_modal<?= $comment['id'] ?>" type="button"
                            data-toggle="delete" title="削除"><i class="far fa-trash-alt"></i></button>
                        <div class="delete_confirmation" id="delete_modal<?= $comment['id'] ?>">
                            <span class="modal_title">こちらのコメントを削除しますか？</span>
                            <span class="memo_content"><?= nl2br($comment['text']) ?></span>
                            <form action="../comment/comment_delete_done.php" method="post">
                                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                <input type="hidden" name="image_name" value="<?= $comment['image'] ?>">
                                <input type="hidden" name="user_id" value="<?= $memo_user['id'] ?>">
                                <input type="hidden" name="memo_id" value="<?= $memo['id'] ?>">
                                <button class="btn btn-outline-danger" type="submit" name="delete"
                                    value="delete">削除</button>
                                <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                            </form>
                        </div>
                        <?php endif; ?>
                        <div class="reply_comment_count">
                            <button class="btn modal_btn" data-target="#reply_modal<?= $comment['id'] ?>" type="button"
                                data-toggle="reply" title="返信"><i class="fas fa-reply"></i></button>
                            <span
                                class="memo_comment_count"><?= current($comment_class->get_reply_comment_count()) ?></span>
                        </div>
                        <div class="reply_comment_confirmation" id="reply_modal<?= $comment['id'] ?>">
                            <p class="modal_title">このコメントに返信しますか？</p>
                            <p class="memo_content"><?= nl2br($comment['text']) ?></p>
                            <form method="post" action="../comment/comment_add_done.php" enctype="multipart/form-data">
                                <textarea id="comment_counter" class="textarea form-control" placeholder="コメント内容を入力ください"
                                    name="text"></textarea>
                                <div class="counter">
                                    <span class="comment_count">0</span><span>/300</span>
                                </div>
                                <div class="comment_img">
                                    <label>
                                        <i class="far fa-image"></i>
                                        <input type="file" name="image_name" id="reply_comment_image" accept="image/*"
                                            multiple>
                                    </label>
                                    <p><img class="reply_comment_preview"></p>
                                    <i class="far fa-times-circle reply_comment_clear"></i>
                                </div>
                                <input type="hidden" name="id" value="<?= $memo['id'] ?>">
                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                <div class="memo_btn">
                                    <button class="btn btn-outline-danger" type="submit" name="comment"
                                        value="comment">コメント</button>
                                    <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                            print '<span class="comment_created_at margin_bottom">' . convert_to_fuzzy_time($comment['created_at']) . '</span>';
                            foreach ($reply_comments as $reply_comment) :
                                if ($reply_comment['comment_id'] == $comment['id']) :
                                    $user_class = new User($reply_comment['user_id']);
                                    $reply_comment_user = $user_class->get_user();
                            ?>

                    <div class="reply">
                        <div class="reply_comment">
                            <object><a
                                    href="/user/user_disp.php?user_id=<?= $reply_comment_user['id'] ?>&page_id=<?= $reply_comment_user['id'] ?>&type=all">
                                    <div class="user_info">
                                        <img src="data:image/jpeg;base64,<?= $reply_comment_user['image'] ?>">
                                        <?php print '' . $reply_comment_user['name'] . ''; ?>
                                    </div>
                            </object></a>
                            <?php
                                            print '<span class="comment_text">' . $reply_comment['text'] . '</span>';
                                            if (!empty($reply_comment['image'])) {
                                                print '<p class="comment_image"><img src="data:image/jpeg;base64,' . $reply_comment['image'] . '"></p>';
                                            }
                                            if ($memo['user_id'] == $current_user['id']) :
                                            ?>
                            <div class="comment_info">
                                <button class="btn modal_btn" data-target="#delete_modal<?= $reply_comment['id'] ?>"
                                    type="button"><i class="far fa-trash-alt"></i></button>
                                <div class="delete_confirmation" id="delete_modal<?= $reply_comment['id'] ?>">
                                    <span class="modal_title">こちらのコメントを削除しますか？</span>
                                    <span class="memo_content"><?= nl2br($reply_comment['text']) ?></span>
                                    <form action="../comment/comment_delete_done.php" method="post">
                                        <input type="hidden" name="id" value="<?= $reply_comment['id'] ?>">
                                        <input type="hidden" name="image_name" value="<?= $reply_comment['image'] ?>">
                                        <input type="hidden" name="user_id" value="<?= $memo_user['id'] ?>">
                                        <input type="hidden" name="memo_id" value="<?= $memo['id'] ?>">
                                        <button class="btn btn-outline-danger" type="submit" name="delete"
                                            value="delete">削除</button>
                                        <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
                                    </form>
                                </div>
                            </div>

                            <?php endif; ?>
                            <span
                                class="comment_created_at"><?= convert_to_fuzzy_time($reply_comment['created_at']) ?></span>

                        </div>

                    </div>

                    <?php endif; ?>

                    <?php endforeach; ?>
                </div>
                <?php endif ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    </div>



    <?php require_once('../footer.php'); ?>