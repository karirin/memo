<?php
if (!empty($_POST['search_memo'])) {
    $hoge = $_POST['search_input'];
    header("Location:memo_index.php?type=search&query=${hoge}");
}
require_once('../config_1.php');
$user = new User($_SESSION['user_id']);
?>
<div class="row">
    <div class="col-4">
        <form method="post" action="../memo/memo_add_done.php" enctype="multipart/form-data">
            <textarea id="memo_process_counter" class="textarea form-control" placeholder="メモ内容を入力ください"
                name="text"></textarea>
            <div class="counter">
                <span class="memo_process_count">0</span><span>/300</span>
            </div>
            <div class="memo_image">
                <label>
                    <i class="far fa-image"></i>
                    <input type="file" name="image_name" id="memo_image" accept="image/*" multiple>
                </label>
                <p><img class="memo_preview"></p>
                <i class="far fa-times-circle memo_clear"></i>
            </div>
            <div class="memo_btn">
                <button class="btn btn-outline-danger" type="submit" name="memo" value="memo" id="memo">メモ</button>
            </div>
        </form>
    </div>
    <div class="col-8">
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

                    case 'mymemo':
                        $memos = $memo->get_memos($_SESSION['user_id'], 'mymemo', 0);
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