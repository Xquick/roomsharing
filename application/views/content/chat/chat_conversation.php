<div class="messages-conversation-header" data-user-id="<?php
echo $conversationInfo[0]->user_id_pk;
?>" data-conversation-id="<?php
echo $conversationInfo[0]->conversation_id_fk;
?>">
    <div class="availability-state"></div>
    <?php echo $conversationInfo[0]->firstname . ' ' . $conversationInfo[0]->lastname; ?>
</div>


<div class="messages-conversation" data-user-id="<?php
echo $conversationInfo[0]->user_id_pk;
?>" data-conversation-id="<?php
echo $conversationInfo[0]->conversation_id_fk;
?>">
    <div class=" scrollable">
        <?php
        if (!empty($messages)) {
            $messages = array_reverse($messages);
            foreach ($messages as $message): ?>
                <div class="conversation-item">
                    <a href="/user/<?php echo $message->user_id_fk; ?>">
                        <div class="user-photo-mini">
                            <?php
                            $userData['fb_id'] = $message->fb_id;
                            $userData['user_id'] = $message->user_id_fk;
                            $this->load->view('content/user_miniature', $userData);
                            ?>
                        </div>
                    </a>
                    <a href="/user/<?php echo $message->user_id_fk; ?>">
                        <div class="conversation-user-name">
                            <?php echo $message->firstname . ' ' . $message->lastname; ?>
                        </div>
                    </a>

                    <div class="conversation-reply">
                        <?php echo $message->reply; ?>
                    </div>
                    <div class="conversation-time">
                        <?php echo $message->time; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php
        } else {
            ?>
            <div class="big-text no-messages">
                <? echo lang("no_messages") ?>
            </div>
        <?php
        } ?>
    </div>
</div>
