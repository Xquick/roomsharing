<div id="object" class="shadow-right scrollable right-window" data-objectid="<?php echo $objectId; ?>"
     data-adId="<?php echo $adId ?>">
    <?php $this->load->view("components/button_close.php"); ?>
    <h2><?php echo lang("object_edit_header"); ?></h2>

    <div class="subheader"><?php echo lang("object_edit_subheader"); ?></div>

    <div class="object-new-menu">
        <ul>
            <li class="active" data-id="1"><?php echo lang('object-new-type'); ?>
                <div></div>
            </li>
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
        $this->load->view('content/objects_form/objects_form_ad', array("id" => "2"));
        $this->load->view('content/objects_form/objects_form_price', array("id" => "3"));
        $this->load->view('content/objects_form/objects_form_time', array("id" => "4"));
        $this->load->view('content/objects_form/objects_form_equipment', array("id" => "5"));
        $this->load->view('content/objects_form/objects_form_photos', array("id" => "6"));
        ?>
    </div>
</div>
