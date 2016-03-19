<div id="roommates-new" class="right-window">
    <div class="loader"></div>
    <?php $this->load->view("components/button_close.php"); ?>

    <div id="header">
        <div class="header-text">
            <h2><?php echo lang("roommates_header"); ?></h2>

            <div class="subheader"><?php echo lang("roommates_subheader"); ?></div>
        </div>
    </div>
    <?php  if (isset($followers) && !empty($followers)) {
        $counter = 0;
        foreach ($followers as $roommate):
            $counter++;
            ?>
            <div class="roommate <?php if ($counter == 1) echo "visible"; ?>"
                 data-id="<?php echo $roommate->user_id_pk; ?>" data-count="<?php echo $counter ?>">
                <div class="">
                    <?php
                    //                    echo $currentUserId . ", " . $roommate->user1_id_fk . ":" . $roommate->user2_id_fk . "," . $roommate->answer;
                    if (isset($roommate->answer) && $roommate->answer == 0 && $roommate->user2_id_fk == $currentUserId) {
                        ?>
                        <a href="/user_api/acceptRoommate" class="ajax">
                            <div class="icon-like"><?php echo lang("accept"); ?></div>
                        </a>
                        <a href="/user_api/rejectRoommate" class="ajax">
                            <div class="icon-dislike"><?php echo lang("reject"); ?></div>
                        </a>
                        <div class="wants-you"><?php echo lang("wants_you"); ?></div>
                    <?php
                    } else {
                        ?>
                        <a href="/user_api/likeRoommate" class="ajax">
                            <div class="icon-like"><?php echo lang("sendrequest"); ?></div>
                        </a>
                        <a href="/user_api/dislikeRoommate" class="ajax">
                            <div class="icon-dislike"><?php echo lang("reject"); ?></div>
                        </a>
                    <?php
                    } ?>

                </div>

                <div class="roommate-selection">
                    <?php
                    $data["roommate"] = $roommate;
                    $this->load->view("components/profile", $data);
                    ?>
                </div>
            </div>
        <?php
        endforeach;
    }
    ?>
</div>