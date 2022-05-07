<?php
require_once('../config_1.php');

$page_type = $_GET['type'];
if (isset($_GET['page_id']) && $_GET['page_id'] != 'current_user') {
    $user = new User($_GET['page_id']);
    $current_user = $user->get_user();
} else {
    $user = new User($_SESSION['user_id']);
    $current_user = $user->get_user();
}

$favorite_count = $user->get_user_count('favorite');
$follow_count = $user->get_user_count('follow');
$follower_count = $user->get_user_count('follower');
?>

<body>

    <form method="post" action="../ajax_edit_profile.php" enctype="multipart/form-data">
        <div class="row wide_disp">
            <div class="myprofile col-6">
                <div class="profile">

                    <div class="detail_profile">
                        <div class="edit_profile_img">
                            <label>
                                <div class="fa-image_range">
                                    <i class="far fa-image"></i>
                                </div>
                                <input type="file" name="image_name" id="edit_profile_img" accept="image/*" multiple>
                            </label>
                            <img name="profile_image" class="editing_profile_img" src="data:image/jpeg;base64,<?= $current_user['image']; ?>">
                            <label>
                                <i class=" far fa-times-circle profile_clear"></i>
                                <input type="button" id="profile_clear">
                            </label>
                        </div>
                        <div class="profile_detail">
                            <div class="profile_detail_user">
                                <img class="mypage" src="data:image/jpeg;base64,<?= $current_user['image']; ?>">
                                <h2 class="profile_name"><?= $current_user['name'] ?></h2>
                                <p class="comment"><?= $current_user['profile'] ?></p>
                            </div>
                            <input type="hidden" name="id" class="user_id" value="<?= $current_user['id'] ?>">
                            <input type="file" name="image" class="image" value="<?= $current_user['image'] ?>" style="display:none;">
                            <div class="btn_flex">
                                <input type="submit" class="btn btn-outline-dark edit_done" value="編集完了">
                                <button class="btn btn-outline-info modal_close" type="button">キャンセル</button>
                            </div>
                        </div>
                        <div class="myprofile_btn">
                            <?php if ($current_user['id'] == $_SESSION['user_id']) : ?>
                                <button class="btn btn btn-outline-dark edit_btn" type="button" name="follow">プロフィール編集</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $skills = explode(" ", $current_user['skill']);
            $skills_len = "";
            $skills_delspace = str_replace("     ", "", $current_user['skill']);
            $licences = explode(" ", $current_user['licence']);
            $licences_len = "";
            $licencs_delspace = str_replace(" ", "", $current_user['licence']);
            ?>
            <div class="tag col-6">
                <div class="tags">
                    <div class="tag_skill">
                        <p class="tag_tittle">スキル</p>
                        <?php
                        foreach ($skills as $skill) :
                            if ($current_user['skill'] != '' && $skill != '') : ?>
                                <span id="child-span" class="skill_tag"><?= $skill ?></span>
                        <?php
                                if (!isset($skill_tag)) {
                                    $skill_tag = array();
                                }
                                array_push($skill_tag, $skill);
                                $skills_len .= $skill;

                                if (3 <= count($skill_tag) || 9 <= strlen($skills_len)) {
                                    print '<div></div>';
                                    $skill_tag = array();
                                    $skills_len = "";
                                }
                            endif;

                        endforeach;
                        ?>
                    </div>
                    <div class="tag_licence">
                        <p class="tag_tittle">取得資格</p>
                        <?php
                        foreach ($licences as $licence) :
                            if ($current_user['licence'] != '' && $licence != '') : ?>
                                <span id="child-span" class="licence_tag"><?= $licence ?></span>
                        <?php
                                if (!isset($licence_tag)) {
                                    $licence_tag = array();
                                }
                                array_push($licence_tag, $licence);
                                $licences_len .= $licence;

                                if (2 <= count($licence_tag) || 9 <= strlen($licences_len)) {
                                    print '<div></div>';
                                    $licence_tag = array();
                                    $licence_len = "";
                                }
                            endif;

                        endforeach;
                        ?>
                    </div>
                </div>
                <div class="background">
                    <p class="tag_tittle">職歴</p>
                    <p class="user_workhistory"><?= $current_user['workhistory'] ?></p>
                </div>
            </div>
            <div class="form">
                <div id="skill">
                    <p class="tag_tittle">スキル</p>
                    <?php
                    foreach ($skills as $skill) :
                        if ($current_user['skill'] != '' && $skill != '') : ?>
                            <span id="child-span" class="skill_tag"><?= $skill ?><label><input type="button"><i class="far  fa-times-circle skill"></i></label></span>
                    <?php
                            if (!isset($skill_tag)) {
                                $skill_tag = array();
                            }
                            array_push($skill_tag, $skill);
                            $skills_len .= $skill;

                            if (3 <= count($skill_tag) || 9 <= strlen($skills_len)) {
                                print '<div></div>';
                                $skill_tag = array();
                                $skills_len = "";
                            }
                        endif;

                    endforeach;
                    ?>
                </div>

                <input placeholder="skill Stack" name="name" id="skill_input" />
                <input type="hidden" name="skills" id="skills">
                <input type="hidden" name="skill_count" id="skill_count">
                <input type="hidden" name="myskills" value="<?= $current_user['skill'] ?>">
                <div id="licence">
                    <p class="tag_tittle" style="margin-top: 1rem;">取得資格</p>
                    <?php
                    foreach ($licences as $licence) :
                        if ($current_user['licence'] != '' && $licence != '') : ?>
                            <span id="child-span" class="licence_tag"><?= $licence ?><label><input type="button"><i class="far fa-times-circle licence"></i></label></span>
                    <?php
                            if (!isset($licence_tag)) {
                                $licence_tag = array();
                            }
                            array_push($licence_tag, $licence);
                            $licences_len .= $licence;

                            if (2 <= count($licence_tag) || 9 <= strlen($licences_len)) {
                                print '<div></div>';
                                $licence_tag = array();
                                $licence_len = "";
                            }
                        endif;

                    endforeach;
                    ?>


                </div>
                <input placeholder="licence Stack" name="name" id="licence_input" />
                <input type="hidden" name="licences" id="licences">
                <input type="hidden" name="licence_count" id="licence_count">
                <input type="hidden" name="mylicences" value="<?= $current_user['licence'] ?>">
                <div class="background">
                    <p class="tag_tittle">職歴</p>
                    <p class="workhistory"><?= $current_user['workhistory'] ?></p>
                </div>
            </div>
        </div>
    </form>
    </div>
    </div>
    </div>

    <form method="post" action="../ajax_edit_profile.php" enctype="multipart/form-data">
        <div class="row narrow_disp">
            <div class="myprofile col-6">
                <div class="profile">

                    <div class="detail_profile">
                        <div class="edit_profile_img">
                            <label>
                                <div class="fa-image_range">
                                    <i class="far fa-image"></i>
                                </div>
                                <input type="file" name="image_name" id="edit_profile_img_narrow" accept="image/*" multiple>
                            </label>
                            <img name="profile_image" class="editing_profile_img" src="data:image/jpeg;base64,<?= $current_user['image']; ?>">
                            <label>
                                <i class="far fa-times-circle profile_clear"></i>
                                <input type="button" id="profile_clear">
                            </label>
                        </div>
                        <div class="profile_detail">
                            <div class="profile_detail_user">
                                <img src="data:image/jpeg;base64,<?= $current_user['image']; ?>" class="mypage">
                                <h2 class="profile_name_narrow"><?= $current_user['name'] ?></h2>
                                <p class="comment_narrow"><?= $current_user['profile'] ?></p>
                            </div>
                            <input type="hidden" name="id" class="user_id" value="<?= $current_user['id'] ?>">
                            <input type="file" name="image" class="image" value="<?= $current_user['image'] ?>" style="display:none;">
                            <div class="btn_flex">
                                <input type="submit" class="btn btn-outline-dark edit_done" value="編集完了">
                                <button class="btn btn-outline-info modal_close" type="button">キャンセル</button>
                            </div>
                            <div class="myprofile_btn">
                                <?php if ($current_user['id'] == $_SESSION['user_id']) : ?>
                                    <button class="btn btn btn-outline-dark edit_btn" type="button" name="follow">プロフィール編集</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tag col-6">
                <div class="tags">
                    <div class="tag_skill">
                        <p class="tag_tittle">スキル</p>
                        <?php
                        foreach ($skills as $skill) :
                            if ($current_user['skill'] != '' && $skill != '') : ?>
                                <span id="child-span_narrow" class="skill_tag"><?= $skill ?></span>
                        <?php
                                if (!isset($skill_tag)) {
                                    $skill_tag = array();
                                }
                                array_push($skill_tag, $skill);
                                $skills_len .= $skill;

                                if (3 <= count($skill_tag) || 9 <= strlen($skills_len)) {
                                    print '<div></div>';
                                    $skill_tag = array();
                                    $skills_len = "";
                                }
                            endif;

                        endforeach;
                        ?>
                    </div>
                    <div class="tag_licence_narrow">
                        <p class="tag_tittle">取得資格</p>
                        <?php
                        foreach ($licences as $licence) :
                            if ($current_user['licence'] != '' && $licence != '') : ?>
                                <span id="child-span_narrow" class="licence_tag_narrow"><?= $licence ?></span>
                        <?php
                                if (!isset($licence_tag)) {
                                    $licence_tag = array();
                                }
                                array_push($licence_tag, $licence);
                                $licences_len .= $licence;

                                if (2 <= count($licence_tag) || 9 <= strlen($licences_len)) {
                                    print '<div></div>';
                                    $licence_tag = array();
                                    $licence_len = "";
                                }
                            endif;

                        endforeach;
                        ?>
                    </div>
                </div>
                <div class="background">
                    <p class="tag_tittle">職歴</p>
                    <p class="user_workhistory"><?= $current_user['workhistory'] ?></p>
                </div>
            </div>
            <div class="form">
                <div id="skill_narrow">
                    <p class="tag_tittle">スキル</p>
                    <?php
                    foreach ($skills as $skill) :
                        if ($current_user['skill'] != '' && $skill != '') : ?>
                            <span id="child-span_narrow" class="skill_tag"><?= $skill ?><label><input type="button"><i class="far  fa-times-circle skill"></i></label></span>
                    <?php
                            if (!isset($skill_tag)) {
                                $skill_tag = array();
                            }
                            array_push($skill_tag, $skill);
                            $skills_len .= $skill;

                            if (3 <= count($skill_tag) || 9 <= strlen($skills_len)) {
                                print '<div></div>';
                                $skill_tag = array();
                                $skills_len = "";
                            }
                        endif;

                    endforeach;
                    ?>
                </div>

                <input placeholder="skill Stack" name="name" id="skill_input_narrow" />
                <input type="hidden" name="skills" id="skills_narrow">
                <input type="hidden" name="skill_count" id="skill_count_narrow">
                <input type="hidden" name="myskills" value="<?= $current_user['skill'] ?>">
                <div id="licence_narrow">
                    <p class="tag_tittle" style="margin-top: 1rem;">取得資格</p>
                    <?php
                    foreach ($licences as $licence) :
                        if ($current_user['licence'] != '' && $licence != '') : ?>
                            <span id="child-span_narrow" class="licence_tag"><?= $licence ?><label><input type="button"><i class="far fa-times-circle licence"></i></label></span>
                    <?php
                            if (!isset($licence_tag)) {
                                $licence_tag = array();
                            }
                            array_push($licence_tag, $licence);
                            $licences_len .= $licence;

                            if (2 <= count($licence_tag) || 9 <= strlen($licences_len)) {
                                print '<div></div>';
                                $licence_tag = array();
                                $licence_len = "";
                            }
                        endif;

                    endforeach;
                    ?>


                </div>
                <input placeholder="licence Stack" name="name" id="licence_input_narrow" />
                <input type="hidden" name="licences" id="licences_narrow">
                <input type="hidden" name="licence_count" id="licence_count_narrow">
                <input type="hidden" name="mylicences" value="<?= $current_user['licence'] ?>">
                <div class="background">
                    <p class="tag_tittle">職歴</p>
                    <p class="workhistory_narrow"><?= $current_user['workhistory'] ?></p>
                </div>
            </div>
        </div>
    </form>

    <form method="post" action="../ajax_edit_profile.php" enctype="multipart/form-data">
        <divaa class="row narrower_disp">
            <div class="myprofile col-5">
                <div class="profile">
                    <div class="detail_profile">
                        <div class="edit_profile_img">
                            <label>
                                <div class="fa-image_range">
                                    <i class="far fa-image"></i>
                                </div>
                                <input type="file" name="image_name" id="edit_profile_img_narrower" accept="image/*" multiple>
                            </label>
                            <img name="profile_image" class="editing_profile_img" src="data:image/jpeg;base64,<?= $current_user['image']; ?>">
                            <label>
                                <i class=" far fa-times-circle profile_clear"></i>
                                <input type="button" id="profile_clear">
                            </label>
                        </div>
                        <div class="profile_detail">
                            <div class="profile_detail_user">
                                <img src="data:image/jpeg;base64,<?= $current_user['image']; ?>" class="mypage">
                                <h2 class="profile_name_narrower"><?= $current_user['name'] ?></h2>
                                <p class="comment_narrower"><?= $current_user['profile'] ?></p>
                            </div>
                            <input type="hidden" name="id" class="user_id" value="<?= $current_user['id'] ?>">
                            <input type="file" name="image" class="image" value="<?= $current_user['image'] ?>" style="display:none;">
                            <div class="btn_flex">
                                <input type="submit" class="btn btn-outline-dark edit_done" value="編集完了">
                                <button class="btn btn-outline-info modal_close" type="button">キャンセル</button>
                            </div>
                        </div>
                        <div class="myprofile_btn">
                            <?php if ($current_user['id'] == $_SESSION['user_id']) : ?>
                                <button class="btn btn btn-outline-dark edit_btn" type="button" name="follow">プロフィール編集</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $skills = explode(" ", $current_user['skill']);
            $skills_len = "";
            $skills_delspace = str_replace("     ", "", $current_user['skill']);
            $licences = explode(" ", $current_user['licence']);
            $licences_len = "";
            $licencs_delspace = str_replace(" ", "", $current_user['licence']);
            ?>
            <div class="tag col-6">
                <div class="tags">
                    <div class="tag_skill">
                        <p class="tag_tittle">スキル</p>
                        <?php
                        foreach ($skills as $skill) :
                            if ($current_user['skill'] != '' && $skill != '') : ?>
                                <span id="child-span_narrower" class="skill_tag"><?= $skill ?></span>
                        <?php
                                if (!isset($skill_tag)) {
                                    $skill_tag = array();
                                }
                                array_push($skill_tag, $skill);
                                $skills_len .= $skill;

                                if (3 <= count($skill_tag) || 9 <= strlen($skills_len)) {
                                    print '<div></div>';
                                    $skill_tag = array();
                                    $skills_len = "";
                                }
                            endif;

                        endforeach;
                        ?>
                    </div>
                    <div class="tag_licence_narrower">
                        <p class="tag_tittle">取得資格</p>
                        <?php
                        foreach ($licences as $licence) :
                            if ($current_user['licence'] != '' && $licence != '') : ?>
                                <span id="child-span_narrower" class="licence_tag_narrower"><?= $licence ?></span>
                        <?php
                                if (!isset($licence_tag)) {
                                    $licence_tag = array();
                                }
                                array_push($licence_tag, $licence);
                                $licences_len .= $licence;

                                if (2 <= count($licence_tag) || 9 <= strlen($licences_len)) {
                                    print '<div></div>';
                                    $licence_tag = array();
                                    $licence_len = "";
                                }
                            endif;

                        endforeach;
                        ?>
                    </div>
                </div>
                <div class="background">
                    <p class="tag_tittle">職歴</p>
                    <p class="user_workhistory"><?= $current_user['workhistory'] ?></p>
                </div>
            </div>
            <div class="form">
                <div id="skill_narrower">
                    <p class="tag_tittle">スキル</p>
                    <?php
                    foreach ($skills as $skill) :
                        if ($current_user['skill'] != '' && $skill != '') : ?>
                            <span id="child-span_narrower" class="skill_tag"><?= $skill ?><label><input type="button"><i class="far  fa-times-circle skill"></i></label></span>
                    <?php
                            if (!isset($skill_tag)) {
                                $skill_tag = array();
                            }
                            array_push($skill_tag, $skill);
                            $skills_len .= $skill;

                            if (3 <= count($skill_tag) || 9 <= strlen($skills_len)) {
                                print '<div></div>';
                                $skill_tag = array();
                                $skills_len = "";
                            }
                        endif;

                    endforeach;
                    ?>
                </div>

                <input placeholder="skill Stack" name="name" id="skill_input_narrower" />
                <input type="hidden" name="skills" id="skills_narrower">
                <input type="hidden" name="skill_count" id="skill_count_narrower">
                <input type="hidden" name="myskills" value="<?= $current_user['skill'] ?>">
                <div id="licence_narrower">
                    <p class="tag_tittle" style="margin-top: 1rem;">取得資格</p>
                    <?php
                    foreach ($licences as $licence) :
                        if ($current_user['licence'] != '' && $licence != '') : ?>
                            <span id="child-span_narrower" class="licence_tag"><?= $licence ?><label><input type="button"><i class="far fa-times-circle licence"></i></label></span>
                    <?php
                            if (!isset($licence_tag)) {
                                $licence_tag = array();
                            }
                            array_push($licence_tag, $licence);
                            $licences_len .= $licence;

                            if (2 <= count($licence_tag) || 9 <= strlen($licences_len)) {
                                print '<div></div>';
                                $licence_tag = array();
                                $licence_len = "";
                            }
                        endif;

                    endforeach;
                    ?>


                </div>
                <input placeholder="licence Stack" name="name" id="licence_input_narrower" />
                <input type="hidden" name="licences" id="licences_narrower">
                <input type="hidden" name="licence_count" id="licence_count_narrower">
                <input type="hidden" name="mylicences" value="<?= $current_user['licence'] ?>">
                <div class="background">
                    <p class="tag_tittle">職歴</p>
                    <p class="workhistory_narrower"><?= $current_user['workhistory'] ?></p>
                </div>
            </div>
            </div>
    </form>
    </div>
    </div>
    </div>

    <?php

    require_once('../footer.php'); ?>