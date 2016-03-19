<div id="chat" class="resizableHeight overflow">
    <div class="recent-chat">
        <div class="recent-chat-header">
            <div>Vaši uživatelé</div>
        </div>
        <div class="recent-chat-lookup">
            <?php echo form_open('', array('id' => 'find-chat-people')); ?>
            <?php echo form_input(array('name' => 'find', 'placeholder' => 'začít chatovat s...', 'autocomplete' => 'off')); ?>
            <?php echo form_close(); ?>
        </div>
        <div class="recent-chat-body">
            <div class="recent-chat-body-inner">
                <ul>
                    <?php
                    if (!empty($conversations)) {
                        foreach ($conversations as $conversation): ?>
                            <li>
                                <div class="recent-user <?php
                                $unreadMessage = 0;
                                for ($i = 0; $i < sizeof($messageCount) - 1; $i++) {
                                    if ($messageCount[$i]->conversation_id_fk == $conversation->conversation_id_fk) {
                                        echo 'unread-message';
                                        $unreadMessage = $messageCount[$i]->unread_replies_count;
                                    }
                                }
                                ?>"
                                     data-id="<?php echo $conversation->conversation_id_fk; ?>"
                                     data-user-id="<?php echo $conversation->user_id_fk; ?>">
                                    <div class="user-photo-mini">
                                        <?php
                                        $data['fb_id'] = $conversation->fb_id;
                                        $data['user_id'] = $conversation->user_id_fk;
                                        $this->load->view('content/user_miniature', $data); ?>
                                    </div>
                                    <div class="recent-user-info">
                                        <?php echo $conversation->firstname . ' ' . $conversation->lastname; ?>
                                    </div>
                                    <div class="recent-user-new-messages">
                                        <?php if ($unreadMessage > 0) {
                                            echo $unreadMessage;
                                        }
                                        ?>
                                    </div>
                                    <div class="recent-user-availability">
                                        <div class="availability-state"></div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach;
                    } else {
                        ?>
                        <div class="no-users">
                            Nemáte žádné kontakty
                        </div>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>