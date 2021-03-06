<?php
$block = array();
$block = pagination_block($memos);

// すべてのメモ２ページ目以降を開いていても、正常にメモグループを選択できるよう調整
if ($_SESSION['group_select'] == 1) {
    $i = $_SESSION[$o];
} else {
    $i = $_SESSION[$n];
}

if (isset($block[0])) :
    foreach ($block[$i] as $memo) :
        $memo_user = $user->get_user($memo['user_id']);
?>
<div class="memo memo_area" id="memo_list<?= $memo['id'] ?>" data-target="#memo_list<?= $memo['id'] ?>"
    data-toggle="memo_list">
    <div class="memo_list memo_list<?= $memo['id'] ?>">
        <div class="memo_text" id="memo<?= $memo['id'] ?>"><?= $memo['text'] ?></div>
        <input type="hidden" value="<?= $memo['id'] ?>">
        <div id="memo_info<?= $memo['id'] ?>">
            <?php require('memo_info.php'); ?>
            <p class="memo_created_at"><?php print '' . convert_to_fuzzy_time($memo['created_at']) . ''; ?></p>
        </div>
    </div>
</div>
<?php endforeach ?>
<?php endif ?>
<?php require('../pagination.php'); ?>