<div class="availability-state"></div>
<div class="user-name left">
    <?php echo $roommate->firstname . " " .
        $roommate->lastname . ", " .
        $roommate->age . " " . lang("yo");
    if ($roommate->student == 1) {
        echo " (" . lang("student") . ")";
    }
    ?>
</div>
<div class="img-wrapper">
    <?php
    $data['fb_id'] = $roommate->fb_id;
    $data['user_id'] = $roommate->user_id_pk;
    $this->load->view('content/user_miniature', $data);
    ?>

    <?php if (isset($currentUserId) && isset($isRoommate) && $currentUserId && $isRoommate) { ?>
        <div class="roommate-contact button-wide">
            <?php echo lang("roommate_contact"); ?>
        </div>
    <?php } ?>
</div>
<div class="roommate-info">
    <div class="roommate-info-item roommate-info-gender">
        <div class="info-item-header"><?php echo lang("gender"); ?></div>
        <div class="info-item-content"><?php echo lang($roommate->gender); ?></div>
    </div>

    <div class="roommate-info-item roommate-info-gender">
        <div class="info-item-header"><?php echo lang("smoker"); ?></div>
        <div class="info-item-content">
            <?switch ($roommate->smoker) {
                case 1:
                    echo lang("smoker_1");
                    break;
                case 2:
                    echo lang("smoker_2");
                    break;
                case 3:
                    echo lang("smoker_3");
                    break;
                default:
                    lang("empty");
            } ?>
        </div>
    </div>

    <div class="roommate-info-item roommate-info-alcohol">
        <div class="info-item-header"><?php echo lang("alcohol"); ?></div>
        <div class="info-item-content">
            <? switch ($roommate->alcohol) {
                case 1:
                    echo lang("alcohol_1");
                    break;
                case 2:
                    echo lang("alcohol_2");
                    break;
                case 3:
                    echo lang("alcohol_3");
                    break;
                case 4:
                    echo lang("alcohol_4");
                    break;
                default:
                    lang("empty");
            } ?>
        </div>
    </div>

    <div class="roommate-info-item roommate-info-family-state">
        <div class="info-item-header"><?php echo lang("family_state"); ?></div>
        <div class="info-item-content">
            <?php switch ($roommate->family_state) {
                case 1:
                    echo lang("family_state_1");
                    break;
                case 2:
                    echo lang("family_state_2");
                    break;
                case 3:
                    if ($roommate->gender == "male")
                        echo lang("family_state_3m");
                    if ($roommate->gender == "female")
                        echo lang("family_state_3f");
                    break;
                default:
                    lang("empty");
            }   ?>
        </div>
    </div>

    <!--                        <div class="roommate-info-item roommate-info-orientation">--><?php //echo lang("orientation");
//                            echo $roommate->orientation ?><!--</div>-->

    <div class="roommate-info-item roommate-info-languages">
        <div class="info-item-header"><?php echo lang("languages"); ?></div>
        <div class="info-item-content">
            <?php  foreach ($roommate->languages as $language)
                echo "<div class=job-item>" . $language->language_name . "</div>" ?>
        </div>
    </div>

    <div class="roommate-info-item roommate-info-education">
        <div class="info-item-header"><?php echo lang("education"); ?></div>
        <div class="info-item-content">
            <?php foreach ($roommate->education as $education)
                echo "<div class=job-item>" . $education->education_name . "</div>" ?></div>

    </div>

    <div class="roommate-info-item roommate-info-job">
        <div class="info-item-header"><?php echo lang("job"); ?></div>
        <div class="info-item-content">
            <?php foreach ($roommate->job as $job)
                echo "<div class=job-item>" . $job->job_name . "</div>"?>
        </div>
    </div>
</div>