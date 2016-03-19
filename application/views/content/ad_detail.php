<?php
if (!empty($adDetail)) {
    ?>
    <div id="ad-detail" data-ad-id="<?php echo $adDetail->ad_id_pk ?>" class="right-window shadow-right scrollable">
        <?php $this->load->view("components/button_close.php");


        ?>
        <h3><?php echo $adDetail->ad_title; ?></h3>
        <section class="ad-detail-header">
            <div class="ad-image-wrapper">
                <?php foreach ($gallery as $image): ?>
                    <div class="ad-detail-image">
                        <a href="/galleries/<?php echo $adDetail->object_id_pk . '/' . $image; ?>"
                           rel="prettyPhoto[gallery-<?php echo $adDetail->object_id_pk; ?>]">
                            <img src="/galleries/<?php echo $adDetail->object_id_pk . '/_thumbs/' . $image; ?>"/>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="ad-detail-box">
                <div class="ad-detail-price">
                    Cena <?php echo $adDetail->ad_price; ?>/měs.
                </div>
                <div class="ad-detail-bail">
                </div>
                <?php if (isset($currentUserId) && $currentUserId != $adDetail->user_id_fk) { ?>
                    <div class="ad-detail-contact button-wide-light">
                        <?php echo lang("ad_contact"); ?>
                    </div>
                <?php } ?>
            </div>
            <div class="ad-state">
                <div class="ad-type">
                    <?php
                    switch ($adDetail->ad_type) {
                        case 0:
                            echo "Celý byt/dům";
                            break;
                        case 1:
                            echo "Samostatný pokoj";
                            break;
                        case 2:
                            echo "Sdílený pokoj";
                            break;
                    }?>
                </div>
                <div class="max-people-count">
                    <?php if (isset($adDetail->max_people_count)) {
                        echo "Volných míst";
                        echo "<span class=max-people-count-value>" . $adDetail->max_people_count . "</span>";
                    } ?>
                </div>
                <div class="max-people-count">
                    <?php if (isset($adDetail->people_present)) {
                        echo "Obsazené místo";
                        echo $adDetail->people_present;
                    } ?>
                </div>
            </div>
        </section>

        <div class="icons">
            <?php if (!$isFollowed) { ?>
                <div class="icon-medium icon-follow">
                    <a href="user_c/follow/<?php echo $adDetail->ad_id_pk; ?>" class="ajax">
                    </a>
                </div>
            <?php
            } else {
                ?>
                <div class="icon-medium icon-unfollow">
                    <a href="user_c/unfollow/<?php echo $adDetail->ad_id_pk; ?>" class="ajax">
                    </a>
                </div>
            <?php } ?>
        </div>
        <section class="ad-detail-info-top">
            <div class="info-description-location">
            <span class="description-location-postaltown big-text">
                <?php echo $adDetail->postal_town; ?>,
            </span>
            <span class="description-location-route big-text">
                <?php echo $adDetail->route; ?>
            </span>
            </div>
        </section>
        <section class="ad-detail-info">

            <div class="ad-detail-created">
                <?php echo lang("inserted") . ": " . $formatTime; ?>
            </div>
            <table>
                <tr>
                    <td class="header"><?php echo "Rozloha: "; ?></td>
                    <td class="value"><?php if (isset($adDetail->square_area)) {
                            echo $adDetail->square_area . "m<sup>2</sup>";
                        } ?></td>
                </tr>
                <tr>
                    <td class="header"> <?php echo "Průchozí pokoj: "; ?></td>
                    <td class="value"> <?php if (isset($adDetail->walkthrough)) {

                            if ($adDetail->walkthrough == 0) {
                                echo "ne";
                            }
                            if ($adDetail->walkthrough == 1) {
                                echo "ano";
                            }
                        } ?></td>
                </tr>
                <tr>
                    <td class="header"><?php echo "Kauce:"; ?></td>
                    <td class="value">  <?php if (isset($adDetail->bail)) {
                            echo $adDetail->bail;
                        } ?></td>

                </tr>
            </table>
            <table>
                <tr>
                    <td class="header"><?php echo "Určeno pro: "; ?></td>
                    <td class="value"> <?php if (isset($adDetail->sex)) {
                            switch ($adDetail->sex) {
                                case 0:
                                    echo "muže i ženy";
                                    break;
                                case 1:
                                    echo "muže";
                                    break;
                                case 2:
                                    echo "ženy";
                                    break;
                            }
                        } ?></td>

                </tr>
                <td class="header"><?php echo "Počet místností:"; ?></td>
                <td class="value">    <?php if (isset($adDetail->room_count)) {
                        echo $adDetail->room_count . " " . lang("room_count");
                    } ?></td>
                <tr>

                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>

            </table>

            <!---->
            <!--    --><?php //if (isset($adDetail->age_from)) {
            //        echo $adDetail->age_from;
            //    }
            ?>
            <!---->
            <!--    --><?php //if (isset($adDetail->age_to)) {
            //        echo $adDetail->age_to;
            //    }
            ?>

        </section>

        <section class="">
            <h3>
                <?php echo lang("description_text"); ?>
            </h3>

            <div>
                <?php if (isset($adDetail->ad_body)) {
                    echo $adDetail->ad_body;
                } ?>
            </div>
        </section>
        <section class="">
            <h3>
                <?php echo lang("equipment_text"); ?>
            </h3>

            <div class="ad-equipment-wrapper">
                <?php if (isset($ad_equipment)) {
                    foreach ($ad_equipment as $equipment)
                        echo "<div class=ad-equipment>" . $equipment->equipment_name . "</div>";
                } ?>
            </div>
        </section>
        <section class="">

        </section>

        <div class="ad-roommates"></div>
        <!--    --><?php //var_dump($adDetail); ?>
    </div>
<?php } ?>