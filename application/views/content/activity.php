<div id="activity" class="scrollable">
    <?php
    foreach ($activity as $item): ?>
        <?php
        switch ($item->activity_type) {
            case ACTIVITY_COMMENT:
                $action = "komentoval(a)";
                break;
            case ACTIVITY_UNFOLLOW:
                $action = "přestal(a) sledovat";
                break;
            case ACTIVITY_CREATE:
                $action = "vytvořil(a)";
                break;
            case ACTIVITY_FOLLOW:
                $action = "začal(a) sledovat";
                break;
            case ACTIVITY_RATE:
                $action = "ohodnotil(a)";
                break;
            case ACTIVITY_ACTIVATE:
                $action = "aktivoval(a)";
                break;
            case ACTIVITY_DEACTIVATE:
                $action = "deaktivoval(a)";
                break;
        }
        ?>
        <div class="activity-item">
            <div class="activity-item-photo">
                <div class="user-photo-mini">
                    <a href="/user/<?php echo $item->user_id_pk; ?>">
                        <?php
                        $data['fb_id'] = $item->fb_id;
                        $data['user_id'] = $item->user_id_pk;
                        $this->load->view('content/user_miniature', $data); ?>
                    </a>
                </div>
            </div>
            <div class="activity-item-time"><?php echo $item->time_inserted; ?></div>
            <span class="activity-actor">
                <a href="/user/<?php echo $item->user_id_pk; ?>">
                    <?php echo $item->firstname . ' ' . $item->lastname; ?>
                </a>
            </span>
            <span class="activity-item-header"><?php echo $action; ?></span>

            <a href="campaign/<?php echo $item->ad_id_pk; ?>" class="ajax">
                <div class="activity-item-target"><?php echo $item->ad_title; ?></div>
            </a>
        </div>
        <div class="top-line"></div>
    <?php endforeach; ?>
</div>