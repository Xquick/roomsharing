<div class="post" data-id="<?php echo $post['ad_id_pk']; ?>" data-object-id="<?php echo $post['object_id_pk']; ?>">
    <span class="marker" data-lat="<?php echo $post['lat'] ?>" data-lng="<?php echo $post['lng'] ?>"></span>

    <div class="post-icons">
        <div class="post-icons-inner">
            <!--            <button class="button-wide-light" name="roommates">-->
            <!--                --><?php //echo lang("button_find_roommates"); ?>
            <!--            </button>-->
            <?php
            if (isset($isFollowed)) {
                if (!$isFollowed) {
                    ?>
                    <div class="post-icon-right icon-medium icon-follow">
                        <a href="user_c/follow/<?php echo $post['ad_id_pk']; ?>" class="ajax">
                        </a>
                    </div>
                <?php
                } else {
                    ?>
                    <div class="post-icon-right icon-medium icon-unfollow">
                        <a href="user_c/unfollow/<?php echo $post['ad_id_pk']; ?>" class="ajax">
                        </a>
                    </div>

                <?php }
            } ?>
        </div>
    </div>
    <div class="post-photo top-line">
        <div class="loader"></div>
        <div class="post-photo-inner">
            <img src="/galleries/<?php echo $post['object_id_pk']; ?>/1.jpg" alt=""/>

            <div class="more-images">

            </div>
        </div>
        <div class="icon-next"></div>
        <div class="icon-prev"></div>
        <div class="photo-menu">
            <a href="/discover/campaign/<?php echo $post['ad_id_pk']; ?>" class="ajax">
                <div class="grayout"></div>
            </a>
        </div>
        <div class="top-line"></div>
    </div>

    <div class="post-description">
        <!--    <div class="post-date">--><?php //echo $post->date_inserted; ?><!--</div>-->
        <div class="info-description">
            <div class="info-description-location">
               <span class="locality"> <?php
                   echo $post['sublocality_level_1'];
                   ?>  </span>
                <span class="route">
                    <?php
                    if (!empty($post['route']))
                        echo ' - ' . $post['route'];
                    ?>
                </span>
            </div>
            <div class="info-description-price">
                <?php echo $post['ad_price'] . ',-'; ?>
            </div>
        </div>
    </div>
</div>