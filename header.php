<?php require_once('class.php');
?>
<nav class="navbar navbar-dark">
    <div class="modal"></div>
    <?php if (isset($_SESSION['login']) == false) : ?>
    <ul class="main_ul">
        <li class="top_link"><a href="../user_login/user_top.php">Pair Code</a></li>
        <li><a href="../user_login/user_login.php">login</a></li>
        <li><a href="../user/user_add.php">sign up</a></li>
        <?php
    else :
        if (isset($_GET['page_id']) && $_GET['page_id'] != 'current_user') {
            $user = new User($_GET['page_id']);
            $current_user = $user->get_user();
        } else {
            $user = new User($_SESSION['user_id']);
            $current_user = $user->get_user();
        }
        ?>
        <ul class="main_ul">
            <li class="top_link"><a href="../user_login/user_top.php?type=main&page_id=current_user">Pair Code</a></li>
            <li class="header_menu memo_modal"><a href="#">memo</a></li>
            <li class="header_menu"><a href="../memo/memo_index.php?type=mymemo">memolist</a></li>
            <li class="header_menu_wide"><a href="../user_login/user_logout.php">logout</a></li>
            <li class="header_menu_wide"><a class="withdraw" href="#">withdrawal</a></li>
            <li class="show_menu">menu
                <div class="slide_menu">
                    <a class="modal_close" href="#">
                        <p><i class="fas fa-angle-left"></i></p>
                    </a>
                    <ul>
                        <a href="../user_login/user_logout.php">
                            <li>logout</li>
                        </a>
                        <a href="/withdraw.php">
                            <li>withdrawal</li>
                        </a>
                        <a href="#">
                            <li class="slide_menu_message">memo</li>
                        </a>
                        <a href="../memo/memo_index.php?type=mymemo">
                            <li class="slide_menu_message">memolist</li>
                        </a>
                    </ul>
                </div>
            </li>
        </ul>
        <?php
    endif;
        ?>
</nav>
<p class="flash">
    <?php
    if (isset($flash_messages)) :
        foreach ((array)$flash_messages as $message) :
            print '<span class="flash_message">' . $message . '</span>';
        endforeach;
    endif;
    ?>
</p>