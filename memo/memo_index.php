<?php
require_once('../config_1.php');
$user = new User($_SESSION['user_id']);
$current_user = $user->get_user();
$memo_groups = get_memo_groups();
?>
<div class="row">
    <div class="col-4">
        <!-- foreach ($block[$_SESSION[$i]] as $memo) : メモグループ仕様にする-->
        <div class="memo_group">
            <?php foreach ($memo_groups as $memo_group) : ?>
            <div class="memo_group_list" id="memo_group_list<?= $memo_group['id'] ?>"
                data-target="#memo_group_list<?= $memo_group['id'] ?>" data-toggle="memo_group_list">
                <?php require('memo_group_list.php'); ?>
                <input type="hidden" class="memo_create_form" name="memo_create">
            </div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" class="memo_group_create_form" name="memo_group_create">
        <div class="memo_create">
        </div>
    </div>
    <div class="col-8">
        <?php
        $memo = new Memo(0);
        $memos = $memo->get_memos($_SESSION['user_id'], 'mymemo', 0);
        ?>

        <?php
        require('memo_list.php');
        print '</div>';
        require_once('../footer.php');
        ?>