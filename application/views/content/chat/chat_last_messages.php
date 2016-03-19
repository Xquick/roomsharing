<div id="last-messages">
    <?php
    if (isset($lastMessages)) {
        if (!empty($lastMessages)) {
            foreach ($lastMessages as $message): ?>
                <div class="last-messages-item <?php
                $unreadMessage = 0;
                for ($i = 0; $i < sizeof($messageCount) - 1; $i++) {
                    if ($messageCount[$i]->conversation_id_fk == $message->conversation_id_fk) {
                        echo 'unread-message';
                        $unreadMessage = $messageCount[$i]->unread_replies_count;
                    }
                }
                ?>" data-id="<?php echo $message->conversation_id_fk; ?>">
                    <div class="user-photo-mini">
                        <?php
                        $data['fb_id'] = $message->fb_id;
                        $data['user_id'] = $message->user_id_fk;
                        $this->load->view('content/user_miniature', $data); ?>
                    </div>
                    <div class="last-messages-user-name">
                        <?php echo $message->firstname . ' ' . $message->lastname; ?>
                    </div>
                    <div class="last-messages-item-body">
                        <?php echo $message->reply; ?>
                    </div>
                </div>
                <div class="top-line"></div>
            <?php endforeach;
        }
    } else {
        ?>
        <div class="no-users">
            Nemáte žádné předchozí konverzace
        </div>
    <?php
    }
    ?>
</div>