<div id="menu-side">
    <ul>
        <li class="<?php if ($this->uri->segment(1) == "discover") {
            echo "active";
        } ?>"><a href="/discover">
                <img src="/images/icons/discover.png"
                     alt="discover icon"/><br><span><?php echo lang("discover_header"); ?></span></a>
        </li>

        <?php if ($logged): ?>
            <li class="user-followed <?php if ($this->uri->segment(1) == "followed") {
                echo "active";
            } ?>"><a href="/followed">
                    <img src="/images/icons/followedAll.png"
                         alt="follow icon"/><br><span><?php echo lang("followed_header"); ?></span></a>
            </li>

            <li class="roommates <?php if ($this->uri->segment(1) == "roommates") {
                echo "active";
            } ?>"><a href="/roommates">
                    <img src="/images/icons/roommates.png"
                         alt="follow icon"/><br><span><?php echo lang("roommates_header"); ?></span></a>

<!--                <div class="notification">5</div>-->
            </li>

            <li class="suggested <?php if ($this->uri->segment(1) == "suggested") {
                echo "active";
            } ?>"><a href="/suggested">
                    <img src="/images/icons/pin.png"
                         alt="follow icon"/><br><span><?php echo lang("suggested_header"); ?></span></a>

<!--                <div class="notification">1</div>-->
            </li>
        <?php endif; ?>
    </ul>
</div>