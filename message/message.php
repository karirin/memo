<?php
require_once('../config_1.php');

$user = new User($_SESSION['user_id']);
$current_user = $user->get_user();
$user = new User($_GET['user_id']);
$destination_user = $user->get_user();
$message = new Message();
$messages = $message->get_messages($current_user['id'], $destination_user['id']);
$bottom_message = $message->get_new_message($current_user['id'], $destination_user['id']);
$message->reset_message_count($current_user['id'], $destination_user['id']);
?>

<body>
    <div class="row">
        <div class="col-8 offset-2">
            <div class="message">
                <h2 class="center"><?= $destination_user['name'] ?></h2>
                <?php
                foreach ($messages as $message) :
                ?>
                <div class="my_message">
                    <?php if ($message['user_id'] == $current_user['id']) : ?>
                    <div class="mycomment right">
                        <span class="message_created_at">
                            <?= convert_to_fuzzy_time($message['created_at']) ?>
                        </span>
                        <p><?= $message['text'] ?>
                            <?php if (!empty($message['image'])) : ?>
                            <img src="data:image/jpeg;base64,<?= $message['image'] ?>">
                            <?php endif; ?>
                        </p><img src="data:image/jpeg;base64,<?= $current_user['image']; ?>" class="message_user_img">
                    </div>
                    <?php else : ?>

                    <div class="left"><img src="data:image/jpeg;base64,<?= $destination_user['image'] ?>"
                            class="message_user_img">
                        <div class="says"><?= $message['text'] ?>
                            <?php if (!empty($message['image'])) : ?>
                            <img src="data:image/jpeg;base64,<?= $message['image'] ?>">
                            <?php endif; ?>
                        </div><span
                            class="message_created_at"><?= convert_to_fuzzy_time($message['created_at']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach ?>

                    <div class="message_process">
                        <form method="post" action="../message/message_add_done.php" enctype="multipart/form-data">
                            <div class="message_text">
                                <textarea id="message_counter" class="textarea form-control" placeholder="メッセージを入力ください"
                                    name="text"></textarea>
                                <div class="counter">
                                    <span class="message_count">0</span><span>/300</span>
                                </div>
                                <input type="hidden" name="destination_user_id" value="<?= $destination_user['id'] ?>">
                            </div>
                            <div class="message_btn">
                                <div class="message_image">
                                    <label>
                                        <i class="far fa-image"></i>
                                        <input type="file" name="image" id="my_image" accept="image/*" multiple>
                                    </label>
                                </div>
                                <button class="btn btn-outline-primary" type="submit" name="post" value="post"
                                    id="post">送信</button>
                            </div>
                            <div class="message_image_detail">
                                <div><img class="my_preview"></div>
                                <i class="far fa-times-circle my_clear"></i>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
require('../footer.php'); ?>
<script>
$(window).on('load', function() {
    $('html, body').scrollTop($(document).height());
});
</script>