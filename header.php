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
            <li class="header_menu_wide"><a href="../user/user_list.php?type=all">userlist</a></li>
            <li class="header_menu"><a href="../message/message_top.php">
                    message<?php
                                $message = new Message();
                                if (current($user->get_user_count('message_relation')) != 0) {
                                    if (current($message->message_count(($user->id))) != 0) {
                                        print '' . current($message->message_count(($user->id))) . '';
                                    }
                                }
                                ?>
                </a></li>
            <li class="header_menu_wide"><a href="../match/match.php">matching</a></li>
            <li class="header_menu_wide memo_modal"><a href="#">memo</a></li>
            <li class="header_menu_wide"><a href="../user_login/user_logout.php">logout</a></li>
            <li class="header_menu_wide"><a class="withdraw" href="#">withdrawal</a></li>
            <li class="show_menu">menu
                <div class="slide_menu">
                    <a class="modal_close" href="#">
                        <p><i class="fas fa-angle-left"></i></p>
                    </a>
                    <ul>
                        <a href="../user/user_list.php?type=all">
                            <li>userlist</li>
                        </a>
                        <a href="../message/message_top.php">
                            <li class="slide_menu_message">
                                message<?php
                                            $message = new Message();
                                            if (current($user->get_user_count('message_relation')) != 0) {
                                                if (current($message->message_count(($user->id))) != 0) {
                                                    print '' . current($message->message_count(($user->id))) . '';
                                                }
                                            }
                                            ?>
                            </li>
                        </a>
                        <a href="../user_login/user_logout.php">
                            <li>logout</li>
                        </a>
                        <a href="/withdraw.php">
                            <li>withdrawal</li>
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