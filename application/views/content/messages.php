<!--<h2>Messages</h2>-->
<!---->
<!--<div>Input and output from other users</div>-->
<div id="header">
    <div class="header-text">
        <h2><?php echo lang("messages_header"); ?></h2>

        <div class="subheader"><?php echo lang("messages_subheader"); ?></div>
    </div>
</div>
<div id="messages">

    <div class="messages-list">
        <div class="recent-chat-lookup">
            <?php
            echo form_open('', array('id' => 'find-chat-people')); ?>
            <?php echo form_input(array('name' => 'find', 'placeholder' => 'začít chatovat s...', 'autocomplete' => 'off')); ?>
            <?php echo form_close();
            ?>
        </div>
        <div class=messages-list-content-wrapper>
            <div class="messages-list-content">
                <?php
                if (isset($conversations)) {
                foreach ($conversations as $conversation): ?>
                    <div class="message-item" data-id="<?php echo $conversation->conversation_id_fk; ?>">
                        <div class="message-item-name">
                            <a href="/site_c/getMessages/<?php echo $conversation->conversation_id_fk; ?>"
                               class="ajax folded">
                                <div class="user-photo-mini">
                                    <?php
                                    $data['fb_id'] = $conversation->fb_id;
                                    $data['user_id'] = $conversation->user_id_pk;
                                    $this->load->view('content/user_miniature', $data); ?>
                                </div>
                                <?php echo $conversation->firstname . ' ' . $conversation->lastname; ?>
                                <?php
                                if (isset($messageCount[$conversation->conversation_id_fk]['unread_replies_count'])) {
                                    echo '(' . $messageCount[$conversation->conversation_id_fk]['unread_replies_count'] . ')';
                                }
                                ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach;
                ?>
            </div>
        </div>
    </div>
    <div class="messages-conversation-wrapper">
        <div class="messages-conversation-inner"></div>
        <div class="messages-conversation-new-message">
            <?php echo form_open('', array(
                'data-id' => $conversation->conversation_id_fk,
                'data-user-id' => $conversation->user_id_fk,
                'class' => 'send-chat-message'));
            ?>
            <?php echo form_textarea(array('name' => 'find', 'autocomplete' => 'off')); ?>
            <?php echo form_close(); ?>
        </div>
        <?php } ?>
    </div>

</div>
