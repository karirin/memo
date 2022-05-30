<?php
$block = array();
$block = pagination_block($memos);

if (isset($block[0])) :
    foreach ($block[$_SESSION[$i]] as $memo) :

        $memo_user = $user->get_user($memo['user_id']);
?>
<div class="memo">

    <div class="memo_list">
        <div class="memo_text ellipsis" id="memo<?= $memo['id'] ?>"><?= $memo['text'] ?></div>
        <input type="hidden" value="<?= $memo['id'] ?>">
        <?php
                if (!empty($memo['image'])) :
                    print '<img src="/memo/image/' . $memo['image'] . '" class="memo_img" >';
                endif;
                ?>
        <?php require('memo_info.php'); ?>
        <p class="memo_created_at"><?php print '' . convert_to_fuzzy_time($memo['created_at']) . ''; ?></p>

    </div>
</div>

<?php endforeach ?>
<?php endif ?>
<?php require('../pagination.php'); ?>