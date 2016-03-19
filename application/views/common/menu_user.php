<div id="menu_user" class="top-line">
    <ul>

        <?php if ($logged) : ?>
            <li class="<?php if ($this->uri->segment(1) == "objects") {
                echo "active";
            } ?>"><a href="/objects"><img src="/images/icons/objects.png"
                                          alt="discover icon"/><br><?php echo lang("myObjects_header") ?></a></li>
            <li class="profile-picture"><a href="/user">
                    <div class="profile-picture-img-wrapper">
                        <?php
                        $data['fb_id'] = $_SESSION['fb_id'];
                        $data['user_id'] = $current_user_info[0]->user_id_pk;
                        $this->load->view('content/user_miniature', $data);
                        ?>
                    </div>
                    <?php echo $this->session->userdata('username'); ?></a></li>
            <li class="small-icon <?php if ($this->uri->segment(1) == "messages") {
                echo "active";
            } ?>">
                <a href="/user/messages" data-type="last_messages">
                    <img src="/images/icons/messages.png" alt="discover icon"/>

                    <div
                        class="messages-info-count notification <?php if ($messageCount[sizeof($messageCount) - 1]['total_count'] <= 0) echo "hidden"; ?>"><?php echo $messageCount[sizeof($messageCount) - 1]['total_count']; ?></div>
                </a></li>
            <li class="small-icon <?php if ($this->uri->segment(1) == "settings") {
                echo "active";
            } ?>"><a href="/settings"><img src="/images/icons/settings.png" alt="discover icon"/></a>
            </li>
        <?php endif; ?>


        <?php if (!$logged) : ?>
            <li class="<?php if ($this->uri->segment(1) == "login") {
                echo "active";
            } ?>"><a href="/login"><img src="/images/icons/login.png" alt="login icon"/><br>Login</a></li>
        <?php endif; ?>

    </ul>
</div>
