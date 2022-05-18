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

var ball = document.querySelector('.memo');


$(document).on('mousedown', '.memo', function(event) {
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
                enterDroppable(currentDroppable);

            }
        }
    }

    document.addEventListener('mousemove', onMouseMove);

    ball.onmouseup = function() {
        document.removeEventListener('mousemove', onMouseMove);
        ball.onmouseup = null;
    };
});

function enterDroppable(elem) {
    let memo_id = elem.id.slice(9),
        memo_text = $("#memo" + memo_id).val();
    $('.memo').on('mouseup', function(event) {
        let ball_target = $(this).data("target"),
            ball = document.querySelector(ball_target),
            ball_id = ball_target.slice(10),
            ball_text = $("#memo" + ball_id).val();
        $("#memo" + memo_id).replaceWith('<input class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" value="' + memo_text + ball_text + '">');
        ball.style.display = 'none';
        $('.memo').off();
    });

}

function leaveDroppable(elem) {
    $('.memo').off();
}

ball.ondragstart = function() {
    return false;
};