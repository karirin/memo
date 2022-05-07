<?php
require('config_1.php');
$user = new User($_SESSION['user_id']);
$current_user = $user->get_user();
$img = $_FILES["image_name"];

$image = base64_encode(file_get_contents($_FILES['image_name']['tmp_name']));
_debug($image);

?>

<img class="mypage" src="data:image/jpeg;base64,<?= $image; ?>">
<?php
require('footer.php');
?>