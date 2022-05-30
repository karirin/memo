<?php
require_once('../config_1.php');
$user = new User($_SESSION['user_id']);
?>
<div class="row">
    <div class="col-4">
        <?php
        $current_user = $user->get_user();
        $memo = new Memo(0);
        $memos = $memo->get_memos($_SESSION['user_id'], 'mymemo', 0);
        ?>
        <!-- foreach ($block[$_SESSION[$i]] as $memo) : メモグループ仕様にする-->
        <?php //require('memo_group_list.php'); 
        ?>

        <div class="memo_create">
            <input type="hidden" value="memo_create" class="memo_create_form" name="memo_create">
        </div>

    </div>
    <div class="col-8">


        <?php
        require('memo_list.php');
        print '</div>';
        require_once('../footer.php');
        ?>