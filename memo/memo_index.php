<?php
if (!empty($_POST['search_memo'])) {
    $hoge = $_POST['search_input'];
    header("Location:memo_index.php?type=search&query=${hoge}");
}
require_once('../config_1.php');
$user = new User($_SESSION['user_id']);
?>
<div class="col-6 offset-3">
    <div class="input-group mb-2">
        <div class="input-group-append">
            <?php
            $current_user = $user->get_user();
            $page_type = $_GET['type'];
            $memo = new Memo(0);

            switch ($page_type) {
                case 'all':
                    $memos = $memo->get_memos('', 'all', 0);
                    break;

                case 'search':
                    $memos = $memo->get_memos('', 'search', $_GET['query']);
                    break;
            }
            ?>
        </div>
    </div>
    <?php
    require_once('memo_list.php');
    print '</div>';
    require_once('../footer.php');
    ?>