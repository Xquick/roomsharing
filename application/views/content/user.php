<?php
//var_dump($user_info);
?>

<div id="user" class="">
    <div class="fullWidthHeight">
        <div class="user-block">
            <div class="user-photo left overflow">
                <?php
                $data['fb_id'] = $user_info[0]->fb_id;
                $data['user_id'] = $user_info[0]->user_id_pk;
                $this->load->view('content/user_miniature', $data); ?>
            </div>

            <div class="user-info">
                <div class="user-type">
                    user
                </div>
                <div class="user-name">
                    <?php echo $user_info[0]->firstname; ?>
                    <?php echo $user_info[0]->lastname; ?>
                </div>
                <?php if (!$isCurrentUser): ?>
                    <div class="contact-user" data-user-id="<?php echo $user_info[0]->user_id_pk; ?>">
                        <?php echo form_button(array('name' => 'contact-user', 'content' => 'Kontaktovat uživatele', 'class' => 'button-wide right')); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <div id="user-follow" class="right">
            <div id="user-follow-followers">
                followers
                <div class="font-middle font-center"><?php echo $followersNum; ?></div>
            </div>
            <div id="user-follow-followed">
                following
                <div class="font-middle font-center"><?php echo $followedNum; ?></div>
            </div>
        </div>
        <div id="user-profile-menu">
            <?php if ($isCurrentUser) { ?>
                <a href="/user/info">
                    <div class="user-profile-menu-item <?php
                    if ($this->uri->segment(2) == 'info')
                        echo 'active';
                    ?>">
                        Info
                    </div>
                </a>
<!--                <a href="/user/groups">-->
                <!--                    <div class="user-profile-menu-item --><?php
//                    if ($this->uri->segment(2) == 'groups')
//                        echo 'active';
//                    ?><!--">-->
                <!--                        Skupiny-->
                <!--                    </div>-->
                <!--                </a>-->
            <?php
            } else {
                ?>
                <a href="#">
                    <div class="user-profile-menu-item">
                        Info
                    </div>
                </a>
            <?php
            } ?>
        </div>
    </div>
    <div class="top-line-light"></div>
</div>
<?php if ($isCurrentUser) { ?>
    <?php
    switch ($userContent) {
        case 'messages':
            break;
        case 'info':
            break;
        case 'groups':
            break;
    }
    ?>
<?php
} else {
    $this->load->view('content/user_info');
} ?>
