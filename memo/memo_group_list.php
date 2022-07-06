<?php
// memo_groupからmemo_idを取得
// 取得したメモ情報を$memosに渡す
$memos_id = array();
$memos_id = get_memos_id($memo_group['id']);
$memos_id = explode(" ", $memos_id[0]["memo_id"]);
$memos = array();
$i = 0;

for ($j = 0; $j < count($memos_id); $j++) {
    $memo_class = new Memo($memos_id[$j]);
    $memo_class = $memo_class->get_memo();
    array_push($memos, $memo_class);
}

foreach ($memos as $memo) :
    if ($memo != '') :
        $memo_user = $user->get_user();
?>
<div class="memo">

    <div class="memo_list">
        <div class="memo_text ellipsis" id="memo<?= $memo['id'] ?>"><?php if (!empty($memo['text'])) {
                                                                                print '' . $memo["text"] . '';
                                                                            } ?></div>
        <input type="hidden" value="<?= $memo['id'] ?>">
    </div>
</div>
<?php
        $i++;
    endif;
    if ($i == 3) {
        print '<div class="memo_omit" style="display:inline-block;">・・・</div>';
        break;
    } else {
        print '<div class="memo_omit" style="display:none;">・・・</div>';
    }
endforeach
?>