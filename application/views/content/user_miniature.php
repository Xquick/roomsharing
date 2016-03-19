<img src="<?php
if (isset($fb_id) && $fb_id > 0) {
    echo 'http://graph.facebook.com/' . $fb_id . '/picture?type=large';
} else {
    echo '/profiles/' . $user_id . '/1.jpg';
} ?>
"/>