// メモ入力処理
$(document).on('click', '.fa-edit', function() {
    var $target_modal = $(this).data("target"),
        $target_id = $target_modal.slice(1),
        memo_text = $($target_modal).text();
    $("#" + $target_id).replaceWith('<textarea type="text" name="memo_text" id="edit_' + $target_id + '" >' + memo_text);
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
            $("#edit_" + $target_id).replaceWith('<div class="memo_text ellipsis" id="' + $target_id + '" data-target="' + $target_modal + '" data-toggle="memo">' + edit_memo_text + '</div>');
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

var ball = document.querySelector('.memo');


$(document).on('dblclick', '.memo', function(event) {
    let ball_id = $(this).data("target");
    let ball = document.querySelector(ball_id);
    let currentDroppable = null;
    let shiftX = event.clientX - ball.getBoundingClientRect().left;
    let shiftY = event.clientY - ball.getBoundingClientRect().top;

    //絶対位置で一番上になるように
    ball.style.position = 'absolute';
    ball.style.zIndex = 1000;

    //対象オブジェクトをbody要素に追加
    document.body.append(ball);

    moveAt(event.pageX, event.pageY);

    function moveAt(pageX, pageY) {
        ball.style.left = pageX - shiftX + 'px';
        ball.style.top = pageY - shiftY + 'px';
    }

    function onMouseMove(event) {
        moveAt(event.pageX, event.pageY);

        ball.hidden = true;
        let elemBelow = document.elementFromPoint(event.clientX, event.clientY);
        ball.hidden = false;

        if (!elemBelow) return;

        let droppableBelow = elemBelow.closest('.memo');
        if (currentDroppable != droppableBelow) {
            if (currentDroppable) { // null when we were not over a droppable before this event
                leaveDroppable(currentDroppable);
            }
            currentDroppable = droppableBelow;
            if (currentDroppable) { // null if we're not coming over a droppable now
                $('.memo').on('mouseup', function(event) {
                    let memo_id = currentDroppable.id.slice(9),
                        memo_text = $("#memo" + memo_id).text(),
                        ball_target = $(this).data("target"),
                        ball = document.querySelector(ball_target),
                        ball_id = ball_target.slice(10),
                        ball_text = $("#memo" + ball_id).text(),
                        text = memo_text + ball_text,
                        delete_flg = 1;
                    ball.style.display = 'none';
                    $(".memo_edit_process").fadeIn();
                    $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">メモ</h2><p class="modal_memo_text before_text">' + ball_text + '</p><p>' + memo_text + '</p><p class="modal_memo_text after_text">' + ball_text + '</p><button class="btn btn-outline-primary modal_close" type="button">キャンセル</button></div>');
                    $('.modal_close').on('click', function(event) {
                        $(".memo_edit_process").fadeOut();
                        ball.style.display = 'block';
                        $('.memo').off();
                    });
                    $('.before_text').one('click', function() {
                        text_flg = 0;
                        enterDroppable(currentDroppable, ball_target, text_flg);
                    });
                    $('.after_text').one('click', function() {
                        text_flg = 1;
                        enterDroppable(currentDroppable, ball_target, text_flg);
                    });
                });
            }
        }
    }

    document.addEventListener('mousemove', onMouseMove);

    ball.onmouseup = function() {
        document.removeEventListener('mousemove', onMouseMove);
        ball.onmouseup = null;
    };
});

function enterDroppable(elem, ball_target, text_flg) {
    let memo_id = elem.id.slice(9),
        memo_text = $("#memo" + memo_id).text(),
        ball = document.querySelector(ball_target),
        ball_id = ball_target.slice(10),
        ball_text = $("#memo" + ball_id).text(),
        delete_flg = 1;
    if (text_flg == 0) {
        text = ball_text + memo_text;
    } else {
        text = memo_text + ball_text;
    }
    $(".memo_edit_process").fadeOut();
    $.ajax({
        type: 'POST',
        url: '../ajax_edit_memo.php',
        dataType: 'text',
        data: {
            memo_id: memo_id,
            ball_id: ball_id,
            memo_text: text,
            delete_flg: delete_flg
        }
    }).done(function() {
        if (text_flg == 0) {
            $("#memo" + memo_id).replaceWith('<div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + ball_text + memo_text + '</div>');
        } else {
            $("#memo" + memo_id).replaceWith('<div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + memo_text + ball_text + '</div>');
        }
    }).fail(function() {});
    $('.memo').off();
}

function leaveDroppable(elem) {
    $('.memo').off();
}

ball.ondragstart = function() {
    return false;
};