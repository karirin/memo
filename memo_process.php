<div class="modal_memo"></div>
<div class="memo_process">
    <h2 class="memo_title">メモ</h2>
    <form method="post" action="../memo/memo_add_done.php" enctype="multipart/form-data">
        <textarea id="memo_process_counter" class="textarea form-control" placeholder="メモ内容を入力ください"
            name="text"></textarea>
        <div class="counter">
            <span class="memo_process_count">0</span><span>/300</span>
        </div>
        <div class="memo_btn">
            <button class="btn btn-outline-light" type="submit" name="memo" value="memo" id="memo">メモ</button>
            <button class="btn btn-outline-light modal_close" type="button">キャンセル</button>
        </div>
    </form>
</div>
<div class="memo_edit_process">
    <div class="modal_edit_process">
    </div>
</div>
<div class="memo_helpdisp" style="display:none;">
    <i class="far fa-times-circle memo_clear"></i>
    <div class="memodrag_helpdisp">
        <p class="memodrag_title">メモのドラッグ</p>
    </div>
    <div class="memoadd_helpdisp">
        <p class="memoadd_title">メモの追加</p>
    </div>
    <div class="memogroup_helpdisp">
        <p class="memogroup_title">メモグループ</p>
    </div>
    <i class="fas fa-angle-left"></i>
    <i class="fas fa-angle-right"></i>
</div>
</div>