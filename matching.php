<div class="modal_match"></div>
<div class="match_process" id="match<?= $user['id'] ?>">
    <h2 class="post_title"><?= $user['name'] ?>さんとマッチしました</h2>
    <img name="profile_image" class="matching_img" src="data:image/jpeg;base64,<?= $user['image']; ?>">
</div>
<i class="far fa-times-circle match_clear"></i>