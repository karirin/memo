<div class="memo_info">
    <form class="favorite_count" action="#" method="post">
        <input type="hidden" name="memo_id" value="<?= $memo['id'] ?>">
        <button type="button" name="favorite" class="btn favorite_btn" data-toggle="favorite" title="いいね">
            <?php if (!check_favolite_duplicate($_SESSION['user_id'], $memo['id'])) : ?>
            <i id="star_<?= $memo['id'] ?>" class="far fa-star"></i>
            <?php else : ?>
            <i id="star_<?= $memo['id'] ?>" class="fas fa-star"></i>
            <?php endif; ?>
        </button>
        <?php
        $memo_class = new Memo($memo['id']);
        $memo = $memo_class->get_memo();
        ?>
    </form>
    <i class="fas fa-edit" data-target="#memo<?= $memo['id'] ?>" data-toggle="memo"></i>
    <button class="btn modal_btn" data-target="#delete_modal<?= $memo['id'] ?>" type="button" data-toggle="delete"
        title="削除"><i class="far fa-trash-alt"></i></button>
    <div class="delete_confirmation" id="delete_modal<?= $memo['id'] ?>">
        <p class="modal_title">こちらのメモを削除しますか？</p>
        <p class="memo_content"><?= nl2br($memo['text']) ?></p>
        <form action="../memo/memo_delete_done.php" method="post">
            <input type="hidden" name="id" value="<?= $memo['id'] ?>">
            <button class="btn btn-outline-danger" type="submit" name="delete" value="delete">削除</button>
            <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
        </form>
    </div>
</div>