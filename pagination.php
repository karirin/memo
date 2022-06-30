<?php
if (isset($block[1])) :
    $block_count = count($block);
?>

<form method="post" action="#">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center pagination-lg">
            <li class="page-item">
                <?php if ($_SESSION[$n] == 0) : ?>
                <input class="page-link first" name="block" type="submit" value="&laquo;" disabled>
                <?php else : ?>
                <input class="page-link" name="block" type="submit" value="&laquo;">
                <?php endif; ?>
            </li>

            <?php for ($l = 1; $l < $block_count + 1; $l++) {
                    print '<li class="page-item"><input class="page-link" name="block" type="submit" value="' . $l . '"></li>';
                } ?>
            <li class="page-item">
            <li class="page-item">
                <?php if ($_SESSION[$n] == $block_count - 1) : ?>
                <input class="page-link last" name="block" type="submit" value="&raquo;" disabled>
                <?php else : ?>
                <input class="page-link" name="block" type="submit" value="&raquo;">
                <?php endif; ?>
            </li>
            </li>
        </ul>
    </nav>
</form>
<?php endif; ?>