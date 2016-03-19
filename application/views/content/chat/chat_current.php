<div class="current-chat-content">
    <div class="current-chat-<?php echo $conversation[0]->conversation_id_fk; ?> dark
    <?php
    $unreadMessage = 0;
    for ($i = 0; $i < sizeof($messageCount) - 1; $i++) {
        if ($messageCount[$i]->conversation_id_fk == $conversation[0]->conversation_id_fk) {
            echo 'unread-chat';
            $unreadMessage = $messageCount[$i]->unread_replies_count;
        }
    }
    ?>
    "
         data-id="<?php echo $conversation[0]->conversation_id_fk; ?>"
         data-user-id="<?php echo $conversation[0]->user_id_fk; ?>">

        <div class="current-chat-body">
            <div class="current-chat-body-inner scrollable">
                <div class="current-chat-scroll">
                    <?php foreach ($messages as $message): ?>
                        <?php
                        $data['message'] = $message->reply;
                        $data['userId'] = $message->user_id_fk;
                        $data['fbId'] = $message->fb_id;
                        $data['time'] = $message->time;
                        $data['currentUserId'] = $currentUserId;
                        $data['sending'] = $message->user_id_fk != $currentUserId;
                        $data['type'] = 1;
                        ?>
                        <?php $this->load->view('content/chat/chat_message', $data); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="current-chat-input-base">
            <div class="current-chat-input">
                <?php echo form_open('', array(
                    'data-id' => $conversation[0]->conversation_id_fk,
                    'data-user-id' => $conversation[0]->user_id_fk,
                    'class' => 'send-chat-message'));
                ?>
                <?php echo form_textarea(array('name' => 'find', 'autocomplete' => 'off')); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>