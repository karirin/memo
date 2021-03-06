<?php
require_once('../config_1.php');
$user = new User($_SESSION['user_id']);
$current_user = $user->get_user();
$memo_groups = get_memo_groups();
$memo_group_maxid = get_memo_group_maxid();
$i = 0;
?>
<div class="row">
    <div class="col-3">
        <div class="all_memo">
            すべてのメモ
        </div>
        <?php foreach ($memo_groups as $memo_group) : ?>

        <div class="memo_group_list" id="memo_group_list<?= $memo_group['id'] ?>"
            data-target="#memo_group_list<?= $memo_group['id'] ?>" data-toggle="memo_group_list">
            <?php if ($memo_group['title'] == '') : ?>
            <div><?= $memo_group['title'] ?></div>
            <?php endif; ?>
            <?php require('memo_group_list.php'); ?>
            <input type="hidden" class="memo_create_form<?= $memo_group['id']; ?>" name="memo_create">
        </div>
        <?php endforeach; ?>
        <input type="hidden" class="memo_group_create_form" name="memo_group_create">
        <div class="memo_create">
            <i class="fas fa-plus"></i>
            メモグループを追加する
        </div>
    </div>
    <div class="col-9">
        <?php
        $memo = new Memo(0);
        $memos = $memo->get_memos($_SESSION['user_id'], 'mymemo', 0);
        ?>
        <?php
        require('memo_list.php');
        print '</div>';
        require_once('../footer.php');
        ?>