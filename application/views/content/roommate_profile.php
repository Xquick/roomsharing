<div id="roommate" class="right-window">
    <div class="loader"></div>
    <?php $this->load->view("components/button_close.php");

    if (isset($roommate) && !empty($roommate)) {
    $roommate = $roommate[0];
    ?>

    <div class="roommate visible" data-id="<?php echo $roommate->user_id_pk; ?>">
        <div class="roommate-selection">
            <?php
            $data["roommate"] = $roommate;
            $this->load->view("components/profile", $data);
            ?>
        </div>

        <div class="roommate-info-about">
            <div class="info-item-header"><?php echo lang("about_me"); ?></div>
            <div class="info-item-content"> <?php echo $roommate->about_me ?></div>
        </div>
        <div class="">
            <?php
            foreach ($adsIncommon as $item):
                $data['post'] = $item;
                $data['isFollowed'] = 1;
                $this->load->view('content/post', $data);
            endforeach;
            ?>
        </div>
    </div>
</div>
<?php
}
?>
</div>