<?php $data = Array();
?>
<div id="object-new" class="shadow-right scrollable right-window"
    <?php if (isset($adDetail)) {
        echo "data-objectid=" . $adDetail->object_id_fk;
        echo " data-adid=" . $adDetail->ad_id_pk;
    } ?>>
    <?php $this->load->view("components/button_close.php"); ?>
    <h2> <?php if (!isset($adDetail)) {
            echo lang("object_new_header");
        } else {
            echo lang("object_edit_header");
        } ?></h2>

    <div class="subheader">
        <?php if (!isset($adDetail)) {
            echo lang("object_new_subheader");
        } else {
            echo lang("object_edit_subheader");
        } ?>
    </div>

    <div class="object-new-menu">
        <ul>
            <?php
            //          if adDetail is loaded then we are in edit mode
            if (!isset($adDetail)) {
                ?>
                <li class="active" data-id="1"><?php echo lang('object-new-type'); ?>
                    <div></div>
                </li>
            <?php
            } else {
                $data["adDetail"] = $adDetail;
                $data["ad_equipment"] = $ad_equipment;
                $data["ad_gallery"] = $ad_gallery;
            } ?>
            <li data-id="2"><?php echo lang('object-new-ad'); ?>
                <div></div>
            </li>
            <li data-id="3"><?php echo lang('object-new-price'); ?>
                <div></div>
            </li>
            <li data-id="4"><?php echo lang('object-new-time'); ?>
                <div></div>
            </li>
            <li data-id="5"><?php echo lang('object-new-equipment'); ?>
                <div></div>
            </li>
            <li data-id="6"><?php echo lang('object-new-photo'); ?>
                <div></div>
            </li>
        </ul>
    </div>
    <div class="object-new-content">
        <?php
        if (!isset($adDetail)) {
            $this->load->view('content/objects_form/objects_form_type');
        }
        $data["equipment"] = $equipment;
        $this->load->view('content/objects_form/objects_form_ad', $data);
        $this->load->view('content/objects_form/objects_form_price', $data);
        $this->load->view('content/objects_form/objects_form_time', $data);
        $this->load->view('content/objects_form/objects_form_equipment', $data);
        $this->load->view('content/objects_form/objects_form_photos', $data);
        //        $this->load->view('content/objects_form/objects_form_rooms');

        ?>
    </div>
</div>
