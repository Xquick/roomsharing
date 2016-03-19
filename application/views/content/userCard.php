<div class="post <?php if ($user->answer == 1) {
    echo 'paired';
} else {
    echo 'pending';
} ?>" data-id="<?php echo $user->user_id_pk; ?>">
    <div class="loader"></div>
    <div class="post-icons">
        <div class="really card-info">
            <span class="card-info-text left"><?php echo lang("really_delete"); ?></span>
            <span class="icon-small icon-dislike right"></span>
            <span class="icon-small icon-like right"></span>

        </div>
        <div class="post-icons-inner">

            <?php
            if ($user->student == 1) {
                echo '<div class=icon-small icon-student data-title=student></div>';
            }
            if ($user->answer == 1) {
                echo '<div class="icon-small icon-paired"></div>';
            } else {
                echo '<div class="icon-small icon-pending"></div>';
            } ?>
            <div class="icon-small icon-remove right">
            </div>
        </div>
    </div>
    <div class="post-photo user-photo top-line">
        <div class=user-photo-inner>
            <img src="<?php
            if ($user->fb_id > 0) {
                echo 'http://graph.facebook.com/' . $user->fb_id . '/picture?type=large';
            } else {
                echo '/profiles/' . $user->user_id_pk . '/1.jpg';?>
                    <?php } ?>
"/>
        </div>
        <div class="top-line"></div>
    </div>
    <div class="post-description">
        <div class="info-description">
            <div class="info-description-name">
                <?php echo $user->firstname . ' ' . $user->lastname; ?>
            </div>
        </div>
    </div>
</div>