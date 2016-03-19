<div class="user-contact-card" data-userId="<?php echo $user->user_id_pk; ?>">
    <div class="loader"></div>
    <div class="chat-close">x</div>
    <header>
        <div class="user-photo-mini">
            <img src="/profiles/<?php echo $user->user_id_pk; ?>/1.jpg"/>
        </div>
        <div class="user-contact-right">
            <div class="user-name">
                <?php echo $user->firstname . " " . $user->lastname; ?>
                <div class="availability-state"></div>
            </div>
            <span class="user-relation">Majitel</span>
                    <span class="user-email">
                        <?php echo $user->email; ?>
                    </span>
        </div>
    </header>
    <div class="current-chat"></div>
</div>