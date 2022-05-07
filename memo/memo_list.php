<?php
$block = array();
$block = pagination_block($memos);

if (isset($block[0])) :
    foreach ($block[$_SESSION[$i]] as $memo) :

        $memo_user = $user->get_user($memo['user_id']);
?>
<div class="memo">
    <a href="/memo/memo_disp.php?memo_id=<?= $memo['id'] ?>&user_id=<?= $current_user['id'] ?>" class="memo_link">

        <div class="memo_list">
            <div class="memo_user">
                <object><a
                        href="/user/user_disp.php?user_id=<?= $current_user['id'] ?>&page_id=<?= $memo_user['id'] ?>&type=main">
                        <img src="/user/image/<?= $memo_user['image'] ?>">
                        <?php print '' . $memo_user['name'] . ''; ?>
                    </a></object>
            </div>
            <div class="memo_text ellipsis" id="memo_text"><?php print '' . $memo['text'] . ''; ?></div>
            <?php
                    if (!empty($memo['image'])) :
                        print '<img src="/memo/image/' . $memo['image'] . '" class="memo_img" >';
                    endif;
                    ?>
    </a>
    <?php require('memo_info.php'); ?>
    <p class="memo_created_at"><?php print '' . convert_to_fuzzy_time($memo['created_at']) . ''; ?></p>
</div>
</div>

<?php endforeach ?>
<?php endif ?>
<?php require('../pagination.php'); ?>