<?php if ($type == 1) { ?>
    <div class="conversation-item">
        <div class="user-photo-mini">
            <?php
            $data['fb_id'] = $fbId;
            $data['user_id'] = $userId;
            $this->load->view('content/user_miniature', $data);
            ?>
        </div>
        <div class="conversation-user-name">

            <?php
            if (isset($firstname) && isset($lastname))
                echo $firstname . ' ' . $lastname; ?>
        </div>

        <div class="conversation-reply">
            <?php echo $message; ?>
        </div>
        <div class="conversation-time">
            <?php echo $time; ?>
        </div>
    </div>
<?php } ?>
<?php if ($type == 2) { ?>
    <div class="message <? if ($sending) {
        echo 'host-message';
    } else {
        echo 'my-message';
    } ?>" title="<?php echo $time; ?>">
        <?php
        if ($sending) {
            ?>
            <div class="message-user">
                <div class="user-photo-mini">
                    <?php
                    $data['fb_id'] = $fbId;
                    $data['user_id'] = $userId;
                    $this->load->view('content/user_miniature', $data); ?>
                </div>
            </div>
        <?php } ?>

        <div class="message-content">
            <?php echo $message; ?>
        </div>
    </div>
<?php } ?>