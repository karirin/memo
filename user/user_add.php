<?php
require_once('../config_1.php');

?>

<body>
    <div class="row">
        <div class="col-6 offset-3 center">
            <h2>新規登録</h2>
            <form method="post" action="user_add_check.php" enctype="multipart/form-data">
                <input type="text" name="name" class="user_name_input" placeholder="ユーザー名">
                <input type="password" name="pass" class="user_pass_input" placeholder="パスワード">
                <input type="password" name="pass2" class="user_pass_input" placeholder="パスワードを再度入力してください">
                <div class="image_select">プロフィール画像を選んでください。</div>
                <div class="post_btn margin_top">
                    <label>
                        <i class="far fa-image"></i>

                        <input type="file" name="image" id="my_image" accept="image/*" multiple>
                    </label>
                </div>
                <p class="preview_img"><img class="my_preview"></p>
                <input type="button" id="my_clear" value="ファイルをクリアする">
                <div class="flex_btn margin_top">


                    <input class="btn btn-outline-dark" type="submit" value="登録">
                    <input class="btn btn-outline-info" type="button" onclick="history.back()" value="戻る">
                </div>
            </form>
</body>
<?php require_once('../footer.php'); ?>

</html>