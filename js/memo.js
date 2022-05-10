// メモ入力処理
$(document).on('click', '.memo_text', function() {
    var $target_modal = $(this).data("target"),
        $target_id = $target_modal.slice(1),
        memo_text = $($target_modal).val();
    $($target_modal).replaceWith('<textarea type="text" name="memo_text" id="edit_' + $target_id + '" >' + memo_text);

    $("#edit_" + $target_id).on('mouseout', function(e) {
        e.stopPropagation();
        var memo_id = $(this).next().val(),
            edit_memo_text = $("#edit_" + $target_id).val();
        $.ajax({
            type: 'POST',
            url: '../ajax_edit_memo.php',
            dataType: 'text',
            data: {
                memo_id: memo_id,
                memo_text: edit_memo_text
            }
        }).done(function() {
            $("#edit_" + $target_id).replaceWith('<input class="memo_text ellipsis" id="' + $target_id + '" data-target="' + $target_modal + '" data-toggle="memo" value="' + edit_memo_text + '">');
        }).fail(function() {});
    });
});

// いいね機能処理
$(document).on('click', '.favorite_btn', function(e) {
    e.stopPropagation();
    var memo_id = $(this).prev().val(),
        memo_star = $("#star_" + memo_id),
        memo_count = $("#memo_count_" + memo_id);
    $.ajax({
        type: 'POST',
        url: '../ajax_memo_favorite_process.php',
        dataType: 'text',
        data: {
            memo_id: memo_id
        }
    }).done(function(data) {
        if (memo_star.attr('class') == "far fa-star") {
            memo_star.attr('class', 'fas fa-star');
            memo_count[0].textContent = 1;
        } else {
            memo_star.attr('class', 'far fa-star');
            memo_count[0].textContent = 0;
        }
    }).fail(function(XMLHttpRequest, status, e) {

    });
});