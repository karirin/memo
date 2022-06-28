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
        memo_star = $("#star_" + memo_id);
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
        } else {
            memo_star.attr('class', 'far fa-star');
        }
    }).fail(function(XMLHttpRequest, status, e) {

    });
});

// メモグループクリック時
$(document).on('click', '.memo_group_list', function() {
    var ball_target = $(this).data("target"),
        ball = document.querySelector(ball_target),
        group_id = ball.id.slice(15),
        group_select = 1,
        group_max_id = $(".memo_group_create_form").prev()[0].id.slice(16);
    // $("#" + $target_id).replaceWith('<textarea type="text" name="memo_text" id="edit_' + $target_id + '" >' + memo_text);
    // $("#edit_" + $target_id).on('mouseout', function(e) {
    //     e.stopPropagation();
    //     var memo_id = $(this).next().val(),
    //         edit_memo_text = $("#edit_" + $target_id).val();
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
        // /$("#edit_" + $target_id).replaceWith('<div class="memo_text ellipsis" id="' + $target_id + '" data-target="' + $target_modal + '" data-toggle="memo">' + edit_memo_text + '</div>');
    }).fail(function() {});
    // });
});

// すべてのメモクリック時
$(document).on('click', '.all_memo', function() {
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
$(document).on('click', '.far.fa-question-circle', function() {
    $(".modal_memo").fadeIn();
    $(".far.fa-times-circle.memo_clear").fadeIn();
    $(".memo_helpdisp").fadeIn();
    $(document).on('click', '.far.fa-times-circle.memo_clear', function() {
        $(".modal_memo").fadeOut();
        $(".memo_helpdisp").fadeOut();
    });
});

var ball = document.querySelector('.memo');
var ball_list = document.querySelector('.memo_list');

$(document).on('dblclick', '.memo', function(event) {
    let ball_id = $(this).data("target");
    let ball = document.querySelector(ball_id);
    let currentDroppable = null;
    let currentDroppable_memogroup = null;
    let currentDroppable_memogroup_create = null;
    let shiftX = event.clientX - ball.getBoundingClientRect().left;
    let shiftY = event.clientY - ball.getBoundingClientRect().top;

    //絶対位置で一番上になるように
    ball.style.position = 'absolute';
    ball.style.zIndex = 5;

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
                leaveDroppable(currentDroppable, ball);
            }
            currentDroppable = droppableBelow;
            if (currentDroppable) { // null if we're not coming over a droppable now
                ball_children = document.querySelector('#' + ball.id);
                ball_children_width = ball_children.style.width;
                ball_children.style.width = '150px';
                ball_id = ball.id.slice(9);
                ball_info_children = document.querySelector('#memo_info' + ball_id);
                ball_info_children.style.display = 'none';
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
                    $(".modal_memo").fadeIn();
                    $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">メモを前後どちらに追加しますか？</h2><p class="modal_memo_text before_text">' + ball_text + '</p><p>' + memo_text + '</p><p class="modal_memo_text after_text">' + ball_text + '</p><div class="right"><button class="btn memo_close modal_close" type="button">キャンセル</button></div></div>');
                    $('.modal_close').on('click', function(event) {
                        $(".memo_edit_process").fadeOut();
                        $(".modal_memo").fadeOut();
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
        let droppableBelow_memogroup = elemBelow.closest('.memo_group_list');
        if (currentDroppable_memogroup != droppableBelow_memogroup) {
            if (currentDroppable_memogroup) { // null when we were not over a droppable before this event
                leaveDroppable_memogroup(currentDroppable_memogroup, ball);
            }


            currentDroppable_memogroup = droppableBelow_memogroup;
            if (currentDroppable_memogroup) {
                ball_children = document.querySelector('#' + ball.id);
                ball_children_width = ball_children.style.width;
                ball_children.style.width = '150px';
                ball_id = ball.id.slice(9);
                ball_info_children = document.querySelector('#memo_info' + ball_id);
                ball_info_children.style.display = 'none';
                $('.memo').on('mouseup', function() {
                    var ball_target = $(this).data("target"),
                        ball = document.querySelector(ball_target),
                        memo_group_id = ball.id.slice(9) + " ",
                        group_id = currentDroppable_memogroup.id.slice(15);
                    ball.style.display = 'none';
                    $(".memo_edit_process").fadeIn();
                    $(".modal_memo").fadeIn();
                    $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">メモグループに追加しますか？</h2><div class="right"><button class="btn ok_btn" type="button">OK</button><button class="btn memo_close modal_close" type="button">キャンセル</button></div></div>');
                    $('.modal_close').on('click', function(event) {
                        $(".memo_edit_process").fadeOut();
                        $(".modal_memo").fadeOut();
                        ball.style.display = 'block';
                        $('.memo').off();
                    });
                    $('.ok_btn').one('click', function() {
                        memo_group_list = 1;
                        enterDroppable_memogroup(currentDroppable_memogroup, ball_target, memo_group_list);
                    });
                });
            }
        }
        let droppableBelow_memogroup_create = elemBelow.closest('.memo_create');
        if (currentDroppable_memogroup_create != droppableBelow_memogroup_create) {
            if (currentDroppable_memogroup_create) { // null when we were not over a droppable before this event
                leaveDroppable_memogroup_create(currentDroppable_memogroup_create, ball);
            }


            currentDroppable_memogroup_create = droppableBelow_memogroup_create;
            if (currentDroppable_memogroup_create) {
                ball_children = document.querySelector('#' + ball.id);
                ball_children_width = ball_children.style.width;
                ball_children.style.width = '150px';
                ball_id = ball.id.slice(9);
                ball_info_children = document.querySelector('#memo_info' + ball_id);
                ball_info_children.style.display = 'none';
                $('.memo').on('mouseup', function() {
                    var ball_target = $(this).data("target"),
                        ball = document.querySelector(ball_target),
                        memo_group_id = ball.id.slice(9) + " ",
                        group_id = currentDroppable_memogroup_create.id.slice(15),
                        memo_group_text = $("#memo" + memo_group_id).text();
                    ball.style.display = 'none';
                    $(".memo_edit_process").fadeIn();
                    $(".modal_memo").fadeIn();
                    $(".modal_edit_process").replaceWith('<div class="modal_edit_process"><h2 class="memo_title">メモグループを新規作成しますか？</h2><div class="right"><button class="btn ok_btn" type="button">OK</button><button class="btn memo_close modal_close" type="button">キャンセル</button></div></div>');
                    $('.modal_close').on('click', function(event) {
                        $(".memo_edit_process").fadeOut();
                        $(".modal_memo").fadeOut();
                        ball.style.display = 'block';
                        $('.memo').off();
                    });
                    $('.ok_btn').one('click', function() {
                        enterDroppable_memogroup_create(currentDroppable_memogroup_create, ball_target);
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

function leaveDroppable(elem, ball) {
    $('.memo').off();
    ball_children = document.querySelector('#' + ball.id);
    ball_children.style.width = '200px';
    ball_id = ball.id.slice(9);
    ball_info_children = document.querySelector('#memo_info' + ball_id);
    ball_info_children.style.display = 'block';
}

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
    $(".modal_memo").fadeOut();
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

function leaveDroppable_memogroup(elem, ball) {
    $('.memo').off();
    ball_children = document.querySelector('#' + ball.id);
    ball_children.style.width = '200px';
    ball_id = ball.id.slice(9);
    ball_info_children = document.querySelector('#memo_info' + ball_id);
    ball_info_children.style.display = 'block';
}

function enterDroppable_memogroup(elem, ball_target, memo_group_list) {
    var ball = document.querySelector(ball_target),
        memo_group_id = ball.id.slice(9) + " ",
        memo_id = ball.id.slice(9),
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
            ball_id: memo_id,
            group_id: group_id,
            group_max_id: group_max_id,
            memo_group_id: memo_group_id,
            memo_group_list: memo_group_list,
            delete_flg: delete_flg
        }
    }).done(function() {
        // メモグループ更新時の処理を記載する
        // ('.memo_create_form' + group_id)のgroup_idで更新をかけるメモを指定したい
        $('.memo_create_form' + group_id).replaceWith('<div class="memo"><div class="memo_list"><div class="memo_text ellipsis" id="memo' + memo_id + '" data-target="#memo' + memo_id + '" data-toggle="memo" >' + memo_text + '</div></div></div><input type="hidden" class="memo_create_form' + group_id + '" name="memo_create">');
        $('.memo').off();
    }).fail(function() {});
}

function leaveDroppable_memogroup_create(elem, ball) {
    $('.memo').off();
    ball_children = document.querySelector('#' + ball.id);
    ball_children.style.width = '200px';
    ball_id = ball.id.slice(9);
    ball_info_children = document.querySelector('#memo_info' + ball_id);
    ball_info_children.style.display = 'block';
}

function enterDroppable_memogroup_create(elem, ball_target) {
    var ball = document.querySelector(ball_target),
        group_id = elem.id.slice(15),
        memo_group_id = ball.id.slice(9) + " ",
        memo_id = ball.id.slice(9),
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
            ball_id: memo_id,
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
        var ball = document.querySelector($target_modal),
            group_id = ball.id.slice(15),
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
                console.log($($target_modal)[0]);
                $($target_modal)[0].style.display = 'none';
            }).fail(function() {});
        });
    }, LONGPRESS);
}).on("mouseup mouseleave touchend", function() {
    clearTimeout(timerId);
});

if (ball != null) {
    ball.ondragstart = function() {
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

// $('.click').on('click', function() {
//     // if (jqxhr) { //追記部分
//     //     return;
//     // }
//     /*jqxhr = */
//     $.ajax({
//         type: 'POST',
//         url: '../click.php',
//         dataType: 'text'
//     }).done(function() { console.log("test"); }).fail(function() {});
// });

// var ball = document.querySelector('.memo');
// var ball_list = document.querySelector('.memo_list');

// $(document).on('dblclick', '.memo', function(event) {
//     let ball_id = $(this).data("target");
//     let ball = document.querySelector(ball_id);
//     let currentDroppable = null;
//     let currentDroppable_memogroup = null;
//     let shiftX = event.clientX - ball.getBoundingClientRect().left;
//     let shiftY = event.clientY - ball.getBoundingClientRect().top;

//     //絶対位置で一番上になるように
//     ball.style.position = 'absolute';
//     ball.style.zIndex = 1000;

//     //対象オブジェクトをbody要素に追加
//     document.body.append(ball);

//     moveAt(event.pageX, event.pageY);

//     function moveAt(pageX, pageY) {
//         ball.style.left = pageX - shiftX + 'px';
//         ball.style.top = pageY - shiftY + 'px';
//     }

//     function onMouseMove(event) {
//         moveAt(event.pageX, event.pageY);

//         ball.hidden = true;
//         let elemBelow = document.elementFromPoint(event.clientX, event.clientY);
//         ball.hidden = false;

//         let droppableBelow_memogroup = elemBelow.closest('.memo_area');
//         if (currentDroppable_memogroup != droppableBelow_memogroup) {
//             if (currentDroppable_memogroup) { // null when we were not over a droppable before this event
//                 leaveDroppable(currentDroppable_memogroup, ball);
//             }
//         }

//         currentDroppable_memogroup = droppableBelow_memogroup;
//         if (currentDroppable_memogroup) {
//             //var jqxhr;
//             $('.memo').on('mouseup', function() {
//                 var ball_target = $(this).data("target"),
//                     ball = document.querySelector(ball_target),
//                     memo_group_id = ball.id.slice(9) + " ",
//                     group_id = currentDroppable_memogroup.id.slice(15),
//                     memo_group_list = 1;
//                 /*if (jqxhr) { //追記部分
//                     return;
//                 }
//                 /*jqxhr =*/
//                 $.ajax({
//                     type: 'POST',
//                     url: '../ajax_edit_memo.php',
//                     dataType: 'text',
//                     data: {
//                         group_id: group_id,
//                         memo_group_id: memo_group_id,
//                         memo_group_list: memo_group_list
//                     }
//                 }).done(function() { console.log("test"); }).fail(function() {});
//             });
//         }
//     }

//     document.addEventListener('mousemove', onMouseMove);

//     ball.onmouseup = function() {
//         document.removeEventListener('mousemove', onMouseMove);
//         ball.onmouseup = null;
//     };
// });