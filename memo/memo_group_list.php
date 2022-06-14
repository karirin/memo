<?php
// memo_groupからmemo_idを取得
// 取得したメモ情報を$memosに渡す
$memos_id = array();
$memos_id = get_memos_id($memo_group['id']);
$memos_id = explode(" ", $memos_id[0]["memo_id"]);
$memos = array();

for ($i = 0; $i < count($memos_id); $i++) {
    $memo_class = new Memo($memos_id[$i]);
    $memo_class = $memo_class->get_memo();
    array_push($memos, $memo_class);
}

$block = pagination_block($memos);
if (isset($block[0])) :
    foreach ($block[$_SESSION[$n]] as $memo) :
        if ($memo != '') :
            $memo_user = $user->get_user();
?>
<div class="memo">

    <div class="memo_list">
        <div class="memo_text ellipsis" id="memo<?= $memo['id'] ?>"><?php if (!empty($memo['text'])) {
                                                                                    print '' . $memo["text"] . '';
                                                                                } ?></div>
        <input type="hidden" value="<?= $memo['id'] ?>">
        <?php
                    if (!empty($memo['image'])) :
                        print '<img src="/memo/image/' . $memo['image'] . '" class="memo_img" >';
                    endif;
                    ?>
    </div>
</div>
<?php endif ?>
<?php endforeach ?>
<?php endif ?>

<?php require('../pagination.php'); ?>