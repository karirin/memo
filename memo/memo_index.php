<?php
if (!empty($_POST['search_memo'])) {
    $hoge = $_POST['search_input'];
    header("Location:memo_index.php?type=search&query=${hoge}");
}
require_once('../config_1.php');
$user = new User($_SESSION['user_id']);
?>
<div class="col-6 offset-3">
    <h2 class="center margin_top">投稿一覧</h2>
    <form method="post" action="#" class="search_container">
        <div class="input-group mb-2">
            <input type="text" name="search_input" class="form-control" placeholder="投稿検索">
            <div class="input-group-append">
                <input type="submit" name="search_memo" class="btn btn-outline-secondary">
                <?php
                $current_user = $user->get_user();
                // $page_type = $_GET['type'];
                $page_type = 'all'; //テスト用
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
    </form>
    <?php
    require_once('memo_list.php');
    print '</div>';
    require_once('../footer.php');
    ?>