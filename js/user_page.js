// 変数定義
var user_comment = $('.comment').text(),
    user_name = $('.profile_name').text(),
    user_comment_narrow = $('.comment_narrow').text(),
    user_name_narrow = $('.profile_name_narrow').text(),
    user_comment_narrower = $('.comment_narrower').text(),
    user_name_narrower = $('.profile_name_narrower').text(),
    user_workhistory = $('.workhistory').text(),
    user_workhistory_narrow = $('.workhistory_narrow').text(),
    user_workhistory_narrower = $('.workhistory_narrower').text(),
    user = $('.user').val();

// getパラメータ取得
function get_param(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return false;
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function file_name(extension) {
    var s = this.replace(/\\/g, '/');
    s = s.substring(s.lastIndexOf('/') + 1);
    return extension ? s.replace(/[?#].+$/, '') : s.split('.')[0];
}

// ビジーwaitを使う方法
function sleep(waitMsec) {
    var startMsec = new Date();

    // 指定ミリ秒間だけループさせる（CPUは常にビジー状態）
    while (new Date() - startMsec < waitMsec);
}

//================================
// 画像処理
//================================

// 画像の選択時、表示処理
$('#my_image').on('change', function(e) {
    var reader = new FileReader();
    $(".my_preview").fadeIn();
    $(".message_text").fadeOut();
    reader.onload = function(e) {
        $(".my_preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});

// 画像の選択時、表示処理
$('#memo_image').on('change', function(e) {
    var reader = new FileReader();
    $(".memo_preview").fadeIn();
    reader.onload = function(e) {
        $(".memo_preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});

$('#my_image').on('change', function(e) {
    //画像を選択した際に、メッセージテキストに画像情報を追加する
    var img = document.getElementById('my_image');
    $('#message').val = ImageToBase64(img, "image/jpeg");
});

$('#edit_image,#edit_image_narrow,#edit_image_narrower').on('change', function(e) {
    var reader = new FileReader();
    $(".edit_preview").fadeIn();
    reader.onload = function(e) {
        $(".edit_preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});

$('#comment_image,#comment_image_narrow,#comment_image_narrower').on('change', function(e) {
    var reader = new FileReader();
    $(".comment_preview").fadeIn();
    reader.onload = function(e) {
        $(".comment_preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});

$('#reply_comment_image').on('change', function(e) {
    var reader = new FileReader();
    $(".reply_comment_preview").fadeIn();
    reader.onload = function(e) {
        $(".reply_comment_preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});

$('#edit_profile_img,#edit_profile_img_narrow,#edit_profile_img_narrower').on('change', function(e) {
    var reader = new FileReader();
    $(".editing_profile_img").fadeIn();
    reader.onload = function(e) {
        $(".editing_profile_img").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});

$(document).on('change', '#my_image', function() {
    $('.far.fa-times-circle.my_clear').show();
    $(document).on('click', '.far.fa-times-circle.my_clear', function() {
        $(".message_text").fadeIn();
        $('#my_image').val('');
        $('.far.fa-times-circle.my_clear').hide();
        $('.my_preview').hide();
        $('#edit_profile_img,#edit_profile_img_narrow,#edit_profile_img_narrower').val('');
    });
});

$(document).on('change', '#edit_profile_img,#edit_profile_img_narrow,#edit_profile_img_narrower', function() {
    $('.far.fa-times-circle.profile_clear').show();
    $(document).on('click', '#profile_clear', function() {
        $('#edit_profile_img,#edit_profile_img_narrow,#edit_profile_img_narrower').val('');
        $('.far.fa-times-circle.profile_clear').hide();
        $('.editing_profile_img').hide();
    });
});

$(document).on('change', '#comment_image,#comment_image_narrow,#comment_image_narrower', function() {
    $('.far.fa-times-circle.comment_clear').show();
    $(document).on('click', '.far.fa-times-circle.comment_clear', function() {
        $('#comment_image,#comment_image_narrow,#comment_image_narrower').val('');
        $(this).hide();
        $('.comment_preview').hide();
        $('.far.fa-times-circle.comment_clear').hide();
    });
});

$(document).on('change', '#reply_comment_image', function() {
    $('.far.fa-times-circle.reply_comment_clear').show();
    $(document).on('click', '.far.fa-times-circle.reply_comment_clear', function() {
        $('#reply_comment_image').val('');
        $(this).hide();
        $('.reply_comment_preview').hide();
        $('.far.fa-times-circle.edit_clear').hide();
    });
});

$(document).on('change', '#edit_image,#edit_image_narrow,#edit_image_narrower', function() {
    $('.far.fa-times-circle.edit_clear').show();
    $(document).on('click', '.far.fa-times-circle.edit_clear', function() {
        $('#edit_image,#edit_image_narrow,#edit_image_narrower').val('');
        $(this).hide();
        $('.edit_preview').hide();
        $('.far.fa-times-circle.edit_clear').hide();
    });
});

$('#edit_profile_img,#edit_profile_img_narrow,#edit_profile_img_narrower').on('change', function(e) {
    var reader = new FileReader();
    reader.onload = function(e) {
        file_name = $(".editing_profile_img").attr('src', e.target.result).file_name();
    }
    reader.readAsDataURL(e.target.files[0]);
});

//================================
// ajax処理
//================================

// いいね機能処理
$(document).on('click', '.favorite_btn', function(e) {
    e.stopPropagation();
    var $this = $(this),
        post_id = $this.prev().val();
    $.ajax({
        type: 'POST',
        url: '../ajax_post_favorite_process.php',
        dataType: 'json',
        data: {
            post_id: post_id
        }
    }).done(function() {}).fail(function() {});
});

// フォロー機能処理
$(document).on('click', '.follow_btn', function(e) {
    e.stopPropagation();
    var $this = $(this),
        current_user_id = $('.current_user_id').val(),
        user_id = $this.prev().val();
    $.ajax({
        type: 'POST',
        url: '../ajax_follow_process.php',
        dataType: 'json',
        data: {
            current_user_id: current_user_id,
            user_id: user_id
        }
    }).done(function() {}).fail(function() {});
});

//================================
// モーダルウィンドウ処理
//================================

// ウィンドウ横のモーダルウィンドウ処理
$('.slide_menu').show();
$('.show_menu').on('click', function() {
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    $('.modal').fadeIn();
    $('.slide_menu').addClass('open');
})

$('.slide_prof').on('click', function() {
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    $('.slide_prof').addClass('open');
})

// モーダル画面キャンセルボタン押下時の処理
$(document).on('click', ".modal_close", function() {
    $('body').removeClass('fixed').css({ 'top': 0 });
    window.scrollTo(0, scroll_position);
    $('.modal').fadeOut();
    $('.modal_post').fadeOut();
    $('.modal_memo').fadeOut();
    $('.modal_prof').fadeOut();
    $('.modal_withdraw').fadeOut();
    $('.delete_confirmation').fadeOut();
    $('.post_process').fadeOut();
    $('.memo_process').fadeOut();
    $('.withdraw_process').fadeOut();
    $('.post_edit').fadeOut();
    $('.comment_confirmation').fadeOut();
    $('.reply_comment_confirmation').fadeOut();
    $('.edit_comment').replaceWith('<p class="comment">' + user_comment + '</p>');
    $('.edit_name').replaceWith('<h2 class="profile_name">' + user_name + '</h2>');
    $('.edit_comment_narrow').replaceWith('<p class="comment">' + user_comment_narrow + '</p>');
    $('.edit_name_narrow').replaceWith('<h2 class="profile_name">' + user_name_narrow + '</h2>');
    $('.edit_comment_narrower').replaceWith('<p class="comment">' + user_comment_narrower + '</p>');
    $('.edit_name_narrower').replaceWith('<h2 class="profile_name">' + user_name_narrower + '</h2>');
    $('.edit_workhistory').replaceWith('<p class="workhistory">' + user_workhistory + '</p>');
    $('.edit_workhistory_narrow').replaceWith('<p class="workhistory_narrow">' + user_workhistory_narrow + '</p>');
    $('.edit_workhistory_narrower').replaceWith('<p class="workhistory_narrow">' + user_workhistory_narrower + '</p>');
    $('.mypage').css('display', 'inline');
    $('.edit_profile_img').css('display', 'none');
    $('.btn_flex').css('display', 'none');
    $('.profile').removeClass('editing');
    $('.edit_btn').fadeIn();
    $('.slide_menu').removeClass('open');
    $('.form').fadeOut();
    $('.tag').css('display', 'inline-block');
});

// 編集ボタン押下時の処理
$(document).on('click', '.edit_btn', function() {
    scroll_position = $(window).scrollTop();
    $('.edit_btn').fadeOut();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    $('.comment').replaceWith('<textarea class="edit_comment form-control" type="text" name="user_comment" >' + user_comment);
    $('.profile_name').replaceWith('<input class="edit_name form-control" type="text" name="user_name" value="' + user_name + '">');
    $('.comment_narrow').replaceWith('<textarea class="edit_comment form-control" type="text" name="user_comment" >' + user_comment_narrow);
    $('.profile_name_narrow').replaceWith('<input class="edit_name form-control" type="text" name="user_name" value="' + user_name_narrow + '">');
    $('.comment_narrower').replaceWith('<textarea class="edit_comment form-control" type="text" name="user_comment" >' + user_comment_narrower);
    $('.profile_name_narrower').replaceWith('<input class="edit_name form-control" type="text" name="user_name" value="' + user_name_narrower + '">');
    $('.workhistory').replaceWith('<textarea class="edit_workhistory form-control" type="text" name="user_workhistory" >' + user_workhistory);
    $('.workhistory_narrow').replaceWith('<textarea class="edit_workhistory form-control" type="text" name="user_workhistory" >' + user_workhistory_narrow);
    $('.workhistory_narrower').replaceWith('<textarea class="edit_workhistory form-control" type="text" name="user_workhistory" >' + user_workhistory_narrower);
    $('.mypage').css('display', 'none');
    $('.edit_profile_img').css('display', 'inline-block');
    $('.btn_flex').css('display', 'flex');
    $('.profile').addClass('editing');
    $('.form').css('display', 'inline-block');
    $('.tag').fadeOut();
});

// モーダル画面出力ボタン押下時の処理
$(document).on('click', '.modal_btn', function() {
    var $target_modal = $(this).data("target")
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    $($target_modal).fadeIn();
    $('.modal').fadeIn();
});

// 投稿モーダル画面出力処理
$(document).on('click', '.post_modal', function() {
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    $('.post_process').fadeIn();
    $('.modal_post').fadeIn();
});

// 投稿モーダル画面出力処理
$(document).on('click', '.memo_modal', function() {
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    $('.memo_process').fadeIn();
    $('.modal_memo').fadeIn();
});

$(document).on('click', '.prof_modal', function() {
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    $('.slide_prof').fadeIn();
    $('.modal_prof').fadeIn();
    $('.slide_prof').addClass('open');
});

$(document).on('click', '.withdraw', function() {
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({ 'top': -scroll_position });
    // モーダルウィンドウを開く
    $('.withdraw_process').fadeIn();
    $('.modal_withdraw').fadeIn();
});

$(document).on('click', ".prof_close", function() {
    window.scrollTo(0, scroll_position);
    $('body').removeClass('fixed').css({ 'top': 0 });
    $('.slide_prof').fadeOut();
    $('.modal_prof').fadeOut();
    $('.slide_prof').removeClass('open');
});

//================================
// カウンタ処理
//================================

// テキストエリア内の文字数表示
$('#post_counter').on('input', function() {
    var count = $(this).val().length;
    $('.post_count').text(count);
    if (count > 300) {
        $('.post_count').css('color', '#FF7763');
    } else {
        $('.post_count').css('color', '#000');
    }
});

$('#comment_counter,#comment_counter_narrow,#comment_counter_narrower').on('input', function() {
    var count = $(this).val().length;
    $('.comment_count').text(count);
    if (count > 300) {
        $('comment_count').css('color', '#FF7763');
    } else {
        $('comment_count').css('color', '#FFF');
    }
});

$('#edit_counter,#edit_counter_narrow,#edit_counter_narrower').on('input', function() {
    var count = $(this).val().length;
    $('.post_edit_count').text(count);
    if (count > 300) {
        $('.post_edit_count').css('color', '#FF7763');
    } else {
        $('.post_edit_count').css('color', '#FFF');
    }
});

$('#post_process_counter').on('input', function() {
    var count = $(this).val().length;
    $('.post_process_count').text(count);
    if (count > 300) {
        $('.post_process_count').css('color', '#FF7763');
    } else {
        $('.post_process_count').css('color', '#FFF');
    }
});

$('#memo_process_counter').on('input', function() {
    var count = $(this).val().length;
    $('.memo_process_count').text(count);
    if (count > 300) {
        $('.memo_process_count').css('color', '#FF7763');
    } else {
        $('.memo_process_count').css('color', '#FFF');
    }
});

$('#message_counter').on('input', function() {
    var count = $(this).val().length;
    $('.message_count').text(count);
    if (count > 300) {
        $('.message_count').css('color', '#FF7763');
    } else {
        $('.message_count').css('color', '#000');
    }
});

// 文字数が0文字、300文字以上以外ボタンを活性化
$(document).on('input', '.textarea', function() {
    if ($(this).val().length !== 0 && $(this).val().length <= 300) {
        $('#post').prop('disabled', false);
    } else {
        $('#post').prop('disabled', true);
    }
});

$(document).on('input', '.textarea', function() {
    if ($(this).val().length !== 0 && $(this).val().length <= 300) {
        $('#post_btn').prop('disabled', false);
    } else {
        $('#post_btn').prop('disabled', true);
    }
});

//================================
// スキルタグ処理（大画面）
//================================

const skill_list = new Array(
    'AWS',
    'Bootstrap',
    'C',
    'CakePHP',
    'C#',
    'C++',
    'COBOL',
    'CSS',
    'Docker',
    'Go',
    'Git',
    'HTTP',
    'iOS',
    'Java',
    'JavaScript',
    'JIRA',
    'Kotlin',
    'Laravel',
    'MATLAB',
    'MySQL',
    'Oracle Database',
    'Perl',
    'PHP',
    'PostgreSQL',
    'Python',
    'R',
    'React',
    'Ruby',
    'Ruby on Rails',
    'Rust',
    'SVN',
    'SSL',
    'SQLite',
    'TypeScript',
    'Vue.js'
);

$(function() {
    $("#skill_input").autocomplete({
        source: "../autocomplete_skill.php"
    });
});

$(function() {
    $("#skill_input_narrow").autocomplete({
        source: "../autocomplete_skill.php"
    });
});

$(function() {
    $("#skill_input_narrower").autocomplete({
        source: "../autocomplete_skill.php"
    });
});

if (document.getElementById('skill_input') != null || document.getElementById('skill_input_narrow') != null || document.getElementById('skill_input_narrower') != null) {
    let skill_input = document.getElementById('skill_input');
    let skill_input_narrow = document.getElementById('skill_input_narrow');
    let skill_input_narrower = document.getElementById('skill_input_narrower');
    skill_input.addEventListener('change', inputChange_skill);
    skill_input_narrow.addEventListener('change', inputChange_skill);
    skill_input_narrower.addEventListener('change', inputChange_skill);

    var skill = document.getElementById("skill"),
        skill_narrow = document.getElementById("skill_narrow"),
        skill_narrower = document.getElementById("skill_narrower"),
        spans = skill.getElementsByTagName("span"),
        spans_narrow = skill_narrow.getElementsByTagName("span_narrow"),
        spans_narrower = skill_narrower.getElementsByTagName("span_narrower");

    // 初期状態のタグ数でskill_countの値を決める
    if (document.getElementById('skill_count').val) {
        if (spans.length > 3) {
            skill_count_val = spans.length % 3;
            switch (skill_count_val) {
                case 0:
                    document.getElementById('skill_count').val = 3;
                    break;

                case 1:
                    document.getElementById('skill_count').val = 1;
                    break;

                case 2:
                    document.getElementById('skill_count').val = 2;
                    break;

                default:
            }
        }
    }

    // 初期状態のタグ数でskill_countの値を決める
    if (document.getElementById('skill_count_narrow').val === undefined) {
        if (spans_narrow.length > 3) {
            skill_count_val_narrow = spans_narrow.length % 3;
            switch (skill_count_val_narrow) {
                case 0:
                    document.getElementById('skill_count_narrow').val = 3;
                    break;

                case 1:
                    document.getElementById('skill_count_narrow').val = 1;
                    break;

                case 2:
                    document.getElementById('skill_count_narrow').val = 2;
                    break;

                default:
            }
        }
    }

    // 初期状態のタグ数でskill_countの値を決める
    if (document.getElementById('skill_count_narrower').val === undefined) {
        if (spans_narrower.length > 3) {
            skill_count_val_narrower = spans.length % 3;
            switch (skill_count_val_narrower) {
                case 0:
                    document.getElementById('skill_count_narrower').val = 3;
                    break;

                case 1:
                    document.getElementById('skill_count_narrower').val = 1;
                    break;

                case 2:
                    document.getElementById('skill_count_narrower').val = 2;
                    break;

                default:
            }
        }
    }
}

function inputChange_skill() {
    var fome_x_name = $(this).val(),
        fome_x_name_narrow = $(this).val(),
        fome_x_name_narrower = $(this).val(),
        skill = document.getElementById("skill"),
        skill_narrow = document.getElementById("skill_narrow"),
        skill_narrower = document.getElementById("skill_narrower"),
        skills = new Array(),
        skills_narrow = new Array(),
        skills_narrower = new Array(),
        spans = skill.getElementsByTagName("span"),
        spans_narrow = skill_narrow.getElementsByTagName("span"),
        spans_narrower = skill_narrower.getElementsByTagName("span");

    for (i = 0; i < spans.length; i++) {
        skills[i] = spans[i].textContent;
    }

    for (i = 0; i < spans_narrow.length; i++) {
        skills_narrow[i] = spans_narrow[i].textContent;
    }

    for (i = 0; i < spans_narrower.length; i++) {
        skills_narrower[i] = spans_narrower[i].textContent;
    }

    skills = skills.join('');
    skills_narrow = skills_narrow.join('');
    skills_narrower = skills_narrower.join('');

    // 既に入力済みのものはタグ追加しない
    if (skills.indexOf(fome_x_name) != -1 || skills_narrow.indexOf(fome_x_name_narrow) != -1 || skills_narrower.indexOf(fome_x_name_narrower) != -1) {
        return false;
    }
    // 入力した文字列がlistと合えばタグ追加
    if (skill_list.indexOf(fome_x_name) != -1 || skill_list.indexOf(fome_x_name_narrow) != -1 || skill_list.indexOf(fome_x_name_narrower) != -1) {

        var span_element = document.createElement("span"),
            span_element_narrow = document.createElement("span"),
            span_element_narrower = document.createElement("span"),
            label_element = document.createElement("label"),
            label_element_narrow = document.createElement("label"),
            label_element_narrower = document.createElement("label"),
            i_element = document.createElement("i"),
            i_element_narrow = document.createElement("i"),
            i_element_narrower = document.createElement("i"),
            input_element = document.createElement("input"),
            input_element_narrow = document.createElement("input"),
            input_element_narrower = document.createElement("input"),
            newContent = document.createTextNode(fome_x_name),
            newContent_narrow = document.createTextNode(fome_x_name_narrow),
            newContent_narrower = document.createTextNode(fome_x_name_narrower),
            div_element = document.createElement("div"),
            div_element_narrow = document.createElement("div"),
            div_element_narrower = document.createElement("div"),
            parentDiv = document.getElementById("skill"),
            parentDiv_narrow = document.getElementById("skill_narrow"),
            parentDiv_narrower = document.getElementById("skill_narrower"),
            skill_count = document.getElementById('skill_count').val,
            skill_count_narrow = document.getElementById('skill_count_narrow').val,
            skill_count_narrower = document.getElementById('skill_count_narrower').val;

        span_element.appendChild(newContent);
        span_element_narrow.appendChild(newContent_narrow);
        span_element_narrower.appendChild(newContent_narrower);
        span_element.setAttribute("id", "child-span" + i + "");
        span_element_narrow.setAttribute("id", "child-span_narrow" + i + "");
        span_element_narrower.setAttribute("id", "child-span_narrower" + i + "");
        span_element.setAttribute("class", "skill_tag");
        span_element_narrow.setAttribute("class", "skill_tag");
        span_element_narrower.setAttribute("class", "skill_tag");
        span_element.setAttribute("style", "margin-right:4px;");
        span_element_narrow.setAttribute("style", "margin-right:4px;");
        span_element_narrower.setAttribute("style", "margin-right:4px;");
        div_element.setAttribute("id", "span" + i + "");
        div_element_narrow.setAttribute("id", "span_narrow" + i + "");
        div_element_narrower.setAttribute("id", "span_narrower" + i + "");
        i_element.setAttribute("class", "far fa-times-circle skill");
        i_element_narrow.setAttribute("class", "far fa-times-circle skill");
        i_element_narrower.setAttribute("class", "far fa-times-circle skill");
        input_element.setAttribute("type", "button");
        input_element_narrow.setAttribute("type", "button");
        input_element_narrower.setAttribute("type", "button");

        // タグの改行があった場合
        if (0 < document.getElementById('skill_count').val || 0 < document.getElementById('skill_count_narrow').val || 0 < document.getElementById('skill_count_narrower').val) {
            i--;
            var skills = new Array();
            var skills_narrow = new Array();
            var skills_narrower = new Array();

            // 改行した列で再度文字数取得
            for (k = 0; k < skill_count; k++) {
                skills[k] = spans[i].textContent;
                i--;
            }

            // 改行した列で再度文字数取得
            for (k = 0; k < skill_count_narrow; k++) {
                skills_narrow[k] = spans[i].textContent;
                i--;
            }

            // 改行した列で再度文字数取得
            for (k = 0; k < skill_count_narrower; k++) {
                skills_narrower[k] = spans[i].textContent;
                i--;
            }
            spans = '';
            spans_narrow = '';
            spans_narrower = '';
            skills = skills.join('');
            skills_narrow = skills_narrow.join('');
            skills_narrower = skills_narrower.join('');

            // skill_countの値で改行後のタグ数を決める
            switch (skill_count) {
                case 2:
                    i += 1;
                    spans = '@@';
                    break;

                case 3:
                    i += 2;
                    spans = '@@@';
                    break;
                case 4:
                    i += 3;
                    spans = '@@@@';
                    break;

                case 5:
                    i += 4;
                    spans = '@@@@@';
                    break;
                default:
            }

            // skill_countの値で改行後のタグ数を決める
            switch (skill_count_narrow) {
                case 2:
                    i += 1;
                    spans_narrow = '@@';
                    break;

                case 3:
                    i += 2;
                    spans_narrow = '@@@';
                    break;
                case 4:
                    i += 3;
                    spans_narrow = '@@@@';
                    break;

                case 5:
                    i += 4;
                    spans_narrow = '@@@@@';
                    break;
                default:
            }
            // skill_countの値で改行後のタグ数を決める
            switch (skill_count_narrower) {
                case 2:
                    i += 1;
                    spans_narrower = '@@';
                    break;

                case 3:
                    i += 2;
                    spans_narrower = '@@@';
                    break;
                case 4:
                    i += 3;
                    spans_narrower = '@@@@';
                    break;

                case 5:
                    i += 4;
                    spans_narrower = '@@@@@';
                    break;
                default:
            }
            i++;
            document.getElementById('skill_count').val += 1;
            document.getElementById('skill_count_narrow').val += 1;
            document.getElementById('skill_count_narrower').val += 1;
        }

        // タグ数が３つ以上または、タグの文字数が９文字以上は改行
        if ((3 <= spans.length || 9 <= skills.length) || (3 <= spans_narrow.length || 9 <= skills_narrow.length) || (3 <= spans_narrower.length || 9 <= skills_narrower.length)) {　　
            i--;
            if (document.getElementById('child-span' + i + '') !== null || document.getElementById('child-span_narrow' + i + '') !== null | document.getElementById('child-span_narrower' + i + '') !== null) {
                parentDiv.appendChild(div_element, document.getElementById('child-span' + i + ''));
                parentDiv_narrow.appendChild(div_element_narrow, document.getElementById('child-span_narrow' + i + ''));
                parentDiv_narrower.appendChild(div_element_narrower, document.getElementById('child-span_narrower' + i + ''));
            }
            i++;
            document.getElementById('skill_count').val = 1;
            document.getElementById('skill_count_narrow').val = 1;
            document.getElementById('skill_count_narrower').val = 1;
        }
        i++;

        parentDiv.appendChild(span_element, parentDiv.firstChild);
        parentDiv_narrow.appendChild(span_element_narrow, parentDiv_narrow.firstChild);
        parentDiv_narrower.appendChild(span_element_narrower, parentDiv_narrower.firstChild);
        span_element.appendChild(label_element, span_element.firstChild);
        span_element_narrow.appendChild(label_element_narrow, span_element_narrow.firstChild);
        span_element_narrower.appendChild(label_element_narrower, span_element_narrower.firstChild);
        label_element.insertBefore(i_element, label_element.firstChild);
        label_element_narrow.insertBefore(i_element_narrow, label_element_narrow.firstChild);
        label_element_narrower.insertBefore(i_element_narrower, label_element_narrower.firstChild);
        label_element.insertBefore(input_element, label_element.firstChild);
        label_element_narrow.insertBefore(input_element_narrow, label_element_narrow.firstChild);
        label_element_narrower.insertBefore(input_element_narrower, label_element_narrower.firstChild);
        $(this).val('');
    }
}

// タグのバツ印がクリックされた場合
$(document).on('click', '.far.fa-times-circle.skill', function() {
    var k = 0,
        skills = new Array(),
        skill = document.getElementById("skill"),
        spans = skill.getElementsByTagName("span"),
        span = $(this).parents(".skill_tag")[0].textContent;

    // skill_countの値を元に最終行のタグ情報を取得
    switch (document.getElementById('skill_count').val) {
        case 1:
            var spans_count = spans.length - 1;
            break;

        case 2:
            var spans_count = spans.length - 2;
            break;

        case 3:
            var spans_count = spans.length - 3;
            break;

        default:
    }
    for (i = spans_count; i < spans.length; i++) {
        skills[k] = spans[i].textContent;
        k++;
    }
    $(this).parents(".skill_tag").remove();

    skills = skills.join('');
    if (skills.indexOf(span) != -1) {
        document.getElementById('skill_count').val -= 1;
    }
});

// タグのバツ印がクリックされた場合
$(document).on('click', '.far.fa-times-circle.skill_narrow', function() {
    var k = 0,
        skills_narrow = new Array(),
        skill_narrow = document.getElementById("skill_narrow"),
        spans_narrow = skill_narrow.getElementsByTagName("span"),
        span_narrow = $(this).parents(".skill_tag")[0].textContent;

    // skill_countの値を元に最終行のタグ情報を取得
    switch (document.getElementById('skill_count_narrow').val) {
        case 1:
            var spans_count_narrow = spans_narrow.length - 1;
            break;

        case 2:
            var spans_count_narrow = spans_narrow.length - 2;
            break;

        case 3:
            var spans_count_narrow = spans_narrow.length - 3;
            break;

        default:
    }
    for (i = spans_count_narrow; i < spans_narrow.length; i++) {
        skills_narrow[k] = spans_narrow[i].textContent;
        k++;
    }
    $(this).parents(".skill_tag").remove();

    skills_narrow = skills_narrow.join('');
    if (skills_narrow.indexOf(span_narrow) != -1) {
        document.getElementById('skill_count_narrow').val -= 1;
    }
});

// タグのバツ印がクリックされた場合
$(document).on('click', '.far.fa-times-circle.skill_narrower', function() {
    var k = 0,
        skills_narrower = new Array(),
        skill_narrower = document.getElementById("skill_narrower"),
        spans_narrower = skill_narrower.getElementsByTagName("span"),
        span_narrower = $(this).parents(".skill_tag")[0].textContent;

    // skill_countの値を元に最終行のタグ情報を取得
    switch (document.getElementById('skill_count_narrower').val) {
        case 1:
            var spans_count_narrower = spans_narrower.length - 1;
            break;

        case 2:
            var spans_count_narrower = spans_narrower.length - 2;
            break;

        case 3:
            var spans_count_narrower = spans_narrower.length - 3;
            break;

        default:
    }
    for (i = spans_count_narrower; i < spans_narrower.length; i++) {
        skills_narrower[k] = spans_narrower[i].textContent;
        k++;
    }
    $(this).parents(".skill_tag").remove();

    skills_narrower = skills_narrower.join('');
    if (skills_narrower.indexOf(span_narrower) != -1) {
        document.getElementById('skill_count_narrower').val -= 1;
    }
});

$(document).on('click', '.edit_done', function() {
    var skill = document.getElementById("skill"),
        skill = document.getElementById("skill_narrow"),
        skill = document.getElementById("skill_narrower"),
        skill_div = document.getElementById("skills"),
        skill_div_narrow = document.getElementById("skills_narrow"),
        skill_div_narrower = document.getElementById("skills_narrower"),
        spans = skill.getElementsByTagName("span"),
        skills = new Array();
    document.getElementById('skill_count').val = 4;

    for (i = 0; i < spans.length; i++) {
        skills[i] = spans[i].textContent;
    }
    skills = skills.join(' ');
    console.log(skills);
    skill_div.value = skills;
    skill_div_narrow.value = skills;
    skill_div_narrower.value = skills;

});

//================================
// 資格タグ処理
//================================

const licence_list = new Array(
    'ITパスポート',
    '基本情報技術者',
    '応用情報技術者',
    'ITストラテジスト',
    'ITサービスマネージャー',
    'プロジェクトマネージャー',
    'システム監査技術者',
    'エンベデッドシステムスペシャリスト',
    'システムアーキテクト',
    'データベーススペシャリスト',
    'ネットワークスペシャリスト',
    '情報セキュリティスペシャリスト'
);

$(function() {
    $("#licence_input").autocomplete({
        source: "../autocomplete_licence.php"
    });
});

$(function() {
    $("#licence_input_narrow").autocomplete({
        source: "../autocomplete_licence.php"
    });
});

$(function() {
    $("#licence_input_narrower").autocomplete({
        source: "../autocomplete_licence.php"
    });
});

if (document.getElementById('licence_input') != null || document.getElementById('licence_input_narrow') != null || document.getElementById('licence_input_narrower') != null) {
    let licence_input = document.getElementById('licence_input'),
        licence_input_narrow = document.getElementById('licence_input_narrow'),
        licence_input_narrower = document.getElementById('licence_input_narrower');
    licence_input.addEventListener('change', inputChange_licence);
    licence_input_narrow.addEventListener('change', inputChange_licence);
    licence_input_narrower.addEventListener('change', inputChange_licence);

    var licence = document.getElementById("licence"),
        licence_narrow = document.getElementById("licence_narrow"),
        licence_narrower = document.getElementById("licence_narrower"),
        spans = licence.getElementsByTagName("span"),
        spans_narrow = licence_narrow.getElementsByTagName("span"),
        spans_narrower = licence_narrower.getElementsByTagName("span");

    // 初期状態のタグ数でlicence_countの値を決める
    if (document.getElementById('licence_count').val === undefined || document.getElementById('licence_count_narrow').val || document.getElementById('licence_count_narrower').val) {
        if (spans.length > 3 || spans_narrow.length > 3 || spans_narrower.length > 3) {
            licence_count_val = spans.length % 3;
            licence_count_val_narrow = spans_narrow.length % 3;
            licence_count_val_narrower = spans_narrower.length % 3;
            switch (licence_count_val) {
                case 0:
                    document.getElementById('licence_count').val = 3;
                    break;

                case 1:
                    document.getElementById('licence_count').val = 1;
                    break;

                case 2:
                    document.getElementById('licence_count').val = 2;
                    break;

                default:
            }

            switch (licence_count_val_narrow) {
                case 0:
                    document.getElementById('licence_count_narrow').val = 3;
                    break;

                case 1:
                    document.getElementById('licence_count_narrow').val = 1;
                    break;

                case 2:
                    document.getElementById('licence_count_narrow').val = 2;
                    break;

                default:
            }
            switch (licence_count_val_narrower) {
                case 0:
                    document.getElementById('licence_count_narrower').val = 3;
                    break;

                case 1:
                    document.getElementById('licence_count_narrower').val = 1;
                    break;

                case 2:
                    document.getElementById('licence_count_narrower').val = 2;
                    break;

                default:
            }
        }
    }
}

function inputChange_licence() {
    var fome_x_name = $(this).val(),
        fome_x_name_narrow = $(this).val(),
        fome_x_name_narrower = $(this).val(),
        licence = document.getElementById("licence"),
        licence_narrow = document.getElementById("licence_narrow"),
        licence_narrower = document.getElementById("licence_narrower"),
        licences = new Array(),
        licences_narrow = new Array(),
        licences_narrower = new Array(),
        spans = licence.getElementsByTagName("span"),
        spans_narrow = licence_narrow.getElementsByTagName("span"),
        spans_narrower = licence_narrower.getElementsByTagName("span");

    for (i = 0; i < spans.length; i++) {
        licences[i] = spans[i].textContent;
    }

    for (i = 0; i < spans_narrow.length; i++) {
        licences_narrow[i] = spans_narrow[i].textContent;
    }

    for (i = 0; i < spans_narrower.length; i++) {
        licences_narrower[i] = spans_narrower[i].textContent;
    }

    licences = licences.join('');
    licences_narrow = licences_narrow.join('');
    licences_narrower = licences_narrower.join('');

    // 既に入力済みのものはタグ追加しない
    if (licences.indexOf(fome_x_name) != -1) {
        return false;
    }

    // 既に入力済みのものはタグ追加しない
    if (licences_narrow.indexOf(fome_x_name_narrow) != -1) {
        return false;
    }
    // 既に入力済みのものはタグ追加しない
    if (licences_narrower.indexOf(fome_x_name_narrower) != -1) {
        return false;
    }
    // 入力した文字列がlistと合えばタグ追加
    if (licence_list.indexOf(fome_x_name) != -1 || licence_list.indexOf(fome_x_name_narrow) != -1 || licence_list.indexOf(fome_x_name_narrower) != -1) {

        var span_element = document.createElement("span"),
            span_element_narrow = document.createElement("span"),
            span_element_narrower = document.createElement("span"),
            label_element = document.createElement("label"),
            label_element_narrow = document.createElement("label"),
            label_element_narrower = document.createElement("label"),
            i_element = document.createElement("i"),
            i_element_narrow = document.createElement("i"),
            i_element_narrower = document.createElement("i"),
            input_element = document.createElement("input"),
            input_element_narrow = document.createElement("input"),
            input_element_narrower = document.createElement("input"),
            newContent = document.createTextNode(fome_x_name),
            newContent_narrow = document.createTextNode(fome_x_name_narrow),
            newContent_narrower = document.createTextNode(fome_x_name_narrower),
            div_element = document.createElement("div"),
            div_element_narrow = document.createElement("div"),
            div_element_narrower = document.createElement("div"),
            parentDiv = document.getElementById("licence"),
            parentDiv_narrow = document.getElementById("licence_narrow"),
            parentDiv_narrower = document.getElementById("licence_narrower"),
            licence_count = document.getElementById('licence_count').val,
            licence_count_narrow = document.getElementById('licence_count_narrow').val,
            licence_count_narrower = document.getElementById('licence_count_narrower').val;

        span_element.appendChild(newContent);
        span_element_narrow.appendChild(newContent_narrow);
        span_element_narrower.appendChild(newContent_narrower);
        span_element.setAttribute("id", "child-span" + i + "");
        span_element_narrow.setAttribute("id", "child-span_narrow" + i + "");
        span_element_narrower.setAttribute("id", "child-span_narrower" + i + "");
        span_element.setAttribute("class", "licence_tag");
        span_element_narrow.setAttribute("class", "licence_tag_narrow");
        span_element_narrower.setAttribute("class", "licence_tag_narrower");
        span_element.setAttribute("style", "margin-right:4px;");
        span_element_narrow.setAttribute("style", "margin-right:4px;");
        span_element_narrower.setAttribute("style", "margin-right:4px;");
        div_element.setAttribute("id", "span" + i + "");
        div_element_narrow.setAttribute("id", "span_narrow" + i + "");
        div_element_narrower.setAttribute("id", "span_narrower" + i + "");
        i_element.setAttribute("class", "far fa-times-circle licence");
        i_element_narrow.setAttribute("class", "far fa-times-circle licence_narrow");
        i_element_narrower.setAttribute("class", "far fa-times-circle licence_narrower");
        input_element.setAttribute("type", "button");
        input_element_narrow.setAttribute("type", "button");
        input_element_narrower.setAttribute("type", "button");

        // タグの改行があった場合
        if (0 < document.getElementById('licence_count').val || 0 < document.getElementById('licence_count_narrow').val || 0 < document.getElementById('licence_count_narrower').val) {
            i--;
            var licences = new Array();
            var licences_narrow = new Array();
            var licences_narrower = new Array();

            // 改行した列で再度文字数取得
            for (k = 0; k < licence_count; k++) {
                licences[k] = spans[i].textContent;
                i--;
            }

            // 改行した列で再度文字数取得
            for (k = 0; k < licence_count_narrow; k++) {
                licences_narrow[k] = spans_narrow[i].textContent;
                i--;
            }
            // 改行した列で再度文字数取得
            for (k = 0; k < licence_count_narrower; k++) {
                licences_narrower[k] = spans_narrower[i].textContent;
                i--;
            }
            spans = '';
            spans_narrow = '';
            spans_narrower = '';
            licences = licences.join('');
            licences_narrow = licences_narrow.join('');
            licences_narrower = licences_narrower.join('');

            // licence_countの値で改行後のタグ数を決める
            switch (licence_count) {
                case 2:
                    i += 1;
                    spans = '@@';
                    break;

                case 3:
                    i += 2;
                    spans = '@@@';
                    break;
                case 4:
                    i += 3;
                    spans = '@@@@';
                    break;

                case 5:
                    i += 4;
                    spans = '@@@@@';
                    break;
                default:
            }

            // licence_countの値で改行後のタグ数を決める
            switch (licence_count_narrow) {
                case 2:
                    i += 1;
                    spans_narrow = '@@';
                    break;

                case 3:
                    i += 2;
                    spans_narrow = '@@@';
                    break;
                case 4:
                    i += 3;
                    spans_narrow = '@@@@';
                    break;

                case 5:
                    i += 4;
                    spans_narrow = '@@@@@';
                    break;
                default:
            }

            // licence_countの値で改行後のタグ数を決める
            switch (licence_count_narrower) {
                case 2:
                    i += 1;
                    spans_narrower = '@@';
                    break;

                case 3:
                    i += 2;
                    spans_narrower = '@@@';
                    break;
                case 4:
                    i += 3;
                    spans_narrower = '@@@@';
                    break;

                case 5:
                    i += 4;
                    spans_narrower = '@@@@@';
                    break;
                default:
            }

            i++;
            document.getElementById('licence_count').val += 1;
            document.getElementById('licence_count_narrow').val += 1;
            document.getElementById('licence_count_narrower').val += 1;
        }

        // タグ数が３つ以上または、タグの文字数が９文字以上は改行
        if ((3 <= spans.length || 9 <= licences.length) || (3 <= spans_narrow.length || 9 <= licences_narrow.length) || (3 <= spans_narrower.length || 9 <= licences_narrower.length)) {　　
            i--;
            if (document.getElementById('child-span' + i + '') !== null || document.getElementById('child-span_narrow' + i + '') !== null || document.getElementById('child-span_narrower' + i + '') !== null) {
                parentDiv.appendChild(div_element, document.getElementById('child-span' + i + ''));
                parentDiv_narrow.appendChild(div_element_narrow, document.getElementById('child-span_narrow' + i + ''));
                parentDiv_narrower.appendChild(div_element_narrower, document.getElementById('child-span_narrower' + i + ''));
            }
            i++;

            document.getElementById('licence_count').val = 1;
            document.getElementById('licence_count_narrow').val = 1;
            document.getElementById('licence_count_narrower').val = 1;
        }
        i++;

        parentDiv.appendChild(span_element, parentDiv.firstChild);
        parentDiv_narrow.appendChild(span_element_narrow, parentDiv_narrow.firstChild);
        parentDiv_narrower.appendChild(span_element_narrower, parentDiv_narrower.firstChild);
        span_element.appendChild(label_element, span_element.firstChild);
        span_element_narrow.appendChild(label_element_narrow, span_element_narrow.firstChild);
        span_element_narrower.appendChild(label_element_narrower, span_element_narrower.firstChild);
        label_element.insertBefore(i_element, label_element.firstChild);
        label_element_narrow.insertBefore(i_element_narrow, label_element_narrow.firstChild);
        label_element_narrower.insertBefore(i_element_narrower, label_element_narrower.firstChild);
        label_element.insertBefore(input_element, label_element.firstChild);
        label_element_narrow.insertBefore(input_element_narrow, label_element_narrow.firstChild);
        label_element_narrower.insertBefore(input_element_narrower, label_element_narrower.firstChild);

        $(this).val('');
    }
}

$(document).on('click', '.far.fa-times-circle.licence', function() {
    var k = 0,
        licences = new Array(),
        licence = document.getElementById("licence"),
        spans = licence.getElementsByTagName("span"),
        span = $(this).parents(".licence_tag")[0].textContent;

    // skill_countの値を元に最終行のタグ情報を取得
    switch (document.getElementById('licence_count').val) {
        case 1:
            var spans_count = spans.length - 1;
            break;

        case 2:
            var spans_count = spans.length - 2;
            break;

        case 3:
            var spans_count = spans.length - 3;
            break;

        default:
    }

    for (i = spans_count; i < spans.length; i++) {
        licences[k] = spans[i].textContent;
        k++;
    }

    $(this).parents(".licence_tag").remove();
    console.log("test");

    licences = licences.join('');

    if (licences.indexOf(span) != -1) {
        document.getElementById('licence_count').val -= 1;
    }
});


$(document).on('click', '.far.fa-times-circle.licence_narrow', function() {
    var k = 0,
        licences_narrow = new Array(),
        licence_narrow = document.getElementById("licence_narrow"),
        spans_narrow = licence_narrow.getElementsByTagName("span"),
        span_narrow = $(this).parents(".licence_tag_narrow")[0].textContent;

    // skill_countの値を元に最終行のタグ情報を取得
    switch (document.getElementById('licence_count_narrow').val) {
        case 1:
            var spans_count_narrow = spans_narrow.length - 1;
            break;

        case 2:
            var spans_count_narrow = spans_narrow.length - 2;
            break;

        case 3:
            var spans_count_narrow = spans_narrow.length - 3;
            break;

        default:
    }

    for (i = spans_count_narrow; i < spans_narrow.length; i++) {
        licences_narrow[k] = spans_narrow[i].textContent;
        k++;
    }

    $(this).parents(".licence_tag_narrow").remove();

    licences_narrow = licences_narrow.join('');

    if (licences_narrow.indexOf(span_narrow) != -1) {
        document.getElementById('licence_count_narrow').val -= 1;
    }
});


$(document).on('click', '.far.fa-times-circle.licence_narrower', function() {
    var k = 0,
        licences_narrower = new Array(),
        licence_narrower = document.getElementById("licence_narrower"),
        spans_narrower = licence_narrower.getElementsByTagName("span"),
        span_narrower = $(this).parents(".licence_tag_narrower")[0].textContent;

    // skill_countの値を元に最終行のタグ情報を取得
    switch (document.getElementById('licence_count_narrower').val) {
        case 1:
            var spans_count_narrower = spans_narrower.length - 1;
            break;

        case 2:
            var spans_count_narrower = spans_narrower.length - 2;
            break;

        case 3:
            var spans_count_narrower = spans_narrower.length - 3;
            break;

        default:

            for (i = spans_count_narrower; i < spans_narrower.length; i++) {
                licences_narrower[k] = spans_narrower[i].textContent;
                k++;
            }
    }
    $(this).parents(".licence_tag_narrower").remove();

    licences_narrower = licences_narrower.join('');

    if (licences_narrower.indexOf(span_narrower) != -1) {
        document.getElementById('licence_count_narrower').val -= 1;
    }
});


$(document).on('click', '.edit_done', function() {
    var licence = document.getElementById("licence"),
        licence_narrow = document.getElementById("licence_narrow"),
        licence_narrower = document.getElementById("licence_narrower"),
        licence_div = document.getElementById("licences"),
        licence_div_narrow = document.getElementById("licences_narrow"),
        licence_div_narrower = document.getElementById("licences_narrower"),
        spans = licence.getElementsByTagName("span"),
        spans_narrow = licence_narrow.getElementsByTagName("span"),
        spans_narrower = licence_narrower.getElementsByTagName("span"),
        licences = new Array(),
        licences_narrow = new Array(),
        licences_narrower = new Array();
    document.getElementById('licence_count').val = 4;
    document.getElementById('licence_count_narrow').val = 4;
    document.getElementById('licence_count_narrower').val = 4;

    for (i = 0; i < spans.length; i++) {
        licences[i] = spans[i].textContent;
    }
    for (i = 0; i < spans_narrow.length; i++) {
        licences_narrow[i] = spans_narrow[i].textContent;
    }
    for (i = 0; i < spans_narrower.length; i++) {
        licences_narrower[i] = spans_narrower[i].textContent;
    }
    licences = licences.join(' ');
    licences_narrow = licences_narrow.join(' ');
    licences_narrower = licences_narrower.join(' ');
    licence_div.value = licences;
    licence_div_narrow.value = licences_narrow;
    licence_div_narrower.value = licences_narrower;

    //$('.workhistory').val() = $('.edit_workhistory').val;
});

//================================
// フラッシュメッセージ処理
//================================

// フラッシュメッセージを表示させる
$(function() {
    $message = ('.flash_message');
    setTimeout(function() { $($message).slideToggle('slow'); }, 2000);
});

//================================
// その他
//================================

// 省略されている投稿の高さを取得
$(document).on('click', '.show_all', function() {
    $(document).find('.post_text').removeClass('ellipsis');
    $(this).remove();
});

// メッセージリンク押下時の処理
$(document).on('click', "#message_link", function() {
    $('#message_count').fadeOut();
});

$(document).on('focus', '.textarea', function() {
    $('#post').prop('disabled', false);
});

$(document).on('focus', '.textarea', function() {
    $('#post_btn').prop('disabled', false);
});

// 省略されているスレッドの表示
$(document).on('click', '.thread_btn', function() {
    var $target_modal = $(this).data("target"),
        omit_height = $(this).parent().height();
    scroll_position = $(window).scrollTop();
    $(this).remove();
    $($target_modal).fadeIn();
    $(this).parent().height(omit_height);
});

$(document).on('click', '#next', function() {
    $('.my_post').fadeIn();
    $('.woman').fadeOut();
});

$(document).on('click', '#before', function() {
    $('.my_post').fadeOut();
    $('.woman').fadeIn();
});

// 各種ツールチップ処理
$('[data-toggle="favorite"]').tooltip();
$('[data-toggle="post"]').tooltip();
$('[data-toggle="edit"]').tooltip();
$('[data-toggle="delete"]').tooltip();
$('[data-toggle="reply"]').tooltip();