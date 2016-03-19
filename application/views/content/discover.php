<?php
$data['header'] = lang("discover_header");
$data['subheader'] = lang("discover_subheader");
$this->load->view("components/section_header", $data);
?>

<div id="filter-box">
    <?php $this->load->view('content/filter'); ?>
</div>

<div id="discover">
    <div class="loader"></div>
    <div id="discover-objects">
        <?php $this->load->view('content/discover_objects'); ?>
    </div>
</div>

