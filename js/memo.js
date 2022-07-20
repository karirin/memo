// メモ入力処理
$(document).on('click ontouchstart', '.fa-edit', function() {
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
$(document).on('click ontouchstart', '.favorite_btn', function(e) {
    e.stopPropagation();
    var memo_id = $(this).prev().val(),
        memo_star = $("#star_" + memo_id);
    $.ajax({
        type: 'POST',
        url: '../ajax_memo_favorite_process.php',
        dataType: 'text',
        data: {
            memo_id: memo_id
        }
    }).done(function() {
        if (memo_star.attr('class') == "far fa-star") {
            memo_star.attr('class', 'fas fa-star');
        } else {
            memo_star.attr('class', 'far fa-star');
        }
    }).fail(function() {});
});

// メモグループクリック時
$(document).on('click ontouchstart', '.memo_group_list', function() {
    var memo_target = $(this).data("target"),
        memo = document.querySelector(memo_target),
        group_id = memo.id.slice(15),
        group_select = 1,
        group_max_id = $(".memo_group_create_form").prev()[0].id.slice(16);
    $.ajax({
        type: 'POST',
        url: '../ajax_edit_memo.php',
        dataType: 'text',
        data: {
            group_id: group_id,
            group_select: group_select,
            group_max_id: group_max_id
        }
    }).done(function() {
        location.reload();
    }).fail(function() {});
});

// すべてのメモクリック時
$(document).on('click ontouchstart', '.all_memo', function() {
    var all_memo = 1;
    $.ajax({
        type: 'POST',
        url: '../ajax_edit_memo.php',
        dataType: 'text',
        data: {
            all_memo: all_memo
        }
    }).done(function() {
        location.reload();
    }).fail(function() {});
});

// すべてのメモクリック時
$(document).on('click ontouchstart', '.far.fa-question-circle', function() {
    $(".modal_memo").fadeIn();
    $(".far.fa-times-circle.memo_clear").fadeIn();
    $(".memo_helpdisp").fadeIn();
    $(document).on('click ontouchstart', '.far.fa-times-circle.memo_clear', function() {
        $(".modal_memo").fadeOut();
        $(".memo_helpdisp").fadeOut();
    });
});

var memo = document.querySelector('.memo');

$(document).on('dblclick', '.memo', function(event) {
    let memo_id = $(this).data("target");
    let memo = document.querySelector(memo_id);
    let currentDroppable = null;
    let currentDroppable_memogroup = null;
    let currentDroppable_memogroup_create = null;
    let shiftX = event.clientX - memo.getBoundingClientRect().left;
    let shiftY = event.clientY - memo.getBoundingClientRect().top;

    //絶対位置で一番上になるように
    memo.style.position = 'absolute';
    memo.style.zIndex = 5;

    //対象オブジェクトをbody要素に追加
    document.body.append(memo);

    moveAt(event.pageX, event.pageY);

    function moveAt(pageX, pageY) {
        memo.style.left = pageX - shiftX + 'px';
        memo.style.top = pageY - shiftY + 'px';
    }

    function onMouseMove(event) {
        moveAt(event.pageX, event.pageY);

        memo.hidden = true;
        let elemBelow = document.elementFromPoint(event.clientX, event.clientY);
        memo.hidden = false;

        if (!elemBelow) return;

        let droppableBelow = elemBelow.closest('.memo.memo_area');

        if (currentDroppable != droppableBelow) {
            if (currentDroppable) {
                leaveDroppable(currentDroppable, memo);
            }
            currentDroppable = droppableBelow;
            if (currentDroppable) {
                memo_children = document.querySelector('#' + memo.id);
                memo_children_width = memo_children.style.width;
                memo_children.style.width = '150px';
                memo_id = memo.id.slice(9);
                memo_info_children = document.querySelector('#memo_info' + memo_id);
                memo_info_children.style.display = 'none';
                $('.memo').on('mouseup', function(event) {
                    let memo_id = currentDroppable.id.slice(9),
                        memo_text = $("#memo" + memo_id).text(),
                        memo_target = $(this).data("target"),
                        memo = document.querySelector(memo_target),
                        memo_target_id = memo_target.slice(10),
                        memo_target_text = $("#memo" + memo_target_id).text(),
                        text = memo_text + memo_target_text,
                        delete_flg = 1;
                    memo.style.display = 'none';
                    $(".memo_edit_process").fadeIn();
                    $(".modal_memo").fadeIn();
                    $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">メモを前後どちらに追加しますか？</h2><p class="modal_memo_text before_text">' + memo_target_text + '</p><p>' + memo_text + '</p><p class="modal_memo_text after_text">' + memo_target_text + '</p><div class="right"><button class="btn memo_close modal_close" type="button">キャンセル</button></div></div>');
                    $('.modal_close').on('click', function(event) {
                        $(".memo_edit_process").fadeOut();
                        $(".modal_memo").fadeOut();
                        memo.style.display = 'block';
                        $('.memo').off();
                    });
                    $('.before_text').one('click', function() {
                        text_flg = 0;
                        enterDroppable(currentDroppable, memo_target, text_flg);
                    });
                    $('.after_text').one('click', function() {
                        text_flg = 1;
                        enterDroppable(currentDroppable, memo_target, text_flg);
                    });
                });
            }
        }
        let droppableBelow_memogroup = elemBelow.closest('.memo_group_list');
        if (currentDroppable_memogroup != droppableBelow_memogroup) {
            if (currentDroppable_memogroup) { // null when we were not over a droppable before this event
                leaveDroppable_memogroup(currentDroppable_memogroup, memo);
            }


            currentDroppable_memogroup = droppableBelow_memogroup;
            if (currentDroppable_memogroup) {
                memo_children = document.querySelector('#' + memo.id);
                memo_children_width = memo_children.style.width;
                memo_children.style.width = '150px';
                memo_id = memo.id.slice(9);
                memo_info_children = document.querySelector('#memo_info' + memo_id);
                memo_info_children.style.display = 'none';
                $('.memo').on('mouseup', function() {
                    var memo_target = $(this).data("target"),
                        memo = document.querySelector(memo_target),
                        memo_group_id = memo.id.slice(9) + " ",
                        group_id = currentDroppable_memogroup.id.slice(15);
                    memo.style.display = 'none';
                    $(".memo_edit_process").fadeIn();
                    $(".modal_memo").fadeIn();
                    $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">メモグループに追加しますか？</h2><div class="right"><button class="btn ok_btn" type="button">OK</button><button class="btn memo_close modal_close" type="button">キャンセル</button></div></div>');
                    $('.modal_close').on('click', function(event) {
                        $(".memo_edit_process").fadeOut();
                        $(".modal_memo").fadeOut();
                        memo.style.display = 'block';
                        $('.memo').off();
                    });
                    $('.ok_btn').one('click', function() {
                        memo_group_list = 1;
                        enterDroppable_memogroup(currentDroppable_memogroup, memo_target, memo_group_list);
                    });
                });
            }
        }
        let droppableBelow_memogroup_create = elemBelow.closest('.memo_create');
        if (currentDroppable_memogroup_create != droppableBelow_memogroup_create) {
            if (currentDroppable_memogroup_create) { // null when we were not over a droppable before this event
                leaveDroppable_memogroup_create(currentDroppable_memogroup_create, memo);
            }

            currentDroppable_memogroup_create = droppableBelow_memogroup_create;
            if (currentDroppable_memogroup_create) {
                memo_children = document.querySelector('#' + memo.id);
                memo_children_width = memo_children.style.width;
                memo_children.style.width = '150px';
                memo_id = memo.id.slice(9);
                memo_info_children = document.querySelector('#memo_info' + memo_id);
                memo_info_children.style.display = 'none';
                $('.memo').on('mouseup', function() {
                    var memo_target = $(this).data("target"),
                        memo = document.querySelector(memo_target),
                        memo_group_id = memo.id.slice(9) + " ",
                        group_id = currentDroppable_memogroup_create.id.slice(15),
                        memo_group_text = $("#memo" + memo_group_id).text();
                    memo.style.display = 'none';
                    $(".memo_edit_process").fadeIn();
                    $(".modal_memo").fadeIn();
                    $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">メモグループを新規作成しますか？</h2><div class="right"><button class="btn ok_btn" type="button">OK</button><button class="btn memo_close modal_close" type="button">キャンセル</button></div></div>');
                    $('.modal_close').on('click', function(event) {
                        $(".memo_edit_process").fadeOut();
                        $(".modal_memo").fadeOut();
                        memo.style.display = 'block';
                        $('.memo').off();
                    });
                    $('.ok_btn').one('click', function() {
                        enterDroppable_memogroup_create(currentDroppable_memogroup_create, memo_target);
                    });
                });
            }
        }
    }

    document.addEventListener('mousemove', onMouseMove);

    memo.onmouseup = function() {
        document.removeEventListener('mousemove', onMouseMove);
        memo.onmouseup = null;
    };
});

function leaveDroppable(elem, memo) {
    $('.memo').off();
    memo_children = document.querySelector('#' + memo.id);
    memo_children.style.width = '200px';
    memo_id = memo.id.slice(9);
    memo_info_children = document.querySelector('#memo_info' + memo_id);
    memo_info_children.style.display = 'block';
}

function enterDroppable(elem, memo_target, text_flg) {
    let memo_id = elem.id.slice(9),
        memo_text = $("#memo" + memo_id).text(),
        memo = document.querySelector(memo_target),
        memo_target_id = memo_target.slice(10),
        memo_target_text = $("#memo" + memo_target_id).text(),
        delete_flg = 1;
    if (text_flg == 0) {
        text = memo_target_text + memo_text;
    } else {
        text = memo_text + memo_target_text;
    }
    $(".memo_edit_process").fadeOut();
    $(".modal_memo").fadeOut();
    $.ajax({
        type: 'POST',
        url: '../ajax_edit_memo.php',
        dataType: 'text',
        data: {
            memo_id: memo_id,
            memo_target_id: memo_target_id,
            memo_text: text,
            delete_flg: delete_flg
        }
    }).done(function() {
        if (text_flg == 0) {
            $(".memo_area #memo" + memo_id).replaceWith('<div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + memo_target_text + memo_text + '</div>');
            $(".memo_group_list #memo" + memo_id).replaceWith('<div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + memo_target_text + memo_text + '</div>');
        } else {
            $(".memo_area #memo" + memo_id).replaceWith('<div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + memo_text + memo_target_text + '</div>');
            $(".memo_group_list #memo" + memo_id).replaceWith('<div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + memo_text + memo_target_text + '</div>');
        }
    }).fail(function() {});
    $('.memo').off();
}

function leaveDroppable_memogroup(elem, memo) {
    $('.memo').off();
    memo_children = document.querySelector('#' + memo.id);
    memo_children.style.width = '200px';
    memo_id = memo.id.slice(9);
    memo_info_children = document.querySelector('#memo_info' + memo_id);
    memo_info_children.style.display = 'block';
}

function enterDroppable_memogroup(elem, memo_target, memo_group_list) {
    var memo = document.querySelector(memo_target),
        memo_group_id = memo.id.slice(9) + " ",
        memo_id = memo.id.slice(9),
        memo_text = $("#memo" + memo_id).text(),
        group_id = elem.id.slice(15),
        group_max_id = $(".memo_group_create_form").prev()[0].id.slice(16),
        delete_flg = 1;
    $(".memo_edit_process").fadeOut();
    $(".modal_memo").fadeOut();
    $.ajax({
        type: 'POST',
        url: '../ajax_edit_memo.php',
        dataType: 'text',
        data: {
            memo_target_id: memo_id,
            group_id: group_id,
            group_max_id: group_max_id,
            memo_group_id: memo_group_id,
            memo_group_list: memo_group_list,
            memo_id: '',
            memo_text: '',
            memo_group_create: '',
            group_select: '',
            all_memo: '',
            delete_group_flg: '',
            delete_flg: delete_flg
        }
    }).done(function() {
        // メモグループ更新時の処理を記載する
        // ('.memo_create_form' + group_id)のgroup_idで更新をかけるメモを指定したい
        if ($('#memo_group_list' + group_id + ' .memo_omit:last')[0] === undefined || $('#memo_group_list' + group_id + ' .memo_omit:last')[0].style.display != 'inline-block') {
            $('.memo_create_form' + group_id).replaceWith('<div class="memo"><div class="memo_list"><div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + memo_text + '</div></div></div><input type="hidden" class="memo_create_form' + group_id + '" name="memo_create">');
        }
        $('.memo').off();
    }).fail(function() {});
}

function leaveDroppable_memogroup_create(elem, memo) {
    $('.memo').off();
    memo_children = document.querySelector('#' + memo.id);
    memo_children.style.width = '200px';
    memo_id = memo.id.slice(9);
    memo_info_children = document.querySelector('#memo_info' + memo_id);
    memo_info_children.style.display = 'block';
}

function enterDroppable_memogroup_create(elem, memo_target) {
    var memo = document.querySelector(memo_target),
        group_id = $(".memo_group_create_form").prev()[0].id.slice(15),
        memo_group_id = memo.id.slice(9) + " ",
        memo_id = memo.id.slice(9),
        memo_text = $("#memo" + memo_id).text(),
        memo_group_create = 1,
        memo_group_list = document.getElementsByClassName("memo_group_create_form"),
        memo_group_maxid = $(".memo_group_maxid").val(),
        delete_flg = 1;
    if (group_id.indexOf("C") == -1) {
        group_id = "C" + 0;
    } else {
        group_id = group_id.slice(1);
        ++group_id;
        group_id = "C" + group_id;
    }
    $(".memo_edit_process").fadeOut();
    $(".modal_memo").fadeOut();
    $.ajax({
        type: 'POST',
        url: '../ajax_edit_memo.php',
        dataType: 'text',
        data: {
            memo_target_id: memo_id,
            memo_group_id: memo_group_id,
            memo_group_create: memo_group_create,
            delete_flg: delete_flg
        }
    }).done(function() {
        $('.memo_group_create_form').replaceWith('<div class="memo_group_list" id="memo_group_list' + group_id + '" data-target="#memo_group_list' + group_id + '" data-toggle="memo_group_list"><div class="memo"><div class="memo_list"><div class="memo_text ellipsis" id="memo' + memo_id + '">' + memo_text + '</div><input type="hidden" value="' + memo_id + '"></div></div><input type="hidden" class="memo_create_form' + group_id + '" name="memo_create"></div><input type="hidden" class="memo_group_create_form" name="memo_group_create">');
        $('.memo').off();
        // 新規追加時のメモグループを表示する
    }).fail(function() {});
}

/// 長押しを検知する閾値
var LONGPRESS = 500;
/// 長押し実行タイマーのID
var timerId;

/// 長押し・ロングタップを検知する
$(document).on("mousedown touchstart", '.memo_group_list', function() {
    var $target_modal = $(this).data("target");
    timerId = setTimeout(function() {
        var memo = document.querySelector($target_modal),
            group_id = memo.id.slice(15),
            group_max_id = $(".memo_group_create_form").prev()[0].id.slice(16),
            delete_group_flg = 1;
        /// 長押し時（Longpress）のコード
        $(".memo_edit_process").fadeIn();
        $(".modal_memo").fadeIn();
        $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">こちらのメモグループを削除しますか？</h2><div class="right"><button class="btn ok_btn" type="button">OK</button><button class="btn memo_close modal_close" type="button">キャンセル</button></div></div>');
        $('.modal_close').on('click', function(event) {
            $(".memo_edit_process").fadeOut();
            $(".modal_memo").fadeOut();
        });
        $('.ok_btn').one('click', function() {
            $(".memo_edit_process").fadeOut();
            $(".modal_memo").fadeOut();
            $.ajax({
                type: 'POST',
                url: '../ajax_edit_memo.php',
                dataType: 'text',
                data: {
                    group_id: group_id,
                    group_max_id: group_max_id,
                    delete_group_flg: delete_group_flg
                }
            }).done(function() {
                $($target_modal)[0].style.display = 'none';
            }).fail(function() {});
        });
    }, LONGPRESS);
}).on("mouseup mouseleave touchend", function() {
    clearTimeout(timerId);
});

if (memo != null) {
    memo.ondragstart = function() {
        return false;
    };
}

// チュートリアル内の矢印処理
$(document).on('click', '.fas.fa-angle-left', function() {
    if ($('.memoadd_helpdisp').css('display') == 'block') {
        $('.memoadd_helpdisp').css('display', 'none');
        $('.memodrag_helpdisp').css('display', 'block');
    } else if ($('.memogroup_helpdisp').css('display') == 'block') {
        $('.memogroup_helpdisp').css('display', 'none');
        $('.memoadd_helpdisp').css('display', 'block');
    }
});

$(document).on('click', '.fas.fa-angle-right', function() {
    if ($('.memodrag_helpdisp').css('display') == 'block') {
        $('.memodrag_helpdisp').css('display', 'none');
        $('.memoadd_helpdisp').css('display', 'block');
    } else if ($('.memoadd_helpdisp').css('display') == 'block') {
        $('.memoadd_helpdisp').css('display', 'none');
        $('.memogroup_helpdisp').css('display', 'block');
    }
});